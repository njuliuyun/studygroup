<?php
$pagetitle = " | Members";
$current = "members"; 
require_once('../templates/header.php');
if(!$loggedin) die("<script>window.location = 'index.php';</script>");

// view profile
if (isset($_GET['view'])) {
    $view = sanitizeString($_GET['view']);
    if ( strtolower($view) ==  strtolower($user)) $name = "Your";
    else $name = "$view's";
    echo "<h2 class='title'>$name Profile</h2><div class='display'>";
    showProfile($view);
    echo "<a class='button' href='groups.php?view=$view'>View $name groups</a>" .
         "<a class='button' href='friends.php?view=$view'>View $name friends</a>" .
         "<a class='button' href='messages.php?view=$view'>View $name messages</a></div>";
    include_once('../templates/footer.php');
    die();
}
// add and remove friend
if (isset($_GET['add'])) {
    $add = sanitizeString($_GET['add']);
    $result = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='$add'");
    if (!$result->num_rows) queryMysql("INSERT INTO friends VALUES('$user', '$add')");    
}
elseif (isset($_GET['remove'])) {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE user='$user' AND friend='$remove'");
}

// display members
$result = queryMysql("SELECT user FROM members ORDER BY user");
$num = $result->num_rows;
echo "<h3 class='title'>Other members</h3><div class='display'><ul>";
for ($i = 0; $i < $num; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ( strtolower($row['user']) ==  strtolower($user)) continue;
    echo "<li><a href='members.php?view=" . $row['user'] . "'>" . $row['user'] . "</a>";
    // check friend status
    $follow = "follow";
    $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
    $t1 = $result1->num_rows;
    $result1 = queryMysql("SELECT * FROM friends WHERE user='" . $row['user'] . "' AND friend='$user'");
    $t2 = $result1->num_rows;
    if ($t1 + $t2 > 1) echo "&harr; is a mutual friend.";
    elseif ($t1) echo "&larr; you are following";
    elseif ($t2) {
        echo "&rarr; is following you";
        $follow = 'recip'; 
    }

    if (!$t1) {
        echo "<span class='action'>[<a href='members.php?add=" . $row['user'] . "'>$follow</a>]</span>";
    }
    else echo "<span class='action'>[<a href='members.php?remove=" . $row['user'] . "'>drop</a>]</span>";
    echo "</li>";
}
echo "</ul></div>";
include_once('../templates/footer.php');
