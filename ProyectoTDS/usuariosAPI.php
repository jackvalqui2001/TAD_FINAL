<?php
    include 'header_pre.php';
    
    //READ
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://localhost/ProyectoTDS/api/usuarios/read.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $response = json_decode($response, true);
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventana Emergente</title>
    <style>
        /* Estilos para la ventana emergente */
        #myModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        #modalContent {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
        }

        #closeBtn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="usuarioCreate.php" class="btn btn-success">Crear +</a>
        </div>
        <h1>Usuarios</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Tipo de Usuario</th>
                    <th>Correo</th>
                    <th>  </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Mostrar los datos de los usuarios en la tabla
                    foreach ($response as $user) {
                        echo "<tr>";
                        echo "<td>" . $user ['idUsuario']. "</td>";
                        echo "<td><a href='usuarioUpdate.php?id=" . $user['idUsuario'] . "' style='color: red; text-decoration: underline;'>" . $user['nombreUsuario'] . "</a></td>";
                        echo "<td>" . $user ['nombre'] . "</td>";
                        echo "<td>" . $user ['apellido'] . "</td>";
                        echo "<td>" . $user ['tipo_uso'] . "</td>";
                        echo "<td>" . $user['correo'] . "</td>";
                        echo "<td><a href='#' onclick='openDeleteConfirmation(" . $user['idUsuario'] . ")' style='color: red; text-decoration: underline;'>Borrar</a></td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>


        <!-- Contenido de la ventana emergente -->
    <div id="myModal">
        <div id="modalContent">
            <span id="closeBtn" onclick="closeModal()">&times;</span>
            <h2>Eliminando</h2>
        </div>
    </div>

    <script>

        // Función para abrir la ventana emergente al hacer clic en el enlace de borrar
        function openDeleteConfirmation(deleteId) {
            
            /*------------------------COMUNICACIÓN ENTRE PHP Y JAVASCRIPT-------------------------*/
            // Construye la URL con el parámetro delete_id
            var confirmationUrl = 'confirmacionDelete.php?delete_id=' + deleteId;
            // Datos que deseas enviar al servidor
            var datos = "Hola, mundo!";

            // Crear una instancia de XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configurar la solicitud
            xhr.open("POST", confirmationUrl, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Definir la función de retorno de llamada (callback) para manejar la respuesta del servidor
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // La respuesta del servidor está en xhr.responseText
                    console.log("Respuesta del servidor:", xhr.responseText);
                }
            };

            // Enviar los datos al servidor
            xhr.send("datos=" + encodeURIComponent(datos));
            /*--------------------------------------------------------------------------------------*/

            //Ver la ventana emergente
            document.getElementById('myModal').style.display = 'block';
        }


        // Función para cerrar la ventana emergente
        function closeModal() {
            document.getElementById('myModal').style.display = 'block';
            location.reload();
        }

        // Cerrar la ventana emergente si se hace clic fuera de ella
        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = 'block';
            }
            location.reload();
        }
    </script>

</body>
</html>

<?php
    include 'footer.php';
?>