<?php
// Conexión a la base de datos
$conn = mysqli_connect('localhost', 'root', '', 'proyectoiot');

// Verificar la conexión
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Obtén el valor del parámetro "incubadora" de la URL
$incubadora = $_GET['incubadora'];

// Consulta para obtener los periodos filtrados
$queryPeriodos = "SELECT DISTINCT id_eclosion FROM eclosion WHERE id_incubadora = '$incubadora'";
$resultPeriodos = mysqli_query($conn, $queryPeriodos);

// Obtén los periodos en un formato adecuado para la respuesta
$periodos = array();
while ($rowPeriodos = mysqli_fetch_assoc($resultPeriodos)) {
    $periodos[] = $rowPeriodos['id_eclosion'];
}

// Crea un array para la respuesta
$response = array(
    'periodos' => $periodos
);

// Devuelve la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>