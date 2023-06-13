<?php
require_once "model/Database.php";
require_once('repository/usersRepository.php');
class User{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $imgType;

    function __construct($name, $lastname, $username, $password, $email, $imgType){
        $this->name = $name;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->imgType = $imgType;
    }


    static function getPublicUserByUsername($username){

    }
    static function getPublicUserById($userId){
        $db = new Database();
        $query = "SELECT * FROM Usuario WHERE id=$userId";
        $result = $db->select($query);
        $userArr = $result->fetch_assoc();
        return [
            "name" => $userArr['nombre'],
            "username" => $userArr['username'],
            "id" => $userArr['id'],
            "imgType" => $userArr['foto']
        ];
    }

    function registerUser(){
        if(!userExists($this->username)){
            if(emailExists($this->email)){
                return 'emailExists';
            }
            if(insertUserIntoDatabase($this->name, $this->username, $this->password, $this->email, $this->imgType)){
                return 'success';
            }
            else{
                return 'error';
            }
        }
        else{
            return 'userExists';
        }
        
    }

}