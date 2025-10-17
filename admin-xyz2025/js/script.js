console.log("El script se ejecutó correctamente.");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM completamente cargado.");
    // ---------------------- Funciones ----------------------
    initModalMiCuenta(); // Modal 'Mi Cuenta'
    initSoporteImagenes(); // Modal para imágenes adjuntas en Soporte
    initSoporteFormulario(); // Envío AJAX del formulario de soporte con spinner y mensajes
    initModalDescripcion(); // Modal Descripción de Servicios
    initFormularioResetPassword(); // Form Resetear Contraseña
    initFormularioResetPassword(); // Form Editar Usuario + spinner
    initLogoutModal(); // Modal para cerrar sesión
    initRecuperarPassword() // Envío AJAX para recuperar contraseña

    // ------ Inicialización de validación de inputs ------
    // ABM Servicios
    initFormularioGenerico("form-servicio-crear", [
      { inputId: "nombre", errorId: "error-nombre", required: true },
      { inputId: "descripcion", errorId: "error-descripcion", required: true }
    ]);

    initFormularioGenerico("form-servicio-editar", [
      { inputId: "nombre", errorId: "error-nombre", required: true },
      { inputId: "descripcion", errorId: "error-descripcion", required: true }
    ]);
    
    // ABM Precios
    initFormularioGenerico("form-precio-crear", [
      { inputId: "servicio_id", errorId: "error-servicio_id", required: true },
      { inputId: "descripcion", errorId: "error-descripcion", required: true },
      { inputId: "tipo_unidad", errorId: "error-tipo_unidad", required: true },
      { inputId: "precio", errorId: "error-precio", required: true }
    ]);

    initFormularioGenerico("form-precio-editar", [
      { inputId: "servicio_id", errorId: "error-servicio_id", required: true },
      { inputId: "descripcion", errorId: "error-descripcion", required: true },
      { inputId: "tipo_unidad", errorId: "error-tipo_unidad", required: true },
      { inputId: "precio", errorId: "error-precio", required: true }
    ]);

    // ABM Usuarios
    initFormularioGenerico("form-usuario-crear", [
      { inputId: "nombre", errorId: "error-nombre", required: true },
      { inputId: "apellido", errorId: "error-apellido", required: true },
      { inputId: "email", errorId: "error-email", required: true, tipo: "email" }
    ]);

    initFormularioGenerico("form-usuario-editar", [
      { inputId: "nombre", errorId: "error-nombre", required: true },
      { inputId: "apellido", errorId: "error-apellido", required: true },
      { inputId: "email", errorId: "error-email", required: true }
    ]);
    
    // Validación soporte
    initFormularioGenerico("formSoporte", [
      { inputId: "asunto", errorId: "error-asunto", required: true },
      { inputId: "mensaje", errorId: "error-mensaje", required: true }
    ]);

    // Inicializar modal de eliminación
    document.querySelectorAll('.btn-eliminar-tabla').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // evitar navegación inmediata
            const url = this.href;
            const nombre = this.dataset.nombre;
            const servicio = this.dataset.servicio;
            const email = this.dataset.email;
            let mensaje;

            if (servicio) {
              mensaje = `¿Eliminar el precio de "${servicio}" (${nombre})?`;
            } else if (email) {
              mensaje = `¿Eliminar el usuario "${email}"?`;
            } else {
                mensaje = `¿Eliminar el servicio "${nombre}"?`;
            }

            abrirModalEliminar(url, mensaje);
        });
    });
});

// GA Client
 fetch("backend/analytics_cache.php", { credentials: "same-origin" })
    .then(res => res.json())
    .then(data => {
      document.getElementById("users7d").textContent = data.users;
      document.getElementById("sessions7d").textContent = data.sessions;
      document.getElementById("rtUsers").textContent = data.rtUsers;
      document.getElementById("formSubmits").textContent = data.formSubmits;
      document.getElementById("calcUses").textContent = data.calcUses;
    })
    .catch(() => {
      document.getElementById("users7d").textContent = "Error";
      document.getElementById("sessions7d").textContent = "Error";
      document.getElementById("rtUsers").textContent = "Error";
      document.getElementById("formSubmits").textContent = "Error";
      document.getElementById("calcUses").textContent = "Error";
    });

// ---------------------- Modal 'Mi Cuenta' ----------------------
function initModalMiCuenta() {
  const btnMiCuenta = document.getElementById('btn-mi-cuenta');
  const modal = document.getElementById('modal-mi-cuenta');
  const cerrarModal = document.getElementById('cerrar-modal');
  const btnLogout = document.getElementById('btn-logout');

  if (!btnMiCuenta || !modal || !cerrarModal || !btnLogout) return;

  btnMiCuenta.addEventListener('click', e => {
    e.preventDefault();
    modal.style.display = 'block';
  });

  cerrarModal.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  window.addEventListener('click', e => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
}

// ---------------------- Modal logout ----------------------
function initLogoutModal() {
  const btnLogout = document.getElementById('btn-logout');
  const modalLogout = document.getElementById('modal-logout');
  const cerrarLogout = document.getElementById('cerrar-logout');
  const cancelLogout = document.getElementById('cancel-logout');
  const confirmLogout = document.getElementById('confirm-logout');

  if (!btnLogout || !modalLogout) return;

  // Abrir modal al tocar el botón
  btnLogout.addEventListener('click', () => {
    modalLogout.style.display = 'block';
  });

  // Cerrar modal
  cerrarLogout.addEventListener('click', () => {
    modalLogout.style.display = 'none';
  });

  cancelLogout.addEventListener('click', () => {
    modalLogout.style.display = 'none';
  });

  // Cerrar al hacer clic fuera del modal
  window.addEventListener('click', (e) => {
    if (e.target === modalLogout) {
      modalLogout.style.display = 'none';
    }
  });

  // Confirmar logout
  confirmLogout.addEventListener('click', () => {
    window.location.href = 'logout.php';
  });
}

// ---------------------- Preview y modal de imágenes adjuntas en Soporte ----------------------
function initSoporteImagenes() {
  const inputImagen = document.getElementById('imagen');
  const previewImg = document.getElementById('previewImg');
  const previewBox = document.getElementById('previewBox');
  const imageCount = document.getElementById('imageCount');
  const imageModal = document.getElementById('imageModal');
  const modalImages = document.getElementById('modalImages');
  const closeModal = document.getElementById('closeModal');

  let allImageUrls = [];

  if (!inputImagen) return;

  inputImagen.addEventListener('change', function() {
    const files = Array.from(this.files);
    allImageUrls = [];

    if (files.length > 0) {
      // Mostrar la primera imagen en preview
      const firstFile = files[0];
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
        previewBox.style.display = 'block';
      };
      reader.readAsDataURL(firstFile);

      // Guardar todas las imágenes en array de URLs
      files.forEach(file => {
        const fr = new FileReader();
        fr.onload = e => allImageUrls.push(e.target.result);
        fr.readAsDataURL(file);
      });

      // Mostrar contador si hay más de 1
      imageCount.textContent = files.length > 1 ? `+${files.length - 1}` : '';
    } else {
      previewImg.style.display = 'none';
      previewBox.style.display = 'none';
      imageCount.textContent = '';
    }
  });

  // Abrir modal con todas las imágenes
  previewBox.addEventListener('click', () => {
    modalImages.innerHTML = '';
    allImageUrls.forEach(url => {
      const img = document.createElement('img');
      img.src = url;
      img.style.width = '200px';
      img.style.borderRadius = '6px';
      img.style.margin = '5px';
      modalImages.appendChild(img);
    });
    imageModal.style.display = 'block';
  });

  // Cerrar modal
  closeModal.addEventListener('click', () => {
    imageModal.style.display = 'none';
  });
}

// ------------- Envío AJAX del formulario de soporte con spinner y mensajes -----------
function initSoporteFormulario() {
  const form = document.getElementById('formSoporte');
  const spinner = document.getElementById('spinner');
  const rta = document.getElementById('respuesta');

  if (!form) return;

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    spinner.style.display = 'block';
    rta.textContent = '';
    rta.style.color = '';

    const formData = new FormData(this);
    fetch('./soporte/enviar_soporte.php', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      spinner.style.display = 'none';
      if (data.ok) {
        rta.style.color = 'green';
        rta.textContent = '✅ Tu mensaje fue enviado con éxito. Nos pondremos en contacto pronto.';
        form.reset();
        const previewImg = document.getElementById('previewImg');
        const previewBox = document.getElementById('previewBox');
        if (previewImg) previewImg.style.display = 'none';
        if (previewBox) previewBox.style.display = 'none';
      } else {
        rta.style.color = 'red';
        rta.textContent = '❌ ' + data.msg;
      }
    })
    .catch(err => {
      spinner.style.display = 'none';
      rta.style.color = 'red';
      rta.textContent = '❌ Error al enviar el mensaje';
      console.error(err);
    });
  });
}

// ---------------------- Modal Descripción de Servicios ----------------------
function initModalDescripcion() {
  const modal = document.getElementById("modalDescripcion");
  const contenido = document.getElementById("textoCompleto");

  if (!modal || !contenido) return;

  // Mostrar modal con texto completo
  window.mostrarModal = function(texto) {
    contenido.textContent = texto;
    modal.style.display = "block";
  };

  // Cerrar modal
  window.cerrarModal = function() {
    modal.style.display = "none";
  };

  // Cerrar modal si clic fuera del contenido
  window.onclick = function(event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  };
}

// -------- Validar contraseñas al crear usuario o modificar contraseña existente ------------
function initValidacionResetPassword(selectorFormulario) {
    const form = document.querySelector(selectorFormulario);
    if (!form) return;

    const inputPassword = form.querySelector('#password');
    const inputRepetir = form.querySelector('#repetir_password');
    const errorCoincidencia = form.querySelector('#errorCoincidencia');
    const errorContrasena = form.querySelector('#error-contrasena');

    if (!inputPassword || !inputRepetir || !errorCoincidencia || !errorContrasena) return;

    const reglas = {
        ruleLength: pass => pass.length >= 8,
        ruleMayuscula: pass => /[A-Z]/.test(pass),
        ruleMinuscula: pass => /[a-z]/.test(pass),
        ruleNumero: pass => /\d/.test(pass),
        ruleEspecial: pass => /[!@#$%^&*()_\-+={}[\]:;"'<>,.?/]/.test(pass)
    };

    function actualizarEstado(id, cumplido) {
        const elem = document.getElementById(id);
        if (!elem) return;
        const textoBase = elem.textContent.replace(/^✅ |^❌ /, "");
        elem.textContent = (cumplido ? "✅ " : "❌ ") + textoBase;
        elem.style.color = cumplido ? "green" : "gray";
        elem.style.fontWeight = cumplido ? "bold" : "normal";
    }

    inputPassword.addEventListener('input', () => {
        const pass = inputPassword.value;
        for (const id in reglas) {
            actualizarEstado(id, reglas[id](pass));
        }
        errorContrasena.style.display = "none";
    });

    inputRepetir.addEventListener('input', () => {
        errorCoincidencia.style.display =
            inputRepetir.value && inputPassword.value !== inputRepetir.value
                ? 'block'
                : 'none';
    });

    form.addEventListener('submit', (e) => {
        const pass = inputPassword.value;
        const repetir = inputRepetir.value;
        const cumpleTodo = Object.values(reglas).every(fn => fn(pass));

        if (!cumpleTodo) {
            e.preventDefault();
            errorContrasena.textContent = "La contraseña no cumple con los requisitos.";
            errorContrasena.style.display = "block";
            errorContrasena.scrollIntoView({ behavior: "smooth", block: "center" });
        } else if (pass !== repetir) {
            e.preventDefault();
            errorCoincidencia.style.display = 'block';
            errorCoincidencia.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
}

// ---------------------- Form Resetear Contraseña ----------------------
function initEnvioResetPassword() {
    const form = document.querySelector('.formulario-admin_resetear_password');
    if (!form) return;

    const passwordInput = form.querySelector('input[name="password"]');
    const confirmarInput = form.querySelector('input[name="confirmar"]');

    // Intentar usar el contenedor de error existente
    let errorContainer = document.querySelector('.mensaje-error_resetear_password');
    if (!errorContainer) {
        errorContainer = document.createElement('p');
        errorContainer.classList.add('mensaje-error_resetear_password');
        form.insertBefore(errorContainer, form.firstChild);
    }

    form.addEventListener('submit', function (e) {
        const password = passwordInput.value.trim();
        const confirmar = confirmarInput.value.trim();

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!password || !confirmar) {
            e.preventDefault();
            errorContainer.textContent = "Ambos campos son obligatorios.";
        } else if (password !== confirmar) {
            e.preventDefault();
            errorContainer.textContent = "Las contraseñas no coinciden.";
        } else if (!regex.test(password)) {
            e.preventDefault();
            errorContainer.textContent =
                "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
        } else {
            errorContainer.textContent = '';
        }
    });
}

// -------- Spinner para resetear contraseña --------
function initFormularioResetPassword() {
    const btn = document.getElementById('btn-reset-password');
    const spinner = document.getElementById('spinner');
    const rta = document.getElementById('respuesta-reset');

    if (!btn || !spinner || !rta) return;

    btn.addEventListener('click', function () {
        const id = btn.dataset.id;
        const origen = btn.dataset.origen;

        spinner.style.display = 'block';
        rta.textContent = '';
        rta.style.color = '';

        const formData = new FormData();
        formData.append('id', id);
        formData.append('origen', origen);

        fetch('../recuperacion/enviar_link_password.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            spinner.style.display = 'none';
            if (data.ok) {
              rta.innerHTML = `
                  <div class="mensaje-ok">
                      ${data.msg}
                      <div id="mensaje-aclaracion">
                          Si esta no es tu cuenta, recomendale al usuario que revise su correo para continuar con el cambio de contraseña.
                      </div>
                  </div>
              `;
              rta.style.color = '';
          } else {
              rta.textContent = data.msg;
              rta.style.color = 'red';
          }
        });
    });
}

// ------------------ Envío AJAX para recuperar contraseña -----------------
function initRecuperarPassword() {
  const form = document.getElementById('formRecuperar');
  const spinner = document.getElementById('spinner');
  const rta = document.getElementById('respuesta');

  if (!form) return;

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    spinner.style.display = 'block';
    rta.textContent = '';
    rta.style.color = '';

    const formData = new FormData(this);

    fetch('./recuperar_password.php', {
      method: 'POST',
      body: formData
    })
    .then(r => r.text())
    .then(html => {
      spinner.style.display = 'none';
      if (html.includes("Revisá tu correo electrónico")) {
        rta.style.color = 'green';
        rta.textContent = '✅ Revisá tu correo electrónico para continuar con la recuperación.';
        form.reset();
      } else if (html.includes("registrado") || html.includes("No se pudo")) {
        rta.style.color = 'red';
        rta.textContent = '❌ ' + html.replace(/<[^>]*>?/gm, ''); // limpia etiquetas si hubiera
      } else {
        rta.style.color = 'red';
        rta.textContent = '❌ Ocurrió un error inesperado.';
      }
    })
    .catch(err => {
      spinner.style.display = 'none';
      rta.style.color = 'red';
      rta.textContent = '❌ Error al enviar la solicitud';
      console.error(err);
    });
  });
}

// ------------------ Modal de confirmación para eliminar un elemento -----------------
function abrirModalEliminar(url, mensaje) {
    const modalEliminar = document.getElementById('modal-confirm');
    const modalMensaje = document.getElementById('modal-mensaje');
    const btnCancelar = document.getElementById('modal-cancelar');
    const btnConfirmar = document.getElementById('modal-confirmar');
    const spanCerrar = document.getElementById('cerrar-modal-confirm');

    let urlEliminarActual = url;

    modalMensaje.textContent = mensaje;
    modalEliminar.style.display = 'block';

    function cerrarModal() {
        modalEliminar.style.display = 'none';
        urlEliminarActual = null;
        // Quitamos listeners para no duplicarlos
        btnConfirmar.removeEventListener('click', confirmar);
        btnCancelar.removeEventListener('click', cerrarModal);
        spanCerrar.removeEventListener('click', cerrarModal);
    }

    function confirmar() {
        if (urlEliminarActual) window.location.href = urlEliminarActual;
    }

    btnCancelar.addEventListener('click', cerrarModal);
    spanCerrar.addEventListener('click', cerrarModal);
    btnConfirmar.addEventListener('click', confirmar);
    window.addEventListener('click', function(e) {
        if (e.target == modalEliminar) cerrarModal();
    });
}

// ------------------ Validación de inputs -----------------
function initFormularioGenerico(formId, campos) {
  const form = document.getElementById(formId);
  if (!form) return;

  let valido = true;
  let primerError = null;

  const showError = (el, msg) => {
    el.textContent = msg || "";
    el.style.display = msg ? "block" : "none";
    if (msg && !primerError) primerError = el;
  };

  // Asignar eventos "input" para limpiar errores
  campos.forEach(({ inputId, errorId }) => {
    const input = document.getElementById(inputId);
    const errorEl = document.getElementById(errorId);
    if (!input || !errorEl) return;
    input.addEventListener("input", () => showError(errorEl, ""));
  });

  form.addEventListener("submit", function (e) {
    valido = true;
    primerError = null;

    campos.forEach(({ inputId, errorId, required, tipo }) => {
      const input = document.getElementById(inputId);
      const errorEl = document.getElementById(errorId);
      if (!input || !errorEl) return;

      if (required && !input.value.trim()) {
        showError(errorEl, `El campo ${inputId} es obligatorio.`);
        valido = false;
      } else if (tipo === "email" && !/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(input.value)) {
        showError(errorEl, "Ingresá un correo válido.");
        valido = false;
      } else {
        showError(errorEl, "");
      }
    });

    if (!valido) {
      e.preventDefault();
      if (primerError)
        primerError.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  });
}
