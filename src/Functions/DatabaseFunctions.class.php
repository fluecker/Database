<?php
namespace Database\Functions;
use Database\Exceptions\DatabaseExceptions;
use Database\Database;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Exceptions\NoConnectionExceptions;

class DatabaseFunctions {

    /**
     * Bereinigt einen String für die Datenbank
     * @param string $string
     * @param Database $connection
     * @return string
     * @throws NoConnectionExceptions
     */
    public static function clearString(string $string, Database $connection) : string {
        if( get_magic_quotes_gpc() == 1 ) {
            $string = stripslashes($string);
        }

        if($connection->getConnection()) {
            return $connection->getConnection()->real_escape_string($string);
        } else {
            throw new NoConnectionExceptions('No Connection');
        }
    }

    /**
     * Setzt das Datenbank Charset
     * @param string $set
     * @param Database $connection
     * @return bool
     * @throws DatabaseExceptions
     */
    public static function setCharset(string $set, Database $connection) : bool {
        try
        {
            if($connection->getConnection()) {
                $connection->getConnection()->set_charset($set);
            } else {
                throw new NoConnectionExceptions('No Connection');
            }

            return true;
        } catch(\Exception $ex) {
            throw new DatabaseExceptions($ex->getMessage());
        }
    }

    /**
     * Prüft die Datenbank Zugangsdaten
     * @param array $data
     * @return bool
     * @throws DatabaseExceptions
     */
    public static function validateDatabaseData(array $data) : bool {
        foreach($data as $key => $value) {
            if ($data[$key] == '') {
                throw new DatabaseExceptions('Database ' . $key . ' cannot be empty', 3);
            }
        }

        return true;
    }

    /**
     * Wählt die gewünschte Datenbank aus
     * @param string $db
     * @param Database $connection
     * @return bool
     * @throws DatabaseExceptions
     */
    public static function selectDatabase(string $db, Database $connection) : bool {
        try {
            if($connection->getConnection()) {
                $connection->getConnection()->select_db(self::clearString($db, $connection));
            } else {
                throw new NoConnectionExceptions('No Connection');
            }
        } catch(\Exception $ex) {
            throw new DatabaseExceptions($ex->getMessage());
        }

        return false;
    }

    /**
     * Gibt die letzte vergebene ID zurück
     * @param Database $connection
     * @return int
     * @throws NoConnectionExceptions
     */
    public static function getLastInsertID(Database $connection) : int {
        if($connection->getConnection()) {
            return $connection->getConnection()->insert_id;
        } else {
            throw new NoConnectionExceptions('No Connection');
        }
    }

    /**
     * Gibt die Anzahl der Datensätze zurück
     * @param Database $connection
     * @return mixed
     * @throws NoConnectionExceptions
     */
    public static function numRows(Database $connection) : int {
        if($connection->getConnection()) {
            if($connection->getResult()) {
                return $connection->getResult()->num_rows;
            }
        } else {
            throw new NoConnectionExceptions('No Connection');
        }

        return false;
    }

    /**
     * Gibt die Anzahl der betroffenen Datensätze zurück
     * @param Database\Database $connection
     * @return int
     * @throws NoConnectionExceptions
     */
    public static function affectedRows(Database $connection) : int {
        if($connection->getConnection()) {
            if($connection->getResult()) {
                return $connection->getResult()->affected_rows;
            }
        } else {
            throw new NoConnectionExceptions('No Connection');
        }

        return false;
    }

    /**
     * @param string $db
     * @param Database $connection
     * @return bool
     * @throws DatabaseExceptions
     * @throws NoConnectionExceptions
     */
    public static function changeDb(string $db, Database $connection) : bool{
        if($connection->getConnection()) {
            self::selectDatabase($db, $connection);
        } else {
            throw new NoConnectionExceptions('No Connection');
        }

        return false;
    }

    /**
     * @param Database $connection
     * @param string $table
     * @param string $field
     * @return bool
     * @throws DatabaseExceptions
     * @throws NoConnectionExceptions
     * @throws \Database\Exceptions\DatabaseQueryException
     */
    public static function reAutoIncrement(Database $connection, string $table, string $field) : bool {
        $query = "ALTER TABLE " . $table . " DROP " . $field;
        $connection->execute($query);
        $query = "ALTER TABLE " . $table . " ADD " . $field . " TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        $connection->execute($query);
        return true;
    }

    /**
     * Escapet einen String ohne Datenbankverbindung
     * @param string $value
     * @return string
     */
    public static function real_escape_string(string $value) : string {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

    /**
     * Validiert den Inhalt von Feldbezeichnungen
     * @param string $field
     * @return bool
     * @throws DatabaseStatementExceptions
     */
    public static function validateField(string $field){
        if($field == '') {
            throw new DatabaseStatementExceptions('Field cannot be empty');
        }

        return true;
    }

    public static function quoteString(string $string){
        return '\'' . $string . '\'';
    }

    public static function allowedMysqlFunction(string $function):bool {
        $allowed = false;

        $functions = [
            'NOW',
            'DATE'
        ];

        foreach($functions as $tmpFunction){
            if(strpos($function, $tmpFunction) !== false){
                $allowed = true;
            }
        }

        return $allowed;
    }

    public static function getArrayDepth($array) {
        $depth = 0;
        $iteIte = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));

        foreach ($iteIte as $ite) {
            $d = $iteIte->getDepth();
            $depth = $d > $depth ? $d : $depth;
        }

        return $depth;
    }
}
