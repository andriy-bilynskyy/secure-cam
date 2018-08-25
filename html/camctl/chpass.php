<?php
    include("session.php");

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $old_password  = $_POST['oldpass'];
        $new_password  = $_POST['newpass'];
        $conf_password = $_POST['confpass'];
        
        $handle = fopen($pass_file, 'r') or die('Cannot open password file');
        $real_password = fread($handle, filesize($pass_file));
        fclose($handle);
        if(strcmp($old_password, $real_password) == 0)
        {
            if(strcmp($new_password, $conf_password) == 0)
            {
                if(strlen($new_password) >= 6)
                {
                    $handle = fopen($pass_file, 'w') or die('Cannot create password file');
                    fwrite($handle, $new_password);
                    fclose($handle);
                    $error = "Password changed";
                }
                else
                {
                    $error = "New password length is less than 6";
                }
            }
            else
            {
                $error = "New passwords are differ";
            }
        }
        else
        {
            $error = "Login password is invalid";
        }
    }
?>

<html>
    <head>
        <title>Change Password</title>
        <link rel="icon" href="images/icon.ico">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <?php
        if(isMobile())
        {
        ?>
        <link rel="stylesheet" type="text/css" href="css/mobile.css">
        <?php
        }else{
        ?>
        <link rel="stylesheet" type="text/css" href="css/desktop.css">
        <?php
        }
        ?>
    </head>
    <body bgcolor = "#FFFFFF">
        <div align = "center">
            <div style = "width:29ch; border: solid 1px #333333; " align = "left">
                <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Change password</b></div>
                    <div style = "margin:2ch">
                        <form action = "" method = "post">
                            <label>Old password     :</label><input style='width:100%;' type = "password" name = "oldpass"  class = "box" value = <?php echo $act_pass; ?> ><br/><br/>
                            <label>New password     :</label><input style='width:100%;' type = "password" name = "newpass"  class = "box" value = <?php echo $new_pass; ?> ><br/><br/>
                            <label>Confirm password :</label><input style='width:100%;' type = "password" name = "confpass" class = "box" value = <?php echo $conf_pass; ?> ><br/><br/>
                            <input type = "submit" value = "Submit"><br/>
                        </form>
                    <div style = "color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                </div>
            </div>
            <div style = "padding:3px;"><a href = "welcome.php">Back</a></div>
        </div>
    </body>
</html>
