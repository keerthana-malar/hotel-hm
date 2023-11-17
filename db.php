<?php
$host = 'localhost';
$dbname = 'infygfqg_magizham_hotel';
$username = 'infygfqg_hotel-hm';
$password = 'Infy@2021';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>