var cuentaEtiquetas = [];
var cuentaImagenes = 0;
var maxCaracteres = 255;

document.getElementById("addEtiqueta").onclick = function () {
  var pEtiqueta = document.getElementById("verEtiquetas");
  var inputEtiqueta = document.getElementById("etiquetas");
  var allEtiquetas = document.getElementById("allEtiquetas");

  let aux = allEtiquetas.value;
  let etiqueta = inputEtiqueta.value;

  if (etiqueta.replace(/ /g, "").length == 0 || etiqueta.length > 25) {
    alert("No se permiten etiquetas vacias o mayores de 25 caracteres");
  } else {
    var span = document.createElement("span");

    if (cuentaEtiquetas.length == 0) {
      aux = etiqueta;
      pEtiqueta.appendChild(span);
      span.innerHTML = etiqueta;
      span.classList.add("etiqueta");
    } else {
      aux += "/" + etiqueta;
      pEtiqueta.appendChild(span);
      span.innerHTML = " / ";
      span = document.createElement("span");
      pEtiqueta.appendChild(span);
      span.innerHTML = etiqueta;
      span.classList.add("etiqueta");
    }

    allEtiquetas.value = aux;
    inputEtiqueta.value = "";

    cuentaEtiquetas = allEtiquetas.value.split("/");

    span.id = cuentaEtiquetas.length - 1;

    if (cuentaEtiquetas.length >= 5) {
      document.getElementById("addEtiqueta").disabled = true;
    }

    span.onclick = function () {
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        if (this.innerHTML == cuentaEtiquetas[i]) {
          cuentaEtiquetas.splice(i, 1);
        }
      }

      allEtiquetas.value = cuentaEtiquetas.join("/");

      if (this.nextSibling) {
        this.nextSibling.remove();
      } else if (this.previousSibling) {
        this.previousSibling.remove();
      }
      this.remove();

      let x = 0;

      for (let i = 0; i < pEtiqueta.childNodes.length; i++) {
        if (pEtiqueta.childNodes[i].innerHTML == cuentaEtiquetas[x]) {
          pEtiqueta.childNodes[i].id = x;
          x++;
        }
      }

      if (cuentaEtiquetas.length < 5) {
        document.getElementById("addEtiqueta").disabled = false;
      }
    };
  }
};

document.getElementById("descripcion").onkeyup = function () {
  var text = document.getElementById("descripcion").value;
  document.getElementById("maxCaract").innerHTML = maxCaracteres - text.length;
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
