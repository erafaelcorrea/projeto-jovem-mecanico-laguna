<?php
$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome FROM afilhados ORDER BY nome ASC");
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Criar Avaliação</h2>
            <form method="POST" action="index.php?pagina=salvar-nova-avaliacao-instrutor" accept-charset="UTF-8" enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Instrutor:</legend>
                    <select name="instrutor" class="select select-bordered w-full">
                        <option value="Agnelo">José Agnelo</option>
                    </select>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Selecione um Jovem Mecânico para ser avaliado:</legend>
                        <select name="afilhado_id" class="select select-bordered w-full">
                            <?php foreach ($afilhados as $afilhado): ?>
                                <option value="<?= $afilhado['id'] ?>"><?= $afilhado['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Data de liberação:</legend>
                    <div class="flex flex-col gap-1">
                        <input type="date" name="liberar" value="<?= date('Y-m-d') ?>" class="input input-bordered w-full">
                        <span class="text-xs text-gray-500">Defina uma data para liberar a avaliação</span>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <button class="btn btn-neutral w-full">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>