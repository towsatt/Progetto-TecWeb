<?php

class DatabaseError extends Exception {
    public function __construct($message = "", $code = 0, Exception $previous) {
        parent::__construct($message, $code, $previous);
    }
}

class InputError extends Exception {
    public function __construct($message = "", $code = 0, Exception $previous) {
        parent::__construct($message, $code, $previous);
    }
}