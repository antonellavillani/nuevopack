<?php
// Incluir el header
include('includes/header.php');
?>

<body>
    <!-- Contenido Principal -->
    <main class="articles articles contacto-page">
        <!-- Título Principal -->
        <h1>Contáctanos</h1>
        
        <!-- Contenedor de Contacto -->
        <div class="contact-container">
            <!-- Formulario de Contacto -->
            <div class="formulario-contacto-consultas">
                <h2>Déjanos tu consulta</h2>
                <form id="form-consulta" action="backend/enviar_consulta.php" method="POST">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>

                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Tu correo electrónico" required>

                    <label for="consulta">Consulta:</label>
                    <textarea id="consulta" name="consulta" placeholder="Escribe tu consulta aquí" rows="6" required></textarea>

                    <button type="submit">Enviar</button>

                    <!-- Ícono de cargando inicialmente oculto -->
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
                </form>
                <div id="mensaje-envio" class="mensaje-envio-mail texto-centrado"></div>
            </div>

            <!-- Información de Contacto y Mapa -->
            <div class="info-contacto">
                <h2>Encuéntranos</h2>
                <!-- Mapa de Google -->
                <div id="mapa">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3282.2310116409636!2d-58.25012358477338!3d-34.70012328043442!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcd3a3a8f9b4b7%3A0x123456789abcdef!2s237%20P.%20Elustondo%2C%20Quilmes!5e0!3m2!1ses!2sar!4v1710000000000!5m2!1ses!2sar" 
                        width="100%" 
                        height="300" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
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
<script src="public/js/script.js" defer></script>
