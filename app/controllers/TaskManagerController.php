<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getModelsRoutes()['taskManager']);
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getControllersRoutes()['accessControl']);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class TaskManagerController
{
    private $accessControlController;

    public function __construct()
    {
        $this->accessControlController = new AccessControlController();
    }

    // Método para obter todas as tarefas
    public function getTasks()
    {
        $model = new TaskManagerModel();

        if (!$this->accessControlController->checkPermission('R')) {
            return ['success' => false, 'message' => 'Permissão negada para visualizar tarefas'];
        }

        $callGetTask = filter_input(INPUT_POST, 'callGetTasks', FILTER_DEFAULT);

        if ($callGetTask === 'all') {
            $tasks = $model->getTasks();
        } elseif ($callGetTask === 'pending') {
            $tasks = $model->getPendingTasks();
        }


        return $tasks;
    }


    // Método para criar uma nova tarefa
    public function createTask()
    {
        $model = new TaskManagerModel();

        if (!$this->accessControlController->checkPermission('C')) {
            return ['success' => false, 'message' => 'Permissão negada para criar tarefas'];
        }

        $data['name'] = strtoupper(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
        $data['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['user_id'] = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);

        $response = $model->createTask($data);
        return $response;
    }

    // Método para editar uma tarefa existente
    public function editTask()
    {
        $model = new TaskManagerModel();

        if (!$this->accessControlController->checkPermission('U')) {
            return ['success' => false, 'message' => 'Permissão negada para editar tarefas'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $data['name'] = strtoupper(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
        $data['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);

        $response = $model->editTask($data);
        return $response;
    }

    // Método para excluir uma tarefa
    public function deleteTask()
    {
        $model = new TaskManagerModel();

        if (!$this->accessControlController->checkPermission('D')) {
            return ['success' => false, 'message' => 'Permissão negada para excluir tarefas'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $response = $model->deleteTask($data);
        return $response;
    }

    // Método para visualizar uma tarefa por ID
    public function viewTask()
    {
        $model = new TaskManagerModel();

        if (!$this->accessControlController->checkPermission('R')) {
            return ['success' => false, 'message' => 'Permissão negada para visualizar tarefas'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $response = $model->viewTask($data);
        return $response;
    }
    // Método para atualizar o status de uma tarefa
    public function updateTaskStatus()
    {
        $model = new TaskManagerModel();

        if (!$this->accessControlController->checkPermission('R')) {
            return ['success' => false, 'message' => 'Permissão negada para atualizar o status da tarefa'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $data['status'] = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        $response = $model->updateTaskStatus($data);
        return $response;
    }
}
