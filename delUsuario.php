<!--Eliminar Usuario-->
<?php 
$msgresultadoEliminar = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProfEliminar'])) {
    $idProfEliminar = $_POST['idProfEliminar'];

    try {
        // Consulta para eliminar al usuario
        $sql = "DELETE FROM profesores WHERE IdProf = :idProf";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['idProf' => $idProfEliminar]);

        // Confirmación de eliminación
        $msgresultadoEliminar = '<div class="alert alert-success">Usuario eliminado correctamente.</div>';
    } catch (PDOException $ex) {
        $msgresultadoEliminar = '<div class="alert alert-danger">Error al eliminar el usuario: ' . $ex->getMessage() . '</div>';
    }
}
?>

<!-- Modal para eliminar el usuario pidiendo confirmacion-->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al siguiente usuario?</p>
                    <ul>
                        <!--Mostramos los datos en la ventana modal-->
                        <li><strong>ID:</strong> <span id="modal-id"></span></li>
                        <li><strong>Nombre:</strong> <span id="modal-nombre"></span></li>
                        <li><strong>Email:</strong> <span id="modal-email"></span></li>
                        <li><strong>Nombre Usuario:</strong> <span id="modal-usuario"></span></li>
                    </ul>
                    <input type="hidden" name="idProfEliminar" id="modal-idProfEliminar">
                </div>
                <div class="modal-footer">
                    <!--Añadimos los botones para aceptar o no la eliminacion-->
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Pasar datos al modal
    var modalEliminar = document.getElementById('modalEliminar');

    //Mostramos la ventana modal al pulsar el boton
    modalEliminar.addEventListener('show.bs.modal', function (event) { 

        // Guardamos los datos del boton en las variables
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var email = button.getAttribute('data-email');
        var usuario = button.getAttribute('data-usuario');

        // Escribimos los datos en el modal
        document.getElementById('modal-id').textContent = id;
        document.getElementById('modal-nombre').textContent = nombre;
        document.getElementById('modal-email').textContent = email;
        document.getElementById('modal-usuario').textContent = usuario;
        document.getElementById('modal-idProfEliminar').value = id;
    });
</script>
