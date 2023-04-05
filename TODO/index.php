<?php
require_once '../phpmyadmin/vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader(".");

$twig = new \Twig\Environment($loader);


session_start();

if (isset($_SESSION["usuario"])) {
    echo $twig->render('template.html', array('usuario' => $_SESSION["usuario"]));
} else {
    $attrs = array();
    if (isset($_COOKIE["accion"])) {
        $attrs = [
            "accion" => $_COOKIE["accion"],
            "result" => $_COOKIE["result"]
        ];
        setcookie("accion", "", time() - 3600);
        setcookie("result", "", time() - 3600);
        //will reset cookie(client,browser)
        unset($_COOKIE["accion"]);
        unset($_COOKIE["result"]);
        // will destroy cookie(server)
    }
    echo $twig->render('inicio.html', ["attrs" => $attrs]);
}