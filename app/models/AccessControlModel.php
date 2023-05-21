
<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getConfigsRoutes()['database']);

class AccessControlModel
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

    public function getUsers()
    {
        $query = "SELECT u.*, perm.acronym, perm.description 
                  FROM $this->table u 
                  LEFT JOIN $this->table2 up ON u.id = up.user_id 
                  LEFT JOIN $this->table1 perm ON up.permission_id = perm.id 
                  ORDER BY u.name ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar as permissões por usuário
        $groupedUsers = [];
        foreach ($users as $user) {
            $userId = $user['id'];
            if (!isset($groupedUsers[$userId])) {
                $groupedUsers[$userId] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'created_at' => $user['created_at'],
                    'status' => $user['status'],
                    'permissions' => []
                ];
            }
            if ($user['acronym']) {
                $groupedUsers[$userId]['permissions'][] = [
                    'acronym' => $user['acronym'],
                    'description' => $user['description']
                ];
            }
        }

        return array_values($groupedUsers);
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $response['success'] = true;
            $response['user'] = $user;
        } else {
            $response['success'] = false;
        }

        return $response;
    }

    public function editUser($data)
    {
        $query = "
        UPDATE $this->table
        SET
            name = :name,
            phone = :phone";

        if (!empty($data['password'])) {
            $query .= ",
            password = :password";
        }

        $query .= "
        WHERE
            id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

        if (!empty($data['password'])) {
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        }

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
    }


    public function editUserPermissions($data)
    {
        // Primeiro, remove todas as permissões existentes do usuário
        $query = "DELETE FROM $this->table2 WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $data['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Em seguida, insere as novas permissões para o usuário
        $query = "INSERT INTO $this->table2 (user_id, permission_id) VALUES (:user_id, :permission_id)";
        $stmt = $this->pdo->prepare($query);
        foreach ($data['permissions'] as $permissionId) {
            $stmt->bindParam(':user_id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':permission_id', $permissionId, PDO::PARAM_INT);
            $stmt->execute();
        }
        return true;
    }

    public function getPermissionsByUserId($data)
    {
        $query = "SELECT PU.permission_id,P.acronym FROM $this->table2 PU INNER JOIN $this->table1 P ON P.id = PU.permission_id WHERE PU.user_id = :userId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $response;
    }
    public function getPermissions()
    {
        $query = "SELECT * FROM $this->table1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }
}
