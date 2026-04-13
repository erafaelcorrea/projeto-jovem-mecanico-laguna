<?php
session_start();

if (isset($_SESSION['afilhado'])) {
    header("Location: index.php?pagina=bem-vindo");
    exit;
}
require_once "functions.php";
?>
<!DOCTYPE html>
<html lang="pt-br" data-theme="light" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Jovem Mecânico</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link href="../assets/css/daisyui.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/tailwind.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <style>
        body {
            background-color: #3f4295;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="w-full max-w-md px-4">

    <!-- Card de Login -->
    <div class="card bg-base-100 shadow-2xl pt-4">
        <img src="../assets/img/login-arte.jpg">
        
      <div class="card-body pt-0">
  <?php
  switch (@$_GET['pagina']) {
      case "autenticar":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = $_POST['user'] ?? '';
            $senha = $_POST['senha'] ?? '';

            // chama sua API
            $url = SERVIDOR . "index.php?url=afilhados/login";

            $data = [
                'user' => $user,
                'senha' => $senha
            ];

            $options = [
                'http' => [
                    'header'  => "Content-Type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            $result = json_decode($response, true);

            if ($result && $result['status'] == 200) {
                $_SESSION['afilhado'] = $result['data'];
                echo "<script>window.location.href = \"index.php?pagina=bem-vindo\";</script>";

            } else {
                ?>
                <div role="alert" class="alert alert-error font-semibold">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    <span>Dados incorretos!</span>
                </div>
                <?php
                include "pages/entrar.php";
            }
            
        }

      break;
      case "recuperar-senha":
        include "pages/recuperar-senha.php";
      break;
      default:
        include "pages/entrar.php";
        
  }
  ?>
      </div>
    </div>

  </div>

</body>
</html>