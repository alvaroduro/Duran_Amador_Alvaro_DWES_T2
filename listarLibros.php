<!-- Listar Libros para profesor o usuario-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php
if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo $rolUsuario . ", " . $nombre . ", " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}

try {
    // Escribimos la consulta
    $sql = "SELECT * FROM libros";
    // Preparamos la consulta
    $resultado = $conexion->prepare($sql);
    // Ejecutamos la consulta
    $resultado->execute();

    //Si hay datos en la consulta
    if ($resultado) {
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
            <!--Atras Definimos si es usuario o admin y mandamos parámetros-->
            <?php if ($rolUsuario == 0) { //Si es usuario
            ?>
                <a href="profesor.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>

            <?php } else { //Si es admin
            ?>
                <a href="admin.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"></a>
            <?php } ?>

            <!--Título-->
            <h1 class="text-center">Listado de Libros</h1>

            <!--Salir login-->
            <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
        </div>

        <!--Tabla Resultados-->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>IdEjemplar</th>
                    <th>ISBN</th>
                    <th>Título</th>
                    <th>Fecha Publicación</th>
                    <th>Editorial</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Portada</th>
                    <th>Estado</th> <!--Estado-> 0=libre, 1=prestado-->
                </tr>
            </thead>
            <tbody>
                <!--Recogemos resultados-->
                <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <!--Si está o no disponible lo mostramos y el rol es usuario-->
                        <?php if ($rolUsuario == 0 && $fila['Estado'] == 0) { ?>
                            <td><?= $fila['IdEjemplar'] ?></td>
                            <td><?= $fila['ISBN'] ?></td>
                            <td><?= $fila['Titulo'] ?></td>
                            <td><?= $fila['Fecha_Publicacion'] ?></td>
                            <td><?= $fila['Editorial'] ?></td>
                            <td><?= $fila['Descripcion'] ?></td>
                            <td><?= $fila['Precio'] ?></td>
                            <td><?= $fila['Portada'] ?></td>
                            <!--Modificamos el estado-->
                            <td><?php if (($fila['Estado']) == 0) {
                                    echo "Libre";
                                } else {
                                    echo "Prestado";
                                } ?></td>
                        <?php
                            //Si el rol es admin mostramos todos 
                        } elseif ($rolUsuario == 1) { ?>
                            <td><?= $fila['IdEjemplar'] ?></td>
                            <td><?= $fila['ISBN'] ?></td>
                            <td><?= $fila['Titulo'] ?></td>
                            <td><?= $fila['Fecha_Publicacion'] ?></td>
                            <td><?= $fila['Editorial'] ?></td>
                            <td><?= $fila['Descripcion'] ?></td>
                            <td><?= $fila['Precio'] ?></td>
                            <td><?= $fila['Portada'] ?></td>
                            <!--Modificamos el estado-->
                            <td><?php if (($fila['Estado']) == 0) {
                                    echo "Libre";
                                } else {
                                    echo "Prestado";
                                } ?></td>
                        <?php } ?>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>