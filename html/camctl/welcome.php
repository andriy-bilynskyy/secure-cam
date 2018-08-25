<?php
    include("session.php");

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(exec("/etc/init.d/you-stream_serv status") == "Running")
        {
            shell_exec("sudo service you-stream_serv stop");
        }else{
            shell_exec("sudo service you-stream_serv start");
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
                        if(exec("/etc/init.d/you-stream_serv status") == "Running")
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
