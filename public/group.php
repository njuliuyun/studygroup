<?php
/*
  display the information about a group. user can choose to join this group or drop it if the user is in the group.
*/
$pagetitle = " | Group Info";
require_once('../templates/header.php');
if (!$loggedin) die("<script>window.location = 'index.php';</script>");


//add or remove this group
if (isset($_GET['add'])) {    
    $add = sanitizeString($_GET['add']);
    addGroup($user, $add);
}
elseif (isset($_GET['remove'])) {    
    $remove = sanitizeString($_GET['remove']);
    removeGroup($user, $remove);
}

// display group information
if (isset($_GET['view'])) {    
    $view = sanitizeString($_GET['view']);
    $result_course = queryMysql("SELECT * FROM courses WHERE course='$view'");    
    if ($result_course->num_rows) {
        echo "<div class='display'><h2 class='title'>Course Information</h2>";
        //display a button for join/drop the group
        $result_join = queryMysql("SELECT * FROM groups WHERE user='$user' AND course='$view'");
        if ($result_join->num_rows) {
            echo "<a class='button inline' href='group.php?remove=$view&view=$view'>Drop this group</a>";
        }
        else echo "<a class='button inline' href='group.php?add=$view&view=$view'>Join this group</a>";
        
        // display course information
        $row_course = $result_course->fetch_array(MYSQLI_ASSOC);
        echo "<table>";
        echo "<tr><td>Course Code</td><td>" . $row_course['course'] . "</td></tr>" .
             "<tr><td>Course Name</td><td>" . $row_course['coursename'] . "</td></tr>" .
             "<tr><td>Instructor</td><td>" . $row_course['instructor'] . "</td></tr>";
        $result_member = queryMysql("SELECT user FROM groups WHERE course='$view'");
        $num_member = $result_member->num_rows;
        echo "<tr><td>Group Members</td><td>";
        if ($num_member) {
            for ($i = 0; $i < $num_member; $i++) {
                $row_member = $result_member->fetch_array(MYSQLI_ASSOC);
                echo "<a href='members.php?view=" . $row_member['user'] . "'>" . $row_member['user'] . "</a>";
                if ($i != $num_member-1) echo ", ";
            }
            
        } 
        else echo "<p>Nobody is here yet.</p>";
        echo "</td></tr></table>";
        
        echo "</div>";
        
        // display discussions
        
        // add a discussion
        if (isset($_POST['text'])) {
            $text = sanitizeString($_POST['text']);
            if ($text != '') {
                $time = time();
                queryMysql("INSERT INTO discussions VALUES(NULL, '$view', '$user', $time, '$text')");
            }            
        }
        // remove a discussion
        if (isset($_GET['erase'])) {
            $erase = sanitizeString($_GET['erase']);
            queryMysql("DELETE FROM discussions WHERE id=$erase AND user='$user'");
        }
        
        echo "<div class='display'><h2 class='title'>Course Discussions</h2>";
        $result_dis = queryMysql("SELECT * FROM discussions WHERE course='$view' ORDER BY time DESC");
        $num_dis = $result_dis->num_rows;
        if ($num_dis) {
            for ($i = 0; $i < $num_dis; $i++) {
                $row_dis = $result_dis->fetch_array(MYSQLI_ASSOC);
                echo "<div class='discuss'><a href='members.php?view=" . $row_dis['user'] . "'>" . $row_dis['user'] . "</a>: " . 
                "<span>" . $row_dis['message'] . "</span>";                
                if (strtolower($row_dis['user']) == strtolower($user)) {
                    echo "<span class='action'>[<a href='group.php?view=$view&erase=" . $row_dis['id'] . "'>erase</a>]</span>";
                }
                echo "<p class='date'>" . date('  M jS \'y g:ia', $row_dis['time']) . "</p>";
                echo "</div>";
            }
        } else echo "<p>No discussions yet.</p>";
        echo "</div>";
        
        // discussion submit form
        // only group members can post a discussion
        echo "<div class='display'>";
        $result_user = queryMysql("SELECT * FROM groups WHERE course='$view' AND user='$user'");
        if ($result_user->num_rows) {
                echo <<<_END
            
            <form class='left' method="post" action="group.php?view=$view">
            Type here to post a discussion:<br>
            <textarea name="text" cols="40" rows="3"></textarea><br>
            <input class="submit" type="submit" value="Post Discussion"></form>
_END;
        }
        else echo " Join this group to discuss with other members.";
        echo "</div>";
    }
    else echo "<div class='display'><h2 class='title'>Course Information</h2><p>Course not found.</p></div>"; 
}
else {
    echo "<div class='display'><h2 class='title'>Course Information</h2><p>No group selected.</p></div>";   
}
echo "</div>";

// show discussion


include_once('../templates/footer.php');

