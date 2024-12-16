<?php
$host = 'localhost';        
$dbname = 'dolphin_crm';    
$username = 'root';         
$password = '';     

try {
    // Create a new PDO instance and connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Uncomment the line below if you want to test the connection
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    // Handle any connection errors
    echo ("Connection failed: " . $e->getMessage());
    exit();
}
?>
