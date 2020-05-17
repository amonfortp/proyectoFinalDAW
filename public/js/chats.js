document.getElementById("mercure-message-input").focus();
window.scrollTo(0, document.body.scrollHeight);
document.getElementById("controlChat").value = document.getElementById(
  "usu2"
).value;

function ordenarId() {
  var id1 = document.getElementById("usu1").value;
  var id2 = document.getElementById("usu2").value;

  if (Number(id1) > Number(id2)) {
    return id2 + "" + id1;
  } else {
    return id1 + "" + id2;
  }
}

const _receiver = document.getElementById("mercure-content-receiver");
const _messageInput = document.getElementById("mercure-message-input");
const _sendForm = document.getElementById("mercure-message-form");

const sendMessage = (message) => {
  if (message.trim() === "") {
    return;
  }

  var time = new Date();
  var s;

  if (time.getMinutes() < 10) {
    s = "0" + time.getMinutes();
  } else {
    s = time.getMinutes();
  }

  if (document.getElementById("numPubli").value != 0) {
    var idPubli = document.getElementById("publi1").value;
  } else {
    var idPubli = 0;
  }

  fetch(_sendForm.action, {
    method: _sendForm.method,
    body:
      "message=" +
      message +
      "&usuario2id=" +
      document.getElementById("usu2").value +
      "&time=" +
      time.getHours() +
      ":" +
      s +
      "&numPubli=" +
      document.getElementById("numPubli").value +
      "&idPubli=" +
      idPubli,
    headers: new Headers({
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    }),
  }).then(() => {
    _messageInput.value = "";
  });
};

_sendForm.onsubmit = (evt) => {
  sendMessage(_messageInput.value);

  evt.preventDefault();
  return false;
};

if (document.getElementById("numPubli").value != 0) {
  var idPubli = document.getElementById("publi1").value;
  var tituloPubli = document.getElementById("publi2").value;
  var mensajePubli =
    `Me interesa su publicación - <a href="/publicacion/` +
    idPubli +
    `">` +
    tituloPubli +
    `</a>`;
  sendMessage(mensajePubli);
  document.getElementById("numPubli").value = 0;
}

const urlGenerico = new URL("http://192.168.1.63:3000/.well-known/mercure");
urlGenerico.searchParams.append(
  "topic",
  "http://chat.monbarter/" + ordenarId()
);
const eventSource = new EventSource(urlGenerico, { withCredentials: true });
eventSource.onmessage = (evt) => {
  const data = JSON.parse(evt.data);
  if (!data.message || !data.user) {
    return;
  }

  if (data.id != document.getElementById("usu1").value) {
    _receiver.insertAdjacentHTML(
      "beforeend",
      `
    <div class="message" align="right">${data.user}: ${data.message} ${data.time} | Mercure</div>`
    );
  } else {
    _receiver.insertAdjacentHTML(
      "beforeend",
      `
    <div class="message">${data.user}: ${data.message} ${data.time} | Mercure</div>`
    );
  }

  document.getElementById("mercure-message-input").focus();
  window.scrollTo(0, document.body.scrollHeight);
};
