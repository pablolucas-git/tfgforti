<?php

require_once 'generalFunctions.php';
require_once 'postManagement.php';
require_once 'repository/usersRepository.php';

function home(){
    if(validSession()){
        loadUserHomePage();
    }
    else{
        loadDefaultHomePage();
    }
}

function loadUserHomePage(){
    $posts = getFriendPosts();
    $attrs['posts'] = $posts;
    $attrs['user'] = getUserByUsername($_SESSION['user']);
    loadPage('home', $attrs);
}

function loadDefaultHomePage(){
    $attrs[] = null;
    if(isset($_COOKIE['loginResult'])){
        $attrs['loginResult'] = $_COOKIE['loginResult'];
        deleteCookie('loginResult');
    }
    if(isset($_COOKIE['registerResult'])){
        $attrs['registerResult'] = $_COOKIE['registerResult'];
        deleteCookie('registerResult');
    }
    loadPage('start', $attrs);
}

function prueba(){
    loadPage('prueba');
}