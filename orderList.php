<?php

$idArray = $_POST['id'];


include_once "config/config.php";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
    }
for ($i = 0; $i < count($idArray); $i++) {
    if ($_POST[$idArray[$i]] >= 1 and $POST[$idArray[$i]] <=6) {

  $stmt = $conn->prepare("UPDATE posts SET slideShowID = ? WHERE PostID = ?");
  $stmt->bind_param( "ss", $_POST[$idArray[$i]], $idArray[$i] );
$stmt->execute();

    }

    else {
        $stmt = $conn->prepare("UPDATE posts SET slideShowID = ? WHERE PostID = ?");
  $stmt->bind_param( "ss", $zero, $idArray[$i] );
  $zero = '0';
$stmt->execute();
    }




}
header('Location:AdminPanel.php');

?>