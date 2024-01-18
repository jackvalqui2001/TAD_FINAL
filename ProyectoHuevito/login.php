<?php
session_start();
include("conexion.php");

if (isset($_POST['usuario']) && isset($_POST['password'])) {
    function validar($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $usuario = validar($_POST['usuario']);
    $password = validar($_POST['password']);
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        if ($row['nombre_usuario'] == 'admin' && $row['password'] === 'admin') {
            $_SESSION['usuario'] = $row['nombre_usuario'];
            $_SESSION['password'] = $row['password'];
            header("location:usuariosAPI.php");
        } else {
            $_SESSION['usuario'] = $row['nombre_usuario'];
            $_SESSION['password'] = $row['password'];
            header("location:index.php");
            exit();
        }
    } else {
        echo "<script>alert('Usuario o contraseña incorrecta');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IncuSmart</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>
    <nav id="barra" class="navbar navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">INCUSMART</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            </div>
        </div>
    </nav>

    <div class="container login">
        <div class="row justify-content-center">
            <div class="col-6 col-sm-4">
                <h1 class="titulo">LOGIN</h1>
                <div class="text-center my-4">
                    <img src="img/pollito.png" class="logo" alt="Logo de IncuSmart">
                </div>
                <form action="login.php" method="post">
                    <input type="text" class="form-control my-3" id="usuario" name="usuario" placeholder="Usuario" required>
                    <input type="password" class="form-control  my-3" id="password" name="password" placeholder="Contraseña" required>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" value="" id="checkRecordarDatos">
                        <label class="form-check-label" for="checkRecordarDatos">
                            Recordar datos
                        </label>
                    </div>
                    <div class="text-center my-3">
                        <button type="submit" class="btn btn-primary mb-2 mx-1" id="ingresar">Ingresar</button>
                        <a href="registro.php">
                            <button type="button" class="btn btn-primary mb-2 mx-1">Registrarse</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    include 'footer.php';
?>