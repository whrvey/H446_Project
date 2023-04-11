<?php
# Ref : https://www.w3schools.com/php/php_mysql_connect.asp

$servername = "localhost";
$username = "root";
$password = "";

// Create connection

$db = new mysqli($servername, $username, $password, "harvey_app");

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}
//echo "Connected successfully."
?>