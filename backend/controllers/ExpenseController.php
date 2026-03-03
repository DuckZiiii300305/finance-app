<?php
require_once __DIR__ . '/../services/ExpenseService.php';

class ExpenseController {

    private $service;

    public function __construct($pdo) {
        $this->service = new ExpenseService($pdo);
    }

    public function index() {
        echo json_encode($this->service->getAll());
    }

    public function show($id) {
        echo json_encode($this->service->getById($id));
    }

    public function store($data) {
        $this->service->create($data);
        http_response_code(201);
        echo json_encode(["message" => "Expense created"]);
    }

    public function update($id, $data) {
        $this->service->update($id, $data);
        echo json_encode(["message" => "Expense updated"]);
    }

    public function destroy($id) {
        $this->service->delete($id);
        echo json_encode(["message" => "Expense deleted"]);
    }
}