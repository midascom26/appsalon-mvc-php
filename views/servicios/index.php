<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php
    include_once __DIR__ . '/../templates/barra.php'; // Incluir el archivo barra.php
?>

<ul class="servicios">
    <?php foreach ($servicios as $servicio) : // Recorrer los servicios ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span></p> <!-- Mostrar el nombre del servicio -->
            <p>Precio: <span>$<?php echo $servicio->precio; ?></span></p> <!-- Mostrar el precio del servicio -->
        
            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a> <!-- Botón para actualizar el servicio -->

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>"> <!-- Input oculto con el id del servicio -->
                    <input type="submit" class="boton-eliminar" value="Eliminar"> <!-- Botón para eliminar el servicio -->
                </form>
            </div>
        </li>
    <?php endforeach; ?> <!-- Fin foreach -->
</ul>
