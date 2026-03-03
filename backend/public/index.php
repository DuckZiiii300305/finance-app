<?php

// ==========================
// CORS (PHẢI ĐẶT TRƯỚC MỌI OUTPUT)
// ==========================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");

// Handle preflight IMMEDIATELY
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

// ==========================
// Load system
// ==========================
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/../routes/api.php";