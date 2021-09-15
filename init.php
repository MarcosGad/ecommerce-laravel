<?php

// Error Reporting 

ini_set('display_errors', 'On'); 
error_reporting(E_ALL); 


$sessionUser = ''; 
if(isset($_SESSION['user'])) {

      $sessionUser = $_SESSION['user']; 
}




include'admin/connect.php';
//routes
$tpl='includes/templates/';//template directory
$lang='includes/languages/';//language directory
$func='includes/functions/';//functions directory
$css='layout/css/';//css directory
$js='layout/js/';//js directory

//include the important files
include $func.'functions.php';
include $lang.'english.php';
include $tpl.'header.php';

