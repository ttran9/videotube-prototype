<?php
class Account {

    private $con;
    private $errorArray = array();

    public function __construct($con) {
        $this->con = $con;
    }

    public function register($firstName, $lastName, $username, $email, $email2, $password, $password2) {
        $this->validFirstName($firstName);
        $this->validLastName($lastName);
        $this->validUsername($username);
    }

    private function validFirstName($firstName) {
        if(strlen($firstName) < 2 || strlen($firstName) > 25) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }

    private function validLastName($lastName) {
        // can do other validations if necessary....
        if(strlen($lastName) < 2 || strlen($lastName) > 25) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }

    private function validUsername($username) {
        // can do other validations if necessary....
        if(strlen($username) < 5 || strlen($username) > 25) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return ;
        }
        $query = $this->con->prepare("SELECT username FROM users WHERE username=:username");
        $query->bindParam(":username", $username);
        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }

    }

    public function getError($error) {
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

}
?>