<!--Pantalla principal Porfesores-->
<?php require 'includes/header.php'; ?>
<?php
if (isset($_GET['rol']) && isset($_GET['nombre'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo $rolUsuario . ", " . $nombre . ", " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}
?>

<body>
    <!-- Contenedor principal -->
    <div class="container text-center my-5">

        <!--Ponemos el nombre del profesor-->
        <p>Bienvenido, <b><?php echo $nombre?></b></p>

        <!-- Grupo de enlaces -->
        <div class="d-grid gap-3 mx-auto col-6">
            <!-- Título -->
            <div class="d-flex flex-row mb-3 justify-content-evenly">
                <h1 class="text-center">Elija Operación a Realizar</h1>
                <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
            </div>
            <!-- Listar libros disponibles pasando el rol, idProf y nombre-->
            <a href="listarLibros.php?rol=<?php echo $rolUsuario;?>&idProf=<?php echo $idProf;?>&nombre=<?php echo $nombre;?>" class="btn btn-primary btn-lg shadow-sm">
                <img class="mx-2" src="img/listarLibros.png" alt="listarLibro" width="30" height="30">Listar Libros Disponibles
            </a>

            <!-- resgistrar Prestamos libro -->
            <a href="registrarPrestamo.html" class="btn btn-success btn-lg shadow-sm">
                <img class="mx-2" src="img/registroLibro.png" alt="registroLibro" width="30" height="30">Registrar Préstamo
            </a>

            <!-- Devolver Libro -->
            <a href="devolverLibro.html" class="btn btn-warning btn-lg shadow-sm">
                <img class="mx-2" src="img/devolverLibro.png" alt="devolverLibro" width="30" height="30">Devolver Libro
            </a>

            <!-- Estado préstamos -->
            <a href="devolverLibro.html" class="btn btn-light btn-lg shadow-sm">
                <img class="mx-2" src="img/prestamosLibro.png" alt="estadoPrestamos" width="30" height="30">Estado Préstamos
            </a>

            <!-- Historial Libros -->
            <a href="devolverLibro.html" class="btn btn-secondary btn-lg shadow-sm">
                <img class="mx-2" src="img/historialLibros.png" alt="historialLibros" width="30" height="30">Ver Historial Préstamos
            </a>
        </div>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php require 'includes/footer.php'; ?>