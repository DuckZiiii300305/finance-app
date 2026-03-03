<?php
require_once __DIR__ . '/../services/IncomeService.php';

class IncomeController {

    private $service;

    public function __construct($pdo) {
        $this->service = new IncomeService($pdo);
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
        echo json_encode(["message" => "Income created"]);
    }

    public function update($id, $data) {
        $this->service->update($id, $data);
        echo json_encode(["message" => "Income updated"]);
    }

    public function destroy($id) {
        $this->service->delete($id);
        echo json_encode(["message" => "Income deleted"]);
    }
}