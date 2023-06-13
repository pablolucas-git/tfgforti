<?php
require_once "home.php";
require_once "usersManager.php";
require_once 'chatManagement.php';
require_once 'search.php';
require_once 'postManagement.php';

$routes = [
    '' => 'home',
    'profile' => 'showUser',
    'login' => 'doLogin',
    'prueba' => 'prueba',
    'register' => 'doRegister',
    'endSession' => 'endSession',
    'chatWith' => 'getChatWith',
    'sendMessageTo' => 'sendMessageTo',
    'chatList' => 'getChatList',
    'getChats' => 'getChats',
    'findUsers' => 'findUsers',
    'search' => 'search',
    'sendPost' => 'makePost', 
    'getPosts' => 'getFriendPosts', //??????
    'getUserPosts' => 'getUserPost', // ??????
    'prueba' => 'prueba',
    'likePost' => 'likePost',
    'dislikePost' => 'dislikePost',
    'getLikes' => 'getLikes',
    'savePost' => 'savePost',
    'unsavePost' => 'unsavePost',
    'getSavedPosts' => 'getSavedPosts', // ???????
    'getSavedCount' => 'getSavedCount',
    'makeComent' => 'makeComent',
    'getComents' => 'getComents',
    'getComentsCount' => 'getComentsCount',
    "getThisUserImg" => "getThisUserImg",
    "getChatInfo" => "getChatInfo",
    'getFriendsByUser' => 'getFriendsByUser',
    'getFriendsCount' => 'getFriendsCount',
    'findFriends' => 'findFriends',
    'getFriendRequests' => 'getFriendRequests',
    'acceptFriendRequest' => 'acceptFriendRequest',
    'rejectFriendRequest' => 'rejectFriendRequest',
    'sendFriendRequest' => 'sendFriendRequest',
    'unsendFriendRequest' => 'unsendFriendRequest',
    'getIds'    => 'getIds',
    "savedposts" => "showSavedPosts",

];

?>