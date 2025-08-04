<?php
require_once '../config/db.php';

// Obtener clientes para el select
$clientes = $pdo->query("SELECT id_cliente, razon_social FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_zona = $_POST['nombre_zona'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $id_cliente = $_POST['id_cliente'] ?? '';

    try {
        $sql = "INSERT INTO zonas_vigilancia (nombre_zona, direccion, id_cliente) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_zona, $direccion, $id_cliente]);
        echo "<script>alert('Zona de vigilancia registrada exitosamente'); window.location.href='R_zonas_vigilancia.php';</script>";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Zona de Vigilancia</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Registrar Zona de Vigilancia</h1>
            <nav>
                <ul>
                    <li><a href="../public/menu.php">Inicio</a></li>
                    <li><a href="R_zonas_vigilancia.php">Ver Zonas de Vigilancia</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="nombre_zona">Nombre de la Zona:</label>
                        <input type="text" name="nombre_zona" id="nombre_zona" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" required>
                    </div>
                    <div class="form-group">
                        <label for="id_cliente">Cliente:</label>
                        <select name="id_cliente" id="id_cliente" required>
                            <option value="">Seleccione un cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['id_cliente'] ?>"><?= htmlspecialchars($cliente['razon_social']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">Registrar</button>
                    </div>
                </form>
                <a href="R_zonas_vigilancia.php" class="btn-primary">Volver</a>
            </div>
        </div>
    </main>
</body>
</html>
