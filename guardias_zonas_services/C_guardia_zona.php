<?php
require_once '../config/db.php';

// Obtener lista de guardias
try {
    $sql = "SELECT id_guardia, nombre FROM guardias WHERE estado = 'activo' ORDER BY nombre ASC";
    $stmt = $pdo->query($sql);
    $guardias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al obtener guardias: " . $e->getMessage();
    $guardias = [];
}

// Obtener lista de zonas de vigilancia
try {
    $sql = "SELECT id_zona, nombre_zona FROM zonas_vigilancia ORDER BY nombre_zona ASC";
    $stmt = $pdo->query($sql);
    $zonas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al obtener zonas: " . $e->getMessage();
    $zonas = [];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_guardia = $_POST['id_guardia'] ?? '';
    $id_zona = $_POST['id_zona'] ?? '';

    if ($id_guardia && $id_zona) {
        // Verificar si ya existe la asignación
        $sql_check = "SELECT 1 FROM guardias_zonas WHERE id_guardia = ? AND id_zona = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_guardia, $id_zona]);
        if ($stmt_check->fetch()) {
            $error = "Este guardia ya está asignado a esta zona.";
        } else {
            try {
                $sql = "INSERT INTO guardias_zonas (id_guardia, id_zona) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_guardia, $id_zona]);
                echo "<script>alert('Asignación registrada exitosamente'); window.location.href='R_guardia_zona.php';</script>";
            } catch(PDOException $e) {
                $error = "Error al registrar asignación: " . $e->getMessage();
            }
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Guardia a Zona</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Asignar Guardia a Zona de Vigilancia</h2>
        <a href="R_guardia_zona.php" class="btn-primary">Volver a la lista</a>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="id_guardia">Guardia:</label>
                <select name="id_guardia" id="id_guardia" required>
                    <option value="">Seleccione un guardia</option>
                    <?php foreach ($guardias as $g): ?>
                        <option value="<?= $g['id_guardia'] ?>"><?= htmlspecialchars($g['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_zona">Zona de Vigilancia:</label>
                <select name="id_zona" id="id_zona" required>
                    <option value="">Seleccione una zona</option>
                    <?php foreach ($zonas as $z): ?>
                        <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre_zona']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit">Asignar</button>
        </form>
    </div>
</body>
</html>
