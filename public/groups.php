<?php
/*
 display all the existing groups. user can create a new one. 
*/

$pagetitle = " | Groups"; 
require_once('../templates/header.php');

if (!$loggedin) die("<script>window.location = 'index.php';</script>");

echo "<div class='main'>";

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
    echo "<h3>$name Groups</h3>";
    showGroups($view);
    echo $afterStr;
    die("</div></body></html>");
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

echo "<h3>Groups</h3><ul>";

for ($i = 0; $i < $num; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    echo "<li><a href='group.php?view=" . $row['course'] . "'>" . $row['course'] . "(" . $row['coursename'] . ")</a>";    
    // check if the user is in a group
    $result1 = queryMysql("SELECT * FROM groups WHERE user='$user' AND course='" . $row['course'] . "'");
    if ($result1->num_rows) {
        echo "You are in this group";
        echo "[<a href='groups.php?remove=" . $row['course'] . "'>drop</a>]";
    }
    else echo "[<a href='groups.php?add=" . $row['course'] . "'>join</a>]";    
    echo "</li>";
}
echo "</ul><div>didn't find your course? click <a href='createGroup.php'>here</a> to create a new group.</div>"
?>
        </div>
    </body>
</html>
