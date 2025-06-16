<?php
header('Content-Type: application/json'); // Responder JSON

// Configuración conexión
$host = 'localhost';
$usuario = 'root';      // Cambia por tu usuario MySQL
$password = '';  // Cambia por tu contraseña MySQL
$baseDatos = 'fichah';

// Crear conexión
$conn = new mysqli($host, $usuario, $password, $baseDatos);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos.']);
    exit();
}

// Recibir y sanitizar datos
$nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
$apellido = $conn->real_escape_string($_POST['apellido'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$ciudad = $conn->real_escape_string($_POST['ciudad'] ?? '');
$departamento = $conn->real_escape_string($_POST['departamento'] ?? '');
$mensaje = $conn->real_escape_string($_POST['mensaje'] ?? '');

// Validar campos obligatorios
if (empty($nombre) || empty($apellido) || empty($email) || empty($ciudad) || empty($departamento) || empty($mensaje)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos.']);
    exit();
}

// Preparar consulta para insertar
$sql = "INSERT INTO fichas (nombre, apellido, email, ciudad, departamento, mensaje, fecha) VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    exit();
}

// Vincular parámetros y ejecutar
$stmt->bind_param("ssssss", $nombre, $apellido, $email, $ciudad, $departamento, $mensaje);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Ficha registrada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la ficha.']);
}

$stmt->close();

$conn->close();
?>
