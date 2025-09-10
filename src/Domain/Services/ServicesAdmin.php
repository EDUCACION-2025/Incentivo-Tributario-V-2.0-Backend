<?php



namespace App\Domain\Services;

use App\Domain\Repositories\Repositories;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'src/utilities/PHPMailer/src/Exception.php';
require 'src/utilities/PHPMailer/src/PHPMailer.php';
require 'src/utilities/PHPMailer/src/SMTP.php';


class ServicesAdmin{

    private $repositorio;
    private static $instance = null;

    public function __construct() {
        $this->repositorio = new Repositories();
    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ServicesAdmin();
        }
        return self::$instance;
    }

    public function sftp__servicios($remote_file,$local_file) {

        $sftp=$this->repositorio->getConexion__sftp__re();

        if ($sftp->get($remote_file, $local_file)) {

            $file_contents = file_get_contents($local_file);
            $base64_encoded = base64_encode($file_contents);
            
            return $base64_encoded;
    
        } else {

           return 0;

        }

    }


    public function conexion__sshh() {


        date_default_timezone_set('America/Guayaquil');

        $dias_semana = array(
            'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'
        );

        $meses = array(
            1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        );

        $fecha_actual = date('Y-m-d');

        $numero_dia_semana = date('w', strtotime($fecha_actual));
        $dia_semana = $dias_semana[$numero_dia_semana];

        $numero_mes = date('n', strtotime($fecha_actual));
        $mes = $meses[$numero_mes];

        $anio = date('Y', strtotime($fecha_actual));

        $fecha_formateada = ucfirst($dia_semana) . ", " . ucfirst(date('d')) . " del " . $mes . " de " . $anio;

        return $fecha_formateada;

    }


    public function fecha__letras() {


        date_default_timezone_set('America/Guayaquil');

        $dias_semana = array(
            'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'
        );

        $meses = array(
            1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        );

        $fecha_actual = date('Y-m-d');

        $numero_dia_semana = date('w', strtotime($fecha_actual));
        $dia_semana = $dias_semana[$numero_dia_semana];

        $numero_mes = date('n', strtotime($fecha_actual));
        $mes = $meses[$numero_mes];

        $anio = date('Y', strtotime($fecha_actual));

        $fecha_formateada = ucfirst($dia_semana) . ", " . ucfirst(date('d')) . " del " . $mes . " de " . $anio;

        return $fecha_formateada;

    }


    public function select__archivo__natural__ruta($ruta) {


            $imageData = file_get_contents($ruta);
            $imageBase64 = base64_encode($imageData);
            $mimeType = mime_content_type($ruta);

            $documento = 'data:' . $mimeType . ';base64,' . $imageBase64;

            return $documento;
            
    }

    public function select__archivo__natural($nombreDocumento,$ruta) {


        $imagePath = $ruta.$nombreDocumento;
        $imageData = file_get_contents($imagePath);
        $imageBase64 = base64_encode($imageData);
        $mimeType = mime_content_type($imagePath);

        $documento = 'data:' . $mimeType . ';base64,' . $imageBase64;

        return $documento;
        
    }


    public function select__archivo__incentivo($consulta,$ruta,$campo) {

        $motivosOpcionales = $this->repositorio->select__operativo__incentivo($consulta);

        foreach ($motivosOpcionales as $valor) {
            $nombreDocumento=$valor[$campo];
        }

        $imagePath = $ruta.$nombreDocumento;
        $imageData = file_get_contents($imagePath);
        $imageBase64 = base64_encode($imageData);
        $mimeType = mime_content_type($imagePath);

        $documento = 'data:' . $mimeType . ';base64,' . $imageBase64;

        return $documento;

    }


    public function select__archivo($consulta,$ruta,$campo) {

    	$motivosOpcionales = $this->repositorio->select__operativo($consulta);

    	foreach ($motivosOpcionales as $valor) {
    		$nombreDocumento=$valor[$campo];
    	}

        $imagePath = $ruta.$nombreDocumento;
        $imageData = file_get_contents($imagePath);
        $imageBase64 = base64_encode($imageData);
        $mimeType = mime_content_type($imagePath);

        $documento = 'data:' . $mimeType . ';base64,' . $imageBase64;

        return $documento;

    }


    public function select__general($consulta) {

        $consulta = $this->repositorio->select__operativo($consulta);
        return $consulta;

    }


    public function select__general__incentivo($consulta) {

        $consulta = $this->repositorio->select__operativo__incentivo($consulta);
        return $consulta;

    }


    public function select__general__talento($consulta) {

        $consulta = $this->repositorio->select__operativo__talento($consulta);
        return $consulta;

    }


    public function inserta__general($tabla,$array__columnas,$array__campos) {

        $consulta = $this->repositorio->insertSingleRow($tabla,$array__columnas,$array__campos);

        return $consulta;

    }

    public function inserta__general__incentivo($tabla,$array__columnas,$array__campos) {

        $consulta = $this->repositorio->insertSingleRow__incentivo($tabla,$array__columnas,$array__campos);

        return $consulta;

    }

    public function inserta__general__talento($tabla,$array__columnas,$array__campos) {

        $consulta = $this->repositorio->insertSingleRow__talento($tabla,$array__columnas,$array__campos);

        return $consulta;

    }

    public function actualiza__general__incentivo($consulta) {

        $consulta = $this->repositorio->updateRow__incentivo($consulta);

        return $consulta;

    }


    public function actualiza__general__talento($consulta) {

        $consulta = $this->repositorio->updateRow__talento($consulta);

        return $consulta;

    }

    public function actualiza__general($consulta) {

        $consulta = $this->repositorio->updateRow($consulta);

        return $consulta;

    }

     public function enviarCorreo($email,$body,$documento=false){

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.office365.com';                  
            $mail->SMTPAuth   = true;                                  
            $mail->Username   = 'notificacion@deporte.gob.ec';              
            $mail->Password   =  'M$i$n$i$s$t$e$r$i$o2024';                         
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        
            $mail->Port       = 587;                             

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('notificacion@deporte.gob.ec', 'Ministerio del Deporte');

            for ($i=0; $i < count($email); $i++) { 
                    
                $mail->addAddress($email[$i]); 

            }

            $mail->isHTML(true);                                
            $mail->Subject = 'Ministerio del Deporte';
            $mail->Body = $body; 

            if ($documento!=false) {
                $mail->addAttachment($documento); 
            }

            return $mail->send();

        } catch (Exception $e) {
                
            return $e;

        }

        return $mail;

    }
    

    public function generarCodigo($length) {

        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

    }   

    public function archivoCargar($tmp_name,$size,$direccion,$nombreArchivo) {

        $limiteTamano = 5 * 1024 * 1024; 

        if ($size > $limiteTamano) {

            return 2; 

        }else {

            $directorioDestino = $direccion;
            $rutaCompleta = $directorioDestino . $nombreArchivo;

            if (file_exists($rutaCompleta)) {

                unlink($rutaCompleta);

            }

            if (move_uploaded_file($tmp_name, $rutaCompleta)) {

                return 1;

            } else {

                return 0; 

            }

        }

    }   

    public function archivoCargar__25__mb($tmp_name, $size, $direccion, $nombreArchivo) {

        $limiteTamano = 20 * 1024 * 1024;

        if ($size > $limiteTamano) {
            return 200000;
        }

        $mimeType = mime_content_type($tmp_name);

        if ($mimeType !== 'application/pdf') {
            return 40;
        }

        $rutaCompleta = $direccion . $nombreArchivo;

        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }

        return move_uploaded_file($tmp_name, $rutaCompleta) ? 1 : 0;
        
    }


}
