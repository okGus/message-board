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

    // Add new user
    // Hash password before inserting
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (board_username, email, password) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // redirect
        $stmt->close();
        $connection->close();
        $_SESSION['username'] = $username;
        header("Location: ../dashboard/dashboard.php");
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
    <link rel="stylesheet" href="../css/login-register.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Open+Sans&display=swap">
</head>

<body>
    <div class="main">
        <div class="form-container">
            <h1 class="form-header">Register</h1>
            <form action="register.php" method="POST">
                <div class="input-field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Username" required onchange="checkUsername()" />
                </div>
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="Email" required />
                </div>
                <div class="input-field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required />
                </div>
                <input type="submit" value="Create Account" />
                <p id="error"></p>
            </form>
        </div>
    </div>
    <script src="../scripts/script.js"></script>
</body>

</html>