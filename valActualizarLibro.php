<?php

/**
 * Script que muestra en una tabla los valores enviados por el usuario a través 
 * del formulario utilizando el método POST
 */

// Definimos e inicializamos el array de errores
// Definimos e inicializamos el array de errores y las variables asociadas a cada campo
$errores = [];
$nuevotitulo = "";
$nuevofechapubli = "";
$fechaFormateada = date('m/Y', strtotime($nuevofechapubli)); //Convertimos la fecha al formato deseado
$nuevoeditorial = "";
$nuevodescripcion = "";
$nuevoprecio = "";
$nuevaimagen = "";
$nuevoestado = "";
$nuevoautor = "";
$nuevoisbn = "";

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

// Visualización de las variables obtenidas mediante el formulario en modal
function valoresfrm()
{
    global $nuevotitulo, $nuevoisbn, $fechaFormateada, $nuevoeditorial, $nuevodescripcion, $nuevoprecio, $nuevoautor, $nuevoestado, $nuevaimagen;

    echo "<h4>Datos del Libro <b>" . $nuevotitulo . "</b> obtenidos mediante el formulario para actualizar</h4><br/>";
    echo "<strong>ISBN: </strong>" . $nuevoisbn . "<br/>";
    echo "<strong>Título: </strong>" . $nuevotitulo . "<br/>";
    echo "<strong>Fecha Publicación: </strong>" . $fechaFormateada  . "<br/>";
    echo "<strong>Editorial: </strong>" . $nuevoeditorial . "<br/>";
    echo "<strong>Descripción: </strong>" . $nuevodescripcion . "<br/>";
    echo "<strong>Precio: </strong>" . $nuevoprecio . "€ <br/>";
    echo "<strong>Autor: </strong>" . $nuevoautor . "<br/>";
    echo "<strong>Estado: </strong>" . $nuevoestado . "<br/>";
    echo "<strong>Foto(ruta): </strong>" . $_FILES["portada"]["tmp_name"] . "<br/>";
    echo "<strong>Foto: </strong>" . $nuevaimagen . "<br/>";
}

// Comprobamos los campos
if (isset($_POST["actualizar"])) { //Si se pulsa en Añadir Libro

    //Validamos los campos introducidos

    //Campo ISBN
    if (
        !empty($_POST["isbn"])
        && (preg_match('/^\d{10,13}$/', $_POST["isbn"]))
        //Cualquier digito del 0 al 9
        //Entre 10 y 13 numeros
    ) {
        $isbn = $_POST["isbn"];
        //echo  "ISBN: <b>" . $isbn . "</b><br/>";
    } else {
        $errores["isbn"] = "El formato del ISBN no es válido";
    }

    //Campo Título
    if (
        !empty($_POST["titulo"]) &&
        (strlen($_POST["titulo"]) < 100)
    ) {
        //Satinizamos
        $nuevotitulo = htmlspecialchars(trim($_POST['titulo']));
        //echo  "Título: <b>" . $titulo . "</b><br/>";
    } else {
        $errores["titulo"] = "No puede estar vacío/No puede contener más de 20 caracteres";
    }

    //Campo Fecha Publicación
    if (!empty($_POST["fechaPubli"])) {
        //Formateamos fecha al deseado d/m/a
        $nuevofechapubli = $_POST["fechaPubli"];
        $fechaFormateada = date('m/Y', strtotime($nuevofechapubli)); //Convertimos la fecha al formato deseado
        //echo "Fecha de Publicación: <b>" . $fechaFormateada . "</b><br/>";
    } else {
        $errores["fechaPubli"] = "Fecha de publicación errónea, debes introducir una fecha nueva";
    }

    //Campo Editorial
    if (
        !empty($_POST["editorial"]) &&
        (strlen($_POST["editorial"]) < 40)
    ) {
        //Satinizamos
        $nuevoeditorial = htmlspecialchars(trim($_POST['editorial']));
        //echo  "Editorial: <b>" . $editorial . "</b><br/>";
    } else {
        $errores["editorial"] = "No puede estar vacío/No puede contener más de 20 caracteres";
    }

    //Campo Descripcion
    if (
        !empty($_POST["descripcion"])
    ) {
        //Satinizamos
        $nuevodescripcion = $_POST["descripcion"];
        $nuevodescripcion = trim($nuevodescripcion); // Eliminamos espacios en blanco
        $nuevodescripcion = htmlspecialchars($nuevodescripcion); //Caracteres especiales a HTML
        $nuevodescripcion = stripslashes($nuevodescripcion); //Elimina barras invertidas
        //echo  "Descripción: <b>" . $descripcion . "</b><br/>";
    } else {
        $errores["descripcion"] = "No puede estar vacío";
    }

    //Campo Precio
    if (
        !empty($_POST["precio"] && is_numeric($_POST["precio"]) && $_POST["precio"] >= 0)
    ) {
        //Satinizamos
        $nuevoprecio = $_POST['precio'];
        //echo  "Precio: <b>" . $precio . "</b><br/>";
    } else {
        $errores["precio"] = "Error en el precio";
    }

    //Campo Fotografía
    if (!isset($_FILES["portada"]) || empty($_FILES["portada"]["tmp_name"])) {
        $errores["portada"] = "Seleccione una portada válida";
    } else {
        $nuevaimagen = "La portada nos ha llegado<br/>";
    }

    //Campo Autor
    if (!empty($_POST['autor'])) {
        $nuevoautor = $_POST["autor"];
        //echo "Autor: <b>" . $autor . "</b><br/>";
    } else {
        $errores["autor"] = "Debe seleccionar un autor :(";
    }

    // Estado
    if (isset($_POST['estado'])) {
        $nuevoestado = htmlspecialchars(trim($_POST['estado'])); // Guardamos el estado seleccionado
    } else {
        echo "vemos el formulario";
        var_dump($_POST);
        $errores["estado"] = "Debes seleccionar un estado válido (Libre o Prestado).<br/>";
    }
}
