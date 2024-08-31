<?php
    class DB {
        private $host = 'localhost';
        private $username = 'root';
        private $password = '';
        private $database = '';
        private $port = 3306;
        private $inTransaction = false;
        private $rowCount;
        private $db;

        /**
         * Undocumented function
         *
         * @param String|null $host
         * @param String|null $username
         * @param String|null $password
         * @param String|null $database
         */
        public function __construct(String $host = null,String $username = null,String $password = null,String $database = null, int $port = null) {
            
            $this->host = DB_HOST;
            $this->username = DB_USER;
            $this->password = DB_PASSWORD;
            $this->database = DB_NAME;
            $this->port = DB_PORT;

            if(!empty($host)) { $this->host = $host; }
            if(!empty($username)) { $this->username = $username; }
            if(!empty($password)) { $this->password = $password; }
            if(!empty($database)) { $this->database = $database; }
            if(!empty($port)) { $this->port = $port; }

            try {
                $connectOptions = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
                // $connectOptions = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                $this->db = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';port='.$this->port,$this->username,$this->password, $connectOptions);
            } catch(PDOException $e) {
                die($e->getMessage());
                header('Location: ./error.php');
                die();
            }
        }

        public function fetch($sql, $data = array()) {
            $req = $this->db->prepare($sql);
            $req->execute($data);
            $this->rowCount = $req->rowCount();

            // return $req->fetch(PDO::FETCH_OBJ);
            return $req->fetch(PDO::FETCH_ASSOC);
        }

        public function fetchAll($sql, $data = array()) {
            $req = $this->db->prepare($sql);
            $req->execute($data);
            $this->rowCount = $req->rowCount();

            // return $req->fetchAll(PDO::FETCH_OBJ);
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }

        public function request($sql, $data = array()) {
            $req = $this->db->prepare($sql);
            $success = $req->execute($data);
            $this->rowCount = $req->rowCount();

            if ($success) { return $this->db->lastInsertId(); } 
            else { return false; }
        }

        public function getLastInsertedID() { return $this->db->lastInsertId(); }

        public function beginTransaction() {
            if (!$this->inTransaction) {
                $this->db->beginTransaction();
                $this->inTransaction = true;
            }
        }

        public function commit() {
            if ($this->inTransaction) {
                $this->db->commit();
                $this->inTransaction = false;
            }
        }

        public function rollback() {
            if ($this->inTransaction) {
                $this->db->rollBack();
                $this->inTransaction = false;
            }
        }

        public function rowCount() { return $this->rowCount; }
    }
?>