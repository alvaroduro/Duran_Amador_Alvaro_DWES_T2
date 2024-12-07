<!--Archivo para comprobar si el usuario existe y las creedenciales son válidad-->
<?php

//Variables
$msgresultadoCampo = ""; // Mensaje para fallo en campos
$modal = "";
$usuarioInput = ''; // Almacenará el último usuario ingresado
$rolUsuario = null; // Variable para almacenar el rol del usuario

// Cuando se pulse boton iniciar sesion
if (isset($_POST['btningresar'])) {

    // Comprobamos si algún campo está vacío
    if (!empty($_POST['usuario']) && !empty($_POST['password'])) {

        // Si no están vacío iniciamos la consulta
        $usuario = $_POST['usuario'];
        $password = md5($_POST['password']);
        $usuarioInput = htmlspecialchars($usuario); // Evitamos caracteres especiales al mantener el usuario en el input

        // Generamos el listado de usuarios
        try {
            //Conectamos en la BD y lo guardamos
            $sql = "SELECT * FROM Profesores WHERE usuario = :usuario AND password = :password ";
            $resultado = $conexion->prepare($sql);
            //Comprobamos creedenciales
            $resultado->execute(['usuario' => $usuarioInput, 'password' => $password]);
            //var_dump(!empty($fila = $resultado->fetch(PDO::FETCH_ASSOC)));

            //Si hay datos en la consulta
            if (!empty($filas = $resultado->fetch(PDO::FETCH_ASSOC))) { ?>
                <!--Mostramos alerta de creedenciales correctas-->
                <script>
                    swal("Creedenciales Correctas!", "Bienvenido", "success");
                </script>
            <?php

                //Guardamos el rol del profesor
                $rolUsuario = $filas['Rol'];

                // Redirigimos a listarLibros.php con el rol del usuario
                header('Location: listarLibros.php?rol=' . $rolUsuario);

                //Si la consulta no se ha realizado corectamente
            } else {
                // En caso de no existir datos de ese usuario
            ?>
                <!--Mostramos alerta de creedenciales Incorrectas-->
                <script>
                    swal("Creedenciales Incorrectas!", "Usuario o contraseña no coinciden", "error");
                </script>
            <?php
            }
        } catch (PDOException $ex) {
            // Error de conexión a la Base de datos
            // En caso de no existir datos de ese usuario
            ?>
            <!--Mostramos alerta de creedenciales Incorrectas-->
            <script>
                swal("Ocurrió un error en la Base de Datos", "Procederemos a solucionarlo lo antes posible", "warning");
            </script>
<?php
            die();
        }

        // Si algun campo está vacío
    } else {
        $msgresultadoCampo = '<div class="alert alert-danger mx-2">Por favor, complete todos los campos.</div>';
    }
}

?>