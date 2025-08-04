<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar
verificarPermiso('listar');

try {
    $sql = "SELECT
                g.id_guardia,
                g.nombre AS nombre_guardia,
                gz.id_zona,
                z.nombre_zona
            FROM guardias_zonas gz
            INNER JOIN guardias g ON gz.id_guardia = g.id_guardia
            INNER JOIN zonas_vigilancia z ON gz.id_zona = z.id_zona
            ORDER BY g.nombre, z.nombre_zona";
    $stmt = $pdo->query($sql);
    $guardias_zonas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $guardias_zonas = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guardias por Zona de Vigilancia</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Guardias Asignados a Zonas de Vigilancia</h2>
        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_guardia_zona.php" class="btn-primary">Nueva Asignación</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Guardia</th>
                    <th>Nombre Guardia</th>
                    <th>ID Zona</th>
                    <th>Nombre Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($guardias_zonas)): ?>
                    <tr>
                        <td colspan="5">No hay asignaciones registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($guardias_zonas as $gz): ?>
                        <tr>
                            <td><?= htmlspecialchars($gz['id_guardia']) ?></td>
                            <td><?= htmlspecialchars($gz['nombre_guardia']) ?></td>
                            <td><?= htmlspecialchars($gz['id_zona']) ?></td>
                            <td><?= htmlspecialchars($gz['nombre_zona']) ?></td>
                            <td>
                    
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_guardia_zona.php?id_guardia=<?= urlencode($gz['id_guardia']) ?>&id_zona=<?= urlencode($gz['id_zona']) ?>&nombre_guardia=<?= urlencode($gz['nombre_guardia']) ?>&nombre_zona=<?= urlencode($gz['nombre_zona']) ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>
                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_guardia_zona.php?id_guardia=<?= urlencode($gz['id_guardia']) ?>&id_zona=<?= urlencode($gz['id_zona']) ?>&nombre_guardia=<?= urlencode($gz['nombre_guardia']) ?>&nombre_zona=<?= urlencode($gz['nombre_zona']) ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">Eliminar</a>
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
