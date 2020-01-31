"use strict";

/*
 * Function checks the variable, that was sent from PHP (variable 'check'), 
 * if 'check' is false - popup with "user wasn't found",
 * if 'check' is true - popup with "sending a temp password"
 */
function checkEmail() {

    if(check == '0')
        window.alert("404/1 \nPage was found, but User wasn't found");
    else
        window.alert("Temporary password was sent to your email");

}