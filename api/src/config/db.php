<?php
$host ='localhost';
$dbname ='slimsso';
$username ='root';
$password = '';

$conexion = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
try {
    $pdo = new PDO($conexion,$username,$password);
} catch (PDOException $e) {
    echo 'Error al conectarse a la base de datos:'.$e->getMessage();
}
