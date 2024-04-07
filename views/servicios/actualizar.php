<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php
    include_once __DIR__ . '/../templates/barra.php'; // Incluir el archivo barra.php
    include_once __DIR__ . '/../templates/alertas.php'; // Incluir el archivo alertas.php
?>

<!-- No se coloca action="/servicios/actualizar" porque en la url se está pasando el id del servicio que se va a actualizar, -->
<!-- Ejemplo: "http://localhost:3000/servicios/actualizar?id=1" y el $router->post('/servicios/actualizar') no recibe parámetros, en este caso el id -->
<!-- Por lo tanto no se coloca action="/servicios/actualizar" pero automaticamante lo va a enviar a la -->
<!-- url http://localhost:3000/servicios/actualizar?id=1 y respeta el id, de otra forma se perdería en algun punto la referencia al id -->
<form method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?> <!-- Incluir el archivo formulario.php -->
    <input type="submit" class="boton" value="Actualizar"> <!-- Botón para guardar el servicio -->
</form>