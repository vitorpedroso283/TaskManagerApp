<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getConfigsRoutes()['authenticator']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Agendas e Tarefas</title>
    <link rel="stylesheet" href="/vendor/bootstrap/bootstrap-5.3.0-alpha3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/public/css/sidebar.css">
</head>

<body>
    <div class="container-fluid">
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "header.php") ?>

        <div class="row">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "sidebar.php") ?>
            <div class="col-md-9">
                <div class="container">
                    <h1 class="mt-5">Tarefas</h1>
                    <table id="taskTable" class="table mt-4 table-bordered">
                        <thead id="filtros"></thead>
                        <!-- será preenchido pelo jquery -->
                    </table>
                </div>
            </div>
        </div>

        <div class="fixed-bottom text-center mb-4">
            <button class="btn btn-primary btn-create-task" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                +
            </button>
        </div>

        <!-- modal de inclusão task -->
        <?php include_once('modals/create_task_modal.php') ?>
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "libs.php") ?>
        <script src="/public/js/home/home.js"></script>
    </div>
</body>

</html>
