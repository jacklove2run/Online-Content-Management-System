<?php

class dbConnection {
    public  $conn;
    private $servername = "localhost";
    private $username = "root";
    private $password = "RKMKJx7EmkjkB2QD";
    private $dbname = "my_project";
    private $hasError = false;

    public function __construct() {
       $this->conn = $this->sqlConn();
    }

    public function runSql($sql) {
        return $this->conn->query($sql);
    }

    public function selectUser($username,$password) {
    $uname = $this->conn->real_escape_string($username);
        return "select *  from t_user where username = '{$uname}' and password = '{$password}'";
    }
    
    public function checkUser($username) {
    $uname = $this->conn->real_escape_string($username);
        return "select count(*) as cnt from t_user where username = '{$uname}'";
    }

    public function uploadFile($user_id, $filetype, $filepath, $filesize, $filename, $realname) {
        $filepath = $this->conn->real_escape_string($filepath);
        $filename = $this->conn->real_escape_string($filename);
        $filetype = $this->conn->real_escape_string($filetype);
        $createtime = time();
        return "insert into t_user_files(id, file_name, file_size, file_path, file_type, create_time, user_id, realname) values (null, '{$filename}', {$filesize}, '{$filepath}', '{$filetype}', {$createtime}, {$user_id}, '{$realname}')";
    }

    public function createDir($user_id, $filename, $filepath) {
        $filename = $this->conn->real_escape_string($filename);
        $filepath = $this->conn->real_escape_string($filepath);
        $createtime = time();
        return "insert into t_user_files(file_name, file_size, file_path, file_type, create_time, user_id, realname) values ('{$filename}', 0, '{$filepath}', 'directory', {$createtime}, {$user_id}, '')";
    }

    public function checkFile($user_id, $filepath, $filename) {
        $filepath = $this->conn->real_escape_string($filepath);
        $filename = $this->conn->real_escape_string($filename);
        return "select count(*) as cnt from t_user_files where user_id={$user_id} and file_path='{$filepath}' and file_name='{$filename}'";
    }

    public function getAllFiles($user_id, $filepath) {
        $filepath = $this->conn->real_escape_string($filepath);
        return "select * from t_user_files where user_id = {$user_id} and file_path = '{$filepath}'";
    }

    public function registerUser($username,$password) {
        $uname = $this->conn->real_escape_string($username);
        $createtime = time(); 
        return "insert into t_user(id,username, password, create_time) values (null, '{$uname}', '{$password}', {$createtime})";
    }

    public function sqlConn() {
        $conn = new mysqli($this->servername,$this->username,$this->password,$this->dbname);
        if ($conn->connect_error) {
            $this->hasError = true;
            die("connection failed: ") . $conn->connect_error;
        } else {
            return $conn;
        }
    }

    //close db connection
    public function closeConn() {
        if ($this->hasError) {
            return;
        }
        $this->conn->close();
    }

}

