<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getConfigsRoutes()['database']);


class LoginModel
{
    private $pdo;
    private $table = "users";
    private $table1 = "permissions";
    private $table2 = "user_permission";

    public function __construct()
    {
        $conexaoModel = new Connection();

        $this->pdo = $conexaoModel->connectDB("task_manager");
    }

    public function register($data)
    {
        try {
            // Verificar se o email já existe
            if ($this->checkExistingEmail($data['email'])) {
                return ['success' => false, 'message' => 'O email já está em uso'];
            }

            // Inicia uma transação
            $this->pdo->beginTransaction();

            $query = "INSERT INTO $this->table (name, email, password, phone, created_at, updated_at, status) 
                      VALUES (UPPER(:name), LOWER(:email), :password, :phone, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Obtém o ID gerado para o usuário inserido
                $user_id = $this->pdo->lastInsertId();

                //pesquisar acesso de leitura (R- READ)
                $stmt = $this->pdo->prepare("SELECT id FROM $this->table1 WHERE acronym = 'R';");
                $stmt->execute();
                $permition = $stmt->fetch();
                $permition_id = $permition['id'];

                // Inserir registro correspondente na tabela "user_permission"
                $stmt = $this->pdo->prepare("INSERT INTO $this->table2 (user_id, permission_id) VALUES (:user_id, :permission_id)");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':permission_id', $permition_id, PDO::PARAM_INT);
                $stmt->execute();

                // Confirma a transação
                $this->pdo->commit();

                return ['success' => true, 'message' => 'Usuário registrado com sucesso'];
            }
        } catch (PDOException $e) {
            // Caso ocorra algum erro, desfaz a transação
            $this->pdo->rollback();
            return ['success' => false, 'message' => 'Erro ao registrar o usuário'];
        }
    }

    public function checkExistingEmail($email)
    {
        $query = "SELECT COUNT(*) as count FROM $this->table WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0; // Retorna true se o email já existe, caso contrário, retorna false
    }
    public function login($dados)
    {
        // Verificar se o usuário existe com o email fornecido
        $query = "SELECT * FROM $this->table WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // Verificar se a senha fornecida está correta
        if (!password_verify($dados['password'], $user['password'])) {
            return false;
        }
        // Pegar todas permissões do usuário
        $query = "SELECT P.acronym FROM $this->table2 PU INNER JOIN $this->table1 P ON P.id = PU.permission_id WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->execute();
        $allPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
        // O login foi bem-sucedido
        return ['user_id' => $user['id'], 'permissions' => $allPermissions];
    }

    public function logout()
    {

        if (!isset($_SESSION)) {
            session_start();
        }
        session_regenerate_id();
        $dados['mensagem'] = true;
        session_destroy();
        return $dados;
    }
}
