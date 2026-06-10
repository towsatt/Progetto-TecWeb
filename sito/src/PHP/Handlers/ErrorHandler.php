<?php

class DatabaseError extends Exception {
    public function __construct($message = "", $code = 0, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class InputError extends Exception {
    public function __construct($message = "", $code = 0, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class AuthError extends Exception {
    public function __construct($message = "", $code = 0, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}