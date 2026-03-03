<?php
require_once __DIR__ . '/../repositories/ExpenseRepository.php';

class ExpenseService {

    private $repository;

    public function __construct($pdo) {
        $this->repository = new ExpenseRepository($pdo);
    }

    public function getByDate($date) {
        return $this->repository->getByDate($date);
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
    public function getAll()
    {
        return $this->repository->getAll();
    }
}