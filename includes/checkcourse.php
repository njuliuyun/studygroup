<?php 
require_once('functions.php');

if (isset($_POST['course'])) {
    $course = sanitizeString($_POST['course']);
    $result = queryMysql("SELECT * FROM courses WHERE course='$course'");
    if ($result->num_rows) {
        echo "<span class='taken'>&nbsp;&#x2718; " . "This group already exists</span>";
    }
    //else echo "<span class='available'>&nbsp;&#x2714; " . "This username is available</span>";
}