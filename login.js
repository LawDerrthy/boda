// Script para la página de login
const loginForm = document.getElementById('loginForm');
const passwordInput = document.getElementById('password');
const passwordToggle = document.getElementById('passwordToggle');

// Mostrar/ocultar contraseña
passwordToggle.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    const icon = passwordToggle.querySelector('i');
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
});

// Auto-focus en el primer campo
window.addEventListener('load', () => {
    document.getElementById('username').focus();
});

// Validación del formulario
loginForm.addEventListener('submit', (e) => {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    
    if (!username || !password) {
        e.preventDefault();
        alert('Por favor completa todos los campos');
        return false;
    }
});

// Prevenir envío con Enter si los campos están vacíos
document.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        
        if (!username || !password) {
            e.preventDefault();
        }
    }
});

