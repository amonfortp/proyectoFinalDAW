$(".collapse").collapse();

document.getElementById("imgPerfil").onchange = function () {
  var labelImg = document.getElementById("labelImg");

  if (document.getElementById("imgPerfil").value != "") {
    labelImg.innerHTML = "Imagen ya escogida";
  } else {
    labelImg.innerHTML = "Escoge tu foto de perfil";
  }
};
