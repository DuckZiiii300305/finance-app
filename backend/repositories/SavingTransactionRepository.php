<?php

class SavingTransactionRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($goalId, $amount, $date)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO saving_transactions (goal_id, amount, transaction_date)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([$goalId, $amount, $date]);
    }

    public function getLastTransactionDate($goalId)
    {
        $stmt = $this->pdo->prepare("
            SELECT MAX(transaction_date) AS last_date
            FROM saving_transactions
            WHERE goal_id = ?
        ");

        $stmt->execute([$goalId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['last_date'] ?? null;
    }
}