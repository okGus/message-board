<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../Login/login.php');
    exit();
}

$username = $_SESSION['username'];
echo "<script>console.log(" . json_encode($username) . ")</script>";

$config = parse_ini_file('../../private/config.ini');
define('DB_hostname', 'localhost');

$connection = mysqli_connect(DB_hostname, $config['username'], $config['password'], $config['dbname']);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT board_username, post.title, post.body, post.date_time FROM users INNER JOIN post ON users.userid = post.userid";
$response = $connection->query($sql);

if ($response->num_rows > 0) {
    while ($row = $response->fetch_assoc()) {
        echo "<script>console.log(" . json_encode($row) . ")</script>";
    }
}

$connection->close();
?>

<h1> Dashboard </h1>