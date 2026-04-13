<?php
date_default_timezone_set('America/Sao_Paulo');

require_once "padrinho/functions.php";
?>
<!DOCTYPE html>
<html lang="pt-br" data-theme="light" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Jovem Mecânico</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link href="assets/css/daisyui.min.css?v=1.1" rel="stylesheet" type="text/css" />
    <link href="assets/css/tailwind.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <style>
        @media print {
            /* Esconde tudo */
            body * {
                visibility: hidden;
            }
            /* Mostra apenas a área de impressão e seus descendentes */
            .area-impressao, .area-impressao * {
                visibility: visible;
            }
            /* Posiciona a área no topo da página */
            .area-impressao {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* Esconde elementos específicos definidos na classe */
            .nao-imprimir {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="drawer lg:drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        
        <div class="drawer-content flex flex-col min-h-screen">
            
            <div class="navbar bg-base-100 shadow-md w-full top-0 z-10 px-4">
                <?php include("padrinho/layout/navbar-top.php"); ?>
            </div>

            <main class="p-6 flex-grow bg-base-200 transition-colors duration-300">
        <?php
        switch (@$_GET['print']) { 
            case "avaliacao":
                //pegar nome do afilhado
                $nome_afilhado = retornarUmValor("SELECT a.nome FROM afilhados a INNER JOIN batizado b WHERE a.id = b.afilhado AND b.id = {$_GET['batizado']}");
                //pegar id das perguntas cadastradas para essa avaliação
                //$perguntas_id = array_map('intval', explode(",", $data_liberar_avaliar['perguntas']));
                //calcular quantas perguntas existem no total
                //$total_perguntas = count($perguntas_id);
?>
<div class="max-w-3xl mx-auto p-4 area-impressao">
  <!-- CABEÇALHO -->
  <div class="text-center mb-6 flex justify-between">
    <h1 class="text-2xl font-bold">AVALIAÇÃO DE DESEMPENHO</h1>
    <div class="text-sm mt-1">Data: ____ / ____ / ______</div>
  </div>
  <!-- IDENTIFICAÇÃO -->
  <div class="card bg-base-100 shadow-sm border mb-6">
    <div class="card-body">
      <!-- DIREÇÃO DA AVALIAÇÃO -->
      <div class="flex justify-between items-center text-sm font-semibold mb-4">
        <span class="badge badge-outline p-3">Padrinho</span>
        <span class="text-gray-400 text-2xl">→</span>
        <span class="badge badge-outline p-3">Afilhado</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-semibold">Nome do Padrinho</label>
          <div class="border-b border-gray-400 h-6"></div>
        </div>
        <div>
          <label class="text-sm font-semibold">Nome do Afilhado</label>
          <div class="border-b border-gray-400 h-6"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- PERGUNTA 1 -->
  <div class="card bg-base-100 shadow-sm border mb-4">
    <div class="card-body relative">
      <span class="absolute top-1 left-3 text-2xl text-gray-300">PERGUNTA #1</span>    
      <fieldset class="fieldset mt-4">
        <legend class="fieldset-legend font-semibold">
          1. Comprometimento com as atividades
        </legend> 
        <span class="label text-sm italic mt-2">
          Avalie o nível de responsabilidade e dedicação nas tarefas
        </span> 
        <div class="grid grid-cols-5 gap-3 p-2 mt-2">
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-error" />
            <span class="text-xs font-semibold">PÉSSIMO</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-warning" />
            <span class="text-xs font-semibold">RUIM</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-info" />
            <span class="text-xs font-semibold">BOM</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-accent" />
            <span class="text-xs font-semibold">ÓTIMO</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-success" />
            <span class="text-xs font-semibold">EXCELENTE</span>
          </div>
        </div>
      </fieldset> 
    </div>
  </div>
  <!-- PERGUNTA 2 -->
  <div class="card bg-base-100 shadow-sm border mb-4">
    <div class="card-body relative">
      <span class="absolute top-1 left-3 text-2xl text-gray-300">PERGUNTA #2</span>    

      <fieldset class="fieldset mt-4">
        <legend class="fieldset-legend font-semibold">
          2. Relacionamento interpessoal
        </legend> 
        <span class="label text-sm italic mt-2">
          Avalie a comunicação e convivência com a equipe
        </span> 

        <div class="grid grid-cols-5 gap-3 p-2 mt-2">
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-error" />
            <span class="text-xs font-semibold">PÉSSIMO</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-warning" />
            <span class="text-xs font-semibold">RUIM</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-info" />
            <span class="text-xs font-semibold">BOM</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-accent" />
            <span class="text-xs font-semibold">ÓTIMO</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <input type="radio" class="radio radio-success" />
            <span class="text-xs font-semibold">EXCELENTE</span>
          </div>
        </div>
      </fieldset> 
    </div>
  </div>

  <!-- ASSINATURA -->
  <div class="mt-10">
    <div class="flex justify-between">
      <div class="w-1/2 pr-4">
        <div class="border-t border-gray-400 pt-2 text-center text-sm">
          Assinatura
        </div>
      </div>
      <div class="w-1/2 pl-4">
        <div class="border-t border-gray-400 pt-2 text-center text-sm">
          Responsável
        </div>
      </div>
    </div>
  </div>
</div>
<label for="my_modal_6" class="btn">open modal</label>
<?php

            break;  
            default:
                
        }
        ?> 
            </main>

            <footer class="footer p-2 flex flex-row justify-between">
                 <?php include("padrinho/layout/footer.php"); ?>
            </footer>
        </div> 

        
    </div>

<script src="assets/js/global.js?v=1.2"></script>
<input type="checkbox" id="my_modal_6" class="modal-toggle" />
<div class="modal" role="dialog">
  <div class="modal-box">
    <h3 class="text-lg font-bold">Hello!</h3>
    <p class="py-4">This modal works with a hidden checkbox!</p>
    <div class="modal-action">
      <label for="my_modal_6" class="btn">Close!</label>
    </div>
  </div>
</div>
</body>
</html>