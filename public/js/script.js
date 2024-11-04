let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;

    // Asegúrate de que el índice esté dentro del rango
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
    showSlide(currentSlide + 1);
}

function prevSlide() {
    showSlide(currentSlide - 1);
}

// Inicializa el carrusel mostrando el primer slide
document.addEventListener('DOMContentLoaded', () => {
    showSlide(currentSlide);
});
