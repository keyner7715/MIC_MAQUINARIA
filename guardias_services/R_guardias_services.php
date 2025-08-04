<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para listar guardias (acceso a la página)
verificarPermiso('listar');

try {
    $sql = "SELECT * FROM guardias ORDER BY id_guardia DESC";
    $stmt = $pdo->query($sql);
    $guardias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $guardias = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listar Guardias</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    
    <div class="container">

        <h2>Lista de Guardias</h2>

        <div class="actions">
            <?php if (tienePermiso('crear')): ?>
                <a href="C_guardias_services.php" class="btn-primary">Nuevo Guardia</a>
            <?php endif; ?>
            <a href="../public/menu.php" class="btn-primary">Inicio</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Cedula</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($guardias)): ?>
                    <tr>
                        <td colspan="5">No hay guardias registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($guardias as $guardia): ?>
                        <tr>
                            <td><?= htmlspecialchars($guardia['id_guardia']) ?></td>
                            <td><?= htmlspecialchars($guardia['nombre']) ?></td>
                            <td><?= htmlspecialchars($guardia['cedula']) ?></td>
                            <td><?= htmlspecialchars($guardia['telefono']) ?></td>
                            <td><?= htmlspecialchars($guardia['direccion']) ?></td>
                            <td><?= htmlspecialchars($guardia['estado']) ?></td>
                            <td>
                                <?php if (tienePermiso('editar')): ?>
                                    <a href="U_guardias_services.php?id=<?= $guardia['id_guardia'] ?>" class="btn-edit">Editar</a>
                                <?php endif; ?>

                                <?php if (tienePermiso('eliminar')): ?>
                                    <a href="D_guardias_services.php?id=<?= $guardia['id_guardia'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
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
