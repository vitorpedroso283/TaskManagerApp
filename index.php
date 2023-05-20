<?php 

include_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
if (!isset($_SESSION)) {
    session_start();

    if (!isset($_SESSION['id_usuario'])) {
        session_destroy();
        header("location:" . Routes::getViewsRoutes()["login"]);
    }else{
        header("location:" . Routes::getViewsRoutes()["home"]);
    }
}