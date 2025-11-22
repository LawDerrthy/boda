// Script para la galería
const galeriaGrid = document.getElementById('galeriaGrid');
const filtrosBtns = document.querySelectorAll('.filtro-btn');
const modal = document.getElementById('modal');
const modalClose = document.getElementById('modalClose');
const modalBody = document.getElementById('modalBody');
const modalInfo = document.getElementById('modalInfo');
const estadisticas = document.getElementById('estadisticas');

let archivos = [];
let filtroActual = 'todos';

// Cargar archivos al iniciar
document.addEventListener('DOMContentLoaded', () => {
    cargarArchivos();
});

// Eventos de filtros
filtrosBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Remover clase active de todos los botones
        filtrosBtns.forEach(b => b.classList.remove('active'));
        // Agregar clase active al botón clickeado
        btn.classList.add('active');
        
        filtroActual = btn.dataset.tipo;
        cargarArchivos();
    });
});

// Cerrar modal
modalClose.addEventListener('click', () => {
    modal.classList.remove('show');
    modalBody.innerHTML = '';
    modalInfo.innerHTML = '';
});

// Cerrar modal al hacer clic fuera
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.remove('show');
        modalBody.innerHTML = '';
        modalInfo.innerHTML = '';
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('show')) {
        modal.classList.remove('show');
        modalBody.innerHTML = '';
        modalInfo.innerHTML = '';
    }
});

// Cargar archivos desde la API
async function cargarArchivos() {
    galeriaGrid.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Cargando archivos...</div>';
    
    try {
        const response = await fetch(`api_galeria.php?tipo=${filtroActual}`);
        const data = await response.json();
        
        if (data.success) {
            archivos = data.archivos;
            mostrarArchivos(archivos);
            mostrarEstadisticas(data.estadisticas);
        } else {
            galeriaGrid.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><h3>Error</h3><p>${data.message}</p></div>`;
        }
    } catch (error) {
        galeriaGrid.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><h3>Error</h3><p>Error al cargar los archivos: ${error.message}</p></div>`;
    }
}

// Mostrar archivos en la galería
function mostrarArchivos(archivosFiltrados) {
    if (archivosFiltrados.length === 0) {
        galeriaGrid.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>No hay archivos</h3>
                <p>No se encontraron archivos para mostrar.</p>
            </div>
        `;
        return;
    }
    
    galeriaGrid.innerHTML = '';
    
    archivosFiltrados.forEach(archivo => {
        const item = crearItemGaleria(archivo);
        galeriaGrid.appendChild(item);
    });
}

// Crear item de galería
function crearItemGaleria(archivo) {
    const div = document.createElement('div');
    div.className = 'galeria-item';
    
    if (!archivo.existe) {
        div.classList.add('error');
    }
    
    let contenido = '';
    
    if (archivo.es_imagen) {
        contenido = `
            <img src="${archivo.ruta_archivo}" alt="${archivo.nombre_original}" class="galeria-imagen" 
                 onerror="this.parentElement.classList.add('error')">
            <div class="galeria-overlay">
                <i class="fas fa-search-plus galeria-overlay-icon"></i>
            </div>
        `;
    } else if (archivo.es_video) {
        contenido = `
            <video class="galeria-video" preload="metadata">
                <source src="${archivo.ruta_archivo}" type="${archivo.tipo_archivo}">
            </video>
            <div class="galeria-overlay">
                <i class="fas fa-play-circle galeria-overlay-icon"></i>
            </div>
        `;
    } else {
        contenido = `
            <div style="height: 200px; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                <i class="fas fa-file" style="font-size: 3rem; color: #999;"></i>
            </div>
        `;
    }
    
    div.innerHTML = `
        ${contenido}
        <div class="galeria-info">
            <div class="galeria-nombre" title="${archivo.nombre_original}">${archivo.nombre_original}</div>
            <div class="galeria-meta">
                <span class="galeria-tipo">
                    <i class="fas fa-${archivo.es_imagen ? 'image' : archivo.es_video ? 'video' : 'file'}"></i>
                    ${archivo.es_imagen ? 'Imagen' : archivo.es_video ? 'Video' : 'Archivo'}
                </span>
                <span>${formatearTamaño(archivo.tamano_archivo)}</span>
            </div>
        </div>
    `;
    
    // Evento click para abrir modal
    div.addEventListener('click', () => {
        abrirModal(archivo);
    });
    
    return div;
}

// Abrir modal con archivo
function abrirModal(archivo) {
    modalBody.innerHTML = '';
    modalInfo.innerHTML = '';
    
    let contenido = '';
    
    if (archivo.es_imagen) {
        contenido = `<img src="${archivo.ruta_archivo}" alt="${archivo.nombre_original}" style="max-width: 100%; max-height: 60vh; border-radius: 10px;">`;
    } else if (archivo.es_video) {
        contenido = `
            <video controls style="width: 100%; max-height: 60vh; border-radius: 10px;">
                <source src="${archivo.ruta_archivo}" type="${archivo.tipo_archivo}">
                Tu navegador no soporta la reproducción de videos.
            </video>
        `;
    } else {
        contenido = `
            <div style="padding: 3rem; text-align: center;">
                <i class="fas fa-file" style="font-size: 5rem; color: #999;"></i>
                <p>Vista previa no disponible</p>
            </div>
        `;
    }
    
    modalBody.innerHTML = contenido;
    
    // Información del archivo
    modalInfo.innerHTML = `
        <div class="modal-info-item">
            <span class="modal-info-label">Nombre:</span>
            <span class="modal-info-value">${archivo.nombre_original}</span>
        </div>
        <div class="modal-info-item">
            <span class="modal-info-label">Tipo:</span>
            <span class="modal-info-value">${archivo.tipo_archivo}</span>
        </div>
        <div class="modal-info-item">
            <span class="modal-info-label">Tamaño:</span>
            <span class="modal-info-value">${formatearTamaño(archivo.tamano_archivo)}</span>
        </div>
        <div class="modal-info-item">
            <span class="modal-info-label">Fecha de subida:</span>
            <span class="modal-info-value">${formatearFecha(archivo.fecha_subida)}</span>
        </div>
    `;
    
    modal.classList.add('show');
}

// Mostrar estadísticas
function mostrarEstadisticas(stats) {
    estadisticas.innerHTML = `
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-number">${stats.total}</div>
            <div class="stat-label">Total Archivos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-image"></i>
            </div>
            <div class="stat-number">${stats.imagenes}</div>
            <div class="stat-label">Imágenes</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-video"></i>
            </div>
            <div class="stat-number">${stats.videos}</div>
            <div class="stat-label">Videos</div>
        </div>
    `;
}

// Formatear tamaño
function formatearTamaño(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Formatear fecha
function formatearFecha(fechaString) {
    const fecha = new Date(fechaString);
    return fecha.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

