<?php
    require_once('UserClass.php');
    session_start();

    if(!isset($_SESSION['user'])) {
        header('location: home.php');
    }

    # Array for Java outputs
    $output=array();

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
        Code for normal encryption of friend's file
    */
    if(isset($_POST['userKey'])) {
        $path = '"'.$_SESSION['fileName'].'"';      # saving path to file from session
        $typeOfAction = 3;                          # decryption of friend's file with key 
        $fileSize =$_SESSION['fileSize'];           # saving a file size from session
        $key=$_POST['key'];                         # saving a key

        # Passing all data to java for decryption
        exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $fileSize $key", $output);

        # Checking the output from java, 4 - invalid key
        if($output[0]=='4') {
            echo '<div id = "popup" class = "popup">
                    <div id = "keyForm">

                        <form action = "" method = "post">

                            <a class="close" href="#">&times;</a>
                
                                <h3>
                                    Invalid key!
                                </h3>

                                <p>
                                    <label>Key:</label>
                                    <input class = "input" type = "password" name = "key" required>
                                </p>
                                <p>
                                    <input id = "button" type = "submit" name = "userKey" value = "OK">
                                </p>
                        </form>
                    </div>
                </div>';
        }

        else {
            # saving a path to file taking an output from java
            $file="C:/xampp/htdocs/Cipher/upload/".$output[1];

            # downloading and deleting code
            if (file_exists($file)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Pragma: public');
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header('Content-Length: ' . filesize($file));
                readfile($file);
                unlink($file);
                unlink('C:/xampp/htdocs/Cipher/upload/'.$_SESSION['fileName']);
                exit;
            }
        }
    }

    /*
        Code for strong decryption
    */
    if(isset($_POST['sendStrongKey'])) {

        $path = '"'.$_SESSION['fileName'].'"';          # saving a path of file
        $typeOfAction = 5;                              # strong decryption with key
        $fileSize =$_SESSION['fileSize'];               # saving a size of file
        $key = '"'.$_POST['decStrongKey'].'"';          # saving a key from post array
        
        # Passing all data to java for strong decryption
        exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $fileSize $key", $output);

        # Saving a path to file on server
        $file="C:/xampp/htdocs/Cipher/upload/".$output[1];

        # Downloading and deleting code
        if (file_exists($file)) {
        
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Pragma: public');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink($file);
            unlink("C:/xampp/htdocs/Cipher/upload/".$_SESSION['fileName']);
            exit;
        }
    }

    /*
        Code for decryption, sending data to java and checking the output from java,
        with this output - making specify action
    */
    if(isset($_POST['butDecryption'])) {
        # In case no file was selected
        if(empty($_FILES['loadFile']['name'])) 
            echo "<div id = 'errorMess'>Select a file first</div>";
        else {

            $user = new UserClass();                    # creating aan object
            $user = $_SESSION['user'];                  # passing an object from session
            $path = '"'.$_FILES['loadFile']['name'].'"';    # saving a path to file
            $typeOfAction = 2;                          # decryption
            $userEmail = $user->getEmail();             # getting user's email
            $fileSize = $_FILES['loadFile']['size'];    # saving a size of file
            $_SESSION['fileName'] = $_FILES['loadFile']['name'];    # saving in session a file's name without quots
            $_SESSION['fileSize'] = $fileSize;          # saving in session file size

            # Passing all data to java for decryption, user's email will be sent but will be used only for normal decryption
            exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $userEmail $fileSize", $output);

            # Checking the output from java
            # 2 - friend's file, opening popup for inputing a key
            # 3 - file troubles, like file is not our's (no .seqr extension)
            # 6 - strong decryption, opening a popup for input a key
            # default - downloading and deleting a file from server
            switch($output[0]) {

                case '2':
                    echo '<div id = "popup" class = "popup">
                            <div id = "keyForm">

                                <form action = "" method = "post">

                                    <a class="close" href="#">&times;</a>

                                    <h3>
                                        This file belongs to another user,<br>to decrypt you must enter a key
                                    </h3>

                                    <p>
                                        <label>Key:</label>
                                        <input class = "input" type = "password" name = "key" required>
                                    </p>
                                    <p>
                                        <input id = "button" type = "submit" name = "userKey" value = "OK">
                                    </p>
                                </form>
                            </div>
                        </div>';
                    break;

                case '3':
                    echo "<div id = 'errorMess'>File is not compatible for this action</div>";
                    unlink("C:/xampp/htdocs/Cipher/upload/".$_SESSION['fileName']);
                    break;

                case '6':
                    echo '<div id = "popup" class = "popup">
                            <div id = "keyForm">

                                <form action = "" method = "post">

                                    <a class="close" href="#">&times;</a>

                                    <h3>Enter a key</h3>

                                    <p>
                                        <label>Key:</label>
                                        <input class = "input" type = "password" name = "decStrongKey" required>
                                    </p>
                                    <p>
                                        <input id = "button" type = "submit" name = "sendStrongKey" value = "OK">
                                    </p>
                                </form>
                            </div>
                        </div>';
                    break;

                default: 
                        # Saving a file's name with path
                        $file="C:/xampp/htdocs/Cipher/upload/".$output[1];   

                        if (file_exists($file)) {
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($file).'"');
                            header('Expires: 0');
                            header('Pragma: public');
                            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            unlink($file);
                            # deleting a file from server
                            if(isset($_SESSION['fileName']))
                                unlink("C:/xampp/htdocs/Cipher/upload/".$_SESSION['fileName']);   # file with strong decryption or friend's file
                            else
                                unlink("C:/xampp/htdocs/Cipher/upload/".$_FILES['loadFile']['name']);   # your's file only
                            exit;
                        }
            }
        }
    }
    

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Decryption</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/decrypt.css" />
    
</head>
<body onload="GetFileInfo()">

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
            <button type = "submit" name = "butDecryption" 
                    id = "decryptBut"
                    onmouseover = "showWhat('hovDecFile')" onmouseout = "hideWhat('hovDecFile')"></button>
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
    <div class = "what" id = "hovDecFile">Decrypt File</div>
    <div class = "what" id = "hovHom">Home</div>
    <div class = "what" id = "hovCyp">Cypher</div>
    <div class = "what" id = "hovAbo">About</div>
    <div class = "what" id = "hovCon">Contact Us</div>
    <div class = "what" id = "hovExit">Exit Acount</div>

    <script src="../Cipher/javascript/encryption.js"></script>
    <script src="../Cipher/javascript/decryption.js"></script>
    <script src="../Cipher/javascript/main.js"></script>

</body>
</html>