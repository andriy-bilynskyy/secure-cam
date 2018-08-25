<?php
    include("session.php");

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(file_exists($on_file))
        {
            unlink($on_file);
        }else{
            $handle = fopen($on_file, 'w');
            fwrite($handle, '<h1>Привет, мир!</h1>');
            fclose($handle);
        }
    }
?>

<html>
    <head>
        <title>Welcome</title>
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
            <div style = "width:40ch; border: solid 1px #333333; " align = "left">
                <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Welcome</b></div>
                <div style = "margin:2ch">
                    <?php
                        if(file_exists($on_file))
                        {
                            echo "<font color='green'>Camera alarm is active</font>";
                            $security = "turn off";
                        }else{
                            echo "<font color='red'>Camera alarm is inactive</font>";
                            $security = "turn on";
                        }
                    ?>
                    <br/><br/>
                    <form action = "" method = "post">
                        <input type = "submit" value = "<?php echo $security; ?>"><br/>
                    </form>
                    <a href = "chpass.php">Change Password</a><br/><br/>
                    <a href = "logout.php">Sign Out</a><br/>
                </div>
            </div>
        </div>
    </body>
</html>
