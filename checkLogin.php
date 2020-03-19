<?php

require_once("./constValue.php");

class checkLogin {
    public $user_id = 0;
    private $checksum;
    public $loginStatus;
    public function __construct() {
        if (!isset($_COOKIE['id'])) {
            $this->loginStatus = false;
            return;
        }
        
        $this->checksum = $_COOKIE['checksum'];
        $password = $_COOKIE['password'];
        $this->user_id = $_COOKIE['id'];
        $this->loginStatus = $password && $this->user_id && $this->checkSum();
    }

    private function checkSum() {
        return $this->checksum === md5($this->user_id.COOKIE_CHECKSUM_STRING.$this->user_id);
    }

}