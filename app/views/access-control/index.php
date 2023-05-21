<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");
require_once($_SERVER["DOCUMENT_ROOT"] . Routes::getConfigsRoutes()['authenticator']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Acessos</title>
    <link rel="stylesheet" href="/vendor/bootstrap/bootstrap-5.3.0-alpha3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<?php //validar o nivel de acesso
validateAccessPermission('A');
?>

<body>
    <div class="container-fluid">
        <!-- Header -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "header.php") ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">Controle de Acessos</h3>
                        <!-- Botão de inclusão fixo -->
                        <button id="createUserButton" class="btn btn-primary btn-lg">
                            <span class="fas fa-plus"></span> Criar Usuário
                        </button>
                    </div>
                    <div class="card-body border">
                        <div class="table-responsive text-center">
                            <table id="usersTable" class="table table-bordered table-hover table-striped text-center mt-4">
                                <thead id="filtros" class="border-top text-center"></thead> <!-- será preenchido pelo jQuery -->
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal de inclusão de usuário -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['login'] . 'modals/register_user_modal.php') ?>
        <!-- Modal de edição de usuário -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['accessControl'] . 'modals/edit_user_modal.php') ?>
        <!-- Modal de edição de usuário -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['accessControl'] . 'modals/edit_permission_modal.php') ?>
        <!-- inclusão de libs -->
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "libs.php") ?>
        <script src="/public/js/accessControl/accessControl.js"></script>
    </div>
</body>

</html>