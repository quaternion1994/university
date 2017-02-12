<?php
    require_once("./auth.php");
    if(Auth::Login($_POST["email"], $_POST["password"])){
        Auth::RedirectTo("./index.php");
    }
?>