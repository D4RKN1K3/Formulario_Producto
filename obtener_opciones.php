<?php
    //Realizar conexion
    require 'conexion.php';

    try {
        // Consulta para obtener monedas
        $monedas = $conexion->query("SELECT id, nombre FROM moneda")->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para obtener bodegas
        $bodegas = $conexion->query("SELECT id, nombre FROM bodega")->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'monedas' => $monedas,
            'bodegas' => $bodegas
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'error' => 'Error al obtener opciones'
        ]);
    }
?>