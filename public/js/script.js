// BOTÓN DESPLEGABLE NAVBAR
document.addEventListener("DOMContentLoaded", function() {
    const dropdown = document.querySelector('.productos-dropdown');
    const dropdownContent = dropdown.querySelector('.dropdown-content');
    
    dropdown.addEventListener('mouseover', function() {
        dropdownContent.classList.add('show');
    });

    dropdown.addEventListener('mouseout', function() {
        dropdownContent.classList.remove('show');
    });
});

// ------------------------------------------------------------------------------------
// CARRUSEL DE IMÁGENES EN INICIO
let currentSlide = 0;
const slides = document.querySelectorAll(".carousel-slide");
const totalSlides = slides.length;
const carouselContainer = document.getElementById("carouselContainer");

// Función para mostrar el slide actual
function showSlide(index) {
    if (index >= totalSlides) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = index;
    }

    // Mover el contenedor del carrusel
    carouselContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
}

// Función para avanzar al siguiente slide
function nextSlide() {
    showSlide(currentSlide + 1);
}

// Función para retroceder al slide anterior
function prevSlide() {
    showSlide(currentSlide - 1);
}

// Evento para los botones de navegación
document.querySelector(".next-button").addEventListener("click", nextSlide);
document.querySelector(".prev-button").addEventListener("click", prevSlide);

// Auto-slide cada 5 segundos
let autoSlide = setInterval(nextSlide, 5000);

// Pausar auto-slide al pasar el mouse por el carrusel
document.querySelector(".carousel").addEventListener("mouseenter", () => {
    clearInterval(autoSlide);
});

// Reanudar auto-slide al salir del carrusel
document.querySelector(".carousel").addEventListener("mouseleave", () => {
    autoSlide = setInterval(nextSlide, 5000);
});

// Inicializa el carrusel
document.addEventListener("DOMContentLoaded", () => {
    showSlide(currentSlide);
});


// ------------------------------------------------------------------------------------
// CALCULADORA DE PRECIOS
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

// ------------------------------------------------------------------------------------
// ATAJO PARA DASHBOARD
document.addEventListener("keydown", function(event) {
    // Combinación de teclas: Ctrl + Shift + Y
    if (event.ctrlKey && event.shiftKey && event.key === "Y") {
        // Redirige al panel de administración
        window.location.href = "/nuevopack/admin-xyz2025/login.php";
    }
});