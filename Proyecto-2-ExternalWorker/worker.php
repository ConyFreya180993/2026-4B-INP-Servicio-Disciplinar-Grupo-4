<?php
// ======================================================
// EXTERNAL WORKER - PELUDITOS PET CHILE
// Conexión Flowable + MariaDB
// Ejecutar: php worker.php
// ======================================================

// FLOWABLE
$flowableUrl = "http://127.0.0.1:8080/flowable-rest";
$usuario = "rest-admin";
$contrasena = "test";
$tema = "sincronizar-cliente";
$workerId = "peluditos-worker-1";

// MARIADB
$servidor = "127.0.0.1";
$puerto = 3306;
$usuarioBD = "root";
$contrasenaBD = "";
$baseDatos = "peluditos_db";

// CONEXION A MARIADB
$conexion = new mysqli(
    $servidor,
    $usuarioBD,
    $contrasenaBD,
    $baseDatos,
    $puerto
);

if ($conexion->connect_error) {
    die("Error de conexión MariaDB: " . $conexion->connect_error . PHP_EOL);
}

$conexion->set_charset("utf8mb4");

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

// FUNCION PARA BUSCAR CLIENTE EN MARIADB
function buscarCliente($idLead)
{
    global $conexion;

    $sql = "SELECT id_lead, nombre_cliente,
                   contacto_cliente, canal_origen, fecha_registro
            FROM leads
            WHERE id_lead = ?";

    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("i", $idLead);
    $consulta->execute();

    $resultado = $consulta->get_result();
    $cliente = $resultado->fetch_assoc();

    $consulta->close();

    if (!$cliente) {
        throw new RuntimeException(
            "No existe el cliente con id_lead $idLead"
        );
    }

    return $cliente;
}

// INICIO DEL WORKER
echo "========================================\n";
echo "EXTERNAL WORKER - PELUDITOS PET CHILE\n";
echo "========================================\n";
echo "Conectado a MariaDB: $baseDatos\n";
echo "Tema: $tema\n";
echo "Worker: $workerId\n";
echo "Presiona Ctrl+C para detenerlo.\n\n";

// CICLO PRINCIPAL
while (true) {

    try {

        $trabajos = solicitarFlowable(
            "POST",
            "/external-job-api/acquire/jobs",
            [
                "topic" => $tema,
                "lockDuration" => "PT30S",
                "numberOfTasks" => 1,
                "workerId" => $workerId
            ]
        );

        foreach ($trabajos as $trabajo) {

            if (empty($trabajo["id"])) {
                continue;
            }

            $idTrabajo = $trabajo["id"];

            echo "----------------------------------------\n";
            echo "Tarea recibida: $idTrabajo\n";

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

                // CONSULTAR CLIENTE EN MARIADB
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

                echo "Tarea completada correctamente en Flowable.\n";

            } catch (Throwable $error) {

                echo "ERROR AL PROCESAR TAREA: " .
                    $error->getMessage() . "\n";

                echo "La tarea no fue completada.\n";
            }
        }

    } catch (Throwable $error) {

        echo "ERROR DE FLOWABLE: " .
            $error->getMessage() . "\n";
    }

    sleep(5);
}