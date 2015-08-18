<?php
$pagetitle = " | Profile"; 
$current = "profile";
require_once("../templates/header.php");
if (!$loggedin) die();
echo "<h2 class='title'>Your Profile</h2><div class='display'>";
$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

// check submitted profile information
if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);
    
    if ($result->num_rows) {
        queryMysql("UPDATE profiles SET text='$text' WHERE user='$user'");
    }
    else queryMysql("INSERT INTO profiles VALUES('$user','$text')");
}
else {
    if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $text = stripslashes($row['text']);
    }
    else $text = '';
}

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

// check whether a image was uploaded
if (isset($_FILES['image']['name'])) {
    $saveto = "img/$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;
    switch($_FILES['image']['type']) {
        case "image/gif": $src = imagecreatefromgif($saveto); break;
        case "image/jpeg": // Both regular and progressive jpegs
        case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
        case "image/png": $src = imagecreatefrompng($saveto); break;
        default: $typeok = FALSE; break;
    }
    if ($typeok) {
        // process the image
        list($w, $h) = getimagesize($saveto);
        $max = 100;
        $tw = $w;
        $th = $h;
        
        if ($w > $h && $max < $w)
        {
            $th = $max / $w * $h;
            $tw = $max;
        }
        elseif ($h > $w && $max < $h)
        {
            $tw = $max / $h * $w;
            $th = $max;
        }
        elseif ($max < $w)
        {
            $tw = $th = $max;
        }
        // resize the image
        $temp = imagecreatetruecolor($tw,$th);
        imagecopyresampled($temp, $src, 0, 0, 0, 0, $tw, $th, $w, $h); // image may blurred
        //imageconvolution($temp, array(array(-1,-1,-1),array(-1,16,-1),array(-1,-1,-1)), 8, 0); // sharpen the image
        imagejpeg($temp, $saveto);
        imagedestroy($temp);
        imagedestroy($src);
    }   
}

showProfile($user);
echo "</div>";

// profile form
echo <<<_END
        <div class='display'>
        <form class='left' method="post" action="profile.php" enctype="multipart/form-data">
            <h4>Enter or edit your details and/or upload an image</h4>
            <textarea name="text" cols="50" rows="3">$text</textarea><br>
            Image: <input type="file" name="image" size="14">
            <input class="submit" type="submit" value="Save Profile">
        </form>
        </div>
_END;
include_once('../templates/footer.php');