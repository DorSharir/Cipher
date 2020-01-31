<?php

    require_once("ClassDB.php");

     /*
        Class of user
    */
    class UserClass {

        private $user_email;            # user's email
        private $user_password;         # user's password
        private $user_first_name;       # user's first name
        private $user_last_name;        # user's lasst name

        # Setter/Getter for user's email
        public function setEmail($email) {
            $this->user_email = $email;
        }
        public function getEmail() {
            return $this->user_email;
        }

        # Setter/Getter for user's password
        public function setPassword($password) {
            $this->user_password = $password;
        }
        public function getPassword() {
            return $this->user_password;
        }

        # Setter/Getter for user's first name
        public function setFirstName($first_name) {
            $this->user_first_name = $first_name;
        }
        public function getFirstName() {
            return $this->user_first_name;
        }

        # Setter/Getter for user's last name
        public function setLastName($last_name) {
            $this->user_last_name = $last_name;
        }
        public function getLastName() {
            return $this->user_last_name;
        }

        /*
            Method for adding user into a data base
        */
        public function addUser() {
            $db = new ClassDB();
            $db->connect();

            $result = $db->getConnection()->prepare('INSERT INTO users (user_email,
                                                                        user_password,
                                                                        user_first_name,
                                                                        user_last_name) 
                                                    VALUES (:email,
                                                            :pass,
                                                            :firstName,
                                                            :lastName)');

            $result->bindParam(':email', $this->user_email);        
            $result->bindParam(':pass', $this->user_password); 
            $result->bindParam(':firstName', $this->user_first_name);
            $result->bindParam(':lastName', $this->user_last_name);
            
            $result->execute();
            $db->disconnect();
        }
    }

?>