<?php
    include("common.php");
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $password = $_POST['password']; 
        $handle = fopen($pass_file, 'r') or die('Cannot open password file');
        $real_password = fread($handle, filesize($pass_file));
        fclose($handle);
        if(strcmp($password, $real_password) == 0)
        {
            $_SESSION['login_user'] = TRUE;
            header("location: welcome.php");
        }
        else
        {
            $error = "Login password is invalid";
        }
    }
?>

<html> 
    <head>
        <title>Login Page</title>
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
    <?php
    if(isset($_SESSION['login_user']))
    {
        header("location: welcome.php");
    }
    ?>
    <body bgcolor = "#FFFFFF">
        <div align = "center">
            <div style = "width:40ch; border: solid 1px #333333; " align = "left">
                <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
                    <div style = "margin:2ch">
                        <form action = "" method = "post">
                            <label>Password  :</label><input input style='width:100%;' type = "password" name = "password" class = "box"><br/><br/>
                            <input type = "submit" value = "Submit"><br/>
                        </form>
                    <div style = "color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                </div>
            </div>
        </div>
    </body>
</html>