<!--Actualizar datos del Usuario-->
<!--Agregar Usuario-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'valActualizarLibro.php'; ?>
<?php require 'verificarAtualizarCampo.php'; ?>
<?php if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre si es usuario
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    $idEjemplar = $_GET['idEje']; //Obtenemos el idejemplar
    $isbn = $_GET['isbn']; //Obtenemos el isbn
    $titulo = $_GET['titulo']; //Obtenemos el titulo
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ", idprof (para actualizar)= " . $idProf . ", IdEjemplar = " . $idEjemplar.", isbn = " . $isbn.", titulo=".$titulo;
} else {
    echo "No se recibió ningún rol.";
}
$msgresultado = "";
$msgresultadoIsbn = "";
$msgresultadoTitulo = "";

//Variables actualizar
$valisbn = "";
$valtitulo = "";
$valfechapubli = "";
$valeditorial = "";
$valdescripcion = "";
$valprecio = "";
$valportada = "";
$valautor = "";
$valestado = "";

//-----------------------------Si se pulsa en actualizar---------------------------------------------
if (isset($_POST["actualizar"]) && (count($errores) == 0)) {
    //Si el título ya existe
    $titulo = $_POST['titulo'];
    $isbn = $_POST['isbn'];

    if (verificarCampoLibro($conexion, 'ISBN', 'libros', $isbn, $idEjemplar)) {
        $msgresultadoIsbn = '<div class="alert alert-danger">' .
            "El ISBN ya existe!! :)" . '</div>';
    }

    if (verificarCampoLibro($conexion, 'Titulo', 'libros', $titulo, $idEjemplar)) {
        $msgresultadoTitulo = '<div class="alert alert-danger">' .
            "El título del Libro ya existe!! :)" . '</div>';
    }

    // Si el email y el nombre usuario no existen
    if (
        !verificarCampoLibro($conexion, 'ISBN', 'libros', $isbn, $idEjemplar)
        && !verificarCampoLibro($conexion, 'Titulo', 'libros', $titulo, $idEjemplar)
    ) {

        // Guardamos los datos para la insercción en la Base de Datos
        $nuevotitulo = $_POST['titulo'];
        $nuevoisbn = $_POST['isbn'];
        $titulo = $nuevotitulo;
        $nuevofechapubli = $_POST['fechaPubli'];
        $nuevoeditorial = $_POST['editorial'];
        $nuevodescripcion = $_POST['descripcion'];
        $nuevodescripcion = strip_tags($nuevodescripcion); //Eliminamos las etiquetas
        $nuevoprecio =  $_POST['precio'];
        $nuevoautor = $_POST['autor'];
        $nuevoestado = $_POST['estado'];
        
        //Insertamos imagem¡n
        $nuevaimagen = "";

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
                    $portada = "La foto de portada del libro nos ha llegado<br/>";
                } else {
                    $imagenCargada = false;
                    $errores["portada"] = "Error al cargar la foto";
                }
            }
        } else {
            $errores["portada"] = "Error en portada, imagen vacía o no recibida";
        }

        
        //Asignamos la nueva imagen
        $nuevaimagen = $imagen;
        
        // Mostramos una ventana modal con los datos del libro introducido al clicar un botón
        require 'modal/modalActualizarLibro.php';


        //Si no hay errores insertamos el libro en la Base de Datos
        try { // Definimos la consulta
            $sql = "UPDATE libros SET ISBN=:ISBN, Titulo=:Titulo, Fecha_Publicacion=:Fecha_Publicacion, Editorial=:Editorial, Descripcion=:Descripcion, Precio=:Precio, Portada=:Portada, Autor=:Autor, Estado=:Estado WHERE IdEjemplar=:IdEjemplar";

            //Preparamos
            $query = $conexion->prepare($sql);

            //Ejecutamos con los valores obtenidos
            $query->execute([
                'IdEjemplar' => $idEjemplar,
                'ISBN' => $nuevoisbn,
                'Titulo' => $nuevotitulo,
                'Fecha_Publicacion' => $nuevofechapubli,
                'Editorial' => $nuevoeditorial,
                'Descripcion' => $nuevodescripcion,
                'Precio' => $nuevoprecio,
                'Portada' => $nuevaimagen,
                'Autor' => $nuevoautor,
                'Estado' => $nuevoestado
            ]);

            // Supervisamos si se ha realizado correctamente
            if ($query) {
                // Registramos en la tabla logs el registro del admin
                registrarActividad($conexion,"actualizacion", "libro actualizado por ".$nombre);
                $msgresultado = '<div class="alert alert-success">' .
                    "El Libro se actualizó correctamente en la Base de Datos!! :)" . '</div>';
            } else {
                $msgresultado = '<div class="alert alert-danger">' .
                    "Datos de la actualización del Libro erróneos!! :( (" . $ex->getMessage() . ')</div>';
                //die();   
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger">' .
                "El Libro no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
        }
    }

    //Damos valores a los campos
    $valtitulo = $nuevotitulo;
    $valfechapubli = $nuevofechapubli;
    $valeditorial = $nuevoeditorial;
    $valdescripcion = $nuevodescripcion;
    $valprecio = $nuevoprecio;
    $valportada = $nuevaimagen;
    $valestado = $nuevoestado;
    $valautor = $nuevoautor;
} else {
    //----------------Si no se pulsa en actualizar nos traemos los datos--------------------------------
    if (isset($_GET['idEje']) && (is_numeric($_GET['idEje']))) { //Si tenemos el id y es número

        //Almacenamos el id
        $id = $_GET['idEje'];

        //Nos traemos los datos de la BD
        try {

            //Conectamos en la BD y lo guardamos
            $query = "SELECT * FROM libros WHERE IdEjemplar=:id";
            $resultado = $conexion->prepare($query);
            $resultado->execute(['id' => $id]);

            //Si hay datos en la consulta
            if ($resultado) {
                $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente(existe el idEjemplar)!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';

                //Insertamos los datos traidos de la bd
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                $fechaFormateada = date('m/Y', strtotime($fila['Fecha_Publicacion']));
                //Guardamos en las variables
                $valisbn = $fila['ISBN'];
                $valtitulo = $fila['Titulo'];
                $valfechapubli = $fila['Fecha_Publicacion'];
                $valeditorial = $fila['Editorial'];
                $valdescripcion = $fila['Descripcion'];
                $valprecio = $fila['Precio'];
                $valautor = $fila['Autor'];
                $valportada = $fila['Portada'];
                $valestado = $fila['Estado'];
                if($valestado == 0) {
                    $valestado = "Libre";
                }else {
                    $valestado = "Prestado";
                }
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!" . $ex->getMessage() . '<img class="mx-2" width="50" height="50" src="https://img.icons8.com/cute-clipart/64/error.png" alt="error"/></div>';
            //die();
        }
    }
}

?>

<!--Validar Formulario Actualizar Usuario-->
<div class="d-flex flex-row mb-3 justify-content-evenly">

    <!--Botón Atras-->
    <a href="listarLibros.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>

    <!--Botón Título-->
    <h1>Actualizar Libro</h1>
    <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
</div>

<!-- Modal para actualizar Libro Utilizamos la clase agregarLibro para estilos-->
<div class="container agregarLibro w-75">

    <!--Mostramos los posibles errores en los campos-->
    <?php echo validez($errores);

    ?>
    <!--Mostramos los mensajes corrspondientes-->
    <?php echo $msgresultado ?>
    <?php echo $msgresultadoIsbn ?>
    <?php echo $msgresultadoTitulo ?>

    <!--Formulario Actualizar Libro-->
    <form action="" method="POST" enctype="multipart/form-data">

        <!--Campos-->
        <!--ISBN-->
        <div class="form-group mb-3">
        <label for="isbn" class="form-label">ISBN</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="isbn" id="isbn" class="form-control" value="<?php 
                echo $valisbn; ?>">
            <?php echo  mostrar_error($errores, "isbn"); ?>
        </div>

        <!--Título-->
        <div class="form-group mb-3">
            <label for="titulo" class="form-label">Título</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?php 
                echo $valtitulo; ?>">
            <?php echo  mostrar_error($errores, "titulo"); ?>
        </div>

        <!--Fecha Publicación-->
        <div class="form-group mb-3">
        <label for="antigua fecha" class="form-label">Fecha Anterior</label></br>
        <input type="text" value="<?php echo $fechaFormateada ?>" readonly></br>
            <label for="fechaPubli" class="form-label">Fecha Publicación</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="date" name="fechaPubli" id="fechaPubli" class="form-control" value="<?php 
                    echo $valfechapubli;  ?>">
            <?php echo  mostrar_error($errores, "fechaPubli"); ?>
        </div>

        <!--Editorial-->
        <div class=" form-group mb-3">
            <label for="editorial" class="form-label">Editorial</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="editorial" id="editorial" class="form-control" value="<?php 
                echo $valeditorial; ?>"> 
            <?php echo  mostrar_error($errores, "editorial"); ?>
        </div>

        <!--Descripción-->
        <div class="form-group mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion">
                <?php
                    echo htmlspecialchars($valdescripcion);?></textarea>
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
                value="<?php
                    echo $valprecio; ?>">

            <?php echo  mostrar_error($errores, "precio"); ?>
        </div>

        <!--Portada-->
        <div class="form-group mb-3">
            <?php if ($valportada != null) { ?>
                <img src="img/<?php echo $valportada; ?>"
                    width="60" /></br>
            <?php } ?>
            <label for="portada" class="form-label">Actualizar Portada</label></br>
            <input type="file" name="portada" class="form-control"><br />
            <?php echo  mostrar_error($errores, "portada"); ?>
        </div>

        <!------Autor------>
        <div class="form-group mb-3">
            <label for="autor" class="form-label">Autor</label><br>

                <label for="antiguo autor" class="form-label">Autor Anterior</label></br>
                <input type="text" value="<?php echo $valautor ?>" readonly></br>
                <label for="nuevo autor" class="form-label">Nuevo Autor</label>
                <select class="form-control form-select-sm" name="autor">
                    <option value="" selected disabled>Elige un autor...</option>
                    <!--Gabriel García Márquez-->
                    <option value="Gabriel García Márquez">Gabriel García Márquez</option>
                    <!--Isabel Allende-->
                    <option value="Isabel Allende">Isabel Allende</option>
                    <!--Jorge Luis Borges-->
                    <option value="Jorge Luis Borges">Jorge Luis Borges</option>
                    <!--Jorge Luis Borges</option-->
                    <option value="Miguel de Cervantes">Miguel de Cervantes</option>
                    <!--DESCONOCIDO-->
                    <option value="Desconocido">Desconocido</option>
                </select>

            <?php echo  mostrar_error($errores, "autor"); ?>
        </div>
        <!--Estado-->
        <div class="form-group mb-3">
            <!-- Campo para mostrar el estado actual -->
            <label for="estadoAactual" class="form-label">Estado Actual</label>
            <input type="text" id="estadoActual" class="form-control" value="<?php
             echo htmlspecialchars($valestado); ?>" readonly>

            <!-- Campo para seleccionar el nuevo estado -->
            <label for="estado" class="form-label">Nuevo Estado</label>
            <select id="estado" name="estado" class="form-control">
                <option value="" disabled selected>Elige un estado...</option>
                <option value="0">Libre</option>
                <option value="1">Prestado</option>
            </select>
            <?php echo  mostrar_error($errores, "estado"); ?>
        </div>

        <!--Btn Añadir Libro-->
        <button onclick="return confirmacion()" type="submit" name="actualizar" class="btn btn-primary">Actuzalizar Libro</button>

        <!--Campo oculto para mensaje de confirmación-->
        <input type="hidden" name="bien" id="bienInput" value="false">
    </form>
    <!--// Creamos una función para mensaje de confirmacion con JS-->
    <script>
        function confirmacion() {

            // Enlazamos con el DOOM de JS
            const bienInput = document.getElementById('bienInput');
            const confirmacion = confirm('¿Estás seguro de que deseas actualizar el libro?');

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