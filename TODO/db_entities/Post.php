<?php

require_once("Amigos.php");
try {
    require_once("./DatabaseConn.php");
} catch (Exception $e) {
    echo $e;
}
class Post
{
    private int $id;
    private int $usuario;
    private string $texto;
    private ?string $imagen;
    private string $fecha;



    function __construct($id, $usuario, $texto, $imagen, $fecha)
    {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->texto = $texto;
        $this->imagen = $imagen;
        $this->fecha = $fecha;
    }

    static function getPost($id)
    {
        $conn = new DatabaseConn();
        $row = $conn->getPost($id);
        $usuario = $row["usuario"];
        $texto = $row["texto"];
        $imagen = $row["imagen"];
        $fecha = $row["fecha"];
        $post = new self($id, $usuario, $texto, $imagen, $fecha);
        return $post;
    }
    static function nuevoPost($usuario, $texto, $imagen)
    {
        $conn = new DatabaseConn();
        $id = $conn->asignarId("Posts");
        $date = new DateTime();
        $fecha = $date->format("Y-m-d H:i:s");
        $post = new self($id, $usuario, $texto, $imagen, $fecha);
        $post->uploadPost();
        return $post;
    }

    static function getPostsByUser($userid)
    {
        $conn = new DatabaseConn();
        $postsID = $conn->postsPropios($userid);
        $posts = array();
        for($i = 0; $i < count($postsID); $i++){
            $posts[] = self::getPost($postsID[$i])->toArray();
        }
        return $posts;
    }

    static function getPostsAmigos($userid){
        $amigos = Amigos::obtenerAmigos($userid);
        $conn = new DatabaseConn();
        $postsID = $conn->postsDeAmigos($amigos);
        for($i = 0; $i < count($postsID); $i++){
            $posts[] = self::getPost($postsID[$i])->toArray();
        }
        return $posts;
    }

    private function uploadPost()
    {
        $conn = new DatabaseConn();
        $conn->subirPost(
            $this->id,
            $this->usuario,
            $this->texto,
            $this->imagen,
            $this->fecha 
        );
    }
    public function toArray()
    {
        return [
            "id" => $this->id,
            "usuario" => $this->usuario,
            "texto" => $this->texto,
            "imagen" => $this->imagen,
            "fecha" => $this->fecha
        ];
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }
    function getUsuario()
    {
        return $this->usuario;
    }
    function setTexto($texto)
    {
        $this->texto = $texto;
    }
    function getTexto()
    {
        return $this->texto;
    }
    function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
    function getImagen()
    {
        return $this->imagen;
    }
    function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    function getFecha()
    {
        return $this->fecha;
    }

}