<?php
    include 'header.php';

    // ================ TEMPERATURA ================

    $query_temperatura = "SELECT fechaHora, temperatura FROM incubadoraporusuario";
    $result_temperatura = mysqli_query($conn, $query_temperatura);
    
    // Obtener los datos de la consulta en un formato adecuado para el gráfico
    $labels_temperatura = array();
    $data_temperatura = array();
    
    while ($row = mysqli_fetch_assoc($result_temperatura)) {
        $labels_temperatura[] = date('H:i:s', strtotime($row['fechaHora'])); // Obtener solo la parte de la hora
        $data_temperatura[] = $row['temperatura'];
    }

    // ================ HUMEDAD ================

    $query_humedad = "SELECT fechaHora, humedad FROM incubadoraporusuario";
    $result_humedad = mysqli_query($conn, $query_humedad);
    
    // Obtener los datos de la consulta en un formato adecuado para el gráfico
    $labels_humedad = array();
    $data_humedad = array();
    
    while ($row = mysqli_fetch_assoc($result_humedad)) {
        $labels_humedad[] = date('H:i:s', strtotime($row['fechaHora'])); // Obtener solo la parte de la hora
        $data_humedad[] = $row['humedad'];
    }
    
    // ================ PORCENTAJE ECLOSION ================

    $query_eclosion = "SELECT id_eclosion, porcentajeEclosion FROM eclosion";
    $result_eclosion = mysqli_query($conn, $query_eclosion);
    
    // Obtener los datos de la consulta en un formato adecuado para el gráfico
    $labels_eclosion = array();
    $data_eclosion = array();
    
    // Obtener los datos de la consulta en un formato adecuado para la gráfica
    while ($row = mysqli_fetch_assoc($result_eclosion)) {
        $labels_eclosion[] = $row['id_eclosion'];
        $data_eclosion[] = $row['porcentajeEclosion'];
    }

    // ================ BENEFICIO ================
    
    $query_beneficio = "SELECT id_eclosion, beneficio FROM eclosion";
    $result_beneficio = mysqli_query($conn, $query_beneficio);
    
    // Obtener los datos de la consulta en un formato adecuado para el gráfico
    $labels_beneficio = array();
    $data_beneficio = array();
    
    // Obtener los datos de la consulta en un formato adecuado para la gráfica
    while ($row = mysqli_fetch_assoc($result_beneficio)) {
        $labels_beneficio[] = $row['id_eclosion'];
        $data_beneficio[] = $row['beneficio'];
    }

    // ================ CONSUMO ENERGETICO ================
    
    $query_comsumoEnergetico = "SELECT id_eclosion, costoEnergetico FROM eclosion";
    $result_comsumoEnergetico = mysqli_query($conn, $query_comsumoEnergetico);
    
    // Obtener los datos de la consulta en un formato adecuado para el gráfico
    $labels_comsumoEnergetico = array();
    $data_comsumoEnergetico = array();
    
    // Obtener los datos de la consulta en un formato adecuado para la gráfica
    while ($row = mysqli_fetch_assoc($result_comsumoEnergetico)) {
        $labels_comsumoEnergetico[] = $row['id_eclosion'];
        $data_comsumoEnergetico[] = $row['costoEnergetico'];
    }
    
    mysqli_close($conn);

    
?>

    <div class="container text-center menu">
        
        <h1 class="titulo">RESUMEN DASHBOARD</h1>
        <div class="row align-items-start text-center justify-content-center">

            <div class="col-6 col-sm-4 my-2">
                <canvas id="grafico_temperatura"></canvas>
                <h2>Temperatura</h2>
            </div>
            <div class="col-6 col-sm-4 my-2">
                <canvas id="grafico_humedad"></canvas>
                <h2>Humedad</h2>
            </div>
            <div class="col-6 col-sm-4 my-2">
                <canvas id="grafico_beneficios"></canvas>
                <h2>Beneficios</h2>
            </div>
            <div class="col-6 col-sm-4 my-2">
                <canvas id="grafico_tasaEclosion"></canvas>
                <h2>Tasa de eclosión</h2>
            </div>
            <div class="col-6 col-sm-4 my-2">
                <canvas id="grafico_consumoEnergetico"></canvas>
                <h2>Consumo energético</h2>
            </div>

            <div class="col-6 col-sm-4 my-2">
                <!-- <canvas id="grafico"></canvas> -->
                <h2>General</h2>
                <button onclick="generarPrediccion()">Generar Predicción</button>
            </div>
        </div>
        <div class="text-center mt-5">
        <button type="button btn-volver" class="btn btn-primary" onclick="window.location.href='index.php'"><b>Volver</b></button>
    </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- TEMPERATURA -->
<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico_temperatura').getContext('2d');

    // Define los datos para las barras
    var labels_temperatura = <?php echo json_encode($labels_temperatura); ?>; // Etiquetas iniciales para las barras obtenidas de la base de datos
    var data_temperatura = <?php echo json_encode($data_temperatura); ?>; // Valores iniciales para las barras obtenidos de la base de datos

    // Crea el gráfico de barras con los datos iniciales
    var myChart_temperatura = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels_temperatura,
            datasets: [{
                label: 'Valores de las barras',
                data: data_temperatura,
                backgroundColor: 'rgba(255, 0, 0, 0.4)', // Color de fondo de las barras
                borderColor: 'rgba(177, 2, 2 , 1)', // Color del borde de las barras
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
    function actualizarDatos_temperatura() {
        // Realizar una petición AJAX para obtener los datos actualizados
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                // Actualizar el gráfico con los nuevos datos
                myChart_temperatura.data.labels = response.labels;
                myChart_temperatura.data.datasets[0].data = response.data;
                myChart_temperatura.update();
            }
        };
        xhr.open('GET', 'obtener_datos_actualizados_temperatura.php', true);
        xhr.send();
    }

</script>

<!-- HUMEDAD -->
<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico_humedad').getContext('2d');

    // Define los datos para las barras
    var labels_humedad = <?php echo json_encode($labels_humedad); ?>; // Etiquetas iniciales para las barras obtenidas de la base de datos
    var data_humedad = <?php echo json_encode($data_humedad); ?>; // Valores iniciales para las barras obtenidos de la base de datos

    // Crea el gráfico de barras con los datos iniciales
    var myChart_humedad = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels_humedad,
            datasets: [{
                label: 'Valores de las barras',
                data: data_humedad,
                backgroundColor: 'rgba(3, 22, 255, 0.4)', // Color de fondo de las barras
                borderColor: 'rgba(1, 12, 150, 1)', // Color del borde de las barras
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
    function actualizarDatos_humedad() {
        // Realizar una petición AJAX para obtener los datos actualizados
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                // Actualizar el gráfico con los nuevos datos
                myChart_humedad.data.labels = response.labels;
                myChart_humedad.data.datasets[0].data = response.data;
                myChart_humedad.update();
            }
        };
        xhr.open('GET', 'obtener_datos_actualizados_humedad.php', true);
        xhr.send();
    }
</script>

<!-- BENEFICIO -->
<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico_beneficios').getContext('2d');

    // Define los datos para las barras
    var labels_beneficio = <?php echo json_encode($labels_beneficio); ?>; // Etiquetas iniciales para las barras obtenidas de la base de datos
    var data_beneficio = <?php echo json_encode($data_beneficio); ?>; // Valores iniciales para las barras obtenidos de la base de datos

    // Agregar la palabra "periodo" a cada valor en el eje X
    var labeledData_beneficio = labels_beneficio.map(function (label) {
        return "Periodo " + label;
    });
    
    // Crea el gráfico de barras con los datos iniciales
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labeledData_beneficio,
            datasets: [{
                label: 'Valores de las barras',
                data: data_beneficio,
                backgroundColor: 'rgba(46, 255, 0, 0.4)', // Color de fondo de las barras
                borderColor: 'rgba(55, 177, 2, 1)', // Color del borde de las barras
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
</script>

<!-- TASA DE ECLOSION -->
<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico_tasaEclosion').getContext('2d');

    // Define los datos para las barras
    var labels_eclosion = <?php echo json_encode($labels_eclosion); ?>; // Etiquetas iniciales para las barras obtenidas de la base de datos
    var data_eclosion = <?php echo json_encode($data_eclosion); ?>; // Valores iniciales para las barras obtenidos de la base de datos
    
    var labeledData_eclosion = labels_eclosion.map(function (label) {
        return "Periodo " + label;
    });

    // Crea el gráfico de barras con los datos iniciales
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labeledData_eclosion,
            datasets: [{
                label: 'Valores de las barras',
                data: data_eclosion,
                backgroundColor: 'rgba(46, 255, 0, 0.4)', // Color de fondo de las barras
                borderColor: 'rgba(55, 177, 2, 1)', // Color del borde de las barras
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
</script>

<!-- CONSUMO ENERGETICO -->
<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico_consumoEnergetico').getContext('2d');

    // Define los datos para las barras
    var labels_comsumoEnergetico = <?php echo json_encode($labels_comsumoEnergetico); ?>; // Etiquetas iniciales para las barras obtenidas de la base de datos
    var data_comsumoEnergetico = <?php echo json_encode($data_comsumoEnergetico); ?>; // Valores iniciales para las barras obtenidos de la base de datos

    var labeledData_comsumoEnergetico = labels_comsumoEnergetico.map(function (label) {
        return "Periodo " + label;
    });

    // Crea el gráfico de barras con los datos iniciales
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labeledData_comsumoEnergetico,
            datasets: [{
                label: 'Valores de las barras',
                data: data_comsumoEnergetico,
                backgroundColor: 'rgba(216, 0, 255, 0.4)', // Color de fondo de las barras
                borderColor: 'rgba(137, 2, 177, 1)', // Color del borde de las barras
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
</script>

<script>
    setInterval(actualizarDatos_temperatura, 5000);
    setInterval(actualizarDatos_humedad, 5000);
</script>

<!-- PREDICCIÓN -->
<script>
        function generarPrediccion() {
            // Cargar el contenido del archivo y mostrarlo en el cuadro
            fetch('resultado.txt')
                .then(response => response.text())
                .then(data => {
                    var cuadro = document.createElement('div');
                    cuadro.setAttribute('id', 'cuadro');
                    cuadro.innerHTML = `
                        <button id="cerrar" onclick="cerrarCuadro()">Cerrar</button>
                        <h1>Resultado de la regresión lineal</h1>
                        <p>${data}</p>
                    `;
                    document.body.appendChild(cuadro);
                });
        }

        function cerrarCuadro() {
            var cuadro = document.getElementById('cuadro');
            cuadro.parentNode.removeChild(cuadro);
        }
    </script>
<?php
    include 'footer.php';
?>