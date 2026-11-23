<?php

require_once __DIR__ . "/conexion.php";

header("Content-Type: application/json; charset=utf-8");

$metodo = $_SERVER["REQUEST_METHOD"];

// ==========================================
// CONSULTAR CAMPAÑAS DE FIDELIZACIÓN
// ==========================================

if ($metodo === "GET") {

    $sql = "
        SELECT
            f.id_fidelizacion,
            l.nombre_cliente,
            p.nombre_mascota,
            f.fecha_evento,
            f.tipo_campana,
            f.estado_envio
        FROM fidelizacion AS f
        INNER JOIN pet AS p
            ON f.id_pet = p.id_pet
        INNER JOIN leads AS l
            ON p.id_lead = l.id_lead
        ORDER BY f.id_fidelizacion
    ";

    $resultado = $conexion->query($sql);

    $campanas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $campanas[] = $fila;
    }

    echo json_encode([
        "estado" => "correcto",
        "total" => count($campanas),
        "campanas" => $campanas
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// ==========================================
// ACTUALIZAR ESTADO DE CAMPAÑA
// ==========================================

if ($metodo === "POST") {

    $id = filter_input(INPUT_POST, "id_fidelizacion", FILTER_VALIDATE_INT);
    $estado = $_POST["estado_envio"] ?? "";

    if (!$id || !in_array($estado, ["Programado", "Enviado"], true)) {

        http_response_code(400);

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Datos de actualización inválidos."
        ]);

        exit;
    }

    // Comprobar que la campaña existe.
    $consulta = $conexion->prepare(
        "SELECT id_fidelizacion FROM fidelizacion WHERE id_fidelizacion = ?"
    );

    $consulta->bind_param("i", $id);
    $consulta->execute();

    $existe = $consulta->get_result()->num_rows > 0;

    $consulta->close();

    if (!$existe) {

        http_response_code(404);

        echo json_encode([
            "estado" => "error",
            "mensaje" => "No se encontró la campaña."
        ]);

        exit;
    }

    // Actualizar en MariaDB.
    $actualizar = $conexion->prepare(
        "UPDATE fidelizacion
         SET estado_envio = ?
         WHERE id_fidelizacion = ?"
    );

    $actualizar->bind_param("si", $estado, $id);
    $actualizar->execute();
    $actualizar->close();

    echo json_encode([
        "estado" => "correcto",
        "id_fidelizacion" => $id,
        "estado_envio" => $estado,
        "mensaje" => "Campaña actualizada correctamente en MariaDB."
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// ==========================================
// MÉTODO NO PERMITIDO
// ==========================================

http_response_code(405);

echo json_encode([
    "estado" => "error",
    "mensaje" => "Método no permitido."
]);

?>