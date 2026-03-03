<?php
require_once __DIR__ . '/../services/IncomeService.php';
require_once __DIR__ . '/../services/ExpenseService.php';
require_once __DIR__ . '/../services/GoalService.php';
require_once __DIR__ . '/../services/WalletService.php';
require_once __DIR__ . '/../services/DashboardService.php';
class DashboardController
{
    private $service;

    public function __construct($pdo)
    {
        $incomeRepo = new IncomeRepository($pdo);
        $expenseRepo = new ExpenseRepository($pdo);

        $incomeService = new IncomeService($pdo);
        $goalService = new GoalService($pdo);   // ✅ sửa tại đây

        $walletService = new WalletService(
            $incomeRepo,
            $expenseRepo,
            $goalService
        );

        $this->service = new DashboardService(
            $incomeService,
            $incomeRepo,
            $expenseRepo,
            $goalService,
            $walletService
        );
    }

    public function index()
    {
        echo json_encode($this->service->getDashboard());
    }
}