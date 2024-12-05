/** Script .js para Tarea Online 2 DWES */
//Función para que nos muestre el año actual
window.addEventListener("load", () => {
  const currentDate = new Date();
  anioCurso.innerText = currentDate.getFullYear();
});

// Animacion a la imagen principal Biblioteca
const imagen = document.getElementById("imagen");
const container = document.getElementById("imagenContainer");

// Al pasar el ratón por el login crea una pequeña animación en la imagen del título
container.addEventListener("mouseover", () => {
  imagen.style.transform = "scale(1.1)";
});

container.addEventListener("mouseout", () => {
  imagen.style.transform = "scale(1)";
});
