<?php
require_once('../templates/header.php');
echo "<div class='main'>";

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
            $error = "<span class='error'>Username/Password invalid</span><br><br>";
        }
        else {
            // set up sessions
            $_SESSION['user'] = $user;
            //$_SESSION['pass'] = $passS;
            //die("You are now logged in. Please <a href='members.php?view=$user'>click here</a> to continue.<br><br>");            
            die("<script>window.location = 'members.php?view=$user';</script>");
        }
    }
}
// log in form
echo <<<_END
        <h2> Log In</h2>
        <form method="post" action="login.php">$error<br>
            <span class="fieldname">Username</span>
            <input type="text" maxlength="16" name="user" value="$user"><br>
            <span class="fieldname">Password</span>
            <input type="password" maxlength="16" name="pass" value="$pass"><br>
            <span class='fieldname'>&nbsp;</span>
            <input type="submit" value="Login">
        </form>
        <div>Don't have an account? Please <a href='signup.php'>sign up</a>.</div>
        </div>
    </body>
</html>
_END;
    
        