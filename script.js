// Elementos del DOM
const formulario = document.getElementById('formularioSubida');
const archivoInput = document.getElementById('archivo');
const uploadArea = document.getElementById('uploadArea');
const fileName = document.getElementById('fileName');
const mensaje = document.getElementById('mensaje');
const resultado = document.getElementById('resultado');
const resultInfo = document.getElementById('resultInfo');
const submitBtn = document.getElementById('submitBtn');
const loginBtn = document.getElementById('loginBtn');

// Eventos del área de drag & drop
uploadArea.addEventListener('click', () => {
    archivoInput.click();
});

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('drag-over');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        archivoInput.files = files;
        mostrarNombreArchivo(files[0]);
    }
});

// Mostrar nombre del archivo seleccionado
archivoInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        mostrarNombreArchivo(e.target.files[0]);
    }
});

function mostrarNombreArchivo(file) {
    fileName.textContent = `📎 ${file.name} (${formatearTamaño(file.size)})`;
    fileName.classList.add('show');
}

// Formatear tamaño de archivo
function formatearTamaño(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Enviar formulario
formulario.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const archivo = archivoInput.files[0];
    
    if (!archivo) {
        mostrarMensaje('Por favor selecciona un archivo.', 'error');
        return;
    }
    
    // Validar tamaño (100 MB)
    const maxSize = 100 * 1024 * 1024;
    if (archivo.size > maxSize) {
        mostrarMensaje('El archivo excede el tamaño máximo de 100 MB.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('archivo', archivo);
    
    // Ocultar mensajes anteriores y mostrar loading
    ocultarMensaje();
    resultado.classList.remove('show');
    submitBtn.disabled = true;
    submitBtn.classList.add('loading');
    
    try {
        const response = await fetch('upload.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            mostrarMensaje('¡Archivo subido exitosamente! 🎉', 'success');
            mostrarResultado(data.data);
            formulario.reset();
            fileName.classList.remove('show');
        } else {
            mostrarMensaje('Error: ' + data.message, 'error');
        }
    } catch (error) {
        mostrarMensaje('Error al conectar con el servidor: ' + error.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
    }
});

// Mostrar mensaje
function mostrarMensaje(texto, tipo) {
    mensaje.textContent = texto;
    mensaje.className = `message ${tipo} show`;
    mensaje.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Ocultar mensaje
function ocultarMensaje() {
    mensaje.classList.remove('show');
}

// Mostrar resultado
function mostrarResultado(datos) {
    resultInfo.innerHTML = `
        <div class="result-item">
            <div class="result-label">ID</div>
            <div class="result-value">${datos.id}</div>
        </div>
        <div class="result-item">
            <div class="result-label">Nombre Original</div>
            <div class="result-value">${datos.nombre_original}</div>
        </div>
        <div class="result-item">
            <div class="result-label">Nombre Guardado</div>
            <div class="result-value">${datos.nombre_guardado}</div>
        </div>
        <div class="result-item">
            <div class="result-label">Ruta</div>
            <div class="result-value">${datos.ruta_archivo}</div>
        </div>
        <div class="result-item">
            <div class="result-label">Tipo</div>
            <div class="result-value">${datos.tipo_archivo}</div>
        </div>
        <div class="result-item">
            <div class="result-label">Tamaño</div>
            <div class="result-value">${formatearTamaño(datos.tamano_archivo)}</div>
        </div>
    `;
    resultado.classList.add('show');
    resultado.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Botón de iniciar sesión - redirigir a login.php
loginBtn.addEventListener('click', () => {
    window.location.href = 'login.php';
});

// Animación al cargar
window.addEventListener('load', () => {
    document.body.classList.add('fade-in');
});

