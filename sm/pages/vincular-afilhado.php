<?php
$padrinho_id = decrypt($_GET['origem']);
$padrinho = GlobalModel::retornarUmObjeto("SELECT id, nome FROM padrinhos WHERE id = {$padrinho_id}");
if (!$padrinho->id) {
    include('404.php');
} else {
    $tem_afilhado_no_momento = GlobalModel::retornarUmValor("SELECT id FROM batizado WHERE padrinho = {$padrinho_id} AND fim >= CURDATE()");
    $afilhados = GlobalModel::retornarUmaLista("SELECT id, nome FROM afilhados ORDER BY nome ASC");
    if ($tem_afilhado_no_momento > 0) {
        echo "<script>alert('Este padrinho já possui um afilhado no momento.'); window.location.href = \"index.php?pagina=padrinhos\";</script>";
        exit;
    }
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Vincular Afilhado</h2>

            <form method="POST" action="index.php?pagina=salvar-vincular" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="padrinho_id" value="<?= $padrinho_id; ?>">
                
                <div class="form-control">
                    <label class="label">Afilhados</label>
                    <select name="afilhado_id" class="select select-bordered w-full">
                        <?php foreach ($afilhados as $afilhado): ?>
                            <option value="<?= $afilhado['id'] ?>"><?= $afilhado['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Período</span>
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <input type="date" name="inicio" class="input input-bordered w-full">
                            <span class="text-xs text-gray-500">Data de início do vínculo</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <input type="date" name="fim" class="input input-bordered w-full">
                            <span class="text-xs text-gray-500">Data de término do vínculo</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                <button class="btn btn-neutral w-full">Salvar</button>
                </div>

            </form>
        </div>
    </div>


    <!-- 🟩 DADOS ANTIGOS -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex w-full">
                <div class="card grid w-1/2 place-items-center">
                    <div class="avatar indicator">
                        <span class="indicator-item badge badge-xs badge-neutral">PADRINHO</span>
                        <div class="w-24 rounded-full"><img src="<?= getFotoPadrinho($padrinho_id) ?>" /></div>
                    </div>
                    <span class="text-wrap"><?= $padrinho->nome ?></span>
                </div>
                <div class="divider divider-horizontal">COM</div>
                <div class="card grid w-1/2 place-items-center">
                    <div class="avatar indicator">
                        <span class="indicator-item badge badge-xs badge-neutral">AFILHADO</span>
                        <div class="w-24 rounded-full"><img src="../afilhado/assets/img/user-img.png" /></div>
                    </div>
                    <span class="text-wrap">Afilhado</span>
                </div>
            </div>
        </div>
    </div> 

</div>
<?php } ?>