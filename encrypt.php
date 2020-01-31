<?php
    require_once('UserClass.php');
    session_start();
 
    if(!isset($_SESSION['user'])) {
        header('location: home.php');
    }

    # Array for Java outputs
    $output= array(); 
 
    # Checking for correctness of uploading file
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
        Code for normal encryption, passing the data to java,
        downloading the file in the end.
    */
    if(isset($_POST['normalCipher'])) {
        # In case no file was selected
        if(empty($_FILES['loadFile']['name'])) 
            echo "<div id = 'errorMess'>Select a file first</div>";
        else {
 
            $user = new UserClass();            # creating object
            $user = $_SESSION['user'];          # passing object from session
            $path = '"'.$_FILES['loadFile']['name'].'"';    # saving path with name of the file
            $typeOfAction = 0;                  # normal encryption
            $userEmail = $user->getEmail();     # getting email of user
            $fileSize = $_FILES['loadFile']['size'];    # saving the size of the file

            # Passing all data to java for encryption
            exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $userEmail $fileSize", $output);
            
            # Saving path with name of the file with .seqr extension
            $temp = pathinfo($_FILES['loadFile']['name']);
            $file=$temp['filename'] . ".seqr";
            $file="C:/xampp/htdocs/Cipher/upload/".$file;
             
            # Code for downloading and deleting the file from server
            if (file_exists($file)) {
                header('Content-Type: "application/octet-stream"');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Pragma: public');
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header('Content-Length: ' . filesize($file));
                ob_clean();
                readfile($file);
                unlink($file);
                unlink("C:/xampp/htdocs/Cipher/upload/".$_FILES['loadFile']['name']);
                exit("<script>alert('Your file has been already encrypted. Choose another file');</script>");
            }  
        }
    }   
 
    /*
        Code for opening a 'key pop up', if strong encryption 
        was chosen.
    */
    if(isset($_POST['strongCipher'])) {
        # In ncase no file was selected
        if(empty($_FILES['loadFile']['name'])) 
            echo "<div id = 'errorMess'>Select a file first</div>";
        else {   
            # Saving data about the file in session array
            $_SESSION['fileName'] = $_FILES['loadFile']['name'];
            $_SESSION['fileSize'] = $_FILES['loadFile']['size'];
 
            # showing pop up for key 
            echo '<div id = "popup" class = "popup">
                    <div id = "keyForm">
     
                        <form action = "" method = "post">
             
                            <a class="close" href="">&times;</a>
             
                            <h3>
                                Choose a key, but beware there will be no way to restore it!
                            </h3>
             
                            <p>
                                <label>Key:</label>
                                <input class = "input" type = "password" name = "key"
                                        id = "password" required>
                            </p>
                            <p>
                                <label>Confirm key:</label>
                                <input class = "input" type = "password" name = "confKey"
                                        id = "pass_conf" required>
                            </p>
                            <p>
                                <input id = "button" type = "submit" name = "strongKey"
                                        value = "OK" onclick = "check()">
                            </p>
                        </form>
                    </div>
                </div>';
        }
    }
 
     
    /*
        Code for strong encryption, using a key, passing the data to java,
        downloading the file in the end.
    */
    if(isset($_POST['strongKey'])) {
 

        $user = new UserClass();                # creating an object
        $user = $_SESSION['user'];              # passing object from session

        $path = '"'.$_SESSION['fileName'].'"';      # saving a path with name from session
        $typeOfAction = 1;                      # strong encryption
        $strongKey ='"'.$_POST['key'].'"';      # saving an inputted key
        $fileSize = $_SESSION['fileSize'];      # saving the size of the file

        # Passing all data to java for strong encryption
        exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $strongKey $fileSize", $output);

        $temp = pathinfo($_SESSION['fileName']);
        $file=$temp['filename'] . ".seqr";
        $file="C:/xampp/htdocs/Cipher/upload/".$file;

        # Code for downloading and deleting the file from server
        if (file_exists($file)) {
            header('Content-Type: "application/octet-stream"');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Pragma: public');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Length: ' . filesize($file));
            ob_clean();
            readfile($file);
            unlink($file);
            unlink("C:/xampp/htdocs/Cipher/upload/".$_SESSION['fileName']);
            exit;
        }  
    }
 
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Encryption</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/encrypt.css" />
     
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
 
        <div id = "actionButtons">
            <p>
                <button type = "submit" name = "normalCipher"
                        id = "normalButton"
                        onmouseover = "showWhat('hovNor')" onmouseout = "hideWhat('hovNor')"></button>
            </p>
            <p>
                <button type = "submit" name = "strongCipher"
                        id = "strongButton"
                        onmouseover = "showWhat('hovStr')" onmouseout = "hideWhat('hovStr')"></button>
            </p>
        </div>
 
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
    <div class = "what" id = "hovNor">Normal Encryption</div>
    <div class = "what" id = "hovStr">Strong Encryption</div>
    <div class = "what" id = "hovHom">Home</div>
    <div class = "what" id = "hovCyp">Cypher</div>
    <div class = "what" id = "hovAbo">About</div>
    <div class = "what" id = "hovCon">Contact Us</div>
    <div class = "what" id = "hovExit">Exit Acount</div>

    <script src="../Cipher/javascript/encryption.js"></script>
    <script src="../Cipher/javascript/main.js"></script>
 
</body>
 
</html>