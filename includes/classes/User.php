<?php
class User {

    private $con, $sqlData;
    public function __construct($con, $sqlData) {
        $this->con = $con;
        $this->sqlData = $sqlData;
    }
}
?>