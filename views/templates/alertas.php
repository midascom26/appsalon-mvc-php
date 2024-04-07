<?php
// Como la variable $alertas se pasa a la vista auth/crear-cuenta,
// por medio del controlador LoginController y el metodo crear(),
// se puede acceder a ella desde alertas.php
foreach($alertas as $key => $mensajes) { // estructura del array alertas['error'] = ['mensaje1', 'mensaje2', ...]
    foreach($mensajes as $mensaje) {
?>
        <div class="alerta <?php echo $key; ?>">
            <?php echo $mensaje; ?>
        </div>
<?php
    }
}
?>


