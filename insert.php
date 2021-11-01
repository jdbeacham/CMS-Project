<?php

    $action = $_POST['action'];
  if (!isset ($action)){
    $action = $_GET['action'];
  }
    
    switch ($action) {
      case 'insert':
        insert();
        break;
        case 'update':
          update();
          break;
          case 'delete':
            delete();
            break;
                      default:
          insert();
    }

function insert(){

$title = $_POST["title"];
$post = $_POST["post"];

if ($_POST["category"] === ""){
  $_POST["category"] = null;
}
$category = $_POST["category"];
$snip = $_POST["snip"];

$image_oldname = $_FILES["image"]["tmp_name"];
  $image_type = $_FILES["image"]["type"];
  $image_dimensions = getimagesize($_FILES["image"]["tmp_name"]);
  $image_newname = $_FILES["image"]["name"];

  move_uploaded_file($image_oldname, "images/$image_newname");

 //reduce post image

 if ($image_type == "image/png") {


  $resize_image = imagecreatefrompng("images/$image_newname");
  
  $resize_imageTwo = imagescale($resize_image, 400, -1);
  ob_start();
imagepng($resize_imageTwo);
$finishSizing = ob_get_clean();
$resize_image = base64_encode($finishSizing);


}

if ($image_type == "image/jpeg") {
$resize_image = imagecreatefromjpeg("images/$image_newname");
$resize_image = imagescale($resize_image, 400, -1);
ob_start();
imagejpeg($resize_image);
$finishSizing = ob_get_clean();
$resize_image = base64_encode($finishSizing);
}

//make thumbnails

if ($image_type == "image/png") {
  $newSize_image = imagecreatefrompng("images/$image_newname");
  $newSize_image = imagescale($newSize_image, 250, -1);
  ob_start();
imagepng($newSize_image);
$finishSizing = ob_get_clean();
$newSize_image = base64_encode($finishSizing);
}

if ($image_type == "image/jpeg") {
$newSize_image = imagecreatefromjpeg("images/$image_newname");
$newSize_image = imagescale($newSize_image, 250, -1);
ob_start();
imagejpeg($newSize_image);
$finishSizing = ob_get_clean();
$newSize_image = base64_encode($finishSizing);
}

$slideshowImage = $_FILES["slideshowImage"]["tmp_name"];
$slideshowImage = base64_encode(file_get_contents(addslashes($slideshowImage)));

if ($title == ""){
  header('Location:AdminPanel.php');
  exit();
}


    include_once "config/config.php";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

	
  $stmt = $conn->prepare("INSERT INTO posts (title, post, category, images, thumbs, slideshowImages, smImages, smWidth, smHeight, smSnip)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param( "sssssssiis", $title, $post, $category, $resize_image, $newSize_image, $slideshowImage, 
  $image_newname, $image_dimensions[0], $image_dimensions[1], $snip );
$stmt->execute();

include_once "put.php";

header('Location:AdminPanel.php');
}

function update(){
    $title = $_POST["title"];
  $post = $_POST["post"];
  $id = $_POST["id"];
  $category = $_POST["category"];
  $snip = $_POST["snip"];
 
  $image_oldname = $_FILES["image"]["tmp_name"];
  $image_type = $_FILES["image"]["type"];
  $image_dimensions = getimagesize($_FILES["image"]["tmp_name"]);
  $image_newname = $_FILES["image"]["name"];

  move_uploaded_file($image_oldname, "images/$image_newname");

  //reduce post image

  if ($image_type == "image/png") {
       $resize_image = imagecreatefrompng("images/$image_newname");
        $resize_imageTwo = imagescale($resize_image, 400, -1);
  ob_start();
  imagepng($resize_imageTwo);
  $finishSizing = ob_get_clean();
  $resize_imageThree = base64_encode($finishSizing);
  }

if ($image_type == "image/jpeg") {
  $resize_image = imagecreatefromjpeg("images/$image_newname");
  $resize_imageTwo = imagescale($resize_image, 400, -1);
  ob_start();
imagejpeg($resize_imageTwo);
$finishSizing = ob_get_clean();
$resize_imageThree = base64_encode($finishSizing);
}
  
//make thumbnails

if ($image_type == "image/png") {
    $newSize_image = imagecreatefrompng("images/$image_newname");
    $newSize_image = imagescale($newSize_image, 250, -1);
    ob_start();
  imagepng($newSize_image);
  $finishSizing = ob_get_clean();
  $newSize_image = base64_encode($finishSizing);
}

if ($image_type == "image/jpeg") {
  $newSize_image = imagecreatefromjpeg("images/$image_newname");
  $newSize_image = imagescale($newSize_image, 250, -1);
  ob_start();
imagejpeg($newSize_image);
$finishSizing = ob_get_clean();
$newSize_image = base64_encode($finishSizing);
}

$slideshowImage = $_FILES["slideshowImage"]["tmp_name"];
$slideshowImage = base64_encode(file_get_contents(addslashes($slideshowImage)));


  if ($title == ""){
    header('Location:AdminPanel.php');
    exit();
  }

  
      include_once "config/config.php";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if ($resize_imageThree != ""){
    $stmt = $conn->prepare("UPDATE posts SET images= ?, thumbs= ?, smImages= ?, smWidth= ?, smHeight= ? WHERE PostID = ?");
  $stmt->bind_param( "sssiii", $resize_imageThree, $newSize_image, $image_newname, $image_dimensions[0], $image_dimensions[1], $id );
  
$stmt->execute();
  }

  if ($slideshowImage != ""){
    $stmt = $conn->prepare("UPDATE posts SET slideshowImages= ? WHERE PostID = ?");
  $stmt->bind_param( "si", $slideshowImage, $id );

$stmt->execute();
  }

  

  $stmt = $conn->prepare("UPDATE posts SET title= ?, post= ?, category= ?, smSnip= ? WHERE PostID = ?");
  $stmt->bind_param( "ssssi", $title, $post, $category, $snip, $thisid );
$thisid = $id;
$stmt->execute();



include_once "put.php";


header('Location:AdminPanel.php');
}

function delete(){
  $id = $_GET['id'];

  
      include_once "config/config.php";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 
  $stmt = $conn->prepare("DELETE FROM posts WHERE postID = ?");
  $stmt->bind_param( "i", $id );
$id = $id;

$stmt->execute();

if ($stmt->execute()) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . mysqli_error($conn);
}

header('Location:AdminPanel.php');

}









    ?>


