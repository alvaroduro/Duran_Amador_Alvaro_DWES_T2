
<!-- Ventana modal muestra datos al introducir un libro -->
<button type="button" class="btn btn-success my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Mostrar datos último Usuario Introducido
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Usuario añadido  <img src="img/user_login.png" alt="nuevolibro" width="32" height="32"></h1>
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
