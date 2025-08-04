<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? 0;
$guardia = null;

// Obtener datos del guardia
if ($id) {
    try {
        $sql = "SELECT * FROM guardias WHERE id_guardia = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $guardia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$guardia) {
            echo "<script>alert('Guardia no encontrado'); window.location.href='R_guardias.php';</script>";
            exit;
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? ''; // <-- Agrega esta línea
    $estado = $_POST['estado'] ?? ''; // <-- Agrega esta línea


    try {
        $sql = "UPDATE guardias SET nombre = ?, cedula = ?, telefono = ?, direccion = ?, estado = ? WHERE id_guardia = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $cedula, $telefono, $direccion, $estado, $id]);

        echo "<script>alert('Guardia actualizado exitosamente'); window.location.href='R_guardias_services.php';</script>";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Guardia</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Guardia</h2>

        <?php if ($guardia): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Nombre del Guardia:</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($guardia['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Cédula:</label>
                    <input type="text" name="cedula" value="<?= htmlspecialchars($guardia['cedula']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($guardia['telefono']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($guardia['direccion']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Estado:</label>
                    <select name="estado" required>
                        <option value="activo" <?= $guardia['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= $guardia['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">Actualizar Guardia</button>

                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
