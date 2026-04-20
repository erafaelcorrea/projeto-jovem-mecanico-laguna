<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl"> 
        <div class="card-body">
            <h2 class="card-title">Adicionar Padrinho</h2>

            <form method="POST" action="index.php?pagina=salvar-adicionar-padrinho" accept-charset="UTF-8" enctype="multipart/form-data">

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nome:</legend>
                    <input type="text" name="nome" class="input input-bordered w-full" value="" required>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Foto:</legend>
                    <input type="file" name="foto" class="file-input file-input-bordered w-full">
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Matrícula:</legend>
                    <input type="text" name="matricula" class="input input-bordered w-full" value="">
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Data de Nascimento:</legend>
                    <input type="date" name="data_nascimento" class="input input-bordered w-full" value="">
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Sexo:</legend>
                    <select name="sexo" class="select select-bordered w-full">
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Telefone:</legend>
                    <input type="text" name="telefone" class="input input-bordered w-full" value="">
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">E-mail:</legend>
                    <input type="email" name="email" class="input input-bordered w-full" value="">
                </fieldset>

                 <fieldset class="fieldset">
                    <legend class="fieldset-legend">User:</legend>
                    <input type="text" name="user" class="input input-bordered w-full" value="" maxlength="20" required>
                </fieldset>

                 <fieldset class="fieldset">
                    <legend class="fieldset-legend">Senha:</legend>
                   <input type="text" name="senha" class="input input-bordered w-full" value="" maxlength="10" required>
                </fieldset>

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
                    <img src="../padrinho/assets/img/padrinho-perfil.png" />
                </div>
            </div>
        </div>
    </div>

</div>
