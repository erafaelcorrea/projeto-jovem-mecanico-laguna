<div class="w-full text-xs flex justify-end">
    <a class="btn btn-xs btn-neutral" href="../index.php"><i class="fa fa-mail-forward" aria-hidden="true"></i> NÃO SOU PADRINHO</a>
</div>
<form method="post" action="login.php?pagina=autenticar">
    <div class="form-control mb-3">
    <label class="label">
        <span class="label-text">Usuário:</span>
    </label>
    <input type="text" name="user" class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-[#3f4295]" required />
    </div>
    <div class="form-control mb-3">
    <label class="label">
        <span class="label-text">Senha:</span>
    </label>
    <input type="password" name="senha" placeholder="********" class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-[#3f4295]" required />
    </div>
    <button type="submit" class="btn w-full text-white btn-neutral border-none">
    ENTRAR
    </button>
</form>
<div class="divider">PARCEIRIA</div>
<div class="flex items-center justify-center">
    <img src="assets/img/logo-light.png" style="height:50px;" />
</div>
<p class="text-center text-sm mt-4">
    Esqueceu sua senha? <a href="login.php?pagina=recuperar-senha" class="btn btn-xs btn-neutral" href="../index.php"><i class="fa fa-mail-forward" aria-hidden="true"></i> RECUPERAR</a>
</p>
