<?php

if(session_status()==PHP_SESSION_NONE){

session_start();

}

if(isset($_SESSION['LAST_ACTIVITY'])){

if(time()-$_SESSION['LAST_ACTIVITY']>SESSION_TIMEOUT){

session_unset();

session_destroy();

}

}

$_SESSION['LAST_ACTIVITY']=time();