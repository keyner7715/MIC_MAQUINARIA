<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para editar turnos de trabajo
verificarPermiso('editar');

$id = $_GET['id'] ?? 0;
$turno = null;

// Obtener datos del turno
if ($id) {
    try {
        $sql = "SELECT * FROM turnos_trabajo WHERE id_turno = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $turno = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$turno) {
            echo "<script>alert('Turno no encontrado'); window.location.href='R_turnos_trabajo.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_turno = $_POST['nombre_turno'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';

    try {
        $sql = "UPDATE turnos_trabajo SET nombre_turno = ?, hora_inicio = ?, hora_fin = ? WHERE id_turno = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_turno, $hora_inicio, $hora_fin, $id]);
        
        echo "<script>alert('Turno actualizado exitosamente'); window.location.href='R_turnos_trabajo.php';</script>";
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
    <title>Actualizar Turno de Trabajo</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Turno de Trabajo</h2>
        
        <?php if ($turno): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="nombre_turno">Nombre del Turno:</label>
                    <input type="text" id="nombre_turno" name="nombre_turno" value="<?= htmlspecialchars($turno['nombre_turno']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="hora_inicio">Hora de Inicio:</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" value="<?= htmlspecialchars($turno['hora_inicio']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="hora_fin">Hora de Fin:</label>
                    <input type="time" id="hora_fin" name="hora_fin" value="<?= htmlspecialchars($turno['hora_fin']) ?>" required>
                </div>
                
                <div class="form-group">
                    <button type="submit">Actualizar Turno</button>
                </div>
            </form>
        <?php else: ?>
            <p>Turno no encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
