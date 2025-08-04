<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';

    try {
        $sql = "INSERT INTO supervisores (cedula, nombre, telefono, correo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cedula, $nombre, $telefono, $correo]);

        echo "<script>alert('Supervisor creado exitosamente'); window.location.href='R_supervisor.php';</script>";
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
    <title>Crear Supervisor</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Crear Nuevo Supervisor</h1>
            <nav>
                <ul>
                    <li><a href="../public/menu.php">Inicio</a></li>
                    <li><a href="R_supervisor.php">Ver Supervisor</a></li>

                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Registrar Nuevo Supervisor</h2>

                <form method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre del supervisor">
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cedula:</label>
                        <input type="text" id="cedula" name="cedula" required placeholder="Ingrese la cédula del supervisor">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" required placeholder="Ingrese el teléfono del supervisor">
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Electrónico:</label>
                        <input type="email" id="correo" name="correo" required placeholder="Ingrese el correo del supervisor">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit">Crear supervisor</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
