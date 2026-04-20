<?php
    $afilhado_id = decrypt($_GET['origem']);
    $afilhado = GlobalModel::RetornarUmObjeto("SELECT id, nome FROM afilhados WHERE id = {$afilhado_id}");

    $respostas_dissertativas = GlobalModel::retornarUmaLista("SELECT a.pergunta, b.resposta, DATE_FORMAT(b.data, '%d/%m/%Y') as data, b.hora, c.padrinho_id FROM perguntas a INNER JOIN respostas b ON a.id = b.pergunta INNER JOIN avaliacao c ON b.avaliacao = c.id WHERE a.modo = 'dissertativa' AND c.afilhado_id = {$afilhado_id}");
?>
<div class="overflow-x-auto">

   <div class="w-full flex justify-between p-2">
        <div class="flex flex-col gap-6">
            <div class="flex">
                <div class="avatar avatar-online avatar-placeholder">
                    <div class="bg-neutral text-neutral-content w-12 h-12 rounded-full text-center"><img src="<?= getFotoAfilhado($afilhado_id) ?>"></div>
                </div>
                <span class="text-lg font-thin text-base-400 ml-2 py-2"><?= $afilhado->nome ?></span>
            </div>
            <div class="flex ml-2">
                <div class="avatar avatar-online avatar-placeholder">
                    <div class="bg-neutral text-neutral-content w-8 h-8 rounded-full text-center"><span class="text-lg"><?= count($respostas_dissertativas) ?></span> </div>
                </div>
                <span class="text-lg font-thin text-base-400 ml-2">RESPOSTAS DISSERTATIVAS</span>
            </div>
        </div>
        <div>
            <a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado_id) ?>" class="btn btn-neutral btn-sm"><i class="fa fa-mail-reply"></i> VOLTAR</a>
        </div>
   </div>
    <div class="ml-4">
    <?php foreach ($respostas_dissertativas as $index => $rd): ?>
        <div class="chat chat-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full"><img src="<?= getFotoPadrinho($rd['padrinho_id']) ?>" /></div>
            </div>
            <div class="chat-header">
                <time class="text-xs opacity-70"><?= $rd['data'] ?> - </time>
                <?= GlobalModel::retornarUmValor("SELECT nome FROM padrinhos WHERE id = {$rd['padrinho_id']}") ?>
                
            </div>
            <div class="chat-bubble"><?= $rd['resposta'] ?></div>
            <div class="chat-footer opacity-70 text-xs"><?= $rd['hora'] ?></div>
        </div>
    <?php endforeach; ?>
    </div>

</div>


