<?php

class GoalRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM saving_goals ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO saving_goals
                (user_id, name, target_amount, current_amount, start_date, end_date, auto_daily_amount, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            USER_ID,
            $data['name'],
            $data['target_amount'],
            0,
            $data['start_date'],
            $data['end_date'],
            $data['auto_daily_amount'],
            $data['status']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE saving_goals 
                SET name=?, target_amount=?, start_date=?, end_date=?, auto_daily_amount=?, status=?
                WHERE id=?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $data['name'],
            $data['target_amount'],
            $data['start_date'],
            $data['end_date'],
            $data['auto_daily_amount'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM saving_goals WHERE id=?");
        return $stmt->execute([$id]);

    }
    public function increaseCurrentAmount($id, $amount)
    {
        $stmt = $this->pdo->prepare("
            UPDATE saving_goals
            SET current_amount = current_amount + ?
            WHERE id = ?
        ");

        $stmt->execute([$amount, $id]);
    }
    public function markCompleted($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE saving_goals
            SET status = 'completed'
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }
    public function find($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM saving_goals WHERE id = ?
        ");

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getTotalCurrentAmount()
    {
        $sql = "SELECT SUM(current_amount) as total FROM saving_goals";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }
}