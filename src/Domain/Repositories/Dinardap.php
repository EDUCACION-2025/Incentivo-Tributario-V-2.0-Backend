<?php

namespace App\Domain\Repositories;


class Dinardap{

  private static $instance = null;

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new Dinardap();
    }
    return self::$instance;
  }


	public function dinardap($cedula) {

        error_reporting(0);

        $codigoPaquete="3683"; 
        $soapUrl = "https://interoperabilidad.dinardap.gob.ec/interoperador-v2?wsdl";
        $soapUser = "MdEPORteItRo21";  
        $soapPassword = "8&UpP%Cbm+jM5&"; 

        $xml_post_string = '

        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:int="http://interoperabilidad.dinardap.gob.ec/interoperador/">

           <soapenv:Header/>

             <soapenv:Body>

                <int:consultar>       

                   <parametros>           

                      <parametro>             

                         <nombre>codigoPaquete</nombre>             

                         <valor>'.$codigoPaquete.'</valor>

                      </parametro>

                      <parametro>             

                         <nombre>identificacion</nombre>             

                         <valor>'.$cedula.'</valor>

                      </parametro>          

                   </parametros>

                </int:consultar>

             </soapenv:Body>

          </soapenv:Envelope>'; 

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
                "SOAPAction: http://interoperabilidad.dinardap.gob.ec/interoperador/consultar", 
            "Content-length: ".strlen($xml_post_string),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); 
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch); 

        curl_close($ch);

        $response = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">',"",$response);
        $response = str_replace('<soap:Body>',"",$response);
        $response = str_replace('<ns2:consultarResponse xmlns:ns2="http://interoperabilidad.dinardap.gob.ec/interoperador/">',"",$response);
        $response = str_replace('</ns2:consultarResponse>',"",$response);
        $response = str_replace('</soap:Body>',"",$response);
        $response = str_replace('</soap:Envelope>',"",$response);
              
        $parser = simplexml_load_string($response); 

        $array = array();

        foreach($parser->entidades->entidad->filas->fila->columnas->columna as $msp){ 

            array_push($array, $msp->valor);

        }

        if (count($array) == 0) {
        	return 0;	
        }else{
        	return $array;	
        }

        	

	}

  public function dinardap__ruc__certificacion($ruc) {
    
    error_reporting(0);

    $codigoPaquete="3692"; 
    $soapUrl = "https://interoperabilidad.dinardap.gob.ec/interoperador-v2?wsdl"; 
    $soapUser = "MdEPORteItRo21";  
    $soapPassword = "8&UpP%Cbm+jM5&";

    $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:int="http://interoperabilidad.dinardap.gob.ec/interoperador/">
       <soapenv:Header/>
       <soapenv:Body>
          <int:consultar>
             <parametros>
                <parametro>
                   <nombre>codigoPaquete</nombre>
                   <valor>'.$codigoPaquete.'</valor>
                </parametro>
                <parametro>
                   <nombre>ruc</nombre>
                   <valor>'.$ruc.'</valor>
                </parametro>
             </parametros>
          </int:consultar>
       </soapenv:Body>
    </soapenv:Envelope>';   

    $headers = array(
      "Content-type: text/xml;charset=\"utf-8\"",
      "Accept: text/xml",
      "Cache-Control: no-cache",
      "Pragma: no-cache",
        "SOAPAction: http://interoperabilidad.dinardap.gob.ec/interoperador/consultar", 
      "Content-length: ".strlen($xml_post_string),
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $soapUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); 
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch); 

    curl_close($ch);

    $response = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">',"",$response);
    $response = str_replace('<soap:Body>',"",$response);
    $response = str_replace('<ns2:consultarResponse xmlns:ns2="http://interoperabilidad.dinardap.gob.ec/interoperador/">',"",$response);
    $response = str_replace('</ns2:consultarResponse>',"",$response);
    $response = str_replace('</soap:Body>',"",$response);
    $response = str_replace('</soap:Envelope>',"",$response);
                
    $parser = simplexml_load_string($response); 

    $array = array();

    foreach($parser->entidades->entidad->filas->fila->columnas->columna as $msp){ 

      array_push($array, $msp->valor);

    }

    if (count($array) == 0) {
      return 0; 
    }else{
      return $array;  
    }

  }


  public function dinardap__ruc($ruc) {
    
    error_reporting(0);

    $codigoPaquete="3692"; 
    $soapUrl = "https://interoperabilidad.dinardap.gob.ec/interoperador-v2?wsdl"; 
    $soapUser = "MdEPORteItRo21";  
    $soapPassword = "8&UpP%Cbm+jM5&";

    $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:int="http://interoperabilidad.dinardap.gob.ec/interoperador/">
       <soapenv:Header/>
       <soapenv:Body>
          <int:consultar>
             <parametros>
                <parametro>
                   <nombre>codigoPaquete</nombre>
                   <valor>'.$codigoPaquete.'</valor>
                </parametro>
                <parametro>
                   <nombre>ruc</nombre>
                   <valor>'.$ruc.'</valor>
                </parametro>
             </parametros>
          </int:consultar>
       </soapenv:Body>
    </soapenv:Envelope>';   

    $headers = array(
      "Content-type: text/xml;charset=\"utf-8\"",
      "Accept: text/xml",
      "Cache-Control: no-cache",
      "Pragma: no-cache",
        "SOAPAction: http://interoperabilidad.dinardap.gob.ec/interoperador/consultar", 
      "Content-length: ".strlen($xml_post_string),
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $soapUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); 
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch); 

    curl_close($ch);

    $response = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">',"",$response);
    $response = str_replace('<soap:Body>',"",$response);
    $response = str_replace('<ns2:consultarResponse xmlns:ns2="http://interoperabilidad.dinardap.gob.ec/interoperador/">',"",$response);
    $response = str_replace('</ns2:consultarResponse>',"",$response);
    $response = str_replace('</soap:Body>',"",$response);
    $response = str_replace('</soap:Envelope>',"",$response);
                
    $parser = simplexml_load_string($response); 

    $array = array();

    foreach($parser->entidades->entidad->filas->fila->columnas->columna as $msp){ 

      array_push($array, $msp->valor);

    }

    if (count($array) == 0) {
      return 0; 
    }else{
      return $array;  
    }

  }


}