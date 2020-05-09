var pEtiqueta = document.getElementById("verEtiquetas");
var inputEtiqueta = document.getElementById("etiquetas");
var allEtiquetas = document.getElementById("allEtiquetas");

document.getElementById("addEtiqueta").onclick = function () {
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
      console.log("1");
    } else {
      aux += "/" + etiqueta;
      let span = document.createElement("span");
      pEtiqueta.appendChild(span);
      span.innerHTML = " / ";
      span = document.createElement("span");
      pEtiqueta.appendChild(span);
      span.innerHTML = etiqueta;
      span.classList.add("etiqueta");
      console.log("2");
    }

    allEtiquetas.value = aux;
    inputEtiqueta.value = "";

    console.log(allEtiquetas);
  }
};
