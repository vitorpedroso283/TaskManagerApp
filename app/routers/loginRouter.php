<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getControllersRoutes()['login']);

$controller = new LoginController();

try {
    // Verifica se a requisição é do tipo POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
        switch ($action) {
            case 'login':
                $response = $controller->login();
                if (!$response) {
                    //ação não autorizada
                    http_response_code(401);
                    header("Content-Type: application/json");
                    echo json_encode('NOT AUTHORIZED');
                    return;
                }
                //ação correta
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'register':
                $response = $controller->register();
                if (!$response['success']) {
                    // Erro no registro do usuário
                    http_response_code(400);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                // Registro bem-sucedido
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'checkLogin':
                $controller->checkLogin();
                break;
            default:
                // Ação inválida
                http_response_code(404);
                header("Content-Type: application/json");
                echo json_encode('NOT FOUND');
                break;
        }
    } else {
        // Requisição inválida
        http_response_code(405);
        header("Content-Type: application/json");
        echo json_encode('METHOD NOT AUTHORIZED.');
    }
} catch (\Throwable $th) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode('INTERNAL SERVER ERROR.');
}
