<?php
  $categorias_perguntas = [
      ["tag" => "pontualidade", "label" => "Pontualidade"],
      ["tag" => "organizacao-limpeza", "label" => "Organização e Limpeza"],
      ["tag" => "produtividade", "label" => "Produtividade"],
      ["tag" => "proatividade", "label" => "Proatividade"],
      ["tag" => "conhecimento", "label" => "Conhecimento"],
      ["tag" => "seguranca", "label" => "Segurança"],
      ["tag" => "habilidade", "label" => "Habilidade"],
      ["tag" => "desempenho", "label" => "Desempenho"],
  ];
  $map = array_column($categorias_perguntas, 'label', 'tag');
  $perguntas_padrinho = GlobalModel::retornarUmaLista("SELECT id, pergunta, descricao, categoria, modo, perfil FROM perguntas WHERE perfil = 'padrinho'");
  $perguntas_afilhado = GlobalModel::retornarUmaLista("SELECT id, pergunta, descricao, categoria, modo, perfil FROM perguntas WHERE perfil = 'afilhado'");
?>
<div class="grid grid-cols-1 gap-6">
  <div class="rounded-box bg-base-100 shadow-xl rounded-lg py-4 flex flex-col gap-2 p-4">
    <span class="text-sm">UMA PERGUNTA DE MÚLTIPLA ESCOLHA PARA CADA ÁREA ABAIXO:</span>
    <div class="flex gap-2 flex-wrap ml-4">
      <?php foreach ($categorias_perguntas as $index => $cat): ?>
        <span class="badge badge-success text-xs font-semibold"><?= $cat['label'] ?></span>
      <?php endforeach; ?>   
    </div>
    <span class="text-sm">UMA PERGUNTA DISSERTATIVA PARA ÁREA ABAIXO:</span>
    <span class="badge badge-success text-xs font-semibold ml-4">Desempenho</span>
  </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

  <div class="max-h-150 overflow-y-auto rounded-box bg-base-100 shadow-xl rounded-lg py-4">
    <ul class="list bg-base-100">
      <li class="p-4 pb-2 text-lg tracking-wide text-sm">Perguntas direcionadas para o Padrinho responder:</li>
      <?php foreach ($perguntas_padrinho as $index => $pc): ?>
      <li class="list-row">
        <div class="text-4xl font-thin opacity-30 tabular-nums"><?= ($index + 1) ?></div>
        <div class="list-col-grow">
          <div class="mb-1"><?= $pc['pergunta'] ?></div>
          <div class="text-xs font-semibold opacity-60"><?= $pc['descricao'] ?></div>
          <div class="flex gap-2 p-2">
            <span class="badge badge-neutral text-xs font-semibold"><?= $pc['modo'] ?></span>
            <span class="badge badge-success text-xs font-semibold"><?= $map[$pc['categoria']] ?></span>
            <span class="badge badge-warning text-xs font-semibold"><?= $pc['perfil'] ?></span>
          </div>
        </div>
        <a class="btn btn-square btn-ghost" href="index.php?pagina=editar-pergunta&origem=<?= encrypt($pc['id']) ?>">
          <i class="fa fa-pencil"></i>
        </a>
      </li>
      <?php endforeach; ?>   
    </ul>
  </div>

  <div class="max-h-150 overflow-y-auto rounded-box bg-base-100 shadow-xl rounded-lg py-4">
    <ul class="list bg-base-100">
      <li class="p-4 pb-2 text-lg tracking-wide text-sm">Perguntas direcionadas para o Afilhado responder:</li>
      <?php foreach ($perguntas_afilhado as $index => $pc): ?>
      <li class="list-row">
        <div class="text-4xl font-thin opacity-30 tabular-nums"><?= ($index + 1) ?></div>
        <div class="list-col-grow">
          <div class="mb-1"><?= $pc['pergunta'] ?></div>
          <div class="text-xs font-semibold opacity-60"><?= $pc['descricao'] ?></div>
          <div class="flex gap-2 p-2">
            <span class="badge badge-neutral text-xs font-semibold"><?= $pc['modo'] ?></span>
            <span class="badge badge-success text-xs font-semibold"><?= $map[$pc['categoria']] ?></span>
            <span class="badge badge-warning text-xs font-semibold"><?= $pc['perfil'] ?></span>
          </div>
        </div>
        <a class="btn btn-square btn-ghost" href="index.php?pagina=editar-pergunta&origem=<?= encrypt($pc['id']) ?>">
          <i class="fa fa-pencil"></i>
        </a>
      </li>
      <?php endforeach; ?>   
    </ul>
  </div>

</div>