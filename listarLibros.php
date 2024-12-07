<?php 
echo "LISTAR LIBROS";
if (isset($_GET['rol'])) {
    $rolUsuario = $_GET['rol'];
} else {
    echo "No se recibió ningún rol.";
}
?>