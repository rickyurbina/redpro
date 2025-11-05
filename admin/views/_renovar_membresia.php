<?php
require_once '../models/TarjetasModel.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if (TarjetasModel::renovarMembresia($id)) {
        echo json_encode(['success' => true, 'message' => 'Membresía renovada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la renovación']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
}
?>