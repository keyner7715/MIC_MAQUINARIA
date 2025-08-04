<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // Verificar si el registro existe
        $sql_check = "SELECT id_zona FROM zonas_vigilancia WHERE id_zona = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id]);
        
        if ($stmt_check->fetch()) {
            // Eliminar el registro
            $sql = "DELETE FROM zonas_vigilancia WHERE id_zona = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            echo "<script>alert('Registro eliminado exitosamente'); window.location.href='R_zonas_vigilancia.php';</script>";
        } else {
            echo "<script>alert('Registro no encontrado'); window.location.href='R_zonas_vigilancia.php';</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='R_zonas_vigilancia.php';</script>";
    }
} else {
    echo "<script>alert('ID no válido'); window.location.href='R_zonas_vigilancia.php';</script>";
}
?>
