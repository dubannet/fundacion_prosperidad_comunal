/* =========================
   LIMPIEZA GENERAL
========================= */

function limpiarEspacios(valor) {
    return valor.replace(/\s+/g, ' ').trim();
}

/* =========================
   VALIDACIONES BÁSICAS
========================= */

function validarNoVacio(valor) {
    return valor.trim().length > 0;
}

function validarLongitud(valor, min, max) {
    return valor.length >= min && valor.length <= max;
}

/* =========================
   PROTECCIÓN BÁSICA (UX)
========================= */

function validarCaracteresPeligrosos(texto) {
    const patron = /['";<>]/g;
    return !patron.test(texto);
}

function validarPalabrasSQL(texto) {
    const sqlKeywords = [
        "select","insert","delete","update","drop",
        "truncate","alter","union","exec","xp_"
    ];

    const lower = texto.toLowerCase();
    return !sqlKeywords.some(keyword => lower.includes(keyword));
}

function validarSQL(texto) {
    return validarCaracteresPeligrosos(texto) && validarPalabrasSQL(texto);
}

/* =========================
   PROTECCIÓN XSS (UX)
========================= */

function validarXSS(texto) {
    const patron = /<[^>]*>/g;
    return !patron.test(texto);
}

/* =========================
   VALIDACIONES DE CAMPOS
========================= */

function validarNombre(nombre) {
    const patron = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,50}$/;
    return patron.test(nombre);
}

function validarEmail(email) {
    const patron = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return patron.test(email);
}

function validarTelefono(tel) {
    const patron = /^[0-9]{7,15}$/;
    return patron.test(tel);
}

function validarTextoLargo(texto) {
    return (
        validarLongitud(texto, 1, 500) &&
        validarSQL(texto) &&
        validarXSS(texto)
    );
}

/* =========================
   MONTO DONACIÓN
========================= */

function validarMonto(monto) {
    return !isNaN(monto) && monto > 0;
}

/* =========================
   BOTONES MONTO
========================= */

document.querySelectorAll(".monto-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        document.querySelectorAll(".monto-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        document.getElementById("monto").value = this.dataset.valor;
    });
});

/* =========================
   MÉTODO DE PAGO
========================= */

document.querySelectorAll(".metodo-pago").forEach(btn => {
    btn.addEventListener("click", function () {
        document.querySelectorAll(".metodo-pago").forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        document.getElementById("metodo").value = this.dataset.metodo;
    });
});

/* =========================
   ENVÍO FORMULARIO
========================= */

document.getElementById("formDonar").addEventListener("submit", function (e) {
    e.preventDefault();

    const nombre = limpiarEspacios(document.getElementById("nombre").value);
    const email = limpiarEspacios(document.getElementById("email").value);
    const telefono = limpiarEspacios(document.getElementById("telefono").value);
    const mensaje = limpiarEspacios(document.getElementById("mensaje").value);
    const monto = document.getElementById("monto").value;
    const metodo = document.getElementById("metodo").value;

    if (!validarMonto(monto)) {
        alert("Selecciona un monto válido");
        return;
    }

    if (!metodo) {
        alert("Selecciona un método de pago");
        return;
    }

    if (!validarNombre(nombre)) {
        alert("Nombre inválido");
        return;
    }

    if (!validarEmail(email)) {
        alert("Correo electrónico inválido");
        return;
    }

    if (telefono && !validarTelefono(telefono)) {
        alert("Teléfono inválido");
        return;
    }

    if (mensaje && !validarTextoLargo(mensaje)) {
        alert("Mensaje inválido");
        return;
    }

    alert("Formulario validado correctamente");

    // Aquí conectar pasarela de pago
    // this.submit();
});


/* =========================
   CAPTURAR PROYECTO EN DONAR.HTML
========================= */

document.addEventListener("DOMContentLoaded", function () {

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

        const form = document.getElementById("formDonar");

        if (form) {
            form.parentNode.insertBefore(titulo, form);
        }

    }

});