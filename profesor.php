<!--Pantalla principal Porfesores-->
<?php require 'includes/header.php'; ?>
<?php
if (isset($_GET['rol']) && isset($_GET['nombre'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= ".$rolUsuario . ", nombre= " . $nombre . ",idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}
?>

<body>
    <!-- Contenedor principal -->
    <div class="container text-center my-5">

        <!--Ponemos el nombre del profesor-->
        <p>Bienvenido, <b><?php echo $nombre ?></b></p>

        <!-- Grupo de enlaces -->
        <div class="d-grid gap-3 mx-auto col-6">

            <!-- Título -->
            <div class="d-flex flex-row mb-3 justify-content-evenly">
                <h1 class="text-center">Elija Operación a Realizar</h1>
                <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
            </div>

            <!-- Listar libros disponibles pasando el rol, idProf y nombre-->
            <a href="listarLibros.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>" class="btn btn-primary btn-lg shadow-sm">
                <img class="mx-2" src="img/listarLibros.png" alt="listarLibro" width="30" height="30">Listar Libros
            </a>

            <!--Modificar Datos Usuario-->
            <a class="btn btn-success btn-lg shadow-sm" href="actUsuario.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img class="mx-auto" width="40" height="40" src="img/modificarUser.png" alt="modificar usuario">Modificar mis Datos</a>

            <!-- Estado préstamos -->
            <a href="listarPrestamos.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>" class="btn btn-light btn-lg shadow-sm">
                <img class="mx-2" src="img/prestamosLibro.png" alt="estadoPrestamos" width="30" height="30">Estado Préstamos
            </a>

            <!-- Historial Prestamos -->
            <a class="btn btn-secondary btn-lg shadow-sm" href="historialPrestamos.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>" class="btn btn-light btn-lg shadow-sm">
                <img class="mx-2" src="img/historialLibros.png" alt="historialLibros" width="30" height="30">Ver Historial Préstamos
            </a>
        </div>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>

</html>