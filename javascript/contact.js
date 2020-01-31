"use strict";

// Definition of array of mail buttons:
// 0 - button's id, 
// 1 - if button was clicked (0 - false, 1 - true),
// 2 - form of button
var teamBtn = [document.querySelector('#teamBtn'), 0, document.querySelector('#teamLetter')];
var dorBtn = [document.querySelector('#dorBtn'), 0, document.querySelector('#dorLetter')];
var sergeyBtn = [document.querySelector('#sergeyBtn'), 0, document.querySelector('#sergeyLetter')];

/*
 * Adding event listener to each mail button
 * for opening the form respectively
 */
teamBtn[0].addEventListener("click", function() {
    if(teamBtn[1] == 0) {
        teamBtn[2].style.animation = "showForm .2s forwards linear";
        teamBtn[1]++;
    }
    else {
        teamBtn[2].style.animation = "hideForm .2s forwards linear";
        teamBtn[1]--;
    }
})
dorBtn[0].addEventListener("click", function() {
    if(dorBtn[1] == 0) {
        dorBtn[2].style.animation = "showForm .2s forwards linear";
        dorBtn[1]++;
    }
    else {
        dorBtn[2].style.animation = "hideForm .2s forwards linear";
        dorBtn[1]--;
    }
})
sergeyBtn[0].addEventListener("click", function() {
    if(sergeyBtn[1] == 0) {
        sergeyBtn[2].style.animation = "showForm .2s forwards linear";
        sergeyBtn[1]++;
    }
    else {
        sergeyBtn[2].style.animation = "hideForm .2s forwards linear";
        sergeyBtn[1]--;
    }
})