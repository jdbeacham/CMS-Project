<?php
session_start();
$_SESSION = array();
session_unset ($_SERVER['author']);
session_destroy();

header('Location:login1.php');
?>
