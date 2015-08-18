<?php
$pagetitle = " | Search"; 
$current = "search";
require_once('../templates/header.php');
if (!$loggedin) die("<script>window.location = 'index.php';</script>");

echo "<div class='display clearfix'>";

// search course button
echo "<form class='left float' method='get' action='search.php'><input class='search' type='text' name='course'><input class='submit search' type='submit' value='Search Group'></form>";
// search people button
echo "<form class='left float' method='get' action='search.php'><input class='search' type='text' name='people'><input class='submit search' type='submit' value='Search People'></form>";

// search by course code or name 
if (isset($_GET['course'])) {
    $course = sanitizeString($_GET['course']);
    $found = FALSE;
    echo "<h3>Search result for &quot;$course&quot;: </h3><ul>";
    
    $result_course = queryMysql("SELECT * FROM courses WHERE course LIKE '%$course%' OR coursename LIKE '%$course%'");
    $num_course = $result_course->num_rows;
    if ($num_course) {
        $found = TRUE;
        for ($i = 0; $i < $num_course; $i++) {
            $row_course = $result_course->fetch_array(MYSQLI_ASSOC);
            echo "<li><a href='group.php?view=" . $row_course['course'] . "'>" . $row_course['course'] . " " . $row_course['coursename'] . "</a></li>";
        }
        
    }    
    echo "</ul>";
    if (!$found) echo "<p>Sorry, didn't find this group. <a href='createGroup.php'>Create it</a>?</p>";
    else echo "<p>Did not find the group you are looking for? <a href='createGroup.php'>Click here</a> to create it.</p>";
}

// search user name 
if (isset($_GET['people'])) {
    $people = sanitizeString($_GET['people']);
    $found = FALSE;
    echo "<h3>Search result for &quot;$people&quot;: </h3><ul>";
    
    $result_people = queryMysql("SELECT * FROM members WHERE user LIKE '%$people%'");
    $num_people = $result_people->num_rows;
    if ($num_people) {
        $found = TRUE;
        for ($i = 0; $i < $num_people; $i++) {
            $row_people = $result_people->fetch_array(MYSQLI_ASSOC);
            echo "<li><a href='members.php?view=" . $row_people['user'] . "'>" . $row_people['user'] . "</a></li>";
        }        
    }    
    echo "</ul>";
    if (!$found) echo "<p>Sorry, didn't find this user.</p>";
}
echo "</div>";
include_once('../templates/footer.php');