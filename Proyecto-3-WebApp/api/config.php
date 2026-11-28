<?php
// Lee la configuración de la aplicación.
// Los valores se toman de variables de entorno o del archivo config.local.php.
// Si existen las dos, se usa la variable de entorno.
// Para crear config.local.php se debe copiar config.example.php.

$config = [];

if (file_exists(__DIR__ . "/config.local.php")) {
    $config = require __DIR__ . "/config.local.php";
}

function leerConfig($variable, $clave, $porDefecto = null)
{
    global $config;

    $valor = getenv($variable);

    if ($valor !== false && $valor !== "") {
        return $valor;
    }

    return $config[$clave] ?? $porDefecto;
}
