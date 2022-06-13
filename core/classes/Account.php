<?php

namespace MyApp;
use PDO;

class Account {
    public $pdo, $errorArray = array();
    public function __construct() {
        $db = new \MyApp\Database();
        $this->pdo = $db->connect();
    }
    public function register($fn, $ln, $un, $em, $pw) {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmail($em);
        $this->validatePassword($pw);
        if(empty($this->errorArray)) {
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        } else {
            return false;
        }
    }
    public function login($un, $pwd) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE username=:un OR email=:un");
        $stmt->bindParam(":un", $un, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if($stmt->rowCount() != 0) {
            // if(password_verify($pwd, $user->password)) {
                return $user->userID;
            // } else {
            //     array_push($this->errorArray, Constant::$loginFailed);
            //     return false;
            // }
        } else {
            array_push($this->errorArray, Constant::$loginFailed);
            return false;
        }
    }
    private function validateFirstName($fn) {
        if($this->length($fn, 2, 25)) {
            return array_push($this->errorArray, Constant::$firstNameCharacters);
        }
    }
    private function validateLastName($ln) {
        if($this->length($ln, 2, 25)) {
            return array_push($this->errorArray, Constant::$lastNameCharacters);
        }
    }
    private function validateUsername($un) {
        if($this->length($un, 2, 25)) {
            return array_push($this->errorArray, Constant::$UsernameCharacters);
        }

        $stmt = $this->pdo->prepare("SELECT `username` FROM `users` WHERE username=:un");
        $stmt->bindValue(":un", $un, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() != 0) {
            return array_push($this->errorArray, Constant::$UsernameTaken);
        }
    }
    private function validateEmail($em) {
        $stmt = $this->pdo->prepare("SELECT `email` FROM `users` WHERE email=:em");
        $stmt->bindValue(":em", $em, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() != 0) {
            return array_push($this->errorArray, Constant::$EmailTaken);
        }
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            return array_push($this->errorArray, Constant::$EmailInvalid);
        }
    }
    private function validatePassword($pw) {
        if($this->length($pw, 5, 25)) {
            return array_push($this->errorArray, Constant::$PasswordCharacters);
        }
        if(preg_match("/[^A-Za-z0-9]/", $pw)) {
            return array_push($this->errorArray, Constant::$PasswordNotAlphaNumeric);
        }
    }
    private function length($input, $min, $max) {
        if(strlen($input) < $min) {
            return true;
        } else if(strlen($input) > $max) {
            return true;
        }
    }
    private function insertUserDetails($fn, $ln, $un, $em, $pw) {
        $pass_hash = $this->hash($pw);
        $rand = rand(0, 5);
        if($rand === 0) {
            $profilePic = "assets/images/avatar.png";
        } else if($rand === 1) {
            $profilePic = "assets/images/defaultPic.svg";
        } else if($rand === 2) {
            $profilePic = "assets/images/defaultProfilePic.png";
        } else if($rand === 3) {
            $profilePic = "assets/images/other.jpg";
        } else if($rand === 4) {
            $profilePic = "assets/images/profilePic.jpeg";
        } else if($rand === 5) {
            $profilePic = "assets/images/user_profile.png";
        }
        $stmt = $this->pdo->prepare("INSERT INTO users (firstName, lastName, username, email, password, profileImage) Values (:fn, :ln, :un, :em, :pw, :pic)");
        $stmt->bindParam(":fn", $fn, PDO::PARAM_STR);
        $stmt->bindParam(":ln", $ln, PDO::PARAM_STR);
        $stmt->bindParam(":un", $un, PDO::PARAM_STR);
        $stmt->bindParam(":em", $em, PDO::PARAM_STR);
        $stmt->bindParam(":pw", $pass_hash, PDO::PARAM_STR);
        $stmt->bindParam(":pic", $profilePic, PDO::PARAM_STR);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }
    private function hash($pw) {
        return password_hash($pw, PASSWORD_BCRYPT);
    }
    public function getError($errorMessage) {
        if(in_array($errorMessage, $this->errorArray)) {
            return "<span class = 'errorMessage'>$errorMessage</span>";
        }
    }
}
