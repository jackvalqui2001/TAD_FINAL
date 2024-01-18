<?php
session_start();
include("conexion.php");
// if(isset($_SESSION['usuario']) && isset($_SESSION['password'])){
//     $usuario = $_SESSION['usuario'];
//     $password = $_SESSION['password'];
    
//     $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario' AND password = '$password'";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
        
//     if ($stmt->rowCount() != 1) {
//         header("location:login.php");
//         exit();
//     }
// } else {
//     header("location:login.php");
//     exit();
// }
if (isset($_SESSION['usuario']) && isset($_SESSION['password'])) {
    $usuario = $_SESSION['usuario'];
    $password = $_SESSION['password'];
    
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario' AND password = '$password'";
    $result = $conn->query($sql);
        
    if ($result->num_rows != 1) {
        header("location:login.php");
        exit();
    }
} else {
    header("location:login.php");
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</head>
<body>
    <nav id="barra" class="navbar navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">INCUSMART</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="registroperiodo.php">Registro Periodo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cerrar.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>