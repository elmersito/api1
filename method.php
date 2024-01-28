<?php
require "config/Conexion.php";

  //print_r($_SERVER['REQUEST_METHOD']);
  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      // Consulta SQL para seleccionar datos de la tabla
$sql = "SELECT nombre, apellido, telefono, foto FROM usuarios";

$query = $conexion->query($sql);

if ($query->num_rows > 0) {
    $data = array();
    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }
    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "No se encontraron registros en la tabla.";
}

$conexion->close();
      break;
    case 'POST':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir los datos del formulario HTML
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $telefono = $_POST['telefono'];

            // Insertar los datos en la tabla "usuarios"
            $sql = "INSERT INTO usuarios (nombre, apellido, telefono) VALUES ('$nombre', '$apellido', '$telefono')";

            if ($conexion->query($sql) === TRUE) {
                echo "Datos insertados con éxito.";
            } else {
                echo "Error al insertar datos: " . $conexion->error;
            }
        } else {
            echo "Esta API solo admite solicitudes POST.";
        }

        $conexion->close();
        break;

      case 'PATCH':
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            parse_str(file_get_contents("php://input"), $datos);

            $id_num = $datos['id_num'];
            $nombre = $datos['nombre'];
            $apellido = $datos['apellido'];
            $telefono = $datos['telefono'];

            if ($_SERVER['REQUEST_METHOD'] === 'PATCH') { // Método PATCH
                $actualizaciones = array();
                if (!empty($nombre)) {
                    $actualizaciones[] = "nombre = '$nombre'";
                }
                if (!empty($apellido)) {
                    $actualizaciones[] = "apellido = '$apellido'";
                }
                if (!empty($telefono)) {
                    $actualizaciones[] = "telefono = '$telefono'";
                }

                $actualizaciones_str = implode(', ', $actualizaciones);
                $sql = "UPDATE usuarios SET $actualizaciones_str WHERE id_num = $id_num";
            }

            if ($conexion->query($sql) === TRUE) {
                echo "Registro actualizado con éxito.";
            } else {
                echo "Error al actualizar registro: " . $conexion->error;
            }
        } else {
            echo "Método de solicitud no válido.";
        }

        $conexion->close();
        break;

    case 'PUT':
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"), $datos);

            $id_num = $datos['id_num'];
            $nombre = $datos['nombre'];
            $apellido = $datos['apellido'];
            $telefono = $datos['telefono'];

            $sql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', telefono = '$telefono' WHERE id_num = $id_num";
            
            if ($conexion->query($sql) === TRUE) {
                echo "Registro actualizado con éxito.";
            } else {
                echo "Error al actualizar registro: " . $conexion->error;
            }
        } else {
            echo "Método de solicitud no válido.";
        }

        $conexion->close();
        break;
  
      
    case 'DELETE':
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
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
        $conexion->close();
        break;

    default:
        echo 'Tipo de solicitud no definido.';
}

?>