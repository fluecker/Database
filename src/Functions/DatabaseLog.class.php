<?php
namespace Database\Functions;

use Database\Database;
use Database\Exceptions\DatabaseExceptions;
use Database\Exceptions\DatabaseLogExceptions;

class DatabaseLog {
    public static function add(string $query, array $settings, string $method = null, $time = null) {

        if(isset($settings['destination'])) {
            if ($settings['destination'] == 'file' || $settings['destination'] == 'all') {
                self::writeFileLog($settings, $query, $method, $time);
            }

            if ($settings['destination'] == 'database' || $settings['destination'] == 'all') {
                if(isset($settings['database'])) {
                    self::writeLogIntoDatabase($settings, $query, $method, $time);
                } else {
                    throw new DatabaseLogExceptions('Log Database settings are missing, add under "config" -> "log" -> "database" your MySql Connection Data');
                }
            }
        }

        if(isset($settings['echo']) && $settings['echo']) {
            self::echoQuery($query, $time, true);
        }
    }

    private static function writeLogIntoDatabase(array $settings, string $query, string $method, ExecutionTime $time = null){
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

    private static function writeFileLog(array $settings, string $query, string $method, ExecutionTime $time = null){
        if (isset($settings['file']['log_path']) && $settings['file']['log_path'] !== '' && isset($settings['file']['log_file']) && $settings['file']['log_file'] !== '') {

            if(DatabaseFunctions::createFolder($settings['file']['log_path'])) {
                if (!file_exists($settings['file']['log_path'] . '/' . $settings['file']['log_file'])) {
                    try{
                        touch($settings['file']['log_path']);
                    } catch(\Exception $ex){
                        throw new DatabaseLogExceptions($ex->getMessage());
                    }
                }

                error_log(date('Y-m-d H:i:s') . ' | ' . ($time !== null ? $time . ' | ' : '') . ($method !== null && $method !== '' ? $method . ' | ' : '') . $query . PHP_EOL, 3, $settings['file']['log_path'] . '/' . $settings['file']['log_file']);
            } else {
                throw new DatabaseLogExceptions('No logfile path');
            }
        } else {
            throw new DatabaseExceptions('Attribute "log_path" or "log_file" is missing or empty');
        }
    }

    private static function echoQuery(string $query, ExecutionTime $time = null, bool $formated = true){
        if($formated) {
            echo ($time !== null ? $time . PHP_EOL : '') . ' ' . \SqlFormatter::format($query) . PHP_EOL;
        } else {
            echo ($time !== null ? $time . PHP_EOL : '') . ' ' . $query . PHP_EOL;
        }
    }
}