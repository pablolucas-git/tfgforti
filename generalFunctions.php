<?php

require_once '../phpmyadmin/vendor/autoload.php';
require_once 'repository/usersRepository.php';

function validSession(){
    if(isset($_SESSION['user']) && userExists($_SESSION['user'])){
        return true;
    }
    return false;
}


function loadPage($templateName, $atrrs = [null]){
    $loader = new \Twig\Loader\FilesystemLoader("./public/");
    $twig = new \Twig\Environment($loader);
    echo $twig->render($templateName . '.html', $atrrs);
}

function deleteCookie($cookieName){
    if (isset($_COOKIE[$cookieName])) {
        unset($_COOKIE[$cookieName]); 
        setcookie($cookieName, null, -1, '/'); 
        return true;
    } else {
        return false;
    }
}