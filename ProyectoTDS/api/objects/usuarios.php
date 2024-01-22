<?php
class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    //object
    public $id;
    public $nombre_usuario;
    public $nombre;
    public $apellido;
    public $password;
    public $tipo_uso;
    public $correo;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read products
    function read(){
    
        // select all query
        $query = "SELECT * FROM usuarios WHERE nombre_usuario != 'grupo4'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create product
    function create(){
        
        // query to insert record
        $query = "INSERT INTO
                    usuarios
                SET
                    nombre_usuario=:nombre_usuario, nombre=:nombre, apellido=:apellido, password=:password, tipo_uso=:tipo_uso, correo=:correo";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->nombre_usuario=htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->apellido=htmlspecialchars(strip_tags($this->apellido));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->tipo_uso=htmlspecialchars(strip_tags($this->tipo_uso));
        $this->correo=htmlspecialchars(strip_tags($this->correo));
    
        // bind values
        $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":tipo_uso", $this->tipo_uso);
        $stmt->bindParam(":correo", $this->correo);

        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // delete the usuarios
    function delete(){
    
        // delete query
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }

    // update the product
    function update(){    
        // update query
        $query = "UPDATE
                    usuarios
                SET
                    nombre=:nombre,
                    apellido=:apellido,
                    tipo_uso=:tipo_uso,
                    correo=:correo,
                    nombre_usuario=:nombre_usuario,
                    password=:password
                WHERE
                    id_usuario=:id_usuario";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->apellido=htmlspecialchars(strip_tags($this->apellido));
        $this->tipo_uso=htmlspecialchars(strip_tags($this->tipo_uso));
        $this->correo=htmlspecialchars(strip_tags($this->correo));
        $this->nombre_usuario=htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->password=htmlspecialchars(strip_tags($this->password));
    
        // bind new values
        $stmt->bindParam(':id_usuario', $this->id);
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':tipo_uso', $this->tipo_uso);
        $stmt->bindParam(':correo', $this->correo);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}

?>