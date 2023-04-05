<?php
session_start();
unset($_SESSION['usuario']);
 session_destroy();
 header('Location: index.php');
$_SESSION = array();
header("Location: /");