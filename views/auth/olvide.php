<div class="contenedor olvide">

  <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Si has olvidado tu contraseña, coloca tu Email para poder recuperarla.</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form class="formulario" method="POST" action="/olvide">

            <div class="campo">
                <label for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  placeholder="Tu Email"
                  name="email"
                />
            </div>

            <input type="submit" class="boton" value="Enviar Instrucciones">

        </form>

        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta?Crea una.</a>
            <a href="/">Iniciar Sesion</a>
        </div>
    </div>
</div>