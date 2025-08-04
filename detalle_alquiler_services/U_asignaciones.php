<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para editar asignaciones
verificarPermiso('editar');

$id_asignacion = $_GET['id'] ?? 0;
$asignacion = null;

// Obtener datos de la asignación
if ($id_asignacion) {
    try {
        $sql = "
            SELECT * 
            FROM asignaciones 
            WHERE id_asignacion = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_asignacion]);
        $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$asignacion) {
            echo "<script>alert('Asignación no encontrada'); window.location.href='R_asignaciones.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

// Obtener datos para los select
try {
    $guardias = $pdo->query("SELECT id_guardia, nombre FROM guardias WHERE estado = 'activo' ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    $servicios = $pdo->query("SELECT id_servicio, descripcion FROM servicios_contratados ORDER BY descripcion")->fetchAll(PDO::FETCH_ASSOC);
    $turnos = $pdo->query("SELECT id_turno, nombre_turno FROM turnos_trabajo ORDER BY nombre_turno")->fetchAll(PDO::FETCH_ASSOC);
    $supervisores = $pdo->query("SELECT id_supervisor, nombre FROM supervisores ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_guardia = $_POST['id_guardia'] ?? '';
    $id_servicio = $_POST['id_servicio'] ?? '';
    $id_turno = $_POST['id_turno'] ?? '';
    $fecha_asignacion = $_POST['fecha_asignacion'] ?? '';
    $id_supervisor = $_POST['id_supervisor'] ?? null;

    try {
        $sql = "
            UPDATE asignaciones 
            SET id_guardia = ?, id_servicio = ?, id_turno = ?, fecha_asignacion = ?, id_supervisor = ? 
            WHERE id_asignacion = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_guardia, $id_servicio, $id_turno, $fecha_asignacion, $id_supervisor, $id_asignacion]);
        echo "<script>alert('Asignación actualizada exitosamente'); window.location.href='R_asignaciones.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Asignación</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Asignación</h2>
        <?php if ($asignacion): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="id_guardia">Guardia:</label>
                    <select id="id_guardia" name="id_guardia" required>
                        <option value="">Seleccione un guardia</option>
                        <?php foreach ($guardias as $guardia): ?>
                            <option value="<?= $guardia['id_guardia'] ?>" <?= $guardia['id_guardia'] == $asignacion['id_guardia'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($guardia['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_servicio">Servicio:</label>
                    <select id="id_servicio" name="id_servicio" required>
                        <option value="">Seleccione un servicio</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?= $servicio['id_servicio'] ?>" <?= $servicio['id_servicio'] == $asignacion['id_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($servicio['descripcion']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_turno">Turno:</label>
                    <select id="id_turno" name="id_turno" required>
                        <option value="">Seleccione un turno</option>
                        <?php foreach ($turnos as $turno): ?>
                            <option value="<?= $turno['id_turno'] ?>" <?= $turno['id_turno'] == $asignacion['id_turno'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($turno['nombre_turno']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_asignacion">Fecha de Asignación:</label>
                    <input type="date" id="fecha_asignacion" name="fecha_asignacion" value="<?= htmlspecialchars($asignacion['fecha_asignacion']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_supervisor">Supervisor (opcional):</label>
                    <select id="id_supervisor" name="id_supervisor">
                        <option value="">Seleccione un supervisor</option>
                        <?php foreach ($supervisores as $supervisor): ?>
                            <option value="<?= $supervisor['id_supervisor'] ?>" <?= $supervisor['id_supervisor'] == $asignacion['id_supervisor'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($supervisor['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Actualizar Asignación</button>
                    <a href="R_asignaciones.php" class="btn btn-secondary">Volver</a>
                </div>
            </form>
        <?php else: ?>
            <p>Asignación no encontrada.</p>
        <?php endif; ?>
    </div>
</body>
</html>