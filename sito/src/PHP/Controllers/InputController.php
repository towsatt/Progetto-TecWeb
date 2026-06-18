<?php

include_once __DIR__ . '/../Handlers/ErrorHandler.php';
include_once __DIR__ . '/../Validators/InputValidator.php';

class InputController {
    private static function sanitize(string $data): string
    {
        return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
    }

    public static function validateEmail($email) {
        try{
            $email = self::sanitize($email);
            InputValidator::validateEmail($email);
            return $email;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    public static function validatePassword($password) {
        try {
            $password = self::sanitize($password);
            InputValidator::validatePassword($password);
            return $password;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    public static function validateUsername($username) { // Rinominiamo in validateName per usarlo in più contesti? Tipo username, nome personaggio, ecc.
        try {
            $username = self::sanitize($username);
            InputValidator::validateUsername($username);
            return $username;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    public static function validateDescription($description) {
        try {
            $description = self::sanitize($description);
            InputValidator::validateDescription($description);
            return $description;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    //aggiunta creazione personaggio controlli
    //nome personaggio
    public static function validateCharacterName($characterName) {
        try {
            $characterName = self::sanitize($characterName);
            InputValidator::validateCharacterName($characterName);
            return $characterName;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    //punti abilità forza,dx,costit,intel,sagg,carsima 
    public static function validateSkillPoints($skillPoints) {
        try {
            $skillPoints = self::sanitize($skillPoints);
            InputValidator::validateSkillPoints($skillPoints);
            return $skillPoints;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    //controllo da 0
    public static function validateNonNegativeInteger($value) {
        try {
            $value = self::sanitize($value);
            InputValidator::validateNonNegativeInteger($value);
            return intval($value);
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    //controllo quanti caratteri hanno delle lunghi descrizioni
    public static function validateMaxLength($value, $maxLength) {
        try {
            $value = self::sanitize($value);
            InputValidator::validateMaxLength($value, $maxLength);
            return $value;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    //controllo del dado vita
    public static function validateHitDie($hitDie) {
        try {
            $hitDie = self::sanitize($hitDie);
            InputValidator::validateHitDie($hitDie);
            return $hitDie;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    //controllo range di intero con min e max valore
    public static function validateRangeInteger($value, $min, $max) {
        try {
            $value = self::sanitize($value);
            InputValidator::validateRangeInteger($value, $min, $max);
            return intval($value);
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    //controllo enum classe
    public static function validateEnumClasse($classe) {
        try {
            $classe = self::sanitize($classe);
            InputValidator::validateEnumClasse($classe);
            return $classe;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    //controllo enum razza
    public static function validateEnumRazza($razza) {
        try {
            $razza = self::sanitize($razza);
            InputValidator::validateEnumRazza($razza);
            return $razza;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }




}

