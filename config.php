<?php
// Fichero de configuración para la conexión a la Base de Datos (PDO)
$dbHost = 'localhost'; //Servidor
$dbName = 'bdbiblioteca'; //Nombre BD
$dbUser = 'root'; //Usuario
$dbPass = ''; //Contraseña

// Insertamos un bloque try-catch para controlar errores
try {
    //Creamos un objeto clase PDO con los parámetros
    $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    //Configuramos el objeto PDO para lanzar excepciones si ocurre un error
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<div class="alert alert-success">' . "Conectado a la  Base de Datos de la Empresa!! :)" . '</div>';
} catch (PDOException $ex) {
    //Mostramos el posible error a la conexión
    echo '<div class="alert alert-danger">' . "No se pudo conectar a la Base de Datos de la Empresa!! :( <br/>" . $ex->getMessage() . '</div>';
    die();
}
