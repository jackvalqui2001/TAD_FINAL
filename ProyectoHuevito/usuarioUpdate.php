<?php

include 'header.php';
include 'conexion.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    $query = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
    } else {
        echo "Usuario no encontrado.";
        exit();
    }
} else {
    echo "ID de usuario inválido.";
    exit();
}

//API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $curl_handle = curl_init();

    $postData = array(
        'id' => $_POST['id'],
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'nombre_usuario' => $_POST['nombre_usuario'],
        'correo' => $_POST['correo'],
        'tipo_uso' => $_POST['tipo_uso'],
        'password' => $_POST['password']
    );

    curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curl_handle, CURLOPT_URL, 'http://localhost/ProyectoHuevito/api/usuarios/update.php');
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer your_access_token'
    ]);

    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($curl_handle);

    if ($response === false) {
        echo 'Error: ' . curl_error($curl_handle);
    } else {
        $decoded_response = json_decode($response, true);
        echo 'Response: ' . print_r($decoded_response, true);       
    }

    curl_close($curl_handle);
    header("Location: usuariosAPI.php");
    exit;
}
//

?>

<div class="container">
    <h1>Actualizar</h1><form action="usuarioUpdate.php?id=<?php echo $id_usuario; ?>" method="post">
        <div class="mb-3">
            <label for="id" class="form-label">id</label>
            <input type="text" class="form-control" id="id" name="id" value="<?php echo $usuario['id_usuario']; ?>" required readonly>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tipo_uso" class="form-label">Tipo de Usuario</label>
            <select class="form-control" id="tipo_uso" name="tipo_uso" required>
                <option value="Granja" <?php if ($usuario['tipo_uso'] === 'Granja') echo 'selected'; ?>>Granja</option>
                <option value="Avícola" <?php if ($usuario['tipo_uso'] === 'Avícola') echo 'selected'; ?>>Avícola</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo $usuario['nombre_usuario']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo $usuario['password']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success custom-btn" style="background-color: green; color: white;">Actualizar</button>
            <a href="usuariosAPI.php" class="btn btn-primary custom-btn" style="background-color: red; color: white;">Volver</a>
    </form>
</div>

<?php
    include 'footer.php';
?>