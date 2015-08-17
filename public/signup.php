<?php
$pagetitle = "| Register"; 
require_once('../templates/header.php');

echo "<div class='main'>";

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
            $error = "That username already exists<br><br>";
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
        <h2>Sign Up</h2>
        <form method="post" action="signup.php">$error<br>
            <label class="fieldname">Username</label>
            <input type="text" maxlength="16" name="user" value='$user' onBlur="checkUser(this)">
            <span id="info"></span><br>
            <label class="fieldname">Password</label>
            <input type="password" maxlength="16" name="pass" value="$pass" id="pass1"><br>
            <label class="fieldname">Conform Password</label>
            <input type="password" maxlength="16" name="passc" value="$pass2" onkeyup="conformPass(this)"><span id="pass_info"></span><br>
            <input type="submit" value="Sign up">
        </form>
_END;
?>    
        
        
        </div>
    </body>
</html>