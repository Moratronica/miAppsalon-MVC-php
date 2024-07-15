<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php @include_once __DIR__ . '/../templates/alertas.php' ?>

<form method="POST" class="formulario">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" placeholder="Tú Nombre" name='nombre' value="<?php echo s($usuario->nombre); ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" placeholder="Tú Apellido" name='apellido' value="<?php echo s($usuario->apellido); ?>">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" placeholder="Tú Teléfono" name='telefono' value="<?php echo s($usuario->telefono); ?>">
    </div>

    <div class="campo">
        <label for="correo">Correo</label>
        <input type="email" id="correo" placeholder="Tú Correo" name='correo' value="<?php echo s($usuario->correo); ?>">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tú Contraseña" name='password' >
    </div>

    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tú contraseña?</a>
</div>