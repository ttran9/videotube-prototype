<?php
class Account {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function register($firstName, $lastName, $userName, $email, $email2, $password, $password2) {

    }

}
?>