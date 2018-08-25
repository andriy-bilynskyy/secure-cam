<?php
    include("common.php");
    session_start();
    if($_SESSION['login_user'] != TRUE)
    {
        if(session_destroy())
        {
            header("Location: index.php");
        }
    }
?>
