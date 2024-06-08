<?php
    $mysqli = new mysqli('mysql', 'root', 'ui', 'mysqldb');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    $result = $mysqli->query("SELECT * FROM ui");
    $mysqli->close();
