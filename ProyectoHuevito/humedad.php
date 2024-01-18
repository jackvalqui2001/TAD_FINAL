<?php
    include 'header.php';
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
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex flex-row">
                <div>
                    <img src="img/humedad.png" alt="Imagen de humedad" height="200px">
                </div>
                <div class="d-flex flex-column px-2">
                    <p class="cifra respuesta">55% - 65%</p>
                    <p class="respuesta"><b>HUMEDAD ÓPTIMA</b></p>
                    <p><i>Humedad Actual: <span id="humedad-actual"><?php echo $ultimaHumedad; ?></span></i></p>
                    <?php if ($humedadOptimaMax < 0 || $humedadOptimaMin < 0) : ?>
                        <p class="mensaje-atencion" style="color: red;">Aviso: Requiere Atención</p>
                    <?php else : ?>
                        <p class="mensaje-atencion" style="color: green;">Aviso: No se requiere atención</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- COMBO BOX -->
        <div class="col-lg-6 d-flex flex-row align-items-center">
            <div class="col text-end">
                <h4 class="me-3">Partes del Día</h4>
            </div>
            <div class="col">
                <select class="form-select" id="select-periodo" aria-label="Default select example">
                    <option selected>Seleccionar</option>
                    <option value="1">Mañana 0am - 12pm</option>
                    <option value="2">Tarde 12pm - 18pm</option>
                    <option value="3">Noche 18pm - 0am</option>
                </select>
            </div>
        </div>
        <!--  -->
    </div>

    <div class="row">
        <!-- GRÁFICO -->
        <div class="col-lg-6">
            <canvas id="grafico"></canvas>
        </div>
        <!--  -->
        <!-- ETIQUETAS -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Etiquetas adicionales</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Humedad máxima: <span id="humedad-maxima"><?php echo $maxHumedad; ?></span></li>
                        <li class="list-group-item">Humedad mínima: <span id="humedad-minima"><?php echo $minHumedad; ?></span></li>
                        <li class="list-group-item">Rango de humedad: <span id="rango-humedad"><?php echo $rangoHumedad; ?></span></li>
                        <li class="list-group-item">Humedad promedio: <span id="humedad-promedio"><?php echo number_format($promedioHumedad, 3); ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <button type="button btn-volver" class="btn btn-primary" onclick="window.location.href='index.php'"><b>Volver</b></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico').getContext('2d');

    // Define los datos para las barras
    var initialLabels = <?php echo json_encode($labels); ?>; // Etiquetas iniciales para las barras obtenidas de la base de datos
    var initialData = <?php echo json_encode($data); ?>; // Valores iniciales para las barras obtenidos de la base de datos

    // Crea el gráfico de barras con los datos iniciales
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: initialLabels,
            datasets: [{
                label: 'Valores de las barras',
                data: initialData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                borderWidth: 1 // Ancho del borde de las barras
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Función para actualizar los datos
    function actualizarDatos() {
        // Realizar una petición AJAX para obtener los datos actualizados
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                // Actualizar los valores de las etiquetas adicionales
                document.getElementById('humedad-maxima').textContent = response.maxHumedad;
                document.getElementById('humedad-minima').textContent = response.minHumedad;
                document.getElementById('rango-humedad').textContent = response.rangoHumedad;
                document.getElementById('humedad-promedio').textContent = response.promedioHumedad;
                document.getElementById('humedad-actual').textContent = response.ultimaHumedad;

                // Actualizar el gráfico con los nuevos datos
                myChart.data.labels = response.labels;
                myChart.data.datasets[0].data = response.data;
                myChart.update();
            }
        };
        xhr.open('GET', 'obtener_datos_actualizados_humedad.php', true);
        xhr.send();
    }

    // Agrega un evento change al combobox select-periodo
    var selectPeriodo = document.getElementById('select-periodo');
    selectPeriodo.addEventListener('change', function() {
        var optionValue = parseInt(this.value);

        // Filtrar los datos según la opción seleccionada
        var filteredLabels = [];
        var filteredData = [];

        if (optionValue === 1) {
            // Filtrar por mañana (0am - 12pm)
            for (var i = 0; i < initialLabels.length; i++) {
                var hora = parseInt(initialLabels[i].substring(0, 2));
                if (hora >= 0 && hora <= 12) {
                    filteredLabels.push(initialLabels[i]);
                    filteredData.push(initialData[i]);
                }
            }
        } else if (optionValue === 2) {
            // Filtrar por tarde (12pm - 18pm)
            for (var i = 0; i < initialLabels.length; i++) {
                var hora = parseInt(initialLabels[i].substring(0, 2));
                if (hora >= 12 && hora <= 18) {
                    filteredLabels.push(initialLabels[i]);
                    filteredData.push(initialData[i]);
                }
            }
        } else if (optionValue === 3) {
            // Filtrar por noche (18pm - 0am)
            for (var i = 0; i < initialLabels.length; i++) {
                var hora = parseInt(initialLabels[i].substring(0, 2));
                if (hora >= 18 || hora === 0) {
                    filteredLabels.push(initialLabels[i]);
                    filteredData.push(initialData[i]);
                }
            }
        }

        // Actualizar el gráfico con los datos filtrados
        myChart.data.labels = filteredLabels;
        myChart.data.datasets[0].data = filteredData;
        myChart.update();

        // Actualizar las etiquetas adicionales
        var maxHumedad = Math.max(...filteredData);
        var minHumedad = Math.min(...filteredData);
        var rangoHumedad = maxHumedad - minHumedad;
        var promedioHumedad = filteredData.reduce((a, b) => a + b, 0) / filteredData.length;

        document.getElementById('humedad-maxima').textContent = maxHumedad;
        document.getElementById('humedad-minima').textContent = minHumedad;
        document.getElementById('rango-humedad').textContent = rangoHumedad;
        document.getElementById('humedad-promedio').textContent = promedioHumedad;
    });

    // Actualizar los datos cada 5 segundos
    setInterval(actualizarDatos, 5000);

</script>

<?php
    include 'footer.php';
?>
