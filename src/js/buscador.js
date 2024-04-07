// Evento
document.addEventListener('DOMContentLoaded', () => {
    iniciarApp(); // Función que se ejecuta cuando el documento está listo
});

//----------------------iniciarApp----------------------
function iniciarApp() {
    buscarPorFecha();
}
//------------------------------------------------------

//--------------------buscarPorFecha--------------------
function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha'); // Selecciona el input de fecha del formulario de admin/index.php
    fechaInput.addEventListener('input', (e) => { // Agrega un evento input al input de fecha
        const fechaSeleccionada = e.target.value; // Obtiene el valor del input de fecha
        
        // Pasar la fecha a la URL a través de la query string, se obtiene por medio del GET
        // La query string es una cadena de texto que se añade al final de la URL de una página web
        window.location = `?fecha=${fechaSeleccionada}`; // Redirecciona a la URL actual con el parámetro `fecha` y su valor
    });
}
//------------------------------------------------------