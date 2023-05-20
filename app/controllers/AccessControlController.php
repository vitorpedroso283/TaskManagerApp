<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
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
}
