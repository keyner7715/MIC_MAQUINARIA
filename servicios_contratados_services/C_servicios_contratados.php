<?php
require_once '../config/db.php';

$error = '';
$success = '';

// Obtener clientes y zonas para los select
try {
    $stmt_clientes = $pdo->query("SELECT id_cliente, razon_social FROM clientes");
    $clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

    $stmt_zonas = $pdo->query("SELECT z.id_zona, z.nombre_zona, c.razon_social AS cliente 
                               FROM zonas_vigilancia z 
                               INNER JOIN clientes c ON z.id_cliente = c.id_cliente");
    $zonas = $stmt_zonas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener datos: " . $e->getMessage();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $id_cliente = $_POST['id_cliente'] ?? '';
    $id_zona = $_POST['id_zona'] ?? '';

    if ($descripcion && $fecha_inicio && $id_cliente && $id_zona) {
        try {
            $stmt = $pdo->prepare("INSERT INTO servicios_contratados (descripcion, fecha_inicio, fecha_fin, id_cliente, id_zona) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$descripcion, $fecha_inicio, $fecha_fin, $id_cliente, $id_zona]);

            $success = "Servicio contratado creado exitosamente.";
        } catch (PDOException $e) {
            $error = "Error al crear el servicio contratado: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crear Servicio Contratado</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Crear Nuevo Servicio Contratado</h2>
        <a href="R_servicios_contratados.php" class="btn-primary">Volver a la lista</a>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin (opcional):</label>
                <input type="date" name="fecha_fin" id="fecha_fin">
            </div>
            <div class="form-group">
                <label for="id_cliente">Seleccionar Cliente:</label>
                <select name="id_cliente" id="id_cliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= htmlspecialchars($cliente['id_cliente']) ?>">
                            <?= htmlspecialchars($cliente['razon_social']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_zona">Seleccionar Zona:</label>
                <select name="id_zona" id="id_zona" required>
                    <option value="">Seleccione una zona</option>
                    <?php foreach ($zonas as $zona): ?>
                        <option value="<?= htmlspecialchars($zona['id_zona']) ?>">
                            <?= htmlspecialchars($zona['nombre_zona']) ?> (<?= htmlspecialchars($zona['cliente']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-primary">Crear Servicio</button>
        </form>
    </div>
</body>
</html>
