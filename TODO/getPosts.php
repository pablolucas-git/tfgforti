<?php

require_once "DatabaseConn.php";




// Create connection
$conn = new DatabaseConn();


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// $userid = $_POST["userid"];
// $accion = $_POST["accion"];
$userid = 4449;
$accion = "postsAmigos";
$posts = array();

if($accion == "postsAmigos"){
  $posts = $conn->postsDeAmigos($userid);
}
else{
  echo 'hol';
}

try {
  header('Content-type: application/json');
  echo json_encode($posts);
} catch (Exception $e) {
  echo 'nop';
}