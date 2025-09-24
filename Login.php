<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Núcleo Boitatá - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="img/icon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Dirt&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>
    <?php
    // Verifica se há mensagens para exibir
    if (isset($_GET['status'])) {
        $alertClass = ($_GET['status'] === 'success') ? 'alert-success' : 'alert-danger';
        $message = ($_GET['status'] === 'success')
            ? 'Cadastro realizado com sucesso!'
            : 'Erro: ' . htmlspecialchars($_GET['message'] ?? 'Usuario ou senha incorretos');

        echo '<div class="alert ' . $alertClass . ' text-center">' . $message . '</div>';
    }
    ?>

    <header class="cabecalho">
        <div class="img_cabecalho">
            <img src="img/logonome-removebg-preview.png" alt="Logo Núcleo Boitatá">
        </div>

        <div class="botoes_cabecalho">
            <nav>
                <ul>
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a href="Login.php">Registro</a>
                    </li>
                    <li>
                        <a href="mapa.html">Mapas</a>
                    </li>
                    <li>
                        <a href="projeto.html">O Projeto</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="login-container">
        <!-- Partículas decorativas -->
        <div class="particle" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="top: 60%; left: 85%; animation-delay: 2s;"></div>
        <div class="particle" style="top: 30%; left: 75%; animation-delay: 4s;"></div>
        <div class="particle" style="top: 80%; left: 20%; animation-delay: 1s;"></div>
        <div class="particle" style="top: 15%; left: 60%; animation-delay: 3s;"></div>

        <div class="login-card">
            <h1 class="login-title">Bem-vindo</h1>
            <p class="login-subtitle">Faça login para continuar sua jornada</p>

            <form method="post" action="php/conexao_login.php">
                <div class="form-group">
                    <input
                        type="text"
                        name="username"
                        required
                        class="form-input"
                        placeholder=" "
                        autocomplete="username">
                    <label class="form-label">Usuário</label>
                </div>

                <div class="form-group">
                    <input
                        type="password"
                        name="password"
                        required
                        class="form-input"
                        placeholder=" "
                        autocomplete="current-password">
                    <label class="form-label">Senha</label>
                </div>

                <div class="login-actions">
                    <button type="submit" class="btn-login">
                        Entrar
                    </button>

                    <a href="criar-conta.php" class="create-account-link">
                        Criar Nova Conta
                    </a>
                </div>
            </form>
        </div>
    </div>

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

</body>

</html>