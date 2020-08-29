function loadRegisterForm() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("loginForm").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "register_form.html", true);
    xhttp.send();
}

function passwordCheck(password) {
    var upperLetter = false;
    var number = false;
    var symbol = false;

    password = password.trim();

    if (password.length < 8) {
        return false;
    }

    var i;
    var currrent_char;
    var symbols = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '='];
    for (i = 0; i < password.length; i++) {
        currrent_char = password[i];

        // check if current character is an uppercase letter
        if (currrent_char.toLowerCase() != currrent_char.toUpperCase()) {
            if (currrent_char == currrent_char.toUpperCase()) {
                upperLetter = true;
            }
        }

        // check if current character is a number
        if (!isNaN(currrent_char)) {
            number = true;
        }

        if (symbols.includes(currrent_char)) {
            symbol = true;
        }
    }

    return upperLetter && number && symbol;
}

function loadDataUploadForm() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("loginForm").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "data_upload_form.html", true);
    xhttp.send();
}
