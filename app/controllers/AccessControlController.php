<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getModelsRoutes()['accessControl']);

class AccessControlController
{
    // Método para verificar se o usuário possui a permissão necessária
    public function checkPermission($requiredPermission)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['permissions']) || !in_array($requiredPermission, $_SESSION['permissions'])) {
            return false;
        }

        return true;
    }
    // Método para pegar as permissões do usuário
    public function getUserPermissions()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['permissions'])) {
            return false;
        }
        return $_SESSION['permissions'];
    }

    // Método para obter todos os usuários
    public function getUsers()
    {

        if (!$this->checkPermission('A')) {
            return ['success' => false, 'message' => 'Permissão negada para visualizar usuários'];
        }
        $model = new AccessControlModel();
        return $model->getUsers();
    }

    // Método para obter as informações de um usuário pelo ID
    public function getUserById()
    {
        if (!$this->checkPermission('A')) {
            return ['success' => false, 'message' => 'Permissão negada para visualizar usuários'];
        }

        $userId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $model = new AccessControlModel();
        return $model->getUserById($userId);
    }

    // Método para editar as informações de um usuário pelo ID
    public function editUser()
    {
        if (!$this->checkPermission('A')) {
            return ['success' => false, 'message' => 'Permissão negada para visualizar usuários'];
        }

        // Verificar se todos os campos necessários estão presentes
        if (
            !isset($_POST['id']) ||
            !isset($_POST['name']) ||
            !isset($_POST['phone'])
        ) {
            return ['success' => false, 'message' => 'Campos obrigatórios ausentes'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $data['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['phone'] = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['password'] = password_hash(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT);

        $model = new AccessControlModel();
        return $model->editUser($data);
    }

    // Método para editar as permissões de um usuário pelo ID
    public function editUserPermissions()
    {
        if (!$this->checkPermission('A')) {
            return ['success' => false, 'message' => 'Permissão negada para visualizar usuários'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $rawPermissions = $_POST['permissions'] ?? [];
        $data['permissions'] = [];

        // Filtrar e sanitizar os valores dos checkboxes
        if (is_array($rawPermissions)) {
            foreach ($rawPermissions as $permission) {
                $sanitizedPermission = filter_var($permission, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if ($sanitizedPermission !== false) {
                    $data['permissions'][] = $sanitizedPermission;
                }
            }
        }

        $model = new AccessControlModel();
        return $model->editUserPermissions($data);
    }

    // Método para editar as permissões de um usuário pelo ID
    public function getPermissionsByUser()
    {
        if (!$this->checkPermission('A')) {
            return ['success' => false, 'message' => 'Permissão negada para editar permissões'];
        }

        // Verificar se todos os campos necessários estão presentes
        if (!isset($_POST['id'])) {
            return ['success' => false, 'message' => 'ID do usuário ausente'];
        }

        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        // Realizar a consulta para obter as permissões existentes
        $permissionsModel = new AccessControlModel();
        $existingPermissions = $permissionsModel->getPermissions();
        // Realizar a consulta para obter as permissões do usuário pelo ID
        $userPermissionsModel = new AccessControlModel();
        $userPermissions = $userPermissionsModel->getPermissionsByUserId($data);
        // Retornar os dados para o frontend
        return ['success' => true, 'permissions' => $existingPermissions, 'userPermissions' => $userPermissions];
    }
}
