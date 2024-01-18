<?php
include 'header.php';

// Consulta para obtener los datos de la base de datos
$query = "SELECT id_eclosion, costoEnergetico FROM eclosion";
$result = mysqli_query($conn, $query);

// Obtener los datos de la consulta en un formato adecuado para la gráfica
$labels = array();
$data = array();

if ($row = mysqli_fetch_assoc($result)) {
    $labels[] = "Consumo Energético por periodo";
    $data[] = 0;
}
?>

    <div class="container">
        <h1 class="titulo">Consumo energético</h1>
        <h4 class="subtitulo">Consumo actual</h4>
        <div class="d-flex flex-row justify-content-center">
            <div>
                <img src="img/consumo_energetico.png" alt="Imagen de consumo_energetico" height="200px">
            </div>
            <div class="d-flex flex-column px-2">
                <?php if (isset($_POST['filtrar-btn'])) {
                    $usuario = $_SESSION['usuario'];
                    $id_incubadora = $_POST['select-incubadora'];
                    $periodo_final = $_POST['select-periodo-final'];
                    $query = "SELECT dias, costoEnergetico FROM eclosion WHERE id_incubadora = $id_incubadora AND id_eclosion = $periodo_final";
                    $result = mysqli_query($conn, $query);
                    if ($result->num_rows > 0) {
                        // Recorrer los resultados y mostrarlos
                        while ($row = $result->fetch_assoc()) {
                            $num_dias = $row["dias"];
                            $costoEnergetico = $row["costoEnergetico"];
                        }
                        $consumoXhora = 0.0503; // kWh
                        $consumoEnergetico = $consumoXhora * 24 * $num_dias;
                    } else {
                        echo "No se encontraron resultados";
                    }
                ?>
                <p class="cifra respuesta"><?php echo $consumoEnergetico?> Kwh</p>
                <p class="respuesta"><b>Consumo óptimo</b></p>
                <p><i>Consumo en soles: S/ <?php echo $costoEnergetico?></i></p>
                <?php } ?>
            </div>
        </div>

    <!-- INCUBADORAS -->
    <form action="consumo-energetico.php" method="post">
        <div class="row justify-content-center">
            <div class="col-lg-6 d-flex flex-row">
                <div class="col text-end">
                    <h4 class="me-3">Seleccionar Incubadora</h4>
                </div>
                <div class="col">
                    <select class="form-select" id="select-incubadora" name="select-incubadora" aria-label="Default select example">
                        <option selected>Seleccionar</option>
                        <?php
        
        // Conexión a la base de datos
                        $conn = mysqli_connect('localhost', 'root', '', 'proyectoiot');
    
                        // Verificar la conexión
                        if (!$conn) {
                            die('Error de conexión: ' . mysqli_connect_error());
                        }
        
         // Consulta para obtener las incubadoras del usuario
                        $usuario = $_SESSION['usuario'];
                        $query = "SELECT DISTINCT a.id_incubadora, b.Descripcion
                        FROM incubadoraporusuario a
                        JOIN incubadora b on a.id_incubadora = b.id_incubadora
                        WHERE a.nombre_usuario = '$usuario'";
    
        $result = mysqli_query($conn, $query);
        // Generar opciones del combo box
                        while ($row = mysqli_fetch_assoc($result)) {
                            $idIncubadora = $row['id_incubadora'];
                            $nombreIncubadora = $row['Descripcion'];
                            $opcion = $idIncubadora . ' - ' . $nombreIncubadora;
                            echo "<option value='$idIncubadora'>$opcion</option>";
                        }
    
                        // Cerrar la conexión a la base de datos
                        mysqli_close($conn);
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- -->
    
        <!-- Periodos Final-->
        <div class="row justify-content-center">
            <div class="col-lg-6 d-flex flex-row">
                <div class="col text-end">
                    <h4 class="me-3">Periodo Final</h4>
                </div>
                <div class="col">
                    <select class="form-select" id="select-periodo-final" name="select-periodo-final" aria-label="Default select example">
                        <option selected>Seleccionar</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary" id="filtrar-btn" name="filtrar-btn">Filtrar</button>
                </div>
            </div>
        </div>
    </form>
    <!-- -->

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <canvas id="grafico"></canvas>
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="button btn-volver" class="btn btn-primary" onclick="window.location.href='index.php'"><b>Volver</b></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    // Obtén el contexto del lienzo (canvas)
    var ctx = document.getElementById('grafico').getContext('2d');

    // Define los datos iniciales para las barras
    var labels = <?php echo json_encode($labels); ?>; // Etiquetas para el eje X obtenidas de la base de datos
    var data = <?php echo json_encode($data); ?>; // Valores para el eje Y obtenidos de la base de datos

    // Agregar la palabra "periodo" a cada valor en el eje X
    var labeledData = labels.map(function (label) {
        return "Periodo " + label;
    });

    // Crea el gráfico de barras con los datos iniciales
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labeledData,
            datasets: [{
                label: 'Valores de las barras',
                data: data,
                backgroundColor: 'rgba(249, 58, 11, 0.6)',
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

//============================================== COMBO BOX PERIODO FINAL=============================================
    // Obtén el elemento select del combo box "Seleccionar Incubadora"
    var selectIncubadora = document.getElementById('select-incubadora');

    // Agrega un listener al evento 'change' del select
    selectIncubadora.addEventListener('change', function () {
        // Obtén el valor seleccionado
        var selectedValue = selectIncubadora.value;

        // Verifica si se seleccionó una opción válida
        if (selectedValue !== '') {
            // Obtén el elemento select del combo box "Periodos"
            var selectPeriodos = document.getElementById('select-periodo-final');

            // Elimina todas las opciones existentes en el combo box "Periodos"
            while (selectPeriodos.firstChild) {
                selectPeriodos.removeChild(selectPeriodos.firstChild);
            }

            // Crea una opción predeterminada en el combo box "Periodos"
            var defaultOption = document.createElement('option');
            defaultOption.text = 'Seleccionar';
            defaultOption.selected = true;
            selectPeriodos.appendChild(defaultOption);

            // Realiza una nueva consulta en la base de datos para obtener los periodos filtrados
            var xhrPeriodos = new XMLHttpRequest();
            xhrPeriodos.open('GET', 'get_periodos.php?incubadora=' + selectedValue, true);
            xhrPeriodos.onreadystatechange = function () {
                if (xhrPeriodos.readyState === 4 && xhrPeriodos.status === 200) {
                    // Agrega las opciones del combo box "Periodos" con los periodos obtenidos de la respuesta
                    var responsePeriodos = JSON.parse(xhrPeriodos.responseText);
                    var periodos = responsePeriodos.periodos;
                    for (var i = 0; i < periodos.length; i++) {
                        var periodoOption = document.createElement('option');
                        periodoOption.value = periodos[i];
                        periodoOption.text = 'Periodo ' + periodos[i];
                        selectPeriodos.appendChild(periodoOption);
                    }
                }
            };
            xhrPeriodos.send();
        }
    });

    //===========================================================================================

    //=================================== GRÁFICO INCIAL ===================================
    // Obtén el elemento select del combo box "Periodos"
    var selectPeriodos = document.getElementById('select-incubadora');

    // Agrega un listener al evento 'change' del select
    selectPeriodos.addEventListener('change', function () {
        // Obtén el valor seleccionado
        var selectedPeriodo = selectPeriodos.value;

        // Verifica si se seleccionó una opción válida
        if (selectedPeriodo !== '') {
            // Obtén el valor seleccionado de la incubadora
            var selectedIncubadora = selectIncubadora.value;

            // Realiza una nueva consulta en la base de datos para obtener los datos filtrados
            var xhrData = new XMLHttpRequest();
            xhrData.open('GET', 'get_data_consumo-energetico.php?incubadora=' + selectedIncubadora + '&periodo=' + selectedPeriodo, true);
            xhrData.onreadystatechange = function () {
                if (xhrData.readyState === 4 && xhrData.status === 200) {
                    // Actualiza los datos del gráfico con los datos filtrados obtenidos de la respuesta
                    var responseData = JSON.parse(xhrData.responseText);
                    labels = responseData.labels;
                    data = responseData.data;
                    labeledData = labels.map(function (label) {
                        return "Periodo " + label;
                    });
                    myChart.data.labels = labeledData;
                    myChart.data.datasets[0].data = data;
                    myChart.update();
                }
            };
            xhrData.send();
        }
    });

     //=================================== GRÁFICO FILTRADO ===================================
    // Obtén el botón de filtrar
    var filtrarBtn = document.getElementById('filtrar-btn');
    var selectPeriodoFinal = document.getElementById('select-periodo-final');

    // Agrega un listener al evento 'click' del botón filtrar
    filtrarBtn.addEventListener('click', function () {
        // Obtén el valor seleccionado del periodo final
        var selectedPeriodo = selectPeriodoFinal.value;

        // Verifica si se seleccionó una opción válida
        if (selectedPeriodo !== '') {
            // Obtén el valor seleccionado de la incubadora
            var selectedIncubadora = selectIncubadora.value;

            // Realiza una nueva consulta en la base de datos para obtener los datos filtrados
            var xhrData = new XMLHttpRequest();
            xhrData.open('GET', 'get_data_consumo-energeticoXPeriodo.php?incubadora=' + selectedIncubadora + '&periodo=' + selectedPeriodo, true);
            xhrData.onreadystatechange = function () {
                if (xhrData.readyState === 4 && xhrData.status === 200) {
                    // Actualiza los datos del gráfico con los datos filtrados obtenidos de la respuesta
                    var responseData = JSON.parse(xhrData.responseText);
                    labels = responseData.labels;
                    data = responseData.data;
                    labeledData = labels.map(function (label) {
                        return "Periodo " + label;
                    });
                    myChart.data.labels = labeledData;
                    myChart.data.datasets[0].data = data;
                    myChart.update();
                }
            };
            xhrData.send();
        }
    });
    //======================================================================
</script>

<?php
    include 'footer.php';
?>







