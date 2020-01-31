"use strict";

/*
 * Adding event listeners to login button and
 * singup button to change the forms
 */
document.querySelector('#logButton').addEventListener('click', function() {
    document.querySelector('#form').style.animation = 'showLoginForm .3s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)';
    document.querySelector('#signUpForm').style.animation = 'hideForm .1s forwards linear ';
    document.querySelector('#loginForm').style.animation = 'showForm .1s forwards linear .1s';
    document.querySelector('#signUpForm').style.visibility = 'hidden';
    document.querySelector('#signUpForm').style.opacity = '0';
});

document.querySelector('#signButton').addEventListener('click', function() {
    document.querySelector('#form').style.animation = 'showSignUpForm .3s forwards cubic-bezier(0.680, -0.550, 0.265, 1.550)';
    document.querySelector('#loginForm').style.animation = 'hideForm .1s forwards linear ';
    document.querySelector('#signUpForm').style.animation = 'showForm .1s forwards linear .1s';

})

