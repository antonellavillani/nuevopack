<?php
include('includes/header.php');
require_once __DIR__ . '/admin-xyz2025/config_secrets.php';
?>

<body>
    <!-- Contenido Principal -->
    <main class="articles articles contacto-page">
        <!-- Título Principal -->
        <section class="seccion-titulo" data-aos="fade-up">
            <h1 class="titulo-pagina">Contactános</h1>
        </section>
        
        <!-- Contenedor de Contacto -->
        <div class="contact-container" data-aos="fade-up">
            <!-- Formulario de Contacto -->
            <div class="formulario-contacto-consultas">
                <h2>Déjanos tu consulta</h2>
                <form id="form-consulta" action="backend/enviar_consulta.php" method="POST" novalidate>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                    <p class="mensaje-advertencia" id="error-nombre"></p>

                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Tu correo electrónico" required>
                    <p class="mensaje-advertencia" id="error-email"></p>

                    <label for="consulta">Consulta:</label>
                    <div class="textarea-wrapper">
                        <textarea id="consulta" name="consulta" placeholder="Escribí tu consulta acá" rows="6" required></textarea>
                        <div class="contador-caracteres">
                            <span id="contador-consulta">0</span>/1000
                        </div>
                    </div>
                    <p class="mensaje-advertencia" id="error-consulta"></p>

                    <button type="submit">Enviar</button>

                    <!-- Ícono de cargando inicialmente oculto -->
                    <div id="spinner-consulta" class="dot-spinner" style="display: none; margin: 20px auto;">
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                        <div class="dot-spinner__dot"></div>
                    </div>
                </form>
                <div id="mensaje-envio-consulta" class="mensaje-envio-mail texto-centrado"></div>
            </div>

            <!-- Información de Contacto y Mapa -->
            <div class="info-contacto" data-aos="fade-up">
                <h2>Encuéntranos</h2>
                <!-- Mapa de Google -->
                <div>
                    <!-- Mapa de Google (API) -->
                    <div id="mapa" class="mapa-google"></div>
                </div>
                
                <!-- Información de Contacto -->
                <div class="contact-info">
                    <p><strong>Teléfono:</strong> 11 3932-7709 (Daniel)</p>
                    <p><strong>Correo Electrónico:</strong> contacto@imprentanuevopack.online</p>
                </div>
            </div>
        </div>
    </main>

<?php
// Incluir el footer
include('includes/footer.php');
?>

<!-- JavaScript -->
<script src="public/js/script.js"></script>
<!-- Cargar la API de Google Maps -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo API_KEY_GOOGLE_MAPS; ?>&callback=initMap">
</script>
</body>