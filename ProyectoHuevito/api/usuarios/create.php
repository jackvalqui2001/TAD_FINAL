<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object
include_once '../objects/usuarios.php';
  
$database = new Database();
$db = $database->getConnection();
  
$usuario = new Usuario($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->nombre) &&
    !empty($data->apellido) &&
    !empty($data->nombreUsuario) &&
    !empty($data->password) &&
    !empty($data->tipo_uso) &&
    !empty($data->correo)
){
  
    // set product property values
    $usuario->nombre = $data->nombre;
    $usuario->apellido = $data->apellido;
    $usuario->nombre_usuario = $data->nombreUsuario;
    $usuario->password = $data->password;
    $usuario->tipo_uso = $data->tipo_uso;
    $usuario->correo = $data->correo;
  
    // create the product
    if($usuario->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Usuario ha sido creado."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "No se puede crear el producto."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "No se puede crear el producto. Datos incompletos."));
}
?>