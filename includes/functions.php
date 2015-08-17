<?php 
$dbhost = 'localhost';
$dbname = 'studygroup';
$dbuser = 'yun';
$dbpass = '709309';
$appname = "Study Group";

/* establish a connection to the database*/
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_error) die($connection->connect_error);

/* create a table in the database*/
function createTable($name, $query) {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
}

/* query the database*/
function queryMysql($query) {
    global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
}

/* destroy a session*/
function destroySession() {
    $_SESSION = array();
    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-259200, '/');
    }
    session_destroy();
}

/* sanitize a string */
function sanitizeString($var) {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}

/* secure a password */
function secure($pass) {
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    return hash('ripemd128', "$salt1$pass$salt2");
}

/* display someone's profile */
function showProfile($user) {
    if (file_exists("$user.jpg")) {
        echo "<img src='$user.jpg' style='float:left;'>";
    }
    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
    
    if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        echo stripslashes($row['text']) . "<br style='clear:both;'><br>";
    }    
}

/* display someone's groups */
function showGroups($user) {
    $result = queryMysql("SELECT * FROM groups, courses WHERE user='$user'AND groups.course = courses.course");
    $num = $result->num_rows;
    if ($num) {        
        echo "<ul>";
        for ($i = 0; $i < $num; $i++) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            echo "<li><a href='group.php?view=" . $row['course'] . "'>" . $row['course'] . "(" . $row['coursename'] . ")" .
            "</a></li>";
        }
        echo "</ul>";
    }
    else echo "<div>No groups yet.</div>";
    
}

function addGroup($user, $add) {
    $result = queryMysql("SELECT * FROM groups WHERE user='$user' AND course='$add'");
    if (!$result->num_rows) queryMysql("INSERT INTO groups VALUES('$user', '$add')");
}
function removeGroup($user, $remove) {
    queryMysql("DELETE FROM groups WHERE user='$user' AND course='$remove'");
}