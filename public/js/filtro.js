document.getElementById("aplicar").onclick = () => {
  window.location.replace(
    "http://192.168.1.63:8000/publicaciones/" +
      document.getElementById("filtros").value
  );
};
