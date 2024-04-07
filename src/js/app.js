let paso = 1; // Inicializa el paso en 1
const pasoInicial = 1; // Inicializa el paso inicial en 1
const pasoFinal = 3; // Inicializa el paso final en 3

// Objeto cita que almacena los datos de la cita
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

// Evento "DOMContentLoaded" que se ejecuta cuando el documento HTML ha sido completamente cargado y parseado
document.addEventListener('DOMContentLoaded', function () {
    iniciarApp(); // Inicia la aplicación
});

// ---------------------iniciarApp---------------------
function iniciarApp() {
    // Siempre que se cargue la vista /cita/index.php se muestra la sección "Servicios" puesto que la variable paso=1
    mostrarSeccion(); // Muestra y oculta las secciones según el paso seleccionado
    tabs(); // Cambia la sección cuando se presionan los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente(); // Muestra la página siguiente
    paginaAnterior(); // Muestra la página anterior

    consultarAPI(); // Consulta la API de servicios

    idCliente(); // Obtiene el id del cliente y lo asigna al atributo id del objeto cita
    nombreCliente(); // Obtiene el nombre del cliente y lo asigna al atributo nombre del objeto cita
    seleccionarFecha(); // Obtiene la fecha seleccionada y la asigna al atributo fecha del objeto cita
    seleccionarHora(); // Obtiene la hora seleccionada y la asigna al atributo hora del objeto cita

    mostrarResumen(); // Muestra el resumen de la cita
}
//-----------------------------------------------------

// ---------------------tabs---------------------------
function tabs() {
    const botones = document.querySelectorAll('.tabs button'); // Selecciona todos los botones de los tabs

    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            // Obtiene el paso del tab seleccionado "data-paso" especificado en el html de cada boton de cita/index.php
            paso = parseInt(e.target.dataset.paso);

            mostrarSeccion(); // Muestra la sección correspondiente al paso seleccionado
            botonesPaginador(); // Agrega o quita los botones del paginador
        });
    });
}
//-----------------------------------------------------

// ---------------------mostrarSeccion-----------------
function mostrarSeccion() {
    // Ocultar la sección que tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar'); // Elimina la clase "mostrar" de la sección anterior
    }

    // Seleccionar la sección a mostrar con el paso seleccionado
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar'); // Asigna la clase "mostrar" a la sección seleccionada

    // Elimina la clase "actual" del tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual'); // Elimina la clase "actual" del tab anterior
    }

    // Resalta el tab seleccionado
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual'); // Asigna la clase "actual" al tab seleccionado
}
//-----------------------------------------------------

// ---------------------botonesPaginador----------------

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior'); // Selecciona el botón "Anterior"
    const paginaSiguiente = document.querySelector('#siguiente'); // Selecciona el botón "Siguiente"

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar'); // Si el paso es 1, oculta el botón "Anterior"
        paginaSiguiente.classList.remove('ocultar'); // Si el paso es 1, muestra el botón "Siguiente"
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar'); // Si el paso es 3, muestra el botón "Anterior"
        paginaSiguiente.classList.add('ocultar'); // Si el paso es 3, oculta el botón "Siguiente"
        mostrarResumen(); // Muestra el resumen de la cita
    } else {
        paginaAnterior.classList.remove('ocultar'); // Si el paso no es 1, muestra el botón "Anterior"
        paginaSiguiente.classList.remove('ocultar'); // Si el paso no es 3, muestra el botón "Siguiente"
    }

    mostrarSeccion(); // Muestra la sección correspondiente al paso seleccionado
}
//-----------------------------------------------------

// ---------------------paginaAnterior-----------------

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior'); // Selecciona el botón "Anterior"

    // Evento "click" que se ejecuta cuando se presiona el botón "Anterior"
    paginaAnterior.addEventListener('click', function () {
        if (paso <= pasoInicial) return; // Si el paso es menor o igual al paso inicial, no hace nada
        paso--; // Decrementa el paso
        botonesPaginador(); // Agrega o quita los botones del paginador
    });
}
//-----------------------------------------------------

// ---------------------paginaSiguiente----------------

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente'); // Selecciona el botón "Siguiente"

    // Evento "click" que se ejecuta cuando se presiona el botón "Siguiente"
    paginaSiguiente.addEventListener('click', function () {
        if (paso >= pasoFinal) return; // Si el paso es mayor o igual al paso final, no hace nada
        paso++; // Incrementa el paso
        botonesPaginador(); // Agrega o quita los botones del paginador
    });
}
//-----------------------------------------------------

// ---------------------consultarAPI-------------------

// Función asíncrona que consulta la API
async function consultarAPI() {
    try { // Manejo de errores
        const url = `${location.origin}/api/servicios`; // URL de la API - location.origin obtiene la URL del servidor actual que en este caso sería http://localhost:3000
        const resultado = await fetch(url); // fetch para realizar la petición a la API, await para esperar la respuesta
        const servicios = await resultado.json(); // Convierte la respuesta a un array de objetos JSON
        mostrarServicios(servicios); // Muestra los servicios en la vista

    } catch (error) { // Si hay un error en la petición
        console.log(error);
    }
}
//-----------------------------------------------------

// ---------------------mostrarServicios----------------

function mostrarServicios(servicios) {
    // Itera sobre el array de servicios y los muestra en la vista
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio; // Destructuring de los datos del servicio

        const nombreServicio = document.createElement('P'); // Crea un parrafo
        nombreServicio.classList.add('nombre-servicio'); // Asigna la clase "nombre-servicio" al parrafo
        nombreServicio.textContent = nombre; // Asigna el nombre del servicio al parrafo

        const precioServicio = document.createElement('P'); // Crea un parrafo
        precioServicio.classList.add('precio-servicio'); // Asigna la clase "precio-servicio" al parrafo
        precioServicio.textContent = `$${precio}`; // Asigna el precio del servicio al parrafo

        const servicioDiv = document.createElement('DIV'); // Crea un div para el servicio
        servicioDiv.classList.add('servicio'); // Asigna la clase "servicio" al div
        // Asigna el id del servicio al div, el dataset permite almacenar datos personalizados, en este caso es el idServicio=id
        servicioDiv.dataset.idServicio = id;

        // Al hacer click en el servicio, se ejecuta la función seleccionarServicio
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio); // Agrega el nombre del servicio al div
        servicioDiv.appendChild(precioServicio); // Agrega el precio del servicio al div

        const listaServicios = document.querySelector('#servicios'); // Selecciona la clase lista-servicios de /cita/index.php
        listaServicios.appendChild(servicioDiv); // Agrega servicioDiv a listaServicios
    });
}
//-----------------------------------------------------

// ---------------------seleccionarServicio-------------

function seleccionarServicio(servicio) {
    const { id } = servicio; // Destructuring del atributo id del objeto servicio
    const { servicios } = cita; // Destructuring del atributo servicios del objeto cita

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); // Selecciona el div del servicio seleccionado

    // Conprobar si un servicio ya fue agregado
    if (servicios.some(agregado => agregado.id === id)) { // some() comprueba si al menos un elemento del array cumple con la condición, retorna true o false
        // Si el servicio ya ha sido agregado y damos click nuevamente en el mismo servicio, entonces lo eliminamos del array de servicios de la cita
        cita.servicios = servicios.filter(agregado => agregado.id !== id); // filter() crea un nuevo array con todos los elementos que cumplan la condición
        divServicio.classList.remove('seleccionado'); // Elimina la clase "seleccionado" del div del servicio seleccionado
    } else { // Si el servicio no ha sido agregado
        // Agregar el servicio al objeto de la cita
        cita.servicios = [...servicios, servicio]; // Añade el servicio seleccionado al array de servicios, utilizamos ...spread operator para no modificar el array original
        divServicio.classList.add('seleccionado'); // Agrega la clase "seleccionado" al div del servicio seleccionado
    }
}
//-----------------------------------------------------

// ---------------------idCliente-----------------------

function idCliente() {
    cita.id = document.querySelector('#id').value; // Obtiene el valor del input con id "id" y lo asigna al atributo id del objeto cita
}
//-----------------------------------------------------

// ---------------------nombreCliente-------------------

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value; // Obtiene el valor del input con id "nombre" y lo asigna al atributo nombre del objeto cita
}
//-----------------------------------------------------

// ---------------------seleccionarFecha----------------

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha'); // Selecciona el input con id "fecha"

    // Evento "input" que se ejecuta cuando se selecciona una fecha
    inputFecha.addEventListener('input', function (e) {
        const dia = new Date(e.target.value).getUTCDay(); // Obtiene el día de la semana de la fecha seleccionada

        if ([0, 6].includes(dia)) { // Si el día es domingo o sábado
            e.target.value = ''; // Limpia el input
            mostrarAlerta('No se permiten citas los fines de semana', 'error', '.formulario'); // Muestra una alerta con el mensaje 'No se permiten citas los fines de semana
        } else { // Si el día no es domingo o sábado
            cita.fecha = e.target.value; // Asigna la fecha seleccionada al atributo fecha del objeto cita
        }
    });
}
//------------------------------------------------------

// ---------------------seleccionarHora-----------------

function seleccionarHora() {
    const inputHora = document.querySelector('#hora'); // Selecciona el input con id "hora"

    // Evento "input" que se ejecuta cuando se selecciona una hora
    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value; // Obtiene la hora seleccionada
        const hora = horaCita.split(':'); // Divide la hora en un array de dos elementos separados por ":", ej: ["12", "45"]

        if (hora[0] < 10 || hora[0] > 18) { // Si la hora es menor a 10 o mayor a 18
            e.target.value = ''; // Limpia el input
            mostrarAlerta('Hora no válida', 'error', '.formulario'); // Muestra una alerta con el mensaje 'Hora no válida'
        } else { // Si la hora es mayor o igual a 10 y menor o igual a 18
            cita.hora = horaCita; // Asigna la hora seleccionada al atributo hora del objeto cita
        }
    });
}
//------------------------------------------------------

// ---------------------mostrarAlerta-------------------

function mostrarAlerta(mensaje, tipo, elemento, desaparace = true) {
    const alertaPrevia = document.querySelector('.alerta'); // Selecciona la clase alerta de /cita/index.php

    // Previene que se genere más de una alerta
    if (alertaPrevia) { // Si hay una alerta previa
        alertaPrevia.remove(); // Elimina la alerta
    } // pero si no hay una alerta previa entonces continúa con la ejecución de la función

    // Script para crear una alerta
    const alerta = document.createElement('DIV'); // Crea un div para la alerta
    alerta.textContent = mensaje; // Asigna el mensaje a la alerta
    alerta.classList.add('alerta', tipo); // Asigna las clases "alerta" y "tipo" a la alerta

    const referencia = document.querySelector(elemento); // Selecciona la clase que se pasa en el argumento "elemento" de /cita/index.php
    referencia.appendChild(alerta); // Agrega la alerta al elemento HTML

    if (desaparace) { // Si desaparace es true (por defecto es true)
        // Elimina la alerta después de 3 segundos
        setTimeout(() => {
            alerta.remove(); // Elimina la alerta
        }, 3000);
    }
}
//------------------------------------------------------

// ---------------------mostrarResumen------------------

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen'); // Selecciona la clase contenido-resumen de /cita/index.php

    // Limpia el HTML previo
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild); // Elimina todos los elementos hijos de resumen
    }

    // Object.values() retorna un array con los valores de las propiedades de un objeto
    if (Object.values(cita).includes('') || cita.servicios.length === 0) { // Si hay algún campo vacío en el objeto cita o si no hay servicios
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false); // Muestra una alerta con el mensaje 'Faltan datos de Servicios, Fecha u Hora
        return; // Detiene la ejecución de la función
    }

    //--------------- Formatear el div de resumen ----------------
    const { nombre, fecha, hora, servicios } = cita; // Destructuring de los atributos del objeto cita

    // Crea un h3 para el resumen de servicios
    const headingServicios = document.createElement('H3'); // Crea un h3
    headingServicios.textContent = 'Resumen de Servicios'; // Asigna el texto 'Resumen de Servicios' al h3
    resumen.appendChild(headingServicios); // Agrega headingServicios al div contenido-resumen

    // Itera sobre los servicios seleccionados y los muestra en el resumen
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio; // Destructuring de los atributos del objeto servicio

        const contenedorServicio = document.createElement('DIV'); // Crea un div para el servicio
        contenedorServicio.classList.add('contenedor-servicio'); // Asigna la clase "contenedor-servicio" al div

        const textoServicio = document.createElement('P'); // Crea un parrafo
        textoServicio.textContent = nombre; // Asigna el nombre del servicio al parrafo

        const precioServicio = document.createElement('P'); // Crea un parrafo
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`; // Asigna el precio del servicio al parrafo

        contenedorServicio.appendChild(textoServicio); // Agrega textoServicio al div contenedor-servicio
        contenedorServicio.appendChild(precioServicio); // Agrega precioServicio al div contenedor-servicio

        resumen.appendChild(contenedorServicio); // Agrega contenedorServicio al div contenido-resumen
    });

    // Crea un h3 para el resumen de la cita
    const headingCita = document.createElement('H3'); // Crea un h3
    headingCita.textContent = 'Resumen de la Cita'; // Asigna el texto 'Resumen de la Cita' al h3
    resumen.appendChild(headingCita); // Agrega headingCita al div contenido-resumen

    // Crea un parrafo para el nombre
    const nombreCliente = document.createElement('P'); // Crea un parrafo
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`; // Asigna el nombre del cliente al parrafo

    //----------------- Formatear la fecha en español --------------------------------
    // Cada vez que se utilize un objeto Date, se debe tener en cuenta que hay un desfase de 1 día.
    // Como se va a utilizar new Date() 2 veces, se debe tener en cuenta que el desfase se duplica.
    const fechaObj = new Date(fecha); // Crea un objeto fecha con la fecha seleccionada
    const year = fechaObj.getFullYear(); // Obtiene el año de la fecha seleccionada
    const mes = fechaObj.getMonth(); // Obtiene el mes de la fecha seleccionada, inicia en 0 a 11
    const dia = fechaObj.getDate() + 2; // Obtiene el día de la fecha seleccionada inicia en 0. se Suma 2 dias por el desface de utilizar 2 veces new Date()

    // Crea un objeto fecha con la fecha seleccionada en UTC, UTC es el tiempo universal coordinado (GMT), ejemplo: Mon Apr 15 2024 19:00:00 GMT-0500 (hora estándar de Colombia)
    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }; // Opciones para formatear la fecha
    const fechaFormateada = fechaUTC.toLocaleDateString('es-CO', opciones); // Formatea la fecha en español, ejemplo: lunes, 15 de abril de 2024
    //--------------------------------------------------------------------------------

    // Crea un parrafo para la fecha
    const fechaCita = document.createElement('P'); // Crea un parrafo
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`; // Asigna la fecha de la cita al parrafo
    //--------------------------------------------------------------------------------

    // Crea un parrafo para la hora
    const horaCita = document.createElement('P'); // Crea un parrafo
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`; // Asigna la hora de la cita al parrafo

    // Botón para Crear una cita
    const botonReservar = document.createElement('BUTTON'); // Crea un botón
    botonReservar.classList.add('boton'); // Asigna la clase "boton" al botón
    botonReservar.textContent = 'Reservar Cita'; // Asigna el texto 'Reservar Cita' al botón
    // Evento "click" que se ejecuta cuando se presiona el botón "Reservar Cita"
    botonReservar.onclick = reservarCita; // Al hacer click en el botón, se ejecuta la función reservarCita

    resumen.appendChild(nombreCliente); // Agrega nombreCliente al div contenido-resumen
    resumen.appendChild(fechaCita); // Agrega fechaCita al div contenido-resumen
    resumen.appendChild(horaCita); // Agrega horaCita al div contenido-resumen

    resumen.appendChild(botonReservar); // Agrega botonReservar al div contenido-resumen
}
//------------------------------------------------------

// ---------------------reservarCita--------------------

// Función asíncrona que reserva la cita
async function reservarCita() {
    const { nombre, fecha, hora, servicios, id } = cita; // Destructuring de los atributos del objeto cita

    // Solo se necesita pasar a la base de datos el id de los servicios
    const idServicios = servicios.map(servicio => servicio.id); // Mapea los servicios y obtiene los id de los servicios. Retorna un array con los id de los servicios

    // FormData es una interfaz que permite construir un conjunto de pares clave/valor que representan campos de un formulario y sus valores.
    // FormData se utiliza para enviar datos a un servidor.
    const datos = new FormData(); // Crea un objeto FormData para enviar los datos al servidor

    datos.append('fecha', fecha); // Añade la fecha de la cita al objeto FormData
    datos.append('hora', hora); // Añade la hora de la cita al objeto FormData
    datos.append('usuarioId', id); // Añade el id del cliente al objeto FormData
    datos.append('servicios', idServicios); // Añade los id de los servicios al objeto FormData

    // IMPORTANTE: con console.log([...datos]); se puede ver el contenido del objeto FormData
    //console.log([...datos]);

    // try-catch para manejar errores
    try {
        // Petición POST hacia la API
        const url = `${location.origin}/api/citas`; // URL de la API - location.origin obtiene la URL del servidor actual que en este caso sería http://localhost:3000
        const respuesta = await fetch(url, { // fetch para realizar la petición a la API, await para esperar la respuesta
            method: 'POST', // Método POST
            body: datos // Datos a enviar al servidor - Se pasa el objeto FormData
        });
        const resultado = await respuesta.json(); // Convierte la respuesta a un objeto JSON

        // Si la cita fue creada correctamente
        if (resultado.resultado) { // Si el resultado es true
            // Utilizando la librería SweetAlert2
            // Muestra una alerta con el mensaje 'Cita Creada' y el ícono de éxito
            Swal.fire({
                icon: "success", // Ícono de éxito
                title: "Cita Creada", // Título de la alerta
                text: "Tu cita fue creada correctamente", // Texto de la alerta
                button: "OK", // Texto del botón
            }).then(() => { // Después de presionar el botón "OK"
                setTimeout(() => { // Después de 3 segundos
                    window.location.reload(); // Recarga la página
                }, 1000);
            });
        }
    } catch (error) { // Si hay un error en la petición
        // Utilizando la librería SweetAlert2
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita, intenta de nuevo",
        });
    }

}
//------------------------------------------------------