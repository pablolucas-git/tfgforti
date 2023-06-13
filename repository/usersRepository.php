<?php

require_once "model/Database.php";
require_once "entities/Users.php";
function userExists($user)
{
    $db = new Database();
    $query = "SELECT * FROM Usuario u WHERE u.username='$user'";

    $result = $db->select($query);
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}
function emailExists($email)
{
    $db = new Database();
    $query = "SELECT * FROM Usuario u WHERE u.email='$email'";

    $result = $db->select($query);
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}
function insertUserIntoDatabase( $name, $username, $password, $email, $imgType)
{
    try{
    $db = new Database();
    $id = createUserID();
    $query = "INSERT INTO Usuario VALUES($id, '$name', '$username', '$password', '$email', '$imgType')";

    return $db->insert($query);
    }
    catch(Exception $e){
        echo $e;
        return false;
    }
}

function createUserID(){
    $db = new Database();
    do{
    $id = random_int(0, 2147483646);
    $res = $db->select("SELECT * FROM Usuario WHERE id = $id");
    }while($res->num_rows != 0);
    
    return $id;
}

function isRequest($friendid){
    $userid = getUserId($_SESSION['user']);
    $db = new Database();
    $query = "SELECT * FROM Amigos WHERE usuario2 = $friendid AND usuario1 = $userid AND aceptado = 0";
    $res = $db->select($query);
    if($res->num_rows > 0){
        return true;
    }
    return false;
}

function getUserId($username){
    $db = new Database();
    $res = $db->select("SELECT * FROM Usuario WHERE username ='$username'");
    return $res->fetch_assoc()['id'];
}

function validPassword($user, $password)
{
    $db = new Database();
    $query = "SELECT * FROM Usuario u WHERE u.username='$user' and u.password = '$password'";

    $result = $db->select($query);
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

function getUsersFromWord($word){
    $db = new Database();
    $query = "SELECT * FROM Usuario u WHERE lower(u.username) LIKE '%$word%' OR lower(u.nombre) LIKE '%$word%'";
    $result = $db->select($query);
    $arr = $result->fetch_all(MYSQLI_ASSOC);
    $usersArr = [];
    foreach($arr as $user){
        $usersArr[] = [
            "username" => $user['username'],
            "id" => $user['id'],
            "name" => $user['nombre'],
            "isFriend" => isFriend($user['id']),
            "imgType" => $user['foto']
        ];
    }
    return $usersArr;
}

function getFriends($userId){
    $db = new Database();
    $query = "SELECT * FROM Amigos WHERE usuario1=$userId OR usuario2=$userId";
    $result = $db->select($query);
    $arr = $result->fetch_all(MYSQLI_ASSOC);
    $friendsArr = [];
    foreach($arr as $friendRelation){
        $friendId = ($friendRelation['usuario1'] == $userId) ? $friendRelation['usuario2'] : $friendRelation['usuario1'];
        $friendsArr[] = [
            "user" => User::getPublicUserById($friendId),
            "id" => $friendRelation['id']
        ];
    }
    return $friendsArr;
}
function isFriend($userId){
    $db = new Database();
    $thisUserId = getUserId($_SESSION['user']);
    $query = "SELECT * FROM Amigos WHERE (usuario1=$userId AND usuario2=$thisUserId) OR (usuario1=$thisUserId AND usuario2=$userId)";
    $result = $db->select($query);
    if($result->num_rows > 0){
        return $result->fetch_assoc()['aceptado'] == 1;
    }
    return false;
}

function getAllIds(){
    $db = new Database();
    $query = "SELECT id FROM Posts";
    $result = $db->select($query);
    $arr = $result->fetch_all(MYSQLI_ASSOC);
    $idsStr = "";
    foreach($arr as $id){
        $idsStr .= $id['id'] . ",";
    }
    return substr($idsStr, 0, -1);
}


function getUserByUsername($username){
    $publicUser = User::getPublicUserById(getUserId($username));
    return $publicUser;
}

function getUserById($userID){
    $user = User::getPublicUserById($userID);
    return $user;
}

function getFriendByFriendshipId($friendID){
    $db = new Database();
    $query = "SELECT * FROM Amigos WHERE id=$friendID";
    $result = $db->select($query);
    $arr = $result->fetch_all(MYSQLI_ASSOC);
    $friendId = ($arr[0]['usuario1'] == getUserId($_SESSION['user'])) ? $arr[0]['usuario2'] : $arr[0]['usuario1'];
    return getUserById($friendId);
}

function getFriendshipId($userid){
    $db = new Database();
    $thisUserId = getUserId($_SESSION['user']);
    $query = "SELECT * FROM Amigos WHERE (usuario1=$userid AND usuario2=$thisUserId) OR (usuario1=$thisUserId AND usuario2=$userid)";
    $result = $db->select($query);
    $arr = $result->fetch_all(MYSQLI_ASSOC);
    return $arr[0]['id'];
}

function getFriendsFromWord($words){
    $userList = getUsersFromWord($words);
    $friendsList = [];
    foreach($userList as $user){
        if($user['isFriend']){
            $friendsList[] = [
                'user' => $user,
                'id' => getFriendshipId($user['id'])
            ];
        }
    }
    return $friendsList;
}

function getFriendRequestsByUser($userID){
    $db = new Database();
    $query = "SELECT * FROM Amigos WHERE usuario2=$userID AND aceptado=0";
    $result = $db->select($query);
    $arr = $result->fetch_all(MYSQLI_ASSOC);
    $friendRequestsArr = [];
    foreach($arr as $friendRequest){
        $friendRequestsArr[] = [
            "user" => User::getPublicUserById($friendRequest['usuario1']),
            "id" => $friendRequest['id']
        ];
    }
    return $friendRequestsArr;
}

function acceptFriendRequestByFriend($friendRequestID){
    $db = new Database();
    $query = "UPDATE Amigos SET aceptado=1 WHERE id=$friendRequestID";
    $db->update($query);
}

function rejectFriendRequestByFriend($friendRequestID){
    $db = new Database();
    $query = "DELETE FROM Amigos WHERE id=$friendRequestID";
    $db->delete($query);
}

function insertFriendRequest($userID, $friendID){
    $db = new Database();
    $friendshipId = createFriendshipId();
    $query = "INSERT INTO Amigos VALUES ($friendshipId, $userID, $friendID, 0)";
    $db->insert($query);
}

function deleteFriendRequest($userID, $friendID){
    $db = new Database();
    $query = "DELETE FROM Amigos WHERE usuario1=$userID AND usuario2=$friendID";
    $db->delete($query);
}

function createFriendshipId(){
    $db = new Database();
    do{
    $id = random_int(0, 2147483646);
    $res = $db->select("SELECT * FROM Amigos WHERE id = $id");
    }while($res->num_rows != 0);
    
    return $id;
}