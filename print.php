<?php
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/api/functions.php';
require_once __DIR__ . '/api/bootstrap.php';
$perguntas = GlobalModel::retornarUmaLista("SELECT * FROM perguntas WHERE perfil = 'padrinho' ORDER BY modo DESC");

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
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
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
            .pergunta {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .quebra {
                page-break-before: always;
                break-before: page;
            }
        }
    </style>
</head>
<body>

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

  <?php foreach ($perguntas as $index => $pergunta): ?>
  <?php
    if ($index === 3 || $index === 7) { $q = "quebra"; } else { $q = "pergunta"; }
  ?>
  <!-- PERGUNTA 1 -->
  <div class="card bg-base-100 shadow-sm border mb-4 <?= $q ?>">
    <div class="card-body relative">
      <span class="absolute top-1 left-3 text-2xl text-gray-300">PERGUNTA #<?= ($index + 1) ?></span> 

      <?php if ($pergunta['modo'] === 'multipla-escolha'): ?>   
        <fieldset class="fieldset mt-4">
          <legend class="fieldset-legend font-semibold text-wrap text-sm text-black">
            <?= $pergunta['pergunta'] ?>
          </legend> 
          <span class="label text-sm italic mt-2 text-wrap text-black">
            <?= $pergunta['descricao'] ?>
          </span> 
          <div class="grid grid-cols-5 gap-3 p-2 mt-2">
            <div class="flex flex-col items-center gap-2">
              <input type="radio" class="radio radio-neutral" />
              <span class="text-xs font-semibold">PÉSSIMO</span>
            </div>
            <div class="flex flex-col items-center gap-2">
              <input type="radio" class="radio radio-neutral" />
              <span class="text-xs font-semibold">RUIM</span>
            </div>
            <div class="flex flex-col items-center gap-2">
              <input type="radio" class="radio radio-neutral" />
              <span class="text-xs font-semibold">BOM</span>
            </div>
            <div class="flex flex-col items-center gap-2">
              <input type="radio" class="radio radio-neutral" />
              <span class="text-xs font-semibold">ÓTIMO</span>
            </div>
            <div class="flex flex-col items-center gap-2">
              <input type="radio" class="radio radio-neutral" />
              <span class="text-xs font-semibold">EXCELENTE</span>
            </div>
          </div>
        </fieldset> 
      <?php else: ?>
        <fieldset class="fieldset mt-4">
          <legend class="fieldset-legend font-semibold text-wrap text-sm text-black">
            <?= $pergunta['pergunta'] ?>
          </legend> 
          <span class="label text-sm italic mt-2 text-wrap text-black">
            <?= $pergunta['descricao'] ?>
          </span> 
          <textarea name="resposta" class="textarea h-80 w-full textarea-bordered border"></textarea>
        </fieldset>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  

  <!-- ASSINATURA -->
  <div class="mt-10">
    <div class="pr-4 pt-10 w-full">
      <div class="border-t border-gray-400 pt-2 text-center text-sm">
        Assinatura
      </div>
    </div>
  </div>
</div>
           
</body>
</html>