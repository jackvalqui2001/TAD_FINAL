<?php
    include 'header_pre.php';

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://localhost/ProyectoTDS/api/usuarios/create.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
    ));

    $err = curl_error($curl);
    curl_close($curl);

    if ($_POST) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $nombre_usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $tipo_uso = $_POST['tipo_uso'];
        $correo = $_POST['correo'];
    }

?>

    <div class="container">
        <div class="row justify-content-center">
            <h1 id="txtFormulario" class="titulo">Formulario de Registro</h1>
            <div class="text-center my-4">
                <img src="img/pollito.png" class="logo" alt="Logo de IncuSmart">
            </div>
            <form action="registro.php" id="formRegistro" method="post">
                <div class="panel-form">
                    <div class="row">
                        <div class="col-6" id="grupo_nombre">
                            <b><label for="Nombre" class="form-label">Nombre</label></b>
                            <div class="form-grupo-input">
                                <input type="text" class="form-input mb-3" name="nombre" id="nombre" placeholder="Nombre" required>
                                <i class="form-estado bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-6" id="grupo_apellido">
                            <b><label for="Apellido" class="form-label">Apellido</label></b>
                            <div class="form-grupo-input">
                                <input type="text" class="form-input mb-3" name="apellido" id="apellido" placeholder="Apellido" required>
                                <i class="form-estado bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6" id="grupo_usuario">
                            <b><label for="Usuario" class="form-label">Usuario</label></b>
                            <div class="form-grupo-input">
                                <input type="text" class="form-input mb-3" name="usuario" id="usuario" placeholder="Usuario" required>
                                <i class="form-estado bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-6" id="grupo_password">        
                            <b><label for="Contraseña" class="form-label">Contraseña</label></b>
                            <div class="form-grupo-input">
                                <input type="password" class="form-input mb-3" name="password" id="password" placeholder="Contraseña" required>
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
                                    <option value="Granja">Granja</option>
                                    <option value="Avícola">Avícola</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6" id="grupo_correo">
                            <b><label for="Correo" class="form-label">Correo Electrónico</label></b>
                            <div class="form-grupo-input">
                                <input type="email" class="form-input mb-3" name="correo" id="correo" placeholder="Correo" required>
                                <i class="form-estado bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center my-3 registro">
                    <button id="btn-registrar" type="submit" class="btn btn-primary"> <b>Crear</b></button>
                    <button type="button btn-volver" class="btn btn-primary" onclick="window.location.href='usuarioCreate.php'"><b>Volver</b></button>
                </div>
            </form>
            <div class="text-center my-3">
            <?php
            if (isset($_SESSION['registro_exitoso']) && $_SESSION['registro_exitoso']) {
                echo '<div id="popup" class="popup">';
                echo '<p class="texto_pop">¡Registro Exitoso!</p>';
                echo '<a href="registroAdmin.php">';
                echo '<button class="btn btn-dark" type="submit"> <b>Iniciar Sesión </b></button>';
                echo '</a>';
                echo '</div>';

                unset($_SESSION['registro_exitoso']);
                echo '<script>window.onload = function() { document.getElementById("btn-registrar").disabled = true; }</script>';
                echo '<script>window.onload = function() { document.getElementById("btn-volver").disabled = true; }</script>';
            } elseif (isset($_SESSION['registro_existente']) && $_SESSION['registro_existente']) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'El nombre de usuario ya está en uso. Por favor, elige otro nombre de usuario.';
                echo '</div>';                
                unset($_SESSION['registro_existente']);
            }
            ?>
            </div>
        </div>
    </div>
    <!-- Javascript -->
    <script src="script.js"></script>
<?php include 'footer.php';?>