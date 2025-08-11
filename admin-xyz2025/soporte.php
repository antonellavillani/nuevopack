<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Soporte | Panel de Administración NuevoPack</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="estilos/estilos_admin.css" />
</head>
<body>
<div class="contenedor">
    <h1 class="titulo-pagina icono-soporte">Soporte</h1>
    <p class="texto-soporte">¿Tenés algún problema o consulta? Mandanos un mensaje y te ayudamos.</p>

    <form class="formulario-admin" id="formSoporte" enctype="multipart/form-data">
        <label for="asunto">Asunto</label>
        <input type="text" id="asunto" name="asunto" required />

        <label for="mensaje">Mensaje</label>
        <textarea id="mensaje" name="mensaje" rows="6" required></textarea>

        <div class="file-input-wrapper">
            <label for="imagen">Adjuntar imagen (opcional)</label>
            <input type="file" id="imagen" name="imagen[]" accept="image/*" multiple/>
        </div>

        <div id="previewBox" class="previewBox" aria-hidden="true">
            <img id="previewImg" class="previewImg" alt="Imagen adjunta" />
            <span id="imageCount" class="imageCount"></span>
        </div>

        <!-- Modal para mostrar todas -->
        <div id="imageModal" class="imageModal" aria-hidden="true">
            <button type="button" id="closeModal" class="closeModal">Cerrar</button>
            <div id="modalImages" class="modalImages"></div>
        </div>

        <button type="submit" class="btn-guardar">Enviar</button>

        <div id="spinner" class="dot-spinner" style="display: none; margin: 20px auto;">
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
        </div>

        <div id="respuesta" class="mensaje-envio-mail texto-centrado"></div>
    </form>

    <a href="dashboard.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>

</div>

<script>
const inputImagen = document.getElementById('imagen');
const previewImg = document.getElementById('previewImg');
inputImagen.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewImg.style.display = 'none';
    }
});

const previewBox = document.getElementById('previewBox');
const imageCount = document.getElementById('imageCount');
const imageModal = document.getElementById('imageModal');
const modalImages = document.getElementById('modalImages');
const closeModal = document.getElementById('closeModal');

let allImageUrls = [];

inputImagen.addEventListener('change', function() {
    const files = Array.from(this.files);
    allImageUrls = [];

    if (files.length > 0) {
        // Mostrar la primera imagen en preview
        const firstFile = files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewBox.style.display = 'block';
        }
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
        previewBox.style.display = 'none';
    }
});

// Abrir modal al hacer click en el preview
previewBox.addEventListener('click', () => {
    modalImages.innerHTML = '';
    allImageUrls.forEach(url => {
        const img = document.createElement('img');
        img.src = url;
        img.style.width = '200px';
        img.style.borderRadius = '6px';
        modalImages.appendChild(img);
    });
    imageModal.style.display = 'block';
});

// Cerrar modal
closeModal.addEventListener('click', () => {
    imageModal.style.display = 'none';
});

const spinner = document.getElementById('spinner');
const form = document.getElementById('formSoporte');

form.addEventListener('submit', function(e){
    e.preventDefault();
    spinner.style.display = 'block';
    const rta = document.getElementById('respuesta');
    rta.textContent = '';

    const formData = new FormData(this);
    fetch('./soporte/enviar_soporte.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        spinner.style.display = 'none';
        if(data.ok){
            rta.style.color = 'green';
            rta.textContent = '✅ Tu mensaje fue enviado con éxito. Nos pondremos en contacto pronto.';
            form.reset();
            previewImg.style.display = 'none';
            previewBox.style.display = 'none';
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
</script>

</body>
</html>
