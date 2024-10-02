<!DOCTYPE html>


<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprenta NuevoPack</title>


    <!-- Iconos -->
    <link rel="apple-touch-icon" href="public/images/icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="public/images/favicon.png">


    <!-- Hojas de estilo -->
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/estilos.css">


    <!-- Fuentes -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
</head>


<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">


            <a class="navbar-brand text-success logo h1 align-self-center" href="index.php">
                NuevoPack
            </a>


            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <!-- Boton 'Home' -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <!-- Barra de busqueda -->
                        <!--
                            Aca tiene que ir una barra de busqueda
                        -->
                        <!-- Boton desplegable 'Productos' -->
                        <!--
                            Aca tiene que ir un boton desplegable con todos los productos/categorias
                        -->
                        <!-- Boton 'Galeria de Disenos' -->
                        <li class="nav-item">
                            <a class="nav-link" href="pages/galeria_disenos.php">Galería de Diseños</a>
                        </li>
                        <!-- Boton 'Quienes Somos' -->
                        <li class="nav-item">
                            <a class="nav-link" href="pages/quienes_somos.php">Quiénes Somos</a>
                        </li>
                        <!-- Boton 'Contactanos' -->
                        <li class="nav-item">
                            <a class="nav-link" href="pages/contacto.php">Contáctanos</a>
                        </li>
                        <!-- Boton 'Ayuda' -->
                        <li class="nav-item">
                            <a class="nav-link" href="pages/ayuda.php">Ayuda</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <!-- Boton de busqueda NO FUNCIONAL-->
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Buscar ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <!-- Icono carrito NO FUNCIONAL-->
                    <a class="nav-icon position-relative text-decoration-none" href="#">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <!-- Cantidad de productos en el carrito = <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">7</span> -->
                    </a>
                    <!-- Icono cuenta NO FUNCIONAL-->
                    <a class="nav-icon position-relative text-decoration-none" href="#">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                    </a>
                </div>
            </div>


        </div>
    </nav>
    <!-- Fin del Header -->
</body>


</html>
