<?php

if (!isset($_GET['opt']))
    $_GET['opt'] = "tables";

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="./js/Navbar.js"></script> <!-- Include to make the navbar work properly -->
</head>

<body>
    <?php
    include_once "./components/side-menu.php";
    include_once "./components/navbar.php";
    ?>
</body>

</html>