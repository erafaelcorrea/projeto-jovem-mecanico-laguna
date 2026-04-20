<?php
$padrinhos = GlobalModel::retornarUmaLista("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM padrinhos ORDER BY nome");
?>
<div class="flex w-full p-2 justify-end">
    <a href="index.php?pagina=adicionar-padrinho" class="btn btn-neutral">
        <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Padrinho
    </a>
</div>
<div class="overflow-x-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full table-auto">
    <!-- head -->
    <thead>
      <tr>
        <th class="w-8"></th>
        <th class="w-auto">Padrinhos</th>
        <th class="w-20">Afilhado no momento</th>
        <th class="w-20">Total de Afilhados</th>
        <th class="w-20">Avaliações Pendentes</th>
        <th class="w-40">Último acesso</th>
        <th class="w-20">Visualizar</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($padrinhos as $index => $padrinho): ?>
            <tr>
                <?php
                    $tem_afilhado_no_momento = GlobalModel::retornarUmObjeto("SELECT b.nome, b.id FROM batizado a INNER JOIN afilhados b WHERE a.afilhado = b.id AND a.padrinho = {$padrinho['id']} AND a.fim >= CURDATE()");
                    $total_avaliacoes_pendentes = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'padrinho' AND padrinho_id = {$padrinho['id']} AND realizada IS NULL AND liberar <= CURDATE()");
                    $total_afilhados = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM batizado WHERE padrinho = {$padrinho['id']}");
                ?>
                <td>
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="<?= getFotoPadrinho($padrinho['id']) ?>" />
                        </div>
                    </div>
                </td>
                <td><?= $padrinho['nome']; ?></td>
                <td class="text-center">
                    <?php if ($tem_afilhado_no_momento->id): ?>
                        <div class="flex flex-col gap-1 items-center">
                            <div class="avatar"><div class="w-8"><img src="<?= getFotoAfilhado($tem_afilhado_no_momento->id) ?>" /></div></div>
                            <span class="ml-2 text-xs text-center"><?= $tem_afilhado_no_momento->nome ?></span>
                        </div>
                    <?php else: ?>
                        <a href="index.php?pagina=vincular-afilhado&origem=<?= encrypt($padrinho['id']) ?>" class="btn btn-neutral btn-xs">VINCULAR</a>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="avatar-group -space-x-6">
                        <div class="avatar"><div class="w-12"><img src="../assets/img/user-img.png" /></div></div>
                        <div class="avatar avatar-placeholder">
                            <span class="bg-neutral rounded-full w-12 h-12 text-white text-lg py-2 text-center">
                                <?= $total_afilhados; ?>
                            </span>
                        </div>
                    </div>
                </td>
                <td class="text-center"><span class="badge badge-warning rounded-full h-8 w-8"><?= $total_avaliacoes_pendentes; ?></span></td>
                <td class="text-xs"><?= $padrinho['ultimo_acesso']; ?></td>
                <td><a href="index.php?pagina=padrinho&origem=<?= encrypt($padrinho['id']); ?>" class="btn btn-neutral btn-sm"><i class="fas fa-vcard"></i></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>