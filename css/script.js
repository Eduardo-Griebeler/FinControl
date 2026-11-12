function atualizarModoEscuro() {

    const modoEscuro = localStorage.getItem('modoEscuro') === 'true';

    document.body.classList.toggle('dark-mode', modoEscuro);

    const botao = document.getElementById('btnModo');

    if (botao) {
        botao.innerHTML = modoEscuro
            ? '☀️ Modo Claro'
            : '🌙 Modo Escuro';
    }
}

function toggleDarkMode() {

    const modoEscuro = !document.body.classList.contains('dark-mode');

    localStorage.setItem('modoEscuro', modoEscuro);

    atualizarModoEscuro();
}

document.addEventListener('DOMContentLoaded', atualizarModoEscuro);