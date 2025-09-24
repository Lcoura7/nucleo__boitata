function toggleMenu() {
    const menu = document.getElementById('circleMenu');
    menu.classList.toggle('active');
}

// Funcionalidades adicionais
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('.search-input');

    if (input) {
        // Criar span oculto para medir largura do texto digitado
        const measurer = document.createElement('span');
        document.body.appendChild(measurer);

        // Copiar estilos do input para o span
        const inputStyles = getComputedStyle(input);
        measurer.style.position = 'absolute';
        measurer.style.visibility = 'hidden';
        measurer.style.whiteSpace = 'pre';
        measurer.style.fontSize = inputStyles.fontSize;
        measurer.style.fontFamily = inputStyles.fontFamily;
        measurer.style.fontWeight = inputStyles.fontWeight;
        measurer.style.letterSpacing = inputStyles.letterSpacing;

        const updateInputWidth = () => {
            measurer.textContent = input.value || input.placeholder || '';
            const newWidth = measurer.offsetWidth + 30; // padding
            const maxWidth = 200; // limite para não invadir o botão de favoritos
            input.style.width = Math.min(newWidth, maxWidth) + 'px';
        };

        input.addEventListener('input', updateInputWidth);
        updateInputWidth(); // ajustar inicialmente
    }

    // Clique no ícone de pesquisa
    const searchIcon = document.querySelector('.search-icon');
    if (searchIcon) {
        searchIcon.addEventListener('click', function () {
            const searchInput = document.querySelector('.search-input');
            const searchTerm = searchInput.value;
            if (searchTerm.trim()) {
                alert('Pesquisando por: ' + searchTerm);
                // Sua lógica de pesquisa aqui
            } else {
                searchInput.focus();
            }
        });
    }

    // Botão de favoritos
    const favoritesBtn = document.querySelector('.favorites-btn');
    if (favoritesBtn) {
        favoritesBtn.addEventListener('click', function () {
            alert('Favoritos clicado!');
            // Sua lógica de favoritos aqui
        });
    }

    // Fechar menu ao clicar fora
    document.addEventListener('click', function (e) {
        const menu = document.getElementById('circleMenu');
        const bottonMapa = document.querySelector('.botton-mapa');

        if (menu && bottonMapa && !bottonMapa.contains(e.target) && menu.classList.contains('active')) {
            menu.classList.remove('active');
        }
    });
});
function toggleMenu() {
    const menu = document.getElementById('circleMenu');
    const borda = document.querySelector('.borda');
    menu.classList.toggle('active');
    borda.classList.toggle('active');
}
