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
                <img src="public/img/foto_carrusel_1.jpg" alt="Imagen 1">
                <div class="carousel-text">
                    <h2>Título 1</h2>
                    <p>Este es un breve párrafo de ejemplo para la imagen 1.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="public/img/foto_carrusel_2.jpg" alt="Imagen 2">
                <div class="carousel-text">
                    <h2>Título 2</h2>
                    <p>Este es un breve párrafo de ejemplo para la imagen 2.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="public/img/foto_carrusel_3.jpg" alt="Imagen 3">
                <div class="carousel-text">
                    <h2>Título 3</h2>
                    <p>Este es un breve párrafo de ejemplo para la imagen 3.</p>
                </div>
            </div>
        </div>
        <!-- Controles del carrusel -->
        <div class="prev" onclick="prevSlide()">&#10094;</div>
        <div class="next" onclick="nextSlide()">&#10095;</div>
    </section>

    <!-- Sección de artículos -->
    <section class="articles">
        <h2>Título de Sección</h2>
        <div class="article-container">
            <article>
                <h3>Artículo 1</h3>
                <p>Este es el contenido del artículo 1. Un breve texto descriptivo.</p>
            </article>
            <article>
                <h3>Artículo 2</h3>
                <p>Este es el contenido del artículo 2. Otro breve texto descriptivo.</p>
            </article>
        </div>
    </section>

    <!-- Rectángulo de información importante -->
    <section class="important-info">
        <div class="info-box">
            <h3>Información Importante</h3>
            <p>Aquí puedes incluir un mensaje relevante para los usuarios, como una oferta especial o una promoción vigente.</p>
        </div>
    </section>

    <!-- Sección de productos -->
    <section class="products">
        <h2>Explora nuestros productos</h2>
        <div class="product-gallery">
            <div class="product-item"><img src="path/to/product1.jpg" alt="Producto 1"></div>
            <div class="product-item"><img src="path/to/product2.jpg" alt="Producto 2"></div>
            <div class="product-item"><img src="path/to/product3.jpg" alt="Producto 3"></div>
            <div class="product-item"><img src="path/to/product4.jpg" alt="Producto 4"></div>
            <div class="product-item"><img src="path/to/product5.jpg" alt="Producto 5"></div>
            <div class="product-item"><img src="path/to/product6.jpg" alt="Producto 6"></div>
        </div>
    </section>
</main>

<?php
    // Incluir el footer
    include('includes/footer.php');
?>

<!-- JavaScript -->
<script src="public/js/script.js" defer></script>
