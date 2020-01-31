<?php

    session_start();

    /*
        Function for showing messages with alert window
    */
    function showPopUp($string) {
        echo '<script type="text/javascript">alert("'.$string.'");</script>';
    }
    
    # Checking if user exists in data base (for sign up)
    if(isset($_SESSION['check_email'])) {
        if($_SESSION['check_email'] == FALSE) {
            showPopUp("This Email already exists...");
            $_SESSION['check_email'] = TRUE;
        }
    }

    # Checking if user exists (for login)
    if(isset($_SESSION['check_sign_in'])) {
        if($_SESSION['check_sign_in'] == FALSE) {
            showPopUp('No Such User');
            $_SESSION['check_sign_in'] = TRUE;
        }
    }

    # Checking user's password
    if(isset($_SESSION['check_sign_in_password'])) {
        if($_SESSION['check_sign_in_password'] == FALSE) {
            showPopUp('Wrong password');
            $_SESSION['check_sign_in_password'] = TRUE;
        }
    }


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/main_style.css" />
    <link rel="stylesheet" type="text/css" href="../Cipher/styles/home.css" />
    
</head>
<body>

    <div id = "backField">
        <div id = 'form'>
            <form action = 'checks.php' method = "post">
                <div id = "signUpForm">
                    <h3>Sign Up</h3>

                    <div class = 'fullName'>
                        <p>
                        <input class = 'input' type = 'text' name = 'firstName'
                                placeholder = 'First Name' autocomplete = 'off' required>
                        </p>
                        <p>
                            <input class = 'input' type = 'text' name = 'lastName'
                                    placeholder = 'Last Name' autocomplete = 'off' required>
                        </p>
                    </div>

                    <div class = 'unique'>
                        <p>
                            <input class = 'input' type = 'email' name = 'email'
                                    placeholder = 'Email' autocomplete = 'off' required>
                        </p>
                        <p>
                            <input class = 'input' type = 'password' name = 'password'
                                    placeholder = 'Password' minlength = '8' required>
                        </p>
                        <p>
                            <input class = "button" type = "submit" name = "addUser" value = "Sign Up">
                        </p>
                    </div>
                </div>
            </form>
            <form action = 'checks.php' method = "post">
                <div id = "loginForm">
                    <h3>Login</h3>

                    <div class = 'uniqueLogin'>
                        <p>
                            <input class = 'input' type = 'email' name = 'signInEmail'
                                    placeholder = 'Email' autocomplete = 'off' required>
                        </p>
                        <p>
                            <input class = 'input' type = 'password' name = 'signInPassword'
                                    placeholder = 'Password' required>
                        </p>
                        <p>
                            <input class = "button" type = "submit" name = "signIn" value = "Login" >
                        </p>
                        <p>
                            <a href="../Cipher/passEmail.php" target = "_blank">Forgot your password?</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <div id = "logInInfo">
            <h2>Have an account?</h2><br>
            <p>
                Press the button <br> 
                below to login.
            </p>
                <button class = "changeForm" id = "logButton">Login</button>
        </div>
        <div id = "signUpInfo">
            <h2>Don't have an account?</h2><br>
            <p>
                Please, press the button <br> 
                below for signing up.
            </p>
                <button class = "changeForm" id = "signButton">Sign Up</button>
        </div>
        
    </div>
    <script src="../Cipher/javascript/home.js"></script>

</body>
</html>