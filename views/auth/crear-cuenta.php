<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">
    Llena el siguiente formulario para crear una cuenta
</p>

<?php
include_once __DIR__ . '/../templates/alertas.php'; // Incluimos el archivo alertas.php
?>

<form class="formulario" method="post" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tu Nombre"
            value="<?php echo s($usuario->nombre); ?>"
        > <!-- Cargamos en value los datos del objeto usuario['nombre'] -->
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Tu Apellido"
            value="<?php echo s($usuario->apellido); ?>"
        > <!-- Cargamos en value los datos del objeto usuario['apellido'] -->
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Tu Teléfono"
            value="<?php echo s($usuario->telefono); ?>"
        > <!-- Cargamos en value los datos del objeto usuario['telefono'] -->
    </div>

    <div class="campo">
        <label for="email">E-mail</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email"
            value="<?php echo s($usuario->email); ?>"
        > <!-- Cargamos en value los datos del objeto usuario['email'] -->
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password"
        > <!-- No cargamos en value los datos del objeto usuario['password'] por seguridad -->
    </div>

    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>