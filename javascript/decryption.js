"use strict";

/*
 * Adding event listener to 'x' in key popup form to close the form 
 */
document.querySelector('.close').addEventListener("click", function (){
    document.querySelector('#popup').style.display = "none";
})

/*
 * Adding event listener to confirmation button
 * in popup key form, that hides the form
 */
document.querySelector('#button').addEventListener("click", function() {
    if(document.querySelector('.input').value != '')
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