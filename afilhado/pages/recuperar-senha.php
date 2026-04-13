<div class="w-full text-xs flex justify-end">
    <a href="login.php" class="btn btn-xs btn-neutral" href="../index.php"><i class="fa fa-mail-forward" aria-hidden="true"></i> VOLTAR AO LOGIN</a>
</div>
<form>
    <div class="form-control mb-3">
    <label class="label">
        <span class="label-text">Digite o seu usuário:</span>
    </label>
    <input type="text" id="meu_usuario" class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-[#3f4295]" />
    </div>
    <button type="button" onclick="recuperarSenha()" class="btn w-full text-white btn-neutral border-none">
    ENVIAR
    </button>
</form>
<div class="divider">PARCEIRIA</div>
<div class="flex items-center justify-center">
    <img src="assets/img/logo-light.png" style="height:50px;" />
</div>
<script>
    function recuperarSenha() {
        // 1. Pegar o valor do input pelo ID
        var meu_usuario = document.getElementById('meu_usuario').value;
        
        // 2. Definir a URL de destino
        var urlBase = 'https://api.whatsapp.com/send?phone=5514997655601&text=Ol%C3%A1%20sou%20colaborador%20da%20Usina%20Laguna%20e%20fa%C3%A7o%20parte%20do%20Programa%20Jovem%20Mec%C3%A2nico%2C%20preciso%20recuperar%20minha%20senha%20de%20acesso.%20Usu%C3%A1rio%3A%F0%9F%91%A4-%3E%20';
        
        // 3. Redirecionar com o valor na URL (?chave=valor)
        if(meu_usuario) {
            window.location.href = urlBase + encodeURIComponent(meu_usuario);
        } else {
            alert('Por favor, digite seu usuário!');
        }
    }
</script>