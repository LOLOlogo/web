<?php
$host = 'localhost'; // Servidor local en XAMPP
$dbname = 'registro_ninos'; // Nombre de la base de datos
$username = 'root'; // Usuario por defecto en XAMPP
$password = ''; // Contraseña vacía por defecto en XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
