<?php

require_once("DatabaseConn.php");


try {

    if (isset($_POST["login"])) {
        iniciarSesion();
    }
    if (isset($_POST["registro"])) {
        registrar();
    }
    header("Location: /");
} catch (Exception $e) {
    echo $e;
}

function registrar()
{
    try {
        $database = new DatabaseConn();
        $email = $_POST["email"];
        $foto = $_FILES["foto"];
        $username = $_POST["username"];
        $password = md5($_POST["password"]);
        $nombre = $_POST["nombre"];
        setcookie("accion", "registro");
        $res = $database->comprobarRegistro(["email" => $email, "username" => $username, "nombre" => $nombre, "password" => $password]);
        if ($res === "mail") {
            setcookie("result", "problemaMail");
        } else if ($res === "user") {
            setcookie("result", "problemaUser");
        } else if ($res) {
            setcookie("result", 'registrado');
        } else {
            setcookie("result", 'fallo');
        }
    } catch (Exception $e) {
        echo $e;
    }
}

function iniciarSesion()
{
    $username = $_POST["username"];
    $password = md5($_POST["password"]);
    $database = new DatabaseConn();
    if ($database->comprobarLogin($username, $password)) {
        session_start();
        $_SESSION["usuario"] = $username;
    } else {
        setcookie("accion", "login");
        setcookie("result", "error");
    }
}