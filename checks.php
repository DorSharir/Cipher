<?php

    require_once("UserClass.php");
    session_start();
    
    if(!isset($_SESSION['user'])) {
        header('location: home.php');
    }

    /*
        Adding a new user into a data base, 
        setting email, hashed password, first and last name.
        Checking, if user already exists in DB.
    */
    if(isset($_POST['addUser'])) 
    {
        $user = new UserClass();

        $user->setEmail($_POST['email']);
        $user->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));
        $user->setFirstName($_POST['firstName']);
        $user->setLastName($_POST['lastName']);

        # If user already exists, going back to home page
        $_SESSION['check_email'] = TRUE;
        try {
            $user->addUser();
            $_SESSION['user'] = $user;
            header('location: choice.php');
        }
        catch (Exception $e) {
            $_SESSION['check_email'] = FALSE;
            header('location: home.php');
        }
    }

    /*
        Getting user from DB, verification of password
    */
    if(isset($_POST['signIn'])) {

        $db = new ClassDB();
        $db->connect();

        $result = $db->getConnection()->prepare("SELECT * FROM users 
                                                WHERE user_email = :email");
        $result->execute([':email'=>$_POST['signInEmail']]);
        $user = $result->fetchObject('UserClass');

        # if there's no such user in DB, going back to home page
        if($user != NULL) {
            # Verifing the passwords, if doesn't match, going back to home page
            if(password_verify($_POST['signInPassword'], $user->getPassword())){
                $_SESSION['user'] = $user;
                $db->disconnect(); 
                header('location: choice.php');
            }
            else {
                $_SESSION['check_sign_in_password'] = FALSE;
                $db->disconnect(); 
                header('location: home.php');
            }
        }
        else {
           $_SESSION['check_sign_in'] = FALSE;
            $db->disconnect();
            header('location: home.php');
        }
    }

?>