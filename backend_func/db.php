<?php
class sad_db {
    private $sgyp_srvr ;
    private $user_srvr ;
    private $pass_srvr ;
    private $db_name;
    protected function connect() {
        $this->sgyp_srvr = "localhost";
        $this->user_srvr = "root";
        $this->pass_srvr = "";
        $this->db_name = "samgyupsal_db";
        $conn = new mysqli($this->sgyp_srvr,$this->user_srvr,$this->pass_srvr,$this->db_name);
        return $conn;
    }
}