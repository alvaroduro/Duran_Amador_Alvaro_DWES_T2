<?php

/**
 * Script que muestra en una tabla los valores enviados por el usuario a través 
 * del formulario utilizando el método POST
 */

// Definimos e inicializamos el array de errores
// Definimos e inicializamos el array de errores y las variables asociadas a cada campo
$errores = [];
$ape1 = "";
$ape2 = "";
$nombre = "";
$email = "";
$usuario = "";
$password = "";
$foto = "";
$rol = 0; //El estado inicial de un libro es libre
$msgresultado = "";

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
    if (isset($_POST["anadirUsuario"]) && (count($errores) == 0)) {
        return '<div class="alert alert-success" style="margin-top:5px;"> Procedemos a insertar el Usuario en la Base de Datos</div>';
    }
}

// Visualización de las variables obtenidas mediante el formulario en modal
function valoresfrm()
{
    global $ape1, $ape2, $nombre, $email, $usuario, $password, $foto, $rol;

    echo "<h4>Datos del Usuario <b>" . $usuario . "</b> obtenidos mediante el formulario</h4><br/>";
    echo "<strong>Primer Apellido: </strong>" . $ape1 . "<br/>";
    echo "<strong>Segundo Apellido: </strong>" . $ape2  . "<br/>";
    echo "<strong>Nombre: </strong>" . $nombre . "<br/>";
    echo "<strong>Email: </strong>" . $email . "<br/>";
    echo "<strong>Nombre Usuario: </strong>" . $usuario . "<br/>";
    echo "<strong>Contraseña: </strong>" . md5($password) . "<br/>";
    echo "<strong>Rol: </strong>" . $rol . "<br/>";
    echo "<strong>Foto(ruta): </strong>" . $_FILES["foto"]["tmp_name"] . "<br/>";
    echo "<strong>Foto: </strong>" . $foto . "<br/>";
}

// Comprobamos los campos
if (isset($_POST["anadirUsuario"])) { //Si se pulsa en Añadir Libro
    //Validamos los campos introducidos
    echo "Validamos";

    //Campo Apellido 1
    /*if (
        !empty($_POST["ape1"])
        && (!preg_match("/[0-9]/", $_POST["ape1"]))
        && (strlen($_POST["ape1"]) < 15)
    ) {
        $ape1 = trim($_POST["ape1"]);
        $ape1 = filter_var($ape1, FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $errores["ape1"] = "El primer apellido introducido no es válido :(";
    }*/
    //Campo Apellido 2
    /*if (
        !empty($_POST["ape2"])
        && (!preg_match("/[0-9]/", $_POST["ape2"]))
        && (strlen($_POST["ape2"]) < 15)
    ) {
        $ape2 = trim($_POST["ape2"]);
        $ape2 = filter_var($ape2, FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $errores["ape2"] = "El segundo apellido introducido no es válido :(";
    }*/

    //Apellidos
    if (
        !empty($_POST['ape1']) && //Si el campo nombre no está vacío  
        (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['ape1'])) //Expresion regular  
    ) {
        $ape1 = htmlspecialchars(trim($_POST['ape1'])); //Guardamos en una variable
        //echo  "Primer apellido:<b>" . $ape1 . "</b><br/>";
    } else {
        $errores['ape1'] = "No puede estar vacío ni contener mas de 20 caracteres, tampoco debe contener números<br/>";
    }

    if (
        !empty($_POST['ape2']) && //Si el campo nombre no está vacío  
        (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['ape2'])) //Expresion regular  
    ) {
        $ape1 = htmlspecialchars(trim($_POST['ape2'])); //Guardamos en una variable
        //echo  "Segundo apellido:<b>" . $ape1 . "</b><br/>";
    } else {
        $errores['ape2'] = "No puede estar vacío ni contener mas de 20 caracteres, tampoco debe contener números<br/>";
    }

    //Nombre
    if (
        strlen($_POST['nombre']) < 20 && //Los caracteres son menores de 20
        (!empty($_POST['nombre'])) && //Si el campo nombre no está vacío  
        (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['nombre'])) //&& //Expresion regular  
    ) {
        $nombre = htmlspecialchars(trim($_POST['nombre'])); //Guardamos en una variable
        //echo  "Nombre: <b>" . $nombre . "</b><br/>";
    } else {
        echo $_POST['nombre'] . "<br/>";
        $errores['nombre'] = "No puede estar vacío ni contener mas de 20 caracteres, tampoco debe contener números<br/>";
    }

    //Campo Email
    if (
        !empty($_POST["email"])
        && (preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $_POST["email"]))
    ) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //echo  "Email: <b>" . $email . "</b><br/>";
        }
    } else {
        $errores['email'] = "La dirección email introducida no es válida :(<br/>";
    }

    //Campo Password
    if (!empty($_POST["password"]) && (strlen($_POST["password"]) > 6) && (strlen($_POST["password"]) <= 10)) {
        //echo "Contraseña:" . md5($_POST["password"]) . "<br/>";
    } else {
        $errores["password"] = "Introduzca una contraseña válida (6-10 caracteres) :(";
    }

    //Usuario

    if (
        !empty($_POST['usuario']) && // Si el campo usuario no está vacío
        (preg_match("/^[a-zA-Z0-9_]{1,20}$/", $_POST['usuario'])) // Expresión regular para letras, números y guiones bajos
    ) {
        $usuario = htmlspecialchars(trim($_POST['usuario'])); // Guardamos en una variable
        //echo "Nombre de Usuario:<b>" . $usuario . "</b><br/>";
    } else {
        $errores['usuario'] = "El nombre de usuario no puede estar vacío, debe tener un máximo de 20 caracteres y solo puede contener letras, números y guiones bajos.<br/>";
    }


    //Campo Foto
    if (isset($_FILES['foto']) && !empty($_FILES['foto']['tmp_name'])) {

        //Verificamos el formato 
        $imagen = $_FILES['foto'];
        $extensionesPermitidas = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imagen['type'], $extensionesPermitidas)) {
            echo "La foto debe ser un archivo válido (JPG, PNG o GIF).";
        } else {
            //echo  "Fotografía:" . "La imagen nos ha llegado ;)";
        }
    } else {
        $errores['foto'] = "Seleccione una imagen válida :(";
    }
}
