<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Llena todos los campos para añadir un nuevo servicio</p>

<?php
    @include_once __DIR__ . '/../templates/barra.php'; 
    @include_once __DIR__ . '/../templates/alertas.php';  
?>

<form class="formulario" method='POST'>
    <?php @include 'formulario.php' ?>

    <input class="boton" type="submit" value="Guardar Servicio">
</form>
