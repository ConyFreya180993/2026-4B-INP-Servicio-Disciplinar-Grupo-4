<?php

require_once __DIR__ . "/flowable_config.php";

header("Content-Type: application/json; charset=utf-8");

$url = FLOWABLE_URL . "/repository/process-definitions";

$opciones = [
    "http" => [
        "method" => "GET",
        "header" => [
            "Authorization: Basic " . base64_encode(
                FLOWABLE_USUARIO . ":" . FLOWABLE_PASSWORD
            ),
            "Accept: application/json",
            "Connection: close"
        ],
        "timeout" => 15,
        "ignore_errors" => true
    ]
];

$contexto = stream_context_create($opciones);

$respuesta = @file_get_contents($url, false, $contexto);

$codigo_http = 0;

if (isset($http_response_header[0])) {
    if (preg_match(
        '/^HTTP\/\S+\s+(\d{3})/',
        $http_response_header[0],
        $coincidencias
    )) {
        $codigo_http = (int) $coincidencias[1];
    }
}

if ($respuesta === false || $codigo_http !== 200) {
    http_response_code(502);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "No se pudieron consultar los procesos de Flowable",
        "codigo_http" => $codigo_http
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    exit;
}

$datos = json_decode($respuesta, true);

if (!is_array($datos)) {
    http_response_code(502);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Flowable devolvió una respuesta JSON inválida"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    exit;
}

$procesos = [];

foreach ($datos["data"] ?? [] as $proceso) {
    $procesos[] = [
        "id" => $proceso["id"] ?? null,
        "nombre" => $proceso["name"] ?? null,
        "clave" => $proceso["key"] ?? null,
        "version" => $proceso["version"] ?? null
    ];
}

echo json_encode([
    "estado" => "correcto",
    "total" => count($procesos),
    "procesos" => $procesos
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
