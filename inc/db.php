<?php
// $user = "root";
// $pass = "pass";
$user = "host_username";
$pass = "password";
$dbname = "dbname";
try {
    $db = new PDO('mysql:host=localhost;dbname='.$dbname, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
 ?>