<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . Routes::getConfigsRoutes()['database']);
class AccessControlModel {
    private $pdo;
    private $table = "tasks";

    public function __construct()
    {
        $conexaoModel = new Connection();
        $this->pdo = $conexaoModel->connectDB("task_manager");
    }
}