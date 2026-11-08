
/* ==========================================
   PELUDITOS PET CHILE
   SISTEMA DE GESTIÓN DE CLIENTES Y TAREAS
   ========================================== */

// ==========================================
// REGISTRO DE CLIENTES POTENCIALES
// ==========================================

const formulario = document.getElementById("form-lead");

if (formulario) {
    formulario.addEventListener("submit", async function(event) {
        event.preventDefault();

        const nombre = document.getElementById("nombre_cliente").value.trim();
        const contacto = document.getElementById("contacto_cliente").value.trim();
        const canal = document.getElementById("canal_origen").value;

        if (!nombre || !/^[0-9]{8}$/.test(contacto) || !canal) {
            alert("Complete correctamente todos los campos.");
            return;
        }

        const boton = formulario.querySelector('button[type="submit"]');
        boton.disabled = true;

        try {
            const datos = new URLSearchParams({
                nombre_cliente: nombre,
                contacto_cliente: contacto,
                canal_origen: canal
            });

            const respuesta = await fetch("api/registrar_lead.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: datos.toString()
            });

            const resultado = await respuesta.json();

            if (!respuesta.ok || resultado.estado !== "correcto") {
                throw new Error(
                    resultado.mensaje || "No se pudo registrar el cliente."
                );
            }

                const idProceso = resultado.flowable?.process_instance_id;

                let mensaje = resultado.mensaje +
                    "\n\nID del cliente: " + resultado.id_lead;

                if (idProceso) {
                    mensaje += "\nID del proceso Flowable: " + idProceso;
                }

                alert(mensaje);

            formulario.reset();

        } catch (error) {
            alert("Error al registrar el cliente: " + error.message);
        } finally {
            boton.disabled = false;
        }
    });
}


// ==========================================
// SEGUIMIENTOS COMERCIALES
// ==========================================

const tablaSeguimientos =
    document.getElementById("tabla-seguimientos");

async function cargarSeguimientos() {
    if (!tablaSeguimientos) return;

    try {
        const respuesta = await fetch("api/listar_seguimientos.php", {
            cache: "no-store"
        });

        const resultado = await respuesta.json();

        if (!respuesta.ok || resultado.estado !== "correcto") {
            throw new Error(
                resultado.mensaje || "No se pudieron cargar los seguimientos."
            );
        }

        tablaSeguimientos.replaceChildren();

        if (resultado.seguimientos.length === 0) {
            const fila = tablaSeguimientos.insertRow();
            const celda = fila.insertCell();

            celda.colSpan = 5;
            celda.textContent = "No hay seguimientos registrados.";
            return;
        }

        resultado.seguimientos.forEach(function(seguimiento) {
            const fila = tablaSeguimientos.insertRow();

            fila.dataset.idSeguimiento = seguimiento.id_seguimiento;

            fila.insertCell().textContent = seguimiento.nombre_cliente;
            fila.insertCell().textContent = seguimiento.nombre_mascota;
            fila.insertCell().textContent = seguimiento.consulta;

            const celdaEstado = fila.insertCell();
            celdaEstado.textContent = seguimiento.resultado_consulta;

            const celdaAcciones = fila.insertCell();

            const selector = document.createElement("select");

            const opcionInicial = document.createElement("option");
            opcionInicial.value = "";
            opcionInicial.textContent = "Seleccione un resultado";
            opcionInicial.disabled = true;
            opcionInicial.selected = true;
            selector.appendChild(opcionInicial);

            const estados = [
                "Venta Concretada",
                "Venta No Concretada",
                "Pendiente"
            ];

            estados.forEach(function(estado) {
                const opcion = document.createElement("option");
                opcion.value = estado;
                opcion.textContent = estado;
                selector.appendChild(opcion);
            });

            const botonGuardar = document.createElement("button");
            botonGuardar.type = "button";
            botonGuardar.textContent = "Guardar";

            botonGuardar.addEventListener("click", async function() {
                const nuevoEstado = selector.value;

                if (!estados.includes(nuevoEstado)) {
                    alert("Debe seleccionar un resultado válido.");
                    return;
                }

                botonGuardar.disabled = true;

                try {
                    const datos = new URLSearchParams({
                        id_seguimiento: seguimiento.id_seguimiento,
                        resultado_consulta: nuevoEstado
                    });

                    const respuesta = await fetch(
                        "api/actualizar_seguimiento.php",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: datos.toString()
                        }
                    );

                    const resultado = await respuesta.json();

                    if (!respuesta.ok || resultado.estado !== "correcto") {
                        throw new Error(
                            resultado.mensaje ||
                            "No se pudo actualizar el seguimiento."
                        );
                    }

                    celdaEstado.textContent = resultado.resultado_consulta;
                    seguimiento.resultado_consulta =
                        resultado.resultado_consulta;

                    selector.selectedIndex = 0;

                    alert(
                        "Seguimiento actualizado correctamente en MariaDB.\n\n" +
                        "Cliente: " + seguimiento.nombre_cliente + "\n" +
                        "Mascota: " + seguimiento.nombre_mascota + "\n" +
                        "Nuevo estado: " + resultado.resultado_consulta
                    );

                } catch (error) {
                    alert(
                        "Error al actualizar el seguimiento: " +
                        error.message
                    );
                } finally {
                    botonGuardar.disabled = false;
                }
            });

            celdaAcciones.appendChild(selector);
            celdaAcciones.appendChild(botonGuardar);
        });

    } catch (error) {
        console.error("Error al cargar seguimientos:", error);

        tablaSeguimientos.replaceChildren();

        const fila = tablaSeguimientos.insertRow();
        const celda = fila.insertCell();

        celda.colSpan = 5;
        celda.textContent =
            "No se pudieron cargar los seguimientos. Intente nuevamente.";
    }
}

cargarSeguimientos();


// ==========================================
// FIDELIZACIÓN DE CLIENTES
// CONEXIÓN REAL CON MARIADB
// ==========================================

const tablaFidelizacion =
    document.getElementById("tabla-fidelizacion");

async function cargarFidelizacion() {
    if (!tablaFidelizacion) return;

    try {
        const respuesta = await fetch("api/gestionar_fidelizacion.php", {
            cache: "no-store"
        });

        const datos = await respuesta.json();

        if (!respuesta.ok || datos.estado !== "correcto") {
            throw new Error(
                datos.mensaje || "No se pudieron cargar las campañas."
            );
        }

        tablaFidelizacion.replaceChildren();

        if (datos.campanas.length === 0) {
            const fila = tablaFidelizacion.insertRow();
            const celda = fila.insertCell();

            celda.colSpan = 6;
            celda.textContent = "No hay campañas registradas.";
            return;
        }

        datos.campanas.forEach(function(campana) {
            const fila = tablaFidelizacion.insertRow();

            fila.dataset.idFidelizacion = campana.id_fidelizacion;

            fila.insertCell().textContent = campana.nombre_cliente;
            fila.insertCell().textContent = campana.nombre_mascota;

            const fecha = campana.fecha_evento.split("-");
            fila.insertCell().textContent =
                fecha[2] + "-" + fecha[1] + "-" + fecha[0];

            fila.insertCell().textContent = campana.tipo_campana;

            const celdaEstado = fila.insertCell();
            celdaEstado.textContent = campana.estado_envio;

            const celdaAcciones = fila.insertCell();

            const selector = document.createElement("select");

            const opcionInicial = document.createElement("option");
            opcionInicial.value = "";
            opcionInicial.textContent = "Seleccione un estado";
            opcionInicial.disabled = true;
            opcionInicial.selected = true;
            selector.appendChild(opcionInicial);

            const estados = ["Programado", "Enviado"];

            estados.forEach(function(estado) {
                const opcion = document.createElement("option");
                opcion.value = estado;
                opcion.textContent = estado;
                selector.appendChild(opcion);
            });

            const botonGuardar = document.createElement("button");
            botonGuardar.type = "button";
            botonGuardar.textContent = "Guardar";

            botonGuardar.addEventListener("click", async function() {
                const nuevoEstado = selector.value;

                if (!estados.includes(nuevoEstado)) {
                    alert("Debe seleccionar un estado válido.");
                    return;
                }

                botonGuardar.disabled = true;

                try {
                    const datos = new URLSearchParams({
                        id_fidelizacion: campana.id_fidelizacion,
                        estado_envio: nuevoEstado
                    });

                    const respuesta = await fetch(
                        "api/gestionar_fidelizacion.php",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: datos.toString()
                        }
                    );

                    const resultado = await respuesta.json();

                    if (!respuesta.ok || resultado.estado !== "correcto") {
                        throw new Error(
                            resultado.mensaje ||
                            "No se pudo actualizar la campaña."
                        );
                    }

                    celdaEstado.textContent = resultado.estado_envio;
                    campana.estado_envio = resultado.estado_envio;

                    selector.selectedIndex = 0;

                    alert(
                        "Campaña actualizada correctamente en MariaDB.\n\n" +
                        "Cliente: " + campana.nombre_cliente + "\n" +
                        "Mascota: " + campana.nombre_mascota + "\n" +
                        "Nuevo estado: " + resultado.estado_envio
                    );

                } catch (error) {
                    alert(
                        "Error al actualizar la campaña: " +
                        error.message
                    );
                } finally {
                    botonGuardar.disabled = false;
                }
            });

            celdaAcciones.appendChild(selector);
            celdaAcciones.appendChild(botonGuardar);
        });

    } catch (error) {
        console.error("Error al cargar fidelización:", error);

        tablaFidelizacion.replaceChildren();

        const fila = tablaFidelizacion.insertRow();
        const celda = fila.insertCell();

        celda.colSpan = 6;
        celda.textContent =
            "No se pudieron cargar las campañas. Intente nuevamente.";
    }
}

cargarFidelizacion();
