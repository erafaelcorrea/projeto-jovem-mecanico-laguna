<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Adicionar Padrinho</h2>

            <form method="POST" action="index.php?pagina=salvar-adicionar-padrinho" accept-charset="UTF-8" enctype="multipart/form-data">

                <div class="form-control">
                <label class="label">Nome</label>
                <input type="text" name="nome" class="input input-bordered" value="" required>
                </div>

                <div class="form-control">
                <label class="label">Foto</label>
                <input type="file" name="foto" class="file-input file-input-bordered">
                </div>

                <div class="form-control">
                <label class="label">Matrícula</label>
                <input type="text" name="matricula" class="input input-bordered" value="">
                </div>

                <div class="form-control">
                <label class="label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" class="input input-bordered" value="">
                </div>

                <div class="form-control">
                <label class="label">Sexo</label>
                <select name="sexo" class="select select-bordered">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
                </div>

                <div class="form-control">
                <label class="label">Telefone</label>
                <input type="text" name="telefone" class="input input-bordered" value="">
                </div>

                <div class="form-control">
                <label class="label">Email</label>
                <input type="email" name="email" class="input input-bordered" value="">
                </div>

                <div class="form-control">
                <label class="label">User</label>
                <input type="text" name="user" class="input input-bordered" value="" maxlength="20" required>
                </div>

                <div class="form-control">
                <label class="label">Senha</label>
                <input type="text" name="senha" class="input input-bordered" value="" maxlength="10" required>
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
                            <img src="../padrinho/assets/img/padrinho-perfil.png" />
                        </div>
                    </div>

        </div>
    </div>

</div>
