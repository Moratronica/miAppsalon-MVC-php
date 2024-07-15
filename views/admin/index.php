<h1 class="nombre-pagina">Panel de Administración</h1>

<?php @include_once __DIR__ . '/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>

<div class="busqueda">
    <div class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name='fecha' value='<?php echo $fecha?>'>
        </div>
    </div>
</div>

<?php 
    if(count($citas) === 0) { // count se usa para contar el contenido de un arreglo
        echo '<h2 class="no-cita">No hay citas en esta fecha</h2>';
    }
?>

<div class="citas-admin">
    <ul class="citas">
        <?php 
            $idCita = 0;
            foreach($citas as $key => $cita) : // key recogia en un arreglo asociativo el string que lo identifica en un array normal recoge su indice
            if($idCita !== $cita->id):
            $idCita = $cita->id;
            $total = 0;
        ?>
        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Correo: <span><?php echo $cita->correo; ?></span></p>
            <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>
            
            <h3>Servicios</h3>
            <?php endif; ?>
            <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio . '€'; ?></p>
            <?php 
                $total += $cita->precio; // Suma el precio del servicio
                $proximo = $citas[$key + 1]->id ?? 0; // Proximo indice del array
                
                if(esUltimo($idCita, $proximo)) : ?>
                    <p class='total'>Total: <span><?php echo $total . '€'; ?></span></p>

                    <form action="/api/eliminar" method='POST'>
                        <input type="hidden" name="id" value='<?php echo $cita->id; ?>'>
                        <input type="submit" class='boton-eliminar' value="Eliminar Cita">
                    </form>
            <?php 
                endif; 
                endforeach;
            ?>
    </ul>
</div>

<?php
    $script = '<script src="build/js/buscador.js"></script>'
?>