<?php
$pagetitle = " | Home"; 
$current = "home";
require_once('../templates/header.php');
if(!$loggedin) die("<script>window.location = 'index.php';</script>");
else {
    echo "<div class='display'>";
    showProfile($user);
    echo "<a class='button' href='groups.php?view=$user'>View your groups</a>" . 
         "<a class='button' href='messages.php?view=$user'>View your messages</a></div>";
    include_once('../templates/footer.php');
}