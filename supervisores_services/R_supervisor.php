<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar visitantes (acceso a la página)
verificarPermiso('listar');

try {
    $sql = "SELECT * FROM supervisores ORDER BY id_supervisor DESC";
    $stmt = $pdo->query($sql);
    $supervisores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $supervisores = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listar Supervisores</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    
    <div class="container">

        <h2>Lista de Supervisores</h2>

        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_supervisor.php" class="btn-primary">Nuevo supervisor</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($supervisores)): ?>
                    <tr>
                        <td colspan="6">No hay supervisores registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($supervisores as $supervisor): ?>
                        <tr>
                            <td><?= $supervisor['id_supervisor'] ?></td>
                            <td><?= htmlspecialchars($supervisor['nombre']) ?></td>
                            <td><?= htmlspecialchars($supervisor['cedula']) ?></td>
                            <td><?= htmlspecialchars($supervisor['telefono']) ?></td>
                            <td><?= htmlspecialchars($supervisor['correo']) ?></td>
                            <td>
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_supervisor.php?id=<?= $supervisor['id_supervisor'] ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>
                                
                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_supervisor.php?id=<?= $supervisor['id_supervisor'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
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
