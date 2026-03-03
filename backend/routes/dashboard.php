<?php
$user_id = $_GET['user_id'];

$income = $conn->query("SELECT SUM(amount) as total FROM incomes WHERE user_id = $user_id")->fetch();
$expense = $conn->query("SELECT SUM(amount) as total FROM expenses WHERE user_id = $user_id")->fetch();
$saving = $conn->query("SELECT SUM(current_amount) as total FROM saving_goals WHERE user_id = $user_id")->fetch();

$wallet = ($income['total'] ?? 0) - ($expense['total'] ?? 0) - ($saving['total'] ?? 0);

echo json_encode([
    "wallet"=>$wallet,
    "total_income"=>$income['total'] ?? 0,
    "total_expense"=>$expense['total'] ?? 0
]);