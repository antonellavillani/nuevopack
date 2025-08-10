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
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins:wght@700&display=swap" rel="stylesheet" />    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
            <input type="file" id="imagen" name="imagen" accept="image/*" />
        </div>

        <img id="previewImg" class="previewImg" alt="Imagen adjunta" />

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
