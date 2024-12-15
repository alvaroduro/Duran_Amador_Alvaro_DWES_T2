<!--Devolucion Libro-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ",idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}

//Consulta libros con prestamos del usuario
try {
    // Escribimos la consulta
    $sql = "SELECT 
    libros.IdEjemplar, 
    prestamos.IdProf, 
    prestamos.Fecha_Inicio, 
    prestamos.Fecha_Fin, 
    prestamos.Observaciones, 
    libros.ISBN, 
    libros.Titulo, 
    libros.Portada
FROM 
    libros
INNER JOIN 
    prestamos 
ON 
    libros.IdEjemplar = prestamos.IdEjemplar
WHERE 
    prestamos.IdProf = :idprof 
    AND libros.Estado = 1 
    AND prestamos.Fecha_Fin IS NULL;";

    // Preparamos la consulta
    $resultado = $conexion->prepare($sql);
    // Ejecutamos la consulta
    $resultado->execute(['idprof' =>  $idProf]);

    //Si hay datos en la consulta
    if ($resultado) {
        $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';
    } //o no
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!" . '<img class="mx-2" width="50" height="50" src="https://img.icons8.com/cute-clipart/64/error.png" alt="error"/></div>';
    //die();
}
?>

<body>
    <?php echo $msgresultado ?>
    <!--Tabla Resultados-->
    <table class="table table-striped">
        <thead>
            <tr>
                <!--<th>IdEjemplar</th>-->
                <th>IdEjemplar</th>
                <th>ISBN</th>
                <th>IdProf</th>
                <th>Titulo</th>
                <th>Portada</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Observaciones</th>
                <th colspan="1">Operaciones</th>
            </tr>
        </thead>
        <tbody>
            <!--Recogemos resultados-->
            <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <!--Mostramos los datos de la tabla Prestamos-------------->
                    <!--<td><?= $fila['IdPrestamo'] ?></td>-->
                    <td><?= $fila['IdEjemplar'] ?></td>
                    <td><?= $fila['ISBN'] ?></td>
                    <td><?= $fila['IdProf'] ?></td>
                    <td><?= $fila['Titulo'] ?></td>
                    <td><?php if ($fila['Portada'] != null) {
                            echo '<img src="img/' . $fila['Portada'] . '" alt="Portada cargada" width="70" height="80">';
                        } ?></td>
                    <td><?= //Convertimos la fecha al formato
                                $fechaFormateada = date('m/Y', strtotime($fila['Fecha_Inicio']));
                                ?></td>?></td>
                    <td><?= $fila['Fecha_Fin'] ?></td>
                    <td><?= $fila['Observaciones'] ?></td>
                <?php } ?>
                </tr>
        </tbody>
    </table>
</body>
<?php require 'includes/footer.php'; ?>
</body>
</html>