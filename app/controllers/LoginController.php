<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getModelsRoutes()['login']);

class LoginController
{
    //metodo de login
    public function login()
    {
        //instancia da classe model
        $model = new LoginModel();

        //filtro padrão do input email e password
        $data['email'] = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
        $data['password'] = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        //validação se input é email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $response = $model->login($data);
        if (!$response) {
            return $response;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['permissions'] = $response['permissions'];
        $_SESSION['user_id'] = $response['user_id'];
        return true;
    }
    //metodo de cadastro de usuário
    public function register()
    {
        $model = new LoginModel();
        // Recupere os dados do formulário
        $data['name'] = strtoupper(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
        $data['phone'] = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['email'] = strtolower(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $data['password'] = password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_DEFAULT);
        // Verifique se a senha é forte o suficiente
        if (!$this->isPasswordStrong($data['password'])) {
            return ['success' => false, 'message' => 'A senha deve conter pelo menos um caractere especial, um número e uma letra maiúscula, e ter no mínimo 8 caracteres.'];
        }
        $response = $model->register($data);
        return $response;
    }

    function isPasswordStrong($password)
    {
        // Verifica se a senha contém pelo menos um número
        $hasNumber = preg_match('/\d/', $password);

        // Verifica se a senha contém pelo menos uma letra maiúscula
        $hasUpperCase = preg_match('/[A-Z]/', $password);

        // Verifica se a senha contém pelo menos uma letra minúscula
        $hasLowerCase = preg_match('/[a-z]/', $password);

        // Verifica se a senha contém pelo menos um caractere especial
        $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

        // Verifica se a senha atende a todos os critérios
        $isStrong = $hasNumber && $hasUpperCase && $hasLowerCase && $hasSpecialChar;

        return $isStrong;
    }

    //verificar se usuario esta logado
    public function checkLogin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            session_destroy();
            header("location:" . Routes::getViewsRoutes()["login"]);
            exit(); // Termina a execução do script após redirecionar
        }
    }
}
