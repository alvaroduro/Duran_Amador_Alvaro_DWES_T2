<?php
$msgresultado = "";
// Funcion para verificar si existe campo y excluyendo por idprof
function verificarCampo($conexion, $campo, $tabla, $dato, $idProf) {
    try {
        // Consulta para verificar si el campo existe en la tabla especificada
        $sql = "SELECT COUNT(*) AS total FROM $tabla WHERE $campo = :dato AND idProf != :idProf";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['dato' => $dato, 'idProf' => $idProf]);

        // Obtenemos el resultado de la consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el resultado es mayor que 0, el campo existe
        return $resultado['total'] > 0;

    } catch (PDOException $e) {
        // Manejo del error - mostramos el posible error
        $msgresultado = '<div class="alert alert-danger">' .
                "Error al obtener el usuario en la Base de Datos!!. $e . :)" . '</div>';
        return false; // Devolver false en caso de error
    }
}

// Funcion para verificar si existe campo y excluyendo por idprof
function verificarCampoLibro($conexion, $campo, $tabla, $dato, $idEjemplar) {
    try {
        // Consulta para verificar si el campo existe en la tabla especificada
        $sql = "SELECT COUNT(*) AS total FROM $tabla WHERE $campo = :dato AND idEjemplar != :idEjemplar";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['dato' => $dato, 'idEjemplar' => $idEjemplar]);
        var_dump("ejecuto: ".$idEjemplar);

        // Obtenemos el resultado de la consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Resultado en libro";

        // Si el resultado es mayor que 0, el campo existe
        return $resultado['total'] > 0;

    } catch (PDOException $e) {
        // Manejo del error - mostramos el posible error
        $msgresultado = '<div class="alert alert-danger">' .
                "Error al obtener el libro en la Base de Datos!!. $e . :)" . '</div>';
        return false; // Devolver false en caso de error
    }
}
