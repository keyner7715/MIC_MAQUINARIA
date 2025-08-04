<?php
require_once '../config/db.php';

$id_guardia = $_GET['id_guardia'] ?? 0;
$id_zona = $_GET['id_zona'] ?? 0;

if ($id_guardia && $id_zona) {
    try {
        // Verificar si la relación existe
        $sql_check = "SELECT * FROM guardias_zonas WHERE id_guardia = ? AND id_zona = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_guardia, $id_zona]);
        
        if ($stmt_check->fetch()) {
            // Eliminar la relación
            $sql = "DELETE FROM guardias_zonas WHERE id_guardia = ? AND id_zona = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_guardia, $id_zona]);
            
            echo "<script>alert('Relación eliminada exitosamente'); window.location.href='R_guardia_zona.php';</script>";
        } else {
            echo "<script>alert('Relación no encontrada'); window.location.href='R_guardia_zona.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='R_guardia_zona.php';</script>";
    }
} else {
    echo "<script>alert('Datos no válidos'); window.location.href='R_guardia_zona.php';</script>";
}
?>
