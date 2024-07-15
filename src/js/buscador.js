addEventListener('DOMContentLoaded', () => {
    iniciarApp();
})

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha() {
    // Seleccionamos el input 
    const inputFecha = document.querySelector("#fecha");
    inputFecha.addEventListener('input', function(e) {
        const fechaSeleccionada = e.target.value;
        window.location = `?fecha=${fechaSeleccionada}`;
    })
}
