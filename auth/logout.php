<?php

session_start();

session_unset();

$_session = [];

header("location: login.php");
exit;
