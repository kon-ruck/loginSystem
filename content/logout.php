<?php
session_start();
setcookie(session_name(), '', time()-4200);
$_SESSION = array();
session_destroy();
header('Location: /');