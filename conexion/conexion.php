<?php

// La instancia del servidor, nombre de la BD, host, user y pass
$servidor="mysql:dbname=bolsas_solidarias;host=127.0.0.1";
$usuario="root";
$password="";

try { 
    // Si no hay ningún error, se creará la instancia de SQL usando PDO
    $pdo = new PDO($servidor, $usuario, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
    // echo "Conectado a la BD...";
    
} catch (PDOException $e) {
    // Si existe un error, lo imprimirá
    echo "Error de Conexión: ".$e->getMessage();
}

?>