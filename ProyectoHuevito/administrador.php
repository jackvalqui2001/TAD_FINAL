<?php
    include 'header_pre.php';
    include("conexion.php");

    // Verificar si se ha enviado una solicitud para borrar un usuario
    if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $query = "DELETE FROM usuarios WHERE id_usuario = $delete_id";
        $result = mysqli_query($conn, $query);
        if($result) {
            echo "Usuario eliminado correctamente.";
        } else {
            echo "Error al eliminar el usuario.";
        }
    }

    $query = "SELECT * FROM usuarios WHERE nombre_usuario != 'admin'";
    $result = mysqli_query($conn, $query);
?>

<div class="container">
    <div class="d-flex justify-content-end mb-3">
        <a href="administradorRegistro.php" class="btn btn-success">Crear +</a>
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Mostrar los datos de los usuarios en la tabla
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_usuario'] . "</td>";
                    echo "<td><a href='administradorUpdate.php?id=" . $row['id_usuario'] . "' style='color: red; text-decoration: underline;'>" . $row['nombre_usuario'] . "</a></td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['apellido'] . "</td>";
                    echo "<td>" . $row['tipo_uso'] . "</td>";
                    echo "<td>" . $row['correo'] . "</td>";
                    echo "<td><a href='administrador.php?delete_id=" . $row['id_usuario'] . "' style='color: red; text-decoration: underline;'>Borrar</a></td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<?php
    mysqli_close($conn);
    include 'footer.php';
?>