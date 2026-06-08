<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "validators" . DIRECTORY_SEPARATOR . "InputValidator.php";

class User{
    private $id;
    private $username;
    private $email;
    private $password;
    private $description;
    private $campagne;
    private $personaggi_creati;

    public function UserConstructor(array $array){
        if($array["id"] != null){
            $this->id = $array["id"];
        }
        $this->username = $array["username"];
        $this->email = $array["email"];
        $this->password = $array["password"];
        if($array["description"] != null){
            $this->description = $array["description"];
        }
        $this->campagne = $array["campagne"];
        $this->personaggi_creati = $array["personaggi_creati"];
    }

    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getCampagne(){
        return $this->campagne;
    }
    
    public function getPersonaggiCreati(){
        return $this->personaggi_creati;
    }

    public function save(){
        DBAccess::doQuery("INSERT INTO users (username, email, password, description) VALUES (?, ?, ?, ?)", $this->username, $this->email, $this->password, $this->description);
    }

    public function update(){
        $this->username = $array["username"] ?? $this->username;
        $this->email = $array["email"] ?? $this->email;
        $this->password = $array["password"] ?? $this->password;
        $this->description = $array["description"] ?? $this->description;


        DBAccess::doQuery("UPDATE users SET username = ?, email = ?, password = ?, description = ? WHERE id = ?", $this->username, $this->email, $this->password, $this->description, $this->id);
    }

    public function getIsAdmin(){
        $result = DBAccess::doQuery("SELECT * FROM users WHERE email = ? AND is_admin = 1", $this->email);
        return $result !== false;
    }
}