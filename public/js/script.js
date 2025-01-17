// Variables y funciones para el carrusel
let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;

    if (index >= totalSlides) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = index;
    }

    // Oculta todos los slides y muestra el actual
    slides.forEach((slide, i) => {
        slide.style.display = i === currentSlide ? 'flex' : 'none';
    });
}

// Funciones para cambiar de slide
function nextSlide() {
    document.querySelector('.next-button').addEventListener('click', nextSlide);
    showSlide(currentSlide + 1);
}

function prevSlide() {
    document.querySelector('.prev-button').addEventListener('click', prevSlide);
    showSlide(currentSlide - 1);
}

// Inicializa el carrusel mostrando el primer slide
document.addEventListener('DOMContentLoaded', () => {
    showSlide(currentSlide);
});

// ------------------------------------------------------------------------------------
// JS PARA LA CALCULADORA DE PRECIOS
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el checkbox "Barniz" y el contenedor de "Barniz Adicional"
    const barnizCheckbox = document.getElementById('barniz');
    const barnizAdicional = document.querySelector('.barniz-adicional');
    
    // Función para actualizar la visibilidad del bloque "barniz-adicional"
    function toggleBarnizAdicional() {
        if (barnizCheckbox.checked) {
            barnizAdicional.style.display = 'block'; // Mostrar
        } else {
            barnizAdicional.style.display = 'none'; // Ocultar
        }
    }

    // Inicializar el estado de la visibilidad al cargar la página
    toggleBarnizAdicional();

    // Escuchar el cambio de estado del checkbox "Barniz"
    barnizCheckbox.addEventListener('change', toggleBarnizAdicional);
    
    // Escuchar cambios en el formulario para actualizar el precio sin recargar la página
    document.getElementById('calculadora-form').addEventListener('input', function () {
        const cantidadPliegos = document.getElementById('cantidadPliegos').value;
        const cantidadPosturas = document.getElementById('cantidadPosturas').value;
        const cantidadMillares = document.getElementById('cantidadMillares').value;
        const fotocromo = document.getElementById('fotocromo').checked;
        const barniz = barnizCheckbox.checked;
        const cantidadBarnizMillares = document.getElementById('cantidadBarnizMillares').value;

        // Aquí se realizaría el cálculo del precio estimado. Se simula con valores fijos
        let precioBase = 100; // Precio base ficticio para realizar el ejemplo
        let precioTotal = precioBase * cantidadPliegos * cantidadPosturas * cantidadMillares;
        
        // Actualizamos el precio total
        document.getElementById('precioTotal').textContent = precioTotal.toFixed(2);
    });
});

// PROCESAMIENTO DE CORREOS ELECTRÓNICOS ENVIADOS POR FORMULARIOS  
// Esperar a que el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-contacto');
    const mensajeFlotante = document.getElementById('mensaje-flotante');
    const mensajeTexto = document.getElementById('mensaje-texto');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const email = document.getElementById('email').value;
        const mensaje = document.getElementById('mensaje').value;

        const data = new FormData();
        data.append('nombre', nombre);
        data.append('email', email);
        data.append('mensaje', mensaje);

        fetch('/procesar_correo.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())  // Parsear la respuesta JSON
        .then(data => {
            console.log('Respuesta del servidor:', data); 
            if (data.status === 'success') {
                mensajeTexto.innerText = data.message;
                mensajeFlotante.style.display = 'block';
            } else {
                console.error('Error del servidor:', data.message); // Mostrar mensaje de error
            }
            setTimeout(() => {
                mensajeFlotante.style.display = 'none';
            }, 5000);
        })
        .catch(error => {
            console.error('Error en el fetch:', error);
        });
    });
});
