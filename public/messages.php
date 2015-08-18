<?php
$pagetitle = " | Messages"; 
$current = "messages";
require_once('../templates/header.php');

if (!$loggedin) die("<script>window.location = 'index.php';</script>");

if (isset($_GET['view'])) {
    $view = sanitizeString($_GET['view']);    
} else $view = $user;

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    if ($text != '') {
        $pm = sanitizeString($_POST['pm']);
        $time = time();
        queryMysql("INSERT INTO messages VALUES(NULL, '$user', '$view', '$pm', $time, '$text')");
    }
}

if ($view != '') {
    if ($view == $user) $name1 = $name2 = "Your";
    else {
        $name1 = "<a href='members.php?view=$view'>$view</a>'s";
        $name2 = "$view's";
    }
}
echo "<h2 class='title'>$name2 Messages</h2><div class='display'>";
showProfile($view);

// erase a message
if (isset($_GET['erase'])) {
    $erase = sanitizeString($_GET['erase']);
    queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
}
// display messages
$query = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
$result = queryMysql($query);
$num = $result->num_rows;
echo "<div class='content'>";
for ($i = 0; $i < $num; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['pm'] == 0 || strtolower($row['auth']) == strtolower($user) || strtolower($row['recip']) == strtolower($user)) {
        echo date('M jS \'y g:ia: ', $row['time']);
        echo "<a href='members.php?view=" . $row['auth'] . "'>" . $row['auth'] . "</a> ";
        if ($row['pm'] == 0) {
            echo "wrote: &quot;" . $row['message'] . "&quot;";
        }
        else echo "whispered: <span class='whisper'>&quot;" . $row['message'] . "&quot;</span>";
        
        if (strtolower($row['recip']) == strtolower($user)) {
            echo "<span class='action'>[<a href='messages.php?erase=" . $row['id'] . "'>erase</a>]</span>";
        }
        echo "<br>";
    }
}
if (!$num) echo "<br><span class='info'>No messages yet</span><br><br>";

echo "</div><a class='button' href='messages.php?view=$view'>Refresh messages</a></div>";
// message form
echo <<<_END
        <div class='display'>
        <form class='left' method="post" action="messages.php?view=$view">
        Type here to leave a message:<br>
        <textarea name="text" cols="40" rows="3"></textarea><br>
        Public<input type="radio" name="pm" value="0" checked="checked">
        Private<input type="radio" name="pm" value="1">
        <input class="submit" type="submit" value="Post Message"></form>
        </div>
_END;
include_once('../templates/footer.php');
