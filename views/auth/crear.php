<div class="contenedor crear">

<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina"> Crea tu cuenta en ProjectFlow</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form class="formulario" method="POST" action="/crear">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                  type="text"
                  id="nombre"
                  placeholder="Tu Email"
                  name="nombre"
                  value="<?php $usuario->nombre; ?>"
                />
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  placeholder="Tu Email"
                  name="email"
                  value="<?php $usuario->email; ?>"
                />
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input
                  type="password"
                  id="password"
                  placeholder="Tu Contraseña"
                  name="password"
                />
            </div>

            <div class="campo">
                <label for="password2">Confirma Tu Contraseña</label>
                <input
                  type="password"
                  id="password2"
                  placeholder="Repite Tu Contraseña"
                  name="password2"
                />
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">

        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?Inicia Sesion.</a>
            <a href="/olvide">Olvide mi contraseña.</a>
        </div>
    </div>
</div>