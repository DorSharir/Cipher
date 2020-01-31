"use strict";

// Checking if setting button was clicked
var settingsCounter = 1;

// Definition of buttons variables
var buttonPass = document.querySelector('#passBut');
var buttonName =  document.querySelector('#nameBut');

// Definition of forms variables
var formPass = document.querySelector('#changePassword');
var formName = document.querySelector('#changeName');

// Checking which setting was clicked
var passClicked = false;
var nameClicked= false; 

/*
 * Adding event listener to button settings, which shows or hides
 * setting's buttons - change name, change password
 */
document.querySelector("#settings").addEventListener("click", function () {
    if(settingsCounter % 2 == 1) {
        buttonPass.style.animation = "showPassButton .3s forwards ease-in-out";
        buttonName.style.animation = "showNameButton .3s forwards ease-in-out";
        settingsCounter++;
    }
    else {
        buttonPass.style.animation = "hidePassButton .3s forwards ease-in-out";
        buttonName.style.animation = "hideNameButton .3s forwards ease-in-out";
        if(passClicked == true || nameClicked == true) {
            formName.style.animation = "downForm .3s forwards ease-in-out";
            formPass.style.animation = "downForm .3s forwards ease-in-out";
            passClicked = false;
            nameClicked = false;
        }
        settingsCounter--;
    }
});

/*
 * Adding event listener to setting's buttons, which show
 * change password form
 */
buttonPass.addEventListener("click", function () {
    if(nameClicked == false) {
        formPass.style.animation = "upForm .8s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
    }
    else {
        formName.style.animation = "downForm .8s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
        formPass.style.animation = "upForm .8s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
    }
    passClicked = true;
});

/*
 * Adding event listener to setting's buttons, which show
 * change name form
 */
buttonName.addEventListener("click", function () {
    if(passClicked == false) {
        formName.style.animation = "upForm .8s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
    }
    else {
        formPass.style.animation = "downForm .8s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
        formName.style.animation = "upForm .8s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
    }
    nameClicked = true;
});

/*
 * Function for checking two inputted passwords
 * in change password form 
 */
function checkPasswords() {

    var field_new_pass = document.getElementById('new_password').value;
    var field_conf_new_pass = document.getElementById('conf_new_password').value;

    if(field_new_pass != field_conf_new_pass) {
        window.alert('Confirmation of password was failed');
        return false;
    }
    else if(field_new_pass != ''){
        window.alert('Success');
    }
}



