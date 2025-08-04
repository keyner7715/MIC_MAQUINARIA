<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para eliminar turnos de trabajo
verificarPermiso('eliminar');

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // Verificar si el turno existe
        $sql_check = "SELECT id_turno FROM turnos_trabajo WHERE id_turno = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id]);
        
        if ($stmt_check->fetch()) {
            // Eliminar el turno
            $sql = "DELETE FROM turnos_trabajo WHERE id_turno = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            echo "<script>alert('Turno eliminado exitosamente'); window.location.href='R_turnos_trabajo.php';</script>";
        } else {
            echo "<script>alert('Turno no encontrado'); window.location.href='R_turnos_trabajo.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='R_turnos_trabajo.php';</script>";
    }
} else {
    echo "<script>alert('ID de turno no válido'); window.location.href='R_turnos_trabajo.php';</script>";
}
?>
