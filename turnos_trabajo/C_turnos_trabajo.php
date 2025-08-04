<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para crear turnos de trabajo
verificarPermiso('crear');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_turno = $_POST['nombre_turno'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';

    try {
        $sql = "INSERT INTO turnos_trabajo (nombre_turno, hora_inicio, hora_fin) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_turno, $hora_inicio, $hora_fin]);
        
        echo "<script>alert('Turno creado exitosamente'); window.location.href='R_turnos_trabajo.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Turno de Trabajo</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Crear Nuevo Turno de Trabajo</h1>
            <nav>
                <ul>
                    <li><a href="../public/menu.php">Inicio</a></li>
                    <li><a href="R_turnos_trabajo.php">Ver Turnos</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Registrar Nuevo Turno</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="nombre_turno">Nombre del Turno:</label>
                        <input type="text" id="nombre_turno" name="nombre_turno" required placeholder="Ingrese el nombre del turno">
                    </div>
                    
                    <div class="form-group">
                        <label for="hora_inicio">Hora de Inicio:</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" required>
                    </div>

                    <div class="form-group">
                        <label for="hora_fin">Hora de Fin:</label>
                        <input type="time" id="hora_fin" name="hora_fin" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit">Crear Turno</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
