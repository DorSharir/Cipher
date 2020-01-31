<?php

    /*Dor Sharir, Sergey Bogdankevich*/ 

    /*
        Creating class for connection with Data Base
    */
    class ClassDB {
        private $host;
        private $db;
        private $charset;
        private $user;
        private $pass;
        private $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS);

        private $connection;

        /*
            Constructor for connection
        */
        public function __construct(string $host = "localhost",
                                string $db = "users", string $charset = "utf8",
                                string $user = 'root', string $pass = ''){
                                    $this->host = $host;
                                    $this->db = $db;
                                    $this->charset = $charset;
                                    $this->user = $user;
                                    $this->pass = $pass;
                                }
        
        /*
            Method for new connection
        */
        public function connect() {
            $dns = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
            $this->connection = new PDO($dns, $this->user, $this->pass, $this->opt);
        }

        /*
            Getter for Connection with data base
        */
        public function getConnection() {
            return $this->connection;
        }

        /*
            Method for Disconnection from data base
        */
        public function disconnect() {
            $this->connect = null;
        }
    }
?>