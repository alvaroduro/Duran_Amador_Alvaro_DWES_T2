<!-- Listar Usuarios-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'delUsuario.php'; ?>
<?php
$delnombre = "";
$delusuario = "";
$delemail = "";
$delid = "";
$fechaFormateada = "";
if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    if($rolUsuario == 1) {
        $nombre = "Ana Maria";
    }else {
        $nombre = $_GET['nombre']; //obtenemos el nombre si es usuario
    }
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ",idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}

try {
    // Escribimos la consulta
    $sql = "SELECT * FROM profesores";
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
    <!--Mostramos posibles errores y mensajes-->
    <?php echo $msgresultado ?>
    <?php echo $msgresultadoEliminar ?>

    <!--Principal-->
    <div class="container mt-5 justify-content-center">
        <!--Lista-->
        <div class="d-flex flex-row mb-3 justify-content-evenly">

            <!--Botón Atras-->
            <a class="navbar-brand mx-2" href="admin.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img class="mx-1" src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>

            <!--Título-->
            <h1 class="text-center">Listado de Usuarios</h1>

            <!--Agregar Usuario Nuevo-->
            <a class="navbar-brand mx-2" href="agregarUsuario.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>">Agregar Usuario Nuevo<img class="mx-2" width="40" height="40" src="img/agregarUsuario.png" alt="agregar user"></a>

            <!--Salir login-->
            <a class="navbar-brand mx-2" href="index.php">Salir<img class="mx-2" src="img/exit.png" alt="salir" width="40" height="40"></a>
        </div>


        <!--Tabla Resultados-->
        <table class="table table-striped">
            <thead>
                <tr>
                    <!--<th>IdProf</th>-->
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Nombre</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Usuario</th>
                    <th>Foto</th>
                    <th>Rol</th> <!--Estado-> 0=usuario, 1=admin-->
                    <th colspan="2">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <!--Recogemos resultados-->
                <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <!--<td><?= $fila['IdProf'] ?></td>-->
                        <?php $delid = $fila['IdProf'] ?>
                        <td><?= $fila['Apellido1'] ?></td>
                        <td><?= $fila['Apellido2'] ?></td>
                        <td><?= $fila['Nombre'] ?></td>
                        <?php $delnombre = $fila['Nombre'] ?>
                        <td><?= md5($fila['Password']) ?></td>
                        <td><?= $fila['Email'] ?> </td>
                        <?php $delemail = $fila['Email'] ?>
                        <td><?= $fila['Usuario'] ?> </td>
                        <?php $delusuario = $fila['Usuario'] ?>
                        <td><?php if ($fila['Foto'] != null) {
                                echo '<img src="img/' . $fila['Foto'] . '" alt="Foto cargada" width="70" height="80">';
                            } ?> </td>
                        <!--Modificamos el rol-->
                        <td><?php if (($fila['Rol']) == 0) {
                                echo "Usuario";
                            } else {
                                echo "Admin";
                            } ?></td>
                        <!--Damos el rol por si es admin y el idprof del usuario-->
                        <td> <a href="actUsuario.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $delid ?>&nombre=<?php echo $nombre; ?>"><img width="40" height="40" src="img/editarUsuario.png" alt="editar usuario"></a>Editar</td>
                        <td>
                            <!--Solo eliminamos los usuarios que no son admin, mostramos el botón eliminar-->
                            <?php if($fila['Rol'] == 0) {?>
                                <!--Boton eliminar-->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                data-id="<?php echo htmlspecialchars($delid) ?>"
                                data-nombre="<?php echo htmlspecialchars($delnombre); ?>"
                                data-email="<?php echo htmlspecialchars($delemail); ?>"
                                data-usuario="<?php echo htmlspecialchars($delusuario); ?>">
                                <img src="img/1734029819-eliminarUsuario.png" alt="eliminar libro" width="40" height="40">
                                Eliminar
                            </button>
                            <?php } ?>
                        </td>
                    <?php } ?>
                    </tr>
            </tbody>
        </table>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>