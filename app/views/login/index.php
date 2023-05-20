<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/routes.php");

session_start(); // Inicia a sessão
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destrói a sessão
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TASK</title>
    <link rel="stylesheet" href="/vendor/bootstrap/bootstrap-5.3.0-alpha3/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/login.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Task Manager</h2>
                    </div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" placeholder="Entre com seu e-mail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" placeholder="Insira sua senha" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-login">Login</button>
                            <button type="button" class="btn btn-link btn-register" data-bs-toggle="modal" data-bs-target="#registerModal">Cadastre-se</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('modals/register_user_modal.php'); ?>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . Routes::getViewsRoutes()['includes'] . "libs.php") ?>
    <script src="/public/js/login/login.js"></script>
</body>

</html>