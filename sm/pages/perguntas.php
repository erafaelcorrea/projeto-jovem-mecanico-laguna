<?php
$perguntas = AvaliacaoModel::listarPerguntas();
?>
<div class="flex w-full p-2 justify-end gap-2">
    <a href="index.php?pagina=adicionar-pergunta" class="btn btn-neutral">
        <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Pergunta
    </a>
</div>
<div class="overflow-x-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full table-auto">
    <!-- head -->
    <thead>
      <tr>
        <th class="w-8"></th>
        <th class="w-auto">Pergunta</th>
        <th class="w-auto">Descrição</th>
        <th class="w-40">Modelo</th>
        <th class="w-20">Perfil</th>
        <th class="w-50">Categoria</th>
        <th class="w-10">Alterar</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($perguntas as $index => $pergunta): ?>
            <tr>
                <td><?= $index+1; ?></td>
                <td><?= $pergunta['pergunta']; ?></td>
                <td><?= $pergunta['descricao']; ?></td>
                <td><span class="badge badge-<?= $pergunta['modo'] === 'dissertativa' ? 'info' : 'success' ?> badge-xs p-2"><?= $pergunta['modo']; ?></span></td>
                <td><span class="badge badge-<?php if($pergunta['perfil'] === 'afilhado') { echo 'warning'; } elseif($pergunta['perfil'] === 'padrinho') { echo 'error'; } else { echo 'neutral'; } ?> badge-xs p-2"><?= $pergunta['perfil']; ?></span></td>
                <td><span class="badge badge-neutral badge-xs p-2"><?= $pergunta['categoria']; ?></span></td>
                <td>
                    <a href="index.php?pagina=editar-pergunta&origem=<?= encrypt($pergunta['id']); ?>" class="btn btn-xs btn-neutral">
                        <i class="fa fa-edit" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>