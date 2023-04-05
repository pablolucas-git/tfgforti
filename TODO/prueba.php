<?php

require_once("db_entities/Post.php");

$post = Post::getPostsAmigos(4449);
try {
    header('Content-type: application/json');
    echo json_encode($post);
  } catch (Exception $e) {
    echo 'nop';
  }
