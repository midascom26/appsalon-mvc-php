<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html); // Convierte caracteres especiales en entidades HTML
    return $s;
}

// Función que revisa que el usuario haya iniciado sesión
function isAuth() : void {
    if (!isset($_SESSION['login'])) { // Si no existe la sesión login
        header('Location: /'); // Redirigir al login (index.php)
    }
}

// Función que revisa si en la cita el id actual es diferente al id siguiente
function esUltimo(string $actual, string $proximo) : bool {
    return $actual !== $proximo; // Si el id actual es diferente al id siguiente retorna true de lo contrario false
}

// Función que revisa si el usuario es administrador
function isAdmin() : void {
    if (!isset($_SESSION['admin'])) { // Si no existe la sesión admin
        header('Location: /'); // Redirigir al login (index.php)
    }
}