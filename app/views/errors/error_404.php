<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Página não encontrada</title>
  <link rel="stylesheet" href="/vendor/bootstrap/bootstrap-5.3.0-alpha3/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .error-container {
      text-align: center;
    }

    .error-code {
      font-size: 6rem;
      color: #dc3545;
      margin-bottom: 0;
    }

    .error-message {
      font-size: 2rem;
      margin-top: 0;
      color: #333;
    }

    .btn-back {
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="error-container">
      <h1 class="error-code">404</h1>
      <p class="error-message">Página não encontrada</p>
      <p>Desculpe, a página que você está procurando não existe.</p>
      <a href="/" class="btn btn-primary btn-back">Voltar para a página inicial</a>
    </div>
  </div>

  <script src="/vendor/bootstrap/bootstrap-5.3.0-alpha3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
