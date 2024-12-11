<?php

/**
 * Script que muestra en una tabla los valores enviados por el usuario a través 
 * del formulario utilizando el método POST
 */

// Definimos e inicializamos el array de errores
// Definimos e inicializamos el array de errores y las variables asociadas a cada campo
$errores = [];
$isbn = "";
$titulo = "";
$fechaFormateada = "";
$editorial = "";
$descripcion = "";
$precio = "";
$portada = "";
$autor = "";
$estado = 0; //El estado inicial de un libro es libre
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
    if (isset($_POST["anadirLibro"]) && (count($errores) == 0)) {
        return '<div class="alert alert-success" style="margin-top:5px;"> Procedemos a insertar el Libro en la Base de Datos</div>';
    }
}

// Visualización de las variables obtenidas mediante el formulario en modal
function valoresfrm() {
    global $isbn,$titulo,$fechaFormateada,$editorial,$descripcion,$precio,$autor,$estado,$portada;
   
    echo "<h4>Datos del libro <b>". $titulo ."</b> obtenidos mediante el formulario</h4><br/>";
    echo "<strong>ISBN: </strong>" . $isbn . "<br/>";
    echo "<strong>Titulo: </strong>" . $titulo . "<br/>";
    echo "<strong>FechaFormateada: </strong>" . $fechaFormateada . "<br/>";
    echo "<strong>Editorial: </strong>" . $editorial . "<br/>";
    echo "<strong>Descripcion: </strong>" . $descripcion . "<br/>";
    echo "<strong>Precio: </strong>" . $precio . "<br/>";
    echo "<strong>Autor: </strong>" . $autor . "<br/>";
    echo "<strong>Estado: </strong>" . $estado . "<br/>";
    echo "<strong>Portada(ruta): </strong>" . $_FILES["portada"]["tmp_name"] . "<br/>";
    echo "<strong>Portada: </strong>" . $portada . "<br/>";
  }

// Comprobamos los campos
if (isset($_POST["anadirLibro"])) { //Si se pulsa en Añadir Libro
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
        $titulo = htmlspecialchars(trim($_POST['titulo']));
        //echo  "Título: <b>" . $titulo . "</b><br/>";
    } else {
        $errores["titulo"] = "No puede estar vacío/No puede contener más de 20 caracteres";
    }

    //Campo Fecha Publicación
    if (!empty($_POST["fechaPubli"])) {
        //Formateamos fecha al deseado d/m/a
        $fecha = $_POST["fechaPubli"];
        $fechaFormateada = date('m/Y',strtotime($fecha)); //Convertimos la fecha al formato deseaso
        //echo "Fecha de Publicación: <b>" . $fechaFormateada . "</b><br/>";
    } else {
        $errores["fechaPubli"] = "Fecha de publicación errónea";
    }

    //Campo Editorial
    if (
        !empty($_POST["editorial"]) &&
        (strlen($_POST["editorial"]) < 20)
    ) {
        //Satinizamos
        $editorial = htmlspecialchars(trim($_POST['editorial']));
        //echo  "Editorial: <b>" . $editorial . "</b><br/>";
    } else {
        $errores["editorial"] = "No puede estar vacío/No puede contener más de 20 caracteres";
    }

    //Campo Descripcion
    if (
        !empty($_POST["descripcion"])
    ) {
        //Satinizamos
        $descripcion = $_POST["descripcion"];
        $descripcion = trim($descripcion); // Eliminamos espacios en blanco
        $descripcion = htmlspecialchars($descripcion); //Caracteres especiales a HTML
        $descripcion = stripslashes($descripcion); //Elimina barras invertidas
        //echo  "Descripción: <b>" . $descripcion . "</b><br/>";
    } else {
        $errores["descripcion"] = "No puede estar vacío";
    }

    //Campo Precio
    if (
        !empty($_POST["precio"] && is_numeric($_POST["precio"]) && $_POST["precio"] >= 0)
    ) {
        //Satinizamos
        $precio = $_POST['precio'];
        //echo  "Precio: <b>" . $precio . "</b><br/>";
    } else {
        $errores["precio"] = "Error en el precio";
    }

    //Campo Fotografía
    if (!isset($_FILES["portada"]) || empty($_FILES["portada"]["tmp_name"])) {
        $errores["portada"] = "Seleccione una portada válida";
    } else {
        $portada = "La portada nos ha llegado<br/>";
    }

    //Campo Autor
    if (!empty($_POST['autor'])) {
            $autor = $_POST["autor"];
        //echo "Autor: <b>" . $autor . "</b><br/>";
    } else {
        $errores["autor"] = "Debe seleccionar un autor :(";
    }
}
