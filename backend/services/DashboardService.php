<?php

class DashboardService
{
    private $incomeService;
    private $incomeRepo;
    private $expenseRepo;
    private $goalService;
    private $walletService;

    public function __construct(
        $incomeService,
        $incomeRepo,
        $expenseRepo,
        $goalService,
        $walletService
    ) {
        $this->incomeService = $incomeService;
        $this->incomeRepo = $incomeRepo;
        $this->expenseRepo = $expenseRepo;
        $this->goalService = $goalService;
        $this->walletService = $walletService;
    }

    public function getDashboard()
    {
        // 1. xử lý recurring income
        $this->incomeService->processRecurring();

        // 2. xử lý goal auto saving
        $this->goalService->processDailyGoals();

        // 3. lấy summary
        $summary = $this->walletService->getSummary();

        $incomes = $this->incomeRepo->getAll();
        $expenses = $this->expenseRepo->getAll();
        $goals = $this->goalService->getAll();

        // Chuẩn hóa kiểu dữ liệu số
        $incomeTotal = (float)$summary['income_total'];
        $expenseTotal = (float)$summary['expense_total'];
        $goalSavingTotal = (float)$summary['goal_saving_total'];
        $balance = (float)$summary['balance'];

        // Merge transactions
        $transactions = [];

        foreach ($incomes as $income) {
            $transactions[] = [
                "id" => $income['id'],
                "type" => "income",
                "amount" => (float)$income['amount'],
                "category" => $income['category'],
                "note" => $income['note'],
                "date" => $income['income_date'],
                "created_at" => $income['created_at']
            ];
        }

        foreach ($expenses as $expense) {
            $transactions[] = [
                "id" => $expense['id'],
                "type" => "expense",
                "amount" => (float)$expense['amount'],
                "category" => $expense['category'],
                "note" => $expense['note'],
                "date" => $expense['expense_date'],
                "created_at" => $expense['created_at']
            ];
        }

        // sort theo created_at DESC
        usort($transactions, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return [
            "date" => date("Y-m-d"),
            "balance" => $balance,
            "income_total" => $incomeTotal,
            "expense_total" => $expenseTotal,
            "goal_saving_total" => $goalSavingTotal,
            "transactions" => $transactions,
            "goals" => $goals
        ];
    }
}