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

echo "<title>$appname $pagetitle</title><link type='text/css' rel='stylesheet' href='style.css'>" .
    "</head><body>" .
    "<div class='appname'><h2>$appname$userstr</h2></div>";
    
if (!isset($pagetitle)) $pagetitle = "";
if (!isset($current)) $current = "";
if ($current != 'login' && $current != 'signup') {
    echo "<div class='icon'><a  href='search.php'><img src='img/search.png'></a>";
    echo "<a href='logout.php'><img src='img/logout.png'></a></div>";
}
if ($loggedin) {
    echo "<div class='main-wrapper clearfix'><div class='columnL'><nav><ul class='menu'>" ?>
         <li <?php if ($current == 'home') echo "class='current'";?>><a href='home.php'>Home</a></li>
         <li <?php if ($current == 'members') echo "class='current'";?>><a href='members.php'>Members</a></li>
         <li <?php if ($current == 'friends') echo "class='current'";?>><a href='friends.php'>Friends</a></li>
         <li <?php if ($current == 'groups') echo "class='current'";?>><a href='groups.php'>Groups</a></li>
         <li <?php if ($current == 'group') echo "class='current'";?>><a href='creategroup.php'>Create Group</a></li>
         <li <?php if ($current == 'messages') echo "class='current'";?>><a href='messages.php'>Messages</a></li>         
         <li <?php if ($current == 'profile') echo "class='current'";?>><a href='profile.php'>Edit profile</a></li>
         <li <?php if ($current == 'search') echo "class='current'";?>><a href='search.php'>Search</a></li>
         <li><a href='logout.php'>Log out</a></li></ul></nav></div>
    <!--.main-->
    <div class="columnR"><div class='main'>
<?php }
else {
    /*<!--.main-->*/
    echo "<div class='main-wrapper clearfix'><div><div class='main'>";
    echo "<div class='sale'><h1>Study with your friends!</h1><p><i>Study together. Learn better.</i></p></div>";
}

