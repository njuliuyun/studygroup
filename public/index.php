<?php
require_once('../templates/header.php');
echo "<br><span class='main'>Welcome to $appname, ";

if ($loggedin) {
    echo " $user, you are logged in.";
}
else die("<script>window.location = 'login.php';</script>");;
?>
        </span><br><br>
    </body>
</html>