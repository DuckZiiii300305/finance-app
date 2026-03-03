<?php

class WalletService
{
    private $incomeRepo;
    private $expenseRepo;
    private $goalTransferRepo; // thêm repo này

    public function __construct($incomeRepo, $expenseRepo, $goalTransferRepo)
    {
        $this->incomeRepo = $incomeRepo;
        $this->expenseRepo = $expenseRepo;
        $this->goalTransferRepo = $goalTransferRepo;
    }

    public function getSummary()
    {
        $incomeTotal = $this->incomeRepo->getTotal();
        $expenseTotal = $this->expenseRepo->getTotal();

        return [
            "income_total" => $incomeTotal,
            "expense_total" => $expenseTotal,
            "goal_saving_total" => 0, // không ảnh hưởng balance nữa
            "balance" => $incomeTotal - $expenseTotal
        ];
    }
}