
<?php
$host = 'mysql';         // This matches the service name in docker-compose.yml
$port = '3306';          // MySQL default port inside Docker
$db   = 'update';        // Same as MYSQL_DATABASE in docker-compose.yml
$user = 'root';          // Same as MYSQL_ROOT_PASSWORD
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully!";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>





