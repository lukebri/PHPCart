<?php
define('adminEmail', "your@email.com");
$user = "db_user";
$pass = "password";
$dbname = "db_name";
try {
    $db = new PDO("mysql:host=localhost;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}