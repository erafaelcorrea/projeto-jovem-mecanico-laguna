<div class="grid gap-6">
  <!-- 🟢 LINHA 1 PADRINHO DO MOMENTO   ------->
  <div class="grid grid-cols-1 lg:grid-cols-1 gap-4">
    <?php if($_SESSION['afilhado']['batizado']): ?>
    <!-- 🟢 TEM PADRINHO NO MOMENTO   ------->
    <div class="card bg-base-100 shadow-sm">
      <div class="card-body">
        <!---header--card--->
        <div class="flex justify-between">
          <div class="avatar indicator">
            <span class="indicator-item badge badge-xs badge-neutral">PADRINHO</span>
            <div class="w-16 rounded-full">
              <img src="<?= getFotoPadrinho($_SESSION['afilhado']['padrinho_id']) ?>" />
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <span class="badge badge-accent font-semibold">Em andamento <i class="fa fa-toggle-on ml-1" aria-hidden="true"></i></span>
          </div>
        </div>
        <!---fim---header--card--->
        <!----body---card---->
        <div class="flex flex-col">
          <span class="text-xs text-gray-500">NOME:</span>
          <span class="ml-2 text-lg"><?= $_SESSION['afilhado']['padrinho_nome']; ?> </span>
          <span class="text-xs text-gray-500 mt-2">PERÍODO:</span>
          <span class="text-lg ml-2"><?= $_SESSION['afilhado']['inicio']; ?> - <?= $_SESSION['afilhado']['fim']; ?></span>
        </div>
        <!----fim---body---card---->
      </div>
    </div>
    <!-- 🟢 FIM TEM PADRINHO NO MOMENTO   ------->
    <?php else: ?>
    <!-- 🟢 NÃO TEM PADRINHO NO MOMENTO   ------->    
    <div class="card bg-base-100 shadow-sm">
      <div class="card-body">
        <span class="text-1xl font-semibold"><i class="fa fa-user" aria-hidden="true"></i> SEM PADRINHO NESSE MOMENTO</span>
      </div>
    </div> 
    <?php endif; ?>     
  </div>
  <!-- 🟢 FIM DA LINHA 1   ------->
<?php
$padrinhos = GlobalModel::retornarUmaLista("SELECT 
            DATE_FORMAT(a.inicio, '%d/%m/%Y') as inicio, 
            DATE_FORMAT(a.fim, '%d/%m/%Y') as fim,
            a.padrinho as padrinho_id, 
            b.nome,
            a.id as batizado_id
        FROM batizado a  
        INNER JOIN padrinhos b 
            ON a.padrinho = b.id 
        WHERE a.afilhado = {$_SESSION['afilhado']['afilhado_id']} 
        AND a.fim < CURDATE()
        ORDER BY a.id DESC");
?>
  <!-- 🟢 LINHA 2 — Cards com conteúdo -->
   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    <?php foreach ($padrinhos as $index => $padrinho): ?>
    <div class="card relative bg-base-100 shadow-sm">
      <span class="absolute top-1 left-1 text-2xl text-gray-300">#<?= ($index+1); ?></span>
      <div class="card-body">
        
        <div class="flex justify-between">
          <div class="avatar indicator">
            <span class="indicator-item badge badge-xs badge-neutral">PADRINHO</span>
            <div class="w-16 rounded-full">
              <img src="<?= getFotoPadrinho($padrinho['padrinho_id']) ?>" />
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <?php 
              $status = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$_SESSION['afilhado']['afilhado_id']} AND padrinho_id = {$padrinho['padrinho_id']} AND liberar <= CURDATE() AND realizada IS NULL");
            ?>
            <?php if ($status > 0): ?>
              <span class="badge badge-warning font-semibold">
                Pendente
                <i class="fa fa-exclamation-circle ml-2" aria-hidden="true"></i>
              </span>
            <?php else: ?>
              <span class="badge badge-success font-semibold">          
                Finalizado
                <i class="fa fa-check ml-2" aria-hidden="true"></i>
              </span>
            <?php endif; ?>
          </div>
        </div>
        <!----body---card---->
        <div class="flex flex-col">
          <span class="text-xs text-gray-500">NOME:</span>
          <span class="ml-2 text-sm"><?= $padrinho['nome'] ?></span>
          <span class="text-xs text-gray-500 mt-2">PERÍODO:</span>
          <span class="text-sm ml-2"><?= $padrinho['inicio'] ?> - <?= $padrinho['fim'] ?></span>
        </div>
        <!----fim---body---card---->
      </div>
    </div>
    <?php endforeach; ?>

  </div>
  <!-- 🟢 FIM DA LINHA 2   ------->
</div>