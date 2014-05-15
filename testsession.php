<?php
session_start();
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
echo 'destroy session:<a href="logout.php">DESTROYME</a>';

 ?>