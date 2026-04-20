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

    <div class="drawer lg:drawer-open">
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
                        'categoria' => htmlspecialchars($_POST['categoria'] ?? '')
                    ];
                    
                    $retorno = AvaliacaoModel::criarPergunta($dados);

                    if ($retorno !== false) {
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
                    $tem_batizado = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM batizado WHERE padrinho = {$_POST['padrinho_id']} AND afilhado = {$_POST['afilhado_id']}");
                    
                    if($tem_batizado > 0) {
                        $dados = [
                            'quem_avalia' =>  $_POST['quem_avalia'] ?? '',
                            'padrinho_id' => $_POST['padrinho_id'] ?? '',
                            'afilhado_id' => $_POST['afilhado_id'] ?? '',
                            'liberar' => $_POST['liberar'] ?? ''
                        ];
            
                        $retorno = GlobalModel::criarAvaliacao($dados);
                        if ($retorno) {
                            echo "<script>window.location.href = \"index.php?pagina=avaliacoes&status=success&msg=Avaliação criada com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=criar-avaliacao&status=error&msg=Erro ao criar avaliação.\";</script>";
                        }
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=criar-avaliacao&status=error&msg=Padrinho e afilhado devem ter um vínculo.\";</script>";
                    }
                }
            break;
            case "adicionar-pergunta-na-avaliacao":
                $avaliacao_id = decrypt($_GET['origem']);
                $pergunta_id = decrypt($_GET['pergunta']);
                if ($avaliacao_id && $pergunta_id) {
                    $perguntas_na_avaliacao = GlobalModel::retornarUmValor("SELECT perguntas FROM avaliacao WHERE id = {$avaliacao_id}");
                    
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
                        $atualizar_no_banco = GlobalModel::atualizarBanco("UPDATE avaliacao SET perguntas = '{$perguntas_atualizar}' WHERE id = {$avaliacao_id}");
                        
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
                    $perguntas_na_avaliacao = GlobalModel::retornarUmValor("SELECT perguntas FROM avaliacao WHERE id = {$avaliacao_id}");
                    
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
                        $atualizar_no_banco = GlobalModel::atualizarBanco("UPDATE avaliacao SET perguntas = '{$perguntas_atualizar}' WHERE id = {$avaliacao_id}");
                        
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
                    $atualizar_no_banco = GlobalModel::atualizarBanco("UPDATE avaliacao SET liberar = '{$liberar}' WHERE id = {$avaliacao_id}");
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
                $avaliacao_ja_foi_realizada = GlobalModel::retornarUmValor("SELECT realizada FROM avaliacao WHERE id = {$avaliacao_id}");
                
                if ($avaliacao_ja_foi_realizada === NULL) {
                    $dados = [
                        'avaliacao_id' => $avaliacao_id
                    ];
                    
                    $retorno= GlobalModel::excluirAvaliacao($dados);

                    if ($retorno === true) {
                        echo "<script>window.location.href = \"index.php?pagina=avaliacoes&status=success&msg=Avaliação excluída com sucesso!\";</script>";
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
                        'categoria' => htmlspecialchars($_POST['categoria'] ?? '')
                    ];
                    
                    $retorno= AvaliacaoModel::salvarPergunta($dados);
                    if ($retorno > 0) {
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
                    $tem_batizado = GlobalModel::retornarUmValor("SELECT id FROM batizado WHERE padrinho = {$_POST['padrinho_id']} AND afilhado = {$_POST['afilhado_id']}");
                    $padrinho_com_batizado_no_momento = GlobalModel::retornarUmValor("SELECT id FROM batizado WHERE padrinho = {$_POST['padrinho_id']} AND fim >= CURDATE()");
                    $afilhado_com_batizado_no_momento = GlobalModel::retornarUmValor("SELECT id FROM batizado WHERE afilhado = {$_POST['afilhado_id']} AND fim >= CURDATE()");

                    if ($tem_batizado > 0) {
                        echo "<script>window.location.href = \"index.php?pagina=vincular-afilhado&origem=" . encrypt($_POST['padrinho_id']) . "&status=error&msg=Já existe um vínculo para esse padrinho e afilhado.\";</script>";
                    } else if ($padrinho_com_batizado_no_momento > 0 || $afilhado_com_batizado_no_momento > 0) {
                        echo "<script>window.location.href = \"index.php?pagina=vincular-afilhado&origem=" . encrypt($_POST['padrinho_id']) . "&status=error&msg=Padrinho ou Afilhado tem um vínculo no momento.\";</script>";
                    } else {
                        $dados = [
                        'padrinho_id' => $_POST['padrinho_id'] ?? null,
                        'afilhado_id' => $_POST['afilhado_id'] ?? null,
                        'inicio' => $_POST['inicio'] ?? null,
                        'fim' => $_POST['fim'] ?? null
                        ];
                        
                        $retorno= GlobalModel::salvarVinculo($dados);
                        if ($retorno === true) {
                            echo "<script>window.location.href = \"index.php?pagina=padrinhos&status=success&msg=Vínculo salvo com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=vincular-afilhado&origem=" . encrypt($dados['padrinho_id']) . "&status=error&msg=Erro ao salvar vínculo.\";</script>";
                        }
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
                    
                    $retorno= PadrinhoModel::adicionarPadrinho($dados);
                    
                    
                    if ($retorno !== false) {
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                           $salvar_imagem = uploadImagem($_FILES['foto'], 'padrinho_' . $retorno, '../assets/img/'); 
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
                    
                    $retorno= PadrinhoModel::salvarPadrinho($dados);

                    
                    if ($retorno !== false) {
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                            $salvar_imagem = uploadImagem($_FILES['foto'], 'padrinho_' . $dados['id'], '../assets/img/');
                        }
                        echo "<script>window.location.href = \"index.php?pagina=padrinhos&status=success&msg=Padrinho atualizado com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-padrinho&origem=" . encrypt($dados['id']) . "&status=error&msg=Erro ao atualizar padrinho.\";</script>";
                    }

                }
            break;
            case "editar-avaliacao-instrutor":
                include("pages/editar-avaliacao-instrutor.php");
            break;
            case "adicionar-pergunta-instrutor":
                include("pages/adicionar-pergunta-instrutor.php"); 
            break;
            case "avaliacoes-instrutor":
                include("pages/avaliacoes-instrutor.php");
            break;
            case "criar-avaliacao-instrutor":
                include("pages/criar-avaliacao-instrutor.php");
            break;
            case "salvar-nova-avaliacao-instrutor":
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $tem_avaliacao_para_esse_afilhado = GlobalModel::retornarUmValor("SELECT id FROM avaliacao_instrutor WHERE afilhado_id = {$_POST['afilhado_id']}");
                    
                    if($tem_avaliacao_para_esse_afilhado > 0) {
                        echo "<script>window.location.href = \"index.php?pagina=criar-avaliacao-instrutor&status=error&msg=Já existe uma avaliação para esse aluno.\";</script>";
                    } else {
                        $dados = [
                        'instrutor' =>  $_POST['instrutor'] ?? '',
                        'afilhado_id' => $_POST['afilhado_id'] ?? '',
                        'liberar' => htmlspecialchars($_POST['liberar'] ?? '')
                        ];
                    
                        $retorno = AvaliacaoModel::criarAvaliacaoInstrutor($dados); 
                        if ($retorno) {
                            echo "<script>window.location.href = \"index.php?pagina=avaliacoes-instrutor&status=success&msg=Avaliação criada com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=criar-avaliacao-instrutor&status=error&msg=Erro ao criar avaliação.\";</script>";
                        }
                    }
                    
                }
            break;
            case "excluir-avaliacao-instrutor":
                $avaliacao_id = isset($_GET['origem']) ? decrypt($_GET['origem']) : null;
                $avaliacao_ja_foi_realizada = GlobalModel::retornarUmValor("SELECT realizada FROM avaliacao_instrutor WHERE id = {$avaliacao_id} AND realizada IS NOT NULL");
                
                if (!$avaliacao_ja_foi_realizada) {
                    $dados = [
                        'avaliacao_id' => $avaliacao_id
                    ];
                    
                    $retorno= AvaliacaoModel::excluirAvaliacaoInstrutor($dados);

                    if ($retorno === true) {
                        echo "<script>window.location.href = \"index.php?pagina=avaliacoes-instrutor&status=success&msg=Avaliação excluída com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($dados['avaliacao_id']) . "&status=error&msg=Erro ao excluir avaliação.\";</script>";
                    }
                } else {
                    echo "<script>window.location.href = \"index.php?pagina=avaliacoes-instrutor&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Avaliação já foi realizada e não pode ser excluída.\";</script>";
                }
            break;
            case "atualizar-data-avaliacao-instrutor":
                $avaliacao_id = isset($_POST['avaliacao_id']) ? decrypt($_POST['avaliacao_id']) : null;
                $liberar = isset($_POST['liberar']) ? $_POST['liberar'] : null;
                
                if ($avaliacao_id && $liberar) {
                    $atualizar_no_banco = GlobalModel::atualizarBanco("UPDATE avaliacao_instrutor SET liberar = '{$liberar}' WHERE id = {$avaliacao_id}");
                    if ($atualizar_no_banco) {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=success&msg=Data atualizada com sucesso!\";</script>";
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Erro ao atualizar data.\";</script>";
                    }
                    
                } else {
                    include("pages/404.php");
                }
            break;
            case "adicionar-pergunta-na-avaliacao-instrutor":
                $avaliacao_id = isset($_GET['origem']) ? decrypt($_GET['origem']) : null;
                $pergunta_id = isset($_GET['pergunta']) ? decrypt($_GET['pergunta']) : null;
                if ($avaliacao_id && $pergunta_id) {
                    $perguntas_na_avaliacao = GlobalModel::retornarUmValor("SELECT perguntas FROM avaliacao_instrutor WHERE id = {$avaliacao_id}");
                    
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
                        $atualizar_no_banco = GlobalModel::atualizarBanco("UPDATE avaliacao_instrutor SET perguntas = '{$perguntas_atualizar}' WHERE id = {$avaliacao_id}");
                        
                        if ($atualizar_no_banco) {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=success&msg=Pergunta adicionada com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Erro ao adicionar pergunta.\";</script>";
                        }
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Pergunta já adicionada.\";</script>";
                    }

                } else {
                    include("pages/404.php");
                }
            break;
            case "remover-pergunta-na-avaliacao-instrutor":
                $avaliacao_id = isset($_GET['origem']) ? decrypt($_GET['origem']) : null;
                $pergunta_id = isset($_GET['pergunta']) ? decrypt($_GET['pergunta']) : null;
                if ($avaliacao_id && $pergunta_id) {
                    $perguntas_na_avaliacao = GlobalModel::retornarUmValor("SELECT perguntas FROM avaliacao_instrutor WHERE id = {$avaliacao_id}");
                    
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
                        $atualizar_no_banco = GlobalModel::atualizarBanco("UPDATE avaliacao_instrutor SET perguntas = '{$perguntas_atualizar}' WHERE id = {$avaliacao_id}");
                        
                        if ($atualizar_no_banco) {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=success&msg=Pergunta removida com sucesso!\";</script>";
                        } else {
                            echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Erro ao remover pergunta.\";</script>";
                        }
                    } else {
                        echo "<script>window.location.href = \"index.php?pagina=editar-avaliacao-instrutor&origem=" . encrypt($avaliacao_id) . "&status=error&msg=Pergunta não encontrada.\";</script>";
                    }

                } else {
                    include("pages/404.php");
                }
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