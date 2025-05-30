<?php
    //Realizar conexion
    require 'conexion.php';

    $codigo = $_POST['codigo'] ?? '';

    try {
        //Consulta que revisa si algun producto existente
        //ya tiene el codigo ingresado
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM producto WHERE codigo = :codigo");
        $stmt->execute([':codigo' => $codigo]);
        $existe = $stmt->fetchColumn();

        echo json_encode([
            'existe' => $existe > 0
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'error' => 'Error al verificar el código'
        ]);
    }
?>