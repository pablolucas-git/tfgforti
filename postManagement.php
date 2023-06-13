<?php

require_once('repository/usersRepository.php');
require_once('repository/postsRepository.php');
require_once('generalFunctions.php');

function makePost(){
    $userId = getUserId($_SESSION['user']);
    $text = $_POST['descripcion'];
    $id = createPostID();
    $imgExtension = uploadImg($id);
    insertPost($userId, $text, $id, $imgExtension);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}


function uploadImg($id){
    $target_dir = dirname(__DIR__) . "/htdocs/public/res/imgs/post-pics/";
    $imageFileType = "." . strtolower(pathinfo($_FILES["imagen"]["name"],PATHINFO_EXTENSION));
    $target_file = $target_dir . $id . $imageFileType;
    $uploadOk = 1;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
    return $imageFileType;
}

function likePost($postId){
    $userId = getUserId($_SESSION['user']);
    insertLike($userId, $postId);
}

function dislikePost($postId){
    $userId = getUserId($_SESSION['user']);
    deleteLike($userId, $postId);
}

function getLikes($postId){
    $likes = getPostLikes($postId);
    echo json_encode($likes);
}

function savePost($postId){
    $userId = getUserId($_SESSION['user']);
    insertSavedPost($userId, $postId);
}

function unsavePost($postId){
    $userId = getUserId($_SESSION['user']);
    deleteSavedPost($userId, $postId);
}

function getSavedPosts(){
    $userId = getUserId($_SESSION['user']);
    $posts = getSavedPostsByUser($userId);
    return $posts;
}

function getSavedCount($postId){
    $count = getSavedPostCount($postId);
    echo json_encode($count);
}

function makeComent($postId){
    $userId = getUserId($_SESSION['user']);
    $text = $_POST['text'];
    insertComent($userId, $postId, $text);
}

function getComents($postId){
    $coments = getPostComents($postId);
    echo json_encode($coments);
}
function getComentsCount($postId){
    $count = count(getPostComents($postId));
    echo json_encode($count);
}

function getFriendPosts(){
    $userId = getUserId($_SESSION['user']);
    $posts = getPostsByFriends($userId);
    return $posts;
}

function getUserPosts($userId){
    $posts = getPostsByUser($userId);
    return $posts;
}
function showSavedPosts(){
    $attrs['user'] = getUserByUsername($_SESSION['user']);
    $attrs['posts'] = getSavedPosts();
    loadPage('savedPosts', $attrs);
}