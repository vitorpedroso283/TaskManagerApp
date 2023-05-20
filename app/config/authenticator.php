<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getControllersRoutes()["login"]);
$controller = new LoginController;
$controller->checkLogin();
