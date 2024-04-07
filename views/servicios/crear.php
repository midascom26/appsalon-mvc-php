<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Llena todos los campos para añadir un nuevo servicio</p>

<?php
    include_once __DIR__ . '/../templates/barra.php'; // Incluir el archivo barra.php
    include_once __DIR__ . '/../templates/alertas.php'; // Incluir el archivo alertas.php
?>

<form action="/servicios/crear" method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?> <!-- Incluir el archivo formulario.php -->
    <input type="submit" class="boton" value="Guardar Servicio"> <!-- Botón para guardar el servicio -->
</form>