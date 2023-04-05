<?php

class DatabaseConn
{
    protected $conn;


    function __construct()
    {
        $servername = "localhost";
        $username = "forti";
        $password = "forti";
        $dbname = "forti";

        // Create connection
        $this->conn = new mysqli($servername, $username, $password, $dbname);
    }

    public function getConn(){
        return $this->conn;
    }

    function comprobarLogin($username, $password)
    {

        try {
            $sql = "SELECT * FROM Usuario u WHERE u.username='$username' AND u.password='$password'";

            $result = $this->conn->query($sql);
        } catch (Exception $e) {
            echo $e;
        }
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }
    function comprobarRegistro($datos)
    {

        $email = $datos["email"];
        $username = $datos["username"];
        $password = $datos["password"];
        $nombre = $datos["nombre"];

        $problemaMail = false;
        $problemaUser = false;


        try {
            $sql = "SELECT * FROM Usuario WHERE username='$username'";
            $result = $this->conn->query($sql);
        } catch (Exception $e) {
            echo $e;
        }
        if ($result->num_rows > 0) {
            $problemaUser = true;
        }

        try {
            $sql = "SELECT * FROM Usuario WHERE email='$email'";
            $result = $this->conn->query($sql);
        } catch (Exception $e) {
            echo $e;
        }
        if ($result->num_rows > 0) {
            $problemaMail = true;
        }

        if ($problemaMail) {
            return 'mail';
        }
        if ($problemaUser) {
            return 'user';
        }

        $id = $this->asignarId("Usuario");

        $sql = "INSERT INTO Usuario VALUES($id, '$nombre', '$username', '$password', '$email')";


        if ($this->conn->query($sql) === TRUE) {

        } else {
            return false;
        }

        return true;
    }

    function subirPost($id, $usuario, $texto, $imagen, $fecha){
        if($imagen == null){
            $sql = "INSERT INTO Posts VALUES($id, $usuario, '$texto', null, '$fecha')";
            $this->conn->query($sql);
            return;
        }
        $sql = "INSERT INTO Posts VALUES($id, $usuario, '$texto', '$imagen', '$fecha')";
        $this->conn->query($sql);
    }

    function asignarId($tabla)
    {
        $existeID = false;
        do {
            $existeID = false;
            $id = random_int(0, 2147483646);
            try {
                $sql = "SELECT * FROM $tabla WHERE id=" . $id;
                $result = $this->conn->query($sql);
            } catch (Exception $e) {
                echo $e;
            }
            if ($result->num_rows > 0) {
                $existeID = true;
            }
        } while ($existeID);

        return $id;
    }

    function obtenerAmigos($userid)
    {
        $listaAmigos = array();
        try {
            $sql = "SELECT usuario1 FROM Amigos WHERE usuario2=$userid";
            $result = $this->conn->query($sql);
        } catch (Exception $e) {
            echo $e;
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaAmigos[] = $row["usuario1"];
            }
        }

        try {
            $sql = "SELECT usuario2 FROM Amigos WHERE usuario1=$userid";
            $result = $this->conn->query($sql);
        } catch (Exception $e) {
            echo $e;
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaAmigos[] = $row["usuario2"];
            }
        }
        return $listaAmigos;
    }

    function getNombre($userid)
    {
        $nombre = "";
        try {
            $sql = "SELECT * FROM Usuario WHERE id=" . $userid;
            $result = $this->conn->query($sql);
        } catch (Exception $e) {
            echo $e;
        }
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $nombre = $row["nombre"];
            }
        }
        return $nombre;
    }


    function postsPropios($userid)
    {
        $nombre = $this->getNombre($userid);
        $sql = "SELECT * FROM Posts WHERE usuario=$userid ORDER BY fecha DESC";
        $result = $this->conn->query($sql);
        $posts = array();
        try {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $posts[] = $row["id"];
                }
            }
        } catch (Exception $e) {
            echo $e;
        }
        return $posts;
    }

    function getPost($id)
    {
        $sql = "SELECT * FROM Posts WHERE id=" . $id;
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
        return null;
    }
    function postsDeAmigos(array $amigos)
    {
        $sql = "SELECT * FROM Posts WHERE";
        $posts = array();
        if(count($amigos) > 0){
            for($i = 0; $i < count($amigos); $i++){
                if($i != 0){
                    $sql .= " OR";
                }
                $sql .= " usuario=" . $amigos[$i];
            }
            $sql .= " ORDER BY fecha DESC";

            $result = $this->conn->query($sql);
            try {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $posts[] = $row["id"];
                    }
                }
            } catch (Exception $e) {
                echo $e;
            }
            return $posts;
        }
        else{
            return null;
        }

    }



}