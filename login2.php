<?php

session_start();

$user = $_POST['username'];
$pass = $_POST['password'];

if (!isset ($user) && !isset($pass)){
  exit('Please enter a username and password!');
}

include_once "config/config.php";

$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("SELECT Pword FROM people WHERE username = ?");
$stmt->bind_param( "s", $user );
$stmt->execute();
	
$stmt->bind_result($Pword);
$stmt->fetch();
  


if (password_verify($pass, $Pword)) {
  $_SESSION["author"] = $user;

header('Location:AdminPanel.php');
} else {
  header('Location:login1.php');

}

?>




        

