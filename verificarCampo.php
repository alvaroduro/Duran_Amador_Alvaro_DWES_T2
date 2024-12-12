<?php
$msgresultado = "";
// Funcion para verificar si existe campo
function verificarCampo($conexion, $campo, $tabla, $dato) {
    try {
        // Consulta para verificar si el campo existe en la tabla especificada
        $sql = "SELECT COUNT(*) AS total FROM $tabla WHERE $campo = :dato";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['dato' => $dato]);

        // Obtenemos el resultado de la consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el resultado es mayor que 0, el campo existe
        return $resultado['total'] > 0;

    } catch (PDOException $e) {
        // Manejo del error - mostramos el posible error
        $msgresultado = '<div class="alert alert-danger">' .
                "Error al obtener el título del libro en la Base de Datos!!. $e . :)" . '</div>';
        return false; // Devolver false en caso de error
    }
}
