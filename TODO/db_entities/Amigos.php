<?php

require_once "./DatabaseConn.php";
class Amigos{

    static function obtenerAmigos($userid){
        $conn = new DatabaseConn();
        return $conn->obtenerAmigos($userid);
    }




}