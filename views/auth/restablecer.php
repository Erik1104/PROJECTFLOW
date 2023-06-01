<div class="contenedor restablecer">

  <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">

        <p class="descripcion-pagina">Coloca tu nueva contraseña.</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($mostrar) { ?>

        <form class="formulario" method="POST">

            <div class="campo">
                <label for="password">Contraseña:</label>
                <input
                  type="password"
                  id="password"
                  placeholder="Tu nueva contraseña"
                  name="password"
                />
            </div>

            <input type="submit" class="boton" value="Guardar contraseña">

        </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta?Crea una.</a>
            <a href="/">¿Ya tienes una cuenta?Iniciar Sesion.</a>
        </div>
    </div>
</div>