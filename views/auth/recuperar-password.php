<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php
include_once __DIR__ . '/../templates/alertas.php'; // Incluimos el archivo alertas.php
?>

<?php
    // la variable $error viene del metodo recuperar() del controlador LoginController
    // y se pasa a la vista en el método render() del controlador
    if($error) {
        return; // Si hay un error entonces no se muestra el formulario, se interrumpe la ejecución del código
    }
?>
    
<!-- Los datos se pasan a la misma url por eso no se especifica el action -->
<form class="formulario" method="POST"> <!-- Como el token lo tiene en la url entonces no especifico el action="" porque si no lo borra -->
    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            placeholder="Tu Nuevo Password"
            name="password"
        >
    </div>
    <input type="submit" class="boton" value="Guardar Nuevo Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Obtener una</a>
</div>
