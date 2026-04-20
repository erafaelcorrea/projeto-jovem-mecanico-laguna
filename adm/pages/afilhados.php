<?php
$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM afilhados ORDER BY nome");
?>
<div class="w-full flex justify-between p-4">
    <span class="text-2xl">JOVENS MECÂNICOS</span>
    <a href="index.php?pagina=padrinhos" class="btn btn-neutral btn-sm">PADRINHOS <i class="fa fa-mail-forward"></i></a>
</div>

<div class="overflow-x-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full table-auto">
    <!-- head -->
    <thead>
      <tr>
        <th class="w-8"></th>
        <th class="w-auto">Jovens Mecânicos</th>
        <th class="w-20">Padrinho no momento</th>
        <th class="w-20">Total de Padrinhos</th>
        <th class="w-20">Avaliações Pendentes</th>
        <th class="w-40">Último acesso</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($afilhados as $index => $afilhado): ?>
            <tr class="hover:bg-gray-100">
                <?php
                    $tem_padrinho_no_momento = GlobalModel::retornarUmObjeto("SELECT b.nome, b.id FROM batizado a INNER JOIN padrinhos b WHERE a.padrinho = b.id AND a.afilhado = {$afilhado['id']} AND a.fim >= CURDATE()");
                    $total_avaliacoes_pendentes = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado['id']} AND realizada IS NULL AND liberar <= CURDATE()");
                    $total_padrinhos = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM batizado WHERE afilhado = {$afilhado['id']}");
                ?>
                <td> 
                    <div class="avatar">
                        <div class="w-12 rounded-full hover:border hover:border-4 hover:border-yellow-400">
                            <a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado['id']); ?>"><img src="<?= getFotoAfilhado($afilhado['id']) ?>" /></a>
                        </div>
                    </div>
                </td>
                <td><?= $afilhado['nome']; ?></td>
                <td class="text-center">
                    <?php if ($tem_padrinho_no_momento->id): ?>
                        <div class="flex flex-col gap-1 items-center">
                            <div class="avatar"><div class="w-8 h-8 rounded-full hover:border hover:border-3 hover:border-yellow-400"><a href="index.php?pagina=padrinho&origem=<?= encrypt($tem_padrinho_no_momento->id); ?>"><img src="<?= getFotoPadrinho($tem_padrinho_no_momento->id) ?>" /></a></div></div>
                            <span class="ml-2 text-xs text-center"><?= $tem_padrinho_no_momento->nome ?></span>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="avatar-group -space-x-6">
                        <div class="avatar"><div class="w-12"><img src="../assets/img/user-img.png" /></div></div>
                        <div class="avatar avatar-placeholder">
                            <span class="bg-neutral rounded-full w-12 h-12 text-white text-lg py-2 text-center"><?= $total_padrinhos; ?></span>
                        </div>
                    </div>
                </td>
                <td class="text-center"><span class="badge badge-warning rounded-full h-8 w-8"><?= $total_avaliacoes_pendentes; ?></span></td>
                <td class="text-xs"><?= $afilhado['ultimo_acesso']; ?></td>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>