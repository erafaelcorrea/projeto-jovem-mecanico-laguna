<?php
$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM afilhados ORDER BY nome");
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

  <?php foreach ($afilhados as $index => $afilhado): ?>
  <div class="card card-side bg-base-100 shadow-sm">
    <figure>
      <img src="<?= getFotoAfilhado($afilhado['id']) ?>" />
    </figure>
    <div class="card-body">
      <span class="text-xs text-gray-600 font-semibold">DESEMPENHO GERAL</span>
      <div class="flex justify-between gap-4">

        <div class="flex flex-col gap-2">
          <div class="w-20 h-20 radial-progress bg-neutral text-neutral-content border-neutral border-4" style="--value:70; --thickness: 3px;" aria-valuenow="70" role="progressbar">
            0%
          </div>
          <div class="text-xs"><i class="fa fa-info-circle"></i> PADRINHOS</div>
        </div>

        <div class="flex flex-col gap-2">
          <div class="w-20 h-20 radial-progress bg-neutral text-neutral-content border-neutral border-4" style="--value:70; --thickness: 3px;" aria-valuenow="70" role="progressbar">
            0%
          </div>
          <div class="text-xs"><i class="fa fa-info-circle"></i> INSTRUTOR</div>
        </div>

        <div class="flex flex-col gap-2">
          <div class="w-20 h-20 radial-progress bg-neutral text-neutral-content border-neutral border-4" style="--value:70; --thickness: 3px;" aria-valuenow="70" role="progressbar">
            0%
          </div>
          <div class="text-xs"><i class="fa fa-info-circle"></i> CONTEÚDO</div>
        </div>
        

      </div>
    </div>
  </div>
  <?php endforeach; ?>

</div> 