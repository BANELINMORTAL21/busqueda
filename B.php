<?php
$server = 'localhost';
$username = 'root';
$password= '';
$database = 'database-login';

try {
    $conn = mysqli_connect($server, $username, $password,$database);
    
} catch (PDOException $e) {
    die('Connected failed:'.$e->getMessage());
}

// try {
//     $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password );
    
// } catch (PDOException $e) {
//     die('Connected failed:'.$e->getMessage());
// }
?>
