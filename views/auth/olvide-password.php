<h1 class="nombre-pagina">Olvide Contraseña</h1>
<p class="descripcion-pagina">Restablece tú contraseña escribiendo tu correo a continuación</p>

<?php @include __DIR__ . '/../templates/alertas.php'; ?>

<form method="POST" class="formulario">
    <div class="campo">
        <label for="correo">Correo</label>
        <input type="email" id="correo" placeholder="Tú Correo" name='correo'>
    </div>

    <input type="submit" value="Restablecer Contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>