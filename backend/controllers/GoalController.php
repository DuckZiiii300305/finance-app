<?php
require_once __DIR__ . '/../repositories/GoalRepository.php';
require_once __DIR__ . '/../services/GoalService.php';
require_once __DIR__ . '/../config/database.php';

class GoalController {

    private $service;

    public function __construct($pdo) {
        $this->service = new GoalService($pdo);
    }

    public function index() {
        echo json_encode($this->service->getAll());
    }

    public function show($id) {
        echo json_encode($this->service->getById($id));
    }

    public function store($data)
    {
        $id = $this->service->create($data);

        echo json_encode([
            "id" => $id,
            "message" => "Goal created"
        ]);
    }

    public function update($id, $data) {
        $this->service->update($id, $data);
        echo json_encode(["message" => "Goal updated"]);
    }

    public function destroy($id) {
        $this->service->delete($id);
        echo json_encode(["message" => "Goal deleted"]);
    }
    public function save($goalId, $data)
    {
        $this->service->saveToGoal($goalId, $data['amount']);

        echo json_encode([
            "message" => "Saved to goal"
        ]);
    }
}