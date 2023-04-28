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
                    <input type="text" name="username" id="username" placeholder="Username" required="required" />
                </div>
                <div class="input_field">
                    <input type="password" name="password" id="password" placeholder="Password" required="required" />
                </div>
                <input type="submit" value="Log In" />
            </form>
        </div>
    </div>
</body>
</html>