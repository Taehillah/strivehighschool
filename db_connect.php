<?php
// db_connect.php
$host = 'strivehighschool.database.windows.net';
$db = 'strivehighschool';
$user = '43385508@mylife.unisa.ac.za';
$pass = 'Kerileng01';

try {
    $pdo = new PDO("sqlsrv:server=$host;Database=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to Azure SQL Database successfully!";
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
