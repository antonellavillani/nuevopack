console.log("El script se ejecutó correctamente.");

document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM completamente cargado.");
  // ---------------------- Funciones ----------------------
  initAOS(); // AOS Animation
  initFormularioConsultaContacto(); // Formulario de consultas (contacto.php)
  initBotonServicios(); // Botón de Servicios (desktop y mobile)
  initMenuHamburguesa(); // Menú hamburguesa
  initSubmenuServiciosMovil(); // Submenú de servicios móvil
  initDropdownNavbarDesktop(); // Dropdown en navbar (desktop)
  initCarruselImagenes(); // Carrusel de imágenes (index.php)
  initCalculadoraPrecios(); // Calculadora de precios (ficha_servicio.php)
  initFormularioDesdeCalculadora(); // Llenar formulario de contacto según datos de la calculadora
  initFormularioContactoServicio(); // Formulario de contacto (ficha_servicio.php)
  initModalImagenAlmanaques(); // Abrir imágenes de almanaques (ficha_servicio.php)
  initAtajoDashboard(); // Atajo secreto para Dashboard
  initMenuMovilCargar_CerrarClickExterno(); // Menú móvil al cargar y cerrar con click externo
  initMap(); // API Google Maps
});

// ---------------------- AOS Animation ----------------------
function initAOS() {
  AOS.init({
    duration: 1000,
    once: true,
  });
}

// ---------------------- Formulario de consultas (contacto.php) ----------------------
function initFormularioConsultaContacto() {
  const formConsulta = document.getElementById("form-consulta");
  if (!formConsulta) return;

  const spinner = document.getElementById("spinner-consulta");
  const mensajeEnvio = document.getElementById("mensaje-envio-consulta");

  // Inputs
  const nombre = document.getElementById("nombre");
  const email = document.getElementById("email");
  const consulta = document.getElementById("consulta");

  const errorNombre = document.getElementById("error-nombre");
  const errorEmail = document.getElementById("error-email");
  const errorConsulta = document.getElementById("error-consulta");

  // Contador de caracteres
  const contadorConsulta = document.getElementById("contador-consulta");
  if (consulta && contadorConsulta) {
    // Inicial
    contadorConsulta.textContent = consulta.value.length;
    consulta.addEventListener("input", function () {
      contadorConsulta.textContent = this.value.length;
    });
  }
  
  formConsulta.addEventListener("submit", function (e) {
    e.preventDefault();

    let valido = true;
    let primerError = null;

    const showError = (el, msg) => {
      el.textContent = msg || "";
      el.style.display = msg ? "block" : "none";
      if (msg && !primerError) primerError = el;
    };

    // Al escribir en los inputs, ocultar el mensaje de error
    [[nombre, errorNombre], [email, errorEmail], [consulta, errorConsulta]].forEach(
      ([input, errorEl]) => {
        input.addEventListener("input", () => showError(errorEl, ""));
      }
    );

    // Validar nombre
    if (!nombre.value.trim()) {
      showError(errorNombre, "Por favor, ingresá tu nombre.");
      valido = false;
    } else showError(errorNombre, "");

    // Validar email
    if (!email.value.trim()) {
      showError(errorEmail, "Por favor, ingresá tu correo.");
      valido = false;
    } else if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email.value)) {
      showError(errorEmail, "Ingresá un correo válido.");
      valido = false;
    } else showError(errorEmail, "");

    // Validar consulta
    if (!consulta.value.trim()) {
      showError(errorConsulta, "Por favor, escribí tu consulta.");
      valido = false;
    } else showError(errorConsulta, "");

    if (!valido) {
      if (primerError)
        primerError.scrollIntoView({ behavior: "smooth", block: "center" });
      return; // No enviar si hay errores
    }

    if (spinner) spinner.style.display = "flex";

    const formData = new FormData(this);
    fetch(this.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (mensajeEnvio) mensajeEnvio.textContent = data;
        if (spinner) spinner.style.display = "none";
        if (data.includes("Mensaje enviado correctamente.")) {
          // Evento GA4
          if (typeof gtag === "function") {
            gtag("event", "form_submit", {
              event_category: "Formulario",
              event_label: "Contacto",
            });
            console.log("Evento GA4 calculadora enviado");
          }
          this.reset();
          // Ocultar errores
          [errorNombre, errorEmail, errorConsulta].forEach((el) => (el.style.display = "none"));
        }
      })
      .catch((error) => {
        if (mensajeEnvio)
          mensajeEnvio.textContent =
            "Ocurrió un error al enviar el mensaje. Por favor, intente nuevamente.";
        if (spinner) spinner.style.display = "none";
        console.error(error);
      });
  });
}

// ---------------------- Botón de Servicios (desktop y mobile) ----------------------
function initBotonServicios() {
  const serviciosBtn = document.getElementById("servicios-btn");
  if (serviciosBtn) {
    serviciosBtn.addEventListener("click", function (event) {
      event.preventDefault();

      if (window.innerWidth <= 768) return; // Evita doble scroll en mobile

      if (
        window.location.pathname.endsWith("index.php") ||
        window.location.pathname === "/"
      ) {
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
}

// ---------------------- Menú hamburguesa ----------------------
function initMenuHamburguesa() {
  const hamburguesaBtn = document.getElementById("hamburguesa-btn");
  const menuMovil = document.getElementById("menu-movil");

  if (!hamburguesaBtn || !menuMovil) return;

  hamburguesaBtn.addEventListener("click", function () {
    menuMovil.style.display =
      menuMovil.style.display === "block" ? "none" : "block";
  });
}

// ---------------------- Submenú de servicios móvil ----------------------
function initSubmenuServiciosMovil() {
  const movilServicios = document.getElementById("movil-servicios");
  const submenuMovil = document.querySelector(".submenu-movil");

  if (movilServicios && submenuMovil) {
    movilServicios.addEventListener("click", function (event) {
      event.preventDefault();
      submenuMovil.style.display =
        submenuMovil.style.display === "block" ? "none" : "block";
    });
  }
}

// ---------------------- Dropdown en navbar (desktop) ----------------------
function initDropdownNavbarDesktop() {
  const dropdown = document.querySelector(".servicios-dropdown");
  if (!dropdown) return;

  const dropdownContent = dropdown.querySelector(".dropdown-content");
  if (!dropdownContent) return;

  dropdown.addEventListener("mouseover", () => {
    dropdownContent.classList.add("show");
  });

  dropdown.addEventListener("mouseout", () => {
    dropdownContent.classList.remove("show");
  });
}

// ---------------------- Carrusel de imágenes (index.php) ----------------------
function initCarruselImagenes() {
  const slides = document.querySelectorAll(".carousel-slide");
  const carouselContainer = document.getElementById("carouselContainer");

  if (!slides.length || !carouselContainer) return;

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

  function nextSlide() {
    showSlide(currentSlide + 1);
  }

  function prevSlide() {
    showSlide(currentSlide - 1);
  }

  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  if (prevBtn) prevBtn.addEventListener("click", prevSlide);
  if (nextBtn) nextBtn.addEventListener("click", nextSlide);

  let autoSlide = setInterval(nextSlide, 5000);
  const carousel = document.querySelector(".carousel");

  if (carousel) {
    carousel.addEventListener("mouseenter", () => clearInterval(autoSlide));
    carousel.addEventListener(
      "mouseleave",
      () => (autoSlide = setInterval(nextSlide, 5000))
    );
  }

  showSlide(currentSlide);
}

// ---------------------- Calculadora de precios (ficha_servicio.php) ----------------------
function initCalculadoraPrecios() {
  const secciones = [
    { checkboxId: "troquelado_toggle", seccionId: "troquelado_seccion" },
    { checkboxId: "barniz_toggle", seccionId: "barniz_seccion" },
    { checkboxId: "estuches_toggle", seccionId: "estuches_seccion" },
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

  const form = document.getElementById("form-calculadora");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // siempre bloquear envío hasta validar
    let valido = true;

    // Limpio mensajes (NO elimino los placeholders, solo los oculto/limpio)
    document.querySelectorAll(".error-grupo").forEach((el) => {
      el.textContent = "";
      el.style.display = "none";
    });

    // Definición de grupos (con id para el placeholder)
    const grupos = [
      {
        id: "impresion",
        nombre: "Impresión",
        campos: ["ctp", "postura_impresion", "millar_impresion"],
      },
      {
        id: "troquelado",
        nombre: "Troquelado",
        campos: ["bocas", "millar_troquelado"],
        toggle: "troquelado_toggle",
      },
      {
        id: "barniz",
        nombre: "Barniz",
        campos: ["postura_barniz", "millar_barniz"],
        toggle: "barniz_toggle",
      },
      {
        id: "estuches",
        nombre: "Pegado de estuches",
        campos: ["medida_estuche", "cantidad_estuches"],
        toggle: "estuches_toggle",
      },
    ];

    // Guardar primer placeholder con error y scrollear luego
    let primerPlaceholderConError = null;

    grupos.forEach((grupo) => {
      // Chequear si al menos un campo del grupo tiene dato
      let algunoCompleto = false;
      let faltantes = [];

      grupo.campos.forEach((campoId) => {
        // Manejo radios o inputs normales
        const radios = document.getElementsByName(campoId);
        if (radios && radios.length > 0 && radios[0].type === "radio") {
          const anyChecked = Array.from(radios).some((r) => r.checked);
          if (anyChecked) {
            algunoCompleto = true;
          } else {
            faltantes.push(campoId);
          }
        } else {
          // Input normal: por id
          const campo = document.getElementById(campoId);
          if (campo) {
            const val = String(campo.value || "").trim();
            if (val !== "") {
              algunoCompleto = true;
            } else {
              faltantes.push(campoId);
            }
          }
        }
      });

      // Si el toggle existe y está chequeado, exigir todos los campos
      if (grupo.toggle) {
        const toggle = document.getElementById(grupo.toggle);
        if (toggle && toggle.checked) {
          const anyMissing = grupo.campos.some((campoId) => {
            const radios = document.getElementsByName(campoId);
            if (radios && radios.length > 0 && radios[0].type === "radio") {
              return !Array.from(radios).some((r) => r.checked);
            }
            const campo = document.getElementById(campoId);
            return campo && String(campo.value || "").trim() === "";
          });
          if (anyMissing) {
            valido = false;
            mostrarErrorGrupo(
              grupo.id,
              `Completá todos los campos del grupo ${grupo.nombre}.`
            );
            if (!primerPlaceholderConError)
              primerPlaceholderConError = document.querySelector(
                `.error-grupo[data-grupo="${grupo.id}"]`
              );
          }
          return; // Siguiente grupo
        }
      }

      if (algunoCompleto && faltantes.length > 0) {
        valido = false;
        mostrarErrorGrupo(
          grupo.id,
          `Completá todos los campos del grupo ${grupo.nombre}.`
        );
        if (!primerPlaceholderConError)
          primerPlaceholderConError = document.querySelector(
            `.error-grupo[data-grupo="${grupo.id}"]`
          );
      }
    });

    if (!valido) {
      if (primerPlaceholderConError) { // Scroll al primer error de grupo si existe
        primerPlaceholderConError.scrollIntoView({
          behavior: "smooth",
          block: "center",
        });
      }
      return; // Cortar si hay errores
    }
    form.dispatchEvent(new Event("valido")); 
  });

  function mostrarErrorGrupo(grupoId, mensaje) {
    const placeholder = document.querySelector(
      `.error-grupo[data-grupo="${grupoId}"]`
    );
    if (placeholder) {
      placeholder.textContent = mensaje;
      placeholder.style.display = "block";
    } else {
      let contenedor =
        document.getElementById(`${grupoId}_seccion`) ||
        document.querySelector(`#${grupoId}_seccion`) ||
        document.querySelector("form#form-calculadora");
      const p = document.createElement("p");
      p.className = "error-grupo";
      p.style.color = "red";
      p.textContent = mensaje;
      if (contenedor) contenedor.appendChild(p);
    }
  }
}

// ---------------------- Llenar formulario de contacto según datos de la calculadora ----------
function initFormularioDesdeCalculadora() {
  const form = document.getElementById("form-calculadora");
  const resultadoDiv = document.getElementById("resultado-calculadora");

  if (!form || !resultadoDiv) return;

  form.addEventListener("valido", function (e) {
    const formData = new FormData(form);

    function configurarBotonConsulta() {
      const botonConsulta = document.getElementById("boton-consulta");

      if (botonConsulta) {
        botonConsulta.addEventListener("click", function () {
          const resumenPedido = botonConsulta.getAttribute("data-resumen");

          const formulario = document.getElementById("formulario-contacto");
          if (formulario) {
            formulario.scrollIntoView({ behavior: "smooth" });

            const descripcion = document.getElementById("descripcion");
            if (descripcion) {
              descripcion.value = resumenPedido;
            }
          }
        });
      }
    }

    fetch("backend/calcular_precio.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((html) => {
        resultadoDiv.innerHTML = html;

        // GA4
        if (typeof gtag === "function") {
          gtag("event", "price_calc", {
            event_category: "Interacción",
            event_label: "Calculadora de Precios",
          });
          console.log("Evento GA4 calculadora enviado");
        }

        const contenedor = document.querySelector(".calculadora");
        const objetivo = document.getElementById("resultado-precio");

        if (contenedor && objetivo) {
          const offsetTop = objetivo.offsetTop - contenedor.offsetTop;
          contenedor.scrollTo({
            top: offsetTop,
            behavior: "smooth",
          });
        }

        configurarBotonConsulta();

        const botonConsulta = document.getElementById("btn-enviar-consulta");
        if (botonConsulta) {
          botonConsulta.addEventListener("click", () => {
            const ctp = document.getElementById("ctp").value;
            const posturaImpresion =
              document.getElementById("postura_impresion").value;
            const millarImpresion =
              document.getElementById("millar_impresion").value;

            const troqueladoActivo =
              document.getElementById("troquelado_toggle").checked;
            const bocas = document.getElementById("bocas").value;
            const millarTroquelado =
              document.getElementById("millar_troquelado").value;

            const barnizActivo =
              document.getElementById("barniz_toggle").checked;
            const posturaBarniz =
              document.getElementById("postura_barniz").value;
            const millarBarniz = document.getElementById("millar_barniz").value;

            const estuchesActivo =
              document.getElementById("estuches_toggle").checked;
            const medidaEstuche = document.querySelector(
              'input[name="medida_estuche"]:checked'
            );
            const cantidadEstuches =
              document.getElementById("cantidad_estuches").value;

            let descripcion =
              "Hola, me gustaría consultar por el siguiente pedido:\n\n";

            if (ctp || posturaImpresion || millarImpresion) {
              descripcion += "🔹 Impresión:\n";
              if (ctp) descripcion += `- CTP (chapa): ${ctp}\n`;
              if (posturaImpresion)
                descripcion += `- Postura: ${posturaImpresion}\n`;
              if (millarImpresion)
                descripcion += `- Millares: ${millarImpresion}\n`;
              descripcion += "\n";
            }

            if (troqueladoActivo && (bocas || millarTroquelado)) {
              descripcion += "🔹 Troquelado:\n";
              if (bocas) descripcion += `- Bocas: ${bocas}\n`;
              if (millarTroquelado)
                descripcion += `- Millares: ${millarTroquelado}\n`;
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
              if (medidaEstuche)
                descripcion += `- Medida: ${medidaEstuche.value}\n`;
              if (cantidadEstuches)
                descripcion += `- Cantidad: ${cantidadEstuches}\n`;
              descripcion += "\n";
            }

            descripcion += "Gracias, quedo a la espera de su respuesta.";

            const campoDescripcion = document.getElementById("descripcion");
            if (campoDescripcion) {
              campoDescripcion.value = descripcion;
            }

            const formulario = document.getElementById("form-contacto");
            if (formulario) {
              formulario.scrollIntoView({ behavior: "smooth" });
            }
          });
        }
      })
      .catch((error) => {
        resultadoDiv.innerHTML =
          "<p>Ocurrió un error al calcular el precio.</p>";
        console.error("Error:", error);
      });
  });
}

// ---------------------- Formulario de contacto (ficha_servicio.php) ----------------------
function initFormularioContactoServicio() {
  const descripcion = document.getElementById("descripcion");
  const contador = document.getElementById("contador-descripcion");

  if (descripcion && contador) {
    descripcion.addEventListener("input", function () {
      contador.textContent = this.value.length;
    });
  }

  const conocio = document.getElementById("conocio");
  const inputOcultoOtro = document.getElementById("input-oculto-otro");

  if (conocio && inputOcultoOtro) {
    conocio.addEventListener("change", function () {
      inputOcultoOtro.style.display = this.value === "otro" ? "block" : "none";
      if (this.value !== "otro") {
        const otro = document.getElementById("conocio_otro");
        if (otro) otro.value = "";
      }
    });

    if (conocio.value !== "otro") {
      inputOcultoOtro.style.display = "none";
    }
  }

  const inputArchivo = document.getElementById("archivo");
  if (inputArchivo) {
    inputArchivo.addEventListener("change", function () {
      const nombreArchivo = this.files[0]
        ? this.files[0].name
        : "Ningún archivo seleccionado";
      const archivoNombre = document.getElementById("archivo-nombre");
      if (archivoNombre) archivoNombre.textContent = nombreArchivo;
    });
  }

  const formContacto = document.getElementById("form-contacto");
  if (formContacto) {
    // Desactivar burbujas nativas del navegador
    formContacto.setAttribute("novalidate", "novalidate");

    // Contador inicial
    if (descripcion && contador)
      contador.textContent = descripcion.value.length;

    // Referencias
    const nombre = document.getElementById("nombre");
    const errorNombre = document.getElementById("error-nombre");
    const email = document.getElementById("email");
    const errorEmail = document.getElementById("error-email");
    const errorDescripcion = document.getElementById("error-descripcion");
    const medio = document.getElementById("medio");
    const errorMedio = document.getElementById("error-medio");
    const radiosDiseno = document.querySelectorAll('input[name="diseno"]');
    const errorDiseno = document.getElementById("error-diseno");

    // Limpiar los errores
    const inputErrorMap = [
      { input: nombre, error: errorNombre },
      { input: email, error: errorEmail },
      { input: descripcion, error: errorDescripcion },
      { input: medio, error: errorMedio },
    ];

    inputErrorMap.forEach(({ input, error }) => {
      if (input && error) {
        input.addEventListener("input", () => {
          if (input.value.trim()) {
            error.style.display = "none";
            error.textContent = "";
          }
        });
      }
    });

    radiosDiseno.forEach((radio) => {
      radio.addEventListener("change", () => {
        errorDiseno.style.display = "none";
        errorDiseno.textContent = "";
      });
    });

    formContacto.addEventListener("submit", function (e) {
      e.preventDefault();

      let valido = true;
      let primerError = null;

      // Mostrar/ocultar errores
      const showError = (el, msg, inputRef) => {
        if (!el) return;
        el.textContent = msg || "";
        el.style.display = msg ? "block" : "none";

        if (msg && !primerError && inputRef) {
          primerError = inputRef;
        }
      };

      // Validar nombre
      const nombre = document.getElementById("nombre");
      const errorNombre = document.getElementById("error-nombre");
      if (!nombre.value.trim()) {
        showError(errorNombre, "Por favor, ingresá tu nombre.", nombre);
        valido = false;
      } else {
        showError(errorNombre, "");
      }

      // Validar email
      const email = document.getElementById("email");
      const errorEmail = document.getElementById("error-email");
      if (!email.value.trim()) {
        showError(errorEmail, "Por favor, ingresá tu correo.", email);
        valido = false;
      } else if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email.value)) {
        showError(errorEmail, "Ingresá un correo válido.", email);
        valido = false;
      } else {
        showError(errorEmail, "");
      }

      // Validar descripción
      const descripcion = document.getElementById("descripcion");
      const errorDescripcion = document.getElementById("error-descripcion");
      if (!descripcion.value.trim()) {
        showError(errorDescripcion, "Por favor, describí tu pedido.");
        valido = false;
      } else {
        showError(errorDescripcion, "");
      }

      // Validar diseño (radio)
      const radiosDiseno = document.querySelectorAll('input[name="diseno"]');
      const errorDiseno = document.getElementById("error-diseno"); // <-- sin ñ
      let seleccionado = false;
      radiosDiseno.forEach((r) => {
        if (r.checked) seleccionado = true;
      });
      if (!seleccionado) {
        showError(
          errorDiseno,
          "Seleccioná una opción de diseño.",
          radiosDiseno[0]
        );
        valido = false;
      } else {
        showError(errorDiseno, "");
      }

      // Validar medio (select)
      const medio = document.getElementById("medio");
      const errorMedio = document.getElementById("error-medio");
      if (medio && medio.value === "") {
        showError(errorMedio, "Por favor, seleccioná una opción.", medio);
        valido = false;
      } else {
        showError(errorMedio, "");
      }

      if (!valido) {
        if (primerError) {
          primerError.scrollIntoView({ behavior: "smooth", block: "center" });
          primerError.focus({ preventScroll: true });
        }
        return;
      }

      const contenedor = document.querySelector(".scroll-formulario-contacto");
      const spinner = document.getElementById("spinner");

      if (contenedor && spinner) {
        spinner.style.display = "flex";
        setTimeout(() => {
          contenedor.scrollTop = spinner.offsetTop;
        }, 50);
      }

      const formData = new FormData(this);

      fetch(this.action, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          const mensaje = document.getElementById("mensaje-respuesta");
          if (mensaje) mensaje.textContent = data;
          if (spinner) spinner.style.display = "none";
          if (data.includes("Mensaje enviado correctamente")) {
            // Evento GA4
            if (typeof gtag === "function") {
              gtag("event", "form_submit", {
                event_category: "Formulario",
                event_label: "Ficha Servicio",
              });
              console.log("Evento GA4 calculadora enviado");
            }

            this.reset();
            const archivoNombre = document.getElementById("archivo-nombre");
            if (archivoNombre) archivoNombre.textContent = "";
            if (contador) contador.textContent = "0"; // Resetear contador
            // Ocultar todos los errores
            document
              .querySelectorAll(".mensaje-advertencia")
              .forEach((p) => (p.style.display = "none"));
          }
        })
        .catch((error) => {
          const mensaje = document.getElementById("mensaje-respuesta");
          if (mensaje)
            mensaje.textContent =
              "Ocurrió un error al enviar el mensaje. Por favor, intente nuevamente.";
          if (spinner) spinner.style.display = "none";
          console.error(error);
        });
    });
  }

  const inputTelefono = document.getElementById("telefono");
  if (inputTelefono) {
    inputTelefono.addEventListener("input", function () {
      this.value = this.value.replace(/\D/g, "");
    });
  }
}

// ---------------------- Abrir imágenes de almanaques (ficha_servicio.php) ----------------------
function initModalImagenAlmanaques() {
  const imagenes = document.querySelectorAll(".tarjeta-modelo img");
  const modal = document.getElementById("modalImagen");
  const imagenAmpliada = document.getElementById("imagenAmpliada");
  const cerrarModal = document.querySelector(".cerrar-modal");

  if (!imagenes.length || !modal || !imagenAmpliada || !cerrarModal) return;

  imagenes.forEach((img) => {
    img.addEventListener("click", () => {
      modal.style.display = "block";
      imagenAmpliada.src = img.src;
      imagenAmpliada.alt = img.alt || "Imagen ampliada";
    });
  });

  cerrarModal.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
}

// ---------------------- Atajo secreto para Dashboard ----------------------
function initAtajoDashboard() {
  document.addEventListener("keydown", function (event) {
    if (event.ctrlKey && event.shiftKey && event.key === "Y") {
      window.location.href = BASE_URL + "admin-xyz2025/login.php";
    }
  });
}

// ---------------------- Menú móvil al cargar y cerrar con click externo ----------------------
function initMenuMovilCargar_CerrarClickExterno() {
  window.addEventListener("load", () => {
    const menu = document.querySelector(".menu-movil");
    const hamburguesa = document.querySelector(".hamburguesa");
    if (menu) menu.style.display = "none";
    if (hamburguesa) hamburguesa.classList.remove("is-active");
  });

  document.addEventListener("click", function (event) {
    const menu = document.querySelector(".menu-movil");
    const hamburguesa = document.querySelector(".hamburguesa");
    if (
      menu &&
      hamburguesa &&
      !menu.contains(event.target) &&
      !hamburguesa.contains(event.target)
    ) {
      menu.style.display = "none";
    }
  });
}

// ---------------------- API Google Maps para la página de Contacto ----------------------
function initMap() {
  // Coordenadas de la ubicación
  const ubicacion = { lat: -34.730329, lng: -58.297361 };

  // Crear mapa
  const mapa = new google.maps.Map(document.getElementById("mapa"), {
    zoom: 16,
    center: ubicacion,
  });

  // Agregar marcador
  new google.maps.Marker({
    position: ubicacion,
    map: mapa,
    title: "NuevoPack",
  });
}
