<?php
  $afilhado_id = decrypt($_GET['origem']);
  $afilhados = GlobalModel::retornarUmaLista("SELECT id, nome FROM afilhados ORDER BY nome ASC");

    $conteudos = [
      ["PARTICIPAÇÃO"],
      ["ORGANIZAÇÃO"],
      ["TRABALHO EM EQUIPE"],
      ["PONTUALIDADE"],
      ["PROATIVIDADE"],
      ["ASSIDUIDADE"],
      ["CONHECIMENTO TEÓRICO"],
      ["DINÂMICA EM GRUPO"],
      ["DESENVOLVIMENTO TEÓRICO"],
      ["DESENVOLVIMENTO PRÁTICO"]
    ];
    //$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome, TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) AS idade, sexo, telefone FROM afilhados ORDER BY nome");
?>
<div class="w-full flex p-2">
    <a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado_id) ?>" class="btn btn-neutral btn-sm"><i class="fa fa-mail-reply"></i> VOLTAR</a>
</div>
<div class="overflow-x-auto bg-base-100 shadow-xl rounded-lg p-2">
<div class="w-full overflow-x-auto">
  <table class="table-fixed min-w-[900px] border">
    
    <colgroup>
      <col class="w-[250px]"> <!-- Nome fixo maior -->
      <col span="11" class="w-[100px]"> <!-- colunas padrão -->
    </colgroup>

    <thead class="bg-gray-100">
      <tr>
        <th class="border p-3 text-left">Nome</th>
        <?php foreach ($conteudos as $conteudo): ?>
          <th class="border p-3 text-center text-xs font-medium">
            <?= $conteudo[0] ?>
          </th>
        <?php endforeach; ?>
        <th class="border p-3 text-center text-xs font-medium">MÉDIA</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($afilhados as $afilhado): ?>
        <tr class="<?= ($afilhado['id'] == $afilhado_id) ? 'bg-yellow-200 font-semibold' : 'hover:bg-gray-100' ?>">
          <td class="border p-3 whitespace-nowrap">
            <?= $afilhado['nome'] ?>
          </td>

          <?php foreach ($conteudos as $conteudo): ?>
            <td class="border p-3 text-center">
              <!-- nota -->
            </td>
          <?php endforeach; ?>
          <td class="border p-3 text-center"></td>
        </tr>
      <?php endforeach; ?>

    </tbody>

  </table>
</div>
</div>
