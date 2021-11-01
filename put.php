<?php


$dashTitle = preg_replace("/[\s\/\?]/", "-", $title);

$insertDropDown = <<<EOT
<?php
for (\$i = 0; \$i <= count(\$menuItem); \$i++) {
	for (\$j = 0; \$j <= count(\$menuItem); \$j++) {
		if (\$menuOrder[\$j] == \$i + 1) {

	echo '<div class="menuListItem">
<a href="'
. \$menuURL[\$j] . 
'">'
. \$menuItem[\$j] . '</a>
</div>';
}}}
?>
EOT;
	


$pageContents = '

<!DOCTYPE html>
<html lang="en">
<head>
<title>' . $title . '</title>
	<meta charset="UTF-8">
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
  gtag(\'js\', new Date());

  gtag(\'config\', \'G-211ZCEP6HH\');
</script>

<?php

include_once "config/config.php";
	
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT images, smImages, smWidth, smHeight FROM posts WHERE PostID = ?");
$stmt->bind_param( "i", $id );
$id =' . $id . ';
$stmt->execute();
$stmt->bind_result($images, $image_newname, $image_width, $image_height);
$stmt->fetch();

$stmt->close();

?>

<meta name="twitter:card" content="summary" />
<meta name="twitter:creator" content="@JohnDBeacham" />
	<meta property="twitter:image" content="https://www.johnbeacham.com/images/<?php echo $image_newname ?>
	" />
	<meta property="twitter:url" content="https://www.johnbeacham.com/' . $dashTitle . '
	" />
<meta property="twitter:title" content="' . $title . '" />
	<meta property="og:image" content="https://www.johnbeacham.com/images/<?php echo $image_newname ?>
	" />
	<meta property="og:image:width" content="<?php echo $image_width ?>" />
<meta property="og:image:height" content="<?php echo $image_height ?>"  />
<meta property="og:image:description" content="' . $snip . '"  />
	

</head>
<body>
<?php 

$menuItem = array();
$menuURL = array();
$menuOrder = array();
$stmt = $conn->prepare("SELECT * FROM menu");
$stmt->execute();
$stmt->bind_result($ID, $thisMenuItem, $thisMenuURL, $thisMenuOrder);
while ($stmt->fetch()) {
	array_push($menuItem, $thisMenuItem);
	array_push($menuURL, $thisMenuURL);
	array_push($menuOrder, $thisMenuOrder);
	}




?>

<div class = "menuButton" onclick="dropDown()" style="width: 40px; position: fixed; z-index: 6;">Menu</div>

<div id="menuContainerPost">'

. $insertDropDown .

'</div>

<div id="navbarContainer">
<div class="navbarText">John Beacham</div>
<div class="navbarText" style="color: #411000; font-size: 1em">writes poetry, political, flash fiction, code</div>
</div>

<div id="articleImage"><img src="data:image/png;base64,<?php echo $images ?>" width="100%"></div>
<div id="articleContainer">
<div id="categoryContainer">
<div id="articleCategory">' . $category . '</div>
<div id="shareContainer">
<div id="shareImage" onclick="toggleShare()"><img src="ShareButtonJB.png" style="width: 35px;"></div>
<div id="shareOpen" class="articleCategoryFadeIn";>
<div class="menuListItemShare" style="width: 10px;">
<a href="https://www.facebook.com/sharer/sharer.php?u=https://www.johnbeacham.com/' . $dashTitle . '
.php&t=' . $title . '">F</a>
</div>
<div class="menuListItemShare" style="width: 10px;">
<a href="https://twitter.com/intent/tweet?text=' . $title . ' https://www.johnbeacham.com/' . $dashTitle . '.php">T</a>
</div>

</div>
</div>
</div>

<div id="articleContent">
<p id = "articleTitle">' . $title . '</p>'

. $post . '</div>

</div>

<div id = "listContainer">
<div id = "postContainer">

<?php

$stmt = $conn->prepare("SELECT title, category, thumbs FROM posts WHERE category != \"about\" ORDER BY postdate DESC LIMIT 10  ");
$stmt->execute();
$stmt->bind_result($title, $category, $thumbs);
while ($stmt->fetch()) {
	$dashTitlePosts = preg_replace("/[\s\/\?]/", "-", $title);
	echo \'
		<div class = "posts">
		<a href="\' . $dashTitlePosts . \'.php">
	<div class = "postsImage"><img src="data:image/png;base64,\' . $thumbs . \'"></div>
	<div class = "postsCategory">\' . $category . \'</div>
	<div class = "postsTitle">\' . $title . \'</div>
	</a></div>\';
}
?>

</div></div>

<div id = "footerContainer">
	<div id = "footerImage"><img src="JBLogo.png"></div>
	<div id = "footerText">The pen and the sword are mightier weapons when correctly
		wielded together at the proper point in time and space</div> 
</div>

<script>
let menuButton = document.getElementsByClassName("menuButton");

function dropDown() {
    let articleCategory = document.getElementById("articleCategory");
	articleCategory.style.display="none";

	let menuContainerPost = document.getElementById("menuContainerPost");
	menuContainerPost.style.display="block";

	let shareContainer = document.getElementById("shareContainer");
	shareContainer.style.display="none";

	menuButton[0].addEventListener("click", closeMenu);

}
let click = 0;
function closeMenu() {
	click++;
	if (click % 2 == 0) {
		dropDown();
	}
	else {
	articleCategory.style.display="block";
	articleCategory.className = "articleCategoryFadeIn";
	menuContainerPost.style.display="none";
	shareContainer.className = "articleCategoryFadeIn";
	shareContainer.style.display="block";
	
	}
}

function toggleShare() {
	let shareOpen = document.getElementById("shareOpen");
	shareOpen.style.display="block";
		let shareImage = document.getElementById("shareImage");
	shareImage.addEventListener("click", toggleClose);
	
}
let shareClick = 0;
function toggleClose () {
	shareClick++;
	if (shareClick % 2 == 0) {
	toggleShare();
}
else {
	shareOpen.style.display="none";
}
}
</script>

</body>
</html>









';














file_put_contents($dashTitle . '.php', $pageContents);
?>