<?php
session_start(); // IMPORTANTE: Iniciar a sessão

// Verificar se o usuário está logado
$usuario_logado = isset($_SESSION['logado']) && $_SESSION['logado'] === true;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Núcleo Boitatá - Mapa de Solos MG</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="icon" href="img/icon.ico" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Dirt&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        .real-map-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
        }

        .map-wrapper {
            position: relative;
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 20px;
            overflow: hidden;
        }

        .map-image {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
            cursor: crosshair;
        }

        /* Overlay para áreas clicáveis invisíveis */
        .map-overlay {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border-radius: 10px;
        }

        /* Áreas clicáveis invisíveis */
        .clickable-area {
            position: absolute;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            border-radius: 5px;
        }

        .clickable-area:hover {
            background-color: rgba(231, 76, 60, 0.2);
            border-color: rgba(231, 76, 60, 0.8);
            transform: scale(1.02);
        }

        .clickable-area.selected {
            background-color: rgba(231, 76, 60, 0.3);
            border-color: #e74c3c;
            border-width: 3px;
        }

        /* Tooltip para mostrar informações rápidas */
        .map-tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            pointer-events: none;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s;
            max-width: 200px;
        }

        .map-tooltip.show {
            opacity: 1;
        }

        /* Sidebar melhorada */
        .enhanced-sidebar {
            width: 350px;
            background: white;
            border-right: 1px solid #e9ecef;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 180px);
        }

        .search-section-enhanced {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .search-tabs {
            display: flex;
            margin-bottom: 15px;
            background: #e9ecef;
            border-radius: 8px;
            padding: 3px;
        }

        .search-tab {
            flex: 1;
            padding: 8px 12px;
            text-align: center;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
        }

        .search-tab.active {
            background: #e74c3c;
            color: white;
        }

        .search-input-enhanced {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-input-enhanced:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .search-results {
            max-height: 200px;
            overflow-y: auto;
            margin-top: 10px;
        }

        .search-result-item {
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 6px;
            margin-bottom: 5px;
            background: white;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .search-result-item:hover {
            background: #e74c3c;
            color: white;
        }

        .info-panel-enhanced {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .soil-info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #e74c3c;
        }

        .soil-title {
            font-size: 1.3rem;
            color: #e74c3c;
            margin-bottom: 15px;
            font-family: 'Rubik Dirt', system-ui;
        }

        .info-grid-enhanced {
            display: grid;
            gap: 12px;
        }

        .info-item-enhanced {
            background: white;
            padding: 12px;
            border-radius: 8px;
            border-left: 3px solid #e74c3c;
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-value {
            color: #495057;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Cities database simulation */
        .cities-found {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 10px;
            margin: 10px 0;
        }

        .city-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #c3e6cb;
        }

        .city-item:last-child {
            border-bottom: none;
        }

        .city-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .city-soil {
            font-size: 12px;
            color: #6c757d;
        }

        /* Legenda integrada */
        .integrated-legend {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 20px;
            border: 1px solid #e9ecef;
        }

        .legend-title {
            font-weight: bold;
            margin-bottom: 12px;
            color: #2c3e50;
            text-align: center;
        }


        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            padding: 4px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            border: 1px solid #666;
            flex-shrink: 0;
        }

        /* Cores baseadas na legenda real */
        .color-aur {
            background-color: #D4C4A8;
        }

        .color-au {
            background-color: #90EE90;
        }

        .color-cxbd {
            background-color: #E8C4A8;
        }

        .color-cxbdf {
            background-color: #D4B894;
        }

        .color-cxbe {
            background-color: #C8A882;
        }

        .color-chd {
            background-color: #E8D4A8;
        }

        .color-cybe {
            background-color: #F4E8C4;
        }

        .color-ffd {
            background-color: #E8B4C8;
        }

        .color-gmd {
            background-color: #A8C8E8;
        }

        .color-gxbd {
            background-color: #94B8D4;
        }

        .color-lad {
            background-color: #F4E4BC;
        }

        .color-lvad {
            background-color: #E8C898;
        }

        .color-lvadf {
            background-color: #D4B474;
        }

        .color-lvae {
            background-color: #E8D488;
        }

        .color-lvd {
            background-color: #F4C888;
        }

        .color-lvdf {
            background-color: #E8B474;
        }

        .color-lve {
            background-color: #F4D498;
        }

        .color-lvef {
            background-color: #E8C484;
        }

        .color-nve {
            background-color: #F4A8A8;
        }

        .color-nvef {
            background-color: #E89898;
        }

        .color-nxd {
            background-color: #D4A8A8;
        }

        .color-nxe {
            background-color: #E8B8B8;
        }

        .color-pvad {
            background-color: #E8B4B8;
        }

        .color-pvae {
            background-color: #F4C4C8;
        }

        .color-pvd {
            background-color: #D4A4A8;
        }

        .color-pve {
            background-color: #E8B4B8;
        }

        .color-rld {
            background-color: #B8B8B8;
        }

        .color-rldh {
            background-color: #A8A8A8;
        }

        .color-rle {
            background-color: #C8C8C8;
        }

        .color-rlh {
            background-color: #D8D8D8;
        }

        .color-rqg {
            background-color: #E6E6E6;
        }

        .color-rqo {
            background-color: #F0F0F0;
        }

        .color-rubd {
            background-color: #C8D4E8;
        }

        .color-rube {
            background-color: #D8E4F8;
        }

        .color-sxe {
            background-color: #B8E8B8;
        }

        .color-tco {
            background-color: #D4A464;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .mapa-container {
                flex-direction: column;
            }

            .enhanced-sidebar {
                width: 100%;
                max-height: 40vh;
                order: 2;
            }

            .real-map-container {
                order: 1;
                padding: 10px;
            }

            .legend-grid {
                grid-template-columns: 0.1fr;
            }


        }

        .legend-img {
            max-width: 300px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Novos estilos para o mapa com imagem real */
        .real-map-image {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
        }

        /* Melhorias na responsividade */
        @media (max-width: 992px) {
            .mapa-container {
                flex-direction: column;
            }

            .enhanced-sidebar {
                width: 100%;
                max-height: 40vh;
                order: 2;
            }

            .real-map-container {
                order: 1;
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .legend-grid {
                grid-template-columns: 0.1fr;
            }

            .soil-info-card {
                padding: 15px;
            }
        }

        .login-required-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 180px);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
        }

        .login-required-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .login-required-card h2 {
            color: #e74c3c;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .login-required-card p {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-login-required {
            display: inline-block;
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-login-required:hover {
            background: linear-gradient(45deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            color: white;
            text-decoration: none;
        }

        .logout-link {
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #c82333 !important;
        }
    </style>
</head>

<body>
    <header class="cabecalho">
        <div class="img_cabecalho">
            <img src="img/logonome-removebg-preview.png" alt="Logo Núcleo Boitatá" />
        </div>
        <div class="botoes_cabecalho">
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="Login.php">Registro</a></li>
                    <li><a href="mapa.php" class="active">Mapas</a></li>
                    <li><a href="projeto.html">O Projeto</a></li>
                    <?php if ($usuario_logado): ?>
                        <li><a href="logout.php" class="logout-link">Sair</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <?php if (!$usuario_logado): ?>
        <div class="login-required-container">
            <div class="login-required-card">
                <h2>Acesso Restrito</h2>
                <p>Para acessar o mapa interativo de solos de Minas Gerais, você precisa estar logado.</p>
                <p>Faça seu login para explorar as informações detalhadas sobre os diferentes tipos de solo de MG.</p>
                <a href="Login.php" class="btn-login-required">Fazer Login</a>
            </div>
        </div>
    <?php else: ?>
        <div class="mapa-container">
            <div class="enhanced-sidebar">
                <div>
                    <h2 class="sidebar-title">Solos de Minas Gerais</h2>
                    <p class="sidebar-subtitle">Explore solos por região ou cidade</p>
                </div>

                <div class="search-section-enhanced">
                    <div class="search-tabs">
                        <button class="search-tab active" data-tab="region">Por Solo</button>
                        <button class="search-tab" data-tab="city">Por Cidade</button>
                    </div>

                    <div class="search-box">
                        <input type="text" class="search-input-enhanced" id="searchInput"
                            placeholder="Digite o nome do solo ou cidade...">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>

                <div class="info-panel-enhanced" id="infoPanel">
                    <div class="welcome-message">
                        <h3 style="color: #e74c3c; margin-bottom: 15px;">🌱 Como usar:</h3>
                        <p><strong>Por Solo:</strong> Clique diretamente nas áreas coloridas do mapa</p>
                        <p><strong>Por Cidade:</strong> Use a aba "Por Cidade" e digite o nome do município</p>
                        <p><strong>Informações:</strong> Cada tipo de solo possui dados únicos sobre composição, uso e
                            características</p>
                    </div>
                </div>
            </div>

            <div class="real-map-container">
                <div class="map-wrapper">
                    <!-- Imagem real do mapa com mapa de áreas clicáveis -->
                    <img src="./img/MapaMG.png" alt="Mapa de Solos de MG" class="real-map-image" id="realMapImage"
                        usemap="#soilMap">

                    <!-- Mapa de áreas clicáveis - coordenadas devem ser ajustadas conforme sua imagem -->
                    <map name="soilMap" id="soilMap">
                        <!-- Exemplo de área clicável - você precisará definir as coordenadas corretas -->
                        <area shape="poly" coords="100,200,150,250,200,300,250,350" alt="Região Norte" data-soil="LVAd"
                            title="Latossolo Vermelho Amarelo Distrófico">
                        <area shape="poly" coords="300,400,350,450,400,500,450,550" alt="Região Triângulo" data-soil="LVAdf"
                            title="Latossolo Vermelho Amarelo Distroférrico">
                        <!-- Adicione todas as áreas correspondentes às regiões do seu mapa -->
                    </map>

                    <!-- Tooltip para informações rápidas -->
                    <div class="map-tooltip" id="mapTooltip"></div>
                </div>

                <!-- Legenda integrada -->
                <div class="integrated-legend">
                    <div class="legend-title">
                        Legenda - Tipos de Solo
                    </div>
                    <div class="legend-grid">

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <footer id="rodape">
        <p class="direitos">
            &copy; 2025 Núcleo Boitatá |
            <a href="politica-privacidade.html">Política de Privacidade</a>
            |
            <a href="termos-servico.html">Termos de Serviço</a>
            |
            <a href="https://www.instagram.com/nucleo_boitata?igsh=MTJlcmdZzMwMGhkOA==">Instagram</a>
        </p>
    </footer>

    <?php if ($usuario_logado): ?>
        <script>
            // Base de dados expandida com todos os municípios de MG
            const citiesDatabase = {
                // Norte de Minas
                'Montes Claros': ['LVAd', 'PVAd', 'RLd'],
                'Januária': ['LVAd', 'RQg', 'GMd'],
                'Pirapora': ['LVAd', 'PVAd'],
                'Janaúba': ['LVAd', 'RLd'],
                'Bocaiúva': ['LVAd', 'CXbd'],
                'Salinas': ['LVAd', 'PVAd'],
                'Taiobeiras': ['LVAd', 'RLd'],
                'Capelinha': ['PVAd', 'RLd'],
                'Diamantina': ['RLd', 'CXbd'],
                'Pedra Azul': ['RLd', 'CXbd'],
                'Araçuaí': ['RLd', 'PVAd'],
                'Itaobim': ['PVAd', 'RLd'],
                'Porteirinha': ['LVAd', 'RLd'],
                'Jaíba': ['LVAd', 'RQg'],
                'Mato Verde': ['LVAd', 'PVAd'],

                // Triângulo Mineiro/Alto Paranaíba
                'Uberlândia': ['LVAdf', 'LAd'],
                'Uberaba': ['LVAdf', 'LVAd'],
                'Ituiutaba': ['LVAdf', 'LAd'],
                'Araguari': ['LVAdf', 'PVAd'],
                'Patos de Minas': ['LVAdf', 'LAd'],
                'Patrocínio': ['LVAdf', 'PVAd'],
                'Araxá': ['LVAdf', 'LAd'],
                'Frutal': ['LVAdf', 'PVAd'],
                'Iturama': ['LVAdf', 'LAd'],
                'Campina Verde': ['LVAdf', 'PVAd'],
                'Monte Carmelo': ['LVAdf', 'LAd'],
                'Coromandel': ['LVAdf', 'PVAd'],
                'Carmo do Paranaíba': ['LVAdf', 'LAd'],
                'Lagoa Formosa': ['LVAdf', 'PVAd'],

                // Centro-Oeste
                'Divinópolis': ['LAd', 'LVAd', 'CXbd'],
                'Formiga': ['LAd', 'PVAd'],
                'Oliveira': ['LAd', 'CXbd'],
                'Itaúna': ['LAd', 'LVAd'],
                'Pará de Minas': ['LAd', 'CXbd'],
                'Pitangui': ['LAd', 'PVAd'],
                'Bom Despacho': ['LAd', 'LVAd'],
                'Arcos': ['LAd', 'CXbd'],
                'Luz': ['LAd', 'PVAd'],
                'Campos Gerais': ['LAd', 'LVAd'],

                // RM Belo Horizonte
                'Belo Horizonte': ['CXbd', 'LVAd', 'RLd'],
                'Contagem': ['CXbd', 'LVAd'],
                'Betim': ['CXbd', 'LAd'],
                'Nova Lima': ['CXbd', 'RLd'],
                'Sabará': ['CXbd', 'PVAd'],
                'Santa Luzia': ['CXbd', 'LVAd'],
                'Vespasiano': ['CXbd', 'RLd'],
                'Ribeirão das Neves': ['CXbd', 'PVAd'],
                'Ibirité': ['CXbd', 'LVAd'],
                'Brumadinho': ['CXbd', 'RLd'],
                'Caeté': ['CXbd', 'PVAd'],
                'Pedro Leopoldo': ['CXbd', 'LVAd'],
                'São Joaquim de Bicas': ['CXbd', 'RLd'],
                'Matozinhos': ['CXbd', 'PVAd'],
                'Lagoa Santa': ['CXbd', 'LVAd'],

                // Zona da Mata
                'Juiz de Fora': ['LVAd', 'PVAd', 'CXbd'],
                'Viçosa': ['LVAd', 'CXbd'],
                'Muriaé': ['PVAd', 'LVAd'],
                'Cataguases': ['LVAd', 'PVAd'],
                'Ubá': ['LVAd', 'CXbd'],
                'Ponte Nova': ['LVAd', 'PVAd'],
                'Manhuaçu': ['PVAd', 'CXbd'],
                'Leopoldina': ['LVAd', 'PVAd'],
                'Carangola': ['PVAd', 'CXbd'],
                'São João Nepomuceno': ['LVAd', 'PVAd'],
                'Além Paraíba': ['LVAd', 'CXbd'],
                'Astolfo Dutra': ['LVAd', 'PVAd'],
                'Miraí': ['PVAd', 'CXbd'],
                'Tombos': ['LVAd', 'PVAd'],
                'Visconde do Rio Branco': ['LVAd', 'CXbd'],

                // Sul de Minas
                'Varginha': ['LVAd', 'PVAd'],
                'Pouso Alegre': ['LVAd', 'CXbd'],
                'Itajubá': ['LVAd', 'RLd'],
                'Três Corações': ['LVAd', 'PVAd'],
                'Lavras': ['LVAd', 'CXbd'],
                'São Lourenço': ['LVAd', 'PVAd'],
                'Poços de Caldas': ['LVAd', 'CXbd'],
                'Passos': ['LVAd', 'PVAd'],
                'Alfenas': ['LVAd', 'CXbd'],
                'Guaxupé': ['LVAd', 'PVAd'],
                'Extrema': ['LVAd', 'CXbd'],
                'Santa Rita do Sapucaí': ['LVAd', 'PVAd'],
                'Campanha': ['LVAd', 'CXbd'],
                'Machado': ['LVAd', 'PVAd'],
                'Boa Esperança': ['LVAd', 'CXbd'],

                // Vale do Rio Doce
                'Governador Valadares': ['PVAd', 'RLd'],
                'Ipatinga': ['PVAd', 'CXbd'],
                'Caratinga': ['PVAd', 'LVAd'],
                'Timóteo': ['PVAd', 'CXbd'],
                'Coronel Fabriciano': ['PVAd', 'LVAd'],
                'Mantena': ['PVAd', 'RLd'],
                'Aimorés': ['PVAd', 'CXbd'],
                'Conselheiro Pena': ['PVAd', 'LVAd'],
                'Resplendor': ['PVAd', 'RLd'],
                'Itabira': ['PVAd', 'CXbd'],
                'Nova Era': ['PVAd', 'LVAd'],
                'Santana do Paraíso': ['PVAd', 'RLd'],
                'Belo Oriente': ['PVAd', 'CXbd'],
                'Periquito': ['PVAd', 'LVAd'],
                'Engenheiro Caldas': ['PVAd', 'RLd'],

                // Noroeste de Minas
                'Paracatu': ['RQg', 'LAd'],
                'Unaí': ['RQg', 'LVAd'],
                'Buritis': ['RQg', 'LAd'],
                'Arinos': ['RQg', 'LVAd'],
                'Cabeceira Grande': ['RQg', 'LAd'],
                'Bonfinópolis de Minas': ['RQg', 'LVAd'],
                'Riachinho': ['RQg', 'LAd'],
                'Uruana de Minas': ['RQg', 'LVAd'],
                'Brasilândia de Minas': ['RQg', 'LAd'],
                'João Pinheiro': ['RQg', 'LVAd'],
                'Lagamar': ['RQg', 'LAd'],
                'Lagoa Grande': ['RQg', 'LVAd'],
                'Santa Fé de Minas': ['RQg', 'LAd'],
                'Vazante': ['RQg', 'LVAd'],
                'Presidente Olegário': ['RQg', 'LAd'],

                // Jequitinhonha
                'Teófilo Otoni': ['PVAd', 'RLd'],
                'Almenara': ['PVAd', 'CXbd'],
                'Jequitinhonha': ['PVAd', 'RLd'],
                'Minas Novas': ['PVAd', 'CXbd'],
                'Capelinha': ['PVAd', 'RLd'],
                'Diamantina': ['RLd', 'CXbd'],
                'Araçuaí': ['RLd', 'PVAd'],
                'Pedra Azul': ['RLd', 'CXbd'],
                'Salto da Divisa': ['PVAd', 'RLd'],
                'Felisburgo': ['PVAd', 'CXbd'],
                'Joaíma': ['PVAd', 'RLd'],
                'Josenópolis': ['PVAd', 'CXbd'],
                'Medina': ['PVAd', 'RLd'],
                'Rubim': ['PVAd', 'CXbd'],
                'Santo Antônio do Jacinto': ['PVAd', 'RLd']
            };

            // Base de dados detalhada dos solos (mantida do código anterior)
            const soilDatabase = {
                'LAd': {
                    name: 'Latossolo Amarelo Distrófico',
                    composition: 'Rico em óxidos de alumínio e ferro, textura argilosa',
                    ph: '4.0 - 5.5 (muito ácido)',
                    drainage: 'Boa drenagem, pode reter água em períodos chuvosos',
                    depth: 'Muito profundo (>2m)',
                    fertility: 'Baixa fertilidade natural, necessita correção',
                    climate: 'Tropical quente e úmido',
                    cultures: 'Eucalipto, pastagem, cultivos com correção do solo',
                    characteristics: 'Solo profundo, bem estruturado, coloração amarelada devido aos óxidos de ferro hidratados',
                    limitations: 'Alta acidez, baixa fertilidade, possível compactação',
                    management: 'Calagem, adubação orgânica e mineral, práticas conservacionistas'
                },
                'LVAd': {
                    name: 'Latossolo Vermelho Amarelo Distrófico',
                    composition: 'Mistura de óxidos de ferro e alumínio, textura argilosa a média',
                    ph: '4.5 - 6.0 (ácido)',
                    drainage: 'Excelente drenagem',
                    depth: 'Muito profundo (>2m)',
                    fertility: 'Baixa a média, requer manejo adequado',
                    climate: 'Tropical e subtropical',
                    cultures: 'Café, soja, milho, cana-de-açúcar, eucalipto',
                    characteristics: 'Transição entre vermelho e amarelo, boa estrutura física',
                    limitations: 'Acidez, baixo teor de nutrientes, susceptível à erosão',
                    management: 'Correção da acidez, adubação balanceada, cobertura vegetal'
                },
                'LVAdf': {
                    name: 'Latossolo Vermelho Amarelo Distroférrico',
                    composition: 'Alto teor de ferro, textura muito argilosa',
                    ph: '4.5 - 6.5',
                    drainage: 'Excelente drenagem',
                    depth: 'Muito profundo (>2m)',
                    fertility: 'Média, melhor que outros latossolos',
                    climate: 'Tropical e subtropical',
                    cultures: 'Soja, milho, café, cana-de-açúcar (alto potencial produtivo)',
                    characteristics: 'Rico em ferro, cor vermelho-amarelada intensa, excelente estrutura',
                    limitations: 'Possível deficiência de micronutrientes',
                    management: 'Manejo de micronutrientes, adubação equilibrada'
                },
                'PVAd': {
                    name: 'Argissolo Vermelho Amarelo Distrófico',
                    composition: 'Acúmulo de argila no horizonte B, textura variável',
                    ph: '4.5 - 6.5',
                    drainage: 'Moderada a boa',
                    depth: 'Profundo (1-2m)',
                    fertility: 'Média, melhor que latossolos',
                    climate: 'Tropical e subtropical',
                    cultures: 'Café, fruticultura, eucalipto, pastagem',
                    characteristics: 'Gradiente textural, horizonte B mais argiloso',
                    limitations: 'Susceptibilidade à erosão, compactação do horizonte B',
                    management: 'Práticas conservacionistas, manejo da compactação'
                },
                'RLd': {
                    name: 'Neossolo Litólico Distrófico',
                    composition: 'Solo raso sobre rocha, material mineral pouco desenvolvido',
                    ph: '5.0 - 7.5',
                    drainage: 'Rápida, excessiva',
                    depth: 'Muito raso (<50cm)',
                    fertility: 'Baixa a média, limitada pelo volume',
                    climate: 'Variável, comum em regiões montanhosas',
                    cultures: 'Pastagem natural, silvicultura, agricultura limitada',
                    characteristics: 'Solo raso, pedregoso, limitado pela rocha subjacente',
                    limitations: 'Pouco volume de solo, pedregosidade, erosão',
                    management: 'Conservação, manejo extensivo, reflorestamento'
                },
                'CXbd': {
                    name: 'Cambissolo Háplico Tb Distrófico',
                    composition: 'Solo jovem, minerais primários ainda presentes',
                    ph: '5.0 - 7.0',
                    drainage: 'Variável conforme relevo',
                    depth: 'Raso a profundo',
                    fertility: 'Variável, média a alta',
                    climate: 'Diversificado',
                    cultures: 'Agricultura familiar, horticultura, café',
                    characteristics: 'Solo jovem, desenvolvimento incipiente',
                    limitations: 'Variabilidade espacial alta',
                    management: 'Análise caso a caso, manejo conservacionista'
                },
                'GMd': {
                    name: 'Gleissolo Melânico Tb Distrófico',
                    composition: 'Solo hidromórfico, rico em matéria orgânica',
                    ph: '4.5 - 6.5',
                    drainage: 'Deficiente, sujeito a alagamento',
                    depth: 'Variável',
                    fertility: 'Alta em matéria orgânica, limitada pela drenagem',
                    climate: 'Áreas úmidas, várzeas',
                    cultures: 'Arroz irrigado, pastagem adaptada à umidade',
                    characteristics: 'Solo escuro, rico em matéria orgânica, encharcado',
                    limitations: 'Excesso de água, anaerobiose',
                    management: 'Drenagem controlada, cultivos adaptados'
                },
                'RQg': {
                    name: 'Neossolo Quartzarênico Hidromórfico',
                    composition: 'Essencialmente areia quartzosa, pouco desenvolvido',
                    ph: '4.5 - 6.0',
                    drainage: 'Variável, pode ter lençol freático alto',
                    depth: 'Variável',
                    fertility: 'Muito baixa',
                    climate: 'Tropical, áreas de transição',
                    cultures: 'Reflorestamento, cultivos com irrigação',
                    characteristics: 'Solo arenoso, pouco desenvolvido, influência do lençol freático',
                    limitations: 'Baixa fertilidade, lixiviação de nutrientes',
                    management: 'Irrigação, adubação frequente, matéria orgânica'
                }
            };


            // Estado atual
            let currentSearchMode = 'region';
            let selectedSoil = null;

            // Elementos DOM
            const searchTabs = document.querySelectorAll('.search-tab');
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const infoPanel = document.getElementById('infoPanel');
            const mapTooltip = document.getElementById('mapTooltip');
            const realMapImage = document.getElementById('realMapImage');
            const soilMap = document.getElementById('soilMap');

            // Inicialização
            document.addEventListener('DOMContentLoaded', function() {
                initializeTabs();
                initializeSearch();
                initializeMapAreas();
            });

            // Tabs de busca
            function initializeTabs() {
                searchTabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const mode = this.dataset.tab;
                        switchSearchMode(mode);
                    });
                });
            }

            function switchSearchMode(mode) {
                currentSearchMode = mode;

                searchTabs.forEach(tab => {
                    tab.classList.toggle('active', tab.dataset.tab === mode);
                });

                searchInput.placeholder = mode === 'region' ?
                    'Digite o nome da solo...' :
                    'Digite o nome da cidade...';

                searchInput.value = '';
                searchResults.innerHTML = '';
            }

            // Sistema de busca
            function initializeSearch() {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim().toLowerCase();

                    if (query.length < 2) {
                        searchResults.innerHTML = '';
                        return;
                    }

                    let results = [];

                    if (currentSearchMode === 'region') {
                        // Buscar por tipos de solo (regiões)
                        results = Object.keys(soilDatabase).filter(soil => {
                            return soilDatabase[soil].name.toLowerCase().includes(query);
                        }).map(soil => ({
                            type: 'soil',
                            id: soil,
                            name: soilDatabase[soil].name
                        }));
                    } else {
                        // Buscar por cidades
                        results = Object.keys(citiesDatabase).filter(city => {
                            return city.toLowerCase().includes(query);
                        }).map(city => ({
                            type: 'city',
                            id: city,
                            name: city,
                            soils: citiesDatabase[city]
                        }));
                    }

                    displaySearchResults(results);
                });
            }

            function displaySearchResults(results) {
                if (results.length === 0) {
                    searchResults.innerHTML = '<div class="search-result-item">Nenhum resultado encontrado</div>';
                    return;
                }

                searchResults.innerHTML = '';

                results.slice(0, 10).forEach(result => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item';

                    if (result.type === 'soil') {
                        item.textContent = result.name;
                        item.addEventListener('click', () => {
                            showSoilInfo(result.id);
                            searchInput.value = '';
                            searchResults.innerHTML = '';
                        });
                    } else {
                        item.innerHTML = `
                        <div class="city-search-result">
                            <strong>${result.name}</strong>
                            <div class="city-soils">Solos: ${result.soils.join(', ')}</div>
                        </div>
                    `;
                        item.addEventListener('click', () => {
                            showCityInfo(result.name, result.soils);
                            searchInput.value = '';
                            searchResults.innerHTML = '';
                        });
                    }

                    searchResults.appendChild(item);
                });
            }

            // Inicialização das áreas do mapa
            function initializeMapAreas() {
                const areas = soilMap.getElementsByTagName('area');

                Array.from(areas).forEach(area => {
                    area.addEventListener('click', function(e) {
                        e.preventDefault();
                        const soilType = this.dataset.soil;
                        showSoilInfo(soilType);
                    });

                    area.addEventListener('mouseover', function(e) {
                        const soilType = this.dataset.soil;
                        const soilName = soilDatabase[soilType]?.name || 'Tipo de solo desconhecido';
                        showTooltip(e, soilName);
                    });

                    area.addEventListener('mouseout', function() {
                        hideTooltip();
                    });
                });
            }

            // Exibir informações do solo
            function showSoilInfo(soilType) {
                const soil = soilDatabase[soilType];

                if (!soil) {
                    infoPanel.innerHTML = `
                    <div class="soil-info-card">
                        <h3 class="soil-title">Tipo de solo não encontrado</h3>
                        <p>Não foi possível encontrar informações para este tipo de solo.</p>
                    </div>
                `;
                    return;
                }

                // Encontrar cidades com este tipo de solo
                const citiesWithThisSoil = Object.keys(citiesDatabase).filter(
                    city => citiesDatabase[city].includes(soilType)
                );

                infoPanel.innerHTML = `
                <div class="soil-info-card">
                    <h3 class="soil-title">${soil.name} (${soilType})</h3>
                    
                    <div class="info-grid-enhanced">
                        <div class="info-item-enhanced">
                            <span class="info-label">Composição</span>
                            <span class="info-value">${soil.composition}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">pH</span>
                            <span class="info-value">${soil.ph}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Drenagem</span>
                            <span class="info-value">${soil.drainage}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Profundidade</span>
                            <span class="info-value">${soil.depth}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Fertilidade</span>
                            <span class="info-value">${soil.fertility}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Clima</span>
                            <span class="info-value">${soil.climate}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Culturas</span>
                            <span class="info-value">${soil.cultures}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Características</span>
                            <span class="info-value">${soil.characteristics}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Limitações</span>
                            <span class="info-value">${soil.limitations}</span>
                        </div>
                        
                        <div class="info-item-enhanced">
                            <span class="info-label">Manejo</span>
                            <span class="info-value">${soil.management}</span>
                        </div>
                    </div>
                    
                    ${citiesWithThisSoil.length > 0 ? `
                        <div class="cities-found">
                            <h4 style="margin-bottom: 10px;">🏙️ Cidades com este solo:</h4>
                            ${citiesWithThisSoil.slice(0, 5).map(city => `
                                <div class="city-item">
                                    <span class="city-name">${city}</span>
                                    <span class="city-soil">${citiesDatabase[city].join(', ')}</span>
                                </div>
                            `).join('')}
                            ${citiesWithThisSoil.length > 5 ? `
                                <p style="margin-top: 10px; font-size: 12px;">
                                    + ${citiesWithThisSoil.length - 5} outras cidades
                                </p>
                            ` : ''}
                        </div>
                    ` : ''}
                </div>
            `;

                selectedSoil = soilType;
            }

            // Exibir informações da cidade
            function showCityInfo(cityName, soilTypes) {
                infoPanel.innerHTML = `
                <div class="soil-info-card">
                    <h3 class="soil-title">🏙️ ${cityName}</h3>
                    <p style="margin-bottom: 15px;">Tipos de solo encontrados nesta região:</p>
                    
                    <div class="cities-found">
                        ${soilTypes.map(soilType => {
                const soil = soilDatabase[soilType];
                return `
                                <div class="city-item" style="cursor: pointer;" data-soil="${soilType}">
                                    <span class="city-name">${soil ? soil.name : soilType}</span>
                                    <span class="city-soil">${soilType}</span>
                                </div>
                            `;
            }).join('')}
                    </div>
                </div>
            `;

                // Adicionar event listeners para os itens de solo
                document.querySelectorAll('.city-item[data-soil]').forEach(item => {
                    item.addEventListener('click', function() {
                        const soilType = this.dataset.soil;
                        showSoilInfo(soilType);
                    });
                });
            }

            // Tooltip functions
            function showTooltip(event, text) {
                mapTooltip.textContent = text;
                mapTooltip.classList.add('show');

                // Posicionar o tooltip próximo ao cursor
                mapTooltip.style.left = (event.pageX + 10) + 'px';
                mapTooltip.style.top = (event.pageY + 10) + 'px';
            }

            function hideTooltip() {
                mapTooltip.classList.remove('show');
            }
        </script>
    <?php endif; ?>
</body>

</html>