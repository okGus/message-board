function checkUsername() {
    var username = document.getElementById("username").value;
    var xhttp = new XMLHttpRequest();
    var params = "user=" + username;
    
    xhttp.open("POST", "/checkUsername.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = () => {
        if (xhttp.readyState == XMLHttpRequest.DONE && xhttp.status == 200) {
            document.getElementById("availability").innerHTML = xhttp.responseText;
        }
    };
    xhttp.send(params);
}