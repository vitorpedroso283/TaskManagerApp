<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getControllersRoutes()["login"]);
$controller = new LoginController;
$controller->checkLogin();
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getControllersRoutes()['accessControl']);

function validateAccessPermission($permission)
{
    $accessControlController = new AccessControlController();
if (!$accessControlController->checkPermission($permission)) {
    echo '<div class="container">';
    echo '<div class="row justify-content-center">';
    echo '<div class="col-md-6">';
    echo '<div class="alert alert-danger text-center" role="alert">';
    echo '<i class="bi bi-exclamation-octagon-fill text-danger display-1"></i>';
    echo '<p class="mb-0 mt-2 h4">Você não tem permissão para acessar esta página.</p>';
    echo '<p class="mb-0">Solicite acesso ao administrador do sistema.</p>';
    echo '<p class="mb-0">Você será redirecionado em 3 segundos.</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<script>setTimeout(function() { window.history.go(-1); }, 3000);</script>';
    exit;
}

}

