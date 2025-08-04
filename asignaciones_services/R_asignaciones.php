<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar asignaciones
verificarPermiso('listar');

try {
    // Consulta para obtener las asignaciones con los nombres relacionados
    $sql = "
        SELECT 
            a.id_asignacion,
            g.nombre AS nombre_guardia,
            s.descripcion AS servicio,
            t.nombre_turno AS turno,
            t.hora_inicio,
            t.hora_fin,
            sp.nombre AS nombre_supervisor,
            a.fecha_asignacion
        FROM asignaciones a
        INNER JOIN guardias g ON a.id_guardia = g.id_guardia
        INNER JOIN servicios_contratados s ON a.id_servicio = s.id_servicio
        INNER JOIN turnos_trabajo t ON a.id_turno = t.id_turno
        LEFT JOIN supervisores sp ON a.id_supervisor = sp.id_supervisor
        ORDER BY a.id_asignacion ASC
    ";
    $stmt = $pdo->query($sql);
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $asignaciones = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Asignaciones</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Asignaciones</h2>
        
        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_asignaciones.php" class="btn-primary">Nueva Asignación</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guardia</th>
                    <th>Servicio</th>
                    <th>Turno</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Supervisor</th>
                    <th>Fecha Asignación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($asignaciones)): ?>
                    <tr>
                        <td colspan="8">No hay asignaciones registradas</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($asignaciones as $asignacion): ?>
                        <tr>
                            <td><?= $asignacion['id_asignacion'] ?></td>
                            <td><?= htmlspecialchars($asignacion['nombre_guardia']) ?></td>
                            <td><?= htmlspecialchars($asignacion['servicio']) ?></td>
                            <td><?= htmlspecialchars($asignacion['turno']) ?></td>
                            <td><?= htmlspecialchars($asignacion['hora_inicio']) ?></td>
                            <td><?= htmlspecialchars($asignacion['hora_fin']) ?></td>
                            <td><?= htmlspecialchars($asignacion['nombre_supervisor'] ?? 'Sin Supervisor') ?></td>
                            <td><?= htmlspecialchars($asignacion['fecha_asignacion']) ?></td>
                            <td>
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_asignaciones.php?id=<?= $asignacion['id_asignacion'] ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>
                                <br></br>
                                
                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_asignaciones.php?id=<?= $asignacion['id_asignacion'] ?>" class="btn-delete">Eliminar</a>
                                <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>