<?php
require_once('includes/auth_admin.php');
include ("includes/header.php");
?>

<body>
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>
    
<div class="contenedor">
    <h1 class="titulo-pagina icono-soporte">Soporte</h1>
    <p class="texto-soporte">¿Tenés algún problema o consulta? Mandanos un mensaje y te ayudamos.</p>

    <form class="formulario-admin" id="formSoporte" enctype="multipart/form-data" novalidate>
        <label for="asunto">Asunto</label>
        <input type="text" id="asunto" name="asunto" required />
        <p id="error-asunto" class="mensaje-advertencia"></p>

        <label for="mensaje">Mensaje</label>
        <textarea id="mensaje" name="mensaje" rows="6" required></textarea>
        <p id="error-mensaje" class="mensaje-advertencia"></p>

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

<!-- JavaScript -->
<script src="js/script.js"></script>
</body>
</html>
