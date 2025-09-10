<?php 

namespace App\Domain\Repositories;

use App\Application\Conexion\Conexion;
use PDO;
use PDOException;

use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

class Repositories{

    private $conexionEstablecida;
    private $conexionEstablecida__incentivo;
    private $conexionEstablecida__ssh;

    public function __construct() {

        $this->conexionEstablecida = Conexion::getInstance()->getConexion();
        $this->conexionEstablecida->exec("set names utf8");
        $this->conexionEstablecida->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $this->conexionEstablecida__incentivo = Conexion::getInstance()->getConexion__incentivo();
        $this->conexionEstablecida__incentivo->exec("set names utf8");
        $this->conexionEstablecida__incentivo->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $this->conexionEstablecida__talento = Conexion::getInstance()->getConexion__talentoHumano();
        $this->conexionEstablecida__talento->exec("set names utf8");
        $this->conexionEstablecida__talento->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

    }

    public function fecha__actual() {
        date_default_timezone_set("America/Guayaquil");
        return date('Y-m-d');
    }

    public function hora__actual() {
        date_default_timezone_set("America/Guayaquil");
        return date('H:i:s');
    }

    /*============================
    =            Sshh            =
    ============================*/
    
    public function getConexion__sftp__re($host__v="192.168.12.15",$port__v=21024,$username__v="root",$password__v="SmBR0otD3p2024*") {

         try {

            $sftp = new SFTP($host__v, $port__v);

            if (!$sftp->login($username__v, $password__v)) {
                exit('Error de autenticación SFTP');
            }else{
                return $sftp;
            }

        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }


    }
    
    /*=====  End of Sshh  ======*/
    

    /*======================================
    =            Talento Humano            =
    ======================================*/
    
   public function select__operativo__talento($argumento) {

        $query = $this->conexionEstablecida__talento->prepare($argumento);

        $query->execute();

        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

        $conexionEstablecida__talento = null; 

        return $resultado;
    }
    

    public function select__maximo__talento($maximo,$tabla){

        $query = $this->conexionEstablecida__talento->prepare("SELECT MAX($maximo) AS maximo FROM $tabla;");

        $query->execute();

        $resultado = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conexionEstablecida__talento = null; 

        return $resultado;

    }   

    public function getDatatablets__talento($parametro1) {
        // Preparar y ejecutar la consulta
        $query = $parametro1;
        $resultado = $this->conexionEstablecida__talento->query($query);

        if (!$resultado) {
            echo "error";
        } else {
            $arreglo = array();
            while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $arreglo["data"][] = $data;
            }
        }

        $conexionEstablecida__talento = null; 

        return $arreglo;
    }

    
    public function insertSingleRow__talento($tabla, $campos, $task) {

        try {

            $sql = "INSERT INTO $tabla (";

            $sql .= implode(", ", array_map(function($valor) {
                return "`$valor`";
            }, $campos));
            $sql .= ") VALUES (";
            $sql .= implode(", ", array_map(function($valor) {
                return ":$valor";
            }, $campos));
            $sql .= ");";

            $resultado = $this->conexionEstablecida__talento->prepare($sql);

            $conexionEstablecida__talento = null; 

            return $resultado->execute($task);

        } catch(PDOException $e) {

            echo "ERROR: " . $e->getMessage();

        }

    }

     public function updateSingleRow__talento($tabla, $campos, $task, $condicion) {

        $sql = "UPDATE $tabla SET ";
        $sql .= implode(", ", array_map(function($valor) {
            return "`$valor` = :$valor";
        }, $campos));
        $sql .= " $condicion";

        $resultado = $this->conexionEstablecida__talento->prepare($sql);

        $conexionEstablecida__talento = null; 

        return $resultado->execute($task);

    }


    public function eliminaRow__talento($tabla, $campo, $valor) {

        $sql = "DELETE FROM $tabla WHERE $campo = ?";
        $resultado = $this->conexionEstablecida__talento->prepare($sql);
        $resultado->execute([$valor]);
        $conexionEstablecida__talento = null; 
        return $resultado->rowCount();

    }

    public function updateRow__talento($sentencia) {

        $resultado = $this->conexionEstablecida__talento->exec($sentencia);
        $conexionEstablecida__talento = null; 
        return $resultado;
        
    }           
    
    /*=====  End of Talento Humano  ======*/
    

    /*=================================
    =            Incentivo            =
    =================================*/
    
   public function select__operativo__incentivo($argumento) {

        $query = $this->conexionEstablecida__incentivo->prepare($argumento);

        $query->execute();

        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

        $conexionEstablecida__incentivo = null; 

        return $resultado;
    }
    

    public function select__maximo__incentivo($maximo,$tabla){

        $query = $this->conexionEstablecida__incentivo->prepare("SELECT MAX($maximo) AS maximo FROM $tabla;");

        $query->execute();

        $resultado = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conexionEstablecida__incentivo = null; 

        return $resultado;

    }   

    public function getDatatablets__incentivo($parametro1) {
        // Preparar y ejecutar la consulta
        $query = $parametro1;
        $resultado = $this->conexionEstablecida__incentivo->query($query);

        if (!$resultado) {
            echo "error";
        } else {
            $arreglo = array();
            while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $arreglo["data"][] = $data;
            }
        }

        $conexionEstablecida__incentivo = null; 

        return $arreglo;
    }

    
    public function insertSingleRow__incentivo($tabla, $campos, $task) {

        try {

            $sql = "INSERT INTO $tabla (";

            $sql .= implode(", ", array_map(function($valor) {
                return "`$valor`";
            }, $campos));
            $sql .= ") VALUES (";
            $sql .= implode(", ", array_map(function($valor) {
                return ":$valor";
            }, $campos));
            $sql .= ");";

            $resultado = $this->conexionEstablecida__incentivo->prepare($sql);

            $conexionEstablecida__incentivo = null; 

            return $resultado->execute($task);

        } catch(PDOException $e) {

            echo "ERROR: " . $e->getMessage();

        }

    }

     public function updateSingleRow__incentivo($tabla, $campos, $task, $condicion) {

        $sql = "UPDATE $tabla SET ";
        $sql .= implode(", ", array_map(function($valor) {
            return "`$valor` = :$valor";
        }, $campos));
        $sql .= " $condicion";

        $resultado = $this->conexionEstablecida__incentivo->prepare($sql);

        $conexionEstablecida__incentivo = null; 

        return $resultado->execute($task);

    }


    public function eliminaRow__incentivo($tabla, $campo, $valor) {

        $sql = "DELETE FROM $tabla WHERE $campo = ?";
        $resultado = $this->conexionEstablecida__incentivo->prepare($sql);
        $resultado->execute([$valor]);
        $conexionEstablecida__incentivo = null; 
        return $resultado->rowCount();

    }

    public function updateRow__incentivo($sentencia) {

        $resultado = $this->conexionEstablecida__incentivo->exec($sentencia);
        $conexionEstablecida__incentivo = null; 
        return $resultado;
        
    }       


    /*=====  End of Incentivo  ======*/
    

    /*=====================================
    =            Configuración            =
    =====================================*/
    
   public function select__operativo($argumento) {

        $query = $this->conexionEstablecida->prepare($argumento);

        $query->execute();

        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }


    public function select__maximo($maximo,$tabla){

        $query = $this->conexionEstablecida->prepare("SELECT MAX($maximo) AS maximo FROM $tabla;");

        $query->execute();

        $resultado = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conexionEstablecida = null; 

        return $resultado;

    }   

    public function getDatatablets($parametro1) {
        // Preparar y ejecutar la consulta
        $query = $parametro1;
        $resultado = $this->conexionEstablecida->query($query);

        if (!$resultado) {
            echo "error";
        } else {
            $arreglo = array();
            while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $arreglo["data"][] = $data;
            }
        }

        return $arreglo;
    }

    public function insertSingleRow($tabla, $campos, $task) {

        try {

            $sql = "INSERT INTO $tabla (";

            $sql .= implode(", ", array_map(function($valor) {
                return "`$valor`";
            }, $campos));
            $sql .= ") VALUES (";
            $sql .= implode(", ", array_map(function($valor) {
                return ":$valor";
            }, $campos));
            $sql .= ");";

            $resultado = $this->conexionEstablecida->prepare($sql);

            return $resultado->execute($task);

        } catch(PDOException $e) {

            echo "ERROR: " . $e->getMessage();

        }

    }


     public function updateSingleRow($tabla, $campos, $task, $condicion) {

        $sql = "UPDATE $tabla SET ";
        $sql .= implode(", ", array_map(function($valor) {
            return "`$valor` = :$valor";
        }, $campos));
        $sql .= " $condicion";

        $resultado = $this->conexionEstablecida->prepare($sql);
        return $resultado->execute($task);

    }

    public function eliminaRow($tabla, $campo, $valor) {

        $sql = "DELETE FROM $tabla WHERE $campo = ?";
        $resultado = $this->conexionEstablecida->prepare($sql);
        $resultado->execute([$valor]);
        return $resultado->rowCount();

    }

    public function updateRow($sentencia) {

        $resultado = $this->conexionEstablecida->exec($sentencia);
        return $resultado;
        
    }       

    public function __destruct() {
        $this->conexionEstablecida = null;
        $this->conexionEstablecida__incentivo = null;
        $this->conexionEstablecida__talento = null;
    }
    

    
    /*=====  End of Configuración  ======*/
    
}
