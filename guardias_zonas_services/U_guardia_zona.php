<?php
require_once '../config/db.php';

$id_guardia = $_GET['id_guardia'] ?? '';
$id_zona = $_GET['id_zona'] ?? '';
$error = '';
$success = '';

// Obtener todos los guardias y zonas para los select
try {
    $stmt_guardias = $pdo->query("SELECT id_guardia, nombre FROM guardias");
    $guardias = $stmt_guardias->fetchAll(PDO::FETCH_ASSOC);

    $stmt_zonas = $pdo->query("SELECT id_zona, nombre_zona FROM zonas_vigilancia");
    $zonas = $stmt_zonas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener datos: " . $e->getMessage();
}

// Verificar que los IDs actuales sean válidos
if ($id_guardia && $id_zona) {
    try {
        $stmt_guardia = $pdo->prepare("SELECT * FROM guardias WHERE id_guardia = ?");
        $stmt_guardia->execute([$id_guardia]);
        $guardia = $stmt_guardia->fetch(PDO::FETCH_ASSOC);

        $stmt_zona = $pdo->prepare("SELECT * FROM zonas_vigilancia WHERE id_zona = ?");
        $stmt_zona->execute([$id_zona]);
        $zona = $stmt_zona->fetch(PDO::FETCH_ASSOC);

        if (!$guardia || !$zona) {
            echo "<script>alert('Guardia o Zona no encontrados'); window.location.href='R_guardia_zona.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        $error = "Error al obtener datos: " . $e->getMessage();
    }
} else {
    echo "<script>alert('Datos inválidos'); window.location.href='R_guardia_zona.php';</script>";
    exit;
}

// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_id_guardia = $_POST['id_guardia'] ?? '';
    $nuevo_id_zona = $_POST['id_zona'] ?? '';

    if ($nuevo_id_guardia && $nuevo_id_zona) {
        try {
            // Actualizar la relación en la tabla guardias_zonas
            $stmt_update = $pdo->prepare("UPDATE guardias_zonas SET id_guardia = ?, id_zona = ? WHERE id_guardia = ? AND id_zona = ?");
            $stmt_update->execute([$nuevo_id_guardia, $nuevo_id_zona, $id_guardia, $id_zona]);

            $success = "Datos actualizados exitosamente.";
            // Actualizar los IDs actuales para reflejar los cambios
            $id_guardia = $nuevo_id_guardia;
            $id_zona = $nuevo_id_zona;
        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Guardia y Zona</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Guardia y Zona</h2>
        <a href="R_guardia_zona.php" class="btn-primary">Volver a la lista</a>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="id_guardia">Seleccionar Guardia:</label>
                <select name="id_guardia" id="id_guardia" required>
                    <option value="">Seleccione un guardia</option>
                    <?php foreach ($guardias as $g): ?>
                        <option value="<?= htmlspecialchars($g['id_guardia']) ?>" <?= $g['id_guardia'] == $id_guardia ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_zona">Seleccionar Zona:</label>
                <select name="id_zona" id="id_zona" required>
                    <option value="">Seleccione una zona</option>
                    <?php foreach ($zonas as $z): ?>
                        <option value="<?= htmlspecialchars($z['id_zona']) ?>" <?= $z['id_zona'] == $id_zona ? 'selected' : '' ?>>
                            <?= htmlspecialchars($z['nombre_zona']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</body>
</html>
