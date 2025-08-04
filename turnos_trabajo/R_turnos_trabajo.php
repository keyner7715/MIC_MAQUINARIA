<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar turnos de trabajo
verificarPermiso('listar');

try {
    // Consulta para obtener los turnos de trabajo
    $sql = "SELECT id_turno, nombre_turno, hora_inicio, hora_fin FROM turnos_trabajo ORDER BY id_turno ASC";
    $stmt = $pdo->query($sql);
    $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $turnos = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Turnos de Trabajo</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Turnos de Trabajo</h2>
        
        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_turnos_trabajo.php" class="btn-primary">Nuevo Turno</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Turno</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($turnos)): ?>
                    <tr>
                        <td colspan="5">No hay turnos registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($turnos as $turno): ?>
                        <tr>
                            <td><?= $turno['id_turno'] ?></td>
                            <td><?= htmlspecialchars($turno['nombre_turno']) ?></td>
                            <td><?= htmlspecialchars($turno['hora_inicio']) ?></td>
                            <td><?= htmlspecialchars($turno['hora_fin']) ?></td>
                            <td>
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_turnos_trabajo.php?id=<?= $turno['id_turno'] ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>
                                
                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_turnos_trabajo.php?id=<?= $turno['id_turno'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
