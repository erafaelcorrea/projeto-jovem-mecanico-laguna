<?php
$padrinho_id = decrypt($_GET['origem']);
$padrinho = retornarUmObjeto("SELECT id, nome, matricula, data_nascimento, sexo, telefone, email, user FROM padrinhos WHERE id = {$padrinho_id}");
if (!$padrinho) {
    include('404.php');
} else {
    $dn = explode('-', $padrinho['data_nascimento']);
?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Editar Dados</h2>

            <form method="POST" action="index.php?pagina=salvar-editar-padrinho" accept-charset="UTF-8" enctype="multipart/form-data">

                <input type="hidden" name="padrinho_id" value="<?= $padrinho_id; ?>">

                <div class="form-control">
                <label class="label">Nome</label>
                <input type="text" name="nome" class="input input-bordered" value="<?= $padrinho['nome']; ?>" required>
                </div>

                <div class="form-control">
                <label class="label">Foto</label>
                <input type="file" name="foto" class="file-input file-input-bordered">
                </div>

                <div class="form-control">
                <label class="label">Matrícula</label>
                <input type="text" name="matricula" class="input input-bordered" value="<?= $padrinho['matricula']; ?>">
                </div>

                <div class="form-control">
                <label class="label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" class="input input-bordered" value="<?= $padrinho['data_nascimento']; ?>">
                </div>

                <div class="form-control">
                <label class="label">Sexo</label>
                <select name="sexo" class="select select-bordered">
                    <option value="M" <?= $padrinho['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= $padrinho['sexo'] === 'F' ? 'selected' : '' ?>>Feminino</option>
                </select>
                </div>

                <div class="form-control">
                <label class="label">Telefone</label>
                <input type="text" name="telefone" class="input input-bordered" value="<?= $padrinho['telefone']; ?>">
                </div>

                <div class="form-control">
                <label class="label">Email</label>
                <input type="email" name="email" class="input input-bordered" value="<?= $padrinho['email']; ?>">
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
            <div class="avatar indicator">
                        <span class="indicator-item badge badge-xs badge-neutral">PADRINHO</span>
                        <div class="w-24 rounded-full">
                            <img src="<?= getFotoPadrinho($padrinho_id) ?>" />
                        </div>
                    </div>

            <div class="space-y-2">

                <p><strong>Nome:</strong> <?= $padrinho['nome']; ?></p>
                <p><strong>Matrícula:</strong> <?= $padrinho['matricula']; ?></p>
                <p><strong>Data de Nascimento:</strong> <?= $padrinho['data_nascimento']; ?></p>
                <p><strong>Sexo:</strong> <?= $padrinho['sexo'] === 'M' ? 'Masculino' : 'Feminino' ?></p>
                <p><strong>Telefone:</strong> <?= $padrinho['telefone']; ?></p>
                <p><strong>Email:</strong> <?= $padrinho['email']; ?></p>
                <p><strong>Usuário:</strong> <?= $padrinho['user']; ?></p>

            </div>
        </div>
    </div>

</div>


<?php } ?>