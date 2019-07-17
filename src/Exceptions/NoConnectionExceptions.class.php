<?php
namespace Database\Exceptions;


class NoConnectionExceptions extends \Exception {
    public function __construct($message, $code = 0 , \Exception $previous = null, $myobj = null, $request = null, $method = ''){
        parent::__construct($message, $code, $previous);
        error_log("No database connection. Method: '" . $method . ' ' . $message);
    }
}