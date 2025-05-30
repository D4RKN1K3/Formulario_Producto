<?php
    // Este archivo realiza la conexion a la base de datos
    $host = "localhost"; 
    $port = "5432"; 
    $dbname = "formularioproductodb"; //Nombre asignado a la base de datos
    $user = "postgres";
    $password = "1234";

    try {
        $conexion = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
?>