<?php

namespace App\Application\Auth;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


require_once 'vendor/autoload.php'; // Incluye el autoloader de Composer


class Auth{


	private $secret_key;
    private static $instance = null;


    public function __construct() {
        $this->secret_key = "1{&52MqP2w,]i";
    }

	
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }


	public function getAuthToken($idUsuario,$usuario){

		try {

			$secret_key_p = $this->secret_key;

		    $token__array = array(
		        "user_id" => $idUsuario,
		        "username" => $usuario,
		        "exp" => time() + 3600 
		    );

		    $token= JWT::encode($token__array,$secret_key_p, 'HS256');

		   return $token;

		} catch (Exception $e) {
		    return json_encode(array("error" => $e->getMessage()));
		}		

	}

    public function verifyToken($token) {

        try {
            $secret_key_p = $this->secret_key;
            $decoded = JWT::decode($token, new Key($secret_key_p, 'HS256'));
            return (array) $decoded; 
        } catch (Exception $e) {
            return json_encode(array("error" => $e->getMessage()));
        }

    }

    public function restriccionToken($token) {

    	session_start();

    	if ($_SESSION["token"]) {
    		return true;
    	}else{
    		return false;
    	}   	
        
    }

    public function restriccionToken__2($token) {

    	session_start();

    	if ($_SESSION["token"]) {
    		return $_SESSION["token"];
    	}else{
    		return $_SESSION["token"];
    	}   	
        
    }

}
