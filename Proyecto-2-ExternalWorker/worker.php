<?php
// ======================================================
// EXTERNAL WORKER - PELUDITOS PET CHILE
// Conexión Flowable + Servicio Web Jakarta REST
// Ejecutar: php worker.php
// ======================================================

// FLOWABLE
$flowableUrl = getenv("PELUDITOS_FLOWABLE_URL") ?: "http://127.0.0.1:8080/flowable-rest";
$usuario = getenv("PELUDITOS_FLOWABLE_USER") ?: "rest-admin";
$contrasena = getenv("PELUDITOS_FLOWABLE_PASSWORD") ?: "test";
$workerId = getenv("PELUDITOS_WORKER_ID") ?: "peluditos-worker-1";

// SERVICIO WEB
$urlServicio = getenv("PELUDITOS_WS_URL") ?: "http://127.0.0.1:8081/jakarta";

// TOPICOS DEL BPMN (modelo to-be+fidelizacion)
$topicos = [
    "actualizar datos de ultima consulta"
];

// Falta implementar (necesitan endpoints de escritura en el servicio):
// "registrar resultado lead"
// "registrar datos campaña iniciada"
// "registrar si cliente muestra interes en la oferta"

// REINTENTOS Y ARCHIVO DE ERRORES
$reintentos = 3;
$archivoLog = __DIR__ . "/worker.log";

// FUNCION PARA MOSTRAR Y GUARDAR MENSAJES
function escribirLog($mensaje)
{
    global $archivoLog;

    $linea = "[" . date("Y-m-d H:i:s") . "] " . $mensaje . "\n";

    echo $linea;
    file_put_contents($archivoLog, $linea, FILE_APPEND);
}

// FUNCION PARA COMUNICARSE CON FLOWABLE
function solicitarFlowable($metodo, $ruta, $datos = null)
{
    global $flowableUrl, $usuario, $contrasena;

    $opciones = [
        "http" => [
            "method" => $metodo,
            "header" =>
                "Authorization: Basic " .
                base64_encode("$usuario:$contrasena") . "\r\n" .
                "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n",
            "ignore_errors" => true,
            "timeout" => 15
        ]
    ];

    if ($datos !== null) {
        $opciones["http"]["content"] = json_encode(
            $datos,
            JSON_THROW_ON_ERROR
        );
    }

    $contexto = stream_context_create($opciones);

    $respuesta = @file_get_contents(
        $flowableUrl . $ruta,
        false,
        $contexto
    );

    $codigo = 0;

    foreach ($http_response_header ?? [] as $cabecera) {
        if (preg_match('/^HTTP\/\S+\s+(\d+)/', $cabecera, $coincidencia)) {
            $codigo = (int) $coincidencia[1];
        }
    }

    if ($respuesta === false || $codigo < 200 || $codigo >= 300) {
        throw new RuntimeException(
            "Error HTTP $codigo: " . ($respuesta ?: "Sin respuesta")
        );
    }

    return $respuesta !== ""
        ? json_decode($respuesta, true, 512, JSON_THROW_ON_ERROR)
        : [];
}

// FUNCION PARA CONSULTAR EL SERVICIO WEB
function consultarServicio($ruta)
{
    global $urlServicio;

    $opciones = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n",
            "ignore_errors" => true,
            "timeout" => 10
        ]
    ];

    $contexto = stream_context_create($opciones);

    $respuesta = @file_get_contents(
        $urlServicio . $ruta,
        false,
        $contexto
    );

    $codigo = 0;

    foreach ($http_response_header ?? [] as $cabecera) {
        if (preg_match('/^HTTP\/\S+\s+(\d+)/', $cabecera, $coincidencia)) {
            $codigo = (int) $coincidencia[1];
        }
    }

    if ($respuesta === false || $codigo < 200 || $codigo >= 300) {
        throw new RuntimeException(
            "Error al consultar el servicio web (HTTP $codigo)"
        );
    }

    $datos = json_decode($respuesta, true);

    if (!is_array($datos)) {
        throw new RuntimeException(
            "El servicio web devolvió una respuesta inválida"
        );
    }

    return $datos;
}

// FUNCION PARA BUSCAR UN CLIENTE
// Por ahora el servicio solo tiene GET /clientes (lista todos),
// asi que se busca el id_lead dentro de la lista.
function buscarCliente($idLead)
{
    $clientes = consultarServicio("/clientes");

    foreach ($clientes as $cliente) {
        if ((int) $cliente["id_lead"] === $idLead) {
            return $cliente;
        }
    }

    throw new RuntimeException(
        "No existe el cliente con id_lead $idLead"
    );
}

// FUNCION PARA AVISAR A FLOWABLE QUE LA TAREA FALLO
function reportarFallo($trabajo, $mensaje)
{
    global $workerId, $reintentos;

    $quedan = ($trabajo["retries"] ?? $reintentos) - 1;

    if ($quedan < 0) {
        $quedan = 0;
    }

    solicitarFlowable(
        "POST",
        "/external-job-api/acquire/jobs/" .
            rawurlencode($trabajo["id"]) .
            "/fail",
        [
            "workerId" => $workerId,
            "errorMessage" => $mensaje,
            "errorDetails" => $mensaje,
            "retries" => $quedan,
            "retryTimeout" => "PT30S"
        ]
    );

    return $quedan;
}

// INICIO DEL WORKER
echo "========================================\n";
echo "EXTERNAL WORKER - PELUDITOS PET CHILE\n";
echo "========================================\n";
echo "Servicio web: $urlServicio\n";
echo "Tópicos: " . implode(", ", $topicos) . "\n";
echo "Worker: $workerId\n";
echo "Presiona Ctrl+C para detenerlo.\n\n";

// CICLO PRINCIPAL
while (true) {

    try {

        // BUSCAR TRABAJOS DE CADA TOPICO
        $trabajos = [];

        foreach ($topicos as $tema) {

            $encontrados = solicitarFlowable(
                "POST",
                "/external-job-api/acquire/jobs",
                [
                    "topic" => $tema,
                    "lockDuration" => "PT30S",
                    "numberOfTasks" => 1,
                    "workerId" => $workerId
                ]
            );

            foreach ($encontrados as $encontrado) {
                $trabajos[] = $encontrado;
            }
        }

        foreach ($trabajos as $trabajo) {

            if (empty($trabajo["id"])) {
                continue;
            }

            $idTrabajo = $trabajo["id"];

            echo "----------------------------------------\n";
            escribirLog("Tarea recibida: $idTrabajo");

            try {

                // RECUPERAR VARIABLES DE FLOWABLE
                $variables = [];

                foreach ($trabajo["variables"] ?? [] as $variable) {
                    $variables[$variable["name"]] = $variable["value"] ?? null;
                }

                if (!isset($variables["id_lead"])) {
                    throw new RuntimeException(
                        "Flowable no entregó la variable id_lead"
                    );
                }

                $idLead = filter_var(
                    $variables["id_lead"],
                    FILTER_VALIDATE_INT
                );

                if ($idLead === false || $idLead <= 0) {
                    throw new RuntimeException("id_lead inválido");
                }

                echo "ID del cliente: $idLead\n";

                // CONSULTAR CLIENTE EN EL SERVICIO WEB
                $cliente = buscarCliente($idLead);

                echo "Cliente encontrado: " .
                    $cliente["nombre_cliente"] . "\n";

                echo "Contacto: " .
                    $cliente["contacto_cliente"] . "\n";

                echo "Canal: " .
                    $cliente["canal_origen"] . "\n";

                // COMPLETAR TAREA SOLO SI LA CONSULTA FUE EXITOSA
                solicitarFlowable(
                    "POST",
                    "/external-job-api/acquire/jobs/" .
                        rawurlencode($idTrabajo) .
                        "/complete",
                    [
                        "workerId" => $workerId,
                        "variables" => []
                    ]
                );

                escribirLog("Tarea completada correctamente en Flowable.");

            } catch (Throwable $error) {

                escribirLog(
                    "ERROR AL PROCESAR TAREA: " . $error->getMessage()
                );

                // AVISAR A FLOWABLE PARA QUE REINTENTE
                try {

                    $quedan = reportarFallo($trabajo, $error->getMessage());

                    escribirLog("Fallo reportado a Flowable. Reintentos restantes: $quedan");

                } catch (Throwable $errorFallo) {

                    escribirLog(
                        "No se pudo reportar el fallo a Flowable: " .
                        $errorFallo->getMessage()
                    );
                }
            }
        }

    } catch (Throwable $error) {

        escribirLog("ERROR DE FLOWABLE: " . $error->getMessage());
    }

    sleep(5);
}