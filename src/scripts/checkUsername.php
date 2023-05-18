<?php 
$config = parse_ini_file('../../private/config.ini');
define('DB_hostname', 'localhost');

$connection = mysqli_connect(DB_hostname, $config['username'], $config['password'], $config['dbname']);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];

    $sql = $connection->prepare("SELECT * FROM users WHERE board_username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();

    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // username exits
        echo "This username is already taken.";
    } else {
        echo "Username available.";
    }

    $sql->close();
}
$connection->close()
?>
