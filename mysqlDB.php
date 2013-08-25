<?php
Class MysqlDB {
    private $settings = array("host" => "localhost",
                              "user" => "root",
                              "password" => "",
                              "db" => "eandroidim");
    
    private $connection;
    public $result;
    
    function __construct() {
        //echo "connecting to db<br>";
        $this->connection = new mysqli($this->settings['host'], $this->settings['user'], $this->settings['password'], $this->settings['db']);
        $this->connection->autocommit(true);
    }
    
    function __destruct() {
        //echo "<br>destruct object<br>";
        $this->closeResult();
        @$this->connection->close();
    }
    
    function query($queryString) {
        //echo "do query<br>";
        $this->closeResult();
        $this->result = $this->connection->query($queryString);
    }
        
    private function closeResult() {
        if(isset($result)) {
            $result->close();
        }
    }
}
?>