<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // Verificar si el guardia existe
        $sql_check = "SELECT id_guardia FROM guardias WHERE id_guardia = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id]);
        
        if ($stmt_check->fetch()) {
            // Eliminar el guardia
            $sql = "DELETE FROM guardias WHERE id_guardia = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            echo "<script>alert('Guardia eliminado exitosamente'); window.location.href='R_guardias_services.php';</script>";
        } else {
            echo "<script>alert('Guardia no encontrado'); window.location.href='R_guardias_services.php';</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='R_guardias_services.php';</script>";
    }
} else {
    echo "<script>alert('ID de guardia no válido'); window.location.href='R_guardias_services.php';</script>";
}
?>
