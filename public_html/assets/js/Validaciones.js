/* =========================
   LIMPIEZA GENERAL
========================= */

function limpiarEspacios(valor) {
    return valor.replace(/\s+/g, ' ').trim();
}

/* =========================
   VALIDACIONES BÁSICAS
========================= */

function validarLongitud(valor, min, max) {
    return valor.length >= min && valor.length <= max;
}

/* =========================
   MONTO DONACIÓN
========================= */

function validarMonto(monto) {
    return !isNaN(monto) && Number(monto) > 0;
}

/* =========================
   CUANDO EL DOM ESTÁ LISTO
========================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =========================
       BOTONES MONTO
    ========================= */

    const botonesMonto = document.querySelectorAll(".monto-btn");
    const inputMonto = document.getElementById("montoDonacion");

    botonesMonto.forEach(btn => {
        btn.addEventListener("click", function () {

            // quitar clase activa a todos
            botonesMonto.forEach(b => b.classList.remove("active"));

            // activar el seleccionado
            this.classList.add("active");

            // colocar valor en input
            inputMonto.value = this.dataset.valor;
        });
    });


    /* =========================
       ENVÍO FORMULARIO
    ========================= */

    const formulario = document.getElementById("formulario-monto");

    formulario.addEventListener("submit", function (e) {
        e.preventDefault();

        const monto = inputMonto.value;

        if (!validarMonto(monto)) {
            alert("Selecciona un monto válido");
            return;
        }


    });


    /* =========================
       CAPTURAR PROYECTO EN URL
    ========================= */

    const params = new URLSearchParams(window.location.search);
    const project = params.get("project");

    if (!project) return;

    const PROJECTS = {
        educacion: "Educación Transformadora",
        desarrollo: "Desarrollo Comunitario",
        juventud: "Juventud con Futuro"
    };

    const nombreProyecto = PROJECTS[project];

    if (nombreProyecto) {

        const titulo = document.createElement("div");

        titulo.innerHTML = `
            <div class="alert alert-success text-center mb-4">
                <h4>Donación para:</h4>
                <strong>${nombreProyecto}</strong>
            </div>
        `;

        const form = document.getElementById("formulario-monto");

        if (form) {
            form.parentNode.insertBefore(titulo, form);
        }
    }

});