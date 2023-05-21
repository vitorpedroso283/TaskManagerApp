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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container-fluid">
        <!-- Header -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "header.php") ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">Todas tarefas</h3>
                        <!-- Botão de inclusão fixo -->
                        <button id="createTaskButton" class="btn btn-primary btn-lg">
                            <span class="fas fa-plus"></span> Criar Tarefa
                        </button>
                    </div>
                    <div class="card-body border">
                        <div class="table-responsive text-center">
                            <table id="taskTable" class="table table-bordered table-hover table-striped mt-4">
                                <thead id="filtros" class="border-top"></thead>
                                <!-- será preenchido pelo jQuery -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de inclusão de tarefa -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['home'] . 'modals/create_task_modal.php') ?>
            <!-- Modal de edição de tarefa -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['home'] . 'modals/edit_task_modal.php') ?>
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "libs.php") ?>
        <script src="/public/js/allTasks/allTasks.js"></script>
    </div>
</body>

</html>