<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php'; ?> <!-- Incluir el archivo barra.php -->

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>"> <!-- La variable $fecha se obtiene de AdminController -->
        </div>
    </form>
</div>

<?php
    if (empty($citas)) { // Si no hay citas
        echo "<h2>No hay citas programadas para esta fecha</h2>"; // Mostrar mensaje
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
            $idCita = 0; // Inicializar el id de la cita
            foreach ($citas as $key => $cita) : // Recorrer las citas
                if ($idCita !== $cita->id) : // Si el id de la cita es diferente al id de la cita actual           
                    $total = 0; // Inicializar el total
        ?>
                    <li>
                        <p>ID: <span><?php echo $cita->id; ?></span></p> <!-- Mostrar el id de la cita -->
                        <p>Hora: <span><?php echo $cita->hora; ?></span></p> <!-- Mostrar la hora de la cita -->
                        <p>Cliente: <span><?php echo $cita->cliente; ?></span></p> <!-- Mostrar el nombre del cliente -->
                        <p>Email: <span><?php echo $cita->email; ?></span></p> <!-- Mostrar el email del cliente -->
                        <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p> <!-- Mostrar el teléfono del cliente -->

                        <h3>Servicios</h3> <!-- Título de los servicios -->

                        <?php $idCita = $cita->id; ?> <!-- Asignar el id de la cita al idCita -->
                    <!--</li>--> <!-- html cierra la etiqueta automáticamenter por nosotros, con esto resolvemos el espacio en blanco que se genera en el html -->
                <?php endif; // Fin if
                $total += $cita->precio; // Sumar el precio de los servicios
                ?>
                <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p> <!-- Mostrar el servicio y el precio -->

                <?php
                    $actual = $cita->id; // Asignar el id de la cita a actual, osea el id en el que estamos
                    $proximo = $citas[$key + 1]->id ?? 0; // Obtenemos el id siguiente de la tabla de citas, si no existe asignamos 0

                    if (esUltimo($actual, $proximo)) { // Si el id actual es diferente al id siguient
                ?>
                        <p class="total">Total: <span>$<?php echo $total; ?></span></p> <!-- Mostrar el valor total de los servicios -->

                        <!-- Formulario para eliminar la cita -->
                        <form action="/api/eliminar" method="POST">
                            <input type="hidden" name="id" value="<?php echo $cita->id; ?>"> <!-- Input oculto con el id de la cita -->
                            <input type="submit" value="Eliminar" class="boton-eliminar"> <!-- Botón para eliminar la cita -->
                        </form>
                    <?php } ?>
            <?php endforeach; ?> <!-- Fin foreach -->
    </ul>
</div>

<?php
$script = "<script src='build/js/buscador.js'></script>" // Incluir el archivo buscador.js
?>