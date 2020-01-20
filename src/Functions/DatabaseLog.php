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
        if($settings->isLogUseMainConnection()){

        }
        if (!isset($settings['database']['main_host']) || !$settings['database']['main_host']) {
            if(isset($settings['database']['connection_data'])) {
                $database = Database::getInstance([
                        'config' => [
                            'debug' => false, //true = do not send the Query to server
                            'timer' => false, //true = save the sql execution time
                            'log' => [
                                'enabled' => false, // true = enabled the log functions
                            ]
                        ],
                        'connection_data' => [
                            'host' => $settings['database']['connection_data']['host'],
                            'user' => $settings['database']['connection_data']['user'],
                            'pass' => $settings['database']['connection_data']['pass'],
                            'prefix' => $settings['database']['connection_data']['prefix'],
                            'database' => $settings['database']['connection_data']['database'],
                            'port' => $settings['database']['connection_data']['port'],
                            'charset' => $settings['database']['connection_data']['charset'],
                            'timezone' => $settings['database']['connection_data']['timezone'],
                        ]
                    ]
                    , true);
            } else {
                throw new DatabaseLogExceptions('Log Database Connection Data settings are missing, add under "config" -> "log" -> "database" -> "connection_data" your MySql Connection Data');
            }
        } else {
            $database = Database::getInstance();
        }

        foreach ($settings['database']['table_data']['values'] as $key => $value) {
            if (strstr($value, '[message]')) {
                $settings['database']['table_data']['values'][$key] = str_replace('[message]', 'Ausgefuehrte Query: ' . $query, $value);
            }

            if (strstr($value, '[time]') && $time !== null) {
                $settings['database']['table_data']['values'][$key] = str_replace('[time]', $time, $value);
            }
        }

        $database->insert($method)->addTable($settings['database']['table_data']['name'])->addFields($settings['database']['table_data']['columns'])->addValues($settings['database']['table_data']['values']);
        $database->execute();
    }

    private static function writeFileLog(Log $settings, string $query, string $method, ExecutionTime $time = null){
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

    public static function writeErrorLog($message, $settings, $method = null){
        error_log(date('Y-m-d H:i:s') . ' | ' . ($method !== null && $method !== '' ? $method . ' | ' : '') . $message . PHP_EOL, 3, $settings->getFile()->getLogPath() . '/' . $settings->getFile()->getLogFile());
    }
}