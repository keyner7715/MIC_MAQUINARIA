<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? 0;
$supervisor = null;

// Obtener datos del supervisor
if ($id) {
    try {
        $sql = "SELECT * FROM supervisores WHERE id_supervisor = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $supervisor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$supervisor) {
            echo "<script>alert('Supervisor no encontrado'); window.location.href='R_supervisor.php';</script>";
            exit;
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';

    try {
        $sql = "UPDATE supervisores SET nombre = ?, telefono = ?, correo = ? WHERE id_supervisor = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $telefono, $correo, $id]);

        echo "<script>alert('Supervisor actualizado exitosamente'); window.location.href='R_supervisor.php';</script>";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar supervisor</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar supervisor</h2>

        <?php if ($supervisor): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($supervisor['nombre']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Cédula:</label>
                    <input type="text" name="cedula" value="<?= htmlspecialchars($supervisor['cedula']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($supervisor['telefono']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="correo" value="<?= htmlspecialchars($supervisor['correo']) ?>" required>
                </div>
                
                <div class="form-group">
                    <button type="submit">Actualizar supervisor</button>

                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
