<?php
$pagetitle = "| Register";
$current = 'signup'; 
require_once('../templates/header.php');


// check submitted information

$error = $user = $pass = $pass2 = "";

if (isset($_SESSION['user'])) destroySession();
if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']); 
    $pass2 = sanitizeString($_POST['passc']);
    if ($user == '' || $pass=='' || $pass2 == '') $error = 'Not all fields were entered.';
    else if ( $pass != $pass2) $error = "The passwords don't match.";
    else {
        /* $result = queryMysql("SELECT * FROM members WHERE user='$user'");
        if ($result->num_rows) {
            $error = "That username already exists<br><br>";
        }
        else {
            $passS = secure($pass);
            queryMysql("INSERT INTO members VALUES('$user', '$passS')");
            die("<h4>Account created</h4>Please Log in.<br><br>");
        } */
        $result = queryMysql("SELECT * FROM members WHERE user='$user'");
        if ($result->num_rows) {
            $error = "That username already exists";
        }
        else {
            $passS = secure($pass);
            queryMysql("INSERT INTO members VALUES('$user', '$passS')");
            $_SESSION['user'] = $user;
            die("<script>window.location = 'members.php?view=$user';</script>");
        }
    }
}

// sign up form
echo <<<_END
        
        <form method="post" action="signup.php"><span class="error">$error</span><br>
            <label class="fieldname">Username</label>
            <input class="withinfo" type="text" maxlength="16" name="user" value='$user' onBlur="checkUser(this)">
            <span id="info">&nbsp;</span><br>
            <label class="fieldname">Password</label>
            <input type="password" maxlength="16" name="pass" value="$pass" id="pass1"><br>
            <label class="fieldname">Conform Password</label>
            <input class="withinfo" type="password" maxlength="16" name="passc" value="$pass2" onkeyup="conformPass(this)"><span id="pass_info">&nbsp;</span><br>
            <input class="submit" type="submit" value="Sign up">
        </form>
        <p> Already have an acount? Please <a href='login.php'>log in</a>.</p>
_END;

include_once('../templates/footer.php');