<?php
    //Realizar conexion
    require 'conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $codigo = $_POST['codigo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $bodega_id = $_POST['bodega'] ?? '';
        $sucursal_id = $_POST['sucursal'] ?? '';
        $moneda_id = $_POST['moneda'] ?? '';
        $materiales = $_POST['material'] ?? [];

        // Validaciones de vacios
        if (empty($codigo) || empty($nombre) || empty($precio) || empty($descripcion) || empty($bodega_id) || empty($sucursal_id) || empty($moneda_id) || empty($materiales)) {
            echo json_encode(["estado" => "error", "mensaje" => "Todos los campos son obligatorios."]);
            exit;
        }

        if (!preg_match('/^[\w-]{1,15}$/', $codigo)) {
            echo json_encode(["estado" => "error", "mensaje" => "El código no es válido."]);
            exit;
        }

        if (strlen($nombre) > 50 || strlen($nombre) < 2) {
            echo json_encode(["estado" => "error", "mensaje" => "Longitud de nombre incorrecta."]);
            exit;
        }

        if (!is_numeric($precio) || floatval($precio) <= 0) {
            echo json_encode(["estado" => "error", "mensaje" => "El precio no es válido."]);
            exit;
        }

        if (count($materiales) < 2) {
                echo json_encode(['estado' => 'error', 'mensaje' => 'Debe seleccionar al menos dos materiales']);
                exit;
            }

        // Insertar en la tabla producto
        try {
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("INSERT INTO producto (codigo, nombre, precio, descripcion, bodega_id, sucursal_id, moneda_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$codigo, $nombre, $precio, $descripcion, $bodega_id, $sucursal_id, $moneda_id]);

            //Insertar en tabla intermedia entre productos y materiales
            //para manejar la relacion muchos a muchos
            $stmtMaterial = $conexion->prepare("INSERT INTO producto_material (producto_codigo, material_id) VALUES (?, ?)");

            foreach ($materiales as $material_id) {
                $stmtMaterial->execute([$codigo, $material_id]);
            }

            //Se realiza el commit
            $conexion->commit();
            echo json_encode(["estado" => "ok", "mensaje" => "Producto guardado correctamente."]);
        } catch (Exception $e) {
            //En caso de error se realiza un rollBack
            $conexion->rollBack();
            echo json_encode(["estado" => "error", "mensaje" => "Error al guardar: " . $e->getMessage()]);
        }
    }
?>