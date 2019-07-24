<?php
namespace Database\Functions;

use Database\Config\Config;
use Database\Database;
use Database\Exceptions\DatabaseLogExceptions;

class DatabaseLog {
    public static function add(string $query, int $num_rows, string $method = null, $time = null) {

        if (Config::getInstance()->getLogDestination() == 'file' || Config::getInstance()->getLogDestination() == 'all') {
            self::writeFileLog($query, $method, $num_rows, $time);
        }

        if (Config::getInstance()->getLogDestination() == 'database' || Config::getInstance()->getLogDestination() == 'all' && !Config::getInstance()->isDebug()) {
            self::writeLogIntoDatabase($query, $num_rows, $method, $time);
        }

        if(Config::getInstance()->isEcho()) {
            self::echoQuery($query, $num_rows, $time, true);
        }
    }

    private static function writeLogIntoDatabase(string $query, int $num_rows, string $method, ExecutionTime $time = null){
        if (!Config::getInstance()->isLogUseMainConnection()) {
            $database = Database::getInstance([
                    'connection_data' => Config::getInstance()->getLogConnection(),
                ]
                , true);
        } else {
            $database = Database::getInstance();
        }

        foreach (Config::getInstance()->getLogTableValues() as $key => $value) {
            if (strstr($value, '[message]')) {
                Config::getInstance()->setLogTableValues($key, str_replace('[message]', 'Ausgefuehrte Query: ' . $query, $value));
            }

            if (strstr($value, '[time]') && Config::getInstance()->isTimer()) {
                Config::getInstance()->setLogTableValues($key, str_replace('[time]', $time, $value));
            }

            if (strstr($value, '[num_rows]') && Config::getInstance()->isNumRows() ) {
                Config::getInstance()->setLogTableValues($key, str_replace('[num_rows]', $num_rows, $value));
            }
        }

        $database->insert($method)->addTable(Config::getInstance()->getLogTableName())->addFields(Config::getInstance()->getLogTableColumns())->addValues(Config::getInstance()->getLogTableValues());
        $database->execute();
    }

    private static function writeFileLog(string $query, string $method, int $num_rows, ExecutionTime $time = null){
        if(DatabaseFunctions::createFolder(Config::getInstance()->getLogFilePath())) {
            if (!file_exists(Config::getInstance()->getLogFilePath() . '/' . Config::getInstance()->getLogFileName())) {
                try{
                    touch(Config::getInstance()->getLogFileName());
                } catch(\Exception $ex){
                    throw new DatabaseLogExceptions($ex->getMessage());
                }
            }

            error_log(date('Y-m-d H:i:s') . ' | ' . (Config::getInstance()->isTimer() ? $time . ' | ' : '') . ($method !== null && $method !== '' ? $method . ' | ' : '') . (Config::getInstance()->isNumRows() ? 'Num Rows: ' . $num_rows . ' | ' : '') . $query . PHP_EOL, 3, Config::getInstance()->getLogFilePath() . '/' . Config::getInstance()->getLogFileName());
        }
    }

    private static function echoQuery(string $query, $num_rows, ExecutionTime $time = null, bool $formated = true){
        if($formated) {
            echo (Config::getInstance()->isTimer() ? $time . PHP_EOL : '') . (Config::getInstance()->isNumRows() ? 'Num Rows: ' . $num_rows . PHP_EOL : '') . ' ' . \SqlFormatter::format($query) . PHP_EOL;
        } else {
            echo (Config::getInstance()->isTimer() ? $time . PHP_EOL : '') . (Config::getInstance()->isNumRows() ? 'Num Rows: ' . $num_rows . PHP_EOL : '') . ' ' . $query . PHP_EOL;
        }
    }
}