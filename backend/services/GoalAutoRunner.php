<?php
class GoalAutoRunner {

    private $goalRepo;
    private $savingTransactionRepo;

    public function runDaily() {
        $goals = $this->goalRepo->getActiveGoals();

        foreach ($goals as $goal) {

            $saved = $this->savingTransactionRepo->getTotalByGoal($goal['id']);

            if ($saved >= $goal['target_amount']) {
                $this->goalRepo->markCompleted($goal['id']);
                continue;
            }

            $dailyAmount = $goal['daily_amount'];

            $remaining = $goal['target_amount'] - $saved;

            $amountToSave = min($dailyAmount, $remaining);

            $this->savingTransactionRepo->create([
                "goal_id" => $goal['id'],
                "amount" => $amountToSave,
                "transaction_date" => date("Y-m-d")
            ]);
        }
    }
}