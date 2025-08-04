<?php
require_once '../config/db.php';
require_once '../auth_services/permisos.php';

// Verificar permiso para eliminar asignaciones
verificarPermiso('eliminar');

$id_asignacion = $_GET['id'] ?? 0;

if ($id_asignacion) {
    try {
        // Verificar si la asignación existe
        $sql_check = "SELECT id_asignacion FROM asignaciones WHERE id_asignacion = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_asignacion]);

        if ($stmt_check->fetch()) {
            // Eliminar la asignación
            $sql = "DELETE FROM asignaciones WHERE id_asignacion = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_asignacion]);

            echo "<script>alert('Asignación eliminada exitosamente'); window.location.href='R_asignaciones.php';</script>";
        } else {
            echo "<script>alert('Asignación no encontrada'); window.location.href='R_asignaciones.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='R_asignaciones.php';</script>";
    }
} else {
    echo "<script>alert('ID de asignación no válido'); window.location.href='R_asignaciones.php';</script>";
}
?>