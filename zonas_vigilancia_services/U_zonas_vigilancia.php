<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? 0;
$zona = null;

// Obtener lista de clientes para el select
$clientes = $pdo->query("SELECT id_cliente, razon_social FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos de la zona actual
if ($id) {
    try {
        $sql = "SELECT * FROM zonas_vigilancia WHERE id_zona = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $zona = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$zona) {
            echo "<script>alert('Zona no encontrada'); window.location.href='R_zonas_vigilancia.php';</script>";
            exit;
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_zona = $_POST['nombre_zona'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $id_cliente = $_POST['id_cliente'] ?? '';

    try {
        $sql = "UPDATE zonas_vigilancia SET nombre_zona = ?, direccion = ?, id_cliente = ? WHERE id_zona = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_zona, $direccion, $id_cliente, $id]);

        echo "<script>alert('Zona actualizada exitosamente'); window.location.href='R_zonas_vigilancia.php';</script>";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Zona de Vigilancia</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Actualizar Zona de Vigilancia</h2>

    <?php if ($zona): ?>
        <form method="POST">
            <div class="form-group">
                <label for="nombre_zona">Nombre de la Zona:</label>
                <input type="text" name="nombre_zona" id="nombre_zona" value="<?= htmlspecialchars($zona['nombre_zona']) ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($zona['direccion']) ?>" required>
            </div>
            <div class="form-group">
                <label for="id_cliente">Cliente:</label>
                <select name="id_cliente" id="id_cliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>" <?= $zona['id_cliente'] == $cliente['id_cliente'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['razon_social']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Actualizar</button>
            </div>
        </form>
    <?php endif; ?>
    <a href="R_zonas_vigilancia.php" class="btn-volver">Volver</a>
</div>
</body>
</html>
