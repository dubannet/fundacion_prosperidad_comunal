// /public/assets/js/app.js

// Las variables globales URL_BASE y PROYECTO_ID_URL están definidas en donar.php
/**
 * PUNTO DE ENTRADA DESDE EL FORMULARIO
 * Esta función DEBE llamarse exactamente igual que en el onsubmit del HTML
 */
function iniciarDonacionAutomatica() {
  console.log("Iniciando proceso de donación..."); 

  const inputMontoElement = document.getElementById("montoDonacion");

  if (!inputMontoElement) {
    console.error("Error: No se encontró el input 'montoDonacion'");
    return;
  }

  // Decidir flujo
  if (typeof PROYECTO_ID_URL !== "undefined" && PROYECTO_ID_URL !== null) {
    iniciarDonacionProyecto(PROYECTO_ID_URL, inputMontoElement);
  } else {
    iniciarDonacionGenerica(inputMontoElement);
  }
}
/**
 * =========================================================
 * 1. FUNCIÓN PRINCIPAL: INICIAR DONACIÓN GENÉRICA (Fundación)
 * =========================================================
 * @param {HTMLElement} inputElement - El campo de input del monto.
 */
async function iniciarDonacionGenerica(inputElement) {
  const proyectoId = null;
  await validarYManejarPago(inputElement, proyectoId);
}

/**
 * =========================================================
 * 2. FUNCIÓN ESPECÍFICA: INICIAR DONACIÓN A PROYECTO
 * =========================================================
 * @param {number} proyectoId - El ID del proyecto.
 * @param {HTMLElement} inputElement - El campo de input del monto.
 */
async function iniciarDonacionProyecto(proyectoId, inputElement) {
  if (!proyectoId || isNaN(proyectoId)) {
    alert("Error: ID de proyecto no válido para la donación.");
    return;
  }
  await validarYManejarPago(inputElement, proyectoId);
}

/**
 * =========================================================
 * 3. FUNCIÓN AUXILIAR: VALIDAR MONTO Y PREPARAR PAGO
 * =========================================================
 * @param {HTMLElement} inputElement - El campo de input del monto.
 */
async function validarYManejarPago(inputElement, proyectoId) {
  const cont = document.getElementById("contenedor-widget");
  const formularioMonto = document.getElementById("formulario-monto");
  const botonDonar = document.getElementById("botonDonar");

  const amountInPesos = parseInt(inputElement.value, 10);

  if (isNaN(amountInPesos) || amountInPesos < 1000) {
    alert("Por favor, ingrese un monto válido mayor o igual a $1.500 COP.");
    // No necesitamos modificar el UI aquí, solo en el finally o error.
    return;
  }

  // Pasamos el ELEMENTO input para poder limpiarlo más tarde si es necesario
  await manejarInicioDePago(
    cont,
    formularioMonto,
    botonDonar,
    inputElement,
    amountInPesos,
    proyectoId
  );
}

/**
 * =========================================================
 * 4. FUNCIÓN CENTRAL: MANEJAR INICIO DE PAGO (Lógica Wompi)
 * =========================================================
 * @param {HTMLElement} inputElement - El campo de input para acceder a su valor.
 */
async function manejarInicioDePago(
  cont,
  formularioMonto,
  botonDonar,
  inputElement,
  amountInPesos,
  proyectoId
) {
  const currency = "COP";
  const amountInCents = amountInPesos * 100;

  // 1. Mostrar carga y deshabilitar UI (Lógica original del spinner)
  formularioMonto.style.display = "none";
  cont.style.display = "block";
  if (botonDonar) botonDonar.disabled = true;
  cont.innerHTML = `<div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Cargando...</span>
                      </div>
                      <p class="mt-2">Cargando datos de Wompi... por favor espere.</p>`;

  try {
    // 2. Llamada al proxy de firma del backend
    const body = new URLSearchParams();
    body.append("amount", amountInCents);
    body.append("currency", currency);

    // ENVÍO DEL ID DEL PROYECTO
    if (proyectoId) {
      body.append("proyecto_id", proyectoId);
    }

    const respuesta = await fetch(URL_BASE + "/api/generar-firma.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: body.toString(),
    });

    const json = await respuesta.json();

    if (json.error || !respuesta.ok) {
      throw new Error(json.error || "Error al obtener la firma del servidor.");
    }

    const firma = json.signature;
    const reference = json.reference;

    // 3. Abrir Wompi Widget
    cont.innerHTML = "";
    cont.style.display = "none";

    const checkout = new WidgetCheckout({
      currency: currency,
      amountInCents: amountInCents,
      reference: reference,
      publicKey: json.publicKey,
      signature: {
        integrity: firma,
      },
    });

    // El callback se ejecuta SIEMPRE que el widget se cierra
    checkout.open(function (result) {
      //console.log("Widget cerrado. Resultado:", result);
      abrirWidgetWompi(result, formularioMonto, botonDonar, inputElement);
    });
  } catch (err) {
    console.error("Error en la donación:", err);
    alert(
      "Ocurrió un error al generar la firma. Revisa la consola y la configuración del backend."
    );

    if (formularioMonto) formularioMonto.style.display = "block";
    if (botonDonar) botonDonar.disabled = false;
    if (cont) cont.style.display = "none";
  } finally {
    if (formularioMonto) formularioMonto.style.display = "block";
    if (botonDonar) botonDonar.disabled = false;
    if (cont) cont.style.display = "none";
    if (inputElement) inputElement.value = "";
  }
}

/**
 * =========================================================
 * 5. FUNCIÓN AUXILIAR: MANEJAR RESULTADO DE WOMPI
 * =========================================================
 * @param {HTMLElement} inputElement - El campo de input del monto para limpiarlo.
 */
function abrirWidgetWompi(result, formularioMonto, botonDonar, inputElement) {
  // Intentamos obtener la transacción si existe
  const transaction = result ? result.transaction : null;

  if (transaction) {
    console.log("Transacción finalizada con estado:", transaction.status);
    // Aquí podrías poner alertas según el estado (APPROVED, DECLINED, etc.)
    window.location.href =
      URL_BASE +
      "/redireccion.php?status=" +
      transaction.status +
      "&ref=" +
      transaction.reference;
    return;
  } else {
    console.log(
      "El widget se cerró sin generar transacción (clic en X o escape)."
    );
  }

  const cont = document.getElementById("contenedor-widget");

  if (cont) {
    cont.style.display = "none";
    cont.innerHTML = "";
  }

  if (formularioMonto) {
    formularioMonto.style.display = "block"; // Volver a mostrar el formulario
  }

  if (botonDonar) {
    botonDonar.disabled = false; // Reactivar el botón
  }

  if (inputElement) inputElement.value = "";
}
