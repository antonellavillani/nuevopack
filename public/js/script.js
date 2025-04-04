console.log("El script se ejecutó correctamente.");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM completamente cargado");

    const serviciosBtn = document.getElementById("servicios-btn");
    console.log("Botón Servicios:", serviciosBtn);

    if (serviciosBtn) {
        serviciosBtn.addEventListener("click", function (event) {
            event.preventDefault();
            console.log("Click detectado en el botón Servicios");

            if (window.location.pathname.endsWith("index.php") || window.location.pathname === "/") {
                const serviciosSection = document.getElementById("servicios-section");
                if (serviciosSection) {
                    console.log("Sección encontrada, haciendo scroll");
                    serviciosSection.scrollIntoView({ behavior: "smooth" });
                } else {
                    console.log("⚠️ Sección NO encontrada en index.php");
                }
            } else {
                console.log("No estamos en index.php, redirigiendo...");
                sessionStorage.setItem("scrollToServicios", "true"); // Guarda la intención en el almacenamiento temporal
                window.location.href = "index.php"; // Redirige sin el hash
            }
        });
    } else {
        console.log("⚠️ El botón 'Servicios' NO se encontró en el DOM.");
    }

    // Comprobar si viene de otra página con intención de hacer scroll
    if (sessionStorage.getItem("scrollToServicios") === "true") {
        sessionStorage.removeItem("scrollToServicios"); // Elimina la intención para evitar loops
        setTimeout(() => {
            const serviciosSection = document.getElementById("servicios-section");
            if (serviciosSection) {
                console.log("Redirigido desde otra página, haciendo scroll ahora.");
                serviciosSection.scrollIntoView({ behavior: "smooth" });
            }
        }, 500); // Pequeño delay para asegurar que la página haya cargado completamente
    }
    // Menú hamburguesa y submenú móviles
    const hamburguesaBtn = document.getElementById("hamburguesa-btn");
    const menuMovil = document.getElementById("menu-movil");
    const movilServicios = document.getElementById("movil-servicios");
    const submenuMovil = document.querySelector(".submenu-movil");

    // Mostrar/ocultar el menú móvil al hacer clic en el botón hamburguesa
    hamburguesaBtn.addEventListener("click", function () {
        if (menuMovil.style.display === "block") {
            menuMovil.style.display = "none";
        } else {
            menuMovil.style.display = "block";
        }
    });

    // Mostrar/ocultar el submenú de 'Servicios' al hacer clic
    movilServicios.addEventListener("click", function (event) {
        event.preventDefault();
        submenuMovil.style.display = (submenuMovil.style.display === "block") ? "none" : "block";
    });

    // Evitar que 'Servicios' haga scroll en pantallas pequeñas
    const serviciosBtnMovil = document.getElementById("servicios-btn");
    if (serviciosBtnMovil) {
        serviciosBtnMovil.addEventListener("click", function (event) {
            if (window.innerWidth <= 768) {
                event.preventDefault(); // Evita el scroll en móviles
            }
        });
    }

});

// Obtener todos los enlaces dentro del menú móvil
const menuLinks = document.querySelectorAll('.menu-movil a');

// Restablecer el estado del botón hamburguesa cuando se carga una nueva página
window.addEventListener('load', () => {
    const menu = document.querySelector('.menu-movil');
    const hamburguesa = document.querySelector('.hamburguesa');
    
    // Asegurarse de que el menú esté cerrado cuando se recargue la página
    menu.style.display = 'none';
    
    // Opcional: cambiar el ícono del botón hamburguesa a su estado inicial si es necesario
    hamburguesa.classList.remove('is-active'); // Si usas alguna clase activa para indicar el estado
});


// Detectar clic fuera del menú móvil para cerrarlo
document.addEventListener('click', function(event) {
    const menu = document.querySelector('.menu-movil');
    const hamburguesa = document.querySelector('.hamburguesa');
    
    // Verificar si el clic fue fuera del menú o el botón hamburguesa
    if (!menu.contains(event.target) && !hamburguesa.contains(event.target)) {
        menu.style.display = 'none'; // Cerrar el menú si el clic fue fuera
    }
});

// ------------------------------------------------------------------------------------
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
