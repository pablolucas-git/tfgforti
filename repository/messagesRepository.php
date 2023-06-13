<?php
require_once "model/Database.php";

function getMessagesByChatId($id){
    $db = new Database();
    $query = "SELECT * FROM chats WHERE conversacion=$id ORDER BY fecha";

    $result = $db->select($query);
    return $result;
}

function insertMessage($chatID, $remiteID, $text){
    $db = new Database();
    $date = new DateTime();
    $date_str = $date->format('Y-m-d H:i:s');
    $query = "INSERT INTO chats VALUES($chatID, $remiteID, '$text', 1, '$date_str')";
    $db->insert($query);
}