<?php
require_once '../config/db.php';

$id_servicio = $_GET['id'] ?? 0;

if ($id_servicio) {
    try {
        // Verificar si el servicio contratado existe
        $sql_check = "SELECT * FROM servicios_contratados WHERE id_servicio = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_servicio]);

        if ($stmt_check->fetch()) {
            // Eliminar el servicio contratado
            $sql_delete = "DELETE FROM servicios_contratados WHERE id_servicio = ?";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute([$id_servicio]);

            echo "<script>alert('Servicio contratado eliminado exitosamente'); window.location.href='R_servicios_contratados.php';</script>";
        } else {
            echo "<script>alert('Servicio contratado no encontrado'); window.location.href='R_servicios_contratados.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar el servicio contratado: " . $e->getMessage() . "'); window.location.href='R_servicios_contratados.php';</script>";
    }
} else {
    echo "<script>alert('ID de servicio no válido'); window.location.href='R_servicios_contratados.php';</script>";
}
?>
