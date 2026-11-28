<?php

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/flowable_config.php";

header("Content-Type: application/json; charset=utf-8");

// ======================================================
// FUNCION PARA INICIAR EL PROCESO EN FLOWABLE
// ======================================================

function iniciarProcesoFlowable($idLead)
{
    $url = FLOWABLE_URL . "/runtime/process-instances";

    $datos = [
        "processDefinitionKey" => "Process_1pot1gs00",
        "name" => "Registro cliente Peluditos #" . $idLead,
        "variables" => [
            [
                "name" => "id_lead",
                "value" => (int) $idLead,
                "type" => "integer"
            ]
        ]
    ];

    $opciones = [
        "http" => [
            "method" => "POST",
            "header" =>
                "Authorization: Basic " .
                base64_encode(
                    FLOWABLE_USUARIO . ":" . FLOWABLE_PASSWORD
                ) . "\r\n" .
                "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n",
            "content" => json_encode(
                $datos,
                JSON_THROW_ON_ERROR
            ),
            "ignore_errors" => true,
            "timeout" => 15
        ]
    ];

    $contexto = stream_context_create($opciones);

    $respuesta = @file_get_contents(
        $url,
        false,
        $contexto
    );

    $codigo = 0;

    foreach ($http_response_header ?? [] as $cabecera) {
        if (preg_match(
            '/^HTTP\/\S+\s+(\d+)/',
            $cabecera,
            $coincidencia
        )) {
            $codigo = (int) $coincidencia[1];
        }
    }

    if ($respuesta === false || $codigo < 200 || $codigo >= 300) {
        throw new RuntimeException(
            "Flowable respondió HTTP $codigo: " .
            ($respuesta ?: "Sin respuesta")
        );
    }

    $resultado = json_decode(
        $respuesta,
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    if (empty($resultado["id"])) {
        throw new RuntimeException(
            "Flowable no devolvió el ID del proceso"
        );
    }

    return $resultado;
}

// ======================================================
// VALIDAR METODO HTTP
// ======================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Metodo no permitido"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// RECIBIR DATOS DEL FORMULARIO
// ======================================================

$nombre = trim($_POST["nombre_cliente"] ?? "");
$contacto = trim($_POST["contacto_cliente"] ?? "");
$canal = trim($_POST["canal_origen"] ?? "");
$nombre_mascota = trim($_POST["nombre_mascota"] ?? "");
$tipo_mascota = trim($_POST["tipo_mascota"] ?? "");
$cumpleanos = trim($_POST["cumpleanos"] ?? "");
$consulta_texto = trim($_POST["consulta"] ?? "");
$tamano_pastel = trim($_POST["tamano_pastel"] ?? "");

// ======================================================
// VALIDAR CAMPOS OBLIGATORIOS
// ======================================================

if (
    $nombre === "" || $contacto === "" || $canal === "" ||
    $nombre_mascota === "" || $tipo_mascota === "" ||
    $cumpleanos === "" || $consulta_texto === "" ||
    $tamano_pastel === ""
) {

    http_response_code(400);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Todos los campos son obligatorios"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// VALIDAR NOMBRE
// ======================================================

if (mb_strlen($nombre) > 50) {

    http_response_code(400);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "El nombre no puede superar 50 caracteres"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// VALIDAR TELEFONO CHILENO
// ======================================================

if (preg_match('/^[0-9]{8}$/', $contacto)) {
    $contacto = "+569" . $contacto;
}

if (!preg_match('/^\+569[0-9]{8}$/', $contacto)) {

    http_response_code(400);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "El telefono debe tener 8 digitos"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// VALIDAR CANAL DE ORIGEN
// ======================================================

$canales_validos = [
    "WhatsApp",
    "Instagram",
    "Facebook"
];

if (!in_array($canal, $canales_validos, true)) {

    http_response_code(400);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Canal de origen no valido"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// VALIDAR DATOS DE LA MASCOTA Y DE LA CONSULTA
// ======================================================

$tipos_validos = ["Gato", "Perro"];
$tamanos_validos = ["Pequeño", "Mediano", "Grande"];

$error_datos = "";

if (mb_strlen($nombre_mascota) > 50) {
    $error_datos = "El nombre de la mascota no puede superar 50 caracteres";
} elseif (!in_array($tipo_mascota, $tipos_validos, true)) {
    $error_datos = "Tipo de mascota no válido";
} elseif (!in_array($tamano_pastel, $tamanos_validos, true)) {
    $error_datos = "Tamaño de pastel no válido";
} elseif (mb_strlen($consulta_texto) > 1000) {
    $error_datos = "La consulta no puede superar 1000 caracteres";
} else {
    $fecha = DateTime::createFromFormat("Y-m-d", $cumpleanos);

    if (!$fecha || $fecha->format("Y-m-d") !== $cumpleanos) {
        $error_datos = "La fecha de cumpleaños no es válida";
    } elseif ($fecha > new DateTime("today")) {
        $error_datos = "El cumpleaños no puede ser una fecha futura";
    }
}

if ($error_datos !== "") {

    http_response_code(400);

    echo json_encode([
        "estado" => "error",
        "mensaje" => $error_datos
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// REGISTRAR CLIENTE, MASCOTA Y SEGUIMIENTO EN MARIADB
// Se guardan juntos: si algo falla, no se guarda nada.
// ======================================================

try {

    $conexion->begin_transaction();

    // Cliente
    $sql = "INSERT INTO leads
            (nombre_cliente, contacto_cliente, canal_origen)
            VALUES (?, ?, ?)";

    $consulta = $conexion->prepare($sql);

    $consulta->bind_param(
        "sss",
        $nombre,
        $contacto,
        $canal
    );

    $consulta->execute();

    $id_lead = $conexion->insert_id;

    $consulta->close();

    // Mascota
    $sql = "INSERT INTO pet
            (id_lead, nombre_mascota, tipo_pet, cumpleanos)
            VALUES (?, ?, ?, ?)";

    $consulta = $conexion->prepare($sql);

    $consulta->bind_param(
        "isss",
        $id_lead,
        $nombre_mascota,
        $tipo_mascota,
        $cumpleanos
    );

    $consulta->execute();

    $id_pet = $conexion->insert_id;

    $consulta->close();

    // Seguimiento (queda pendiente hasta que se registre el resultado)
    $sql = "INSERT INTO seguimientos
            (id_pet, consulta, tamano_pastel, resultado_consulta)
            VALUES (?, ?, ?, 'Pendiente')";

    $consulta = $conexion->prepare($sql);

    $consulta->bind_param(
        "iss",
        $id_pet,
        $consulta_texto,
        $tamano_pastel
    );

    $consulta->execute();

    $id_seguimiento = $conexion->insert_id;

    $consulta->close();

    $conexion->commit();

} catch (Throwable $error) {

    try {
        $conexion->rollback();
    } catch (Throwable $errorRollback) {
        error_log("Error al deshacer el registro: " . $errorRollback->getMessage());
    }

    error_log("Error MariaDB: " . $error->getMessage());

    http_response_code(500);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "No se pudo registrar el cliente"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// ======================================================
// INICIAR PROCESO BPMN EN FLOWABLE
// ======================================================

try {

    $proceso = iniciarProcesoFlowable($id_lead);

    http_response_code(201);

    echo json_encode([
        "estado" => "correcto",
        "mensaje" => "Cliente, mascota y consulta registrados, y proceso Flowable iniciado",
        "id_lead" => $id_lead,
        "id_pet" => $id_pet,
        "id_seguimiento" => $id_seguimiento,
        "flowable" => [
            "estado" => "correcto",
            "process_instance_id" => $proceso["id"],
            "process_definition_id" => $proceso["processDefinitionId"]
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Throwable $error) {

    error_log("Error Flowable: " . $error->getMessage());

    // El cliente ya esta guardado en MariaDB.
    // No se vuelve a insertar ni se pierde su ID.

    http_response_code(201);

    echo json_encode([
        "estado" => "correcto",
        "mensaje" => "Cliente, mascota y consulta registrados en MariaDB, pero no se pudo iniciar Flowable",
        "id_lead" => $id_lead,
        "id_pet" => $id_pet,
        "id_seguimiento" => $id_seguimiento,
        "flowable" => [
            "estado" => "error",
            "mensaje" => "No se pudo iniciar el proceso BPMN"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

?>
