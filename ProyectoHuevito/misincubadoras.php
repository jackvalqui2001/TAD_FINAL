<?php
    include 'header.php';
    echo $_SESSION['usuario'];
?>
    <div class="container text-center">
        <h1 class="titulo">Mis incubadoras</h1>
        <ul class="incubadoras">
            <li class="incubadora">
                <a href="index.php" class="incubadora-link">
                    <img class="incubadora-img" src="img/incubadora.png" alt="Imagen de la incubadora 1">
                    <span class="incubadora-name">Incubadora 1000</span>
                </a>
            </li>
            <li class="incubadora">
                <a href="index.php" class="incubadora-link">
                    <img class="incubadora-img" src="img/incubadora.png" alt="Imagen de la incubadora 2">
                    <span class="incubadora-name">Incubadora 2</span>
                </a>
            </li>
            <li class="incubadora">
                <a href="index.php" class="incubadora-link">
                    <img class="incubadora-img" src="img/incubadora.png" alt="Imagen de la incubadora 3">
                    <span class="incubadora-name">Incubadora 3</span>
                </a>
            </li>
        </ul>
    </div>

<?php
    include 'footer.php';
?>