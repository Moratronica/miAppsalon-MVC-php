let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre : '',
    fecha : '',
    hora : '',
    servicios : []
}

document.addEventListener('DOMContentLoaded' , function() {
    iniciarApp();
});


function iniciarApp() {
    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaAnterior();
    paginaSiguiente();

    consultarAPI(); // Consulta la API en el backend de PHP

    idCliente(); // Añade el id del cliente al objeto cita
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita al objeto de cita
    seleccionarHora(); // Añade la hora de la cita al objeto de cita
}

function mostrarSeccion() {

    // Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`); // selector atributo []
    tab.classList.add('actual');
    
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button'); 
    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function botonesPaginador() {

    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    switch(paso) {
        case 1:
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar');
            break;
        case 3:
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');
            mostrarResumen();
            break;
        default:
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.remove('ocultar');
            break;            
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click' , () => {

        if(paso<= pasoInicial) return;
        paso--;
        botonesPaginador();
    });
    
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click' , () => {

        if(paso>= pasoFinal) return;
        paso++;
        botonesPaginador();
    });
}

async function consultarAPI() {

    try {
        const url = '/api/servicios'; // url a consumir `${location.origin}/api/servicios`; location.origin equivaldria a http://localhost:3000 esto es
        const resultado = await fetch(url);             // util cuando esta separado el js con el backend en nuestro caso vale con quitar el dominio del enlace al estar junto
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre , precio } = servicio; // Crea variables con esos nombres cuyo contenido es el atributo con ese nombre
        
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = precio + '€';

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.onclick = function() { // hacer un callback
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        const servicios = document.querySelector('#servicios');
        servicios.appendChild(servicioDiv);

    })
}

function seleccionarServicio(servicio) {
    const { id } = servicio; // id del servicio
    const { servicios } = cita // variable servicios con el valor del arreglo del objeto cita llamado servicios

    // Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comrpobar si un servicio ya fue agregado
    if(servicios.some( agregado => agregado.id === id)){ // some sirve para revisar si en un arreglo ya tiene un elemento
        // Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id); // Sobreescribes el arreglo con los servicios que tengan id diferente (eliminas el que coincida)
        divServicio.classList.remove('seleccionado');
    } else {
        // Agregarlo
        cita.servicios = [...servicios, servicio] // hace copia de servicios y le agrega el nuevo servicio
        divServicio.classList.add('seleccionado');
    }
}

function idCliente() {
    const id = document.querySelector('#id').value; // Accedes al valor con value del elemento seleccionado por id
    cita.id = id;
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value; // Accedes al valor con value del elemento seleccionado por id
    cita.nombre = nombre;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {

        const dia = new Date(e.target.value).getUTCDay(); // Devuelve un valor del 0 al 6 segun el dia de la semana

        if( [0, 6].includes(dia) ) { // comprueba si incluye el valor de la variable dia en ese arreglo
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error', 'formulario');
        } else {
            cita.fecha = e.target.value;
        }
        
    });
}


function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {

        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0]; // Crea un arreglo separandolo por el caracter pasado ejemplo: "11:00" => ["11", "00"]

        if(hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Hora no válida', 'error', 'formulario');
        } else {
            cita.hora = e.target.value;
        }
    }); 
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // si existe una alerta no continua el bucle, evitando que se genere mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    }; 

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add("alerta");
    alerta.classList.add(tipo);

    const referencia = document.querySelector(`.${elemento}`);
    referencia.appendChild(alerta);

    if(desaparece) {
        // Eliminar la alerta
        setTimeout(() => {
            alerta.remove(); // En 3 segundos se elimina la alerta  
        }, 3000);
    }
}

function mostrarResumen() {
    const resumen = document.querySelector(".contenido-resumen");

    // Limpiar contenido-resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos de servicios, Fecha u Hora', 'error', 'contenido-resumen', false);
        return;
    } 

    // Formatear el div de resumen
    const {nombre, fecha, hora , servicios} = cita;
    

    // Heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    // Iterando y Mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add("contenedor-servicio");

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })
    
    // Heading para cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate();
    const year = fechaObj.getFullYear();
    
    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-ES', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

    // Boton crear cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent='Reservar Cita';
    botonReservar.onclick = reservarCita; // No se pone el parentesis porque sino llamas a la funcion en vez de asociarla

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar);
}

async function reservarCita() {
    
    const { id, fecha, hora , servicios} = cita;

    const idServicios = servicios.map(servicio => servicio.id); // coloca las coincidencias en la variable asi estraemos los id
    const datos = new FormData(); // para enviar datos con fetch, es  el submit pero con JS para enviar datos de formularios o archivos
    datos.append('usuarioId', id); // Añadimos datos al FormData
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);

    //console.log([...datos]); // con espirador operator podemos ver el contenido del formdata sino no podriamos  

    try {
        // Petición hacia la API
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos // Cuerpo de la petición que vamos a mandar
        });

        const resultado = await respuesta.json();
        
        if(resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tú cita fue creada correctamente",
            }).then(()=>{
                setTimeout(() => {
                    window.location.reload(); // Sirve para recargar la página
                }, 2000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita",
          });                  
    }

       
}