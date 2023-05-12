<?php

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Open+Sans&display=swap">
</head>
<body>
    <div class="hero">
        <div class="header">
            <div class="header-title">Message Board</div>
            <div class="header-username"><img src="../../images/avatar.png" /><?php echo "username"; ?></div>
        </div>
        <div class="dashboard-header"><h1>Your Dashboard</h1></div>
        <div class="form-container">
            <form method="POST" action="">
                <textarea id="messageText" name="messageText" placeholder="Start typing..." required></textarea>
                <input type="submit" value="Post" />
            </form>
        </div>
        <div class="content">
            <span>HELLO</span>
        </div>
    </div>

    <script>
        const textarea = document.querySelector("textarea");
        textarea.addEventListener("keyup", e => {
            textarea.style.height = "64px";
            let height = e.target.scrollHeight;
            textarea.style.height = `${height}px`;
        })
    </script>
</body>
</html>