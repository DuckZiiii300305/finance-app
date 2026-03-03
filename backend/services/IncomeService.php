<?php
require_once __DIR__ . '/../repositories/IncomeRepository.php';

class IncomeService {

    private $repository;

    public function __construct($pdo) {
        $this->repository = new IncomeRepository($pdo);
    }

    public function getByDate($date) {
        return $this->repository->getByDate($date);
    }
    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getTotal() {
        return $this->repository->getTotal();
    }

    public function create($data) {
        return $this->repository->create($data);
    }

    public function update($id, $data) {
        return $this->repository->update($id, $data);
    }

    public function delete($id) {
        return $this->repository->delete($id);
    }
    public function processRecurring()
    {
        $recurringIncomes = $this->repository->getRecurring();

        $today = date('Y-m-d');
        $day = date('d');

        foreach ($recurringIncomes as $income) {

            if ($income['recurring_type'] === 'monthly') {

                if ($income['recurring_day'] != $day) {
                    continue;
                }

                // kiểm tra đã tạo hôm nay chưa
                $exists = $this->repository->existsByDateAndCategory(
                    $today,
                    $income['category'],
                    $income['amount']
                );

                if ($exists) continue;

                $this->repository->create([
                    'amount' => $income['amount'],
                    'category' => $income['category'],
                    'note' => $income['note'],
                    'income_date' => $today,
                    'is_recurring' => 0
                ]);
            }
        }
    }
}