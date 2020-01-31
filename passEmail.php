<?php

require_once("UserClass.php");

/*
    Function for generation a random password.
    Returns random sequence of chars in range of 6-8
*/
function generateRandomPassword() {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle( $chars ), 0, rand(6,8));
}

/*
    For button 'Send'
*/
if(isset($_POST['sendNewPassword'])) {
    # Checking if email field is not empty
    if(!empty($_POST['emailForResetPass'])) {

        # Creating connection with DB
        $db = new ClassDB();
        $db->connect();

        # Looking for user in DB with email
        $result = $db->getConnection()->prepare("SELECT * FROM users 
                                                WHERE user_email = :email");
        $result->execute([':email'=>$_POST['emailForResetPass']]);
        $user = $result->fetchObject('UserClass');
        
        # if user was found, sending a parameter to javascript with 'true',
        # sending an email to user with new temporary password,
        # this new password also goes into data base after hashing.
        if($user != NULL){

            $newPassword = generateRandomPassword();

            echo '<script> 
                        var check = '. json_encode("1") .';
                  </script>';

            $reset_password = $db->getConnection()->prepare("UPDATE users 
                                                             SET user_password = :newPassword 
                                                             WHERE user_email = :email");
            $reset_password->execute([':email'=>$_POST['emailForResetPass'], 
                                      ':newPassword'=>password_hash($newPassword, PASSWORD_DEFAULT)]);
            
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();

            $text = "Your new temporary password is: ".$newPassword;
            $send_to = "".$_POST['emailForResetPass'];
            $subject = "Reset Password On Cypher.seqr";
            $mail_content = file_get_contents("passwordEmailPattern.eml");
    
            $mail_content = strtr($mail_content, array("{FNAME}" => $firstName,
                                                        "{LNAME}" => $lastName,
                                                        "{TEXT}" => $text,));
    
            list($head, $body) = preg_split("/\r?\n\r?\n/s", $mail_content, 2);
            mail($send_to, $subject, $body, $head);

        }
        # if user wasn't found, sending a parameter to javascript with 'false'
        else {
            echo '<script> 
                        var check = '. json_encode("0") .';
                  </script>';
        }

        $db->disconnect(); 
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/passEmail.css" />
    
</head>
<body onload = "checkEmail()">

    <div id = "form">
        <form  method = "post">
            <h3>Send new password to</h3>
            <p>
                <input class = 'input' type = 'email' 
                    name = 'emailForResetPass' required
                    id = 'checkEmailField'
                    autocomplete = 'off' placeholder = 'E-mail'>
            </p> 
            <p>
                <input id = "button" type = "submit" name = "sendNewPassword" value = "Send">
            </p>
        </form>
    </div>
    <script src="../Cipher/javascript/passEmail.js"></script>

</body>
</html>