<!--Consulta para poner fin a un prestamo de un libro-->
<?php
// Procesar la devolución del libro
if (isset($_POST['finalizarPrestamo'])) {

    //Cogemos las variables
    $idEjemplar = $_POST['idEjemplar'];
    $isbn = $_POST['isbn'];
    $fechaFin = date('Y-m-d'); // Fecha actual en formato MySQL

    try {

        // Actualizar la fecha de finalización del préstamo
        $sqlPrestamo = "UPDATE prestamos 
                              SET Fecha_Fin = :Fecha_Fin 
                              WHERE IdEjemplar = :IdEjemplar AND ISBN = :ISBN AND Fecha_Fin IS NULL";
        $resultadoPres = $conexion->prepare($sqlPrestamo);
        $resultadoPres->execute([
            'Fecha_Fin' => $fechaFin,
            'IdEjemplar' => $idEjemplar,
            'ISBN' => $isbn
        ]);

        //Si hay datos en la consulta y se ha metido la fecha de fin de prestamo
        if ($resultadoPres) {
            // Mostramos mensaje de confirmacion prestamo
            $msgresultadoPres = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente(actualizar fecha de fin prestamo)!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';

            // Actualizar el estado del libro a 0 (libre)
            $sqlLibro = "UPDATE libros 
                           SET estado = 0 
                           WHERE IdEjemplar = :IdEjemplar AND ISBN = :ISBN";
            $resultadoLibro = $conexion->prepare($sqlLibro);
            $resultadoLibro->execute([
                'IdEjemplar' => $idEjemplar,
                'ISBN' => $isbn
            ]);

            // si la consulta para actualizar estado en libros
            if ($resultadoLibro) {
                // Mostramos mensaje de confirmacion libro
                $msgresultadoLibro = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente(actualizar estado en libros)!!" . '<img width="50" height="50" src="https://img.icons8.com/clouds/100/ok-hand.png" alt="ok-hand"/></div>';

            } else {
                // Mensaje no se ha podido actualizar libro
                $msgresultadoLibro = '<div class="alert alert-danger">' .
                    "No se puede actualizar estado libro!! :( (" . $ex->getMessage() . ')</div>';
                //die(); 
            }
        } else {
            // Mensaje no se ha podido actualizar prestamo
            $msgresultadoPres = '<div class="alert alert-danger">' .
                "No se puedo devolver el libro!! :( (" . $ex->getMessage() . ')</div>';
            //die(); 
        }
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos(finprestamo)!!" . '<img class="mx-2" width="50" height="50" src="https://img.icons8.com/cute-clipart/64/error.png" alt="error"/></div>';
        //die();
    }
}
?>

<!-- Ventana Modal -->
<div class="modal fade" id="modalDevolucion" tabindex="-1" aria-labelledby="modalDevolucionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDevolucionLabel">Devolver Libro - Fin Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="infoLibro"></p>
                <p>¿Está seguro de que desea finalizar el préstamo del libro <?php echo $deltitulo; ?>?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="">
                    <input type="hidden" name="idEjemplar" id="idEjemplar">
                    <input type="hidden" name="isbn" id="isbn">
                    <button type="submit" name="finalizarPrestamo" class="btn btn-success">Aceptar</button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Rechazar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Script para controlar el modal para devolucion prestamo
    document.addEventListener('DOMContentLoaded', function() {
        // Utilizamos el dom para los elementos
        const modalDevolucion = document.getElementById('modalDevolucion');
        modalDevolucion.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Botón que activa el modal
            const idEjemplar = button.getAttribute('data-id');
            const isbn = button.getAttribute('data-isbn');
            const titulo = button.getAttribute('data-titulo');

            // Rellenar la información del libro en el modal
            const infoLibro = modalDevolucion.querySelector('#infoLibro');
            infoLibro.textContent = `Título: ${titulo}, ISBN: ${isbn}`;

            // Rellenar los campos ocultos en el formulario
            modalDevolucion.querySelector('#idEjemplar').value = idEjemplar;
            modalDevolucion.querySelector('#isbn').value = isbn;
        });
    });
</script>