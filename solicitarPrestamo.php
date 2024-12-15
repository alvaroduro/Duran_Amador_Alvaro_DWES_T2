<!--Solicitar un Prestamo-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    $isbn = $_GET['isbn'];
    $titulo = $_GET['titulo'];
    $idejemplar = $_GET['idEje'];
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ", idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}
$errores = [];
$msgresultado = "";
$msgresultadoPres = "";

// Función que muestra el mensaje de error bajo el campo que no ha superado
// el proceso de validación
function mostrar_error($errores, $campo)
{
    $alert = "";
    if (isset($errores[$campo]) && (!empty($campo))) {
        $alert = '<div class="alert alert-danger" style="margin-top:5px;">' . $errores[$campo] . '</div>';
    }
    return $alert;
}

// Verificamos si todos los campos han sido validados
function validez($errores)
{
    //En caso de no haber errores
    if (isset($_POST["actualizar"]) && (count($errores) == 0)) {
        return '<div class="alert alert-success" style="margin-top:5px;"> Procedemos a actualizar el Libro en la Base de Datos</div>';
    }
}

// Comprobamos el campo Observaciones
if (isset($_POST["registrarPrestamo"])) {
    //Campo Descripcion
    if (
        !empty($_POST["observaciones"])
    ) {
        echo "no esta vacio";
        //Satinizamos
        $observaciones = $_POST["observaciones"];
        $observaciones = trim($observaciones); // Eliminamos espacios en blanco
        $observaciones = htmlspecialchars($observaciones); //Caracteres especiales a HTML
        $observaciones = stripslashes($observaciones); //Elimina barras invertidas
        //echo  "Descripción: <b>" . $descripcion . "</b><br/>";
    } else {
        echo "esta vacio";
        $errores["observaciones"] = "No puede estar vacío";
    }
}

//Si está todo correcto 
if (isset($_POST["registrarPrestamo"]) && (count($errores) == 0)) {

    //Guardamos el campo observaciones
    $observaciones = $_POST['observaciones'];
    $fechaActual = $_POST['fechaini'];
    $fechaActual = date('Y-m-d'); //Formateamos la fecha para la base de datos
    $IdProf = $_POST['idprof'];
    $isbn = $_POST['isbn'];

    // Cogemos los campos que traemos por defecto y efectuamos la consulta
    //Si no hay errores insertamos el libro en la Base de Datos
    try { // Definimos la consulta
        $sql = "INSERT INTO prestamos (IdEjemplar,ISBN,IdProf,Fecha_Inicio,Observaciones) VALUES (:IdEjemplar,:ISBN,:IdProf,:Fecha_Inicio,:Observaciones)";

        //Preparamos
        $query = $conexion->prepare($sql);

        //Ejecutamos con los valores obtenidos
        $query->execute([
            'IdEjemplar' => $idejemplar,
            'ISBN' => $isbn,
            'IdProf' => $IdProf,
            'Fecha_Inicio' => $fechaActual,
            'Observaciones' => $observaciones
        ]);

        // Supervisamos si se ha realizado correctamente
        if ($query) {
            $msgresultadoPres = '<div class="alert alert-success">' .
                "El Préstamo del libro se registró correctamente en la Base de Datos!! :)" . '</div>';

            //Procedemos a cambiar el estado (prestado) de la tabla libros
            $sql = "UPDATE libros SET Estado = 1 WHERE IdEjemplar = :IdEjemplar OR ISBN = :ISBN";

            //Preparamos
            $query = $conexion->prepare($sql);

            //Ejecutamos con los valores obtenidos
            $query->execute([
                'IdEjemplar' => $idejemplar,
                'ISBN' => $isbn
            ]);

            if ($query) {
                $msgresultado = '<div class="alert alert-success">' .
                    "El Estado del libro se registró correctamente en la Base de Datos!! :)" . '</div>';
            }else {
                $msgresultado = '<div class="alert alert-danger">' .
                "Datos del estado del libro erróneos!! :( (" . $ex->getMessage() . ')</div>';
            //die(); 
            }

        } else {
            $msgresultado = '<div class="alert alert-danger">' .
                "Datos de préstamo del libro erróneos!! :( (" . $ex->getMessage() . ')</div>';
            //die();   
        }
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">' .
            "El préstamo no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
    }
}

?>

<!--Registrar Prestamos-->
<div class="container mt-5 justify-content-center">
    <div class="d-flex flex-row mb-3 justify-content-evenly">
        <!--Definimos si el rol es usuario o admin-->

        <!--Atras Definimos si es usuario o admin y mandamos parámetros-->
        <?php if ($rolUsuario == 0) { //Si es usuario
        ?>
            <!--Botón Atras-->
            <a
                href="listarLibros.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img
                    src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
            <!--Título-->
            <h1 class="text-center">Solicitud Préstamo Libro</h1>

        <?php } else { //Si es admin
        ?>
            <!--Botón Atras-->
            <a class="navbar-brand mx-2"
                href="listarLibros.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>&idEje=<?php echo $idejemplar; ?>"><img
                    class="mx-1" src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
                    
            <!--Título-->
            <h1 class="text-center">Solicitud Préstamo</h1>

        <?php } ?>

        <!--Salir login-->
        <a class="navbar-brand mx-2" href="index.php">Salir<img class="mx-2" src="img/exit.png" alt="salir" width="40"
                height="40"></a>
    </div>

    <!--Mostramos el titulo del libro a solicitar prestamo-->
    <h2 class="text-center">Libro-> <i><?php echo $titulo ?></i></h2>
    <div class="container agregarLibro">

        <!--Mostramos mensaje de posible erorres-->
        <?php echo $msgresultado ?>
        <?php echo $msgresultadoPres ?>


        <form action="" method="POST" enctype="multipart/form-data">

            <!--Campos-->
            <!--ISBN-->
            <div class="form-group mb-3">
                <label for="isbn" class="form-label">ISBN</label>

                <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
                <input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo $isbn ?>" readonly>
            </div>

            <!--IdEjemplar-->
            <div class="form-group mb-3">
                <label for="idejemplar" class="form-label">IdEjemplar</label>

                <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
                <input type="text" name="idejemplar" id="idejemplar" class="form-control"
                    value="<?php echo $idejemplar ?>" readonly>
            </div>

            <!--IdProf-->
            <div class="form-group mb-3">
                <label for="idprof" class="form-label">IdProf</label>

                <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
                <input type="text" name="idprof" id="idprof" class="form-control" value="<?php echo $idProf ?>"
                    readonly>
            </div>
            <input type="hidden" name="fechaini" id="fechaini" class="form-control" value="<?php $fechaActual = date('d/m/Y');
                                                                                            echo $fechaActual; ?>">

            <!--Observaciones-->
            <div class="form-group mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" id="observaciones"></textarea>
                <?php echo  mostrar_error($errores, "observaciones"); ?>
            </div>

            <!--Btn Añadir Libro-->
            <button onclick="return confirmacion()" type="submit" name="registrarPrestamo"
                class="btn btn-primary">Registrar Préstamo</button>

            <!--Campo oculto para mensaje de confirmación-->
            <input type="hidden" name="bien" id="bienInput" value="false">
        </form>
    </div>
    <!--// Creamos una función para mensaje de confirmacion con JS-->
    <script>
        function confirmacion() {

            // Enlazamos con el DOOM de JS
            const bienInput = document.getElementById('bienInput');

            // Pasamos el valor de PHP a una variable de JavaScript
            const titulo = "<?php echo $titulo; ?>";
            const confirmacion = confirm('¿Estás seguro de que deseas solicitar el préstamo del libro ' + titulo + '?');

            // Definimos la respuesta de confirmación
            if (confirmacion) {
                bienInput.value = 'true';
            } else {
                bienInput.value = 'false';
            }

            return confirmacion; // Solo envía el formulario si el usuario confirma.
        }
    </script>
</div>
<?php require 'includes/footer.php'; ?>
</body>

</html>