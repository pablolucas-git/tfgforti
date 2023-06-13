<?php

require_once "generalFunctions.php";
require_once "repository/usersRepository.php";

function search(){
    if(validSession()){
        $attrs['user'] = getUserByUsername($_SESSION['user']);
        loadPage('search', $attrs);
    }
    else{
        header('Location: /');
    }
}