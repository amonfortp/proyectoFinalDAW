const url = new URL("http://192.168.1.63:3000/.well-known/mercure");
url.searchParams.append(
  "topic",
  "http://monbarter/" + document.getElementById("idUsuTopic").value
);
const eventSource = new EventSource(url, { withCredentials: true });
eventSource.onmessage = (evt) => {
  const data = JSON.parse(evt.data);

  if (document.getElementById("controlChat").value != data.id) {
    document.getElementById("chatBtn").style.backgroundColor = "#cc0000";
  }
};
