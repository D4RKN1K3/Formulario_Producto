<?php
    //Realizar conexion
    require 'conexion.php';

    $bodega_id = $_POST['bodega_id'] ?? null;

    //Revisar que el id de la bodega seleccionado exista
    if (!$bodega_id) {
        echo json_encode(['error' => 'ID de bodega no proporcionado']);
        exit;
    }

    try {
        //Consulta para obtener sucursales
        $stmt = $conexion->prepare("SELECT id, nombre FROM sucursal WHERE bodega_id = ?");
        $stmt->execute([$bodega_id]);
        $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($sucursales);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener sucursales']);
    }
?>