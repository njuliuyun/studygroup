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
echo "<h3 class='title'>$name2 Messages</h3><div class='display'>";
showProfile($view);

// erase a message
if (isset($_GET['erase'])) {
    $erase = sanitizeString($_GET['erase']);
    queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
}
/* display messages */
echo "<div class='content'>";

// pagination
$page = 1;
$num_per_page = 10;
$pages = 1;

// determine the total pages
$result_page = queryMysql("SELECT * FROM messages WHERE recip='$view'");
$pages = ceil($result_page->num_rows / $num_per_page);

echo "</p>";

if (isset($_GET['page'])) {
    $page = sanitizeString($_GET['page']);            
}
$offset = ($page-1)*$num_per_page;

$query = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC LIMIT $num_per_page OFFSET $offset";
$result = queryMysql($query);
$num = $result->num_rows;
$end = min($num, $num_per_page);
if ($num) {
    echo "<p class='page'>page ";
    $href = "messages.php?view=$view";
    showPage($page, $pages, $href);
    for ($i = 0; $i < $end; $i++) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if ($row['pm'] == 0 || strtolower($row['auth']) == strtolower($user) || strtolower($row['recip']) == strtolower($user)) {
            
            echo "<div class='message clearfix'><a href='members.php?view=" . $row['auth'] . "'>" . showImage($row['auth']) . 
            "<div class='message-text'>" . $row['auth'] . "</a>";
            // public messages
            if ($row['pm'] == 0) {
                echo " wrote: &quot;" . $row['message'] . "&quot;";
            }
            // private messages
            else echo " whispered: <span class='whisper'><i>&quot;" . $row['message'] . "&quot;</i></span>";
            
            if (strtolower($row['recip']) == strtolower($user)) {
                echo "<span class='action'>[<a href='messages.php?erase=" . $row['id'] . "'>erase</a>]</span>";
            }
            echo "<p class='date'>" . date('  M jS \'y g:ia', $row['time']) . "</p>";
            echo "</div></div>";
        }
    }
    echo "<p class='page'>page ";        
    showPage($page, $pages, $href);
    echo "</p>";
}
else echo "<br><span class='info'>No messages yet</span><br><br>";
echo "</div><a class='button' href='messages.php?view=$view'>Refresh messages</a></div>";
echo "</div>";

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
