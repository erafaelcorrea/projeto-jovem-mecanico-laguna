<?php
$avaliacao_id = decrypt($_GET['origem']);

$verificacao = new stdClass();
$verificacao->erro = false;
$verificacao->finalizada = false;
$verificacao->msg = '';


/*
Tabelas:
tabela avaliacao -> id|quem_avalia|padrinho_id|afilhado_id|liberar|realizada|perguntas
tablela batizado -> id|inicio|fim|status|padrinho|afilhado|padrinho_avaliou_data|afilhado_avaliou_data
*/

// 1 - Ver se tem avaliação criada para o padrinho
$avaliacao = retornarUmObjeto("SELECT * FROM avaliacao WHERE id = {$avaliacao_id}");

if ($avaliacao) {
  $hoje = new DateTime();
  //verificar se a data liberar é igual ou já venceu para avaliar
  if($avaliacao['liberar'] <= $hoje->format('Y-m-d')) {

    //pegar nome do afilhado
    $afilhado = retornarUmObjeto("SELECT id, nome from afilhados WHERE id = {$avaliacao['afilhado_id']}");
    //pegar id das perguntas cadastradas para essa avaliação
    $perguntas_id = array_map('intval', explode(",", $avaliacao['perguntas']));
    //calcular quantas perguntas existem no total
    $total_perguntas = count($perguntas_id);

    //buscar se tem respostas para essa avaliação e dar percentual do progresso
    $respostas_id = retornarLista("SELECT pergunta FROM respostas WHERE avaliacao = {$avaliacao['id']}");
    $total_respostas = count($respostas_id);
    $progresso = ($total_respostas > 0 && $total_perguntas > 0) ? round(($total_respostas / $total_perguntas) * 100, 2) : 0;

    //se encontrou alguma resposta na tabela respostas dessa avaliação
    if($total_respostas > 0) {
      //encontrou 1 ou mais respostas e vai remover o id das perguntas que já foram respondidas
      $respondidas = array_column($respostas_id, 'pergunta'); //converteu o array para (1,2,3,4)
      $nao_respondidas = array_diff($perguntas_id, $respondidas); //criou um array tirando de perguntas_id as respondidas
      
    } else {
      //nenhum resposta ainda
      $respondidas = [];
      $nao_respondidas = $perguntas_id;
      
    }

    if(count($nao_respondidas) > 0) {
      $i = array_key_first($nao_respondidas);
      $pergunta_atual_id = $nao_respondidas[$i];
      $pergunta_atual = retornarUmObjeto("SELECT * FROM perguntas WHERE id={$pergunta_atual_id}");
    } else {
      //todas perguntas foram respondidas finalizar a avaliação
      if($total_perguntas === count($respondidas)) {
        $atualizar_padrinho_avaliou_data = atualizarBanco("UPDATE avaliacao SET realizada = NOW() WHERE id = {$avaliacao['id']}");
      } 
      $padrinho_avaliou_data = retornarUmValor("SELECT DATE_FORMAT(realizada, '%d/%m/%Y') as realizada FROM avaliacao WHERE id = {$avaliacao['id']}");
      $verificacao->finalizada = true;
    }

  } else {
    $verificacao->erro = true;
    $verificacao->msg = 'Avaliação não foi liberada!';
  }

} else {
  $verificacao->erro = true;
  $verificacao->msg = 'Avaliação não encontrada!';
}

?>
<?php if ($verificacao->erro): ?>
<script> window.location.href = "index.php?pagina=avaliacoes&status=error&msg=<?= $verificacao->msg ?>"; </script>
<?php else: ?>
<div class="grid gap-6">
  <!-- 🟢 LINHA 1   ------->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="card bg-base-100 shadow-sm">
      <div class="card-body">
        <div class="flex gap-6 items-center">
          <div class="avatar indicator">
            <span class="indicator-item badge badge-xs badge-neutral">AFILHADO</span>
            <div class="w-16 rounded-full">
              <img src="<?= getFotoAfilhado($afilhado['id']) ?>" />
            </div>
          </div>
          <div class="ml-6 flex flex-col gap-1"> 
            <span class="text-xs font-semibold"><i class="fa fa-pencil" aria-hidden="true"></i> AVALIAÇÃO DE DESEMPENHO</span>
            <span class="text-lg font-thin"><?= $afilhado['nome']; ?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="card bg-base-100 shadow-sm">
      <div class="card-body relative">
        <span class="absolute top-1 left-3 text-1xl text-gray-300">PERGUNTAS CONCLUÍDAS</span>
        <div class="flex w-full">
          <div class="w-1/2 flex flex-col justify-center items-center">
            <div class="flex gap-6">
              <div class="text-7xl font-bold"><?= count($respondidas); ?></div>
              <div class="text-xs font-bold text-gray-400 flex items-center">DE</div>
              <div class="text-7xl font-bold"><?= $total_perguntas; ?></div>
            </div>
          </div>
          <div class="w-1/2 flex justify-center">
            <div class="radial-progress bg-neutral text-neutral-content border-neutral border-4" style="--value:<?= $progresso; ?>;" aria-valuenow="<?= $progresso; ?>" role="progressbar"><?= $progresso; ?>%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="grid mt-2">
  <?php if (!$verificacao->finalizada): ?>
    <form method="POST" accept-charset="UTF-8" action="index.php?pagina=responder&origem=<?= encrypt($avaliacao['id']) ?>"> 
      <input type="hidden" name="avaliacao_id" value="<?= $avaliacao['id']; ?>">
      <input type="hidden" name="pergunta_atual_id" value="<?= $pergunta_atual_id; ?>">
      <!-- 🟢 LINHA 1   ------->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="card bg-base-100 shadow-sm">
          <div class="card-body relative">
            <span class="absolute top-1 left-3 text-2xl text-gray-300">PERGUNTA #<?= $total_respostas+1; ?></span>    
            <fieldset class="fieldset mt-4">
                <legend class="fieldset-legend font-semibold"><?= htmlspecialchars($pergunta_atual['pergunta']) ?></legend>
                <span class="label text-sm italic mt-2"><?= htmlspecialchars($pergunta_atual['descricao']) ?></span>
                <div class="flex justify-between gap-3 p-2 mb-2 mt-2">
                  <div class="flex flex-1 flex-col items-center gap-2">
                    <input type="radio" name="resposta" value="1"  class="radio radio-error" /> 
                    <span class="text-xs font-semibold">PÉSSIMO</span>
                  </div>
                  <div class="flex flex-1 flex-col items-center gap-2">
                    <input type="radio" name="resposta" value="2" class="radio radio-warning" />
                    <span class="text-xs font-semibold">RUIM</span>
                  </div>
                  <div class="flex flex-1 flex-col items-center gap-2">
                    <input type="radio" name="resposta" value="3" class="radio radio-info" />
                    <span class="text-xs font-semibold">BOM</span>
                  </div>
                  <div class="flex flex-1 flex-col items-center gap-2">
                    <input type="radio" name="resposta" value="4" class="radio radio-accent" />
                    <span class="text-xs font-semibold">ÓTIMO</span>
                  </div>
                  <div class="flex flex-1 flex-col items-center gap-2">
                    <input type="radio" name="resposta" value="5" checked="checked" class="radio radio-success" />
                    <span class="text-xs font-semibold">EXCELENTE</span>
                  </div>
                </div>
            </fieldset> 
          </div>
        </div>
        <div class="mt-4">
            <span class="text-md font-semibold">AO AVALIAR CONSIDERE SER:</span>
            <span class="rotate-container text-lg font-semibold">
              <span class="rotate-text">
                <span>VERDADEIRO</span>
                <span>JUSTO</span>
                <span>CRITERIOSO</span>
              </span>
            </span>
        </div>
        <button class="btn btn-neutral">ENVIAR RESPOSTA <i class="fa fa-mail-forward" aria-hidden="true"></i></button>
      </div>
    </form>
  <?php else: ?>
    <div class="card bg-base-100 shadow-sm">
      <div class="card-body">
        <div class="flex">
          <div class="flex flex-grow flex-col">
            <span class="text-xs font-semibold">AVALIAÇÃO REALIZADA EM: </span>
            <span class="text-2xl font-thin"><?= $padrinho_avaliou_data; ?></span>
          </div>
          <div>
            <a class="btn btn-neutral" href="index.php?pagina=bem-vindo"><i class="fa fa-mail-forward" aria-hidden="true"></i> VOLTAR</a>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

</div>
<?php endif; ?>