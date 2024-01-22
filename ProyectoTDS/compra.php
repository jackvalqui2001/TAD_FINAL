<?php
include("header_pre.php");
include("conexion.php");
session_start();

if ($_POST) {
  $nombre = $_POST['nombreUsuario'];
  $tipo_uso = $_POST['tipo_uso'];
  $cantidad = $_POST['cantidad'];

  $conn->begin_transaction();

  try {
    // Obtener el id_usuario desde la base de datos
    $queryObtenerId = "SELECT id_usuario FROM usuarios WHERE nombre_usuario = ?";
    $stmtObtenerId = $conn->prepare($queryObtenerId);
    $stmtObtenerId->bind_param('s', $nombre);
    $stmtObtenerId->execute();
    $result = $stmtObtenerId->get_result();
    $row = $result->fetch_assoc();
    $id_usuario = $row['id_usuario'];
    
    // Obtener el stock disponible de la base de datos para el tipo de uso seleccionado
    $queryStockDisponible = "SELECT StockDisponible FROM incubadora WHERE id_incubadora = ?";
    $stmtStockDisponible = $conn->prepare($queryStockDisponible);
    $stmtStockDisponible->bind_param('i', $tipo_uso);
    $stmtStockDisponible->execute();
    $resultStock = $stmtStockDisponible->get_result();
    $rowStock = $resultStock->fetch_assoc();
    $stockDisponible = $rowStock['StockDisponible'];

    // Verificar si hay suficiente stock disponible para la compra
    if ($cantidad <= $stockDisponible) {
      // Insertar en la tabla incubadoraporusuario usando el id_usuario obtenido
      $query1 = "INSERT INTO incubadoraporusuario (id_usuario, nombre_usuario, id_incubadora, fechaHora ,StockUtilizado) VALUES (?, ?, ?, NOW(), ?)";
      $stmt1 = $conn->prepare($query1);
      $stmt1->bind_param('isii', $id_usuario, $nombre, $tipo_uso, $cantidad);

      $query2 = "UPDATE incubadora SET StockDisponible = StockDisponible - ? WHERE id_incubadora = ?";
      $stmt2 = $conn->prepare($query2);
      $stmt2->bind_param('ii', $cantidad, $tipo_uso);

      $stmt1->execute();
      $stmt2->execute();

      $conn->commit();
      echo "Transacción exitosa.";
    } else {
      // Mostrar mensaje de error en el HTML
      echo "<script>document.getElementById('mensajeError').innerHTML = 'No hay suficiente stock disponible para realizar la compra.';</script>";
    }
  } catch (Exception $e) {
    $conn->rollback();
    echo "Fallo: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Registro</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <h1 id="txtFormulario" class="titulo">Formulario de Registro</h1>
      <div class="text-center my-4">
        <img src="img/pollito.png" class="logo" alt="Logo de IncuSmart">
      </div>
      <form action="compra.php" id="formRegistro" method="post">
        <div class="panel-form">
          <div class="row">
            <div class="col-6" id="nombreUsuario">
              <b><label for="nombreUsuario" class="form-label">Nombre Usuario</label></b>
              <div class="form-grupo-input">
                <input type="text" class="form-input mb-3" name="nombreUsuario" id="nombreUsuario" placeholder="nombreUsuario" required>
                <i class="form-estado bi bi-x-circle-fill"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6" id="grupo_tipo_uso">
              <b><label for="Tipo_Uso" class="form-label">Tipo de Uso</label></b>
              <div class="form-grupo-input">
                <select class="form-input mb-3" name="tipo_uso" id="tipo_uso" aria-label="Default select example" required>
                  <option value="" selected>Seleccione una opcion</option>
                  <option value="1000">Pequeña</option>
                  <option value="2000">Mediana</option>
                </select>
              </div>
            </div>
            <div class="col-6" id="cantidad">
              <b><label for="cantidad" class="form-label">Cantidad</label></b>
              <div class="form-grupo-input">
                <input type="text" class="form-input mb-3" name="cantidad" id="cantidad" placeholder="cantidad" required>
                <i class="form-estado bi bi-x-circle-fill"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center my-3 registro">
          <button id="btn-registrar" type="submit" class="btn btn-primary"> <b>Comprar</b></button>
          <button type="button btn-volver" class="btn btn-primary" onclick="window.location.href='login.php'"><b>Volver</b></button>
        </div>
      </form>
      <div class="text-center my-3">
      </div>
    </div>
  </div>

  <script>
    document.getElementById('formRegistro').addEventListener('submit', function(event) {
      event.preventDefault();

      var stockDisponible = <?php echo $stockDisponible; ?>;
      var cantidad = document.getElementById('cantidad').value;
      if (cantidad > stockDisponible) {
        document.getElementById('mensajeError').style.display = 'block';
        document.getElementById('mensajeError').innerHTML = 'No hay suficiente stock disponible para realizar la compra.';
      } else {
        var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        alertHtml += 'Transacción exitosa.';
        alertHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        alertHtml += '<span aria-hidden="true">&times;</span>';
        alertHtml += '</button>';
        alertHtml += '</div>';
        document.getElementById('formRegistro').insertAdjacentHTML('beforebegin', alertHtml);
      }
    });
  </script>

  <?php include 'footer.php';?>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>