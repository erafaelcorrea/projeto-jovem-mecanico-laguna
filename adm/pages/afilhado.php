<?php
$afilhado_id = decrypt($_GET['origem']);
$afilhado = GlobalModel::RetornarUmObjeto("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM afilhados WHERE id = {$afilhado_id}");
$tem_padrinho_no_momento = GlobalModel::RetornarUmObjeto("SELECT b.nome, b.id, DATE_FORMAT(a.inicio, '%d/%m/%Y') as inicio, DATE_FORMAT(a.fim, '%d/%m/%Y') as fim  FROM batizado a INNER JOIN padrinhos b WHERE a.padrinho = b.id AND a.afilhado = {$afilhado_id} AND a.fim >= CURDATE()");
if (!$afilhado) {
    include('404.php');
} else {
?>
<div class="w-full flex justify-between p-4">
    <span class="text-2xl">JOVEM MECÂNICO</span>
    <a href="index.php?pagina=afilhados" class="btn btn-neutral btn-sm">VOLTAR <i class="fa fa-mail-reply"></i></a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-1 gap-4">
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
    <!---header--card--->
    <div class="flex justify-between">
        
        <div class="flex flex-col gap-2">
            <div class="avatar indicator">
                <span class="indicator-item badge badge-xs badge-neutral">AFILHADO</span>
                <div class="w-24 rounded-full">
                    <img src="<?= getFotoAfilhado($afilhado->id) ?>" />
                </div>
            </div>
        </div>
        
        <div class="flex flex-col gap-1 items-center">
            <?php if (!$tem_padrinho_no_momento): ?>
                <span class="badge badge-ghost font-semibold">Sem padrinho no momento<i class="fa fa-toggle-off ml-1" aria-hidden="true"></i></span>
            <?php else: ?>
                <div class="flex mt-2 items-center gap-2">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="<?= getFotoPadrinho($tem_padrinho_no_momento->id) ?>" />
                        </div>
                    </div>
                    <span class="text-xs"><?= $tem_padrinho_no_momento->nome; ?></span>
                </div>
                <div class="text-xs mb-1">DE <?= $tem_padrinho_no_momento->inicio; ?> ATÉ <?= $tem_padrinho_no_momento->fim; ?></div>
                <span class="badge badge-success font-semibold">Com padrinho no momento<i class="fa fa-toggle-on ml-1" aria-hidden="true"></i></span>
            <?php endif; ?> 
        </div>

    </div>
    <!---fim---header--card--->
    <div class="divider text-2xl">Informações</div>
    <!----info---afilhado---->
    <div class="flex flex-col">
        <span class="text-xs text-gray-500">NOME:</span>
        <span class="ml-2 text-lg"><?= $afilhado->nome; ?></span>
        <span class="text-xs text-gray-500 mt-2">ÚLTIMO ACESSO:</span>
        <span class="text-lg ml-2"><?= $afilhado->ultimo_acesso; ?></span>
    </div>
    <!----fim---info---afilhado---->
    <div class="divider text-2xl">Padrinhos</div>
    <!----padrinhos do afilhado---->
    <?php
    $batizados = GlobalModel::retornarUmaLista("SELECT id, padrinho, DATE_FORMAT(inicio, '%d/%m/%Y') as inicio, DATE_FORMAT(fim, '%d/%m/%Y') as fim FROM batizado WHERE afilhado = {$afilhado_id} ORDER BY fim DESC");
    ?>
    <div class="mt-4">
        <table class="table min-w-full">
            <thead>
            <tr>
                <th class="w-8"></th>
                <th class="">Nome do Padrinho</th>
                <th class="w-50 text-center">Data de Início</th>
                <th class="w-50 text-center">Data Fim</th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações criadas para o afilhado com esse padrinho">Avaliações <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações em aberto">Pendentes <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações aguardando data de liberação">Bloqueadas <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
                <th class="w-50 text-center"><div class="tooltip" data-tip="Total de Avaliações realizadas">Realizadas <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($batizados as $index => $batizado): ?>
                    <tr>
                    <td>
                        <div class="avatar indicator">
                            <span class="indicator-item badge badge-sm badge-neutral w-6 h-6 rounded-full"><?= $index+1; ?></span>
                            <div class="w-12 rounded-full hover:border hover:border-4 hover:border-yellow-400">
                                <a href="index.php?pagina=padrinho&origem=<?= encrypt($batizado['padrinho']); ?>">
                                    <img src="<?= getFotoPadrinho($batizado['padrinho']) ?>" />
                                </a>
                            </div>
                        </div>
                    </td>
                    <td><?= GlobalModel::retornarUmValor("SELECT nome FROM padrinhos WHERE id = {$batizado['padrinho']}"); ?></td>
                    <td class="text-center"><?= $batizado['inicio']; ?></td>
                    <td class="text-center"><?= $batizado['fim']; ?></td>
                    <td class="text-center"><badge class="badge badge-info w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado_id} AND padrinho_id = {$batizado['padrinho']}") ?></badge></td>
                    <td class="text-center"><badge class="badge badge-warning w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado_id} AND padrinho_id = {$batizado['padrinho']} AND liberar <= CURDATE()") ?></badge></td>
                    <td class="text-center"><badge class="badge badge-neutral w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado_id} AND padrinho_id = {$batizado['padrinho']} AND liberar > CURDATE()") ?></badge></td>
                    <td class="text-center"><badge class="badge badge-accent w-6 h-6 rounded-full"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado_id} AND padrinho_id = {$batizado['padrinho']} AND realizada != null") ?></badge></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!----fim---padrinhos do afilhado---->
    </div>
</div>
</div>
<?php } ?>