<?php
    // Conexión a la base de datos
    $conn = mysqli_connect('localhost', 'root', '', 'proyectoiot');

    // Verificar la conexión
    if (!$conn) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    // Consulta para obtener los datos de la base de datos
    $query = "SELECT fechaHora, humedad FROM incubadoraporusuario";
    $result = mysqli_query($conn, $query);

    // Obtener los datos de la consulta en un formato adecuado para el gráfico
    $labels = array();
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = date('H:i:s', strtotime($row['fechaHora'])); // Obtener solo la parte de la hora
        $data[] = $row['humedad'];
    }

    // Calcular las etiquetas adicionales
    $maxHumedad = max($data);
    $minHumedad = min($data);
    $rangoHumedad = $maxHumedad - $minHumedad;
    $promedioHumedad = array_sum($data) / count($data);
    $ultimaHumedad = end($data);
    $humedadOptimaMax = 65 - end($data);
    $humedadOptimaMin = end($data) - 55;

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);

    // Crear un array con los datos actualizados
    $response = array(
        'labels' => $labels,
        'data' => $data,
        'maxHumedad' => $maxHumedad,
        'minHumedad' => $minHumedad,
        'rangoHumedad' => $rangoHumedad,
        'promedioHumedad' => $promedioHumedad,
        'ultimaHumedad' => $ultimaHumedad
    );

    // Convertir el array a formato JSON y enviar la respuesta
    echo json_encode($response);
?>