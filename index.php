<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>John Beacham: writes poetry, political, flash fiction, code</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" 
      type="image/png" 
      href="https://www.johnbeacham.com/JBLogo.png">
	
	<link href="JBWriter.css" rel="stylesheet" type="text/css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@1,800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;500&display=swap" rel="stylesheet">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-211ZCEP6HH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-211ZCEP6HH');
</script>

</head>

<body>


	

<?php


include_once "config/config.php";
	
$conn = new mysqli ($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
session_start();
$_SESSION["slideshowImages"] = array();
$_SESSION["slideshowText"] = array();
$_SESSION["categories"] = array();
$_SESSION["dashTitle"] = array();



$stmt = $conn->prepare("SELECT title, category, slideshowImages FROM posts WHERE slideShowID > '0' ORDER BY slideShowID ASC LIMIT 5");
$stmt->execute();
$stmt->bind_result($slide_title, $category, $slideshowImages);
while ($stmt->fetch()) {

	$dashTitleOne = preg_replace("/[\s\/\?]/", "-", $slide_title);
	$dashTitleTwo = "<a href='" . $dashTitleOne . ".php'>" . $slide_title . "</a>";
	array_push($_SESSION["dashTitle"],$dashTitleOne);
	array_push($_SESSION["slideshowImages"],$slideshowImages);
	array_push($_SESSION["slideshowText"],$slide_title);
	array_push($_SESSION["categories"],$category);

}

?>

<div id="navbarContainer">
<div class="navbarText navBarAniOne">John Beacham</div>
<div class="navbarText navBarAniTwo" style="text-shadow: 0px 0px 2px #000000; color: white; 
font-size: 1em;">writes poetry, political, flash fiction, code</div>
</div>
	<div id = "containerOne">
	<div id = "menuContainer">
<div class = "menuButton"><a href ="about.php">John Beacham</a></div>
<div class = "menuButton" style="top: 10px;"><a href="https://www.facebook.com/2020SocialismBook">Facebook</a></div>
<div class = "menuButton"><a href ="https://www.mass-action.org">MASS ACTION</a></div>


	</div>
	<div id="slideshowContainer"></div>
	</div>





<div id="bookContainer">

<?php

$stmt = $conn->prepare("SELECT title, post, images FROM posts WHERE category = 'book' ORDER BY postdate DESC LIMIT 2  ");
$stmt->execute();
$stmt->bind_result($title, $post, $images);
while ($stmt->fetch()) {	
	$dashIn = preg_replace("/[\s\/]/", "-", $title);
	$contentSplit = preg_split("/(\<\/p\>)/", $post);
	$contentSplitTwo = preg_split("/(\<\/p\>)/", $contentSplit[1]);
	$moreAdd = $contentSplit[0] . $contentSplitTwo[0] . '<p><a href = "' . $dashIn . '.php"> ... read more</a></p>';


	echo '<div class = "books">
		<center><div class = "bookImage"><img src="data:image/png;base64,' . $images . '"width = "100%"></div></center>
		<div class = "bookTitle">' . $title . '</div>
	<div class = "bookContent">' . $moreAdd . '</div>
	
	</div>';
	
}
$stmt->reset();

echo '</div>';

echo '<div id = "listContainer">
<div id = "postContainer">';


$stmt = $conn->prepare("SELECT title, category, thumbs FROM posts WHERE category != 'about' ORDER BY postdate DESC LIMIT 10  ");

$stmt->execute();

$stmt->bind_result($list_title, $list_category, $thumbs);
echo $list_title . $list_category;
while ($stmt->fetch()) {
	$dashTitlePosts = preg_replace("/[\s\/\?]/", "-", $list_title);

	echo '<div class = "posts"><a href="' . $dashTitlePosts . '.php">
	<div class = "postsImage"><img src="data:image/png;base64,' . $thumbs . '"></div>
	<div class = "postsCategory">' . $list_category . '</div>
	<div class = "postsTitle">' . $list_title . '</div></a>
	</div>';
}
echo '</div></div>';

?>


<div id = "footerContainer">
	<div id = "footerImage"><img src="JBLogo.png"></div>
	<div id = "footerText">The pen and the sword are mightier weapons when correctly
		wielded together at the proper point in time and space</div> 
</div>

<script>


let slideshowImages = [];
slideshowImages[0] = "<?php echo $_SESSION["slideshowImages"][0]; ?>"
slideshowImages[1] = "<?php echo $_SESSION["slideshowImages"][1]; ?>"
slideshowImages[2] = "<?php echo $_SESSION["slideshowImages"][2]; ?>"
slideshowImages[3] = "<?php echo $_SESSION["slideshowImages"][3]; ?>"
slideshowImages[4] = "<?php echo $_SESSION["slideshowImages"][4]; ?>"


let slideshowText = [];
slideshowText[0] = "<?php echo $_SESSION["slideshowText"][0]; ?>"
slideshowText[1] = "<?php echo $_SESSION["slideshowText"][1]; ?>"
slideshowText[2] = "<?php echo $_SESSION["slideshowText"][2]; ?>"
slideshowText[3] = "<?php echo $_SESSION["slideshowText"][3]; ?>"
slideshowText[4] = "<?php echo $_SESSION["slideshowText"][4]; ?>"

spliceText();

function spliceText () {
	for (i = 0; i < 5; i++) {
	if (slideshowText[i].length > 30){
		let slicy = slideshowText[i].slice(0, 30);
		let placey = slicy.search(/\s\w+$/);
		let superSlicy = slicy.slice(0, placey);
		slideshowText[i] = superSlicy + " ...";
			}
}}

let categories = [];
categories[0] = "<?php echo $_SESSION["categories"][0]; ?>"
categories[1] = "<?php echo $_SESSION["categories"][1]; ?>"
categories[2] = "<?php echo $_SESSION["categories"][2]; ?>"
categories[3] = "<?php echo $_SESSION["categories"][3]; ?>"
categories[4] = "<?php echo $_SESSION["categories"][4]; ?>"


let dashTitle = [];
dashTitle[0] = "<?php echo $_SESSION["dashTitle"][0]; ?>"
dashTitle[1] = "<?php echo $_SESSION["dashTitle"][1]; ?>"
dashTitle[2] = "<?php echo $_SESSION["dashTitle"][2]; ?>"
dashTitle[3] = "<?php echo $_SESSION["dashTitle"][3]; ?>"
dashTitle[4] = "<?php echo $_SESSION["dashTitle"][4]; ?>"




let loopCount = 0;
let count = 1;
let widthVariable
let okDokay = setInterval(slideshowTimer, 6000);

let slideshowContainer = document.getElementById('slideshowContainer');
var thisimage = slideshowImages[0];
slideshowContainer.innerHTML = '<a href="' + dashTitle[0] + '.php">' +
'<img src="data:image/png;base64,' + thisimage + '" width="100%"></a>' +
'<div class="slideshowText">' + categories[0] + ': ' + slideshowText[0] + '</div>';

function slideshowTimer(){

	
	
	
thisimage = slideshowImages[count];
widthVariable = slideshowText[count].length
slideshowContainer.innerHTML = '<a href="' + dashTitle[count] + '.php">' +
'<img src="data:image/png;base64,' + thisimage + '" width="100%"></a>' +
'<div class="slideshowText">' + categories[count] + ': ' + slideshowText[count] + '</div>';


count = count + 1;

if (count == 5){
	count = 0;
	loopCount = loopCount + 1;

	if (loopCount == 2) {
			
			clearInterval(okDokay);
			return lastImage();
		
			}
	
}

}

function lastImage() {
	thisimage = slideshowImages[0];
		
	slideshowContainer.innerHTML = '<a href="' + dashTitle[0] + '.php">' +
'<img src="data:image/png;base64,' + thisimage + '" width="100%" id="lastImage"></a>' +
'<div class="slideshowText">' + categories[0] + ': ' + slideshowText[0] + '</div>';
			nuts = document.getElementById('lastImage');
		nuts.style.animationName="none";
}

</script>

</body>
</html>