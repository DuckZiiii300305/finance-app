<?php

class ExpenseRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo
            ->query("SELECT * FROM expenses ORDER BY expense_date DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal()
    {
        $result = $this->pdo
            ->query("SELECT SUM(amount) as total FROM expenses")
            ->fetch(PDO::FETCH_ASSOC);

        return $result['total'] !== null ? (float)$result['total'] : 0;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO expenses 
            (user_id, amount, category, note, expense_date, goal_id, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );

        $stmt->execute([
            USER_ID,
            $data['amount'],
            $data['category'],
            $data['note'] ?? null,
            $data['expense_date'],
            $data['goal_id'] ?? null
        ]);
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM expenses WHERE id=?");
        return $stmt->execute([$id]);
    }
}