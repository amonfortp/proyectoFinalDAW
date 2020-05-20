const _sendForm = document.getElementById("formFiltros");

var accion = 0;

document.getElementById("aplicar").onclick = function () {
  accion = 0;
  sendMessage();
};

document.getElementById("guardar").onclick = function () {
  accion = 1;
  sendMessage();
};
document.getElementById("eliminar").onclick = function () {
  accion = 2;
  sendMessage();
};

const sendMessage = () => {
  tipo = document.getElementById("tipoFiltro").value;
  orden = document.getElementById("ordenFiltro").value;
  provincia = document.getElementById("provinciaFiltro").value;
  etiqueta = document.getElementById("etiquetaFiltro").value;
  titulo = document.getElementById("tituloFiltro").value;
  idFiltro = document.getElementById("filtros").value;

  fetch(_sendForm.action, {
    method: _sendForm.method,
    body:
      "accion=" +
      accion +
      "&orden=" +
      orden +
      "&provincia=" +
      provincia +
      "&etiqueta=" +
      etiqueta +
      "&titulo=" +
      titulo +
      "&tipo=" +
      tipo +
      "&idFiltro=" +
      idFiltro,
    headers: new Headers({
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    }),
  });
};

_sendForm.onsubmit = (evt) => {
  sendMessage();

  evt.preventDefault();

  return false;
};