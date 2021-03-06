<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

ob_start();
session_start();

require "classes/FormSanitizer.php";
require "classes/Database.php";
require "classes/Constant.php";
require "classes/Account.php";
require "classes/User.php";

$account = new \MyApp\Account();
$loadFromUser = new \MyApp\User();

define("WWW_ROOT", "https://my-webrtc-video-chat.herokuapp.com/");

require "functions.php";
