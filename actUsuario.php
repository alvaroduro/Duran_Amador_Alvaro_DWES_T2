<!--Actualizar datos del Usuario-->
<!--Agregar Usuario-->
<?php require 'includes/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'valActualizarUsuario.php'; ?>
<?php require 'verificarAtualizarCampo.php'; ?>
<?php if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol']; // Obtenemos el Rol del usuario
    if($rolUsuario == 1) {
        $nombre = "Ana Maria";
    }else {
        $nombre = $_GET['nombre']; //obtenemos el nombre si es usuario
    }
    $idProf = $_GET['idProf']; //Obtenemos el idProf
    echo "Rol= " . $rolUsuario . ", nombre= " . $nombre . ", idprof (para actualizar)= " . $idProf;
} else {
    echo "No se recibió ningún rol.";
}
$msgresultado = "";
$msgresultadoUsuario = "";
$msgresultadoMail = "";

//Variables actualizar
$valape1 = "";
$valape2 = "";
$valnombre = "";
$valpass = "";
$valemail = "";
$valusuario = "";
$valimagen = "";
$valrol = "";

//-----------------------------Si se pulsa en actualizar---------------------------------------------
if (isset($_POST["actualizar"]) && (count($errores) == 0)) {
    //Si el título ya existe
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    if (verificarCampo($conexion, 'Email', 'profesores', $email, $idProf)) {
        $msgresultadoMail = '<div class="alert alert-danger">' .
            "El email ya existe!! :)" . '</div>';
    }
    if (verificarCampo($conexion, 'Usuario', 'profesores', $usuario, $idProf)) {
        $msgresultadoUsuario = '<div class="alert alert-danger">' .
            "El nombre de Usuario ya existe!! :)" . '</div>';
    }

    // Si el email y el nombre usuario no existen
    if (
        !verificarCampo($conexion, 'Email', 'profesores', $email, $idProf)
        && !verificarCampo($conexion, 'Usuario', 'profesores', $usuario, $idProf)
    ) {

        // Guardamos los datos para la insercción en la Base de Datos
        $nuevoape1 = $_POST['ape1'];
        $nuevoape2 = $_POST['ape2'];
        $nuevonombre = $_POST['nombre'];
        $nuevapass =  md5($_POST['password']);
        $nuevomail = $_POST['email'];
        $nuevousuario = $_POST['usuario'];
        if($rolUsuario == 1) { $nuevorol = $_POST['rol']; }
        
        //Insertamos imagen
        $nuevaimagen = "";

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

        //Asignamos la nueva imagen
        $nuevaimagen = $imagen;
        // Mostramos una ventana modal con los datos del libro introducido al clicar un botón
        require 'modal/modalActualizarLibro.php';

        //Si es ADMIN
        if($rolUsuario == 1) {
        //Si no hay errores insertamos el libro en la Base de Datos
        try { // Definimos la consulta
            $sql = "UPDATE profesores SET Apellido1=:Apellido1, Apellido2=:Apellido2, Nombre=:Nombre, Password=:Password, Email=:Email, Usuario=:Usuario, Foto=:Foto, Rol=:Rol WHERE IdProf=:IdProf";

            //Preparamos
            $query = $conexion->prepare($sql);

            //Ejecutamos con los valores obtenidos
            $query->execute([
                'IdProf' => $idProf,
                'Apellido1' => $nuevoape1,
                'Apellido2' => $nuevoape2,
                'Nombre' => $nuevonombre,
                'Password' => $nuevapass,
                'Email' => $nuevomail,
                'Usuario' => $nuevousuario,
                'Foto' => $nuevaimagen,
                'Rol' => $nuevorol
            ]);

            // Supervisamos si se ha realizado correctamente
            if ($query) {
                $msgresultado = '<div class="alert alert-success">' .
                    "El Usuario se actualizó correctamente en la Base de Datos!! :)" . '</div>';
            } else {
                $msgresultado = '<div class="alert alert-danger">' .
                    "Datos de la actualización del Usuario erróneos!! :( (" . $ex->getMessage() . ')</div>';
                //die();   
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger">' .
                "El Usuario no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
        }
        //Si es USUARIO
    }else {
        //Si no hay errores insertamos el libro en la Base de Datos
        try { // Definimos la consulta
            $sql = "UPDATE profesores SET Apellido1=:Apellido1, Apellido2=:Apellido2, Nombre=:Nombre, Password=:Password, Email=:Email, Usuario=:Usuario, Foto=:Foto WHERE IdProf=:IdProf";

            //Preparamos
            $query = $conexion->prepare($sql);

            //Ejecutamos con los valores obtenidos
            $query->execute([
                'IdProf' => $idProf,
                'Apellido1' => $nuevoape1,
                'Apellido2' => $nuevoape2,
                'Nombre' => $nuevonombre,
                'Password' => $nuevapass,
                'Email' => $nuevomail,
                'Usuario' => $nuevousuario,
                'Foto' => $nuevaimagen,
            ]);

            // Supervisamos si se ha realizado correctamente
            if ($query) {
                $msgresultado = '<div class="alert alert-success">' .
                    "El Usuario se actualizó correctamente en la Base de Datos!! :)" . '</div>';
            } else {
                $msgresultado = '<div class="alert alert-danger">' .
                    "Datos de la actualización del Usuario erróneos!! :( (" . $ex->getMessage() . ')</div>';
                //die();   
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger">' .
                "El Usuario no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
        }
    }
    }

    //Damos valores a los campos
    $valape1 = $nuevoape1;
    $valape2 = $nuevoape2;
    $valnombre = $nuevonombre;
    $valpass = $nuevapass;
    $valemail = $nuevomail;
    $valusuario = $nuevousuario;
    $valimagen = $nuevaimagen;
    if($rolUsuario == 1) { $valrol = $nuevorol; }
    
} else {
    //----------------Si no se pulsa en actualizar nos traemos los datos--------------------------------
    if (isset($_GET['idProf']) && (is_numeric($_GET['idProf']))) { //Si tenemos el id y es número

        //Almacenamos el id
        $id = $_GET['idProf'];

        //Nos traemos los datos de la BD
        try {

            //Conectamos en la BD y lo guardamos
            $query = "SELECT * FROM profesores WHERE IdProf=:id";
            $resultado = $conexion->prepare($query);
            $resultado->execute(['id' => $id]);

            //Si hay datos en la consulta
            if ($resultado) {
                $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente(existe el idprof)!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';

                //Insertamos los datos traidos
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);

                //Guardamos en las variables
                $valape2 = $fila['Apellido1'];
                $valape1 = $fila['Apellido2'];
                $valnombre = $fila['Nombre'];
                $valpass = md5($fila['Password']);
                $valemail = $fila['Email'];
                $valusuario = $fila['Usuario'];
                $valimagen = $fila['Foto'];
                $valrol = $fila['Rol'];
                var_dump("Rol en consulta 1: " . $valrol);
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!" . '<img class="mx-2" width="50" height="50" src="https://img.icons8.com/cute-clipart/64/error.png" alt="error"/></div>';
            die();
        }
    }
}

?>


<!--Validar Formulario Actualizar Usuario-->
<div class="d-flex flex-row mb-3 justify-content-evenly">

    <!--Botón Atras-->
    <!--ADMIN-->
    <?php if($rolUsuario == 1) { ?>
        <a href="listarUsuarios.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
       <?php }else { ?>
        <a href="profesor.php?rol=<?php echo $rolUsuario; ?>&idProf=<?php echo $idProf; ?>&nombre=<?php echo $nombre; ?>"><img src="img/flechaAtras.png" alt="atras" width="40" height="40"></a>
       <?php } ?>
    <!--ADMIN-->
    

    <!--Botón Título-->
    <h1>Actualizar Usuario</h1>
    <a class="navbar-brand" href="index.php"><img src="img/exit.png" alt="salir" width="40" height="40"></a>
</div>

<!-- Modal para actualizar Usaurio Utilizamos la clase agregarLibro para estilos-->
<div class="container agregarLibro">

    <!--Mostramos los posibles errores en los campos-->
    <?php echo validez($errores);

    ?>
    <!--Mostramos los mensajes corrspondientes-->
    <?php echo $msgresultado ?>
    <?php echo $msgresultadoUsuario ?>
    <?php echo $msgresultadoMail ?>

    <!--Formulario Agregar Usuario-->
    <form action="" method="POST" enctype="multipart/form-data">

        <!--Campos-->
        <!--Campo oculto del id-->
        <input type="hidden" class="form-control" name="idProf" value="<?php echo $idProf ?>">
        <!--Apellido1-->
        <div class="form-group mb-3">
            <label for="ape1" class="form-label">Primer Apellido</label>
            <!--Mostramos el registro con el id anteriormente-->
            <input type="text" name="ape1" id="ape1" class="form-control" value="<?php echo $valape1 ?>">
            <?php echo  mostrar_error($errores, "ape1"); ?>
        </div>

        <!--Segundo Apellido-->
        <!--Apellido2-->
        <div class="form-group mb-3">
            <label for="ape2" class="form-label">Segundo Apellido</label>
            <!--Mostramos el registro con el id anteriormente-->
            <input type="text" name="ape2" id="ape2" class="form-control" value="<?php echo $valape2 ?>">
            <?php echo mostrar_error($errores, "ape2"); ?>
        </div>

        <!--Nombre-->
        <div class="form-group mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <!--Mostramos el registro con el id anteriormente-->
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $valnombre ?>">
            <?php echo  mostrar_error($errores, "nombre"); ?>
        </div>

        <!--Email-->
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <!--Mostramos el registro con el id anteriormente-->
            <input type="text" name="email" id="email" class="form-control" value="<?php echo $valemail ?>">
            <?php echo  mostrar_error($errores, "email"); ?>
        </div>

        <!--Password-->
        <div class="form-group mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <!--Mostramos el registro con el id anteriormente-->
            <input type="password" name="password" id="password" class="form-control" value="<?php echo md5($valpass) ?>">
            <?php echo  mostrar_error($errores, "password"); ?>
        </div>

        <!--Usuario-->
        <div class="form-group mb-3">
            <label for="usuario" class="form-label">Nombre Usuario</label>
            <!--Mostramos el registro con el id anteriormente-->
            <input type="text" name="usuario" id="usuario" class="form-control" value="<?php echo $valusuario ?>">
            <?php echo  mostrar_error($errores, "usuario"); ?>
        </div>

        <!--Foto-->
        <div class="form-group mb-3">
            <?php if ($valimagen != null) { ?>
                <img src="img/<?php echo $valimagen; ?>"
                    width="60" /></br>
            <?php } ?>
            <label for="foto" class="form-label">Actualizar Imagen</label></br>
            <input type="file" name="foto" class="form-control"><br />
            <?php echo  mostrar_error($errores, "foto"); ?>
        </div>

        <!--Solo si es ADMIN Puede modificar el Rol-->
        <?php if($rolUsuario == 1) {?>
            <!--Rol-->
        <div class="form-group mb-3">
            <!-- Campo para mostrar el estado actual -->
            <label for="rolActual" class="form-label">Rol Actual</label>
            <input type="text" id="estadoActual" class="form-control" value="<?php
             echo $valrol; ?>" readonly>
            <label for="rol" class="form-label">ROL</label>
            <!--Mostramos el registro con el id anteriormente-->
            <select id="rol" name="rol" class="form-control">
                <option value="" disabled selected>Elige un rol...</option>
                <option value="0">Usuario</option>
                <option value="1">Administrador</option>
            </select>
        </div>
        <?php } ?>
        

        <!--Btn Añadir Libro-->
        <button onclick="return confirmacion()" type="submit" name="actualizar" class="btn btn-primary">Actualizar Usuario</button>

        <!--Campo oculto para mensaje de confirmación-->
        <input type="hidden" name="bien" id="bienInput" value="false">
    </form>
    <!--// Creamos una función para mensaje de confirmacion con JS-->
    <script>
        function confirmacion() {

            // Enlazamos con el DOOM de JS
            const bienInput = document.getElementById('bienInput');
            const confirmacion = confirm('¿Estás seguro de que deseas actualizar el Usuario?');

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