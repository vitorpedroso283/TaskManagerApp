<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getConfigsRoutes()['database']);

class TaskManagerModel
{
    private $pdo;
    private $table = "tasks";
    private $table1 = "users";

    public function __construct()
    {
        $conexaoModel = new Connection();
        $this->pdo = $conexaoModel->connectDB("task_manager");
    }

    public function getTasks()
    {
        $query = "SELECT t.id AS id, t.user_id, u.name AS user_name, t.name AS task_name, t.description, t.created_at, t.finished_at, t.status
        FROM $this->table t
        INNER JOIN $this->table1 u ON u.id = t.user_id ORDER BY id DESC;
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tasks;
    }

    public function createTask($data)
    {
        $query = "INSERT INTO $this->table (title, description) VALUES (:title, :description)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Tarefa criada com sucesso';
        } else {
            $response['success'] = false;
            $response['message'] = 'Erro ao criar a tarefa';
        }
        return $response;
    }

    public function editTask($data)
    {
        $query = "UPDATE $this->table SET title = :title, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Tarefa editada com sucesso';
        } else {
            $response['success'] = false;
            $response['message'] = 'Erro ao editar a tarefa';
        }
        return $response;
    }

    public function deleteTask($data)
    {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Tarefa excluída com sucesso';
        } else {
            $response['success'] = false;
            $response['message'] = 'Erro ao excluir a tarefa';
        }
        return $response;
    }

    public function viewTask($data)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($task) {
            $response['success'] = true;
            $response['task'] = $task;
        } else {
            $response['success'] = false;
            $response['message'] = 'Erro ao visualizar a tarefa';
        }
        return $response;
    }
}
