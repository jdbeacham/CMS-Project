<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="JBWriterInput.css" rel="stylesheet" type="text/css">
    </head>
    <body>

        <?php
        session_start();
        $author = $_SESSION["author"];
        if ($author == 'jdbeacham'){
        header('Location:AdminPanel.php');
        }
        if (!isset($author) || $author != "jdbeacham"){
            $needPassword = "You must enter a valid username and password :)";
        }
        ?>

<h1>Login</h1>
    <div id = "loginWrapper">
    <form action="login2.php" method="post">
        <h3 style="display: inline-block;">Username&nbsp&nbsp</h3>
        <input id="notinput" type="text" name="username"><br>
        <h3 style="display: inline-block;">Password&nbsp&nbsp</h3>
        <input id="notinput" type="text" name="password"><br><br>
<input class="adminButton" type="submit" value="login">
</form>

    </div>



        
</body>
    </html>
