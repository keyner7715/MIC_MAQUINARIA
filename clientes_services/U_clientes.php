<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar que el usuario tiene permiso para editar
verificarPermiso('editar');

$id = $_GET['id'] ?? 0;
$atraccion = null;

// Obtener los datos de la atracción
if ($id) {
    try {
        $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            echo "<script>alert('Cliente no encontrado'); window.location.href='R_clientes.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $razon = trim($_POST['razon_social'] ?? '');
    $ruc = trim($_POST['ruc'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if ($razon && $ruc && $direccion && $telefono && $correo) {
        try {
            $sql = "UPDATE clientes SET razon_social = ?, ruc = ?, direccion = ?, telefono = ?, correo = ? WHERE id_cliente = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$razon, $ruc, $direccion, $telefono, $correo, $id]);

            echo "<script>alert('Cliente actualizado exitosamente'); window.location.href='R_clientes.php';</script>";
        } catch (PDOException $e) {
            echo "Error al actualizar: " . $e->getMessage();
        }
    } else {
        echo "Por favor complete los campos obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Cliente</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Cliente</h2>
        <?php if ($cliente): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="razon_social">Razón Social:</label>
                    <input type="text" name="razon_social" id="razon_social" value="<?= htmlspecialchars($cliente['razon_social']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="ruc">RUC:</label>
                    <input type="text" name="ruc" id="ruc" value="<?= htmlspecialchars($cliente['ruc']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit">Actualizar Cliente</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
