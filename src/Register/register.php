<?php 
session_start();

$config = parse_ini_file('../../private/config.ini');
define('DB_hostname', 'localhost');

$connection = mysqli_connect(DB_hostname, $config['username'], $config['password'], $config['dbname']);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // add new user
    // hash password before inserting
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (board_username, email, password) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // redirect
        $stmt->close();
        $connection->close();
        $_SESSION['username'] = $username;
        header("Location: ../Dashboard/dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Open+Sans&display=swap">
</head>

<body>
    <div class="hero">
        <div class = "register-container">
            <h1 class="register-header">Register</h1>
            <form action="register.php" method="POST">
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Username" onchange="checkUsername()"/>
                </div>
                <p id="availability"></p>
                <div class="input-field">
                    <input type="text" name="email" id="email" placeholder="Email" />
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="password" placeholder="Password" />
                </div>
                <input type="submit" value="Create Account" />
            </form>
        </div>
    </div>
    <script src="../script.js"></script>
</body>

</html>