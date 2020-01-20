<?php
namespace Database\Exceptions;

use Database\Functions\DatabaseLog;

class DatabaseConnectionExceptions extends \Exception{
    public function __construct($message, $settings, $code = 0 , \Exception $previous = null, $myobj = null, $request = null, $method = ''){
        parent::__construct($message, $code, $previous);

        DatabaseLog::writeErrorLog($this->prepareMessage(), $settings);
    }

    private function prepareMessage(){
        $msg = 'There was an Database error in file:' . PHP_EOL;
        $msg .= $this->getFile() . PHP_EOL . 'At line: ' . $this->getLine() . PHP_EOL;
        $msg .= 'The Messages: ' . PHP_EOL;
        $msg .= $this->getMessage() . PHP_EOL;

        return $msg;
    }

    private function handle($code){
        switch ($code){
            case 1: // Clear String
            case 2: //
            case 3: //Leere Daten{
                break;
        }
    }
}