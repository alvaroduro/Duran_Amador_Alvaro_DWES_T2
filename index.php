<!--Principal Tarea Online 2 DWES-->
<?php require 'includes/header.php'; ?>

<body>

    <!--Título-->
    <div class="container my-3">
        <div class="container text-center">
            <p>
            <h1>Control <img id="imagen" class="mx-2 my-2" src="img/icono_login.png" width="50px" height="50px" /> Acceso Biblioteca</h1>
            </p>
        </div>
    </div>

    <!--Formulario login de Bootstrap-->
    <div class="container form_login my-3" id="imagenContainer">
        <form action="" method="POST"><!--Enviamos los datos-->
            <div class="mb-3">
                <!--Usuario-->
                <label for="usuario" class="form-label"> Usuario</label>
                <input class="form-control" name="email" type="text" placeholder="Nombre usuario" aria-label="default input example">
                <div id="email1" class="form-text"></div>
            </div>

            <!--Password-->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button name="btningresar" type="submit" class="btn btn-primary">INICIAR SESION</button>
        </form>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>

</html>