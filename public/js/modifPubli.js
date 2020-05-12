var cuentaEtiquetas = [];
var cuentaImagenes = 0;
var maxCaracteres = 255;
var imgExist = document.getElementsByClassName("imgExist");

for (let i = 0; i < imgExist.length; i++) {
  imgExist[i].onclick = function () {
    let check = document.getElementById("delete/" + i);

    if (!check.checked) {
      check.checked = true;
    } else {
      check.checked = false;
    }
  };
}

document.getElementById("maxCaract").innerHTML =
  maxCaracteres - document.getElementById("descripcion").value.length;

activarSpan();
activarImg();

function activarSpan() {
  var pEtiqueta = document.getElementById("verEtiquetas");
  var allEtiquetas = document.getElementById("allEtiquetas");

  cuentaEtiquetas = allEtiquetas.value.split("/");
  if (cuentaEtiquetas.length >= 5) {
    document.getElementById("addEtiqueta").disabled = true;
  }
  if (cuentaEtiquetas.length < 5) {
    document.getElementById("addEtiqueta").disabled = false;
  }

  var span = document.getElementsByClassName("etiqueta");

  for (let i = 0; i < span.length; i++) {
    span[i].onclick = function () {
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        if (Number.parseInt(this.id) == i) {
          cuentaEtiquetas.splice(i, 1);
        }
      }

      allEtiquetas.value = cuentaEtiquetas.join("/");

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
}

function activarImg() {}

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

    pEtiqueta.appendChild(span);
    span.innerHTML = etiqueta;
    span.classList.add("etiqueta");

    if (cuentaEtiquetas.length == 0) {
      aux = etiqueta;
    } else {
      aux += "/" + etiqueta;
    }

    allEtiquetas.value = aux;
    inputEtiqueta.value = "";

    cuentaEtiquetas = allEtiquetas.value.split("/");

    console.log(allEtiquetas.value);
    console.log(cuentaEtiquetas);

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

      this.remove();

      pEtiqueta = document.getElementById("verEtiquetas");
      console.log(pEtiqueta);
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        pEtiqueta.childNodes[i].id = i;
      }

      if (cuentaEtiquetas.length < 5) {
        document.getElementById("addEtiqueta").disabled = false;
      }
    };
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

document.getElementById("descripcion").onkeyup = function () {
  var text = document.getElementById("descripcion").value;
  document.getElementById("maxCaract").innerHTML = maxCaracteres - text.length;
};
