<?php
session_start();
$config = parse_ini_file('../private/config.ini');
define('DB_hostname', 'localhost');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btnRegister'])) {
        header("Location: ../register.php");
        exit();
    }
}

$connection = mysqli_connect(DB_hostname, $config['username'], $config['password'], $config['dbname']);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $connection->prepare("SELECT * FROM users WHERE board_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        // redirects to dashboard.php
        header("Location: ../dashboard.php");
        // Make sure that code below does not get executed when redirect
        exit();
    }
    else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Board</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="hero">
        <div class="form">
            <h1 class="header">Message Board</h1>
            <form action="login.php" method="POST">
                <div class="input_field">
                    <input type="text" name="username" id="username" placeholder="Username" />
                </div>
                <div class="input_field">
                    <input type="password" name="password" id="password" placeholder="Password" >
                </div>
                <input type="submit" value="Log In" />
                <input type="submit" name="btnRegister" value="Register"/>
            </form>
            <?php 
                if (isset($error_message))
                    echo '<p id="error">' . $error_message . '</p>';
            ?>
        </div>
    </div>
</body>

</html>