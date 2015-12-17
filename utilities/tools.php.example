<?php

function say($text) {
    echo "$text<br>";
}

class Database {
    protected static $dbname = "logboatDB";
    protected static $username = "be1dbd64a86c89";
    protected static $password = "3b83625d";
    protected static $host = "us-cdbr-azure-central-a.cloudapp.net";
    protected static $port = 3306;
    
    public static function getConn() {
        try {
            $connection = new PDO(  "mysql:host=" . self::$host
                                    . ";dbname=" . self::$dbname
                                    . ";port=" . self::$port
                                    , self::$username
                                    , self::$password
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $connection;
        } catch(PDOException $e) {
            echo "PDO Connection Exception: " . $e->getMessage();
        }
    }
    
    public static function runQuery($queryStr, $bind_params = array(), $conn = null) {
        if($conn == null or !$conn instanceof PDO) {
            try {
                $connection = new PDO(  "mysql:host=" . self::$host
                                        . ";dbname=" . self::$dbname
                                        . ";port=" . self::$port
                                        , self::$username
                                        , self::$password
                );
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch(PDOException $e) {
                echo "PDO Connection Exception: " . $e->getMessage();
            }
        } else {
            $connection = $conn;
        }
        
        try {
            $stmt = $connection->prepare($queryStr);
            $stmt->execute($bind_params);
            
            if($stmt->columnCount() != 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data['numAffected'] = $stmt->rowCount();
            }
            return $data;
        } catch(PDOException $e) {
            echo "PDO Query Exception: " . $e->getMessage();
        }
    }
}