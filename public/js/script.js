console.log("El script se ejecutó correctamente.");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM completamente cargado");

    // Botón de Servicios principal
    const serviciosBtn = document.getElementById("servicios-btn");
    if (serviciosBtn) {
        serviciosBtn.addEventListener("click", function (event) {
            event.preventDefault();
            if (window.location.pathname.endsWith("index.php") || window.location.pathname === "/") {
                const serviciosSection = document.getElementById("servicios-section");
                if (serviciosSection) {
                    serviciosSection.scrollIntoView({ behavior: "smooth" });
                }
            } else {
                sessionStorage.setItem("scrollToServicios", "true");
                window.location.href = "index.php";
            }
        });
    }

    // Detectar si debe hacer scroll tras redirección
    if (sessionStorage.getItem("scrollToServicios") === "true") {
        sessionStorage.removeItem("scrollToServicios");
        setTimeout(() => {
            const serviciosSection = document.getElementById("servicios-section");
            if (serviciosSection) {
                serviciosSection.scrollIntoView({ behavior: "smooth" });
            }
        }, 500);
    }

    // Menú hamburguesa
    const hamburguesaBtn = document.getElementById("hamburguesa-btn");
    const menuMovil = document.getElementById("menu-movil");
    if (hamburguesaBtn && menuMovil) {
        hamburguesaBtn.addEventListener("click", function () {
            menuMovil.style.display = (menuMovil.style.display === "block") ? "none" : "block";
        });
    }

    // Submenú de servicios móvil
    const movilServicios = document.getElementById("movil-servicios");
    const submenuMovil = document.querySelector(".submenu-movil");
    if (movilServicios && submenuMovil) {
        movilServicios.addEventListener("click", function (event) {
            event.preventDefault();
            submenuMovil.style.display = (submenuMovil.style.display === "block") ? "none" : "block";
        });
    }

    // Prevenir scroll en servicios-btn en móviles
    if (serviciosBtn && window.innerWidth <= 768) {
        serviciosBtn.addEventListener("click", function (event) {
            event.preventDefault();
        });
    }

    // Dropdown en navbar
    const dropdown = document.querySelector('.servicios-dropdown');
    if (dropdown) {
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        if (dropdownContent) {
            dropdown.addEventListener('mouseover', () => dropdownContent.classList.add('show'));
            dropdown.addEventListener('mouseout', () => dropdownContent.classList.remove('show'));
        }
    }

    // Carrusel de imágenes
    const slides = document.querySelectorAll(".carousel-slide");
    const carouselContainer = document.getElementById("carouselContainer");
    if (slides.length && carouselContainer) {
        let currentSlide = 0;
        const totalSlides = slides.length;

        function showSlide(index) {
            currentSlide = (index + totalSlides) % totalSlides;
            carouselContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        const nextBtn = document.querySelector(".next-button");
        const prevBtn = document.querySelector(".prev-button");

        if (nextBtn) nextBtn.addEventListener("click", nextSlide);
        if (prevBtn) prevBtn.addEventListener("click", prevSlide);

        let autoSlide = setInterval(nextSlide, 5000);

        const carousel = document.querySelector(".carousel");
        if (carousel) {
            carousel.addEventListener("mouseenter", () => clearInterval(autoSlide));
            carousel.addEventListener("mouseleave", () => autoSlide = setInterval(nextSlide, 5000));
        }

        showSlide(currentSlide);
    }

    // Calculadora de precios
    function toggleSection(checkboxId, sectionId) {
        const checkbox = document.getElementById(checkboxId);
        const section = document.getElementById(sectionId);
        if (checkbox && section) {
            section.style.display = checkbox.checked ? "block" : "none";
            checkbox.addEventListener('change', () => {
                section.style.display = checkbox.checked ? "block" : "none";
            });
        }
    }

    toggleSection('troquelado_toggle', 'troquelado_seccion');
    toggleSection('barniz_toggle', 'barniz_seccion');
    toggleSection('estuches_toggle', 'estuches_seccion');
});

// Menú móvil al cargar la página
window.addEventListener('load', () => {
    const menu = document.querySelector('.menu-movil');
    const hamburguesa = document.querySelector('.hamburguesa');
    if (menu) menu.style.display = 'none';
    if (hamburguesa) hamburguesa.classList.remove('is-active');
});

// Cerrar menú móvil al hacer clic fuera
document.addEventListener('click', function(event) {
    const menu = document.querySelector('.menu-movil');
    const hamburguesa = document.querySelector('.hamburguesa');
    if (menu && hamburguesa && !menu.contains(event.target) && !hamburguesa.contains(event.target)) {
        menu.style.display = 'none';
    }
});

// Atajo para Dashboard
document.addEventListener("keydown", function(event) {
    if (event.ctrlKey && event.shiftKey && event.key === "Y") {
        window.location.href = "/nuevopack/admin-xyz2025/login.php";
    }
});
