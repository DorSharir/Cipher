"use strict";

// All navigation buttons
var menuNav = document.querySelector("#menuNav");
var mainNav = document.querySelector("#mainNav");
var cypherNav = document.querySelector("#cypherNav");
var encryptNav = document.querySelector("#encryptNav");
var decryptNav = document.querySelector("#decryptNav");
var keyNav = document.querySelector("#keyNav");
var aboutNav = document.querySelector("#aboutNav");
var contactNav = document.querySelector("#contactNav");
var exitNav = document.querySelector("#exit");

// Checks if all navigation buttons are opened
var navOpened = false;
// Checks if cypher navigation buttons are opened
var navCyphOpened = false;

// Adding event listener to main menu button
menuNav.addEventListener("click", function() {
    // Setting position of all navigation buttons
    mainNav.style.setProperty('--l', '35%');

    cypherNav.style.setProperty('--l','42.5%');

    encryptNav.style.setProperty('--t','10%');
    encryptNav.style.setProperty('--l','42.5%');

    decryptNav.style.setProperty('--t','10%');
    decryptNav.style.setProperty('--l','42.5%');

    keyNav.style.setProperty('--t','10%');
    keyNav.style.setProperty('--l','42.5%');

    aboutNav.style.setProperty('--l','57.5%');

    contactNav.style.setProperty('--l','65%');

    exitNav.style.setProperty('--l', '72.5%')

    // Checking if main menu was opened. If not - shows all nav buttons
    if(!navOpened) {
        mainNav.style.animation = "showNav .3s forwards ease-in-out";
        cypherNav.style.animation = "showNav .3s forwards ease-in-out .1s";
        encryptNav.style.animation = "showNav .3s forwards ease-in-out .1s";
        decryptNav.style.animation = "showNav .3s forwards ease-in-out .1s";
        keyNav.style.animation = "showNav .3s forwards ease-in-out .1s";
        aboutNav.style.animation = "showNav .3s forwards ease-in-out .2s";
        contactNav.style.animation = "showNav .3s forwards ease-in-out .3s";
        exitNav.style.animation = "showNav .3s forwards ease-in-out .4s";

        navOpened = true;
    }
    // If main menu opened, closes all nav buttons
    else {
        // Setting position of cypther buttons in navigation
        if(navCyphOpened) {
                encryptNav.style.setProperty('--t','18%');
                encryptNav.style.setProperty('--l','38%');

                decryptNav.style.setProperty('--t','20%');
                decryptNav.style.setProperty('--l','42.5%');

                keyNav.style.setProperty('--t','18%');
                keyNav.style.setProperty('--l','47%');
        }
        mainNav.style.animation = "hideNav .3s forwards ease-in-out";
        cypherNav.style.animation = "hideNav .3s forwards ease-in-out";
        encryptNav.style.animation = "hideNav .3s forwards ease-in-out ";
        decryptNav.style.animation = "hideNav .3s forwards ease-in-out ";
        keyNav.style.animation = "hideNav .3s forwards ease-in-out ";
        aboutNav.style.animation = "hideNav .3s forwards ease-in-out";
        contactNav.style.animation = "hideNav .3s forwards ease-in-out";
        exitNav.style.animation = "hideNav .3s forwards ease-in-out";

        navOpened = false;
        navCyphOpened = false;
    }
});

// Adding event listener to nav cypher button
cypherNav.addEventListener("click", function() {
    // Setting position of cypher navigation buttons
    encryptNav.style.setProperty('--t','18%');
    encryptNav.style.setProperty('--l','38%');

    decryptNav.style.setProperty('--t','20%');
    decryptNav.style.setProperty('--l','42.5%');

    keyNav.style.setProperty('--t','18%');
    keyNav.style.setProperty('--l','47%');

    // If cypher nav button wasn't clicked, openes cypher nav buttons
    if(!navCyphOpened) {

        encryptNav.style.animation = "showCyphNav .3s forwards ease-in-out ";
        decryptNav.style.animation = "showCyphNav .3s forwards ease-in-out .1s";
        keyNav.style.animation = "showCyphNav .3s forwards ease-in-out .2s";

        navCyphOpened = true;
    }
    // If cypher nav button was clicked, hides cypher nav buttons
    else {

        encryptNav.style.animation = "hideCyphNav .3s forwards ease-in-out ";
        decryptNav.style.animation = "hideCyphNav .3s forwards ease-in-out ";
        keyNav.style.animation = "hideCyphNav .3s forwards ease-in-out ";

        navCyphOpened = false;
    }
})

var passClicked = false;
var nameClicked= false; 

// Function for showing helping popup, mouseover on buttons
function showWhat(element) {
    if(!nameClicked && !passClicked)
        document.querySelector("#"+element).style.animation = "showWhat .5s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)";
}
// Function for hidding helping popup, mouseout on buttons
function hideWhat(element) {
    document.querySelector("#"+element).style.animation = "hideWhat .1s ";
}
