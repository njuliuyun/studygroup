<?php
$current='login';
require_once('../templates/header.php');

$error = $user = $pass = '';

if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    if ($user == '' || $pass == '') {
       $error = 'Not all fields were entered.';
    }
    else {
        $passS = secure($pass);
        $result = queryMysql("SELECT user, pass FROM members WHERE user='$user' AND pass='$passS'");
        if ($result->num_rows == 0) {
            $error = "<span class='error'>Username/Password invalid</span>";
        }
        else {
            // set up sessions
            $_SESSION['user'] = $user;
            //$_SESSION['pass'] = $passS;
            //die("You are now logged in. Please <a href='members.php?view=$user'>click here</a> to continue.<br><br>");            
            die("<script>window.location = 'home.php';</script>");
        }
    }
}
// log in form
echo <<<_END
        <form method="post" action="login.php"><span class="error">$error</span><br>
            <label class="fieldname">Username</label><br>
            <input type="text" maxlength="16" name="user" value="$user"/><br>
            <label class="fieldname">Password</label><br>
            <input type="password" maxlength="16" name="pass" value="$pass"/><br>
            <input class='submit' type="submit" value="Log In"/>
        </form>
        <p>Don't have an account? Please <a href='signup.php'>sign up</a>.</p>
        </div>    
_END;
include_once('../templates/footer.php');   
        