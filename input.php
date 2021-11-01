<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="JBWriterInput.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <?php

?>

<?php
session_start();
$author = $_SESSION["author"];
if (!isset($author) || $author != "jdbeacham"){
	header('Location:login1.php');
}

$action = $_GET['action'];
$id = $_GET['id'];



if (!isset($action)){
    $action = "insert";
}

if ($action == "update") {

    include_once "config/config.php";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT title, post, category, images, slideshowImages, smSnip FROM posts WHERE PostID = ?");
$stmt->bind_param( "i", $thisID );
$thisID = $id;

$stmt->execute();
$stmt->bind_result($title, $post, $category, $image, $slideshowImage, $snip);
$stmt->fetch();
    $_SESSION["image"] = $image;
}
?>

<div id="linkPop"></div>
<h1>Create, Update Post</h1>


        
        <div id = "inputWrapper">
            <form action="insert.php" method="post" enctype = "multipart/form-data">
                                <label><b>Title</b></label>
                <input type="text" name="title" size= "75" value="<?php echo $title ?>"><br><br>
                
                <label><b>Post Text</b></label><br>
                <input type="button" onclick="copy()" value="<p>">
                <input type="button" onclick="copy()" value="</p>">
                <input type="button" onclick="copy()" value="<br>">
                <input type="button" onclick="copy()" value="<b>">
                <input type="button" onclick="copy()" value="</b>">
                <input type="button" onclick="copy()" value="<i>">
                <input type="button" onclick="copy()" value="</i>">

                <input type="button" onclick="link()" value="<a>">
                
                <br><br>
                <textarea class="textarea" name="post" onclick="pasteFunction()"><?php echo $post ?></textarea><br><br>
                
                <input type="hidden" name="action" 
value="<?php echo $action ?>">
<input type="hidden" name="id" 
value="<?php echo $id ?>">

<div style="display: inline-block; position: relative;">
    
<?php
if ($image != ""){
    echo "<label style='position: relative; bottom: 10px;'><b>Current Image</b></label><br>";
    echo '<img src="data:image/png;base64,' . $image . '" height = "100px" width = "auto" ><br><br>';
}
?>
<label style="position: relative; bottom: 5px;"><b>New Image</b></label> <input type="file" name="image" 
style="position: relative; bottom: 10px;">
</div>


<div style="display: inline-block; position: relative;">
<?php
if ($slideshowImage != ""){
    echo "<label style='position: relative; bottom: 10px;'><b>Current Slideshow Image</b></label><br>";
    echo '<img src="data:image/png;base64,' . $slideshowImage . '" height = "100px" width = "auto" ><br><br>';
}
?>
<label style="position: relative; bottom: 5px;"><b>New Slideshow Image</b></label> 
<input type="file" name="slideshowImage" style="position: relative; bottom: 10px;">
</div><br><br>





<label><b>Category</b></label>
<select name="category" style="display: inline-block; position: relative;" >
    <?php 
    if ($category != ""){
        echo '<option value="' . $category . '" selected>' . $category . '</option>';
    }
    ?>
<option value="poem">poem</option>
<option value="political">political</option>
<option value="book">book</option>
<option value="about">about</option>
</select><br><br>
<label><b>Social Snipet</b></label><br>
<textarea class="textareaSnip" name="snip"><?php echo $snip ?></textarea>

                <input type="submit" value="Save" style="font-size: 18px; margin-left: 5px; 
                position: fixed; left: 580px; top: 210px; bottom: auto;">
                
                
                
                </form>

               
                <a href = "AdminPanel.php"><button value="cancel" style="font-size: 18px; margin-left: 5px; 
                position: fixed; left: 580px; top: 245px; border: solid #a7f78f 3px;
    border-radius: 3px;">Cancel</button></a>
                
                

                

                <script>
let paste = "";
let image = "<?php echo $_SESSION["image"]; ?>"
                function copy(){
                    paste = event.target.value;
                                    }

                function pasteFunction(){
                    let textArea = document.getElementsByClassName('textarea');
                    let pasteHere = textArea[0].selectionStart;
                    let a = textArea[0].value;
                    textArea[0].value = a.slice(0,pasteHere) + paste + a.slice(pasteHere);
                    paste = "";
                }

                

                function link(){

                    let linkPop = document.getElementById('linkPop');
                   linkPop.style.display = "block";
                    linkPop.innerHTML = 
                    '<button onclick=closeLinkAdd() style="position: absolute; right: 10px; font-size: .75em; border-radius: 4%;">x</button><br>' +
                    'Enter Link' + '<br>' +
                    '<input style=text id=addLink style="width: 800px;">' + '<br><br>' +
                    '<button onclick=linkAdd()>add link</button>'
                    
                    
                }
                

                    function linkAdd(){
                        linkPop.style.display = "none";
                    let linkAdded = document.getElementById('addLink')

                    let textArea = document.getElementsByClassName('textarea');
                    let linkStart = textArea[0].selectionStart;
                    
                    let linkEnd = textArea[0].selectionEnd;
                   
                    let a = textArea[0].value;

                    textArea[0].value = a.slice(0,linkStart) + '<a href="' + linkAdded.value + '">' + a.slice(linkStart,linkEnd) + '</a>'
                     + a.slice(linkEnd);
                    

                }

                function closeLinkAdd(){
                    let linkPop = document.getElementById('linkPop');
                    linkPop.style.display = "none";
                }



                </script>
                


        </div>



        
        
    </body>
    </html>