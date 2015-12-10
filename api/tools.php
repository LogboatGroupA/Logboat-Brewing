<?php

function fail($error) {
    $data = array();
    $data['success'] = false;
    $data['error'] = $error;
    echo json_encode($data);
    exit();
}

function success($resultArr = array()) {
    $data = array();
    $data['success'] = true;
    $data['result'] = $resultArr;
    echo json_encode($data);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['userId']);
}

function isUserAdmin() {
    return isLoggedIn() && $_SESSION['isAdmin'];
}

function getBaseUrl() {
    //return "https://cs3380-jam9rd.cloudapp.net/LogboatBrewing/";    //Jacob
    return "https://logboat-brewing-percyodi.c9.io/Logboat-Brewing/"; //Pearse
    //Devun
    //Seth
    //Peter
    //return "https://logboat.cloudapp.net/";                       //Master VM
}

/*
 * Create a random string
 * @author XEWeb <http://www.xeweb.net>
 * @param $length the length of the string to create
 * @return $str the string
 */
function randomString($length = 6) {
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
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