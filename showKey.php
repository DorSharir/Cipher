<?php

    require_once('UserClass.php');
    session_start();

    if(!isset($_SESSION['user'])) {
        header('location: home.php');
    }

    # Saving an output from java
    $output = array();

    # Checking if file was uploaded correctly
    if (!empty($_FILES) && isset($_FILES['loadFile'])) {
        # Using folder 'upload' as a server
        switch ($_FILES['loadFile']["error"]) {
            case UPLOAD_ERR_OK:
                $target = "upload/";
                $target = $target . basename($_FILES['loadFile']['name']);

                if (!move_uploaded_file($_FILES['loadFile']['tmp_name'], $target)) 
                    echo "<div id = 'errorMess'>Sorry, there was a problem uploading your file</div>";
                break;      
        }
    }

    /*
        Code for showing popup with encryption key of file, 
        works only if file was encrypted in normal type
    */
    if(isset($_POST['findKey'])) {
         # In case no file was selected
        if(empty($_FILES['loadFile']['name']))
            echo "<div id = 'errorMess'>Select a file first</div>";
        else {
            $user = new UserClass();                            # creating an object
            $user = $_SESSION['user'];                          # saving an object from user
            $path = '"'.$_FILES['loadFile']['name'].'"';        # saving a path to file
            $typeOfAction = 4;                                  # to show an encryption key
            $userEmail = $user->getEmail();                     # getting user's email
            $fileSize = $_FILES['loadFile']['size'];            # saving a file's size

            # Passing all data to java, in output will be saved any type of error or a key in good case
            exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $userEmail $fileSize", $output);

            # Types of output from java:
            # 0 - session's user is owner of file, creating popup with key 
            # 3 - file type error, like file is not our's or file is after strong decryption
            # 5 - session user is not an owner of the file, popup an error message
            if($output[0] == "0") {
                echo '<div id = "popup" class = "popup">
                        <div id = "showKey">
                    
                            <h3>'.$output[1].'</h3>
                            
                            <p>    
                            <input id = "button" type = "button"  value = "OK" ></p>
                        </div>
                    </div>';
            }
            else if($output[0] == "5") 
                echo '<div id = "errorMess">Sorry, but this file does not belong to you</div>';
            else if($output[0] == "3") 
                echo '<div id = "errorMess">This file is not compatible with this type of action</div>';

            # In the end, deleting file from the server
            if(!empty($_FILES['loadFile']['name'])) 
                unlink("C:/xampp/htdocs/Cipher/upload/".$_FILES['loadFile']['name']);
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Show Key</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/decrypt.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/showKey.css" />
    
</head>
<body>

    <nav>
        <ul>
            <a>
                <div id = "menuNav" ></div>
            </a>
            <a href = "../Cipher/choice.php">
                <div id = "mainNav"
                onmouseover = "showWhat('hovHom')" onmouseout = "hideWhat('hovHom')"></div>
            </a>
            <a>
                <div id = "cypherNav"
                onmouseover = "showWhat('hovCyp')" onmouseout = "hideWhat('hovCyp')"></div>
            </a>
            <a href = "../Cipher/encrypt.php">
                <div id = "encryptNav"
                onmouseover = "showWhat('hovEnc')" onmouseout = "hideWhat('hovEnc')"></div>
            </a>
            <a href = "../Cipher/decrypt.php">
                <div id = "decryptNav"
                onmouseover = "showWhat('hovDec')" onmouseout = "hideWhat('hovDec')"></div>
            </a>
            <a href = "../Cipher/showKey.php">
                <div id = "keyNav"
                onmouseover = "showWhat('hovKey')" onmouseout = "hideWhat('hovKey')"></div>
            </a>
            <a href = "../Cipher/about.html">
                <div id = "aboutNav"
                onmouseover = "showWhat('hovAbo')" onmouseout = "hideWhat('hovAbo')"></div>
            </a>
            <a href = "../Cipher/contact.php">
                <div id = "contactNav"
                onmouseover = "showWhat('hovCon')" onmouseout = "hideWhat('hovCon')"></div>
            </a>
            <a href = "../Cipher/home.php">
                <div id = "exit"
                onmouseover = "showWhat('hovExit')" onmouseout = "hideWhat('hovExit')"></div>
            </a>
        </ul>
    </nav>
    
    <form action = '' method = "post" enctype = 'multipart/form-data'>
        <p>
            <button type = "submit" name = "findKey"
            onmouseover = "showWhat('hovKey')" onmouseout = "hideWhat('hovKey')"></button>
        </p>

        <div id = "dropbox">  
            <div>  
                <input type = "file" id = "fileInput" class = "fileIn" onchange="GetFileInfo()"
                        style="visibility: hidden;" multiple name = "loadFile">
                <label for = 'fileInput' class = "fileIn">
                    <strong>Choose a file</strong>
                </label>
            </div>

        <div id = "info" ></div>

        </div>
    </form>

    <div class = "what" id = "hovEnc">Encryption</div>
    <div class = "what" id = "hovDec">Decryption</div>
    <div class = "what" id = "hovKey">Show Encryption Key</div>
    <div class = "what" id = "hovHom">Home</div>
    <div class = "what" id = "hovCyp">Cypher</div>
    <div class = "what" id = "hovAbo">About</div>
    <div class = "what" id = "hovCon">Contact Us</div>
    <div class = "what" id = "hovExit">Exit Acount</div>

    <script src="../Cipher/javascript/showKey.js"></script>
    <script src="../Cipher/javascript/main.js"></script>
</body>
</html>