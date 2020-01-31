<?php

require_once('UserClass.php');
session_start();

if (!empty($_FILES) && isset($_FILES['loadFile'])) {

    switch ($_FILES['loadFile']["error"]) {
        case UPLOAD_ERR_OK:
            $target = "upload/";
            $target = $target . basename($_FILES['loadFile']['name']);

            if (move_uploaded_file($_FILES['loadFile']['tmp_name'], $target)) {
                
            } else {
                echo "Sorry, there was a problem uploading your file.";
            }
            break; 

    }
}

if(isset($_POST['test2'])) {
        $path = '"'.$_FILES['loadFile']['name'].'"';
        $typeOfAction = 7;
        $fileSize = $_FILES['loadFile']['size'];

        exec("java -jar C:/xampp/htdocs/Cipher/ProjectCipher/out/artifacts/ProjectCipher_jar/ProjectCipher.jar $path $typeOfAction $fileSize", $output);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="styles/styleEncrypt.css" />
    <script src="javascript/encryptJS.js"></script>
</head>
<body>
    <form action = '' method = "post" enctype = 'multipart/form-data'>
        <button type = "submit" name = "test2">TEST</button><br><br>
        <input type = 'file' id = 'fileInput' value = 'Load File' multiple name = 'loadFile'>
        <p>Drop or click</p>
    </form>

</body>
</html>