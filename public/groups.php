<?php
/*
 display all the existing groups. user can create a new one. 
*/

$pagetitle = " | Groups"; 
$current = "groups";
require_once('../templates/header.php');

if (!$loggedin) die("<script>window.location = 'index.php';</script>");

// view groups
if (isset($_GET['view'])) {
    $view = sanitizeString($_GET['view']);
    if ( strtolower($view) ==  strtolower($user)) {
        $name = "Your";
        $afterStr = "<a class='button' href='groups.php'>Join more groups</a>";
    }
    else {
        $name = "$view's";
        $afterStr = "";
    }
    echo "<h2 class='title'>$name Groups</h2><div class='display'>";
    showGroups($view);
    echo $afterStr;
    die("</div></div></body></html>");
}

//add or remove groups
if (isset($_GET['add'])) {    
    $add = sanitizeString($_GET['add']);
    addGroup($user, $add);
}
elseif (isset($_GET['remove'])) {    
    $remove = sanitizeString($_GET['remove']);
    removeGroup($user, $remove);
}
// display groups
$result = queryMysql("SELECT course, coursename FROM courses ORDER BY course");
$num = $result->num_rows;

echo "<h2 class='title'>Groups</h2><div class='display'><ul>";

for ($i = 0; $i < $num; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    echo "<li><a href='group.php?view=" . $row['course'] . "'>" . $row['course'] . " " . $row['coursename'] . "</a>";    
    // check if the user is in a group
    $result1 = queryMysql("SELECT * FROM groups WHERE user='$user' AND course='" . $row['course'] . "'");
    if ($result1->num_rows) {
        echo "<span class='action'> You are in this group";
        echo "[<a href='groups.php?remove=" . $row['course'] . "'>drop</a>]</span>";
    }
    else echo "<span class='action'>[<a href='groups.php?add=" . $row['course'] . "'>join</a>]<span class='action'>";    
    echo "</li>";
}
echo "</ul><div>didn't find your course? click <a href='createGroup.php'>here</a> to create a new group.</div></div>";
include_once('../templates/footer.php');