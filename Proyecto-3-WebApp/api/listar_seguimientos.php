
<?php

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/conexion.php";

try {
    $sql = "
        SELECT
            s.id_seguimiento,
            l.nombre_cliente,
            p.nombre_mascota,
            s.consulta,
            s.resultado_consulta
        FROM seguimientos AS s
        INNER JOIN pet AS p
            ON s.id_pet = p.id_pet
        INNER JOIN leads AS l
            ON p.id_lead = l.id_lead
        ORDER BY s.id_seguimiento
    ";

    $resultado = $conexion->query($sql);

    $seguimientos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $seguimientos[] = $fila;
    }

    echo json_encode([
        "estado" => "correcto",
        "total" => count($seguimientos),
        "seguimientos" => $seguimientos
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $error) {
    error_log("Error al listar seguimientos: " . $error->getMessage());

    http_response_code(500);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "No se pudieron obtener los seguimientos"
    ], JSON_UNESCAPED_UNICODE);
}
