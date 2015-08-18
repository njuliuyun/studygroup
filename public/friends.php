<?php 
$pagetitle = "| Friends";
$current = "friends";
require_once('../templates/header.php');

if (!$loggedin) die("<script>window.location = 'index.php';</script>");

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else $view = $user;

if ($view == $user) {
    $name1 = $name2 = "Your";
    $name3 = "You are";
}
else {
    $name1 = "<a href='members.php?view=$view>$view</a>'s";
    $name2 = "$view's";
    $name3 = "$view is";
}
echo "<h2 class='title'>$name2 Friends</h2><div class='display'>";
$followers = array();
$following = array();

$result = queryMysql("SELECT * FROM friends WHERE user='$view'");
$num = $result->num_rows;

for ($i = 0; $i < $num; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $following[$i] = $row['friend'];
}

$result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
$num = $result->num_rows;

for ($i = 0; $i < $num; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $followers[$i] = $row['user'];
}

$mutual = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
$friends = FALSE;

if (sizeof($mutual)) {
    echo "<span class='subhead'>$name2 mutual friends</span><ul>";
    foreach ($mutual as $friend) {
        echo "<li><a href='members.php?view=$friend'>$friend</a></li>";
    }
    echo "</ul>";
    $friends = TRUE;
}
if (sizeof($followers)) {
    echo "<span class='subhead'>$name2 followers</span><ul>";
    foreach ($followers as $friend) {
        echo "<li><a href='members.php?view=$friend'>$friend</a></li>";
    }
    echo "</ul>";
    $friends = TRUE;
}
if (sizeof($following)) {
    echo "<span class='subhead'>$name3 following</span><ul>";
    foreach ($following as $friend) {
        echo "<li><a href='members.php?view=$friend'>$friend</a></li>";
    }
    echo "</ul>";
    $friends = TRUE;
}

if (!$friends) echo "<br>You don't have any friends yet.<br><br>";

echo "</div>";
include_once('../templates/footer.php');