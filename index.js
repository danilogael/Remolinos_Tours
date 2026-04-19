document.querySelector("#formContacto").addEventListener("submit", async (e) => {
    e.preventDefault();

    const datos = new FormData(e.target);

    const res = await fetch("/viaje/viaje/LoginAPI/guardar_mensaje.php", {
        method: "POST",
        body: datos
    });

    const data = await res.json();

    if(data.status === "ok"){
        alert("Mensaje enviado correctamente en breve nos pondremos en contacto contigo.");
        e.target.reset();
    } else {
        alert("Error al enviar el mensaje");
    }
});

