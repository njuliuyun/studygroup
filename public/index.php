<?php
require_once('../templates/header.php');

if ($loggedin) {
    die("<script>window.location = 'home.php';</script>");
}
else die("<script>window.location = 'login.php';</script>");
?>
        </span><br><br>
    </body>
</html>