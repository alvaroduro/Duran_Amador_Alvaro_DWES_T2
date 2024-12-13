<!--Agregar Libro-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'valAgregarLibro.php'; ?>
<?php require 'verificarCampo.php'; ?>
<?php if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ", idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}

?>

<!--Validar Formulario Agregar Libro-->
<div class="d-flex flex-row mb-3 justify-content-evenly">
    <!--Botón Atras-->
    <a href="listarLibros.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
    <h1>Agregar Libro</h1>
    <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
</div>
<!-- Modal para agregar libro -->
<div class="container agregarLibro">
    <!--Mostramos los posibles errores en los campos-->
    <?php echo validez($errores);

    //Definimos la variable a null ya que todavia no se ha cargado imagen
    $imagen = null; 

    //Si no hay errores imprimimos los valores almacenados
    if (isset($_POST["anadirLibro"]) && (count($errores) == 0)) {

        //Comprobamos que no exista el título
        $titulo = $_POST['titulo'];

        //Si el título ya existe
        if (verificarCampo($conexion, 'Titulo', 'libros', $titulo)) {
            $msgresultado = '<div class="alert alert-danger">' .
                "El título del libro ya existe!! :)" . '</div>';
        } else {
            // Guardamos los datos para la insercción en la Base de Datos
            $isbn = $_POST['isbn'];
            $fechaPubli = $_POST['fechaPubli'];
            $editorial = $_POST['editorial'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $autor = $_POST['autor'];
            $estado = 0;
            //Tratamos la imagen -Definimos su variable a null
            //En caso de almacenar la img en la BD
            $imagen = NULL;

            //Comprobamos que el campo tmp_name tiene una valor asignado
            //Y que hemos recibido la img correctamente
            if (isset($_FILES['portada']) && (!empty($_FILES['portada']['tmp_name']))) {
                //Comprobamos si existe el directorio img(si no, lo creamos)
                if (!is_dir("img")) {
                    $imgDire = "directorio mal";
                    $dir = mkdir("img", 0777, true);
                } else { //Si no, ponemos directorio a true
                    $dir = true;
                    $imgDire = "directorio bien";
                }

                //Verificamos que la carpeta de img existe y movemos el fichero a ella
                if ($dir) {
                    //Aseguramos nombre único
                    $nombreImg = time() . "-" . $_FILES['portada']['name'];

                    //Movemos el archivo a nuestra carpeta
                    $moverImg = move_uploaded_file($_FILES['portada']['tmp_name'], "img/" . $nombreImg);

                    // Definimos el nombre (ruta) de la imagen
                    $imagen = $nombreImg;

                    //Verificamos la carga si se ha realizado correctamente
                    if ($moverImg) { //En caso de que se haya movido bien
                        $imagenCargada = true;
                        $portada = "La portada nos ha llegado<br/>";
                    } else {
                        $imagenCargada = false;
                        $errores["portada"] = "Error al cargar la imagen";
                    }
                }
            } else {
                $errores["portada"] = "Error en portada, imagen vacía o no recibida";
            }

            // Mostramos una ventana modal con los datos del libro introducido al clicar un botón
            require 'modal/modalAgregarLibro.php';

            //Si no hay errores insertamos el libro en la Base de Datos
            try { // Definimos la consulta
                $sql = "INSERT INTO libros(ISBN,Titulo,Fecha_Publicacion,Editorial,Descripcion,Precio,Portada,Autor,Estado) VALUES (:ISBN,:Titulo,:Fecha_Publicacion,:Editorial,:Descripcion,:Precio,:Portada,:Autor,:Estado)";

                //Preparamos
                $query = $conexion->prepare($sql);

                //Ejecutamos con los valores obtenidos
                $query->execute([
                    'ISBN' => $isbn,
                    'Titulo' => $titulo,
                    'Fecha_Publicacion' => $fechaPubli,
                    'Editorial' => $editorial,
                    'Descripcion' => $descripcion,
                    'Precio' => $precio,
                    'Portada' => $imagen,
                    'Autor' => $autor,
                    'Estado' => $estado
                ]);

                // Supervisamos si se ha realizado correctamente
                if ($query) {
                    $msgresultado = '<div class="alert alert-success">' .
                        "El Libro se registró correctamente en la Base de Datos!! :)" . '</div>';
                } else {
                    $msgresultado = '<div class="alert alert-danger">' .
                        "Datos de registro del libro erróneos!! :( (" . $ex->getMessage() . ')</div>';
                    //die();   
                }
            } catch (PDOException $ex) {
                $msgresultado = '<div class="alert alert-danger">' .
                    "El Libro no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
            }
        }
    }

    ?>
    <?php echo $msgresultado ?>
    <!--Formulario Agregar Libro-->
    <form action="" method="POST" enctype="multipart/form-data">

        <!--Campos-->
        <!--ISBN-->
        <div class="form-group mb-3">
            <label for="isbn" class="form-label">ISBN</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="isbn" id="isbn" class="form-control"
                <?php if (isset($_POST["isbn"])) {
                    echo "value='{$_POST["isbn"]}'";
                } ?>>
            <?php echo  mostrar_error($errores, "isbn"); ?>
        </div>

        <!--Título-->
        <div class="form-group mb-3">
            <label for="titulo" class="form-label">Título</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="titulo" id="titulo" class="form-control"
                <?php if (isset($_POST["titulo"])) {
                    echo "value='{$_POST["titulo"]}'";
                } ?>>
            <?php echo  mostrar_error($errores, "titulo"); ?>
        </div>

        <!--Fecha Publicación-->
        <div class="form-group mb-3">
            <label for="fechaPubli" class="form-label">Fecha Publicación</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="date" name="fechaPubli" id="fechaPubli" class="form-control"
                <?php if (isset($_POST["fechaPubli"])) {
                    echo "value='{$_POST["fechaPubli"]}'";
                } ?>>

            <?php echo  mostrar_error($errores, "fechaPubli"); ?>
        </div>

        <!--Editorial-->
        <div class=" form-group mb-3">
            <label for="editorial" class="form-label">Editorial</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="editorial" id="editorial" class="form-control"
                <?php if (isset($_POST["editorial"])) {
                    echo "value='{$_POST["editorial"]}'";
                } ?>>

            <?php echo  mostrar_error($errores, "editorial"); ?>
        </div>

        <!--Descripción-->
        <div class="form-group mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion"> <?php if (isset($_POST["descripcion"])) {
                                                                                    echo $_POST["descripcion"];
                                                                                } ?> </textarea>
            <?php echo  mostrar_error($errores, "descripcion"); ?>
        </div>
        <!-- Inicializar CKEditor -->
        <script>
            CKEDITOR.replace('descripcion');
        </script>

        <!--Precio-->
        <div class="form-group mb-3">
            <label for="precio" class="form-label">Precio</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="number" step="0.01" name="precio" id="precio" class="form-control"
                <?php if (isset($_POST["precio"])) {
                    echo "value='{$_POST["precio"]}'";
                } ?>>

            <?php echo  mostrar_error($errores, "precio"); ?>
        </div>

        <!--Portada-->
        <div class="form-group mb-3">
            <label for="portada" class="form-label">Portada</label>
            <input type="file" name="portada" class="form-control">
            <!--Si la imagen no es null mostramos la ultima imagen cargada-->
            <?php if ($imagen != null) {
                echo '<div class="mt-2">';
                echo '<label>Última portada cargada:</label><br>';
                echo '<img src="img/' . $imagen . '" alt="Portada cargada" width="70" height="80">';
                echo '</div>';
            } ?>
            <?php echo  mostrar_error($errores, "portada"); ?>

        </div>

        <!------Autor------>
        <div class="form-group mb-3">
            <label for="autor" class="form-label">Autor</label><br>
            <div>
                <select class="form-controlt form-select-sm" name="autor" required>
                    <option value="" selected disabled>Elige un autor...</option>
                    <!--Gabriel García Márquez-->
                    <option value="Gabriel García Márquez" <?php if (isset($_POST["autor"])) {
                                                                if ($_POST["autor"] == "Gabriel García Márquez") {
                                                                    echo "selected='selected'";
                                                                }
                                                            } ?>>Gabriel García Márquez</option>
                    <!--Isabel Allende-->
                    <option value="Isabel Allende" <?php if (isset($_POST["autor"])) {
                                                        if ($_POST["autor"] == "Isabel Allende") {
                                                            echo "selected='selected'";
                                                        }
                                                    } ?>>Isabel Allende</option>
                    <!--Jorge Luis Borges-->
                    <option value="Jorge Luis Borges" <?php if (isset($_POST["autor"])) {
                                                            if ($_POST["autor"] == "Jorge Luis Borges") {
                                                                echo "selected='selected'";
                                                            }
                                                        } ?>>Jorge Luis Borges</option>
                    <!--Jorge Luis Borges</option-->
                    <option value="Miguel de Cervantes" <?php if (isset($_POST["autor"])) {
                                                            if ($_POST["autor"] == "Miguel de Cervantes") {
                                                                echo "selected='selected'";
                                                            }
                                                        } ?>>Miguel de Cervantes</option>
                    <!--DESCONOCIDO-->
                    <option value="Desconocido" <?php if (isset($_POST["autor"])) {
                                                    if ($_POST["autor"] == "Desconocido") {
                                                        echo "selected='selected'";
                                                    }
                                                } ?>>Desconocido</option>
                </select>
            </div>
            <?php echo  mostrar_error($errores, "autor"); ?>
        </div>

        <!--Btn Añadir Libro-->
        <button onclick="return confirmacion()" type="submit" name="anadirLibro" class="btn btn-primary">Añadir Libro</button>

        <!--Campo oculto para mensaje de confirmación-->
        <input type="hidden" name="bien" id="bienInput" value="false">
    </form>
    <!--// Creamos una función para mensaje de confirmacion con JS-->
    <script>
        function confirmacion() {

            // Enlazamos con el DOOM de JS
            const bienInput = document.getElementById('bienInput');
            const confirmacion = confirm('¿Estás seguro de que deseas añadir el libro?');

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