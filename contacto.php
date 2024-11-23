<?php
// Incluir el header
include('includes/header.php');
?>

<body>
    <!-- Contenido Principal -->
    <main class="articles">
        <!-- Título Principal -->
        <h1>Contáctanos</h1>
        
        <!-- Contenedor de Contacto -->
        <div class="contact-container">
            <!-- Formulario de Contacto -->
            <div class="formulario-contacto">
                <h2>Déjanos tu consulta</h2>
                <form>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>

                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Tu correo electrónico" required>

                    <label for="consulta">Consulta:</label>
                    <textarea id="consulta" name="consulta" placeholder="Escribe tu consulta aquí" rows="6" required></textarea>

                    <button type="submit">Enviar</button>
                </form>
            </div>

            <!-- Información de Contacto y Mapa -->
            <div class="info-contacto">
                <h2>Encuéntranos</h2>
                <!-- Mapa de Google -->
                <div id="mapa">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3282.7258489313467!2d-58.38164468477401!3d-34.60373448045988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccac64b7f56a7%3A0x885b7ec39b162717!2sVenancio%20Flores%204378%2C%20Claypole!5e0!3m2!1ses!2sar!4v1690123456789!5m2!1ses!2sar" 
                        width="100%" 
                        height="300" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                
                <!-- Información de Contacto -->
                <div class="contact-info">
                    <p><strong>Teléfono:</strong> (011) 1234-5678</p>
                    <p><strong>Correo Electrónico:</strong> contacto@nuevopack.com</p>
                </div>
            </div>
        </div>
    </main>

<?php
// Incluir el footer
include('includes/footer.php');
?>
