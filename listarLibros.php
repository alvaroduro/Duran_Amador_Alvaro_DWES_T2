<!-- Listar Libros para profesor o usuario-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'delLibro.php'; ?>
<?php $msgresultado = "" ?>
<?php $recargarPagina = "" ?>
<?php
$deltitulo = "";
$deleditorial = "";
$delisbn = "";
$delidejemplar = "";
$fechaFormateada = "";
if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ",idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}

try {
    // Escribimos la consulta
    $sql = "SELECT 
                libros.*,
    prestamos.IdEjemplar, 
    prestamos.IdProf, 
    prestamos.Fecha_Fin, 
    profesores.*
FROM 
    libros
INNER JOIN 
    prestamos 
ON 
    libros.IdEjemplar = prestamos.IdEjemplar
INNER JOIN 
    profesores 
ON 
    prestamos.IdProf = profesores.IdProf";

    // Preparamos la consulta
    $resultado = $conexion->prepare($sql);
    // Ejecutamos la consulta
    $resultado->execute();

    //Si hay datos en la consulta
    if ($resultado) {
        $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';

        // Cogemos el IdProf de la tabla prestamos y la Fecha_Fin

    } //o no
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!" . '<img class="mx-2" width="50" height="50" src="https://img.icons8.com/cute-clipart/64/error.png" alt="error"/></div>';
    die();
}
?>

<!--Código HTML-->

<body>
    <?php echo ($msgresultado) ?>

    <div class="container mt-5 justify-content-center">
        <div class="d-flex flex-row mb-3 justify-content-evenly">

            <!--Definimos si el rol es usuario o admin-->
            <!-----------------------USUARIO------------------------------------------------------->
            <?php if ($rolUsuario == 0) { ?>
                <!--Botón Atras usuario-->
                <a href="profesor.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img class="mx-auto" src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
                <!--Título-->
                <h1 class="text-center">Listado de Libros</h1>

                <!-----------------------ADMIN------------------------------------------------------->
            <?php } else {
            ?>
                <!--Botón Atras admin-->
                <a class="navbar-brand mx-2" href="admin.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img class="mx-auto" src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>

                <!--Título-->
                <h1 class="text-center">Listado de Libros</h1>

                <!--Agregar Libros-->
                <a class="navbar-brand mx-2" href="agregarLibro.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>">Agregar Libro<img class="mx-2" width="40" height="40" src="img/agregarLibro.png" alt="agregar libro"></a>

            <?php } ?>

            <!--Salir login-->
            <a class="navbar-brand mx-2" href="index.php">Salir<img class="mx-2" src="img/exit.png" alt="salir" width="40" height="40"></a>
        </div>


        <!--Tabla Resultados-->
        <table class="table table-striped">
            <thead>
                <tr>
                    <!--<th>IdEjemplar</th>-->
                    <th>ISBN</th>
                    <th>Título</th>
                    <th>Fecha Publicación</th>
                    <th>Editorial</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Portada</th>
                    <th>Estado</th> <!--Estado-> 0=libre, 1=prestado-->
                    <th class="text-center" colspan="3">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <!--Variables para recorrer los idejemplar e isbn(antiguos y nuevos)-->
                <?php $ideantiguo = ""; ?>
                <?php $idenuevo = ""; ?>
                <?php $isbneantiguo = ""; ?>
                <?php $isbnenuevo = ""; ?>

                <!--Recogemos resultados Recorremos Array-->
                <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $ideantiguo = $fila['IdEjemplar'];
                    $isbneantiguo = $fila['ISBN'];
                    //var_dump($fila)
                ?>
                    <tr>
                        <!----------------------------------USUARIO------------------------------->
                        <?php if ($rolUsuario == 0) { ?>
                            <!--Mostramos los libres o prestados-->
                            <?php if ($fila['Fecha_Fin'] == null || $fila['Estado'] == 0 && ($ideantiguo != $idenuevo) && ($isbneantiguo != $isbnenuevo)) { ?>
                                <!--<td><?= $fila['IdEjemplar'] ?></td>-->
                                <?php $delidejemplar = $fila['IdEjemplar'] ?>
                                <td><?= $fila['ISBN'] ?></td>
                                <?php $delisbn = $fila['ISBN'] ?>
                                <td><?= $fila['Titulo'] ?></td>
                                <?php $deltitulo = $fila['Titulo'] ?>
                                <td><?= //Convertimos la fecha al formato
                                    $fechaFormateada = date('m/Y', strtotime($fila['Fecha_Publicacion']));
                                    ?></td>
                                <td><?= $fila['Editorial'] ?></td>
                                <td><?= $fila['Descripcion'] ?></td>
                                <td><?= $fila['Precio'] . "€" ?></td>
                                <td><?php if ($fila['Portada'] != null) {
                                        echo '<img src="img/' . $fila['Portada'] . '" alt="Portada cargada" width="70" height="80">';
                                    } ?> </td>

                                <!--Modificamos el estado-->
                                <td><?php if (($fila['Estado']) == 0) {
                                        echo "Libre";
                                    } else {
                                        echo "Prestado";
                                    } ?></td>

                                <!--Boton Solicitar Prestamo-->
                                <!-----En caso de estar libre el libro Solicitar Prestamo----->
                                <?php if (($fila['Estado']) == 0) { ?>
                                    <td><a class="navbar-brand d-block mx-auto text-center" href="solicitarPrestamo.php?rol=<?php echo $rolUsuario; ?>&idEje=<?php echo $delidejemplar ?>&nombre=<?php echo $nombre; ?>&idProf=<?php echo $idProf; ?>&isbn=<?php echo $delisbn; ?>&titulo=<?php echo $deltitulo; ?>"><img class="d-block mx-auto" width="40" height="40" src="img/prestamoLibro.png" alt="solicitar prestamo"> Solicitar Préstamo</a></td>

                                    <!--Si el libro esta PRESTADO y es del USUARIO-->
                                <?php } elseif (($fila['Estado']) == 1 && $fila['Fecha_Fin'] == null && $fila['IdProf'] === $idProf) { ?>
                                    <!-- Botón para Fin Préstamo-->
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDevolucion"
                                            data-id="<?php echo $delidejemplar; ?>"
                                            data-isbn="<?php echo $delisbn; ?>"
                                            data-titulo="<?php echo $deltitulo; ?>">
                                            <img class="d-block mx-auto" width="40" height="40" src="img/devolverPrestamo.png" alt="Devolver Libro">Fin Préstamo
                                        </button>
                                    </td>

                                    <!--Boton Solicitar Reserva-->
                                    <!-----Si el libro esta PRESTADO y NO es del USUARIO----->
                                <?php } else { ?>
                                    <td><a class="navbar-brand d-block mx-auto text-center" href="solicitarPrestamo.php?rol=<?php echo $rolUsuario; ?>&idEje=<?php echo $delidejemplar ?>&nombre=<?php echo $nombre; ?>&idProf=<?php echo $idProf; ?>&isbn=<?php echo $delisbn; ?>&titulo=<?php echo $deltitulo; ?>"><img class="d-block mx-auto" width="40" height="40" src="img/reservaLibro.png" alt="solicitar prestamo">Solicitar Reserva</a></td>
                                <?php } ?>
                            <?php } ?>
                        <?php
                            //-------------------------------ADMIN--------------------------------------->
                        } elseif ($rolUsuario == 1) { ?>
                            <!--Mostramos los libros que no coincidan y esten LIBRES o PRESTADOS-->
                            <?php if (($fila['Estado'] == 0 || $fila['Estado'] == 1) && ($ideantiguo != $idenuevo) && ($isbneantiguo != $isbnenuevo)) { ?>
                                <td><?= $fila['IdEjemplar'] ?></td>
                                <?php $delidejemplar = $fila['IdEjemplar']  ?>
                                <?php $delisbn = $fila['ISBN'] ?>
                                <td><?= $fila['Titulo'] ?></td>
                                <?php $deltitulo = $fila['Titulo'] ?>
                                <td><?=
                                    $fechaFormateada = date('m/Y', strtotime($fila['Fecha_Publicacion'])); //Convertimos la fecha al formato
                                    ?></td>
                                <td><?= $fila['Editorial'] ?></td>
                                <?php $deleditorial = $fila['Editorial'] ?>
                                <td><?= $fila['Descripcion'] ?></td>
                                <td><?= $fila['Precio'] . "€" ?> </td>
                                <td><?php if ($fila['Portada'] != null) {
                                        echo '<img src="img/' . $fila['Portada'] . '" alt="Portada cargada" width="70" height="80">';
                                    } ?> </td>
                                <!--Modificamos el estado-->
                                <td><?php if (($fila['Estado']) == 0) {
                                        echo "Libre";
                                    } else {
                                        echo "Prestado";
                                    } ?></td>

                                <!--Boton editar-->
                                <td>Editar<a class="navbar-brand d-block" href="actLibro.php?rol=<?php echo $rolUsuario; ?>&idEje=<?php echo $delidejemplar ?>&nombre=<?php echo $nombre; ?>&idProf=<?php echo $idProf; ?>&isbn=<?php echo $delisbn; ?>&titulo=<?php echo $deltitulo; ?>"><img class="mx-auto" width="50" height="50" src="img/editarLibro.png" alt="editarLibro"></a></td>

                                <!--Boton eliminar-->
                                <td> <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                        data-id="<?php echo htmlspecialchars($delidejemplar) ?>"
                                        data-titulo="<?php echo htmlspecialchars($deltitulo); ?>"
                                        data-editorial="<?php echo htmlspecialchars($deleditorial); ?>"
                                        data-isbn="<?php echo htmlspecialchars($delisbn); ?>">
                                        <img class="mx-auto" src="img/eliminarLibro.png" alt="eliminar usuario" width="40" height="40">
                                        Eliminar
                                    </button></td>


                                <!--En caso de estar libre el libro Solicitar Prestamo-->
                                <?php if (($fila['Estado']) == 0) { ?>
                                    <td>Solicitar<a class="navbar-brand d-block" href="solicitarPrestamo.php?rol=<?php echo $rolUsuario; ?>&idEje=<?php echo $delidejemplar ?>&nombre=<?php echo $nombre; ?>&idProf=<?php echo $idProf; ?>&isbn=<?php echo $delisbn; ?>&titulo=<?php echo $deltitulo; ?>"><img class="d-block mx-auto" width="40" height="40" src="img/prestamoLibro.png" alt="solicitar prestamo">Préstamo</a></td>
                                <?php } else { ?>

                                    <!-- Botón para Fin Préstamo-->
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDevolucion"
                                            data-id="<?php echo $delidejemplar; ?>"
                                            data-isbn="<?php echo $delisbn; ?>"
                                            data-titulo="<?php echo $deltitulo; ?>">
                                            <img class="d-block mx-auto" width="40" height="40" src="img/devolverPrestamo.png" alt="Devolver Libro">Fin Préstamo
                                        </button>
                                    </td>
                                    <!--Incluimos el modal para fin del prestamo-->

                                <?php } ?>
                            <?php } ?>
                    </tr>
                <?php } ?>
            <?php //Vamos almacenando para no mostrar registros duplicados
                    $idenuevo =  $fila['IdEjemplar'];
                    $isbnenuevo =  $fila['ISBN'];
                } ?>
            <?php include 'finPrestamo.php'; ?>
            </tbody>
        </table>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>

</html>