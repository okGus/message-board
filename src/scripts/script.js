function checkUsername() {
    var username = document.getElementById("username").value;
    var xhttp = new XMLHttpRequest();
    var params = "user=" + username;

    // Send username to checkUsername.php for availibility in db
    xhttp.open("POST", "../scripts/checkUsername.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = () => {
        if (xhttp.readyState == XMLHttpRequest.DONE && xhttp.status == 200) {
            document.getElementById("error").innerHTML = xhttp.responseText;
        }
    };

    xhttp.send(params);
}
