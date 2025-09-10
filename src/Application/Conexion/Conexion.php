<?php

namespace App\Application\Conexion;

use PDO;
use PDOException;

use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

class Conexion
{

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Conexion();
        }
        return self::$instance;
    }

    function getConexion($hostname__v = "localhost", $username__v = "root", $password__V = "", $database__V = "configuracion")
    {

        try {

            error_reporting(0);

            $hostname = $hostname__v;
            $username = $username__v;
            $password = $password__V;
            $database = $database__V;


            $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {

            echo "ERROR: " . $e->getMessage();
        }
    }

    public function getConexion__incentivo($hostname__v = "localhost", $username__v = "root", $password__V = "", $database__V = "incentivo")
    {

        try {

            error_reporting(0);

            $hostname = $hostname__v;
            $username = $username__v;
            $password = $password__V;
            $database = $database__V;


            $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {

            echo "ERROR: " . $e->getMessage();
        }
    }

    public function getConexion__talentoHumano($hostname__v = "localhost", $username__v = "root", $password__V = "", $database__V = "ezonshar_mdepsaddb")
    {

        try {

            error_reporting(0);

            $hostname = $hostname__v;
            $username = $username__v;
            $password = $password__V;
            $database = $database__V;


            $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {

            echo "ERROR: " . $e->getMessage();
        }
    }
}
