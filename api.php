<?php
require 'config/Conexion.php';

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Procesar solicitud GET
   
    $sql = "SELECT id_num, nombre, apellido, telefono FROM usuarios";
    
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $data = array();
        while ($row = $resultado->fetch_assoc()) {
            $data[] = $row;
        }
        // Devolver los resultados en formato JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo "No se encontraron registros en la tabla.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar solicitud POST
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $sql = "INSERT INTO usuarios (nombre, apellido,telefono) VALUES ('$nombre', '$apellido', '$telefono')";

    if ($conexion->query($sql) === TRUE) {
        echo "Datos insertados con éxito.";
    } else {
        echo "Error al insertar datos: " . $conexion->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Procesar solicitud PUT
    parse_str(file_get_contents("php://input"), $putData);
    $id_num = $putData['id_num'];
    $nombre = $putData['nombre'];
    $apellido = $putData['apellido'];
    $telefono = $putData['telefono'];
    $sql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', apellido = '$telefono' WHERE id_num = $id_num";

    if ($conexion->query($sql) === TRUE) {
        echo "Datos actualizados con éxito.";
    } else {
        echo "Error al actualizar datos: " . $conexion->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Procesar solicitud DELETE
    $id_num = $_GET['id_num'];
    $sql = "DELETE FROM usuarios WHERE id_num = $id_num";

    if ($conexion->query($sql) === TRUE) {
        echo "Registro eliminado con éxito.";
    } else {
        echo "Error al eliminar registro: " . $conexion->error;
    }
} else {
    echo "Método de solicitud no válido.";
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
