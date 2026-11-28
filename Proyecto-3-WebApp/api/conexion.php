<?php
// Conexión a MariaDB.
// Los datos vienen de la configuración externa (ver config.php).

require_once __DIR__ . "/config.php";

$servidor = leerConfig("PELUDITOS_DB_HOST", "db_host", "127.0.0.1");
$puerto = (int) leerConfig("PELUDITOS_DB_PORT", "db_puerto", 3306);
$usuario = leerConfig("PELUDITOS_DB_USER", "db_usuario");
$contrasena = leerConfig("PELUDITOS_DB_PASSWORD", "db_password");
$base_datos = leerConfig("PELUDITOS_DB_NAME", "db_nombre", "peluditos_db");

// El usuario y la contraseña no tienen valor por defecto
if ($usuario === null || $contrasena === null) {
    error_log("Falta la configuración de la base de datos (config.local.php).");

    http_response_code(500);
    exit("Falta la configuración de la base de datos.");
}

try {
    $conexion = new mysqli(
        $servidor,
        $usuario,
        $contrasena,
        $base_datos,
        $puerto
    );

    $conexion->set_charset("utf8mb4");

} catch (mysqli_sql_exception $error) {
    error_log("Error de conexión: " . $error->getMessage());

    http_response_code(500);
    exit("No se pudo establecer conexión con la base de datos.");
}
?>
