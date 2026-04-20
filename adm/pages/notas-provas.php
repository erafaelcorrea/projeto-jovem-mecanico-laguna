<?php
$afilhado_id = decrypt($_GET['origem']);
$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM afilhados ORDER BY nome");

$conteudos_id = explode(',', GlobalModel::retornarUmValor("SELECT conteudos FROM instrutor WHERE id = 1"));
$ids = implode(',', array_map('intval', $conteudos_id));
$result = GlobalModel::retornarUmaLista("SELECT id, conteudo FROM conteudos WHERE id IN ($ids)");
$conteudos = [];
foreach ($result as $row) {
    $conteudos[] = [
        'id' => $row['id'],
        'conteudo' => $row['conteudo']
    ];
}
?>
<div class="w-full flex justify-between p-4">
    <span class="text-2xl">JOVENS MECÂNICOS</span>
    <a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado_id) ?>" class="btn btn-neutral btn-sm"><i class="fa fa-mail-reply"></i> VOLTAR</a>
</div>
<div class="max-h-150 overflow-y-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full">
    <!-- head -->
    <thead class="sticky top-0 bg-base-100 z-10">
      <tr>
        <th class="w-8"></th>
        <th class="w-auto">Alunos</th>
        <?php foreach ($conteudos as $index => $conteudo): ?>
            <th class="w-15 text-wrap text-center text-xs"><?= $conteudo['conteudo'] ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($afilhados as $index => $afilhado): ?>
            <tr id="TR<?= $afilhado['id'] ?>" class="scroll-mt-20 hover:bg-gray-100 <?= ($afilhado['id'] == $afilhado_id) ? 'bg-yellow-200 font-semibold' : 'hover:bg-gray-100' ?>">
                <?php
                    $tem_padrinho_no_momento = GlobalModel::retornarUmObjeto("SELECT b.nome, b.id FROM batizado a INNER JOIN padrinhos b WHERE a.padrinho = b.id AND a.afilhado = {$afilhado['id']} AND a.fim >= CURDATE()");
                    $total_avaliacoes_pendentes = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado['id']} AND realizada IS NULL AND liberar <= CURDATE()");
                    $total_padrinhos = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM batizado WHERE afilhado = {$afilhado['id']}");
                ?>
                <td>
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado['id']); ?>"><img src="<?= getFotoAfilhado($afilhado['id']) ?>" /></a>
                        </div>
                    </div>
                </td>
                <td><a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado['id']); ?>"><?= $afilhado['nome']; ?></a></td>
                <?php foreach ($conteudos as $index => $conteudo): ?>
                    <td class="w-15 text-center">
                    <button>
                        <?php 
                            $nota = GlobalModel::retornarUmValor("SELECT nota FROM notas_instrutor WHERE conteudo = {$conteudo['id']} AND aluno = {$afilhado['id']}"); 
                            if ($nota) { echo $nota; } else { echo 0; }
                        ?>
                    </button>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>
