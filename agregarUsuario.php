<!--Agregar Usuario-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'valAgregarUsuario.php'; ?>
<?php require 'verificarCampo.php'; ?>
<?php if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    $nombre = $_GET['nombre']; //obtenemos el nombre
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ", idprof= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}
$msgresultadoUsuario = "";
$msgresultadoMail = "";
?>

<!--Validar Formulario Agregar Usuario-->
<div class="d-flex flex-row mb-3 justify-content-evenly">

    <!--Botón Atras-->
    <a href="admin.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>

    <!--Botón Título-->
    <h1>Agregar Usuario Nuevo</h1>
    <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
</div>

<!-- Modal para agregar Usaurio Utilizamos la clase agregarLibro para estilos-->
<div class="container agregarLibro">

    <!--Mostramos los posibles errores en los campos-->
    <?php echo validez($errores);

    //Definimos la variable a null ya que todavia no se ha cargado imagen
    $imagen = null;

    //Si no hay errores imprimimos los valores almacenados
    if (isset($_POST["anadirUsuario"]) && (count($errores) == 0)) {

        //Si el título ya existe
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        if (verificarCampo($conexion, 'Email', 'profesores', $email)) {
            $msgresultadoMail = '<div class="alert alert-danger">' .
                "El email ya existe!! :)" . '</div>';
        }
        if (verificarCampo($conexion, 'Usuario', 'profesores', $usuario)) {
            $msgresultadoUsuario = '<div class="alert alert-danger">' .
                "El nombre de Usuario ya existe!! :)" . '</div>';
        }

        // Si el email y el nombre usuario no existen
        if (
            !verificarCampo($conexion, 'Email', 'profesores', $email)
            && !verificarCampo($conexion, 'Usuario', 'profesores', $usuario)
        ) {
            // Guardamos los datos para la insercción en la Base de Datos
            $ape1 = $_POST['ape1'];
            $ape2 = $_POST['ape2'];
            $nombre = $_POST['nombre'];
            $password =  md5($_POST['password']);
            $rol = 0; //Definimos el rol usuario(por defecto, solo hay 1 admin)

            //Tratamos la imagen -Definimos su variable a null
            //En caso de almacenar la img en la BD
            $imagen = NULL;

            //Comprobamos que el campo tmp_name tiene una valor asignado
            //Y que hemos recibido la img correctamente
            if (isset($_FILES['foto']) && (!empty($_FILES['foto']['tmp_name']))) {
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
                    $nombreImg = time() . "-" . $_FILES['foto']['name'];

                    //Movemos el archivo a nuestra carpeta
                    $moverImg = move_uploaded_file($_FILES['foto']['tmp_name'], "img/" . $nombreImg);

                    // Definimos el nombre (ruta) de la imagen
                    $imagen = $nombreImg;

                    //Verificamos la carga si se ha realizado correctamente
                    if ($moverImg) { //En caso de que se haya movido bien
                        $imagenCargada = true;
                        $foto = "La foto de avatar nos ha llegado<br/>";
                    } else {
                        $imagenCargada = false;
                        $errores["foto"] = "Error al cargar la foto";
                    }
                }
            } else {
                $errores["foto"] = "Error en foto, imagen vacía o no recibida";
            }

            // Mostramos una ventana modal con los datos del libro introducido al clicar un botón
            require 'modal/modalAgregarUsuario.php';

            //Si no hay errores insertamos el libro en la Base de Datos
            try { // Definimos la consulta
                $sql = "INSERT INTO profesores(Apellido1,Apellido2,Nombre,Password,Email,Usuario,Foto,Rol) VALUES (:Apellido1,:Apellido2,:Nombre,:Password,:Email,:Usuario,:Foto,:Rol)";

                //Preparamos
                $query = $conexion->prepare($sql);

                //Ejecutamos con los valores obtenidos
                $query->execute([
                    'Apellido1' => $ape1,
                    'Apellido2' => $ape2,
                    'Nombre' => $nombre,
                    'Password' => $password,
                    'Email' => $email,
                    'Usuario' => $usuario,
                    'Foto' => $imagen,
                    'Rol' => $rol,
                ]);

                // Supervisamos si se ha realizado correctamente
                if ($query) {
                    $msgresultado = '<div class="alert alert-success">' .
                        "El Usuario se registró correctamente en la Base de Datos!! :)" . '</div>';
                } else {
                    $msgresultado = '<div class="alert alert-danger">' .
                        "Datos de registro del Usuario erróneos!! :( (" . $ex->getMessage() . ')</div>';
                    //die();   
                }
            } catch (PDOException $ex) {
                $msgresultado = '<div class="alert alert-danger">' .
                    "El Usuario no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
            }
        }
    }

    ?>
    <!--Mostramos los mensajes corrspondientes-->
    <?php echo $msgresultado ?>
    <?php echo $msgresultadoUsuario ?>
    <?php echo $msgresultadoMail ?>

    <!--Formulario Agregar Usuario-->
    <form action="" method="POST" enctype="multipart/form-data">

        <!--Campos-->
        <!--Apellido1-->
        <div class="form-group mb-3">
            <label for="ape1" class="form-label">Primer Apellido</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="ape1" id="ape1" class="form-control"
                <?php if (isset($_POST["ape1"])) {
                    echo "value='{$_POST["ape1"]}'";
                } ?>>
            <?php echo  mostrar_error($errores, "ape1"); ?>
        </div>

        <!--Segundo Apellido-->
        <div class="form-group mb-3">
            <label for="ape2" class="form-label">Segundo Apellido</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="ape2" id="ape2" class="form-control"
                <?php if (isset($_POST["ape2"])) {
                    echo "value='{$_POST["ape2"]}'";
                } ?>>
            <?php echo  mostrar_error($errores, "ape2"); ?>
        </div>

        <!--Nombre-->
        <div class="form-group mb-3">
            <label for="nombre" class="form-label">Nombre</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="nombre" id="nombre" class="form-control"
                <?php if (isset($_POST["nombre"])) {
                    echo "value='{$_POST["nombre"]}'";
                } ?>>
            <?php echo  mostrar_error($errores, "nombre"); ?>
        </div>

        <!--Email-->
        <div class=" form-group mb-3">
            <label for="email" class="form-label">Email</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="email" name="email" id="email" class="form-control"
                <?php if (isset($_POST["email"])) {
                    echo "value='{$_POST["email"]}'";
                } ?>>

            <?php echo  mostrar_error($errores, "email"); ?>
        </div>

        <!--Password-->
        <div class=" form-group mb-3">
            <label for="password" class="form-label">Contraseña</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="password" name="password" id="password" class="form-control"
                <?php if (isset($_POST["password"])) {
                    echo "value='{$_POST["password"]}'";
                } ?>>

            <?php echo  mostrar_error($errores, "password"); ?>
        </div>

        <!--Usuario-->
        <div class="form-group mb-3">
            <label for="usuario" class="form-label">Usuario</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="usuario" id="usuario" class="form-control"
                <?php if (isset($_POST["usuario"])) {
                    echo "value='{$_POST["usuario"]}'";
                } ?>>
            <?php echo  mostrar_error($errores, "usuario"); ?>
        </div>

        <!--Foto-->
        <div class="form-group mb-3">
            <label for="foto" class="form-label">Foto/Avatar</label>
            <input type="file" name="foto" class="form-control">
            <!--Si la imagen no es null mostramos la ultima imagen cargada-->
            <?php if ($imagen != null) {
                echo '<div class="mt-2">';
                echo '<label>Última portada cargada:</label><br>';
                echo '<img src="img/' . $imagen . '" alt="Foto cargada" width="70" height="80">';
                echo '</div>';
            } ?>
            <?php echo  mostrar_error($errores, "foto"); ?>

        </div>

        <!--Btn Añadir Libro-->
        <button onclick="return confirmacion()" type="submit" name="anadirUsuario" class="btn btn-primary">Añadir Usuario Nuevo</button>

        <!--Campo oculto para mensaje de confirmación-->
        <input type="hidden" name="bien" id="bienInput" value="false">
    </form>
    <!--// Creamos una función para mensaje de confirmacion con JS-->
    <script>
        function confirmacion() {

            // Enlazamos con el DOOM de JS
            const bienInput = document.getElementById('bienInput');
            const confirmacion = confirm('¿Estás seguro de que deseas añadir el Usuario?');

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