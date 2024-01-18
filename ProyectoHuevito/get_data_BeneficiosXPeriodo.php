<?php
// Conexión a la base de datos
$conn = mysqli_connect('localhost', 'root', '', 'proyectoiot');

// Verificar la conexión
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Obtén el valor del parámetro 'incubadora' enviado por la consulta AJAX
$selectedIncubadora = $_GET['incubadora'];
$selectedPeriodo = $_GET['periodo'];

// Consulta para obtener los datos filtrados de la base de datos
$query = "SELECT id_eclosion, beneficio
    FROM eclosion
    WHERE id_incubadora = $selectedIncubadora AND id_eclosion <= $selectedPeriodo";

$result = mysqli_query($conn, $query);

// Obtener los datos de la consulta en un formato adecuado para la gráfica
$labels = array();
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['id_eclosion'];
    $data[] = $row['beneficio'];
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Devolver los datos filtrados en formato JSON
$response = array(
    'labels' => $labels,
    'data' => $data
);

header('Content-Type: application/json');
echo json_encode($response);
?>