<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="JBWriterInput.css" rel="stylesheet" type="text/css">
</head>
<body>


<?php
session_start();
$author = $_SESSION["author"];
if (!isset($author) || $author != "jdbeacham"){
	header('Location:login1.php');
}
echo '<h1>Administration Panel</h1><br>';
?>
<div id = "buttonWrapper">
<div id="now" style="position: relative; border-radius: 3px 0px 0px 3px;"><a href="input.php"><button class = "adminButtonOne">New Post</button></a></div>
<div id="now" style="position: relative;"><button class = "adminButton" onclick="sliderOrder()">Slider Order</button></div>
<div id="now" style="position: relative;"><button class = "adminButtonOne" onclick="menuLinks()">Menu Links</button></div>
<div id="now" style="position: relative; border-radius: 0px 3px 3px 0px;"><a href="logout.php"><button class = "adminButton">Logout</button></a></div>
<br></div>



<?php
include_once "config/config.php";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM posts";
$result = $conn -> query($sql);
$postsRowcount = $result -> num_rows;


$titlesList = array();
$rowSeven = array();
$fifteensPostsArray = array();

$stmt = $conn->prepare("SELECT postID, title, category, postdate, slideShowID FROM posts ORDER BY postdate DESC");
$stmt->execute();
$stmt->bind_result($postID, $title, $category, $postdate, $slideShowID);
while ($stmt->fetch()) {
	//Slideshow order array
	$thisArray = array($postID=>$title);
	$titlesList = $titlesList + $thisArray;
array_push($rowSeven, $slideShowID);

//Posts into groups of fifteen array
	$postsArray = array();
	
	array_push($postsArray, $postID, $title, $category, $postdate, $slideShowID);
	array_push($fifteensPostsArray, $postsArray);
}	

		$chunkedPosts = array_chunk($fifteensPostsArray, 15);
		$c = ceil(count($chunkedPosts));
		$_SESSION["c"] = $c;


for ($i = 0; $i < $c; $i++) {
echo '<div id = "wrapper-' . $i . '">';

for ($j = 0; $j < 15; $j++) {

	if (is_null($chunkedPosts[$i][$j])){
		break;
	}
	

	$date = date_create($chunkedPosts[$i][$j][3]);
	$date = date_format($date,"D M j h:i.sa");
	
	if ($j % 2 == 0) {
		echo '<div id = "headers" style="color: midnightblue; font-size:18px;">Title: </div>';
	}
 else {echo '<div id = "headers" style="font-size:18px;">Title: </div>';
}
		
		echo '<div id = "nowTitle" style="font-size:16px;">' . '<a href="input.php?action=update&id=',$chunkedPosts[$i][$j][0],'">' . $chunkedPosts[$i][$j][1] . '</a>' . '</div><br>
		<br><div id = "headers" style="width: 125px;">Category</div>
		<div id = "headers" style="width: 150px;">Date</div>
		<div id = "headers" style="width: 125px; text-align: center;">Slide Order</div>
		
		<div class = "postRow">
		<div id = "nowTitle" style="width: 125px;">' . $chunkedPosts[$i][$j][2] . '</div>';
echo '<div id = "nowDate" style="width: 150px;">' . $date . '</div>
<div id = "nowTitle" style="width: 125px; text-align: center;">' . $chunkedPosts[$i][$j][4] . '</div>';
echo '<div id = "now">' . '<a href = "insert.php?action=delete&id=',$chunkedPosts[$i][$j][0],'">

<button class="adminButton" style="font-size: 11px;">DELETE POST</button></a>' . '</div><br>';

echo '<hr style="position: relative;"></div>';
}

echo '</div><br>';
}
if ($postsRowcount > 15) {
	for ($i = 0; $i < $c; $i++){
echo '<div id="fifteensButton-' . $i . '" style="position: relative;"> 
<button class="adminButton" onclick="nextFifteen()" style="font-size:14px; margin-left: 10px;" value="' . $i . '">Posts '
. ($i * 2 + 1) . ' - ' . ($i + 1) * 2 . '</button></div>';
	}
}
// Slideshow order popup
echo '<div id="sliderPop"';

if (isset($_POST['orderListPop'])) {
	echo 'style="display:block;"';
}


echo '>
<button onclick=closeOrderLink() style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button><br>
<form action="orderList.php" method="post">
<div class="popList" style="font-size: 20px;"><b>Titles</b></div><br><br>';
$countOrders = 0;
foreach($titlesList as $x => $val){
	if ($countOrders == 11) {
		break;
	}
echo '<div class="popList">' . $val .  '</div>
<input type="number" style="padding: 3px;" class="popInputs" value="' . $rowSeven[$countOrders] . '" size = "3" name="' . $x . '">
<input type="hidden" name="id[]" value="' . $x . '">'
;
$countOrders++;
}
if (isset($_POST['addId'])){
		$addIdArray = $_POST['addId'];
		$getSearchTitles = array();
		for ($i = 0; $i < count($addIdArray); $i++) {
		$stmt = $conn->prepare("SELECT title FROM posts WHERE postID = ?");
		$stmt->bind_param( "s", $addIdArray[$i] );
		$stmt->execute();
		$stmt->bind_result($titles);
while ($stmt->fetch()) {
		array_push($getSearchTitles,$titles);
		}
	}

	for ($i = 0; $i < count($getSearchTitles); $i++){
		echo '<div class="popList">' . $getSearchTitles[$i] . '</div>
		<input type="number" style="padding: 3px;" class="popInputs" value="0" size = "3" name="' . $addIdArray[$i] . '">
		<input type="hidden" name="id[]" value="' . $addIdArray[$i] . '">';
		
	}
}


echo ' <br><br><input type="submit" class="adminButtonOne" style="position: relative; display: inline-block" value="Order">
<button type = "button" class = "adminButton" onclick = "searchTitlesPop()" 
style="position: relative; display: inline-block">Search Titles</button></div>
</form>';

// Search titles pop

echo '<div id="searchTitlesPop">
<button onclick="closeSearchTitles()" value = "1" style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button>
<div id="searchTitle" class="popList"><b>Search Titles</b></div>
<div id="searchWrapper">
<form action="AdminPanel.php" method="post" >
<input type="text" name="search" class="popInputs">
<input type="submit" class="adminButton" value="Search">
</form>
</div></div>
';

// Searching titles
$searchArray = array();
$idArray = array();
if (isset($_POST['search'])) {
	$search = $_POST['search'];
	$sendSearch = '%' . $search . "%";
	$stmt = $conn->prepare("SELECT postID, title FROM posts WHERE title LIKE ?");
	$stmt->bind_param( "s", $sendSearch );
$stmt->execute();
$stmt->bind_result($postID, $title);
while ($stmt->fetch()) {
	array_push($idArray, $postID);
	array_push($searchArray, $title);
}
echo '<div id="checkSearchPop" style="display: block;">
<button onclick=closeSearchLink() style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button><br>
<div class = "popList"><b>Choose Titles</b></div><br><br>
<form action="AdminPanel.php" method="post">';
for ($i = 0; $i < count($searchArray); $i++) {
	echo '<input type="checkbox" class="checkbox" name="addId[]" value="' . $idArray[$i] . '">
	<div class="popList">' . $searchArray[$i] . '</div><br><br>
	<input type="hidden" name="orderListPop" value="orderListPop">'
;
}
echo '<input type="submit" class="adminButtonOne" value="Add Title">
</form>

</div>';
}

// Menu links popup for posts

echo '<div id="menuPop" style="overflow: auto; height: 90%;">

<button onclick="closeMenuLink()" style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button><br>
<form action="menuLinks.php" method="post">
<div class="popList" style="font-size: 20px;"><b>Post Menu Links</b></div><br><br>
<button type = "button" class = "adminButton" onclick="addMenuItem()">Add Another Link</button>';

$menuID=array();
$menuName=array();
$menuURL=array();
$menuOrder=array();

$sql = "SELECT * FROM menu";
$result = $conn -> query($sql);
$rowcount = $result -> num_rows;

$stmt->prepare("SELECT * FROM menu");
$stmt->execute();
$stmt->bind_result($menuIDdb, $menuNamedb, $menuURLdb, $menuOrderdb);
while ($stmt->fetch()) {
array_push($menuID,$menuIDdb);
array_push($menuName,$menuNamedb);
array_push($menuURL, $menuURLdb);
array_push($menuOrder,$menuOrderdb);
}

if ($rowcount == 0) {
echo '<div class = "menuEntryContainer">
<button onclick="closeMenuItem()" value = "1" style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button>
<div class = "menuEntry">
<div class="menuList"><b>Link 1</b></div><br>
<div class="menuList">Menu Item Name: </div>
<input type="text" name="menuItem[]" class="popInputs" value=""><br>
<div class="menuList">URL: </div>
<input type="text" name="menuURL[]" class="popInputs" value=""><br>
<div class="menuList">Menu Order: </div>
<input type=number size=3 name=menuOrder[] class=popInputs style="padding: 3px;"><br>
</div>
</div>';

}

else {
	for ($i=0; $i<$rowcount; $i++){
		echo '<div class = "menuEntryContainer">
		<button onclick="closeMenuItem()" value="' . $i . '"style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button>
		<div class = "menuEntry">
<div class="menuTitle"><b>Link ' . ($i + 1) . '</b></div><br>
<div class="menuList">Menu Item Name: </div>
<input type="text" name="menuItem[]" class="popInputs" value="' . $menuName[$i] . '"><br>
<div class="menuList">URL: </div>
<input type="text" name="menuURL[]" class="popInputs" value="' . $menuURL[$i] . '"><br>
<div class="menuList">Menu Order: </div>
<input type=number size=3 name=menuOrder[] style="padding: 3px;" class="popInputs" value="' . $menuOrder[$i] . '"><br>
</div>
</div>';
	}
}

echo ' <br><br><input type="submit" class="adminButton" value="Submit Links" style="font-size:20px;"><br>
</form></div>';



?>

<script>

function sliderOrder(){
	let sliderPop = document.getElementById('sliderPop')
	sliderPop.style.display = "block";
	let menuPop = document.getElementById('menuPop')
	menuPop.style.display = "none";
	let searchTitlesPop = document.getElementById('searchTitlesPop');
	searchTitlesPop.style.display = "none";
	let checkSearchPop = document.getElementById('checkSearchPop');
	checkSearchPop.style.display = "none";
}

function menuLinks(){
	let menuPop = document.getElementById('menuPop')
	menuPop.style.display = "block";
	let sliderPop = document.getElementById('sliderPop')
	sliderPop.style.display = "none";
	let searchTitlesPop = document.getElementById('searchTitlesPop');
	searchTitlesPop.style.display = "none";
	let checkSearchPop = document.getElementById('checkSearchPop');
	checkSearchPop.style.display = "none";
}

function closeOrderLink(){
    let sliderPop = document.getElementById('sliderPop');
    sliderPop.style.display = "none";
                }

function closeMenuLink(){
    let menuPop = document.getElementById('menuPop');
	menuPop.style.display = "none";
                }

function closeSearchLink() {
	let checkSearchPop = document.getElementById('checkSearchPop');
	checkSearchPop.style.display = "none";
}

function closeSearchTitles(){
	let searchTitlesPop = document.getElementById('searchTitlesPop');
	searchTitlesPop.style.display = "none";
}
	

function addMenuItem(){
	let menuEntryContainer = document.getElementsByClassName('menuEntryContainer');
	let menuEntry = document.getElementsByClassName('menuEntry');
	let menuValue = (menuEntry.length + 1).toString();
	let addContainer = document.createElement('div');
	addContainer.innerHTML =
	"<div class = menuEntryContainer>" +
	"<button onclick=closeMenuItem() style=position:absolute;right:10px;font-size:.75em;border-radius:4%;>x</button>" +
	"<div class=menuEntry>" +
	"<div class=menuList><b>Link&nbsp;" + (menuEntry.length + 1) + "</b></div><br>" +
"<div class=menuList>Menu Item Name:&nbsp;</div>" +
"<input type=text name=menuItem[] class=popInputs><br>" +
"<div class=menuList>URL:&nbsp;</div>" +
"<input type=text name=menuURL[] class=popInputs><br>" +
"<div class=menuList>Menu Order: </div>" +
"<input type=number size=3 name=menuOrder[] class=popInputs><br>" +
"</div></div>";
	menuEntryContainer[menuEntryContainer.length - 1].appendChild(addContainer);
}

function closeMenuItem() {
	let targetvalue = event.target.value;
	event.target.parentElement.remove();
	let menuTitle = document.getElementsByClassName('menuTitle');
	for (i = 0; i < menuTitle.length; i++) {
		menuTitle[i].innerHTML = "<b>Current Link " + (i + 1) + "</b>";
	}
}

function nextFifteen() {
	let c = "<?php echo $_SESSION["c"]?>";
	for (i = 0; i < c; i++) {
		let thisI = document.getElementById('wrapper-' + i);
		thisI.style.display = "none";

		let buttonsReveal = document.getElementById('fifteensButton-' + i);
		buttonsReveal.style.display = "inline-block";
	}
	let value = event.target.value;

	let thisFifteen = document.getElementById('wrapper-' + value);
	thisFifteen.style.display = "block";

	let buttonHide = document.getElementById('fifteensButton-' + value);
	buttonHide.style.display = "none";
}

function searchTitlesPop(){
let searchTitlesPop = document.getElementById('searchTitlesPop');
searchTitlesPop.style.display = "block";
let sliderPop = document.getElementById('sliderPop');
    sliderPop.style.display = "none";
}

function checkSearchPop() {
	let checkSearchPop = document.getElementById('checkSearchPop');
	checkSearchPop.style.display = "block";
}


</script>


</body>
</html>