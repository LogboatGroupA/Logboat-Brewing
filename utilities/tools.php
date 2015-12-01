<?php

function say($text) {
    echo "$text<br>";
}

function printReadOnly($key, $value = '') {
    echo "<div class='form-group'>";
    echo "<label for='$key'>$key</label>";
    echo "<input type='text' class='form-control' id='$key' value='$value' readonly>";
    echo "</div>";
}

function printTextInput($key, $value = '') {
    echo "<div class='form-group'>";
    echo "<label for='$key'>$key</label>";
    echo "<input type='text' class='form-control' id='$key' name='$key' value='$value' required>";
    echo "</div>";
}

function printNumberInput($key, $value = '') {
    echo "<div class='form-group'>";
    echo "<label for='$key'>$key</label>";
    echo "<input type='number' class='form-control' id='$key' name='$key' value='$value' required>";
    echo "</div>";
}

class Database {
    protected static $dbname = "logboatDB";
    protected static $username = "be1dbd64a86c89";
    protected static $password = "3b83625d";
    protected static $host = "us-cdbr-azure-central-a.cloudapp.net";
    protected static $port = 3306;
    
    public static function runQuery($queryStr, $bind_params = array()) {
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