<?php

require_once 'entities/Users.php';
require_once 'repository/usersRepository.php';
require_once 'generalFunctions.php';
require_once 'postManagement.php';

function doLogin(){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $result = "";
    if(userExists($username)){
        if(validPassword($username, $password)){
            $_SESSION['user'] = $username;
            $result = 'logged';
        }
        else{
            $result = 'wrongPassword';
        }
    }
    else{
        $result = 'wrongUser';
    }
    echo json_encode([
        'loginResult' => $result
    ]);
}

function endSession(){
    $_SESSION['user'] = null;
    session_unset();
    header('Location: /');
}

function findUsers($words){
    $userList = getUsersFromWord($words);
    echo json_encode($userList);
}

function doRegister(){
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $email = $_POST['email'];
    $imgType = getProfilePicExtension();

    $user = new User($name, $lastname, $username, $password, $email, $imgType);
    $registerresult = $user->registerUser();
    if($registerresult == 'success'){
        $newUserId = getUserId($username);
        uploadProfilePic($newUserId);
    }
    echo json_encode([
        'registerResult' => $registerresult
    ]);

}
function getProfilePicExtension(){
    $imageFileType = "." . strtolower(pathinfo($_FILES["registerImage"]["name"],PATHINFO_EXTENSION));
    return $imageFileType;
}
function uploadProfilePic($id){
    $target_dir = dirname(__DIR__) . "/htdocs/public/res/imgs/profile-pics/";
    $imageFileType = "." . strtolower(pathinfo($_FILES["registerImage"]["name"],PATHINFO_EXTENSION));
    $target_file = $target_dir . $id . $imageFileType;
    $uploadOk = 1;
    move_uploaded_file($_FILES["registerImage"]["tmp_name"], $target_file);
}

function showUser($username){
    $user = getUserByUsername($username);
    $friendsCount = getFriendsCount($username);
    $posts = getUserPosts(getUserId($username));
    loadPage('user', [
        'userToShow' => $user,
        'user' => getUserByUsername($_SESSION['user']),
        'friendsCount' => $friendsCount,
        'postCount' => count($posts),
        'isSelf' => $username == $_SESSION['user'],
        'isFriend' => isFriend(getUserId($username)),
        'posts' => $posts,
        'isRequest' => isRequest(getUserId($username)),
    ]);
    
}

function getIds(){
    $ids = getAllIds();
    echo $ids;
}

function getFriendsByUser($username){
    $id = getUserId($username);
    $friends = getFriends($id);
    echo json_encode($friends);
}

function getFriendsCount($username){
    $id = getUserId($username);
    $count = count(getFriends($id));
    return $count;
}

function getPostCount($username){
    $id = getUserId($username);
    $count = count(getPosts($id));
    return $count;
}

function findFriends($words){
    $userID = getUserId($_SESSION['user']);
    $friends = getFriendsFromWord($words);
    echo json_encode($friends);
}

function getFriendRequests(){
    $userID = getUserId($_SESSION['user']);
    $requests = getFriendRequestsByUser($userID);
    echo json_encode($requests);
}

function acceptFriendRequest($friendshipID){
    acceptFriendRequestByFriend($friendshipID);
}

function rejectFriendRequest($friendshipID){
    rejectFriendRequestByFriend($friendshipID);
}

function sendFriendRequest($username){
    $userID = getUserId($_SESSION['user']);
    $friendID = getUserId($username);
    insertFriendRequest($userID, $friendID);
}

function unsendFriendRequest($username){
    $userID = getUserId($_SESSION['user']);
    $friendID = getUserId($username);
    deleteFriendRequest($userID, $friendID);
}
function getThisUserImg(){
    $username = $_SESSION['user'];
    $user = getUserByUsername($username);
    $imgSrc = $user['id'] . $user['imgType'];
    echo json_encode($imgSrc);
}