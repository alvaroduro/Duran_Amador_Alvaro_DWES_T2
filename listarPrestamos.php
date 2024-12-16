<!--Listar los préstamos de cada Usuario-->
<!-- Listar Libros para profesor o usuario-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php
if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ",idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}


//Si es admin mostramos todos
try {
    // Escribimos la consulta para ver los prestamos del usuario
    $sql = "SELECT 
        prestamos.IdEjemplar, prestamos.ISBN, prestamos.IdProf, prestamos.Fecha_Inicio, prestamos.Observaciones, libros.Titulo, libros.Portada, profesores.Usuario, Fecha_Fin 
        FROM prestamos 
        INNER JOIN libros 
        ON prestamos.IdEjemplar = libros.IdEjemplar 
        INNER JOIN profesores
        ON profesores.IdProf = prestamos.IdProf
        AND prestamos.ISBN = libros.ISBN";

    // Preparamos la consulta
    $resultado = $conexion->prepare($sql);
    // Ejecutamos la consulta
    $resultado->execute();

    //Si hay datos en la consulta
    if ($resultado) {
        // Registramos en la tabla logs visualizacion prestamos abiertos 
        registrarActividad($conexion, "visualizacion", "usuario " . $nombre . " visualizacion prestamos activos");
        $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';
    } //o no
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!" . '<img class="mx-2" width="50" height="50" src="https://img.icons8.com/cute-clipart/64/error.png" alt="error"/></div>';
    die();
}


?>

<!--Código HTML-->

<body>
    <?php echo $msgresultado ?>

    <div class="container mt-5 justify-content-center">
        <div class="d-flex flex-row mb-3 justify-content-evenly">
            <!--Definimos si el rol es usuario o admin-->

            <!--Usuario-->
            <?php if ($rolUsuario == 0) { ?>
                <!--Botón Atras-->
                <a href="profesor.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
                <!--Título-->
                <h1 class="text-center">Listado Prestamos Abiertos</h1>
            <!--Admin-->
            <?php } else { ?>
                <!--Botón Atras-->
                <a href="admin.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
                <!--Título-->
                <h1 class="text-center">Listado Prestamos</h1>
            <?php } ?>

            <!--Salir login-->
            <a class="navbar-brand mx-2" href="index.php">Salir<img class="mx-2" src="img/exit.png" alt="salir" width="40" height="40"></a>
        </div>


        <!--Tabla Resultados-->
        <table class="table table-striped">
            <thead>
                <tr>
                    <!--<th>IdEjemplar</th>-->
                    <?php if ($rolUsuario == 0) { ?>
                        <th>ISBN</th>
                        <th>Titulo</th>
                        <th>Portada</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Observaciones</th>
                    <?php } else { ?>
                        <th>IdEjemplar</th>
                        <th>ISBN</th>
                        <th>IdProf</th>
                        <th>Usuario</th>
                        <th>Titulo</th>
                        <th>Portada</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Observaciones</th>
                    <?php } ?>

                </tr>
            </thead>
            <tbody>
                <!--Recogemos resultados-->
                <tr>
                    <!--USUARIO-->
                    <?php
                    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                        if ($rolUsuario == 0 && is_null($fila['Fecha_Fin']) && $fila['IdProf'] == $idProf) { ?>
                            <td><?= $fila['ISBN'] ?></td>
                            <td><?= $fila['Titulo'] ?></td>
                            <td><?php if ($fila['Portada'] != null) {
                                    echo '<img src="img/' . $fila['Portada'] . '" alt="Portada cargada" width="70" height="80">';
                                } ?></td>
                            <td><?= $fila['Fecha_Inicio'] ?></td>
                            <td><?= $fila['Fecha_Fin'] ?></td>
                            <td><?= $fila['Observaciones'] ?></td>
                        <?php } elseif ($rolUsuario == 1) { ?>
                            <!--ADMIN-->
                            <!--<td><?= $fila['IdPrestamo'] ?></td>-->
                            <td><?= $fila['IdEjemplar'] ?></td>
                            <td><?= $fila['ISBN'] ?></td>
                            <td><?= $fila['IdProf'] ?>
                            <td><?= $fila['Usuario'] ?></td>
                            <td><?= $fila['Titulo'] ?></td>
                            <td><?php if ($fila['Portada'] != null) {
                                    echo '<img src="img/' . $fila['Portada'] . '" alt="Portada cargada" width="70" height="80">';
                                } ?></td>
                            <td><?= $fila['Fecha_Inicio'] ?></td>
                            <td><?= $fila['Fecha_Fin'] ?></td>
                            <td><?= $fila['Observaciones'] ?></td>
                        <?php } ?>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>
</html>