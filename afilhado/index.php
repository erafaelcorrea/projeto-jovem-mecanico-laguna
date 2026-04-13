<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['afilhado'])) {
    header("Location: login.php");
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
    <link href="../assets/css/daisyui.min.css?v=1.1" rel="stylesheet" type="text/css" />
    <link href="../assets/css/tailwind.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <style>
        .rotate-container {
        display: inline-block;
        height: 1.2em;
        overflow: hidden;
        vertical-align: bottom;
        }

        .rotate-text {
        display: flex;
        flex-direction: column;
        animation: rotateWords 8s infinite;
        }

        .rotate-text span {
        height: 1.2em;
        }

        /* animação */
        @keyframes rotateWords {
        0%   { transform: translateY(0); }
        25%  { transform: translateY(-1.2em); }
        50%  { transform: translateY(-2.4em); }
        75%  { transform: translateY(-3.6em); }
        100% { transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="drawer lg:drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        
        <div class="drawer-content flex flex-col min-h-screen">
            
            <div class="navbar bg-base-100 shadow-md w-full top-0 z-10 px-4">
                <?php include("layout/navbar-top.php"); ?>
            </div>

            <main class="p-6 flex-grow bg-base-200 transition-colors duration-300">
        <?php
        if(isset($_GET['msg'])) { 
            $status = @$_GET['status'] === 'error' ? 'error' : 'success';
            echo msg($_GET['msg'], $status); 
        } else  {echo "<div id='Rtoast' style='display:none !important;'></div>"; }

        switch (@$_GET['pagina']) {
            case "avaliar":
                include("pages/avaliar.php");
            break; 
            case "responder":
                $responder = responder([
                    'quem_avalia' => $_SESSION['afilhado']['afilhado_id'],
                    'avaliacao' => $_POST['avaliacao_id'],
                    'pergunta' => $_POST['pergunta_atual_id'],
                    'resposta' => $_POST['resposta']
                ]);

                if($responder) {
                    echo "<script>window.location.href = \"index.php?pagina=avaliar&origem=" . encrypt($_POST['avaliacao_id']) . "&status=success&msg=Resposta registrada com sucesso!\";</script>";
                } else {
                    echo "<script>window.location.href = \"index.php?pagina=avaliar&origem=" . encrypt($_POST['avaliacao_id']) . "&status=error&msg=Erro ao registrar resposta.\";</script>";
                }

            break;
            case "avaliacoes":
                include("pages/avaliacoes.php");
            break;  
            case "bem-vindo":
                include("pages/bem-vindo.php");
            break; 
            case "sair":
                // limpa variáveis
                $_SESSION = [];

                // remove cookie da sessão
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();

                    setcookie(
                        session_name(),
                        '',
                        time() - 42000,
                        $params["path"],
                        $params["domain"],
                        $params["secure"],
                        $params["httponly"]
                    );
                }

                // destrói sessão
                session_destroy();

                echo "<script>window.location.href = \"login.php\";</script>";
            break; 
            default:
                include("pages/404.php");
        }
        ?> 
            </main>

            <footer class="footer p-2 flex flex-row justify-between">
                 <?php include("layout/footer.php"); ?>
            </footer>
        </div> 

        <div class="drawer-side z-50 border-r border-base-300">
            <?php include("layout/drawer-menu.php"); ?>
        </div>
    </div>

   <script src="../assets/js/global.js?v=1.3"></script>
</body>
</html>