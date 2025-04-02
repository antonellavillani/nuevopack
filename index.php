<?php
    // Incluir el header
    include('includes/header.php');
?>

<!-- Contenido principal del index -->
<main>
    <!-- Sección del carrusel -->
    <section class="carousel">
        <div class="carousel-container" id="carouselContainer">
            <div class="carousel-slide">
                <img src="public/img/foto_carrusel_1.png" alt="Imagen 1">
                <div class="carousel-text">
                    <h2>Troquelado de Precisión para tus Proyectos</h2>
                    <p>Ofrecemos servicios de troquelado adaptados a tus necesidades, garantizando cortes precisos y acabados de alta calidad.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="public/img/foto_carrusel_2.png" alt="Imagen 2">
                <div class="carousel-text">
                    <h2>Impresiones que Captan la Atención</h2>
                    <p>Utilizamos tecnología de impresión avanzada para brindar colores vibrantes y detalles nítidos en cada proyecto.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="public/img/foto_carrusel_3.png" alt="Imagen 3">
                <div class="carousel-text">
                    <h2>Soluciones Integrales en Pegado de Estuches</h2>
                    <p>Nos especializamos en el ensamblaje y pegado de estuches, ofreciendo productos duraderos y bien terminados.</p>
                </div>
            </div>
        </div>

        <!-- Controles del carrusel -->
        <div class="prev" onclick="prevSlide()"><i class="fas fa-chevron-left"></i></div>
        <div class="next" onclick="nextSlide()"><i class="fas fa-chevron-right"></i></div>
    </section>

    <!-- Sección de artículos -->
    <section class="articles">
        <h2>Personalización sin Límites: Creá el Diseño Ideal para tus Impresiones</h2>
        <div class="article-container">
            <article>
                <h3>La Importancia del Troquelado en el Diseño de Packaging</h3>
                <p>El troquelado es esencial para crear envases personalizados que se ajusten perfectamente al producto, mejorando su presentación y funcionalidad. En nuestra imprenta, utilizamos técnicas de troquelado de última generación para garantizar resultados óptimos.</p>
            </article>
            <article>
                <h3>Cómo una Buena Impresión Mejora la Imagen de tu Empresa</h3>
                <p>Una impresión de calidad refleja profesionalismo y atención al detalle. Descubre cómo nuestros servicios de impresión pueden ayudarte a transmitir la mejor imagen de tu negocio.</p>
            </article>
        </div>
    </section>

    <!-- Rectángulo de información importante -->
    <section class="important-info">
        <div class="info-box">
            <h3>Todo lo que Necesitás Saber Antes de Realizar tu Pedido</h3>
            <p>Realizamos envíos a todo el país con diferentes opciones de mensajería. Consultá costos y tiempos de entrega según tu ubicación.</p>
        </div>
    </section>

    <!-- Sección de servicios -->
    <section class="products">
        <h2 class="padding">Explora nuestros servicios</h2>

        <div class="services-container">
        <!-- Servicio de impresión -->
        <div class="service">
            <img src="imagen-servicio.jpg" alt="Servicio 1">
            <h3 class="service-title">Servicio de Impresión</h3>
            <p class="service-description">En <strong>NuevoPack</strong>, utilizamos tecnología de impresión de última generación para garantizar resultados impecables. Contamos con una amplia variedad de opciones en papel, acabados y tintas para adaptarnos a las necesidades de cada cliente. Desde folletos y tarjetas hasta envases y etiquetas, cada impresión refleja calidad y profesionalismo.</p>
        </div>

        <!-- Servicio de troquelado -->
        <div class="service">
            <img src="imagen-servicio.jpg" alt="Servicio 2">
            <h3 class="service-title">Servicio de Troquelado</h3>
            <p class="service-description">El troquelado es clave para obtener piezas con formas personalizadas y bordes definidos. En <strong>NuevoPack</strong>, empleamos troqueles de alta precisión para cortes exactos en cartón, papel y otros materiales, asegurando resultados impecables en packaging, etiquetas, estuches y más.</p>
        </div>

        <!-- Servicio de pegado de estuches (lateral) -->
        <div class="service">
            <img src="imagen-servicio.jpg" alt="Servicio 3">
            <h3 class="service-title">Servicio de Pegados de Estuches (lateral)</h3>
            <p class="service-description">Ofrecemos un servicio de pegado lateral eficiente y de alta calidad, ideal para cajas y estuches de distintos formatos. Nuestro sistema de pegado asegura resistencia y durabilidad, brindando soluciones prácticas para embalajes comerciales y promocionales.</p>
        </div>

        <!-- Servicio de almanaques -->
        <div class="service">
            <img src="imagen-servicio.jpg" alt="Servicio 4">
            <h3 class="service-title">Servicio de Almanaques</h3>
            <p class="service-description">Los almanaques son una excelente herramienta de marketing y organización. En <strong>NuevoPack</strong>, diseñamos y producimos almanaques personalizados con impresiones nítidas y acabados de primera calidad. Perfectos para oficinas, negocios o como obsequios corporativos.</p>
        </div>

        </div>
    </section>
</main>

<?php
// Incluir el footer
include('includes/footer.php');
?>

<!-- JavaScript -->
<script src="public/js/script.js" defer></script>
