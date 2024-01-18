<?php
include 'header.php';

if (isset($_POST['btn-registrar-inicio'])) {
    $num_huevos = $_POST['num-huevos'];
    $partes = explode('/', $_POST['fecha-inicio']);
    $fecha_inicio = $partes[2].'-'.$partes[0].'-'.$partes[1];
    $id_eclosion =  $_SESSION['periodo-inicio'];
    $id_incubadora = $_SESSION['id-incubadora'];
    $sqlinsert = "INSERT INTO eclosion(id_eclosion, id_incubadora, huevos, fechaHoraInicio) VALUES ('$id_eclosion', '$id_incubadora', '$num_huevos', '$fecha_inicio')";
    if ($conn->query($sqlinsert) === TRUE) {
        echo "Inserción exitosa en la tabla";
    } else {
        echo "Error: " . $sqlinsert . "<br>" . $conn->error;
    }
}

if (isset($_POST['btn-registrar-fin'])) {
    $huevos_eclosionados = $_POST['huevos-eclosionados'];
    $partes = explode('/', $_POST['fecha-fin']);
    $fecha_fin = $partes[2].'-'.$partes[0].'-'.$partes[1];
    $id_eclosion =  $_SESSION['periodo-fin'];
    $id_incubadora = $_SESSION['id-incubadora'];

    $sql = "SELECT huevos, fechaHoraInicio FROM eclosion";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $num_huevos = $row["huevos"];
            $fecha_inicio = $row["fechaHoraInicio"];
        }
    }

    $fin = new DateTime($fecha_fin);
    $inicio = new DateTime($fecha_inicio);

    $diferencia = $fin->diff($inicio);

    $num_dias = $diferencia->days;

    $porcentajeEclosion = round(($huevos_eclosionados / $num_huevos) * 100);

    //18.23
    $consumoXhora = 0.0503; // kWh
    $tarifaXhora = 0.63; // S/0.36
    $costoEnergetico = $consumoXhora * $tarifaXhora * 24 * $num_dias;
    $Beneficio = $huevos_eclosionados * 8 - $costoEnergetico;

    $sqlupdate = "UPDATE eclosion SET eclosiones = '$huevos_eclosionados', porcentajeEclosion = '$porcentajeEclosion', fechaHoraFinal = '$fecha_fin', Beneficio = '$Beneficio', costoEnergetico = '$costoEnergetico', dias = '$num_dias' WHERE id_eclosion = '$id_eclosion' AND id_incubadora = '$id_incubadora'";
    if ($conn->query($sqlupdate) === TRUE) {
        echo "Modificación exitosa en la tabla";
    } else {
        echo "Error: " . $sqlupdate . "<br>" . $conn->error;
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="row">
                <div class="col-6 text-end">
                    <label class="me-3">Seleccionar Incubadora</label>
                </div>
                <div class="col-6">
                    <form action="registroperiodo.php" method="post">
                        <select class="form-select" id="select-incubadora" name="select-incubadora" aria-label="Default select example">
                            <option selected>Seleccionar</option>
                            <?php
                            // Conexión a la base de datos
                            // $conn = mysqli_connect('localhost', 'root', '', 'proyectoiot');
                            // Verificar la conexión
                            // if (!$conn) {
                            //     die('Error de conexión: ' . mysqli_connect_error());
                            // }
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
                            // mysqli_close($conn);
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-enviar-periodo my-3" id="btn-enviar-periodo" name="btn-enviar-periodo" disabled>Enviar</button>
                    </form>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-primary" id="btn-iniciar-periodo" disabled>Iniciar periodo</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-primary" id="btn-finalizar-periodo" disabled>Finalizar periodo</button>
                </div>
            </div>
            <form class="panel_registro_periodo" id="panel-inicio" action="" method="post">
                <?php
                if (isset($_POST['btn-enviar-periodo'])) {
                    $id_incubadora = $_POST['select-incubadora'];
                    $_SESSION['periodo_enviado'] = true;
                    $_SESSION['id-incubadora'] = $id_incubadora;
                    $query = "SELECT COUNT(*) as count FROM eclosion WHERE id_incubadora = '$id_incubadora'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    $periodo_inicio = $row['count'] + 1;
                    $_SESSION['periodo-inicio'] = $periodo_inicio;
                    echo "<p>Periodo a iniciar: " . $periodo_inicio . "</p>";
                }
                ?>
                <div class="row form-group my-1">
                    <!-- <div class="col-6"> -->
                        <label for="date" class="col-6 col-form-label">Fecha de inicio:</label><!-- col-sm-1 -->
                    <!-- </div> -->
                    <div class="col-6"> <!-- col-sm-4 -->
                        <div class="input-group date" id="datepickerinicio">
                            <input type="text" id="fecha-inicio" name="fecha-inicio" class="form-control">
                            <span class="input-group-append">
                                <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-outline">
                    <label class="form col-6" for="typeNumber">Cantidad de huevos:</label>
                    <div class="col-6">
                        <input type="number" id="num-huevos" name="num-huevos" min="0" class="form-control" />
                    </div>
                </div>
                <div class="text-center my-3">
                    <button type="submit" class="btn btn-primary mb-2" id="btn-registrar-inicio" name="btn-registrar-inicio" disabled>Registrar</button>
                </div>
            </form>
            <form class="panel_registro_periodo" id="panel-fin" action="" method="post">
            <?php
                if (isset($_POST['btn-enviar-periodo'])) {
                    $id_incubadora = $_POST['select-incubadora'];
                    $_SESSION['periodo_enviado'] = true;
                    $_SESSION['id-incubadora'] = $id_incubadora;
                    $query = "SELECT COUNT(*) as count FROM eclosion WHERE id_incubadora = '$id_incubadora' AND fechaHoraFinal IS NOT NULL";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    $periodo_fin = $row['count'] + 1;
                    $_SESSION['periodo-fin'] = $periodo_fin;
                    echo "<p>Periodo a finalizar: " . $periodo_fin . "</p>";
                }
                ?>
                <div class="row form-group my-1">
                    <label for="date" class="col-6 col-form-label">Fecha de fin:</label>
                    <div class="col-6">
                        <div class="input-group date" id="datepickerfin">
                            <input type="text" id="fecha-fin" name="fecha-fin" class="form-control">
                            <span class="input-group-append">
                                <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-outline">
                    <label class="form col-6" for="typeNumber2">Cantidad de huevos eclosionados:</label>
                    <div class="col-6">
                        <input type="number" id="huevos-eclosionados" name="huevos-eclosionados" min="0" class="form-control" />
                    </div>
                </div>
                <div class="text-center my-3">
                    <button type="submit" class="btn btn-primary mb-2" id="btn-registrar-fin" name="btn-registrar-fin" disabled>Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#datepickerinicio').datepicker();
        $('#datepickerfin').datepicker();

        // Obtén las referencias a los elementos del formulario
        var fechaInicioInput = $('#fecha-inicio');
        var numHuevosInput = $('#num-huevos');
        var btnRegistrarInicio = $('#btn-registrar-inicio');

        var fechaFinInput = $('#fecha-fin');
        var numHuevosEclosionadosInput = $('#huevos-eclosionados');
        var btnRegistrarFin = $('#btn-registrar-fin');
        
        // Agrega un evento 'input' a los campos del formulario
        fechaInicioInput.on('input', validarCampos);
        numHuevosInput.on('input', validarCampos);

        fechaFinInput.on('input', validarCampos);
        numHuevosEclosionadosInput.on('input', validarCampos);
        
        // Función para validar los campos y habilitar/deshabilitar el botón "Registrar"
        function validarCampos() {
            var fechaInicio = fechaInicioInput.val();
            var numHuevos = numHuevosInput.val();
            
            var fechaFin = fechaFinInput.val();
            var numHuevosEclosionados = numHuevosEclosionadosInput.val();

            // Verifica si los campos están completos
            if (fechaInicio && numHuevos) {
                btnRegistrarInicio.prop('disabled', false); // Habilita el botón "Registrar"
            } else {
                btnRegistrarInicio.prop('disabled', true); // Deshabilita el botón "Registrar"
            }

            if (fechaFin && numHuevosEclosionados) {
                btnRegistrarFin.prop('disabled', false); // Habilita el botón "Registrar"
            } else {
                btnRegistrarFin.prop('disabled', true); // Deshabilita el botón "Registrar"
            }
        }

        $('#select-incubadora').change(function() {
            var selectedOption = $(this).val();
            if (selectedOption !== "Seleccionar") {
                $('#btn-enviar-periodo').prop('disabled', false);
                // $('#btn-iniciar-periodo').prop('disabled', false);
                // $('#btn-finalizar-periodo').prop('disabled', false);
                console.log(selectedOption);
            } else {
                $('#btn-enviar-periodo').prop('disabled', true);
                // $('#btn-iniciar-periodo').prop('disabled', true);
                // $('#btn-finalizar-periodo').prop('disabled', true);
            }
        });
        <?php if (isset($_SESSION['periodo_enviado']) && $_SESSION['periodo_enviado']) { ?>
            $('#btn-iniciar-periodo').prop('disabled', false);
            $('#btn-finalizar-periodo').prop('disabled', false);
        <?php } ?>
        
        // $('#btn-enviar-periodo').click(function() {
        //     $('#btn-iniciar-periodo').prop('disabled', false);
        //     $('#btn-finalizar-periodo').prop('disabled', false);
        // });
        
        $('#btn-iniciar-periodo').click(function() {
            $('#panel-inicio').show();
            $('#panel-fin').hide();
        });
        
        $('#btn-finalizar-periodo').click(function() {
            $('#panel-inicio').hide();
            $('#panel-fin').show();
        });
    });
</script>
<?php

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
//     // Obtener el valor enviado por la petición AJAX
//     $valorSeleccionado = $_POST['valorSeleccionado'];

//     // Hacer algo con el valor recibido
//     // Por ejemplo, puedes guardarlo en una variable de PHP
//     $valorPHP = $valorSeleccionado;
//     echo $valorPHP;

//     // Puedes realizar cualquier otra acción con el valor recibido

//     // Retornar una respuesta en formato JSON
//     $response = array('success' => true, 'message' => 'Valor recibido correctamente');
//     echo json_encode($response);
//     exit;
// }
include 'footer.php';
?>