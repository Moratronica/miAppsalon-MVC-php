<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inica sesión con tus datos</p>
<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="correo">Correo</label>
        <input type="email" id="correo" placeholder="Tú Correo" name='correo'>
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tú Contraseña" name='password'>
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/olvide">¿Olvidaste tú contraseña?</a>
</div>