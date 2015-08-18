<?php
$pagetitle = "| Create";
$current = "group"; 
require_once('../templates/header.php');

if (!$loggedin) die("<script>window.location = 'index.php';</script>");

$error = $course = $instructor = $coursename = "";
if (isset($_POST['course'])) {
    $course = sanitizeString($_POST['course']);
    $course = preg_replace('/\s+/', '', $course);
    $instructor = sanitizeString($_POST['instructor']);
    $coursename = sanitizeString($_POST['coursename']);
    $coursename = preg_replace('/\s\s+/', ' ', $coursename);
    if ($course == '') $error = 'The course code is required.';
    else {        
        $result = queryMysql("SELECT * FROM courses WHERE course='$course'");
        if ($result->num_rows) {
            $error = "That course already exists<br><br>";
        }
        else {            
            queryMysql("INSERT INTO courses VALUES('$course', '$instructor', '$coursename')");
            queryMysql("INSERT INTO groups VALUES('$user', '$course')");
            echo "Group $course is successfully created, and you are the first member.";
        }
    }
}

// the form to create group
echo <<<_END
        <h2 class='title'>Create a Group</h2>
        <form method="post" action="createGroup.php">$error<br>
            <label class="fieldname">Course Code</label>
            <input type="text" maxlength="16" name="course" value='$course' onBlur="checkCourse(this)"><span id="info"></span><br>
            <label class="fieldname">Course Name</label>
            <input type="text" maxlength="64" name="coursename" value='$coursename'"><br>            
            <label class="fieldname">Instructor</label>
            <input type="text" maxlength="16" name="instructor" value="$instructor"><br>
            <input class="submit" type="submit" value="Create">
        </form>
_END;

include_once('../templates/footer.php');