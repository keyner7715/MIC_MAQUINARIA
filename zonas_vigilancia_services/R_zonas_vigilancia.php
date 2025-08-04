<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar (acceso a la página)
verificarPermiso('listar');

try {
    $sql = "SELECT 
                z.id_zona,
                z.nombre_zona,
                z.direccion,
                c.razon_social AS cliente
            FROM zonas_vigilancia z
            INNER JOIN clientes c ON z.id_cliente = c.id_cliente
            ORDER BY z.id_zona DESC";
    $stmt = $pdo->query($sql);
    $zonas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $zonas = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listar Zonas de Vigilancia</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Zonas de Vigilancia</h2>
        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_zonas_vigilancia.php" class="btn-primary">Nueva Zona</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Zona</th>
                    <th>Nombre Zona</th>
                    <th>Dirección</th>
                    <th>Cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($zonas)): ?>
                    <tr>
                        <td colspan="5">No hay zonas registradas</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($zonas as $zona): ?>
                        <tr>
                            <td><?= $zona['id_zona'] ?></td>
                            <td><?= htmlspecialchars($zona['nombre_zona']) ?></td>
                            <td><?= htmlspecialchars($zona['direccion']) ?></td>
                            <td><?= htmlspecialchars($zona['cliente']) ?></td>
                            <td>
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_zonas_vigilancia.php?id=<?= $zona['id_zona'] ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>
                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_zonas_vigilancia.php?id=<?= $zona['id_zona'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
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
