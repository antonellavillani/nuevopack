let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
ghnj
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

// ------------------------------------------------------------------------------------
// JS PARA LA CALCULADORA DE PRECIOS
// Funciones para la calculadora de precios
function calcularPrecio() {
    let precioTotal = 0;

    // Obtener los valores del formulario
    let cantidadPliegos = parseInt(document.getElementById('cantidadPliegos').value) || 0;
    let cantidadPosturas = parseInt(document.getElementById('cantidadPosturas').value) || 0;
    let cantidadMillares = parseInt(document.getElementById('cantidadMillares').value) || 0;
    let cantidadBarniz = parseInt(document.getElementById('cantidadBarniz').value) || 0;

    // Precios (pasados desde PHP)
    let precioMillar = parseFloat('<?= $preciosServicios["millar"] ?>') || 0;
    let precioPostura = parseFloat('<?= $preciosServicios["postura"] ?>') || 0;
    let precioBarniz = parseFloat('<?= $preciosServicios["barniz"] ?>') || 0;
    let precioTroquelado = parseFloat('<?= $preciosServicios["troquelado"] ?>') || 0;
    let precioPegado = parseFloat('<?= $preciosServicios["pegado"] ?>') || 0;
    let precioChapa = parseFloat('<?= $preciosServicios["CTP"] ?>') || 0;

    // Fotocromia
    if (document.getElementById('fotocromia').checked) {
        precioTotal += cantidadPliegos * 4;  // Asegurándote de que el valor se pasa correctamente
    }

    // Millar (sin multiplicar por 1000, ya que cantidadMillares se refiere a la cantidad de millares)
    precioTotal += precioMillar * cantidadMillares;

    // Barniz
    if (document.getElementById('barniz').checked) {
        precioTotal += precioBarniz * cantidadPosturas * cantidadBarniz;
    }

    // Barniz sectorizado
    if (document.getElementById('barnizSectorizado').checked) {
        precioTotal += precioChapa; // Precio de chapa (CTP)
    }

    // Troquelado
    if (document.getElementById('troquelado').checked) {
        precioTotal += precioTroquelado * cantidadPosturas;
    }

    // Pegado de estuches
    if (document.getElementById('pegadoEstuches').checked) {
        // Ajusta esta parte según la cantidad de estuches
        if (cantidadMillares <= 5) {
            precioTotal += precioPegado;
        } else {
            precioTotal += precioPegado * cantidadPosturas;
        }
    }

    // Mostrar precio final
    document.getElementById('precioTotal').textContent = precioTotal.toFixed(2);
}
