<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php
    @include_once __DIR__ . '/../templates/barra.php'; 
    @include_once __DIR__ . '/../templates/alertas.php';  
?>

<form class="formulario" method='POST'>
    <?php @include 'formulario.php' ?>

    <input class="boton" type="submit" value="Actualizar Servicio">
</form>