console.log("El script se ejecutó correctamente.");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM completamente cargado");

    // ---------------------- AOS Animation ----------------------
    AOS.init({
        duration: 1000,
        once: true
    });

    // ---------------------- Botón de Servicios (desktop y mobile) ----------------------
    const serviciosBtn = document.getElementById("servicios-btn");
    if (serviciosBtn) {
        serviciosBtn.addEventListener("click", function (event) {
            event.preventDefault();

            if (window.innerWidth <= 768) return; // Evita doble scroll en mobile

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

    if (sessionStorage.getItem("scrollToServicios") === "true") {
        sessionStorage.removeItem("scrollToServicios");
        setTimeout(() => {
            const serviciosSection = document.getElementById("servicios-section");
            if (serviciosSection) {
                serviciosSection.scrollIntoView({ behavior: "smooth" });
            }
        }, 500);
    }

    // ---------------------- Menú hamburguesa ----------------------
    const hamburguesaBtn = document.getElementById("hamburguesa-btn");
    const menuMovil = document.getElementById("menu-movil");
    if (hamburguesaBtn && menuMovil) {
        hamburguesaBtn.addEventListener("click", function () {
            menuMovil.style.display = (menuMovil.style.display === "block") ? "none" : "block";
        });
    }

    // ---------------------- Submenú de servicios móvil ----------------------
    const movilServicios = document.getElementById("movil-servicios");
    const submenuMovil = document.querySelector(".submenu-movil");
    if (movilServicios && submenuMovil) {
        movilServicios.addEventListener("click", function (event) {
            event.preventDefault();
            submenuMovil.style.display = (submenuMovil.style.display === "block") ? "none" : "block";
        });
    }

    // ---------------------- Dropdown en navbar (desktop) ----------------------
    const dropdown = document.querySelector('.servicios-dropdown');
    if (dropdown) {
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        if (dropdownContent) {
            dropdown.addEventListener('mouseover', () => dropdownContent.classList.add('show'));
            dropdown.addEventListener('mouseout', () => dropdownContent.classList.remove('show'));
        }
    }

    // ---------------------- Carrusel de imágenes ----------------------
    const slides = document.querySelectorAll(".carousel-slide");
    const carouselContainer = document.getElementById("carouselContainer");

    if (slides.length && carouselContainer) {
        let currentSlide = 0;
        const totalSlides = slides.length;
        const indicatorsContainer = document.getElementById("carouselIndicators");
        const indicators = [];

        for (let i = 0; i < totalSlides; i++) {
            const btn = document.createElement("button");
            btn.addEventListener("click", () => showSlide(i));
            indicatorsContainer.appendChild(btn);
            indicators.push(btn);
        }

        function showSlide(index) {
            currentSlide = (index + totalSlides) % totalSlides;
            carouselContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            indicators.forEach((btn, i) => {
                btn.classList.toggle("active", i === currentSlide);
            });
        }

        function nextSlide() { showSlide(currentSlide + 1); }
        function prevSlide() { showSlide(currentSlide - 1); }

        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        if (prevBtn) prevBtn.addEventListener("click", prevSlide);
        if (nextBtn) nextBtn.addEventListener("click", nextSlide);

        let autoSlide = setInterval(nextSlide, 5000);
        const carousel = document.querySelector(".carousel");

        if (carousel) {
            carousel.addEventListener("mouseenter", () => clearInterval(autoSlide));
            carousel.addEventListener("mouseleave", () => autoSlide = setInterval(nextSlide, 5000));
        }

        showSlide(currentSlide);
    }

    // ---------------------- Calculadora de precios ----------------------
    const secciones = [
        { checkboxId: "troquelado_toggle", seccionId: "troquelado_seccion" },
        { checkboxId: "barniz_toggle", seccionId: "barniz_seccion" },
        { checkboxId: "estuches_toggle", seccionId: "estuches_seccion" }
    ];

    secciones.forEach(({ checkboxId, seccionId }) => {
        const checkbox = document.getElementById(checkboxId);
        const seccion = document.getElementById(seccionId);
        if (checkbox && seccion) {
            seccion.style.display = checkbox.checked ? "block" : "none";
            checkbox.addEventListener("change", () => {
                seccion.style.display = checkbox.checked ? "block" : "none";
            });
        }
    });

        // ---------------------- Llenar formulario de contacto según datos de la calculadora ----------
    const form = document.getElementById('form-calculadora');
    const resultadoDiv = document.getElementById('resultado-calculadora');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Evita recargar la página

        const formData = new FormData(form);
        function configurarBotonConsulta() {
            const botonConsulta = document.getElementById('boton-consulta');

            if (botonConsulta) {
                botonConsulta.addEventListener('click', function () {
                const resumenPedido = botonConsulta.getAttribute('data-resumen');

                // Scroll suave al formulario
                const formulario = document.getElementById('formulario-contacto');
                if (formulario) {
                    formulario.scrollIntoView({ behavior: 'smooth' });

                    // Llenar el campo de descripción automáticamente
                    const descripcion = document.getElementById('descripcion');
                    if (descripcion) {
                        descripcion.value = resumenPedido;
                    }
                }
        });
    }
}

        fetch('backend/calcular_precio.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            resultadoDiv.innerHTML = html;

        // Scroll dentro de la calculadora
        const contenedor = document.querySelector('.calculadora');
        const objetivo = document.getElementById('resultado-precio');
        
        if (contenedor && objetivo) {
            const offsetTop = objetivo.offsetTop - contenedor.offsetTop;
            contenedor.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }

            configurarBotonConsulta();

            // Reactivar botón de consulta dinámicamente
            const botonConsulta = document.getElementById('btn-enviar-consulta');
            if (botonConsulta) {
                botonConsulta.addEventListener('click', () => {
                    // Obtener valores de los campos
                    const ctp = document.getElementById('ctp').value;
                    const posturaImpresion = document.getElementById('postura_impresion').value;
                    const millarImpresion = document.getElementById('millar_impresion').value;

                    const troqueladoActivo = document.getElementById('troquelado_toggle').checked;
                    const bocas = document.getElementById('bocas').value;
                    const millarTroquelado = document.getElementById('millar_troquelado').value;

                    const barnizActivo = document.getElementById('barniz_toggle').checked;
                    const posturaBarniz = document.getElementById('postura_barniz').value;
                    const millarBarniz = document.getElementById('millar_barniz').value;

                    const estuchesActivo = document.getElementById('estuches_toggle').checked;
                    const medidaEstuche = document.querySelector('input[name="medida_estuche"]:checked');
                    const cantidadEstuches = document.getElementById('cantidad_estuches').value;

                    let descripcion = "Hola, me gustaría consultar por el siguiente pedido:\n\n";

                    if (ctp || posturaImpresion || millarImpresion) {
                        descripcion += "🔹 Impresión:\n";
                        if (ctp) descripcion += `- CTP (chapa): ${ctp}\n`;
                        if (posturaImpresion) descripcion += `- Postura: ${posturaImpresion}\n`;
                        if (millarImpresion) descripcion += `- Millares: ${millarImpresion}\n`;
                        descripcion += "\n";
                    }

                    if (troqueladoActivo && (bocas || millarTroquelado)) {
                        descripcion += "🔹 Troquelado:\n";
                        if (bocas) descripcion += `- Bocas: ${bocas}\n`;
                        if (millarTroquelado) descripcion += `- Millares: ${millarTroquelado}\n`;
                        descripcion += "\n";
                    }

                    if (barnizActivo && (posturaBarniz || millarBarniz)) {
                        descripcion += "🔹 Barniz:\n";
                        if (posturaBarniz) descripcion += `- Postura: ${posturaBarniz}\n`;
                        if (millarBarniz) descripcion += `- Millares: ${millarBarniz}\n`;
                        descripcion += "\n";
                    }

                    if (estuchesActivo && (cantidadEstuches || medidaEstuche)) {
                        descripcion += "🔹 Pegado de estuches:\n";
                        if (medidaEstuche) descripcion += `- Medida: ${medidaEstuche.value}\n`;
                        if (cantidadEstuches) descripcion += `- Cantidad: ${cantidadEstuches}\n`;
                        descripcion += "\n";
                    }

        descripcion += "Gracias, quedo a la espera de su respuesta.";

        // Llenar el textarea
        const campoDescripcion = document.getElementById('descripcion');
        if (campoDescripcion) {
            campoDescripcion.value = descripcion;
        }

        // Scroll suave al formulario
        const formulario = document.getElementById('form-contacto');
        if (formulario) {
            formulario.scrollIntoView({ behavior: 'smooth' });
        }
                });
            }
        })
        .catch(error => {
            resultadoDiv.innerHTML = "<p>Ocurrió un error al calcular el precio.</p>";
            console.error('Error:', error);
        });
    });

    // ---------------------- Formulario de contacto (ficha_servicio.php) ----------------------
    const conocio = document.getElementById('conocio');
    const inputOcultoOtro = document.getElementById('input-oculto-otro');
    if (conocio && inputOcultoOtro) {
        conocio.addEventListener('change', function () {
            inputOcultoOtro.style.display = this.value === 'otro' ? 'block' : 'none';
            if (this.value !== 'otro') {
                document.getElementById('conocio_otro').value = '';
            }
        });

        if (conocio.value !== 'otro') {
            inputOcultoOtro.style.display = 'none';
        }
    }

    const inputArchivo = document.getElementById('archivo');
    if (inputArchivo) {
        inputArchivo.addEventListener('change', function () {
            const nombreArchivo = this.files[0] ? this.files[0].name : 'Ningún archivo seleccionado';
            const archivoNombre = document.getElementById('archivo-nombre');
            if (archivoNombre) archivoNombre.textContent = nombreArchivo;
        });
    }

    const formContacto = document.getElementById('form-contacto');
    if (formContacto) {
        formContacto.addEventListener('submit', function (e) {
            e.preventDefault();
            const medio = document.getElementById('medio');
            const errorMedio = document.getElementById('error-medio');
            if (medio && medio.value === '') {
                errorMedio.style.display = 'block';
                medio.focus();
                return;
            } else {
                errorMedio.style.display = 'none';
            }

            const contenedor = document.querySelector('.scroll-formulario-contacto');
            const spinner = document.getElementById('spinner');
            
            if (contenedor && spinner) {
              spinner.style.display = 'flex';
              setTimeout(() => {
                contenedor.scrollTop = spinner.offsetTop;
              }, 50);
            }            
            
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('mensaje-respuesta').textContent = data;
                    spinner.style.display = 'none';
                    if (data.includes('Mensaje enviado correctamente')) {
                        this.reset();
                        document.getElementById('archivo-nombre').textContent = '';
                    }
                })
                .catch(error => {
                    document.getElementById('mensaje-respuesta').textContent = 'Ocurrió un error al enviar el mensaje. Por favor, intente nuevamente.';
                    spinner.style.display = 'none';
                    console.error(error);
                });
        });
    }

    const inputTelefono = document.getElementById('telefono');
    if (inputTelefono) {
        inputTelefono.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // ---------------------- Formulario de consultas (contacto.php) ----------------------
    const formConsulta = document.getElementById('form-consulta');
    if (formConsulta) {
        formConsulta.addEventListener('submit', function (e) {
            e.preventDefault();

            const spinner = document.getElementById('spinner');
            spinner.style.display = 'flex';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('mensaje-envio').textContent = data;
                    spinner.style.display = 'none';
                    if (data.includes('Mensaje enviado correctamente')) {
                        this.reset();
                    }
                })
                .catch(error => {
                    document.getElementById('mensaje-envio').textContent = 'Ocurrió un error al enviar el mensaje. Por favor, intente nuevamente.';
                    spinner.style.display = 'none';
                    console.error(error);
                });
        });
    } 
    /* Abrir imágenes de almanaques */
    document.querySelectorAll('.tarjeta-modelo img').forEach(img => {
        img.addEventListener('click', () => {
            const modal = document.getElementById('modalImagen');
            const imagenAmpliada = document.getElementById('imagenAmpliada');
            modal.style.display = 'block';
            imagenAmpliada.src = img.src;
            imagenAmpliada.alt = img.alt || 'Imagen ampliada';
        });
    });

    document.querySelector('.cerrar-modal').addEventListener('click', () => {
        document.getElementById('modalImagen').style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        const modal = document.getElementById('modalImagen');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

});

// ---------------------- Menú móvil al cargar y cerrar con clic externo ----------------------
window.addEventListener('load', () => {
    const menu = document.querySelector('.menu-movil');
    const hamburguesa = document.querySelector('.hamburguesa');
    if (menu) menu.style.display = 'none';
    if (hamburguesa) hamburguesa.classList.remove('is-active');
});

document.addEventListener('click', function (event) {
    const menu = document.querySelector('.menu-movil');
    const hamburguesa = document.querySelector('.hamburguesa');
    if (menu && hamburguesa && !menu.contains(event.target) && !hamburguesa.contains(event.target)) {
        menu.style.display = 'none';
    }
});

// ---------------------- Atajo secreto para Dashboard ----------------------
document.addEventListener("keydown", function (event) {
    if (event.ctrlKey && event.shiftKey && event.key === "Y") {
        window.location.href = "/nuevopack/admin-xyz2025/login.php";
    }
});
