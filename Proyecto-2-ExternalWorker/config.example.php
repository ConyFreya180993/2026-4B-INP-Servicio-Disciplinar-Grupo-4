<?php
// ======================================================
// EJEMPLO DE CONFIGURACION - EXTERNAL WORKER
//
// 1. Copiar este archivo como config.local.php
// 2. Reemplazar los valores CAMBIAR_... por los de tu entorno
//
// config.local.php no se sube a GitHub (esta en .gitignore).
// ======================================================

return [
    // Flowable REST
    "flowable_url" => "http://127.0.0.1:8080/flowable-rest",
    "flowable_usuario" => "CAMBIAR_USUARIO",
    "flowable_password" => "CAMBIAR_CONTRASENA",

    // Servicio web Jakarta REST
    "servicio_url" => "http://127.0.0.1:8081/jakarta",

    // Identificador de este worker
    "worker_id" => "peluditos-worker-1"
];
