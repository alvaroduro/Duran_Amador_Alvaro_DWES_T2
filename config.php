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

//CREAR TABLA LOG DESDE PROCEDIMIENTO
try {
    $sqlCrearTablaLog = "
        CREATE TABLE IF NOT EXISTS log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fecha_hora DATETIME NOT NULL,
            tipo_actividad ENUM('visualizacion', 'alta', 'baja', 'actualizacion') NOT NULL,
            descripcion TEXT NOT NULL
        );
        ";
    $conexion->exec($sqlCrearTablaLog);
    echo  '<div class="alert alert-success">' . "Tabla 'log' creada exitosamente!! :)" . '</div>';
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">' . "Error al crear la tabla 'log': " . $e->getMessage() . '</div>';
}

//Funcion para introducir datos en la tabla log
function registrarActividad($conexion, $tipoActividad, $descripcion)
{
    try {
        // Validar tipo de actividad
        $tiposPermitidos = ['visualizacion', 'alta', 'baja', 'actualizacion'];
        if (!in_array($tipoActividad, $tiposPermitidos)) {
            echo "Tipo de actividad no válido: $tipoActividad";
        }

        // Preparar consulta SQL
        $sql = "INSERT INTO log (fecha_hora, tipo_actividad, descripcion) 
                VALUES (NOW(), :tipoActividad, :descripcion)";
        $resultado = $conexion->prepare($sql);

        // Ejecutar la consulta con los valores
        $resultado->execute([
            ':tipoActividad' => $tipoActividad,
            ':descripcion' => $descripcion
        ]);

        echo "log añadido";
    } catch (PDOException $e) {
        // Manejar errores 
        echo "Error al registrar actividad: " . $e->getMessage();
    }
}
