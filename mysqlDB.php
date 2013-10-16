<?php
Class MysqlDB {
    private $settings = array("host" => "localhost",
                              "user" => "root",
                              "password" => "",
                              "db" => "eandroidim");
    
    private $connection;
    public $result;
    
    function __construct() {
        $this->connection = new mysqli($this->settings['host'], $this->settings['user'], $this->settings['password'], $this->settings['db']);
        $this->connection->autocommit(true);
    }
    
    function __destruct() {
        $this->closeResult();
        @$this->connection->close();
    }
    
    function query($queryString) {
        $this->closeResult();
        $this->result = $this->connection->query($queryString);
    }
    
    function insertId() {
        return $this->connection->insert_id;
    }
        
    private function closeResult() {
        if(isset($result)) {
            $result->close();
        }
    }
}
?>