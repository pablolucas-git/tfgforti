<?php

class Usuario
{
    private int $id;
    private string $nombre;
    private string $username;
    private string $password;
    private string $email;



    function __construct($id){
        
    }

























    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function getNombre()
    {
        return $this->nombre;
    }
    function setUsername($username)
    {
        $this->username = $username;
    }
    function getUsername()
    {
        return $this->username;
    }
    function setPassword($password)
    {
        $this->password = $password;
    }
    function getPassword()
    {
        return $this->password;
    }
    function setEmail($email)
    {
        $this->email = $email;
    }
    function getEmail()
    {
        return $this->email;
    }

}