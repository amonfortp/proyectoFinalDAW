var cuentaEtiquetas = [];
var cuentaImagenes = 0;
var maxCaracteres = 255;
var imgExist = document.getElementsByClassName("imgExist");

for (let i = 0; i < imgExist.length; i++) {
  imgExist[i].onclick = function () {
    let check = document.getElementById("delete" + i);

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

function activarSpan() {
  var allEtiquetas = document.getElementById("allEtiquetas");

  cuentaEtiquetas = allEtiquetas.value.split("/");
  if (cuentaEtiquetas.length >= 5) {
    document.getElementById("addEtiqueta").disabled = true;
  }
  if (cuentaEtiquetas.length < 5) {
    document.getElementById("addEtiqueta").disabled = false;
  }

  var span = document.getElementsByClassName("etiqueta");

  console.log(allEtiquetas.value);

  for (let i = 0; i < span.length; i++) {
    span[i].onclick = function () {
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        if (Number.parseInt(this.id) == i) {
          cuentaEtiquetas.splice(i, 1);
        }
      }

      allEtiquetas.value = cuentaEtiquetas.join("/");

      this.remove();

      var span = document.getElementsByClassName("etiqueta");
      console.log(span);
      console.log(cuentaEtiquetas);
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        span[i].id = i;
      }

      if (cuentaEtiquetas.length < 5) {
        document.getElementById("addEtiqueta").disabled = false;
      }
    };
  }
}

document.getElementById("addEtiqueta").onclick = function () {
  var pEtiqueta = document.getElementById("verEtiquetas");
  var inputEtiqueta = document.getElementById("etiquetas");
  var allEtiquetas = document.getElementById("allEtiquetas");

  let aux = allEtiquetas.value;
  let etiqueta = inputEtiqueta.value;

  if (
    etiqueta.replace(/ /g, "").length == 0 ||
    etiqueta.length > 25 ||
    cuentaEtiquetas.length > 5
  ) {
    alert(
      "No se permiten etiquetas vacias, más de 5 o mayores de 25 caracteres"
    );
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

    span.id = cuentaEtiquetas.length - 1;

    if (cuentaEtiquetas.length >= 5) {
      document.getElementById("addEtiqueta").disabled = true;
    }

    console.log(allEtiquetas.value);

    span.onclick = function () {
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        if (Number.parseInt(this.id) == i) {
          cuentaEtiquetas.splice(i, 1);
        }
      }

      allEtiquetas.value = cuentaEtiquetas.join("/");

      this.remove();

      var span = document.getElementsByClassName("etiqueta");
      for (let i = 0; i < cuentaEtiquetas.length; i++) {
        span[i].id = i;
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

  var div = document.createElement("div");
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
