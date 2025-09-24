
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('criarContaForm');
    const usernameInput = document.getElementById('usernameInput');
    const passwordInput = document.getElementById('passwordInput');
    const confirmPasswordInput = document.getElementById('confirmPasswordInput');
    const submitBtn = document.getElementById('submitBtn');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    // Validação em tempo real do nome de usuário
    usernameInput.addEventListener('input', function () {
        const username = this.value;
        const validation = document.getElementById('usernameValidation');

        if (username.length < 3) {
            showValidation('usernameValidation', 'Nome de usuário deve ter pelo menos 3 caracteres', 'error');
            this.classList.add('invalid');
            this.classList.remove('valid');
        } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            showValidation('usernameValidation', 'Use apenas letras, números e underscore', 'error');
            this.classList.add('invalid');
            this.classList.remove('valid');
        } else {
            showValidation('usernameValidation', 'Nome de usuário válido!', 'success');
            this.classList.add('valid');
            this.classList.remove('invalid');
        }
        updateSubmitButton();
    });

    // Validação da força da senha
    passwordInput.addEventListener('input', function () {
        const password = this.value;
        const strength = calculatePasswordStrength(password);

        if (password.length > 0) {
            passwordStrength.classList.add('show');
            updateStrengthMeter(strength);
        } else {
            passwordStrength.classList.remove('show');
        }

        if (password.length < 6) {
            showValidation('passwordValidation', 'Senha deve ter pelo menos 6 caracteres', 'error');
            this.classList.add('invalid');
            this.classList.remove('valid');
        } else if (strength < 3) {
            showValidation('passwordValidation', 'Senha fraca. Adicione números e símbolos', 'error');
            this.classList.add('invalid');
            this.classList.remove('valid');
        } else {
            showValidation('passwordValidation', 'Senha forte!', 'success');
            this.classList.add('valid');
            this.classList.remove('invalid');
        }

        // Revalidar confirmação de senha
        if (confirmPasswordInput.value) {
            confirmPasswordInput.dispatchEvent(new Event('input'));
        }

        updateSubmitButton();
    });

    // Validação da confirmação de senha
    confirmPasswordInput.addEventListener('input', function () {
        const password = passwordInput.value;
        const confirmPassword = this.value;

        if (confirmPassword !== password) {
            showValidation('confirmPasswordValidation', 'As senhas não coincidem', 'error');
            this.classList.add('invalid');
            this.classList.remove('valid');
        } else if (confirmPassword.length > 0) {
            showValidation('confirmPasswordValidation', 'Senhas coincidem!', 'success');
            this.classList.add('valid');
            this.classList.remove('invalid');
        }
        updateSubmitButton();
    });

    function calculatePasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        return strength;
    }

    function updateStrengthMeter(strength) {
        const percentage = (strength / 5) * 100;
        strengthFill.style.width = percentage + '%';

        if (strength <= 2) {
            strengthFill.className = 'strength-fill strength-weak';
            strengthText.textContent = 'Senha fraca';
        } else if (strength <= 3) {
            strengthFill.className = 'strength-fill strength-medium';
            strengthText.textContent = 'Senha média';
        } else {
            strengthFill.className = 'strength-fill strength-strong';
            strengthText.textContent = 'Senha forte';
        }
    }

    function showValidation(elementId, message, type) {
        const element = document.getElementById(elementId);
        element.textContent = message;
        element.className = `validation-message show ${type}`;
    }

    function updateSubmitButton() {
        const isValid = usernameInput.classList.contains('valid') &&
            passwordInput.classList.contains('valid') &&
            confirmPasswordInput.classList.contains('valid');

        submitBtn.disabled = !isValid;
    }

    // Prevenir envio se o formulário não estiver válido
    form.addEventListener('submit', function (e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }

        // Adicionar loading state
        submitBtn.innerHTML = 'Criando conta...';
        submitBtn.disabled = true;
    });
});