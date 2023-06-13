<?php

require_once('repository/messagesRepository.php');
require_once('repository/usersRepository.php');

function getChatWith($messageList){
    
    $thisUserId = getUserId($_SESSION['user']);
    $resArr = [];
    for($i = 0; $i < $messageList->num_rows; $i++){
        
        $arr = $messageList->fetch_assoc();
        $recieved = $thisUserId != $arr['remite'];
        $resArr[] = [
            'recieved' => $recieved,
            'text' => $arr['texto'],
            'read' => $arr['leido'],
            'date' => $arr['fecha']
        ];
    }
    return $resArr;
}

function sendMessageTo($chatID){
    insertMessage($chatID, getUserId($_SESSION['user']), $_POST['message']);
}

function compareDates($a, $b)
{
    $time1 = date('Y-m-d H:i:s', strtotime($a['messages'][count($a['messages']) - 1]['date']));
    $time2 = date('Y-m-d H:i:s', strtotime($b['messages'][count($b['messages']) - 1]['date']));

    if ($time1 == $time2) {
        return 0;
    }
    return ($time1 < $time2) ? 1 : -1;
}

function getChats(){
    $userID = getUserId($_SESSION['user']);
    $friendsArr = getFriends($userID);
    $chatList = [];
    for($i = 0; $i < count($friendsArr); $i++){
        $messageList = getMessagesByChatId($friendsArr[$i]['id']);
        if($messageList->num_rows != 0){
            $chatList[] = [
                "id" => $friendsArr[$i]['id'],
                "user" => [
                    "name" => $friendsArr[$i]['user']['name'],
                    "username" => $friendsArr[$i]['user']['username'],
                    'imgType' => $friendsArr[$i]['user']['imgType'],
                    'id' => $friendsArr[$i]['user']['id']
                ],
                "messages" => getChatWith($messageList)
            ];
        }
    }
    usort($chatList, 'compareDates');
    echo json_encode($chatList);
}
function getChatList($username){
    $userID = getUserId($username);
    $friendsArr = getFriends($userID);
    $chatList = [];
    for($i = 0; $i < count($friendsArr); $i++){
        $messageList = getMessagesByChatId($friendsArr[$i]['id']);
        if($messageList->num_rows != 0){
            $lastMessage = $messageList->fetch_assoc();
            $chatList[] = [
                "user" => [
                    "name" => $friendsArr[$i]['user']['name'],
                    "username" => $friendsArr[$i]['user']['username'],
                    'imgType' => $friendsArr[$i]['user']['imgType'],
                    'id' => $friendsArr[$i]['user']['id']
                ],
                "text" => $lastMessage['texto'],
                "id" => $friendsArr[$i]['id'],
                "status" => ($userID == $lastMessage['remite']) ? 'send' : 'read',
                'unread' => null
            ];
        }
    }
    echo json_encode($chatList);
}

function getChatInfo($friendshipId){
    $friend = getFriendByFriendshipId($friendshipId);
    echo json_encode($friend);
}