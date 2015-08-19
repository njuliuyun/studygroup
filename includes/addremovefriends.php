<?php 
require_once('functions.php');

if (isset($_POST['add']) && isset($_POST['user'])) {
    $add = sanitizeString($_POST['add']);
    $user = sanitizeString($_POST['user']);
    $result = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='$add'");
    if (!$result->num_rows) {
        queryMysql("INSERT INTO friends VALUES('$user', '$add')");
        echo <<<_END
        <span id='friend' class='button' onclick="removeFriend('$user', '$add')">Unfollow $add</div></span>
_END;
       
    }
}

elseif (isset($_POST['remove']) && isset($_POST['user'])) {
    $remove = sanitizeString($_POST['remove']);
    $user = sanitizeString($_POST['user']);    
    $result = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='$remove'");
    if ($result->num_rows) {
        queryMysql("DELETE FROM friends WHERE user='$user' AND friend='$remove'");
        echo <<<_END
        <span id='friend' ><div class='button' onclick="addFriend('$user', '$remove')">Follow $remove</div></div></span>
_END;
    }
}