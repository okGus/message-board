<?php

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../Login/login.php');
    exit();
}

$username = $_SESSION['username'];

$config = parse_ini_file('../../private/config.ini');
define('DB_hostname', 'localhost');

$connection = mysqli_connect(DB_hostname, $config['username'], $config['password'], $config['dbname']);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $messageText = $_POST['messageText'] ?? '';
    $messageTitle = $_POST['title'] ?? '';
    $dt = date('Y-m-d H:i:s');

    # Get username id to insert post based on that user id
    $stmt = $connection->prepare("SELECT * FROM users WHERE board_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userid = $user['userid'];

    $stmt->close();

    # Insert 
    $sql_in = "INSERT INTO post (title, body, userid, date_time) VALUES (?, ?, ?, ?)";
    $stmt_in = $connection->prepare($sql_in);
    $stmt_in->bind_param("ssis", $messageTitle, $messageText, $userid, $dt);
    $stmt_in->execute();
    $stmt_in->close();

    # Get post based on id
    # Ordered by date, newest one first
    $sql = "SELECT board_username, post.id, post.title, post.body, post.date_time FROM users INNER JOIN post ON users.userid = post.userid ORDER BY post.date_time DESC LIMIT 5";
    $result = mysqli_query($connection, $sql);

} else {
    # Get post based on id
    $sql = "SELECT board_username, post.id, post.title, post.body, post.date_time FROM users INNER JOIN post ON users.userid = post.userid ORDER BY post.date_time DESC LIMIT 5";
    $result = mysqli_query($connection, $sql);
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
    <div class="main">
        <div class="header">
            <div class="header-title"><h2>Message Board</h2></div>
            <div class="header-username"><img src="../../images/avatar.svg" /><h3><?php echo $username; ?></h3></div>
        </div>
        <div class="dashboard-header"><h1><?php echo $username . "'s Dashboard" ; ?></h1></div>
        <div class="form-container">
            <form action="dashboard.php" method="POST">
                <input type="text" id="title" name="title" placeholder="Title" required />
                <textarea id="messageText" name="messageText" placeholder="Start typing..." required></textarea>
                <input type="submit" value="Post" />
            </form>
        </div>
        <div class="content">
            <?php
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result)) { ?>
                        <div class="post">
                            <div class="post-header">
                                <span class="post-title"><?php echo $row['title'] ?></span>
                                <span class="post-username"><?php echo $row['board_username'] ?></span>
                                <span class="post-time"><?php echo $row['date_time'] ?></span>
                            </div>
                            <p><?php echo $row['body'] ?></p>
                            <div class="post-footer">
                                <button class="commment-button" id="post-id-<?php echo $row['id'] ?>">
                                    <span class="button-content"><img src="../../images/down-arrow.svg" id="button-image"/></span>
                                </button>
                            </div>
                            <div class="comment-section" id="comment-section-<?php echo $row['id'] ?>">
                                <div class="comment-form-container">
                                    <form action="dashboard.php" method="POST">
                                        <textarea id="commentText" name="commentText" placeholder="Add a comment..." required></textarea>
                                        <input type="submit" value="Comment" />
                                    </form>
                                </div>
                                <div class="comment">

                                </div>
                            </div>
                        </div>
                <?php }
                }
            ?>
        </div>
    </div>

    <script>
        // This script is for opening and closing the comment section under each post

        // Get all buttons
        const buttons = document.getElementsByTagName("button");
            
        // This function gets the Id of a post by using the comment button
        // Once the post Id is extracted, the corresponding comment section is toggled to active
        const handleButtonPressed = e => {
            // Get post Id
            var buttonId = e.target.id;

            // Get the Id number from the end of the Id
            var postId = buttonId.match(/\d+$/)[0];

            // Get the corresponding comment section
            var commentSection = document.getElementById("comment-section-" + postId);

            if (commentSection) {
                // Comment section is now active
                commentSection.classList.toggle("active");

                // Post Id
                console.log(postId);

                // Change the button image for displaying the comment section
                var image = document.querySelector("#button-image");
                if (image.src.endsWith("down-arrow.svg")) {
                    image.src = "../../images/up-arrow.svg";
                } else {
                    image.src = "../../images/down-arrow.svg";
                }
            } else {
                console.log("Comment section with Id '" + postId + "' was not found.");
            }
        }

        // Add the function to each button when it is clicked
        for (let button of buttons) {
            button.addEventListener("click", handleButtonPressed);
        }
    </script>
    
</body>
</html>