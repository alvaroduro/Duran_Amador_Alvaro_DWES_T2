<?php
ob_start(); // Inicia el buffer de salida
require('fpdf/fpdf.php'); // Incluye la biblioteca FPDF
require_once 'config.php'; // Incluye la conexión a la base de datos

class PDF extends FPDF
{
    // Encabezado de la página
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Listado de Libros', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crea un nuevo objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Consulta para obtener los datos
try {
    $sql = "SELECT 
                libros.ISBN, 
                libros.Titulo, 
                libros.Fecha_Publicacion, 
                libros.Editorial, 
                libros.Descripcion, 
                libros.Precio, 
                libros.Autor, 
                libros.Estado 
            FROM libros";
    $resultado = $conexion->prepare($sql);
    $resultado->execute();

    // Crear encabezados de la tabla en el PDF
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 10, 'ISBN', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Titulo', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Publicacion', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Editorial', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Precio', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Estado', 1, 1, 'C');
    
    // Agregar filas de datos al PDF
    $pdf->SetFont('Arial', '', 10);
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(30, 10, $fila['ISBN'], 1);
        $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $fila['Titulo']), 1);
        $pdf->Cell(30, 10, date('m/Y', strtotime($fila['Fecha_Publicacion'])), 1);
        $pdf->Cell(40, 10, iconv('UTF-8', 'ISO-8859-1', $fila['Editorial']), 1);
        $pdf->Cell(20, 10, $fila['Precio'] . chr(128), 1); //Usamos el codigo del € en html
        $estado = ($fila['Estado'] == 0) ? 'Libre' : 'Prestado';
        $pdf->Cell(20, 10, $estado, 1, 1);
    }
} catch (PDOException $e) {
    $pdf->Cell(0, 10, 'Error al obtener datos: ' . $e->getMessage(), 0, 1);
}

// Salida del PDF
ob_end_clean(); // Limpia el buffer de salida
$pdf->Output('I', 'Listado_Libros.pdf'); // Muestra el PDF en el navegador

?>
