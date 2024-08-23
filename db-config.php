<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MyManagementSystem";

require_once "db/database.php";

$dbh = new DatabaseHelper($dbname);

session_start();
