<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar Servicios Contratados (acceso a la página)
verificarPermiso('listar');

try {
    // Consulta para obtener los servicios contratados junto con el nombre del cliente y el nombre de la zona
    $sql = "SELECT 
                sc.id_servicio,
                sc.descripcion,
                sc.fecha_inicio,
                sc.fecha_fin,
                c.razon_social AS nombre_cliente,
                z.nombre_zona AS nombre_zona
            FROM servicios_contratados sc
            INNER JOIN clientes c ON sc.id_cliente = c.id_cliente
            INNER JOIN zonas_vigilancia z ON sc.id_zona = z.id_zona
            ORDER BY sc.id_servicio DESC";
    $stmt = $pdo->query($sql);
    $servicios_contratados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $servicios_contratados = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listar Servicios Contratados</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Servicios Contratados</h2>

        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_servicios_contratados.php" class="btn-primary">Nuevo Servicio Contratado</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Nombre del Cliente</th>
                    <th>Nombre de la Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($servicios_contratados)): ?>
                    <tr>
                        <td colspan="7">No hay servicios contratados registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($servicios_contratados as $servicio): ?>
                        <tr>
                            <td><?= htmlspecialchars($servicio['id_servicio']) ?></td>
                            <td><?= htmlspecialchars($servicio['descripcion']) ?></td>
                            <td><?= htmlspecialchars($servicio['fecha_inicio']) ?></td>
                            <td><?= htmlspecialchars($servicio['fecha_fin'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($servicio['nombre_cliente']) ?></td>
                            <td><?= htmlspecialchars($servicio['nombre_zona']) ?></td>
                            <td>
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_servicios_contratados.php?id=<?= $servicio['id_servicio'] ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>
                                <br> </br>
                                
                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_servicios_contratados.php?id=<?= $servicio['id_servicio'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
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
