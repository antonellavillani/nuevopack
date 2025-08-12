console.log("El script se ejecutó correctamente.");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM completamente cargado.");
    // ---------------------- Funciones ----------------------
    initModalMiCuenta(); // Modal 'Mi Cuenta'
    initSoporteImagenes(); // Modal para imágenes adjuntas en Soporte
    initSoporteFormulario(); // Envío AJAX del formulario de soporte con spinner y mensajes
    initModalDescripcion(); // Modal Descripción de Servicios
    initValidacionPassword(); // Validar contraseñas al crear usuario
    initFormularioResetPassword(); // Form Resetear Contraseña
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

  btnLogout.addEventListener('click', () => {
    if (confirm('¿Estás seguro que querés cerrar sesión?')) {
      window.location.href = 'logout.php';
    }
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

// ---------------------- Validar contraseñas al crear usuario ----------------------
function initValidacionPassword() {
    const inputPassword = document.getElementById('password');
    const inputRepetir = document.getElementById('repetir_password');
    const errorCoincidencia = document.getElementById('errorCoincidencia');

    if (!inputPassword || !inputRepetir || !errorCoincidencia) return;

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
    });

    inputRepetir.addEventListener('input', () => {
        errorCoincidencia.style.display =
            inputRepetir.value && inputPassword.value !== inputRepetir.value
                ? 'block'
                : 'none';
    });

    document.querySelector('.formulario-admin')?.addEventListener('submit', (e) => {
        const pass = inputPassword.value;
        const repetir = inputRepetir.value;
        const cumpleTodo = Object.values(reglas).every(fn => fn(pass));

        if (!cumpleTodo) {
            alert("La contraseña no cumple con los requisitos.");
            e.preventDefault();
        } else if (pass !== repetir) {
            errorCoincidencia.style.display = 'block';
            e.preventDefault();
        }
    });
}

// ---------------------- Form Resetear Contraseña ----------------------
function initFormularioResetPassword() {
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
