<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // Verificar si el supervisor existe
        $sql_check = "SELECT id_supervisor FROM supervisores WHERE id_supervisor = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id]);
        
        if ($stmt_check->fetch()) {
            // Eliminar el supervisor
            $sql = "DELETE FROM supervisores WHERE id_supervisor = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            echo "<script>alert('Supervisor eliminado exitosamente'); window.location.href='R_supervisor.php';</script>";
        } else {
            echo "<script>alert('Supervisor no encontrado'); window.location.href='R_supervisor.php';</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='R_supervisor.php';</script>";
    }
} else {
    echo "<script>alert('ID de supervisor no válido'); window.location.href='R_supervisor.php';</script>";
}
?>
