# Message Board
Using MySQL
```MySQL
CREATE TABLE users (
    userid INT PRIMARY KEY AUTO_INCREMENT,
    password CHAR(60) NOT NULL,
    email CHAR(255) NOT NULL,
    board_username CHAR(255) NOT NULL,
    UNIQUE(board_username)
); 

CREATE TABLE post (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    body VARCHAR(5000),
    userid INT,
    date_time TIMESTAMP NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(userid)
);

CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userid INT,
    body VARCHAR(5000),
    postid INT,
    date_time TIMESTAMP NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(userid),
    FOREIGN KEY (postid) REFERENCES post(id)
);
```

After cloning\
Create a private folder in root folder\
In folder create config.ini\
Inside config.ini have your db credentials
```
[database]
username = ""
password = ""
dbname = ""
```

To run locally
```bash
php -S localhost:9999
```
or what ever other port you want then go to\
localhost:9999/src/Login/login.php