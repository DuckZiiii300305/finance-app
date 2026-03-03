<?php
require_once __DIR__ . '/../repositories/SavingTransactionRepository.php';
require_once __DIR__ . '/../repositories/GoalRepository.php';
require_once __DIR__ . '/../repositories/ExpenseRepository.php';
require_once __DIR__ . '/../config/database.php';
class GoalService
{
    private $goalRepo;
    private $transactionRepo;
    private $expenseRepo;

    public function __construct($pdo)
    {
        $this->goalRepo = new GoalRepository($pdo);
        $this->transactionRepo = new SavingTransactionRepository($pdo);
        $this->expenseRepo = new ExpenseRepository($pdo);
    }

    public function create($data)
    {
        $days = (strtotime($data['end_date']) - strtotime($data['start_date'])) / 86400 + 1;
        $days = max($days, 1);

        $data['auto_daily_amount'] = $data['target_amount'] / $days;
        $data['current_amount'] = 0;
        $data['status'] = 'active';

        return $this->goalRepo->create($data);
    }

    public function processDailyGoals()
    {
        $goals = $this->goalRepo->getAll();
        $today = strtotime(date('Y-m-d'));

        foreach ($goals as $goal) {

            if ($goal['status'] !== 'active') continue;

            $startDate = strtotime($goal['start_date']);
            if ($startDate > $today) continue;

            $lastDate = $this->transactionRepo
                             ->getLastTransactionDate($goal['id']);

            // nếu chưa có transaction thì bắt đầu từ start_date
            $current = $lastDate
                ? strtotime($lastDate)
                : $startDate;

            // bắt đầu từ ngày tiếp theo
            $current = strtotime('+1 day', $current);

            while ($current <= $today) {

                if ($goal['current_amount'] >= $goal['target_amount']) {
                    $this->goalRepo->markCompleted($goal['id']);
                    break;
                }

                $remaining = $goal['target_amount'] - $goal['current_amount'];
                $amount = min($goal['auto_daily_amount'], $remaining);

                $date = date('Y-m-d', $current);

                $this->transactionRepo->create(
                    $goal['id'],
                    $amount,
                    $date
                );

                $this->goalRepo->increaseCurrentAmount(
                    $goal['id'],
                    $amount
                );

                $goal['current_amount'] += $amount;

                $current = strtotime('+1 day', $current);
            }
        }
    }

    public function getProgress($goal)
    {
        if ($goal['target_amount'] == 0) return 0;

        return round(
            ($goal['current_amount'] / $goal['target_amount']) * 100,
            2
        );
    }

    public function getAll()
    {
        $goals = $this->goalRepo->getAll();

        foreach ($goals as &$goal) {
            $goal['progress'] = $this->getProgress($goal);
        }

        return $goals;
    }

    public function delete($id)
    {
        return $this->goalRepo->delete($id);
    }
    public function getTotalCurrentAmount()
    {
        return $this->goalRepo->getTotalCurrentAmount();
    }
    public function saveToGoal($goalId, $amount)
    {
        $this->expenseRepo->create([
            "amount" => $amount,
            "category" => "Goal Saving",
            "note" => "Saving to goal #" . $goalId,
            "expense_date" => date('Y-m-d'),
            "goal_id" => $goalId
        ]);

        $this->goalRepo->increaseCurrentAmount($goalId, $amount);
    }
    public function removeSaving($expenseId, $goalId, $amount)
    {
        // 1. Xóa expense
        $this->expenseRepo->delete($expenseId);

        // 2. Trừ goal
        $this->goalRepo->decreaseCurrentAmount($goalId, $amount);
    }
}