<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getControllersRoutes()['accessControl']);

$controller = new AccessControlController();

try {
    // Verifica se a requisição é do tipo POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);

        switch ($action) {
            case 'getUsers':
                $response = $controller->getUsers();
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'getUserById':
                $response = $controller->getUserById();
                if (!$response) {
                    http_response_code(404);
                    header("Content-Type: application/json");
                    echo json_encode('User not found');
                    return;
                }
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'check':
                $userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);
                $response = $controller->getUserById($userId);
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'editUser':
                $response = $controller->editUser();
                if (!$response) {
                    http_response_code(204);
                    header("Content-Type: application/json");
                    echo json_encode('No content');
                    return;
                }
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'getUserPermissions':
                $response = $controller->getUserPermissions();
                if (!$response) {
                    http_response_code(403);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'getPermissionsByUser':
                $response = $controller->getPermissionsByUser();
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'editUserPermissions':
                $response = $controller->editUserPermissions();
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            default:
                // Ação inválida
                http_response_code(404);
                header("Content-Type: application/json");
                echo json_encode('Not found');
                break;
        }
    } else {
        // Requisição inválida
        http_response_code(405);
        header("Content-Type: application/json");
        echo json_encode('Method not authorized');
    }
} catch (\Throwable $th) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo $th->getMessage();
}
