<?php
$host = 'fdb1032.awardspace.net';
$db   = '4715152_products';
$user = '4715152_products';
$pass = ',gYoY^qi1pRd%Rf@';

try {

    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {

    die("Bląd połączenia bazy danych: " . $e->getMessage());
}
?>