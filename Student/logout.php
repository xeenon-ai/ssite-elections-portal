<?php

require_once "../config/config.php";
require_once "../includes/session.php";

session_unset();
session_destroy();

header("Location: ../index.php");
exit();