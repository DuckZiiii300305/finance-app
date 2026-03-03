<?php

class IncomeRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo
            ->query("SELECT * FROM incomes ORDER BY income_date DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal()
    {
        return $this->pdo
            ->query("SELECT SUM(amount) as total FROM incomes")
            ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO incomes 
            (user_id, amount, category, note, income_date, is_recurring, recurring_type, recurring_day, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        $stmt->execute([
            USER_ID,
            $data['amount'],
            $data['category'],
            $data['note'] ?? null,
            $data['income_date'],
            $data['is_recurring'] ?? 0,
            $data['recurring_type'] ?? null,
            $data['recurring_day'] ?? null
        ]);
    }
    public function getRecurring()
    {
        $stmt = $this->pdo->query("
            SELECT * FROM incomes
            WHERE is_recurring = 1
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function existsByDateAndCategory($date, $category, $amount)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM incomes
            WHERE income_date = ?
            AND category = ?
            AND amount = ?
            AND is_recurring = 0
        ");

        $stmt->execute([$date, $category, $amount]);

        return $stmt->fetchColumn() > 0;
    }
    public function update($id, $data)
    {
        $sql = "UPDATE incomes 
                SET amount=?, category=?, note=?, income_date=?, is_recurring=?, recurring_type=?, recurring_day=?
                WHERE id=?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $data['amount'],
            $data['category'],
            $data['note'] ?? null,
            $data['income_date'],
            $data['is_recurring'] ?? 0,
            $data['recurring_type'] ?? null,
            $data['recurring_day'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM incomes WHERE id=?");
        return $stmt->execute([$id]);
    }
}