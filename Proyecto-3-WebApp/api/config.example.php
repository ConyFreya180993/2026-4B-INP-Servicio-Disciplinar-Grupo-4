<?php
// ======================================================
// EJEMPLO DE CONFIGURACION - WEBAPP
//
// 1. Copiar este archivo como config.local.php
// 2. Reemplazar los valores CAMBIAR_... por los de tu entorno
//
// config.local.php no se sube a GitHub (esta en .gitignore).
// ======================================================

return [
    // MariaDB
    "db_host" => "127.0.0.1",
    "db_puerto" => 3306,
    "db_nombre" => "peluditos_db",
    "db_usuario" => "CAMBIAR_USUARIO",
    "db_password" => "CAMBIAR_CONTRASENA",

    // Flowable REST
    "flowable_url" => "http://127.0.0.1:8080/flowable-rest/service",
    "flowable_usuario" => "CAMBIAR_USUARIO",
    "flowable_password" => "CAMBIAR_CONTRASENA"
];
