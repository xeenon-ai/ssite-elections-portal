<?php

require_once __DIR__.'/config.php';

$host="localhost";
$dbname="ssite_elections";
$user="root";
$pass="";

try{

$pdo=new PDO(

"mysql:host=$host;dbname=$dbname;charset=utf8mb4",

$user,

$pass

);

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

}

catch(PDOException $e){

die("Database Error : ".$e->getMessage());

}