<?php
    class Auth {
        public static function Login($username, $password) {
            if(empty($username))
            {
                return false;
            }
            if(empty($password))
            {
                return false;
            }
            if(!Auth::CheckLoginInDB($username,$password))
            {
                return false;
            }
            session_start();
            $_SESSION["username"] = $username;
            return true;
        }
        public static function IsLogedin()
        {
            session_start();
            if(empty($_SESSION["username"]))
            {
                return false;
            }
            return true;
        }
        public static function RedirectTo($url)
        {
            header('Location: '.$url);
        }
        public static function CheckLoginInDB($username,$password)
        {
            return true;
        }
    }
?>