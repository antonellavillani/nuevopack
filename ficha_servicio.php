<?php
// Incluir el header
include('includes/header.php');

// Incluir el archivo de configuración de la base de datos
require_once('config/config.php');

// Obtener el ID del servicio de la URL (a través del método GET)
$idServicio = $_GET['idServicio'] ?? '';

// Consulta para obtener la información del servicio
$query = "SELECT * FROM servicios WHERE id = :idServicio";
$stmt = $conn->prepare($query);
$stmt->bindParam(':idServicio', $idServicio, PDO::PARAM_INT);
$stmt->execute();
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener la tabla de precios
$queryPrecios = "SELECT * FROM precios_servicios";
$stmtPrecios = $conn->prepare($queryPrecios);
$stmtPrecios->execute();
$precios = $stmtPrecios->fetchAll(PDO::FETCH_ASSOC);

// Convertir precios a un formato fácil de usar
$preciosServicios = [];
foreach ($precios as $precio) {
    $id = intval($precio['servicio_id']);
    $clave = strtolower(trim($precio['descripcion']));
    $preciosServicios[$id][$clave] = floatval($precio['precio']);
}
?>

<body>
<div class="contenedor-ficha-servicio">

    <!-- CONTENEDOR 1: FOTO DEL SERVICIO + CALCULADORA -->
    <div class="fila-doble" data-aos="fade-up">
        <div class="columna-izquierda contenedor-ficha-servicio">
            <h1 class="nombre-producto"><?= htmlspecialchars($servicio['nombre'] ?? 'Servicio no encontrado') ?></h1>
            <div class="imagen-producto">
                <img src="uploads/<?= htmlspecialchars($servicio['foto'] ?? 'foto_producto_null.jpg') ?>" alt="<?= htmlspecialchars($servicio['nombre'] ?? 'Servicio') ?>">
            </div>
        </div>

        <div class="columna-derecha">
            <?php if ($idServicio == 5): ?>
                <!-- Carrusel de modelos para almanaques -->
                <section class="carrusel-almanaques contenedor-ficha-servicio">
                    <h2 class="nombre-producto">Modelos de almanaques disponibles</h2>
                    <div class="contenedor-carrusel">
                        <!-- Tarjeta 1 -->
                        <div class="tarjeta-modelo">
                            <div class="imagen-con-hover">
                                <img src="public/img/modelo-mensual.jpg" alt="Modelo 1">
                                <div class="texto-hover">Click para ampliar</div>
                            </div>
                            <h4 class="texto-centrado">Modelo Mensual</h4>
                            <p class="texto-centrado">37x53cm abierto, con zona publicitaria de 37x27cm.<br>Incluye fotocromo, laqueado brillante, mensual de 34x23cm impreso en 3 colores sobre papel de 90g.<br>Soporte de cartulina Triplex 370g y varilla metálica para colgar.</p>
                        </div>
                        <!-- Tarjeta 2 -->
                        <div class="tarjeta-modelo">
                            <div class="imagen-con-hover">
                                <img src="public/img/modelo-trimestral.jpg" alt="Modelo 2">
                                <div class="texto-hover">Click para ampliar</div>
                            </div>
                            <h4 class="texto-centrado">Modelo Trimestral Tipo Marítimo</h4>
                            <p class="texto-centrado">29x65cm desplegado, 29x44cm cerrado.<br>Zona publicitaria de 29x21cm.<br>Incluye tres mensuales en papel obra de 90g, impresos en 3 colores.<br>Soporte en cartulina Triplex 370g, con anillado metálico Ring Wire, indicador de fecha rojo y varilla metálica para colgar.</p>
                        </div>
                    </div>
                </section>
            <?php else: ?>
            <!-- Calculadora de precios -->
            <form id="form-calculadora" action="backend/calcular_precio.php" method="POST">
                <section class="calculadora calculadora-form">
                <h2 class="nombre-producto">Calculadora de precios</h2>

                <!-- Impresión -->
                <h3>Impresión</h3>
                <div class="grupo-campo">
                    <label for="ctp">CTP (chapa)</label>
                    <input type="number" name="ctp" id="ctp" placeholder="Ingrese la cantidad de CTP (chapas)">
                </div>
                <div class="grupo-campo">
                    <label for="postura_impresion">Postura</label>
                    <input type="number" name="postura_impresion" id="postura_impresion" placeholder="Ingrese la cantidad de posturas">
                </div>
                <div class="grupo-campo">
                    <label for="millar_impresion">Millares</label>
                    <input type="number" name="millar_impresion" id="millar_impresion" placeholder="Ingrese la cantidad de millares">
                </div>

                <!-- Troquelado -->
                <h3><input type="checkbox" name="troquelado_toggle" id="troquelado_toggle"> Troquelado</h3>
                <div id="troquelado_seccion">
                    <div class="grupo-campo">
                        <label for="bocas">Cantidad de bocas (simple)</label>
                        <input type="number" name="bocas" id="bocas" placeholder="Ingrese la cantidad de bocas">
                    </div>
                    <div class="grupo-campo">
                        <label for="millar_troquelado">Millares</label>
                        <input type="number" name="millar_troquelado" id="millar_troquelado" placeholder="Ingrese la cantidad de millares">
                    </div>
                </div>

                <!-- Barniz -->
                <h3><input type="checkbox" name="barniz_toggle" id="barniz_toggle"> Barniz</h3>
                <div id="barniz_seccion">
                    <div class="grupo-campo">
                        <label for="postura_barniz">Postura</label>
                        <input type="number" name="postura_barniz" id="postura_barniz" placeholder="Ingrese la cantidad de posturas">
                    </div>
                    <div class="grupo-campo">
                        <label for="millar_barniz">Millar</label>
                        <input type="number" name="millar_barniz" id="millar_barniz" placeholder="Ingrese la cantidad de millares">
                    </div>
                </div>

                <!-- Pegado de estuches -->
                <h3><input type="checkbox" name="estuches_toggle" id="estuches_toggle"> Pegado de estuches</h3>
                <div id="estuches_seccion">
                    <p>Medida del estuche abierto</p>
                    <div class="grupo-campo">
                        <label><input type="radio" name="medida_estuche" value="estuches de 10cm a 15cm"> 10cm a 15cm</label><br>
                        <label><input type="radio" name="medida_estuche" value="estuches de 16cm a 25cm"> 16cm a 25cm</label><br>
                        <label><input type="radio" name="medida_estuche" value="estuches de 26cm a 50cm"> 26cm a 50cm</label>
                    </div>
                    <div class="grupo-campo">
                        <label for="cantidad_estuches">Cantidad</label>
                        <input type="number" name="cantidad_estuches" id="cantidad_estuches" placeholder="Ingrese la cantidad de estuches">
                    </div>
                </div>

                <button type="submit" name="calcular_precio">Calcular</button>
                <div id="resultado-calculadora"></div>
                </section>
            </form>

            <?php endif; ?>
        </div>
    </div>

    <!-- CONTENEDOR 2: DESCRIPCIÓN + MEDIOS DE PAGO -->
    <div class="fila-doble" data-aos="fade-up">
        <div class="columna-izquierda contenedor-ficha-servicio">
            <h3 class="nombre-producto">Descripción</h3>
            <p class="font-size-descripcion"><?= htmlspecialchars($servicio['descripcion'] ?? 'Descripción no disponible.') ?></p>
        </div>
        <div class="columna-derecha contenedor-ficha-servicio">
            <h3 class="nombre-producto">Medios de Pago</h3>
            <ul class="medios-pago">
                <li>Efectivo</li>
                <li>Transferencia Bancaria</li>
                <li>E-Check o cheque físico</li>
            </ul>
        </div>
    </div>

    <!-- CONTENEDOR 3: FORMULARIO DE CONTACTO -->
    <div class="formulario-contacto" id="formulario-contacto" data-aos="fade-up">
        <h3 class="nombre-producto texto-centrado">Contacto</h3>
        <div class="scroll-formulario-contacto">
            <form id="form-contacto" action="backend/enviar_correo.php" method="POST" enctype="multipart/form-data">
                <div class="form-grupo">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Escribí tu nombre" required>
                </div>

                <div class="form-grupo">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Escribí tu correo electrónico" required>
                </div>

                <div class="form-grupo">
                    <label for="telefono">Teléfono (opcional):</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ej.: 11 2233 4455" inputmode="numeric" pattern="[0-9]*" maxlength="15" title="Solo se permiten números">
                </div>


                <!-- Servicios requeridos con cantidad -->
                <fieldset class="fieldset-servicios">
                    <legend>Servicios requeridos:</legend>

                    <div class="checkbox-wrapper-46">
                        <label class="opcion-servicio">
                        <input class="inp-cbx" type="checkbox" id="servicio_impresion" name="servicios[]" value="impresion" />

                        <span class="cbx">
                            <span>
                                <svg width="12px" height="10px" viewBox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                            <span>Servicio de impresión</span>
                        </span>
                        </label>

                        <label class="opcion-servicio">
                        <input class="inp-cbx" type="checkbox" id="servicio_troquelado" name="servicios[]" value="troquelado" />
                        <span class="cbx">
                            <span>
                                <svg width="12px" height="10px" viewBox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                            <span>Servicio de troquelado</span>
                        </span>
                    </label>

                    <label class="opcion-servicio">
                        <input class="inp-cbx" type="checkbox" id="servicio_pegado" name="servicios[]" value="pegado_estuches" />
                        <span class="cbx">
                            <span>
                                <svg width="12px" height="10px" viewBox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                            <span>Servicio de pegado de estuches</span>
                        </span>
                    </label>

                    <label class="opcion-servicio">
                        <input class="inp-cbx" type="checkbox" id="servicio_almanaques" name="servicios[]" value="almanaques" />
                        <span class="cbx">
                            <span>
                                <svg width="12px" height="10px" viewBox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                            <span>Servicio de almanaques</span>
                        </span>
                    </label>
                </div>
            </fieldset>

                <!-- Descripción -->
                <div class="form-grupo">
                    <label for="descripcion">Descripción del pedido:</label>
                    <textarea id="descripcion" name="descripcion" rows="10" placeholder="Ej: Necesito 500 cajas troqueladas, impresas a color..."></textarea>
                </div>
                
                <!-- Diseño -->
                <fieldset class="fieldset-servicios">
                    <legend>Diseño gráfico requerido:</legend>
                    <div class="radio-input">
                        <label class="label">
                            <input type="radio" name="diseno" value="tengo" required />
                            <p class="text">Ya tengo el diseño</p>
                        </label>
                        <label class="label">
                            <input type="radio" name="diseno" value="necesito" />
                            <p class="text">Necesito un diseño</p>
                        </label>
                    </div>
                </fieldset>

                <!-- Subir archivo -->
                <div class="form-grupo">
                    <label for="archivo">Subí tu diseño o referencia (opcional):</label>
                    <label for="archivo" class="custum-file-upload">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <div class="text">
                            <span>Subí tu diseño o referencia</span>
                        </div>
                        <input type="file" id="archivo" name="archivo" accept=".jpg,.jpeg,.png,.pdf,.ai,.psd">
                    </label>
                    <p id="archivo-nombre" id="nombre-archivo"></p>
                </div>

                <!-- Medio contacto -->
                <div class="form-grupo">
                    <label for="medio">¿Cómo preferís que te contactemos?</label>
                    <select id="medio" name="medio">
                        <option value="" selected>Seleccionar</option>
                        <option value="email">Correo electrónico</option>
                        <option value="telefono">Teléfono</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <p id="error-medio" class="mensaje-advertencia">Por favor, seleccioná una opción.</p>
                </div>

                <!-- Cómo nos conociste -->
                <div class="form-grupo">
                    <label for="conocio">¿Cómo nos conociste? (opcional)</label>
                    <select id="conocio" name="conocio">
                        <option value="" selected>Seleccionar</option>
                        <option value="google">Google</option>
                        <option value="redes">Redes sociales</option>
                        <option value="recomendacion">Recomendación</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div id="input-oculto-otro" class="form-grupo">
                    <label for="conocio_otro">Contanos cómo nos conociste:</label>
                    <input type="text" id="conocio_otro" name="conocio_otro">
                </div>

                <div class="form-grupo">
                    <button type="submit">Enviar consulta</button>
                </div>

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
            <div id="mensaje-respuesta" class="mensaje-envio-mail texto-centrado"></div>
        </div>
    </div>
</div>

<script>
    const preciosServicios = <?= json_encode($preciosServicios) ?>;
</script>

<?php
// Incluir el footer
include('includes/footer.php');
?>

<!-- Modal para mostrar la imagen de los almanaques ampliada -->
<div id="modalImagen" class="modal-imagen">
  <span class="cerrar-modal">&times;</span>
  <img class="contenido-modal" id="imagenAmpliada" alt="Imagen ampliada">
</div>

<script src="public/js/script.js"></script>

</body>