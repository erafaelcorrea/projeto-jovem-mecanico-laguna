<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['adm'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../api/functions.php';
require_once __DIR__ . '/../api/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-theme="light" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Jovem Mecânico</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
</head>
<body>

    <div class="drawer">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        
        <div class="drawer-content flex flex-col min-h-screen">
            
            <div class="navbar bg-base-100 shadow-md w-full top-0 z-10 px-4">
                <?php include("layout/navbar-top.php"); ?>
            </div>

            <main class="p-6 flex-grow bg-base-200 transition-colors duration-300">
        <?php
        if(isset($_GET['msg'])) { 
            $status = $_GET['status'] === 'error' ? 'error' : 'success';
            echo msg($_GET['msg'], $status); 
        } else  {echo "<div id='Rtoast' style='display:none !important;'></div>"; }

        switch (@$_GET['pagina']) {
            case "relatorio":
                include("pages/relatorio.php");
            break; 
            case "avaliacao-comportamental-instrutor":
                include("pages/avaliacao-comportamental-instrutor.php");
            break; 
            case "dissertativas":
                include("pages/dissertativas.php");
            break;
            case "afilhados":
                include("pages/afilhados.php");
            break;
            case "afilhado":
                include("pages/afilhado.php");
            break;
            case "padrinhos":
                include("pages/padrinhos.php");
            break;
            case "padrinho":
                include("pages/padrinho.php");
            break;
            case "notas-provas":
                include("pages/notas-provas.php");
            break; 
            case "editar-pergunta":
                include("pages/editar-pergunta.php");
            break;
            case "salvar-pergunta":
                case "salvar-editar-pergunta":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dados = [
                        'id' => $_POST['pergunta_id'] ?? null,
                        'pergunta' => htmlspecialchars($_POST['pergunta'] ?? ''),
                        'descricao' => htmlspecialchars($_POST['descricao'] ?? ''),
                        'modo' => htmlspecialchars($_POST['modo'] ?? ''),
                        'perfil' => htmlspecialchars($_POST['perfil'] ?? ''),
                        'categoria' => htmlspecialchars($_POST['categoria'] ?? '')
                    ];
                    
                    $retorno= AvaliacaoModel::salvarPergunta($dados);
                    if ($retorno > 0) {
                        echo "<script>window.location.href = \"index.php?pagina=bem-vindo&status=success&msg=Pergunta atualizada com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-pergunta&origem=" . encrypt($dados['id']) . "&status=error&msg=Erro ao atualizar pergunta.\";</script>";
                    }
                }
            break;
            case "perguntas":
                include("pages/perguntas.php");
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

   <script src="../assets/js/global.js?v=1.5"></script>
</body>
</html>