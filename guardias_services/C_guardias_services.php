<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $estado = $_POST['estado'] ?? '';

    try {
        $sql = "INSERT INTO guardias (nombre, cedula, telefono, direccion, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $cedula, $telefono, $direccion, $estado]);

        echo "<script>alert('Guardia creado exitosamente'); window.location.href='R_guardias_services.php';</script>";
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicado de teléfono
            echo "<script>alert('El teléfono ya está registrado.'); window.history.back();</script>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Guardia</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Crear Nuevo Guardia</h1>
            <nav>
                <ul>
                    <li><a href="../public/menu.php">Inicio</a></li>
                    <li><a href="R_guardias_services.php">Ver Guardias</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Registrar Nuevo Guardia</h2>

                <form method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre">
                    </div>

                    <div class="form-group">
                        <label for="cedula">Cédula:</label>
                        <input type="text" id="cedula" name="cedula" placeholder="Ingrese la cédula">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" required placeholder="Ingrese el teléfono">
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" placeholder="Ingrese la dirección">
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit">Crear Empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>



