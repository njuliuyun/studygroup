<?php 
session_start();
echo "<!DOCTYPE html>\n<html><head>";

require_once('../includes/functions.php');

$userstr = ' (Guest)';
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr = " ($user)";
}
else $loggedin = FALSE;

if (!isset($pagetitle)) $pagetitle = "";
echo "<title>$appname$userstr $pagetitle</title><link type='text/css' rel='stylesheet' href='style.css'>" .
    "</head><body>" .
    "<div class='$appname'>$appname$userstr</div><script src='../includes/javascript.js'></script>";
if ($loggedin) {
    echo "<br><ul class='menu'>" .
         "<li><a href='members.php?view=$user'>Home</a></li>" .
         "<li><a href='members.php'>Members</a></li>" .
         "<li><a href='friends.php'>Friends</a></li>" .
         "<li><a href='groups.php'>Groups</a></li>" .
         "<li><a href='messages.php'>Messages</a></li>" .
         "<li><a href='profile.php'>Edit profile</a></li>" .
         "<li><a href='logout.php'>Log out</a></li></ul><br>";
}
else {
    echo "<h1>Study together with your friends!</h1>";
}

