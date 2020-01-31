"use strict";

/**
 * Function that shows the key after encryption
 */
function showKey() {
    alert("Your key: " + key);
} 

/*
 * Definition of regExp, each one has min length 8 chars:
 * 1) lower/upper case letters and digits
 * 2) lower/upper case letters and non-numeric/non-alphabethic
 * 3) lowercase letters, digits and non-numeric/non-alphabethic
 * 4) uppercase letters, digits and non-numeric/non-alphabethic
 */
var regExp1 = new RegExp("(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}");
var regExp2 = new RegExp("(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}");
var regExp3 = new RegExp("(?=.*[a-z])(?=.*[0-9])(?=.*[\W]).{8,}");
var regExp4 = new RegExp("(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}");

/*
 * Function for checking two keys in strong encryption,
 * field of key definition can't be empty,
 * field of key definition must match with minimum one of four regExp,
 * fields of key definition and confirmation key must be matching.
 * In good conditions, popup with key form will be hidden.
 */
function check() {
    // taking two key fields
    var pass_conf= document.getElementById("pass_conf");
    var password = document.getElementById("password");

    // setting title of key fields as empty
    pass_conf.setCustomValidity("");
    password.setCustomValidity("");
    
    if (password.value == "") {
        password.setCustomValidity("Enter a key");
    } else if (!(regExp1.test(password.value) || regExp2.test(password.value) || regExp3.test(password.value) || regExp4.test(password.value))) {
        password.setCustomValidity("Must contain at least 3 of 4 types of characters group(a-z, A-Z, 0-9, !@#$%^&*...)");
    } else if (pass_conf.value != password.value) {
        pass_conf.setCustomValidity("Key must be matching.");
    }
    else {
        pass_conf.setCustomValidity("");
        password.setCustomValidity("");
        document.querySelector('#popup').style.display = "none";
    }
}

// Adding event listener to 'x' in key form to close the form
document.querySelector('.close').addEventListener("click", function (){
    document.querySelector('#popup').style.display = "none";
})

/*
 * Function onload that shows all 
 * data about the uploaded file in div element,
 * shows file's name, size in KBytes and type.
 */
function GetFileInfo () {

    var fileInput = document.getElementById ("fileInput");
    var message = "";
    var file = fileInput.files[0];

    if ('name' in file) 
        message += "<p>File name:<br><i>" + file.name.substring(0, file.name.lastIndexOf('.')) + "</i></p>";
    else 
        message += "<p>File name:<br><i>" + file.fileName + "</i></p>";
    if ('size' in file) 
        message += "<p>File size:<br><i>" + Math.round(file.size / 1024, 1) + " Kbytes</i></p>";
    else 
        message += "<p>File size:<br><i>" + Math.round(file.fileSize / 1024, 1)+ " Kbytes</i></p>";
    if ('type' in file) 
        message += "<p>File type:<br><i>" + file.name.substring(file.name.lastIndexOf('.') + 1) + "</i></p>";

    // putting message with data of file in div element
    var info = document.getElementById ("info");
    info.innerHTML = message;
}