<?php

require_once 'session.php';

if(!isset($_SESSION['student_id'])){

header("Location: auth/login.php");

exit();

}