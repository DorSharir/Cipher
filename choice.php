<?php

    require_once("UserClass.php");
    session_start();

    if(!isset($_SESSION['user'])) {
        header('location: home.php');
    }

    # Taking object 'user' from session
    $user = new UserClass();
    $user = $_SESSION['user'];

    /*
        Updating password of user in DB
    */
    if(isset($_POST['change_pass'])) {
        
        $db = new ClassDB();
        $db->connect();

        $change_password = $db->getConnection()->prepare("UPDATE users 
                                                         SET user_password = :newPassword 
                                                         WHERE user_email = :email");
        $change_password->execute([':email'=>$user->getEmail(), 
                                  ':newPassword'=>password_hash($_POST['new_password'], PASSWORD_DEFAULT)]);
        

        $result = $db->getConnection()->prepare("SELECT * FROM users 
                                                 WHERE user_email = :email");
        $result->execute([':email'=>$user->getEmail()]);
        $user = $result->fetchObject('UserClass');
        
        $db->disconnect();
    }

    /*
        Updating name of user in DB
    */
    if(isset($_POST['change_name'])) {
        
        $db = new ClassDB();
        $db->connect();

        $change_name = $db->getConnection()->prepare("UPDATE users 
                                                      SET user_first_name = :newFirstName,
                                                          user_last_name = :newLastName  
                                                      WHERE user_email = :email");
        $change_name->execute([':newFirstName'=>$_POST['new_first_name'] == "" ? 
                                                    $user->getFirstName() : $_POST['new_first_name'],
                                ':newLastName'=>$_POST['new_last_name'] == "" ? 
                                                    $user->getLastName() : $_POST['new_last_name'],
                                ':email' => $user->getEmail()]);
        

        $result = $db->getConnection()->prepare("SELECT * FROM users 
                                                 WHERE user_email = :email");
        $result->execute([':email'=>$user->getEmail()]);
        $user = $result->fetchObject('UserClass');
        
        $db->disconnect();
    }

    # Setting session with object 'user'
    $_SESSION['user'] = $user;

    # Greeting sign 
    $greeting = 'Welcome, '.$user->getFirstName().' '.$user->getLastName();
    echo "<h2 id = 'greeting'>".$greeting."</h2>";

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Main</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/choice.css" />
    
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

    <main>
        <div id = 'options'>
            <p>
                <form action = 'encrypt.php' method = "post">
                    <input type = "submit"  name = "encryptFile" id = "encrypt" value = ''
                            onmouseover = "showWhat('hovEnc')" onmouseout = "hideWhat('hovEnc')"> 
                    
                </form>
            </p>
            <p>

                <form action = 'decrypt.php' method = "post">
                    <input type = "submit" name = "decryptFile" id = "decrypt" value = ''
                            onmouseover = "showWhat('hovDec')" onmouseout = "hideWhat('hovDec')">
                </form>
            </p>
            <p>
                <form action = 'showKey.php' method = "post">
                    <input type = "submit" name = "showKey" id = "key" value = ''
                            onmouseover = "showWhat('hovKey')" onmouseout = "hideWhat('hovKey')">
                </form>
            </p>
        </div>

        <button  id = 'settings' 
                onmouseover = "showWhat('hovSet')" onmouseout = "hideWhat('hovSet')"></button>
        
        <p>
            <button id = "passBut" class = "changeOption"
                onmouseover = "showWhat('hovPas')" onmouseout = "hideWhat('hovPas')"></button>
        </p>

        <p>
            <button id = "nameBut" class = "changeOption"
             onmouseover = "showWhat('hovNam')" onmouseout = "hideWhat('hovNam')"></button>
        </p>

        <div id = "changeForm">
            
            <form method = "post" id = "changePassword" >

                <div >
                    <p>
                        <label>New Password</label>
                        <input id = 'new_password' 
                                class = 'input' type = 'password' 
                                name = 'new_password' required>
                    </p>
                    <p>
                        <label>Confirmation</label>
                        <input id = 'conf_new_password' class = 'input'
                                type = 'password' name = 'conf_new_password' 
                                required>
                    </p>
                    <p>
                        <input class = "saveButton" type = "submit" name = "change_pass" 
                                value = "Save" 
                                onclick = "return checkPasswords()">
                    </p>
                </div>

            </form>

            
            <form method = "post" id = "changeName">
                <div >
                    <p>
                        <label>First name</label>
                        <input class = 'input' type = 'text' name = 'new_first_name' 
                                autocomplete = 'off'>
                    </p>
                    <p>
                        <label>Last name</label>
                        <input class = 'input' type = 'text' name = 'new_last_name' 
                                autocomplete = 'off'>
                    </p>
                    <p>
                        <input class = "saveButton" type = "submit" name = "change_name" 
                                value = "Save">
                    </p>
                </div>
            </form>
        </div> 
        
        <div id = "mainField"></div>

        <div class = "what" id = "hovEnc">Encryption</div>
        <div class = "what" id = "hovDec">Decryption</div>
        <div class = "what" id = "hovKey">Show Encryption Key</div>
        <div class = "what" id = "hovSet">Settings</div>
        <div class = "what" id = "hovPas">Change Password</div>
        <div class = "what" id = "hovNam">Change Name</div>
        <div class = "what" id = "hovHom">Home</div>
        <div class = "what" id = "hovCyp">Cypher</div>
        <div class = "what" id = "hovAbo">About</div>
        <div class = "what" id = "hovCon">Contact Us</div>
        <div class = "what" id = "hovExit">Exit Acount</div>

    </main>

    <script src="../Cipher/javascript/choice.js"></script>
    <script src="../Cipher/javascript/main.js"></script>

</body>
</html>