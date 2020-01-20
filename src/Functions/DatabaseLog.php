<?php
namespace Database\Functions;

use Config\Log;
use Database\Database;
use Database\Exceptions\DatabaseExceptions;
use Database\Exceptions\DatabaseLogExceptions;

class DatabaseLog {
    public static function add(string $query, Log $settings, string $method = null, $time = null) {

        if ($settings->getLogDestination() == 'file' || $settings->getLogDestination() == 'all') {
            self::writeFileLog($settings, $query, $method, $time);
        }

        if ($settings->getLogDestination() == 'database' || $settings->getLogDestination() == 'all') {
            self::writeLogIntoDatabase($settings, $query, $method, $time);
        }

        if($settings->isEcho()) {
            self::echoQuery($query, $time, true);
        }
    }

    private static function writeLogIntoDatabase(Log $settings, string $query, string $method, ExecutionTime $time = null){
        $database = new Database($settings->getLogConnection());

        foreach ($settings->getLogConnection()->getLogTableValues() as $key => $value) {
            if (strstr($value, '[message]')) {
                $settings->getLogConnection()->setLogTableValue($key, str_replace('[message]', 'Ausgefuehrte Query: ' . $query, $value));
            }

            if (strstr($value, '[time]') && $time !== null) {
                $settings->getLogConnection()->setLogTableValue($key, str_replace('[time]', $time, $value));
            }
        }

        $database->insert($method)->addTable($settings->getLogConnection()->getLogTableName())->addFields($settings->getLogConnection()->getLogTableColumns())->addValues($settings->getLogConnection()->getLogTableValues());
        $database->execute();
    }

    private static function writeFileLog(Log $settings, string $query, ?string $method, ExecutionTime $time = null){
        if ($settings->getFile()->getLogPath() !== '' && $settings->getFile()->getLogFile() !== '') {
            if(DatabaseFunctions::createFolder($settings->getFile()->getLogPath())) {
                if (!file_exists($settings->getFile()->getLogPath() . '/' . $settings->getFile()->getLogFile())) {
                    try{
                        touch($settings->getFile()->getLogPath());
                    } catch(\Exception $ex){
                        throw new DatabaseLogExceptions($ex->getMessage(), $settings);
                    }
                }

                error_log(date('Y-m-d H:i:s') . ' | ' . ($time !== null ? $time . ' | ' : '') . ($method !== null && $method !== '' ? $method . ' | ' : '') . $query . PHP_EOL, 3, $settings->getFile()->getLogPath() . '/' . $settings->getFile()->getLogFile());
            } else {
                throw new DatabaseLogExceptions('No logfile path', $settings);
            }
        } else {
            throw new DatabaseExceptions('Attribute "log_path" or "log_file" is missing or empty', $settings);
        }
    }

    private static function echoQuery(string $query, ExecutionTime $time = null, bool $formated = true){
        if($formated) {
            echo ($time !== null ? $time . PHP_EOL : '') . ' ' . \SqlFormatter::format($query) . PHP_EOL;
        } else {
            echo ($time !== null ? $time . PHP_EOL : '') . ' ' . $query . PHP_EOL;
        }
    }

    public static function writeErrorLog($message, Log $settings, $method = null){
        self::writeFileLog($settings, $message, $method);
    }
}