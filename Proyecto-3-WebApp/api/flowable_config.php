<?php

// Configuración de Flowable REST.
// Los datos vienen de la configuración externa (ver config.php).

require_once __DIR__ . "/config.php";

define(
    "FLOWABLE_URL",
    leerConfig(
        "PELUDITOS_FLOWABLE_SERVICE_URL",
        "flowable_url",
        "http://127.0.0.1:8080/flowable-rest/service"
    )
);

// El usuario y la contraseña no tienen valor por defecto
define("FLOWABLE_USUARIO", leerConfig("PELUDITOS_FLOWABLE_USER", "flowable_usuario", ""));
define("FLOWABLE_PASSWORD", leerConfig("PELUDITOS_FLOWABLE_PASSWORD", "flowable_password", ""));
