<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Núcleo Boitatá - Criar conta</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="icon" href="img/icon.ico" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Dirt&display=swap"rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"/>
  </head>

  <body class="criar-conta-page">
    
    <header class="cabecalho">
      <div class="img_cabecalho">
        <img src="img/logonome-removebg-preview.png" alt="Logo Núcleo Boitatá"/>
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
              <a href="mapa.php">Mapas</a>
            </li>
            <li>
              <a href="projeto.html">O Projeto</a>
            </li>
          </ul>
        </nav>
      </div>
    </header>

    <div class="criar-conta-container">
   

      <div class="criar-conta-card">
        <h1 class="criar-conta-title">CRIAR CONTA</h1>
        <p class="criar-conta-subtitle">
          Junte-se à nossa comunidade dedicada à preservação e ao plantio responsável
        </p>

        <form action="php/conexao_cadastro.php" method="post" id="criarContaForm">
          <div class="form-group-criar">
            <input 
              type="email"  
              name="username" 
              required 
              class="form-input-criar"
              placeholder=" "
              id="usernameInput"
              autocomplete="username"
              minlength="3"
              maxlength="60"
            />
            <label class="form-label-criar" for="usernameInput">E-mail</label>
            <div class="validation-message" id="usernameValidation"></div>
          </div>

          <div class="form-group-criar">
            <input
              type="password"
              name="password"
              required
              class="form-input-criar"
              placeholder=" "
              id="passwordInput"
              autocomplete="new-password"
              minlength="6"
            />
            <label class="form-label-criar" for="passwordInput">Senha</label>
            <div class="password-strength" id="passwordStrength">
              <div class="strength-bar">
                <div class="strength-fill" id="strengthFill"></div>
              </div>
              <span id="strengthText">Digite uma senha</span>
            </div>
            <div class="validation-message" id="passwordValidation"></div>
          </div>

          <div class="form-group-criar">
            <input 
              type="password"
              name="confirm_password"
              required
              class="form-input-criar"
              placeholder=" "
              id="confirmPasswordInput"
              autocomplete="new-password"
            />
            <label class="form-label-criar" for="confirmPasswordInput">Confirmar Senha</label>
            <div class="validation-message" id="confirmPasswordValidation"></div>
          </div>

          <div class="form-actions-criar">
            <button 
              type="submit" 
              class="btn-criar"
              id="submitBtn"
            >
              Criar Conta
            </button>
          </div>

          <div class="login-link">
            <p>Já possui uma conta? <a href="Login.php">Fazer Login</a></p>
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

    <script src="JavaScript/Infor_cria_conta.js">
    </script>
  </body>
</html>