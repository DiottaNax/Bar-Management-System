<?php

include "../db-config.php";

session_unset();
session_destroy();
header('Location: ../index.php');
