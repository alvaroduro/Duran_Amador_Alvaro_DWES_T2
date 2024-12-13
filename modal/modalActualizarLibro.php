
<!-- Ventana modal muestra datos al actualizar un libro -->
</br>
<button type="button" class="btn btn-success my-2 mx-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Mostrar datos último Libro Actualizado
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Libro actualizado <img src="img/agregarLibro.png" alt="nuevolibro" width="32" height="32"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php valoresfrm(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
