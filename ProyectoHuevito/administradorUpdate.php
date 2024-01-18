<?php
    include 'header.php';
    include("conexion.php");

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

    if ($_POST) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $tipo_uso = $_POST['tipo_uso'];
        $correo = $_POST['correo'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $password = $_POST['password'];

        $query = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', tipo_uso = '$tipo_uso', correo = '$correo', nombre_usuario = '$nombre_usuario', password = '$password' WHERE id_usuario = $id_usuario";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Usuario actualizado correctamente.";
            header("Location: administrador.php"); 
            exit();
        } else {
            echo "Error al actualizar el usuario: " . mysqli_error($conn);
        }
    }
?>

<div class="container">
    <h1>Actualizar</h1>
    <form action="usuariosUpdate.php?id=<?php echo $id_usuario; ?>" method="post">
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
            <a href="administrador.php" class="btn btn-primary custom-btn" style="background-color: red; color: white;">Volver</a>
    </form>
</div>

<?php
    mysqli_close($conn);
    include 'footer.php';
?>