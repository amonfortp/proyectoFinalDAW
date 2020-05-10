var cuentaEtiquetas = 0;
var cuentaImagenes = 0;

document.getElementById("addEtiqueta").onclick = function () {
  var pEtiqueta = document.getElementById("verEtiquetas");
  var inputEtiqueta = document.getElementById("etiquetas");
  var allEtiquetas = document.getElementById("allEtiquetas");

  let aux = allEtiquetas.value;
  let etiqueta = inputEtiqueta.value;

  if (etiqueta.replace(/ /g, "").length == 0 || etiqueta.length > 25) {
    alert("No se permiten etiquetas vacias o mayores de 25 caracteres");
  } else {
    span = document.createElement("span");

    if (aux == "") {
      aux = etiqueta;
      let span = document.createElement("span");
      pEtiqueta.appendChild(span);
      span.innerHTML = etiqueta;
      span.classList.add("etiqueta");
    } else {
      aux += "/" + etiqueta;
      let span = document.createElement("span");
      pEtiqueta.appendChild(span);
      span.innerHTML = " / ";
      span = document.createElement("span");
      pEtiqueta.appendChild(span);
      span.innerHTML = etiqueta;
      span.classList.add("etiqueta");
    }

    cuentaEtiquetas++;

    allEtiquetas.value = aux;
    inputEtiqueta.value = "";

    if (cuentaEtiquetas == 5) {
      document.getElementById("addEtiqueta").disabled = true;
    }
  }
};

document.getElementById("addImagen").onclick = function () {
  var divImg = document.getElementById("divImg");
  cuentaImagenes++;

  var auxInput = document.createElement("input");
  auxInput.setAttribute("type", "file");
  auxInput.name = "imgPubli" + cuentaImagenes;
  auxInput.id = cuentaImagenes;
  auxInput.classList.add("custom-file-input");
  auxInput.accept = "image/png, image/jpeg, image/jpg";

  var auxLabel = document.createElement("label");
  auxLabel.htmlFor = "imgPubli" + cuentaImagenes;
  auxLabel.id = cuentaImagenes;
  auxLabel.classList.add("custom-file-label");
  auxLabel.innerHTML =
    "Escoge la foto " + cuentaImagenes + " de tu publicacion";

  div = document.createElement("div");
  div.classList.add("col-12");
  div.classList.add("mb-2");
  div.classList.add("custom-file");
  div.appendChild(auxInput);
  div.appendChild(auxLabel);
  divImg.appendChild(div);

  document.getElementById("numImg").value = cuentaImagenes;

  auxInput.onchange = function () {
    if (auxInput.value != "") {
      auxLabel.innerHTML = "Imagen ya escogida";
    } else {
      auxLabel.innerHTML =
        "Escoge la foto " + auxInput.id + " de tu publicacion";
    }
  };
};
