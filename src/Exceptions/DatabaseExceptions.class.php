<?php
namespace Database\Exceptions;

class DatabaseExceptions extends \Exception{
    public function __construct($message, $code = 0 , \Exception $previous = null, $myobj = null, $request = null, $method = ''){
        parent::__construct($message, $code, $previous);
        $this->handle($code);
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