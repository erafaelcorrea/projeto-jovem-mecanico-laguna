const themeToggle = document.getElementById('theme-toggle');
const htmlTag = document.getElementById('html-tag');
const logoImg = document.getElementById('logo-app'); // Seleciona a imagem
const logoImgLaguna = document.getElementById('logo-laguna'); // Seleciona a imagem
const Rtoast = document.getElementById('Rtoast');

// Função que define qual imagem usar baseada no tema

function updateImage(theme) {
    if (theme === 'dark') {
        logoImg.src = 'assets/img/logo-dark.png'; // Caminho da imagem para modo escuro
        logoImgLaguna.src = 'assets/img/logo-laguna-dark.png'; // Caminho da imagem para modo escuro
    } else {
        logoImg.src = 'assets/img/logo-light.png'; // Caminho da imagem para modo claro
        logoImgLaguna.src = 'assets/img/logo-laguna.png'; // Caminho da imagem para modo escuro
    }
}

Rtoast.addEventListener('click', function() { this.remove(); });

/*
document.getElementById('Rtoast').addEventListener('click', function() {
    this.remove();
  });
*/

// 1. Ao carregar a página: verifica o tema salvo
const savedTheme = localStorage.getItem('theme') || 'light';
htmlTag.setAttribute('data-theme', savedTheme);
themeToggle.checked = savedTheme === 'dark';
updateImage(savedTheme); // Garante a imagem correta no refresh

// 2. Ao clicar no botão: troca o tema e a imagem
themeToggle.addEventListener('change', () => {
    const newTheme = themeToggle.checked ? 'dark' : 'light';
    htmlTag.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    updateImage(newTheme); // Troca a imagem na hora
});

function verificarDispositivo() {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        console.log("Mobile (Celular/Tablet)");
        // Código para mobile aqui
        return "mobile";
    } else {
        console.log("Desktop (Computador)");
        // Código para desktop aqui
        return "desktop";
    }
}

verificarDispositivo();
