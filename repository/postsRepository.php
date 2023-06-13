<?php
require_once "model/Database.php";
require_once "usersRepository.php";

function insertPost($userID, $text, $id, $img = NULL,){
    $db = new Database();
    $date = new DateTime();
    $date_str = $date->format('Y-m-d H:i:s');
    $query = "INSERT INTO Posts VALUES($id, $userID, '$text', '$img', '$date_str')";
    $db->insert($query);

}

function createPostID(){
    $db = new Database();
    do{
        $id = random_int(0, 2147483646);
        $res = $db->select("SELECT * FROM Posts WHERE id = $id");
    }while($res->num_rows != 0);
    
    return $id;
}

function insertLike($userID, $postID){
    $db = new Database();
    $query = "INSERT INTO megusta VALUES($postID, $userID)";
    $db->insert($query);
}

function insertSavedPost($userID, $postID){
    $db = new Database();
    $date = new DateTime();
    $date_str = $date->format('Y-m-d H:i:s');
    $query = "INSERT INTO postguardados VALUES($postID, $userID, '$date_str')";
    $db->insert($query);
}

function deleteLike($userID, $postID){
    $db = new Database();
    $query = "DELETE FROM megusta WHERE post = $postID AND usuario = $userID";
    $db->delete($query);
}

function deleteSavedPost($userID, $postID){
    $db = new Database();
    $query = "DELETE FROM postguardados WHERE post = $postID AND usuario = $userID";
    $db->delete($query);
}

function getPostById($postid){
    $db = new Database();
    $query = "SELECT * FROM Posts WHERE id = $postid";
    $res = $db->select($query);
    $row = $res->fetch_assoc();
    $userId = getUserId($_SESSION['user']);
    $post = [
        'user' => getUserById($row['usuario']),
        'post' =>[
            'id' => $row['id'],
            'text' => $row['texto'],
            'imgType' => $row['imagen'],
            'comments' => getPostComents($row['id']),
            'commentsCount' => count(getPostComents($row['id'])),
            'likes' => getPostLikes($row['id']),
            'liked' => isLiked($userId, $row['id']),
            'saves' => getSavedPostCount($row['id']),
            'saved' => isSaved($userId, $row['id']),
        ]
    ];
    return $post;
}

function getSavedPostsByUser($userID){
    $db = new Database();
    $query = "SELECT * FROM postguardados WHERE usuario = $userID ORDER BY fecha DESC";
    $res = $db->select($query);
    $posts = array();
    while($row = $res->fetch_assoc()){
        $posts[] = getPostById($row['post']);
    }
    return $posts;
}

function getPostLikes($postID){
    $db = new Database();
    $query = "SELECT * FROM megusta WHERE post = $postID";
    $res = $db->select($query);
    return $res->num_rows;
}

function isLiked($userID, $postID){
    $db = new Database();
    $query = "SELECT * FROM megusta WHERE post = $postID AND usuario = $userID";
    $res = $db->select($query);
    return $res->num_rows > 0;
}

function getSavedPostCount($postID){
    $db = new Database();
    $query = "SELECT * FROM postguardados WHERE post = $postID";
    $res = $db->select($query);
    return $res->num_rows;
}

function isSaved($userID, $postID){
    $db = new Database();
    $query = "SELECT * FROM postguardados WHERE post = $postID AND usuario = $userID";
    $res = $db->select($query);
    return $res->num_rows > 0;
}

function insertComent($userID, $postID, $text){
    $db = new Database();
    $date = new DateTime();
    $date_str = $date->format('Y-m-d H:i:s');
    $query = "INSERT INTO comentarios VALUES($postID, $userID, '$text', '$date_str')";
    $db->insert($query);
}

function getPostComents($postID){
    $db = new Database();
    $query = "SELECT * FROM comentarios WHERE post = $postID";
    $res = $db->select($query);
    $coments = array();
    while($row = $res->fetch_assoc()){
        $coments[] = [
            'user' => getUserById($row['usuario']),
            'comment' => $row['texto'],
            'date' => $row['fecha']
        ];
    }
    return $coments;
}

function getPostsByFriends($userId){
    $friends = getFriends($userId);
    $db = new Database();
    $query = "SELECT * FROM Posts WHERE usuario IN (";
    foreach($friends as $friend){
        $query .= $friend['user']['id'] . ", ";
    }
    $query .= $userId . ") ORDER BY fecha DESC";
    $res = $db->select($query);
    $posts = array();
    while($row = $res->fetch_assoc()){
        $posts[] = [
            'user' => getUserById($row['usuario']),
            'post' =>[
                'id' => $row['id'],
                'text' => $row['texto'],
                'imgType' => $row['imagen'],
                'comments' => getPostComents($row['id']),
                'commentsCount' => count(getPostComents($row['id'])),
                'likes' => getPostLikes($row['id']),
                'liked' => isLiked($userId, $row['id']),
                'saves' => getSavedPostCount($row['id']),
                'saved' => isSaved($userId, $row['id']),
            ]
            ];
    }
    return $posts;
}

function getPostsByUser($userid){
    $user = getUserById($userid);
    $db = new Database();
    $query = "SELECT * FROM Posts WHERE usuario = $userid ORDER BY fecha DESC";
    $res = $db->select($query);
    $posts = array();
    while($row = $res->fetch_assoc()){
        $posts[] = [
            'user' => $user,
            'post' =>[
                'id' => $row['id'],
                'text' => $row['texto'],
                'imgType' => $row['imagen'],
                'comments' => getPostComents($row['id']),
                'commentsCount' => count(getPostComents($row['id'])),
                'likes' => getPostLikes($row['id']),
                'liked' => isLiked($userid, $row['id']),
                'saves' => getSavedPostCount($row['id']),
                'saved' => isSaved($userid, $row['id']),
            ]
            ];
    }
    return $posts;
}