<?php
require_once('../templates/header.php');
//require_once('functions.php');
//session_start();

if (isset($_SESSION['user'])) {
    destroySession();
    //echo "<a href='index.php'>click here</a> to refresh the screen.";
    //header('Location: index.php');
    
    die("<script>window.location = 'index.php';</script>");
}
else {
    require_once('header.php');
    echo "<div class='main'><br>You cannot log out because you are not logged in.";
}
?>
        <br><br></div>
    </body>
</html>