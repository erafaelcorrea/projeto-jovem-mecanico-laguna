<?php
$padrinho_id = decrypt($_GET['origem']);
$padrinho = GlobalModel::retornarUmObjeto("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM padrinhos WHERE id = {$padrinho_id}");
$tem_afilhado_no_momento = GlobalModel::retornarUmObjeto("SELECT b.nome, b.id FROM batizado a INNER JOIN afilhados b WHERE a.afilhado = b.id AND a.padrinho = {$padrinho_id} AND a.fim >= CURDATE()");
if (!$padrinho->id) {
    include('404.php');
} else {
?>
<div class="grid grid-cols-1 lg:grid-cols-1 gap-4">
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
    <!---header--card--->
    <div class="flex justify-between">
        
        <div class="flex flex-col gap-2">
            <div class="avatar indicator">
                <span class="indicator-item badge badge-xs badge-neutral">PADRINHO</span>
                <div class="w-24 rounded-full">
                    <img src="<?= getFotoPadrinho($padrinho->id) ?>" />
                </div>
            </div>
            <a href="index.php?pagina=editar-padrinho&origem=<?= encrypt($padrinho_id); ?>" class="btn btn-neutral btn-xs">EDITAR</a>
        </div>
        
        <div class="flex flex-col gap-1 items-center">
            <?php if (!$tem_afilhado_no_momento->id): ?>
                <span class="badge badge-ghost font-semibold">Sem afilhado no momento<i class="fa fa-toggle-off ml-1" aria-hidden="true"></i></span>
                <a href="index.php?pagina=vincular-afilhado&origem=<?= encrypt($padrinho_id); ?>" class="btn btn-xs btn-neutral font-bold mt-2">
                    VINCULAR AFILHADO <i class="fa fa-link" aria-hidden="true"></i>
                </a>
            <?php else: ?>
                <span class="badge badge-success font-semibold">Com afilhado no momento<i class="fa fa-toggle-on ml-1" aria-hidden="true"></i></span>
                <div class="flex mt-2 items-center gap-2">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="<?= getFotoAfilhado($tem_afilhado_no_momento->id) ?>" />
                        </div>
                    </div>
                    <span class="text-xs"><?= $tem_afilhado_no_momento->nome; ?></span>
                </div>
            <?php endif; ?> 
        </div>

    </div>
    <!---fim---header--card--->
    <div class="divider">Informações</div>
    <!----info---padrinho---->
    <div class="flex flex-col">
        <span class="text-xs text-gray-500">NOME:</span>
        <span class="ml-2 text-lg"><?= $padrinho->nome; ?></span>
        <span class="text-xs text-gray-500 mt-2">ÚLTIMO ACESSO:</span>
        <span class="text-lg ml-2"><?= $padrinho->ultimo_acesso; ?></span>
    </div>
    <!----fim---info---padrinho---->
    <div class="divider">Afilhados</div>
    <!----afilhados--do--padrinho---->
    <?php
    $batizados = GlobalModel::retornarUmaLista("SELECT id, afilhado, DATE_FORMAT(inicio, '%d/%m/%Y') as inicio, DATE_FORMAT(fim, '%d/%m/%Y') as fim FROM batizado WHERE padrinho = {$padrinho_id} ORDER BY fim DESC");
    ?>
    <div class="mt-4">
        <table class="table min-w-full">
            <thead>
            <tr>
                <th class="w-8"></th>
                <th class="">Nome do Afilhado</th>
                <th class="w-50 text-center">Data de Início</th>
                <th class="w-50 text-center">Data Fim</th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações criadas para o padrinho com esse afilhado">Avaliações <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações em aberto">Pendentes <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações aguardando data de liberação">Bloqueadas <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações realizadas">Realizadas <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($batizados as $index => $batizado): ?>
                    <tr>
                    <td>
                        <div class="avatar indicator"><span class="indicator-item badge badge-sm badge-neutral"><?= $index+1; ?></span><div class="w-12 rounded-full"><img src="<?= getFotoAfilhado($batizado['afilhado']) ?>" /></div></div>
                    </td>
                    <td><?= GlobalModel::retornarUmValor("SELECT nome FROM afilhados WHERE id = {$batizado['afilhado']}"); ?></td>
                    <td class="text-center"><?= $batizado['inicio']; ?></td>
                    <td class="text-center"><?= $batizado['fim']; ?></td>
                    <td class="text-center"><badge class="badge badge-info w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'padrinho' AND padrinho_id = {$padrinho_id} AND afilhado_id = {$batizado['afilhado']}") ?></badge></td>
                    <td class="text-center"><badge class="badge badge-warning w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'padrinho' AND padrinho_id = {$padrinho_id} AND afilhado_id = {$batizado['afilhado']} AND liberar <= CURDATE() AND realizada IS NULL") ?></badge></td>
                    <td class="text-center"><badge class="badge badge-neutral w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'padrinho' AND padrinho_id = {$padrinho_id} AND afilhado_id = {$batizado['afilhado']} AND liberar > CURDATE()") ?></badge></td>
                    <td class="text-center"><badge class="badge badge-accent w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'padrinho' AND padrinho_id = {$padrinho_id} AND afilhado_id = {$batizado['afilhado']} AND realizada IS NOT NULL") ?></badge></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!----fim---afilhados--do--padrinho---->
    </div>
</div>
</div>
<?php } ?>