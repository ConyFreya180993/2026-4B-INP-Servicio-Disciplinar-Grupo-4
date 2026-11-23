<?php
// Conexión local a MariaDB mediante XAMPP

$servidor = "127.0.0.1";
$puerto = 3306;
$usuario = "root";
$contrasena = "";
$base_datos = "peluditos_db";

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
