"use strict";

// Defining all button variables
var cypherInfo = document.querySelector('#cypherInfo');
var encryptInfo = document.querySelector('#encryptInfo');
var decryptInfo = document.querySelector('#decryptInfo');
var showKeyInfo = document.querySelector('#showKeyInfo');

// Defining all text variables
var cText = document.querySelector('#cText');
var eText = document.querySelector('#eText');
var dText = document.querySelector('#dText');
var sText = document.querySelector('#sText');

/* 
 *  Adding event listeners to each button, 
 *  that will show it's text respectively and will
 *  hide all other text.
 */
cypherInfo.addEventListener('click', function() {
    eText.style.display = "none";
    eText.style.animation = "hideText .5s forwards linear";
    dText.style.display = "none";
    dText.style.animation = "hideText .5s forwards linear";
    sText.style.display = "none";
    sText.style.animation = "hideText .5s forwards linear";

    cText.style.display = "block";
    cText.style.animation = "showText .5s forwards linear";
})
encryptInfo.addEventListener('click', function() {
    cText.style.display = "none";
    cText.style.animation = "hideText .5s forwards linear";
    dText.style.display = "none";
    dText.style.animation = "hideText .5s forwards linear";
    sText.style.display = "none";
    sText.style.animation = "hideText .5s forwards linear";

    eText.style.display = "block";
    eText.style.animation = "showText .5s forwards linear";
})
decryptInfo.addEventListener('click', function() {
    cText.style.display = "none";
    cText.style.animation = "hideText .5s forwards linear";
    eText.style.display = "none";
    eText.style.animation = "hideText .5s forwards linear";
    sText.style.display = "none";
    sText.style.animation = "hideText .5s forwards linear";

    dText.style.display = "block";
    dText.style.animation = "showText .5s forwards linear";
})
showKeyInfo.addEventListener('click', function() {
    cText.style.display = "none";
    cText.style.animation = "hideText .5s forwards linear";
    eText.style.display = "none";
    eText.style.animation = "hideText .5s forwards linear";
    dText.style.display = "none";
    dText.style.animation = "hideText .5s forwards linear";

    sText.style.display = "block";
    sText.style.animation = "showText .5s forwards linear";
})