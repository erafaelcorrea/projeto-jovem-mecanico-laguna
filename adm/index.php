<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['adm'])) {
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
            $status = isset($_GET['status']) === 'error' ? 'error' : 'success';
            echo msg($_GET['msg'], $status); 
        } else  {echo "<div id='Rtoast' style='display:none !important;'></div>"; }

        switch (@$_GET['pagina']) {
            case "afilhados":
                include("pages/afilhados.php");
            break;
            case "afilhado":
                include("pages/afilhado.php");
            break;
            case "avaliacoes":
                include("pages/avaliacoes.php");
            break;
            case "editar-avaliacao":
                include("pages/editar-avaliacao.php");
            break;
            case "perguntas":
                include("pages/perguntas.php");
            break;
            case "adicionar-pergunta":
                include("pages/adicionar-pergunta.php");
            break;
            case "salvar-pergunta":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dados = [
                        'pergunta' => htmlspecialchars($_POST['pergunta'] ?? ''),
                        'descricao' => htmlspecialchars($_POST['descricao'] ?? ''),
                        'modo' => htmlspecialchars($_POST['modo'] ?? ''),
                        'perfil' => htmlspecialchars($_POST['perfil'] ?? ''),
                        'categoria' => htmlspecialchars($_POST['categoria'] ?? ''),
                        'subcategoria' => htmlspecialchars($_POST['subcategoria'] ?? '')
                    ];
                    $url = SERVIDOR . "index.php?url=global/adicionar-pergunta";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);
                    if ($retorno['status'] === 200) {
                        echo "<script>window.location.href = \"index.php?pagina=perguntas&status=success&msg=Pergunta adicionada com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=adicionar-pergunta&status=error&msg=Erro ao adicionar pergunta.\";</script>";
                    }
                }
            break;
            case "criar-avaliacao":
                include("pages/criar-avaliacao.php");
            break;
            case "salvar-nova-avaliacao":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    
                    $dados = [
                        'quem_avalia' =>  $_POST['quem_avalia'] ?? '',
                        'padrinho_id' => $_POST['padrinho_id'] ?? '',
                        'afilhado_id' => $_POST['afilhado_id'] ?? '',
                        'liberar' => htmlspecialchars($_POST['liberar'] ?? '')
                    ];
                    $url = SERVIDOR . "index.php?url=global/criar-avaliacao";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);
                    if ($retorno['status'] === 200) {
                        echo "<script>window.location.href = \"index.php?pagina=avaliacoes&status=success&msg=Avaliação criada com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=criar-avaliacao&status=error&msg=Erro ao criar avaliação.\";</script>";
                    }
                }
            break;
            case "adicionar-pergunta-na-avaliacao":
                $avaliacao_id = isset($_GET['origem']) ? decrypt($_GET['origem']) : null;
                $pergunta_id = isset($_GET['pergunta']) ? decrypt($_GET['pergunta']) : null;
                if ($avaliacao_id && $pergunta_id) {
                    $perguntas_na_avaliacao = retornarUmValor("SELECT perguntas FROM avaliacao WHERE id = {$avaliacao_id}");
                    
                    // garante array limpo
                    $array = !empty($perguntas_na_avaliacao)
                        ? array_map('intval', explode(',', $perguntas_na_avaliacao))
                        : [];

                    // força int também aqui
                    $pergunta_id = (int) $pergunta_id;

                    // verifica corretamente
                    if (!in_array($pergunta_id, $array, true)) {
                        $array[] = $pergunta_id;
                        $perguntas_atualizar = implode(',', $array);
                    } else {
                        $perguntas_atualizar = false;
                    }

                    if ($perguntas_atualizar !== false) {
                        $atualizar_no_banco = atualizarBanco("UPDATE avaliacao SET perguntas = '{$perguntas_atualizar}' WHERE id = {$avaliacao_id}");
                        
                        if ($atualizar_no_banco) {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=success&msg=Pergunta adicionada com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Erro ao adicionar pergunta.\";</script>";
                        }
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Pergunta já adicionada.\";</script>";
                    }

                } else {
                    include("pages/404.php");
                }
            break;
            case "remover-pergunta-na-avaliacao":
                $avaliacao_id = isset($_GET['origem']) ? decrypt($_GET['origem']) : null;
                $pergunta_id = isset($_GET['pergunta']) ? decrypt($_GET['pergunta']) : null;
                if ($avaliacao_id && $pergunta_id) {
                    $perguntas_na_avaliacao = retornarUmValor("SELECT perguntas FROM avaliacao WHERE id = {$avaliacao_id}");
                    
                    // garante array limpo
                    $array = !empty($perguntas_na_avaliacao)
                        ? array_map('intval', explode(',', $perguntas_na_avaliacao))
                        : [];

                    // força int também aqui
                    $pergunta_id = (int) $pergunta_id;

                    // verifica corretamente
                    if (in_array($pergunta_id, $array, true)) {
                        // remove o valor
                        $array = array_filter($array, function($item) use ($pergunta_id) {
                            return $item !== $pergunta_id;
                        });

                        // reindexa (boa prática)
                        $array = array_values($array);
                        $perguntas_atualizar = implode(',', $array);
                    } else {
                        $perguntas_atualizar = false;
                    }

                    if ($perguntas_atualizar !== false) {
                        $atualizar_no_banco = atualizarBanco("UPDATE avaliacao SET perguntas = '{$perguntas_atualizar}' WHERE id = {$avaliacao_id}");
                        
                        if ($atualizar_no_banco) {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=success&msg=Pergunta removida com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Erro ao remover pergunta.\";</script>";
                        }
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Pergunta não encontrada.\";</script>";
                    }

                } else {
                    include("pages/404.php");
                }
            break;
            case "atualizar-data-avaliacao":
                $avaliacao_id = isset($_POST['avaliacao_id']) ? decrypt($_POST['avaliacao_id']) : null;
                $liberar = isset($_POST['liberar']) ? $_POST['liberar'] : null;
                
                if ($avaliacao_id && $liberar) {
                    $atualizar_no_banco = atualizarBanco("UPDATE avaliacao SET liberar = '{$liberar}' WHERE id = {$avaliacao_id}");
                    if ($atualizar_no_banco) {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=success&msg=Data atualizada com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Erro ao atualizar data.\";</script>";
                    }
                    
                } else {
                    include("pages/404.php");
                }
            break;
            case "excluir-avaliacao":
                $avaliacao_id = isset($_GET['origem']) ? decrypt($_GET['origem']) : null;
                $avaliacao_ja_foi_realizada = retornarUmValor("SELECT realizada FROM avaliacao WHERE id = {$avaliacao_id}");
                
                if ($avaliacao_ja_foi_realizada === NULL) {
                    $dados = [
                        'avaliacao_id' => $avaliacao_id
                    ];
                    $url = SERVIDOR . "index.php?url=global/excluir-avaliacao";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);
                    if ($retorno['status'] === 200) {
                        echo "<script>window.location.href = \"index.php?pagina=avaliacoes\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($dados['avaliacao_id']) . "&status=error&msg=Erro ao excluir avaliação.\";</script>";
                    }
                } else {
                    echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Avaliação já foi realizada e não pode ser excluída.\";</script>";
                }

            break;
            case "editar-pergunta":
                include("pages/editar-pergunta.php");
            break;
            case "salvar-editar-pergunta":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dados = [
                        'id' => $_POST['pergunta_id'] ?? null,
                        'pergunta' => htmlspecialchars($_POST['pergunta'] ?? ''),
                        'descricao' => htmlspecialchars($_POST['descricao'] ?? ''),
                        'modo' => htmlspecialchars($_POST['modo'] ?? ''),
                        'perfil' => htmlspecialchars($_POST['perfil'] ?? ''),
                        'categoria' => htmlspecialchars($_POST['categoria'] ?? ''),
                        'subcategoria' => htmlspecialchars($_POST['subcategoria'] ?? '')
                    ];
                    $url = SERVIDOR . "index.php?url=global/update-pergunta";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);
                    if ($retorno['status'] === 200) {
                        echo "<script>window.location.href = \"index.php?pagina=perguntas&status=success&msg=Pergunta atualizada com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-pergunta&origem=" . encrypt($dados['id']) . "&status=error&msg=Erro ao atualizar pergunta.\";</script>";
                    }
                }
            break;
            case "padrinhos":
                include("pages/padrinhos.php");
            break;
            case "padrinho":
                include("pages/padrinho.php");
            break;
            case "vincular-afilhado":
                include("pages/vincular-afilhado.php");
            break;
            case "salvar-vincular":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dados = [
                        'padrinho_id' => $_POST['padrinho_id'] ?? null,
                        'afilhado_id' => $_POST['afilhado_id'] ?? null,
                        'inicio' => $_POST['inicio'] ?? null,
                        'fim' => $_POST['fim'] ?? null
                    ];
                    $url = SERVIDOR . "index.php?url=global/salvar-vinculo";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);
                    if ($retorno['status'] === 200) {
                        echo "<script>window.location.href = \"index.php?pagina=padrinhos&status=success&msg=Vínculo salvo com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=vincular-afilhado&origem=" . encrypt($dados['padrinho_id']) . "&status=error&msg=Erro ao salvar vínculo.\";</script>";
                    }
                }
            case "adicionar-padrinho":
                include("pages/adicionar-padrinho.php");
            break;
            case "salvar-adicionar-padrinho":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dados = [
                        'nome' => $_POST['nome'] ?? '',
                        'matricula' => $_POST['matricula'] ?? '',
                        'data_nascimento' => $_POST['data_nascimento'] ?? '',
                        'sexo' => $_POST['sexo'] ?? '',
                        'telefone' => $_POST['telefone'] ?? '',
                        'email' => $_POST['email'] ?? '',
                        'user' => $_POST['user'] ?? '',
                        'senha' => $_POST['senha'] ?? ''

                    ];
                    $url = SERVIDOR . "index.php?url=padrinhos";
                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));

                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);

                    
                    if ($retorno['status'] === 201) {
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                            $salvar_imagem = uploadImagem(
                                $_FILES['foto'],
                                'padrinho_' . $retorno['data']['id'],
                                '../padrinho/assets/img/'
                            );
                        }
                        echo "<script>window.location.href = \"index.php?pagina=padrinhos&status=success&msg=Padrinho adicionado com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=adicionar-padrinho&status=error&msg=Erro ao adicionar padrinho.\";</script>";
                    }

                }
            break;
            case "editar-padrinho":
                include("pages/editar-padrinho.php");
            break;
            case "salvar-editar-padrinho":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dados = [
                        'id' => $_POST['padrinho_id'] ?? null,
                        'nome' => $_POST['nome'] ?? '',
                        'matricula' => $_POST['matricula'] ?? '',
                        'data_nascimento' => $_POST['data_nascimento'] ?? '',
                        'sexo' => $_POST['sexo'] ?? '',
                        'telefone' => $_POST['telefone'] ?? '',
                        'email' => $_POST['email'] ?? ''
                    ];
                    $url = SERVIDOR . "index.php?url=padrinhos/update";
                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));

                    $response = curl_exec($ch);
                    $retorno= json_decode($response, true);

                    
                    if ($retorno['status'] === 200) {
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                            $salvar_imagem = uploadImagem($_FILES['foto'], 'padrinho_' . $dados['id'], '../padrinho/assets/img/');
                        }
                        echo "<script>window.location.href = \"index.php?pagina=padrinhos&status=success&msg=Padrinho atualizado com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-padrinho&origem=" . encrypt($dados['id']) . "&status=error&msg=Erro ao atualizar padrinho.\";</script>";
                    }

                }
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