// Variável para controlar qual destaque está ativo
let destaqueAtivo = null;

function mostrarDestaque(tipo) {
    const conteudo = document.getElementById('conteudoDestaque');
    
    // Se o mesmo botão for clicado novamente, esconde o conteúdo
    if (destaqueAtivo === tipo) {
        conteudo.innerHTML = '<h3>Clique sobre as imagens para mostrar os textos.</h3>';
        destaqueAtivo = null;
        conteudo.classList.remove('show');
        return;
    }
    
    // Define o novo destaque ativo
    destaqueAtivo = tipo;
    
    // Remove a classe de animação temporariamente
    conteudo.classList.remove('show');
    
    switch(tipo) {
        case 'deserto':
            conteudo.innerHTML = `
                <div class="estilizar1">
                    <h3>
                        O deserto verde é uma expressão usada para descrever grandes
                        plantações de uma única espécie (monocultura). Dependendo da forma
                        como forem manejadas podem causar danos na disponibilidade de
                        recursos hídricos, solo e também para a biodiversidade local já que um só
                        tipo de vegetação restringe muito os recursos que sustentam a
                        diversidade da fauna.
                    </h3>
                    <h1 id="sabemdesertoverde"> 
                        Os Brasileiros sabem o que é o Deserto Verde?
                    </h1>
                    <h3>
                        O deserto verde mais conhecido é o de plantação de árvores para a
                        produção de papel e celulose. Neste sentido, as árvores mais utilizadas
                        para este cultivo são o eucalipto, pinus e acácia.
                    </h3>
                </div>
            `;                
            break;
            
        case 'Lei':
            conteudo.innerHTML = `
                <div class="estilizar2">
                    <h1 id="lei">
                        Código Florestal | LEI Nº 12.651, DE 25 DE MAIO DE 2012
                    </h1>
                    <h3>
                        Art. 1º-A. Esta Lei estabelece normas gerais sobre a proteção da vegetação, áreas 
                        de Preservação Permanente e as áreas de Reserva Legal; a exploração florestal, o 
                        suprimento de matéria-prima florestal, o controle da origem dos produtos florestais 
                        e o controle e prevenção dos incêndios florestais, e prevê instrumentos econômicos e 
                        financeiros para o alcance de seus objetivos. (Incluído pela Lei nº 12.727, de 2012).

                        Parágrafo único. Tendo como objetivo o desenvolvimento sustentável, esta Lei atenderá 
                        aos seguintes princípios: (Incluído pela Lei nº 12.727, de 2012).
                        I - afirmação do compromisso soberano do Brasil com a preservação das suas florestas e 
                        demais formas de vegetação nativa, bem como da biodiversidade, do solo, dos recursos hídricos 
                        e da integridade do sistema climático, para o bem estar das gerações presentes e futuras; 
                        (Incluído pela Lei nº 12.727, de 2012).
                    </h3>
                </div>
            `;
            break;
            
        case 'infertil':
            conteudo.innerHTML = `
                <div class="estilizar3">
                    <h3>
                        O declínio da fertilidade do solo ocorre quando a quantidade de nutrientes removidos do solo excede
                        a quantidade aplicada. As plantas, então, retiram os nutrientes das reservas do solo. As reservas se 
                        esgotam até que não haja mais recursos para o desenvolvimento das plantas.

                        A seguir estão as principais causas da perda de fertilidade do solo:
                        <ul>
                            <li>uso de fertilizantes sem considerar as condições do campo;</li>
                            <li>sistema agrícola inadequado;</li>
                            <li>cultivo contínuo de culturas;</li>
                            <li>lavoura intensiva;</li>
                            <li>cultivo de monocultura;</li>
                            <li>eliminação completa dos resíduos das culturas;</li>
                            <li>erosão do solo e degradação da terra;</li>
                            <li>clima desfavorável e condições meteorológicas extremas.</li>
                        </ul>
                        A perda da fertilidade do solo tem um impacto negativo significativo não apenas na produção agrícola, 
                        mas também nos ecossistemas ao redor. O esgotamento da terra leva à desertificação, à perda de biodiversidade, 
                        à poluição dos corpos de água e a mudanças potencialmente perigosas nos cursos de água.
                    </h3>
                </div>
            `;
            break;
    }
    
    // Adiciona a classe de animação após um pequeno delay
    setTimeout(() => {
        conteudo.classList.add('show');
    }, 50);
}