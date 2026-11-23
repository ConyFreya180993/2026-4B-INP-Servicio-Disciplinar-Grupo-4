
<?php

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Método no permitido"
    ]);

    exit;
}

$id_seguimiento = filter_var(
    $_POST["id_seguimiento"] ?? null,
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 1]]
);

$resultado_consulta = trim($_POST["resultado_consulta"] ?? "");

$estados_validos = [
    "Venta Concretada",
    "Venta No Concretada",
    "Pendiente"
];

if (!$id_seguimiento || !in_array($resultado_consulta, $estados_validos, true)) {
    http_response_code(400);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Datos del seguimiento no válidos"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $consulta = $conexion->prepare(
        "SELECT id_seguimiento
         FROM seguimientos
         WHERE id_seguimiento = ?"
    );

    $consulta->bind_param("i", $id_seguimiento);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows === 0) {
        $consulta->close();

        http_response_code(404);

        echo json_encode([
            "estado" => "error",
            "mensaje" => "El seguimiento no existe"
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $consulta->close();

    $actualizacion = $conexion->prepare(
        "UPDATE seguimientos
         SET resultado_consulta = ?
         WHERE id_seguimiento = ?"
    );

    $actualizacion->bind_param(
        "si",
        $resultado_consulta,
        $id_seguimiento
    );

    $actualizacion->execute();
    $actualizacion->close();

    echo json_encode([
        "estado" => "correcto",
        "mensaje" => "Seguimiento actualizado correctamente",
        "id_seguimiento" => $id_seguimiento,
        "resultado_consulta" => $resultado_consulta
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $error) {
    error_log("Error al actualizar seguimiento: " . $error->getMessage());

    http_response_code(500);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "No se pudo actualizar el seguimiento"
    ], JSON_UNESCAPED_UNICODE);
}
