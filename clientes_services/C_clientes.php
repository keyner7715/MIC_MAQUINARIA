<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar que el usuario tiene permiso para crear
verificarPermiso('crear');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $razon = trim($_POST['razon_social'] ?? '');
    $ruc = trim($_POST['ruc'] ?? '');
    $direccion = $_POST['direccion'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if ($razon && $ruc && $direccion && $telefono && $correo) {
        try {
            $sql = "INSERT INTO clientes (razon_social, ruc, direccion, telefono, correo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$razon, $ruc, $direccion, $telefono, $correo]);

            echo "<script>alert('Cliente registrado exitosamente'); window.location.href='R_clientes.php';</script>";
        } catch (PDOException $e) {
            echo "Error al registrar el cliente: " . $e->getMessage();
        }
    } else {
        echo "Por favor complete todos los campos obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cliente</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Nuevo Cliente</h2>
        <form method="POST">
            <div class="form-group">
                <label for="razon_social">Razon Social:</label>
                <input type="text" name="razon_social" id="razon_social" required>
            </div>
            <div class="form-group">
                <label for="ruc">Ruc:</label>
                <input type="text" name="ruc" id="ruc" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" required>
            </div>
            <button type="submit">Crear Cliente</button>
        </form>
    </div>
</body>
</html>
