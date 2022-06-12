<?php

namespace MyApp;
use PDO;

class Database {
    function connect() {
        #$pdo = new PDO("mysql:host=127.0.0.1;dbname=webrtc", "root", "");
        $pdo = new PDO("mysql:host=us-cdbr-east-05.cleardb.net;dbname=heroku_c5c746369c00703", "b6ea4de0dc8f3d", "977d2597");
        return $pdo;
    }
}
