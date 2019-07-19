<?php
namespace Database\Functions;

use Database\Exceptions\DatabaseExceptions;

class DatabaseLog {
    public static function add(string $query, array $settings, string $method = null, $time = null) {

        if(isset($settings['destination'])) {
            if ($settings['destination'] == 'file' || $settings['destination'] == 'all') {
                if (isset($settings['file']['log_path']) && $settings['file']['log_path'] !== '') {
                    if (!file_exists(dirname(__DIR__) . $settings['file']['log_path'])) {
                        touch(dirname(__DIR__) . $settings['file']['log_path']);
                    }

                    error_log(date('Y-m-d H:i:s') . ' | ' . ($time !== null ? $time . ' | ' : '') . ($method !== null && $method !== '' ? $method . ' | ' : '') . $query . PHP_EOL, 3, dirname(__DIR__) . $settings['file']['log_path']);
                } else {
                    throw new DatabaseExceptions('Attribute "log_path" is missing');
                }
            }

            if ($settings['destination'] == 'database' || $settings['destination'] == 'all') {

            }
        }

        if(isset($settings['echo']) && $settings['echo']) {
            echo \SqlFormatter::format($query) . PHP_EOL . ($time !== null ? $time . PHP_EOL : '');
        }
    }
}