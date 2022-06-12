<?php

namespace MyApp;
use PDO;

class User {
    public $pdo, $sessionID, $userID;
    public function __construct() {
        $db = new \MyApp\Database();
        $this->pdo = $db->connect();
        $this->userID = $this->ID();
        $this->sessionID = $this->getSessionId();
    }

    public function ID() {
        if(isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
    }

    public function getSessionId() {
        return session_id();
    }

    public function getUserBySessionID($sessionID) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE sessionID=:sessionID");
        $stmt->bindParam(":sessionID", $sessionID, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user;
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE username=:username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user;
    }

    public function getConnectedPeers() {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE userID !=:userid AND `onlineStatus` = 'Online' LIMIT 4");
        $stmt->bindParam(":userid", $this->userID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_OBJ);
    }

    public function updateSession() {
        $stmt = $this->pdo->prepare("UPDATE `users` set `sessionID`=:sessionID WHERE userID=:userID");
        $stmt->bindParam(":sessionID", $this->sessionID, PDO::PARAM_STR);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateConnection($connectionID, $status, $userID) {
        $stmt = $this->pdo->prepare("UPDATE `users` set `connectionID`=:connectionID,`onlineStatus`=:status WHERE userID=:userID");
        $stmt->bindParam(":connectionID", $connectionID, PDO::PARAM_INT);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
        // echo '<pre>';
        // $stmt->debugDumpParams();
        // echo "</pre>";
    }

    public function userData($userid = '') {
        $userID = (!empty($userid) ? $userid : $this->userID);
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE userID=:userID");
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user;
    }
}
