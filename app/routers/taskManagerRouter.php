<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getControllersRoutes()['taskManager']);

$controller = new TaskManagerController();

try {
    // Verifica se a requisição é do tipo POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
        switch ($action) {
            case 'getTasks':
                $response = $controller->getTasks();
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'getPendingTasks':
                $response = $controller->getTasks();
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'createTask':
                $response = $controller->createTask();
                if (!$response['success']) {
                    // Erro ao criar a tarefa
                    http_response_code(400);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                // Tarefa criada com sucesso
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'editTask':
                $response = $controller->editTask();
                if (!$response['success']) {
                    // Erro ao editar a tarefa
                    http_response_code(400);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                // Tarefa editada com sucesso
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'deleteTask':
                $response = $controller->deleteTask();
                if (!$response['success']) {
                    // Erro ao excluir a tarefa
                    http_response_code(400);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                // Tarefa excluída com sucesso
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'viewTask':
                $response = $controller->viewTask();
                if (!$response['success']) {
                    // Erro ao visualizar a tarefa
                    http_response_code(400);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                // Tarefa visualizada com sucesso
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
                break;
            case 'updateTaskStatus':
                $response = $controller->updateTaskStatus();
                if (!$response['success']) {
                    // Erro ao atualizar o status da tarefa
                    http_response_code(400);
                    header("Content-Type: application/json");
                    echo json_encode($response);
                    return;
                }
                // Status da tarefa atualizado com sucesso
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode($response);
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
    echo $th->getMessage();
}
