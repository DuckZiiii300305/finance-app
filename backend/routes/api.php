<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/IncomeController.php';
require_once __DIR__ . '/../controllers/ExpenseController.php';
require_once __DIR__ . '/../controllers/GoalController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../config/constants.php';



$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| Fix REQUEST_URI for built-in server
|--------------------------------------------------------------------------
*/

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));

/*
|--------------------------------------------------------------------------
| Resource detection
|--------------------------------------------------------------------------
*/

$resource = $segments[0] ?? null;
$id = $segments[1] ?? null;
$action   = $segments[2] ?? null;
$pdo = getPDO();
switch ($resource) {

    case 'incomes':
        
        $controller = new IncomeController($pdo);
        break;

    case 'expenses':
        $controller = new ExpenseController($pdo);
        break;

    case 'goals':
        $controller = new GoalController($pdo);
        break;

    case 'dashboard':
        $controller = new DashboardController($pdo);
        break;

    case null:
        echo json_encode(["message" => "Finance API Running"]);
        exit;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found"]);
        exit;
}

/*
|--------------------------------------------------------------------------
| Method routing
|--------------------------------------------------------------------------
*/

switch ($method) {

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */
    case 'GET':

        if ($resource === 'dashboard') {
            $controller->index();
            break;
        }

        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }

        break;


    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */
    case 'POST':

        $data = json_decode(file_get_contents("php://input"), true);

        /*
        |--------------------------------------------------------------
        | Special route: POST /goals/{id}/save
        |--------------------------------------------------------------
        */
        if ($resource === 'goals' && $id && $action === 'save') {

            if (!isset($data['amount'])) {
                http_response_code(400);
                echo json_encode(["error" => "Amount is required"]);
                exit;
            }

            $controller->save($id, $data);
            break;
        }

        /*
        |--------------------------------------------------------------
        | Normal create: POST /resource
        |--------------------------------------------------------------
        */
        if ($id) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid POST endpoint"]);
            exit;
        }

        $controller->store($data);
        break;


    /*
    |--------------------------------------------------------------------------
    | PUT
    |--------------------------------------------------------------------------
    */
    case 'PUT':

        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID required"]);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $controller->update($id, $data);
        break;


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    case 'DELETE':

        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID required"]);
            exit;
        }

        $controller->destroy($id);
        break;
    case 'OPTIONS':
        http_response_code(200);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}