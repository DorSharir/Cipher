<?php

    require_once("UserClass.php");
    session_start();

    if(!isset($_SESSION['user'])) {
        header('location: home.php');
    }

    # Sending a mail to team
    if(isset($_POST['send_to_team'])) {
        if(!empty($_POST['content_team'])) {
            sendEmail($_POST['content_team'], "cypher_team@gmail.com");
            closePost();
        }
    }

    # Sending a mail to Dor
    if(isset($_POST['send_to_dor'])) {
        if(!empty($_POST['content_dor'])) {
            sendEmail($_POST['content_dor'], "dor900@gmail.com");
            closePost();
        }
    }

    # Sending a mail to Sergey
    if(isset($_POST['send_to_sergey'])) {
        if(!empty($_POST['content_sergey'])) {
            sendEmail($_POST['content_sergey'], "sergeybogdan3105@gmail.com");
            closePost();
        }
    }

    /*
        Function for sending a mail to a specify person (team, Dor, Sergey),
        $str - text in message,
        $to - reciever of message
    */
    function sendEmail($str, $to) {

        $user = new UserClass();            # creating an object
        $user = $_SESSION['user'];          # passing object from session

        # getting parameters of user
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $userEmail = $user->getEmail();

        # defining all fields in message
        $send_to = "".$to;
        $subject = "Mail from 'Contact Us'";
        $mail_content = file_get_contents("contactEmailPattern.eml");

        $mail_content = strtr($mail_content, array("{FNAME}" => $firstName,
                                                    "{LNAME}" => $lastName,
                                                    "{USERMAIL}" => $userEmail,
                                                    "{TEXT}" => $str,
                                                    "{TO}" => $to,
                                                    "{WHEN}" => date("Y-m-d H:i:s")));

        list($head, $body) = preg_split("/\r?\n\r?\n/s", $mail_content, 2);
        mail($send_to, $subject, $body, $head);
    }

    /*
        Function for unsetting a post array,
        need to prevent re-sending a mail with refreshing a page
    */
    function closePost() {
        unset($_POST);
        header("Location: contact.php");
        exit;
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Contact Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/contact.css" />

    
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

    <div id = "cypherTeam">
        <button class = "button" id = "teamBtn">
            <img src = "../Cipher/images/gmail.png" height = "30vh">
                Cypher Team
        </button>
        <form method = "post" id = "teamLetter">
            <textarea rows="6" cols="30" name="content_team" form="teamLetter" class = "field"></textarea>
            <input type="submit" class = "sendBut" name = "send_to_team" value = "Send">
        </form>
        
    </div>


    <div id = "dor">
        <button class = "button" id = "dorBtn">
            <img src = "../Cipher/images/gmail.png" height = "30vh">
                Dor Sharir
        </button>
        <form method = "post" id = "dorLetter">
            <textarea rows="6" cols="30" name="content_dor" form="dorLetter" class = "field"></textarea>
            <input type="submit" class = "sendBut" name = "send_to_dor" value = "Send">
        </form>
    </div>


    <div id = "sergey">
        <button class = "button" id = "sergeyBtn">
            <img src = "../Cipher/images/gmail.png" height = "30vh">
                Sergey Bogdankevich
        </button>
        <form method = "post" id = "sergeyLetter">
            <textarea rows="6" cols="30" name="content_sergey" form="sergeyLetter" class = "field"></textarea>
            <input type="submit" class = "sendBut" name = "send_to_sergey" value = "Send">
        </form>
    </div>

    <div class = "what" id = "hovEnc">Encryption</div>
    <div class = "what" id = "hovDec">Decryption</div>
    <div class = "what" id = "hovKey">Show Encryption Key</div>
    <div class = "what" id = "hovHom">Home</div>
    <div class = "what" id = "hovCyp">Cypher</div>
    <div class = "what" id = "hovAbo">About</div>
    <div class = "what" id = "hovCon">Contact Us</div>
    <div class = "what" id = "hovExit">Exit Acount</div>

    <script src="../Cipher/javascript/main.js"></script>
    <script src="../Cipher/javascript/contact.js"></script>

</body>

</html>