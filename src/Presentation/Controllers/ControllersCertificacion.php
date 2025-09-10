<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Application\Auth\Auth;
use App\Presentation\Controllers\ControllersBandeja;
use App\Presentation\Pdf\Base;
use App\Presentation\Pdf\InformeC;
use App\Presentation\Pdf\Solicitud;

class ControllersCertificacion {

    private $fecha;
    private $hora;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->constructor = ServicesAdmin::getInstance();
        $this->constructor__auth =Auth::getInstance();
        // $this->ruta='http://192.168.12.10/repositorio/incentivo2.0/';
        // $this->ruta='file:///C:/wamp64/www/repositorio/incentivo2.0/';
        // $this->ruta='../poa2/repositorio/incentivo2.0/';
        $this->ruta='../repositorio/incentivo2.0/';

        $this->anio=date('Y');
        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

        $this->constructor__basePdf = Base::getInstance();

        $this->bandeja = new ControllersBandeja();


        $this->informePdf = InformeC::getInstance();
        $this->informePdf__solicitud = Solicitud::getInstance();


    }

    public function obtener__certificaciones__mostrar__pendientes($post) {

      return $this->constructor->select__general__incentivo("SELECT b.codigo,b.id, IFNULL((SELECT a1.nombre FROM proyecto_descripcion AS a1 WHERE a1.codigo=b.codigo),(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM ezonshar_mdepsaddb.pro_proyecto AS a1 WHERE a1.codigo=b.codigo)) AS nombreProyecto FROM proyecto_certificacion_factura_tramite AS b WHERE ((SELECT a1.id FROM proyecto_enviado AS a1 WHERE a1.codigoUsuario=b.codigo AND a1.idComite='".$post["idComite"]."' AND a1.escogidoComiteCertificacion LIMIT 1) OR (SELECT a1.id FROM proyecto_enviado AS a1 WHERE a1.codigo=b.codigo AND a1.idComite='".$post["idComite"]."' AND a1.escogidoComiteCertificacion LIMIT 1)) AND b.estado='P' AND b.estadoRevision=1 AND b.estadoRecomendacion=1;");

    }

    public function obtener__certificaciones__mostrar($post) {

      return $this->constructor->select__general__incentivo("SELECT b.codigo,b.id, IFNULL((SELECT a1.nombre FROM proyecto_descripcion AS a1 WHERE a1.codigo=b.codigo),(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM ezonshar_mdepsaddb.pro_proyecto AS a1 WHERE a1.codigo=b.codigo)) AS nombreProyecto FROM proyecto_certificacion_factura_comite AS a INNER JOIN proyecto_certificacion_factura_tramite AS b ON a.idFactura=b.id WHERE a.idComite='".$post["idComite"]."';");

    }

    private function flattenXml(\SimpleXMLElement $xml, &$result = [], $prefix = '') {
        foreach ($xml->children() as $key => $value) {
            $name = $prefix . $key;
            if ($value->children()) {
                $this->flattenXml($value, $result, $name . '.');
            } else {
                // Si ya existe una clave con este nombre, lo convertimos en array
                if (isset($result[$name])) {
                    if (!is_array($result[$name])) {
                        $result[$name] = [$result[$name]];
                    }
                    $result[$name][] = (string)$value;
                } else {
                    $result[$name] = (string)$value;
                }
            }
        }
        return $result;
    }

    public function xml__generar($post) {
        $array = [];

        $xml = simplexml_load_string($post["xmlContent"]);

        if (isset($xml->comprobante)) {
            $cdataContent = trim((string)$xml->comprobante);
            // Limpiar encabezado XML si lo tiene
            $cdataContent = preg_replace('/<\?xml.*?\?>/', '', $cdataContent);
            $xmlInterno = simplexml_load_string($cdataContent);
        } else {
            $xmlInterno = $xml;
        }

        $flatArray = $this->flattenXml($xmlInterno);

        $numeroFactura = ($flatArray["infoTributaria.estab"] ?? '') . '-' .
                         ($flatArray["infoTributaria.ptoEmi"] ?? '') . '-' .
                         ($flatArray["infoTributaria.secuencial"] ?? '');

        $fechaEmision = $flatArray["infoFactura.fechaEmision"] ?? '';
        $totalSinImpuestos = $flatArray["infoFactura.totalSinImpuestos"] ?? '';
        $razonSocial = $flatArray["infoTributaria.razonSocial"] ?? '';
        $ruc = $flatArray["infoTributaria.ruc"] ?? '';
        $total = $flatArray["infoFactura.pagos.pago.total"] ?? $flatArray["infoFactura.importeTotal"] ?? '';

        // Tomar el primer baseImponible y codigoPorcentaje que aparezca
        $baseImponible = '';
        $codigoPorcentaje = '';
        foreach ($flatArray as $key => $value) {
            if (strpos($key, 'totalConImpuestos.totalImpuesto.') !== false) {
                if (strpos($key, 'baseImponible') !== false && $baseImponible === '') {
                    $baseImponible = $value;
                }
                if (strpos($key, 'codigoPorcentaje') !== false && $codigoPorcentaje === '') {
                    $codigoPorcentaje = $value;
                }
            }
        }

        array_push($array, $numeroFactura);
        array_push($array, $fechaEmision);
        array_push($array, $totalSinImpuestos);
        array_push($array, $baseImponible);
        array_push($array, $total);
        array_push($array, $codigoPorcentaje);
        array_push($array, $razonSocial);
        array_push($array, $ruc);

        return $array;
    }



    public function obtener__informacion__certificacion__v1($post) {

      $idTramite=$post["idTramite"];
      $codigo=$post["codigo"];

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo,a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.pdfFacturaNombre,a.xmlNombre,a.pdfNotaDeVentanNombre,IF(a.estado IS NULL OR a.estado='P','PENDIENTE',IF(a.estado='A','CERTIFICADO','NEGADO')) AS estadoCertificacion,b.ruc,b.razonSocial,b.regimen,IFNULL((SELECT a1.texto FROM proyecto_enviado_recomendacion_certificacion AS a1 WHERE c.id=a1.idEnviado AND a.id=a1.idFactura ORDER BY a1.id DESC LIMIT 1),' ') AS texto FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON b.id=a.idPatrocinador INNER JOIN proyecto_enviado AS c ON c.codigo=a.codigo WHERE a.codigo='$codigo';");

    }     

    public function analistaAtiendeCertificado($codigoUsuario) {

      $consulta=$this->constructor->select__general__incentivo("SELECT a.idUsuarioNuevo FROM proyecto_enviado_antecedente AS a INNER JOIN proyecto_certificacion_factura_tramite AS b ON a.idFactura=b.id WHERE a.tipo LIKE '%Reasignado certificación' AND b.codigo='$codigoUsuario' GROUP BY idEnviado;");
      foreach ($consulta as $valor) {
        $idUsuarioNuevo=$valor["idUsuarioNuevo"];
      }

      return $idUsuarioNuevo;

    }   

    public function obtener__seguimiento__margen($codigoUsuario) {

      $consulta=$this->constructor->select__general__incentivo("SELECT IF(estadoSeguimiento IS NULL,'P','E') AS estadoSeguimiento FROM proyecto_enviado WHERE codigoUsuario='$codigoUsuario';");
      foreach ($consulta as $valor) {
        $estadoSeguimientoBd=$valor["estadoSeguimiento"];
      }

      return $estadoSeguimientoBd;

    }     

    public function enviar__terminar__certificacion($post) {
      
      $codigoUsuario=$post["codigoUsuario"];
      $tieneMonto=$post["tieneMonto"];
      $razonFinalizarProyecto=$post["razonFinalizarProyecto"];

      foreach ($this->informacion__proyecto__enviado__certificacion($codigoUsuario) as $valor) {
        $idCredencial=$valor["idCredencial"];
      }

      foreach ($this->recuperarInformacionUsuario__general__correo($idCredencial) as $valor) {
        $correoEnvio=$valor["correoEnvio"];
        $nombreUsuario=$valor["nombreUsuario"];
      }


      $tipoEnviado="";

      if ($tieneMonto==="si") {

        $variableProyecto="P";
        $tipoEnviado="TIENE MONTO CERTIFICADO";
        $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Ministerio del Deporte,</div><br><div style="font-size:10px; font-weight:bold;">Estimado '.$nombreUsuario.',</div><br><div style="font-size:10px;">Usted realizó la petición de desistimiento del proyecto en el caso de montos certificados son menores al monto calificado</div><div style="font-size:10px;">Conforme el Acuerdo Ministerial Nro. 0243 del 21 de noviembre de 2023 y sus reformas, el artículo 50.- Del informe final de cumplimiento señala: “… El término concedido para la presentación del informe final será de sesenta (60) días contados desde la fecha en la que el programa y/o proyecto deportivo concluyó su proceso de certificación</div><br><br><div style="font-size:10px; font-weight:bold;">Ingrese al menú Seguimiento y cargue su informe</div></body></html>';

      }else{

        $tipoEnviado="NO TIENE MONTO CERTIFICADO";
        $variableProyecto="TB";
        $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Ministerio del Deporte,</div><br><div style="font-size:10px; font-weight:bold;">Estimado '.$nombreUsuario.',</div><br><div style="font-size:10px;">Usted realizó la petición de desistimiento del proyecto en el caso de no tener  certificados por lo tanto su proyecto esta en estado cerrado.</div></body></html>';

      }


      $this->constructor->enviarCorreo([$correoEnvio],$bodyMensaje);

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoSeguimiento='$variableProyecto' WHERE codigoUsuario='$codigoUsuario';"); 

      return $this->constructor->inserta__general__incentivo("proyecto_seguimiento_final", ['codigo','tipo','razon','fecha','hora'], array(

        ':codigo' =>$codigoUsuario,
        ':tipo' =>$tipoEnviado,
        ':razon' =>$razonFinalizarProyecto,
        ':fecha' =>$this->fecha,
        ':hora' =>$this->hora,

      ));


    } 


    public function obtener__dias__proyectos() {

      $consulta=$this->constructor->select__general("SELECT dias FROM configuracion_dias WHERE estado='A';");
      foreach ($consulta as $valor) {
        $diasBd=$valor["dias"];
      }

      return $diasBd;

    } 

    public function actualizar__solicitud__continuidad__proyecto($idEnviar) {
      return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_plurianual SET estado='A' WHERE id='$idEnviar';"); 
    } 


    public function sectores__recibidos($fisicamenteEstructura) {

      if (intval($fisicamenteEstructura)===1) {
         return "(e.idComponentes='5' OR e.idComponentes='7') OR (e.idComponentes='5' AND a.infraRecibido='A' AND a.estadoCalificacion!='Comite') OR (e.idComponentes='7' AND a.infraRecibido='A' AND a.estadoCalificacion!='Comite')";
      }else if(intval($fisicamenteEstructura)===15){
        return "(e.idComponentes='5' OR e.idComponentes='7')";
      }else if(intval($fisicamenteEstructura)===26){
        return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='5' OR c.idSector='4') AND c.idSector=a1.idSector)";
      }else if(intval($fisicamenteEstructura)===24){
        return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='2' OR c.idSector='3' OR c.idSector='1') AND c.idSector=a1.idSector)";
      }else if (intval($fisicamenteEstructura)===26 || intval($fisicamenteEstructura)===19) {
         return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='5') AND c.idSector=a1.idSector)";
      }else if (intval($fisicamenteEstructura)===26 || intval($fisicamenteEstructura)===13) {
         return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='4') AND c.idSector=a1.idSector)";
      }else if (intval($fisicamenteEstructura)===24 || intval($fisicamenteEstructura)===14){
        return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='1') AND c.idSector=a1.idSector)";
      }else if (intval($fisicamenteEstructura)===24 || intval($fisicamenteEstructura)===12){
        return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='2' OR c.idSector='3') AND c.idSector=a1.idSector)";
      }

    }

    public function informacionSolicitud__general__calificar__analista__solicitud__de__continuidad($post) {

        $fisicamenteEstructura=$post["fisicamenteEstructura"];

        $consultaSectores=$this->sectores__recibidos($fisicamenteEstructura);

        return $this->constructor->select__general__incentivo("SELECT a.codigo,a.documento, a.anio,f.nombre,a.id AS idSolicitud  FROM proyecto_certificacion_plurianual AS a INNER JOIN proyecto_enviado AS b ON b.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS f ON f.codigo=b.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigo LEFT JOIN sector AS z ON z.idSector=c.idSector WHERE $consultaSectores AND  a.estado='P';");

    }



    public function actualizar__solicitud__continuidad($post) {

      $documento=$post["documento"];
      $codigoUsuario=$post["codigoUsuario"];
      $anio=$post["anio"];
      $idSolicitudContinuidad=$post["idSolicitudContinuidad"];

      if (!empty($this->sumaProyectoTotal__v1($codigoUsuario))) {
        return $this->constructor->inserta__general__incentivo("proyecto_certificacion_plurianual", ['codigo','documento','fecha','hora','anio','estado','incremental'], array(
          ':codigo' =>$codigoUsuario,
          ':documento' =>$documento,
          ':fecha' =>$this->fecha,
          ':hora' =>$this->hora,
          ':anio' =>$anio,
          ':estado' => 'A',
          ':incremental' =>$idSolicitudContinuidad,
        ));
      }else{
        return $this->constructor->inserta__general__incentivo("proyecto_certificacion_plurianual", ['codigo','documento','fecha','hora','anio','estado','incremental'], array(
          ':codigo' =>$codigoUsuario,
          ':documento' =>$documento,
          ':fecha' =>$this->fecha,
          ':hora' =>$this->hora,
          ':anio' =>$anio,
          ':estado' => 'P',
          ':incremental' =>$idSolicitudContinuidad,
        ));

      }



    } 


    public function obtenerExistente__solicitud__continuidad($post) {
       $ruta=$post["docuRuta"];
       // return $this->constructor->select__archivo__natural__ruta('https://servicios.deporte.gob.ec/poa2/firmaRepositorio/documentos/'.$ruta);
       return $this->constructor->select__archivo__natural__ruta('../../react/firmaElectronica/documentos/'.$ruta);
    } 


    public function generar__informe__certificacion__solicitud($post) {

      $codigo=$post["codigo"];
      $anio=$post["anio"];
      $idCredencial=$post["idCredencial"];

      $monto=$this->sumaProyectoTotal__v1($codigo);

      if(!empty($monto)){

        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigo';");
        foreach ($consulta as $valor) {
          $idEnviadoBd=$valor["id"];
        }


        if(empty($idEnviadoBd)){

          $this->constructor->inserta__general__incentivo("proyecto_enviado", ['codigo','codigoV1','idCredencial','fecha','hora'], array(
              ':codigo' =>$codigo,
              ':codigoV1' =>$codigo,
              ':idCredencial' =>$idCredencial,
              ':fecha' =>$this->fecha,
              ':hora' =>$this->hora,
            ));

        }

        $contenido=$this->informePdf__solicitud->solicitud__de__continuidad__v1($codigo,$anio,$idCredencial);

      }else{
        $contenido=$this->informePdf__solicitud->solicitud__de__continuidad($codigo,$anio);
      }

      
      $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

      return $pdfResult;

    } 




    public function buscar__plurianuales__anio__existente($post){

      $anio=$post["anio"];
      $codigo=$post["codigo"];

      $consulta=$this->constructor->select__general__incentivo("SELECT id,IF(estado='P','PENDIENTE',IF(estado='A','Autorizada','Solicitud pendiente')) AS estado,incremental,documento FROM proyecto_certificacion_plurianual WHERE (estado='A' OR estado='P') AND codigo='$codigo' AND anio='$anio';");
      
      foreach ($consulta as $valor) {
        $id=$valor["id"];
        $estado=$valor["estado"];
        $incremental=$valor["incremental"];
        $documento=$valor["documento"];
      }

      if (empty($id)) {
        return [0,'Solicitud pendiente',1,$documento];
      }else{
        return [1,$estado,(intval($incremental)+1),$documento];
      }

    }


    public function codigo__obtenido__id__factura($idFactura){

      $consulta=$this->constructor->select__general__incentivo("SELECT codigo FROM proyecto_certificacion_factura_tramite WHERE id='$idFactura';");
      foreach ($consulta as $valor) {
        $codigo=$valor["codigo"];
      }


      return $codigo;

    }

    public function obtener__proyectos__documentacion__facturas__notas__de__venta__notaDeVenta($idFactura,$id) {
      return $this->constructor->select__archivo__natural__ruta($this->ruta.'certificacion'.'/'.$id.'__'.$this->codigo__obtenido__id__factura($idFactura).'__notaDeVenta.pdf');
    } 


    public function obtener__proyectos__documentacion__facturas__notas__de__venta__factura($idFactura,$id) {
      return $this->constructor->select__archivo__natural__ruta($this->ruta.'certificacion'.'/'.$id.'__'.$this->codigo__obtenido__id__factura($idFactura).'__ruc.pdf');
    } 

    public function obtener__proyectos__documentacion__facturas__notas__de__venta__factura__comprobantes($idFactura,$id) {
      return $this->constructor->select__archivo__natural__ruta($this->ruta.'certificacion'.'/'.$id.'__'.$this->codigo__obtenido__id__factura($idFactura).'__comprobante.pdf');
    } 



    public function obtener__proyectos__documentacion__facturas__notas__de__venta__xml($idFactura,$id) {
      return $this->constructor->select__archivo__natural__ruta($this->ruta.'certificacion'.'/'.$id.'__'.$this->codigo__obtenido__id__factura($idFactura).'__xml.xml');
    } 


    public function obtener__proyectos__recomendados__analistas__final__certificacion($idComite,$estado){

      if ($estado==="FINALIZADA" || $estado==="CERRADA") {
       
        return $this->constructor->select__general__incentivo("(SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,c.documento,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),'NO') AS version,IFNULL((SELECT a1.idIncremental FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),0) AS idIncremental,(SELECT COUNT(a1.codigo) FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.estado='P' AND a1.estadoRevision=1 AND a1.estadoRecomendacion=1 AND a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS cuantosCertificados,IFNULL((SELECT COUNT(id) + 2 AS contador FROM proyecto_certificacion_factura_codigo AS a1 WHERE a1.codigo=a.codigo),2) AS contadorArchivos FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id WHERE c.idComite = '$idComite' AND c.modulo='CERTIFICACION') UNION (SELECT a.id, a.codigo, UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') )  AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,c.documento,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),'NO') AS version,IFNULL((SELECT a1.idIncremental FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),0) AS idIncremental,(SELECT COUNT(a1.codigo) FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.estado='P' AND a1.estadoRevision=1 AND a1.estadoRecomendacion=1 AND a1.codigo=b.codigo GROUP BY a1.codigo) AS cuantosCertificados,IFNULL((SELECT COUNT(id) + 2 AS contador FROM proyecto_certificacion_factura_codigo AS a1 WHERE a1.codigo=a.codigo),2) AS contadorArchivos FROM proyecto_enviado AS a INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS b ON a.codigo = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id WHERE c.idComite = '$idComite' AND c.modulo='CERTIFICACION');");

      }else{


        return $this->constructor->select__general__incentivo("(SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComiteCertificacion IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,(SELECT COUNT(a1.codigo) FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.estado='P' AND a1.estadoRevision=1 AND a1.estadoRecomendacion=1 AND a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS cuantosCertificados,IFNULL((SELECT COUNT(id) + 2 AS contador FROM proyecto_certificacion_factura_codigo AS a1 WHERE a1.codigo=a.codigo),2) AS contadorArchivos FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.escogidoComiteCertificacion IS NOT NULL AND a.idComite = '$idComite') UNION (SELECT a.id, a.codigo, UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') ) AS nombre, IF(a.escogidoComiteCertificacion IS NULL, 0, 1) AS escogido, a.codigo AS codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,(SELECT COUNT(a1.codigo) FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.estado='P' AND a1.estadoRevision=1 AND a1.estadoRecomendacion=1 AND a1.codigo=b.codigo GROUP BY a1.codigo) AS cuantosCertificados,IFNULL((SELECT COUNT(id) + 2 AS contador FROM proyecto_certificacion_factura_codigo AS a1 WHERE a1.codigo=a.codigo),2) AS contadorArchivos FROM proyecto_enviado AS a INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS b ON a.codigo = b.codigo WHERE a.escogidoComiteCertificacion IS NOT NULL AND a.idComite = '$idComite');");

      }


    }


    public function despriozar__proyectos__certificacion($valor,$proyectoId,$idComite){


      $this->constructor->actualiza__general__incentivo("DELETE FROM comite_proyectos WHERE idEnviado='$proyectoId';");
      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComiteCertificacion=NULL,idComite=NULL WHERE id='$proyectoId';"); 
      $mensaje="Se quito la priorización";

      /*====================================
      =            Por facturas            =
      ====================================*/
      
      $consulta__2 = $this->constructor->select__general__incentivo("SELECT codigoUsuario FROM proyecto_enviado WHERE id='$proyectoId';");
      foreach ($consulta__2 as $valor) {
          $codigoUsuario=$valor["codigoUsuario"];
      }

      $consulta__2 = $this->constructor->select__general__incentivo("SELECT id AS idFactura FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigoUsuario';");
      foreach ($consulta__2 as $valor2) {
        
        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora','idFactura'], array(
          ':idFisicamenteActual' =>0,
          ':idUsuarioActual' => 0,
          ':idFisicamenteNuevo' => 0,
          ':idUsuarioNuevo' => 0,
          ':idEnviado' => null,
          ':textoDevuelto' => "Despriorizado del comité",
          ':tipo' =>  "Despriorizado del comité",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
          ':idFactura' =>  $valor2["idFactura"],
        ));

      }

      
      /*=====  End of Por facturas  ======*/
      


      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, textoDevuelto, tipo, fecha,hora  FROM proyecto_enviado_antecedente WHERE idEnviado='$proyectoId' ORDER BY id DESC LIMIT 1;");

      foreach ($consultaEnviado as $valor) {

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
          ':idFisicamenteActual' =>$valor["idFisicamenteActual"],
          ':idUsuarioActual' => $valor["idUsuarioActual"],
          ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
          ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
          ':idEnviado' =>  $valor["idEnviado"],
          ':textoDevuelto' =>  $valor["textoDevuelto"],
          ':tipo' =>  "SE QUITO LA PRIORIZACIÓN DE LA FACTURA",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));

      }

      $cuantos=$this->configuracion__acreditada($idComite);

      if ($cuantos==="si") {
        $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='configurado' WHERE id='$idComite';"); 
      }else{
        $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='creado' WHERE id='$idComite';"); 
      }


      return 1;

    }

    public function obtener__proyectos__recomendados__certificacion(){

      return $this->constructor->select__general__incentivo("(SELECT a.id,a.codigo,UPPER(b.nombre) AS nombre,IF(a.escogidoComiteCertificacion IS NULL,0,1) AS escogido,a.codigoUsuario, GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ') AS ids, GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ') AS idsIncremental,IF(e.version1 IS NULL,'NO','SI') AS version FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b  ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigoUsuario LEFT JOIN proyecto_certificacion_factura_tramite AS z1 ON z1.codigo=a.codigoUsuario AND z1.estado='P' WHERE e.estadoRecomendacion=1 AND a.escogidoComiteCertificacion=1 GROUP BY e.codigo) UNION (SELECT (SELECT a1.id FROM proyecto_enviado AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.id DESC LIMIT 1) AS id,a.codigo,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')  AS nombre,IF(a.escogidoComiteCertificacion IS NULL,0,1) AS escogido,a.codigo AS codigoUsuario, GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ') AS ids, GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ') AS idsIncremental,IF(e.version1 IS NULL,'NO','SI') AS version FROM ezonshar_mdepsaddb.pro_proyecto AS a INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigo LEFT JOIN proyecto_certificacion_factura_tramite AS z1 ON z1.codigo=a.codigo AND z1.estado='P' INNER JOIN proyecto_enviado AS z ON z.codigo=a.codigo WHERE e.estadoRecomendacion=1 AND z.escogidoComiteCertificacion=1 GROUP BY e.codigo);");

    }

    public function priorizar__proyectos__certificacion($valor,$proyectoId,$idComite){


      $consulta = $this->constructor->select__general__incentivo("SELECT id FROM comite_proyectos WHERE idEnviado='$proyectoId';");
        foreach ($consulta as $valor) {
          $idBd=$valor["id"];
      }


      $this->constructor->inserta__general__incentivo("comite_proyectos", ['idEnviado','idComite','fecha','hora','tipo','modulo'], array(
        ':idEnviado' =>$proyectoId,
        ':idComite' =>$idComite,
        ':fecha' =>$this->fecha,
        ':hora' =>$this->hora,
        ':tipo' =>'comite',
        ':modulo' =>'CERTIFICACION',
      ));   


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComiteCertificacion=1,idComite='$idComite' WHERE id='$proyectoId';"); 
      $mensaje="Priorizado comité";

      /*====================================
      =            Por facturas            =
      ====================================*/
      
      
      $consulta__2 = $this->constructor->select__general__incentivo("SELECT codigoUsuario FROM proyecto_enviado WHERE id='$proyectoId';");
      foreach ($consulta__2 as $valor) {
          $codigoUsuario=$valor["codigoUsuario"];
      }


      $consulta__2 = $this->constructor->select__general__incentivo("SELECT id AS idFactura FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigoUsuario';");
      foreach ($consulta__2 as $valor2) {
        
        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora','idFactura'], array(
          ':idFisicamenteActual' =>0,
          ':idUsuarioActual' => 0,
          ':idFisicamenteNuevo' => 0,
          ':idUsuarioNuevo' => 0,
          ':idEnviado' => null,
          ':textoDevuelto' => "PRIORIZADOO COMITÉ FACTURA",
          ':tipo' =>  "PRIORIZADOO COMITÉ FACTURA",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
          ':idFactura' =>  $valor2["idFactura"],
        ));

      }

      
      /*=====  End of Por facturas  ======*/
      

      
      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, textoDevuelto, tipo, fecha,hora  FROM proyecto_enviado_antecedente WHERE idEnviado='$proyectoId' ORDER BY id DESC LIMIT 1;");

      foreach ($consultaEnviado as $valor) {

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
          ':idFisicamenteActual' =>$valor["idFisicamenteActual"],
          ':idUsuarioActual' => $valor["idUsuarioActual"],
          ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
          ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
          ':idEnviado' =>  $valor["idEnviado"],
          ':textoDevuelto' =>  $valor["textoDevuelto"],
          ':tipo' =>  "Priorizado comité factura",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));

      }

      $cuantos=$this->configuracion__acreditada($idComite);


      $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='configurado' WHERE id='$idComite';"); 

      return 1;

    }

    public function configuracion__acreditada($idComite){

      $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM comite_proyectos WHERE idComite='$idComite' GROUP BY idComite;");
      foreach ($consulta as $valor) {
        $idBd=$valor["cuantos"];
      }

      if(empty($idBd) || intval($idBd)===0){
        $aux="no";
      }else{
        $aux="si";
      }

      return $aux;

    }


    public function obtenerProyectos__comite__certificacion(){

      return $this->constructor->select__general__incentivo("(SELECT a.id,a.codigo,b.nombre,IF(a.escogidoComiteCertificacion IS NULL,0,1) AS escogido,a.codigoUsuario, GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ') AS ids, GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ') AS idsIncremental,IF(e.version1 IS NULL,'NO','SI') AS version FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigoUsuario LEFT JOIN proyecto_certificacion_factura_tramite AS z1 ON z1.codigo=a.codigoUsuario AND z1.estado='P' WHERE e.estadoRecomendacion=1 AND a.escogidoComiteCertificacion IS NULL GROUP BY e.codigo) UNION (SELECT (SELECT a1.id FROM proyecto_enviado AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.id DESC LIMIT 1) AS id,a.codigo,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombre,IF(a.escogidoComiteCertificacion IS NULL,0,1) AS escogido,a.codigo AS codigoUsuario, GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ') AS ids, GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ') AS idsIncremental,IF(e.version1 IS NULL,'NO','SI') AS version FROM ezonshar_mdepsaddb.pro_proyecto AS a INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigo LEFT JOIN proyecto_certificacion_factura_tramite AS z1 ON z1.codigo=a.codigo AND z1.estado='P' INNER JOIN proyecto_enviado AS z ON z.codigo=a.codigo WHERE e.estadoRecomendacion=1 AND z.escogidoComiteCertificacion IS NULL GROUP BY e.codigo);");

    }


    public function bandeja__recomendados__comite__certificacion($post) {


      return $this->constructor->select__general__incentivo("(SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, FORMAT(SUM(d.total), 2) AS monto, GROUP_CONCAT(DISTINCT e.id SEPARATOR ', ') AS ids, GROUP_CONCAT(DISTINCT e.idIncremental SEPARATOR ', ') AS idsIncremental  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigoUsuario WHERE e.estadoRecomendacion=1 GROUP BY e.codigo) UNION (SELECT a.codigo AS codigoUsuario,a.codigo,UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(a.tipoDeportistas = 'actividadFisica', UPPER('Educacion Fisica'), IF(a.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(a.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreacion'))))) AS sector, FORMAT(SUM(a.monto), 2) AS monto, GROUP_CONCAT(DISTINCT e.id SEPARATOR ', ') AS ids, GROUP_CONCAT(DISTINCT e.idIncremental SEPARATOR ', ') AS idsIncremental  FROM ezonshar_mdepsaddb.pro_proyecto AS a INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigo WHERE e.estadoRecomendacion=1 GROUP BY e.codigo);");


    } 

    public function enviar__proyecto__comite__certificacion($post) {

       $idCredencial=$post["idCredencial"];
       $codigoUsuario=$post["codigoUsuario"];
       $idFactura=$post["idFactura"];
       $estado=$post["estado"];

        $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
        $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($informacionUsuario[3]);

       if ($estado==="rechazar") {

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio','idFactura'],array(
          ':idFisicamenteActual' => $informacionUsuario[5],
          ':idUsuarioActual' => $informacionUsuario[6],
          ':idFisicamenteNuevo' => 0,
          ':idUsuarioNuevo' => 0,
          ':idEnviado' => null,
          ':tipo' => "Trámite de factura negado",
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':observacionEnvio' => 'Trámite de factura negado',
          ':idFactura' => $idFactura
        ));
      
         return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET  estadoRevision=0,estadoRecomendacion=0,estado='N' WHERE id='$idFactura';");

       }else{

        $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);
        
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado_recomendacion_certificacion SET idUsuario2='$idUsuario',fecha2='".$this->fecha."',hora2='".$this->hora."' WHERE idFactura='$idFactura';");

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio','idFactura'],array(
          ':idFisicamenteActual' => $informacionUsuario[5],
          ':idUsuarioActual' => $informacionUsuario[6],
          ':idFisicamenteNuevo' => 0,
          ':idUsuarioNuevo' => 0,
          ':idEnviado' => null,
          ':tipo' => "Trámite de factura recomendado al comite",
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':observacionEnvio' => 'Trámite de factura recomendado al comite',
          ':idFactura' => $idFactura
        ));
      
        return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET  estadoRevision=1,estadoRecomendacion=1 WHERE id='$idFactura';");

       }

       
    }

    public function regresar__analista__recomendacion__certificacion($post) {

       $personaReasignar=$post["personaReasignar"];
       $textoRegresar=$post["textoRegresar"];
       $idFactura=$post["idFactura"];
       $idCredencial=$post["idCredencial"];

       $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);
       $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
       $informacionUsuario__superiorInmediatoRegresas=$this->bandeja->obtener__usuario($personaReasignar);

      $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio','idFactura'],array(
        ':idFisicamenteActual' => $informacionUsuario[5],
        ':idUsuarioActual' => $informacionUsuario[6],
        ':idFisicamenteNuevo' => $informacionUsuario__superiorInmediatoRegresas[5],
        ':idUsuarioNuevo' => $informacionUsuario__superiorInmediatoRegresas[6],
        ':idEnviado' => null,
        ':tipo' => "Regresa al analista en etapa de certificación",
        ':fecha' => $this->fecha,
        ':hora' => $this->hora,
        ':observacionEnvio' => $textoRegresar,
        ':idFactura' => $idFactura
      ));
      

       return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET  estadoDevuelto='D', textoDevuelto='$textoRegresar',estadoRevision='$personaReasignar',estadoRecomendacion=NULL WHERE id='$idFactura';");

    }

    public function generar__informe__recomendado__certificacion($post) {

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $opcionRecomendacion=$post["opcionRecomendacion"];
        $textoRecomendacion=$post["textoRecomendacion"];
        $idFactura=$post["idFactura"];
        $caso=$post["caso"];

        foreach ($this->techo__presupuestario__general($codigo,$idFactura) as $valor) {
            $techo=$valor["techo"];
            $oficio=$valor["oficio"];
        }


        foreach ($this->informacion__proyecto__enviado__certificacion($codigo) as $valor) {
            $idEnviado=$valor["id"];
        }


        foreach ($this->fecha__calificacion__proyecto($idEnviado) as $valor) {
            $fechaComite=$valor["fecha"];
        }


        foreach ($this->observable__axios__informacion__facturas($idFactura) as $valor) {
            $fechaEnviaFactura=$valor["fecha"];
        }


        foreach ($this->observable__axios__informacion__facturas__al__analista($idFactura) as $valor) {
            $fechaEnviaAAnalsita=$valor["fecha"];
        }


        $formateado = number_format($techo, 2, ',', '.');

        $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);


        foreach ($this->obtener__informacion__certifiacion__id__usuario__recomienda($idFactura) as $valor) {
            $idUsuario__analista=$valor["idUsuario"];
            $idUsuario__recomienda=$valor["idUsuario2"];
        }

        if ($caso==="Recomendar") {
            $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario__analista);
            $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($informacionUsuario[3]);

        }else{
            $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario__analista);
            // $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($idUsuario__recomienda);
            $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($informacionUsuario[3]);
        }

        
        $informacionUsuario__superiorInmediatoFinal=$this->bandeja->obtener__usuario($informacionUsuario__superiorInmediato[3]);


        $contenido=$this->informePdf->portada__informe__de__certificacion($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->base__legal__de__certificacion();

        if($this->obtener__version__en__certificacion($codigo)==="NO"){
          $contenido.=$this->informePdf->datos__informativos__del__proyecto($codigo,$formateado,$oficio,$codigoProyecto,$fechaComite,$fechaEnviaFactura,$fechaEnviaAAnalsita);
        }else{
          $contenido.=$this->informePdf->datos__informativos__del__proyecto__v1($codigo,$formateado,$oficio,$codigoProyecto,$fechaComite,$fechaEnviaFactura,$fechaEnviaAAnalsita);
        }


        $contenido.=$this->informePdf->antecedentes__certificacion($codigo);

        if($this->obtener__version__en__certificacion($codigo)==="NO"){
          $contenido.=$this->informePdf->datos__informativos__de__factura($idFactura,$codigo,$codigoProyecto);
        }else{
          $contenido.=$this->informePdf->datos__informativos__de__factura__v1($idFactura,$codigo,$codigoProyecto);
        }

        if($this->obtener__version__en__certificacion($codigo)==="NO"){
          $contenido.=$this->informePdf->antecedente__final__certificacion($idFactura);
        }else{
          $contenido.=$this->informePdf->antecedente__final__certificacion__v1($idFactura);
        }


        $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

        $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

        return $pdfResult;

    } 

    public function observable__axios__recibidos_certificacion__recomendados($post){

        $idCredencial=$post["idCredencial"];
        $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);

        return $this->constructor->select__general__incentivo("(SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,d.nombre AS nombreProyecto,IF(a.version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo=a.codigo WHERE estadoRecomendacion='$idUsuario' GROUP BY a.id) UNION (SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto,IF(a.version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=a.codigo WHERE estadoRecomendacion='$idUsuario' GROUP BY a.id);");

    }    

    public function insertaReenvio__certificacion__recomendacion($post) {

      $codigoProyecto=$post["codigoProyecto"];
      $codigoUsuario=$post["codigoUsuario"];
      $idFactura=$post["idFactura"];
      $idCredencial=$post["idCredencial"];
      $variableRecomendacion=$post["variableRecomendacion"];
      $comentarioRechazo=$post["comentarioRechazo"];

      $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
      $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($informacionUsuario[3]);

      $personaReasignar=$informacionUsuario__superiorInmediato[6];
      $idRolActual=$informacionUsuario[4];
      $fisicamenteEstructuraActual=$informacionUsuario[5];
      $estadoEnvia="Recomendación Certificación";
      $textoRegresar=$comentarioRechazo;
      $estadoEnviado=$variableRecomendacion;

      $idUsuarioProfesional=$this->bandeja->seleccionarProfesional($idCredencial);
      $informacionUsuario=$this->bandeja->obtener__usuario($idUsuarioProfesional);
      $idEnviado=$this->bandeja->funcion__obtener__enviado($codigoUsuario);

      /*===============================
      =            Informe            =
      ===============================*/
      
      $codigo=$post["codigo"];
      $opcionRecomendacion=$post["variableRecomendacion"];
      $textoRecomendacion=$post["comentarioRechazo"];

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_enviado_recomendacion_certificacion WHERE idFactura='$idFactura';");


      $this->constructor->inserta__general__incentivo("proyecto_enviado_recomendacion_certificacion", ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','recomendacion','idFactura','codigo'], array(
        ':idEnviado' =>$this->bandeja->funcion__obtener__enviado($codigoProyecto),
        ':idUsuario'=>$idUsuario,
        ':idRol'=>intval($informacionUsuario[4]),
        ':texto'=>$textoRecomendacion,
        ':fecha'=>$this->fecha,
        ':hora'=>$this->hora,
        ':estado'=>'A',
        ':tipo'=>'CERTIFICACION',
        ':recomendacion'=>$opcionRecomendacion,
        ':idFactura'=>$idFactura,
        ':codigo'=>$codigoUsuario,
      ));


      // /*=====  End of Informe  ======*/

      $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio','idFactura'],array(
          ':idFisicamenteActual' => $informacionUsuario[5],
          ':idUsuarioActual' => $informacionUsuario[6],
          ':idFisicamenteNuevo' => $informacionUsuario__superiorInmediato[5],
          ':idUsuarioNuevo' => $informacionUsuario__superiorInmediato[6],
          ':idEnviado' => null,
          ':tipo' => "Recomendado en etapa de certificación",
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':observacionEnvio' => $textoRegresar,
          ':idFactura' => $idFactura
        ));
      

      return $this->funcion__asignar__recomendar($personaReasignar,$idRolActual,$codigoUsuario,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar,$idFactura);

    } 


    public function funcion__asignar__recomendar($idUsuarioResignar,$idRol,$codigo,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar,$idFactura) {

      $fisicamenteEnviar=$this->bandeja->obtener__fisicamenteUsuarios($idUsuarioResignar);
      $idEnviado=$this->bandeja->funcion__obtener__enviado($codigo);

       return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET estadoRevision='0',estadoRecomendacion='$idUsuarioResignar',estadoDevuelto=NULL,textoDevuelto=NULL WHERE id='$idFactura';");

    }


    public function generar__informe($post,$comite=false) {

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $opcionRecomendacion=$post["opcionRecomendacion"];
        $textoRecomendacion=$post["textoRecomendacion"];
        $idFactura=$post["idFactura"];

        foreach ($this->techo__presupuestario__general($codigo,$idFactura) as $valor) {
            $techo=$valor["techo"];
            $oficio=$valor["oficio"];
        }

        foreach ($this->informacion__proyecto__enviado__certificacion($codigo) as $valor) {
            $idEnviado=$valor["id"];
        }


        foreach ($this->fecha__calificacion__proyecto($idEnviado) as $valor) {
            $fechaComite=$valor["fecha"];
        }


        foreach ($this->observable__axios__informacion__facturas($idFactura) as $valor) {
            $fechaEnviaFactura=$valor["fecha"];
        }


        foreach ($this->observable__axios__informacion__facturas__al__analista($idFactura) as $valor) {
            $fechaEnviaAAnalsita=$valor["fecha"];
        }


        $formateado = number_format($techo, 2, ',', '.');

        $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
        $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($informacionUsuario[3]);
        $informacionUsuario__superiorInmediatoFinal=$this->bandeja->obtener__usuario($informacionUsuario__superiorInmediato[3]);

        $contenido=$this->informePdf->portada__informe__de__certificacion($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->base__legal__de__certificacion();


        if($this->obtener__version__en__certificacion($codigo)==="NO"){
          $contenido.=$this->informePdf->datos__informativos__del__proyecto($codigo,$formateado,$oficio,$codigoProyecto,$fechaComite,$fechaEnviaFactura,$fechaEnviaAAnalsita);
        }else{
          $contenido.=$this->informePdf->datos__informativos__del__proyecto__v1($codigo,$formateado,$oficio,$codigoProyecto,$fechaComite,$fechaEnviaFactura,$fechaEnviaAAnalsita);
        }
       

        $contenido.=$this->informePdf->antecedentes__certificacion($codigo);



        if($this->obtener__version__en__certificacion($codigo)==="NO"){
          $contenido.=$this->informePdf->datos__informativos__de__factura($idFactura,$codigo,$codigoProyecto);
        }else{
          $contenido.=$this->informePdf->datos__informativos__de__factura__v1($idFactura,$codigo,$codigoProyecto);
        }

        if($this->obtener__version__en__certificacion($codigo)==="NO"){
          $contenido.=$this->informePdf->antecedente__final__certificacion($idFactura);
        }else{
          $contenido.=$this->informePdf->antecedente__final__certificacion__v1($idFactura);
        }
        

        // if ($comite===false && intval($informacionUsuario[4])===3) {
        //     $informacionUsuario__superiorInmediato=[];
        // }

        $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

        $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

        return $pdfResult;

    } 


    public function insertar__informe($post){

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $opcionRecomendacion=$post["opcionRecomendacion"];
        $textoRecomendacion=$post["textoRecomendacion"];
        $idFactura=$post["idFactura"];

        $idUsuario=$this->bandeja->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);

        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_enviado_recomendacion_certificacion WHERE idFactura='$idFactura';");

        return $this->constructor->inserta__general__incentivo("proyecto_enviado_recomendacion_certificacion", ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','recomendacion','idFactura','codigo'], array(
            ':idEnviado' =>$this->bandeja->funcion__obtener__enviado($codigoProyecto),
            ':idUsuario'=>$idUsuario,
            ':idRol'=>intval($informacionUsuario[4]),
            ':texto'=>$textoRecomendacion,
            ':fecha'=>$this->fecha,
            ':hora'=>$this->hora,
            ':estado'=>'A',
            ':tipo'=>'CALIFICACION',
            ':recomendacion'=>$opcionRecomendacion,
            ':idFactura'=>$idFactura,
            ':codigo'=>$codigo,
        ));

    }


    public function observable__axios__recibidos_certificacion_analista($post){

        $idUsuario=$post["idUsuario"];

        return $this->constructor->select__general__incentivo("(SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,(CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal * 0.15 ELSE a.iva END) AS iva, (CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal + (a.subotal * 0.15) ELSE a.total END) AS total ,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,d.nombre AS nombreProyecto,estadoDevuelto,textoDevuelto,IF(a.version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo=a.codigo WHERE estadoRevision='$idUsuario' AND estadoRecomendacion IS NULL) UNION (SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal, (CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal * 0.15 ELSE a.iva END) AS iva, (CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal + (a.subotal * 0.15) ELSE a.total END) AS total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto,estadoDevuelto,textoDevuelto,IF(a.version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=a.codigo WHERE estadoRevision='$idUsuario' AND estadoRecomendacion IS NULL);");

    }    


    public function insertaReenvio($post) {

      $tipoUsuario=$post["tipo"];
      $codigo=$post["codigo"];
      $codigoUsuario=$post["codigoUsuario"];
      $personaReasignar=$post["personaReasignar"];
      $idRolActual=$post["idRol"];
      $fisicamenteEstructuraActual=$post["fisicamenteEstructura"];
      $estadoEnvia=$post["estadoEnvia"];
      $textoRegresar=$post["textoRegresar"];
      $idFactura=$post["idFactura"];

      $estadoEnviado=$this->bandeja->funcion__obtener__informacion__tipo__recibidos($codigo);

      $idUsuario=$this->bandeja->seleccionarProfesional($post["idCredencial"]);
      $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
      $idEnviado=$this->bandeja->funcion__obtener__enviado($codigo);

      return $this->funcion__asignar($personaReasignar,$idRolActual,$codigoUsuario,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar,$idFactura);

    } 



    public function funcion__asignar($idUsuarioResignar,$idRol,$codigo,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar,$idFactura) {

      $fisicamenteEnviar=$this->bandeja->obtener__fisicamenteUsuarios($idUsuarioResignar);
      $idEnviado=$this->bandeja->funcion__obtener__enviado($codigo);

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET estadoRevision='$idUsuarioResignar' WHERE id='$idFactura';");


       return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio','idFactura'],array(':idFisicamenteActual' => $fisicamenteEstructuraActual,':idUsuarioActual' => $idUsuario,':idFisicamenteNuevo' => $fisicamenteEnviar,':idUsuarioNuevo' => $idUsuarioResignar,':idEnviado' => null,':tipo' => "Reasignado en etapa de certificación",':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $textoRegresar,':idFactura' => $idFactura));

    }


    public function observable__axios__recibidos_certificacion($post){

        return $this->constructor->select__general__incentivo("(SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,d.nombre AS nombreProyecto,IF(a.version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo=a.codigo WHERE estadoRevision IS NULL AND estadoRecomendacion IS NULL) UNION (SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')  AS nombreProyecto,IF(a.version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=a.codigo WHERE estadoRevision IS NULL AND estadoRecomendacion IS NULL);");

    }    

    public function informacionSolicitudCertificacion__general($post){

        $codigo=$post["codigo"];

        return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo,ruc,razonSocial,regimen, numeroFactura,fechaEmision,subotal, (CASE WHEN a.gastoRealizar = 'auspicio' THEN a.subotal * 0.15 ELSE a.iva END) AS iva,(CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal + (a.subotal * 0.15) ELSE a.total END) AS total,ivaPorcentaje,tipoComprobante,gastoRealizar,IF(a.estado='P','PENDIENTE',IF(a.estado='N','NEGADO','APROBADO')) AS estado, (SELECT a1.documento FROM comite_proyectos AS a1 WHERE a1.idComite=c.idComite AND c.id=a1.idEnviado AND a1.modulo='CERTIFICACION') AS notificacion ,a.idIncremental,IFNULL((SELECT a1.texto FROM proyecto_enviado_recomendacion_certificacion AS a1 WHERE c.id=a1.idEnviado AND a.id=a1.idFactura ORDER BY a1.id DESC LIMIT 1),' ') AS texto FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo LEFT JOIN proyecto_certificacion_factura_comite AS d ON a.id=d.idFactura WHERE a.codigo='$codigo';");

    }    

    public function obtener__version__en__certificacion($codigo){

      $consulta=$this->constructor->select__general__incentivo("SELECT IF(version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigo';");
      foreach ($consulta as $valor) {
        $versionBd=$valor["version"];
      }
      return $versionBd;

    }    


    public function obtenerComprador__certificacion($codigo){

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_certificacion_factura_codigo WHERE codigo='$codigo';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (!empty($idBd)) {
        return 1;
      }else{
        return 0;
      }

    }  

    public function anios__certificacion__invocados($codigo){

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ','AÑO ',anio,':',ROUND(SUM(total),2)) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND idNivel1 IS NOT NULL AND total>0 GROUP BY anio;");
      foreach ($consulta as $valor) {
        array_push($array, $valor["total"]);
      }

      return $array;

    }
 
    public function montos__anuales__existentes__v1($codigo) {

        $array=array();

        $consulta=$this->constructor->select__general__talento("SELECT ROUND(SUM(presupuesto), 2) AS presupuestoAnio1,ROUND(SUM(presupuesto2), 2) AS presupuestoAnio2,ROUND(SUM(presupuesto3), 2) AS presupuestoAnio3,ROUND(SUM(presupuestoCuatro), 2) AS presupuestoAnio4,DATE_FORMAT(STR_TO_DATE(inicioPeriodos, '%d/%m/%Y'), '%Y-%m-%d') AS fechaInicio,DATE_FORMAT(STR_TO_DATE(finPeriodos, '%d/%m/%Y'), '%Y-%m-%d') AS fechaFin,DATE_FORMAT(STR_TO_DATE(inicioPeriodos, '%Y'), '%Y') AS anioInicio,DATE_FORMAT(STR_TO_DATE(finPeriodos, '%Y'), '%Y') AS anioFin FROM pro_proyetosreferencias WHERE codigoProyecto='$codigo' ORDER BY idProyectoReferencias;");

        foreach ($consulta as $valor) {
            $presupuestoAnio1=$valor["presupuestoAnio1"];
            $presupuestoAnio2=$valor["presupuestoAnio2"];
            $presupuestoAnio3=$valor["presupuestoAnio3"];
            $presupuestoAnio4=$valor["presupuestoAnio4"];
            $fechaInicio=$valor["fechaInicio"];
            $fechaFin=$valor["fechaFin"];
            $anioInicio=$valor["anioInicio"];
            $anioFin=$valor["anioFin"];
        }

        $array__FechaInicio = explode("-", $fechaInicio);
        $array__FechaFin = explode("-", $fechaFin);

        $restador=0;
        $restador=intval($array__FechaFin[0]) - intval($array__FechaInicio[0]);

        for($i=0;$i<=$restador;$i++){

            if($i===0){
              array_push($array, "AÑO ".intval($array__FechaInicio[0]).": ".$presupuestoAnio1);
            }

            if($i===1){
              array_push($array, "AÑO ".(intval($array__FechaInicio[0]) + 1).": ".$presupuestoAnio2);
            }

            if($i===2){
              array_push($array, "AÑO ".(intval($array__FechaInicio[0]) + 2).": ".$presupuestoAnio3);
            }

            if($i===3){
              array_push($array, "AÑO ".(intval($array__FechaInicio[0]) + 3).": ".$presupuestoAnio4);
            }

        }

        return $array;

    }


    public function obtener__informacion__montos__certificacion($post){

    	$codigo=$post["codigoUsuario"];

      if($this->obtener__version__en__certificacion($codigo)==="NO" || empty($this->sumaProyectoTotal__v1($codigo))){
        foreach ($this->sumaProyectoTotal($codigo) as $valor) {
          $totalCalificado=$valor["totalSuma"];
        }
        $arrayAnual=$this->anios__certificacion__invocados($codigo);
      }else{
        $totalCalificado=floatval($this->sumaProyectoTotal__v1($codigo));
        $arrayAnual=$this->montos__anuales__existentes__v1($codigo);
      }

      $sumaCertificacionPendiente=$this->sumaCertificacionPendiente($codigo) ;
    	
      if($this->obtener__version__en__certificacion($codigo)==="NO"){
    	 $sumaCertificacionAprobada=$this->sumaCertificacionAprobada($codigo);
      }else{
       $sumaCertificacionAprobada=floatval($this->sumaCertificacionAprobada($codigo)) + floatval($this->sumaCertificacion__v1($codigo));
       $sumaCertificacionAprobadaV1=floatval($this->sumaCertificacionAprobada($codigo));
      }

    	$sumaPor__certificar=0;

      if($this->obtener__version__en__certificacion($codigo)==="NO"){
    	  $sumaPor__certificar=floatval($totalCalificado) - (floatval($sumaCertificacionAprobada) + floatval($sumaCertificacionPendiente));
      }else{
        $sumaPor__certificar=floatval($this->sumaProyectoTotal__v1($codigo)) - (floatval($this->sumaCertificacion__v1($codigo)) + floatval($sumaCertificacionPendiente) + floatval($sumaCertificacionAprobadaV1));
      }

    	return [$totalCalificado,$sumaCertificacionPendiente,$sumaCertificacionAprobada,$sumaPor__certificar,$arrayAnual];

    }    

    public function insertar__datos__certificacion__v1($post){

      /*========================================
      =            Datos necesarios            =
      ========================================*/
      
      $codigoUsuario=$post["codigoUsuario"];
      $codigoTabla=$post["codigoTabla"];
      
      /*=====  End of Datos necesarios  ======*/

      if(!empty($codigoUsuario)){

        /*==========================================
        =            Datos patrocinador            =
        ==========================================*/
        
        $ruc=$post["rucPatrocinador"];
        $razonSocial=$post["razonSocial"];
        $regimen=$post["regimen"];

        /*=====  End of Datos patrocinador  ======*/
        

        /*======================================
        =            Datos Factuara            =
        ======================================*/

        $numeroFactura=$post["numeroFactura"];
        $fechaEmision=$post["fechaEmision"];
        $subotal=$post["subotal"];
        $iva=$post["iva"];
        $total=$post["total"];
        $ivaPorcentaje=$post["ivaPorcentaje"];
        $tipoComprobante=$post["tipoComprobante"];
        $gastoRealizar=$post["gastoRealizar"];

        $pdfFacturaNombre=$post["pdfFacturaNombre"];
        $xmlNombre=$post["xmlNombre"];
        $pdfNotaDeVentanNombre=$post["pdfNotaDeVentanNombre"];
        $pdfComprobanteNombre=$post["pdfComprobanteNombre"];

        /*=====  End of Datos Factuara  ======*/
        

        /*================================
        =            Archivos            =
        ================================*/
        
        $pdfFacturaEnviado=$post["pdfFacturaEnviado"];
        $xmlEnviado=$post["xmlEnviado"];
        $pdfNotaDeVentanEnviado=$post["pdfNotaDeVentanEnviado"];
        $pdfComprobanteEnviado=$post["pdfComprobanteEnviado"];

        /*=====  End of Archivos  ======*/
        

        /*====================================
        =             Validadores            =
        ====================================*/
     
        $pdfFacturaValidador=$post["pdfFacturaValidador"];
        $xmlValidador=$post["xmlValidador"];
        $pdfNotaDeVentanValidador=$post["pdfNotaDeVentanValidador"];
        $pdfComprobanteValidador=$post["pdfComprobanteValidador"];
        
        /*=====  End of  Validadores  ======*/

        $idCredencial=$post["idCredencial"];
        
        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM certificacion_patrocinadores WHERE ruc='$ruc';");
        foreach ($consulta as $valor) {
          $idPatrocinador=$valor["id"];
        }

        if (empty($idPatrocinador)) {
          
          $this->constructor->inserta__general__incentivo("certificacion_patrocinadores", ['ruc','razonSocial','regimen','estado','fecha','hora'], array(
              ':ruc' =>$ruc,
          ':razonSocial' =>$razonSocial,
          ':regimen' =>$regimen,
          ':estado' =>'A',
          ':fecha' =>$this->fecha,
          ':hora' =>$this->hora,
            ));

        }

        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM certificacion_patrocinadores WHERE ruc='$ruc';");
        foreach ($consulta as $valor) {
          $idPatrocinador=$valor["id"];
        }

        $rutaDefinitiva=$this->ruta."certificacion/";

        if($tipoComprobante!=="Comprobante físico"){
          $this->constructor->archivoCargar($_FILES['pdfFacturaEnviado']['tmp_name'],$_FILES['pdfFacturaEnviado']['size'],$rutaDefinitiva,$pdfFacturaNombre);
          $this->constructor->archivoCargar($_FILES['xmlEnviado']['tmp_name'],$_FILES['xmlEnviado']['size'],$rutaDefinitiva,$xmlNombre);
          $this->constructor->archivoCargar($_FILES['pdfComprobanteEnviado']['tmp_name'],$_FILES['pdfComprobanteEnviado']['size'],$rutaDefinitiva,$pdfComprobanteNombre);
        }else{
          $this->constructor->archivoCargar($_FILES['pdfNotaDeVentanEnviado']['tmp_name'],$_FILES['pdfNotaDeVentanEnviado']['size'],$rutaDefinitiva,$pdfNotaDeVentanNombre);
          $this->constructor->archivoCargar($_FILES['pdfComprobanteEnviado']['tmp_name'],$_FILES['pdfComprobanteEnviado']['size'],$rutaDefinitiva,$pdfComprobanteNombre);
        }

        if($iva==="null" || $subotal==="null"){
          $iva=0;
          $subotal=0;
          $ivaPorcentaje=0;
        }


          $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigoUsuario' GROUP BY codigo;");
          foreach ($consulta as $valor) {
              $cuantos=$valor["cuantos"];
          }

          $cuantos__enviar=0;

          if (empty($cuantos)) {
              $cuantos__enviar=1;
          }else{
              $cuantos__enviar=($cuantos + 1);
          }

          if($tipoComprobante==="Comprobante físico"){

            $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_tramite", ['codigo','numeroFactura','fechaEmision','subotal','iva','total','ivaPorcentaje','tipoComprobante','gastoRealizar','pdfFacturaNombre','xmlNombre','pdfNotaDeVentanNombre','estadoFactura','estadoRevision','estadoRecomendacion','estado','fecha','hora','idPatrocinador','idIncremental','version1','pdfComprobanteNombre','rucXml','razonSocialXml'], array(
              ':codigo' =>$codigoUsuario,
              ':numeroFactura' =>$numeroFactura,
              ':fechaEmision' =>$fechaEmision,
              ':subotal' =>$total,
              ':iva' =>0,
              ':total' =>$total,
              ':ivaPorcentaje' =>$ivaPorcentaje,
              ':tipoComprobante' =>$tipoComprobante,
              ':gastoRealizar' =>$gastoRealizar,
              ':pdfFacturaNombre' =>$pdfFacturaNombre,
              ':xmlNombre' =>$xmlNombre,
              ':pdfNotaDeVentanNombre' =>$pdfNotaDeVentanNombre,
              ':estadoFactura' =>NULL,
              ':estadoRevision' =>NULL,
              ':estadoRecomendacion' =>NULL,
              ':estado' =>'P',
              ':fecha' =>$this->fecha,
              ':hora' =>$this->hora,
              ':idPatrocinador' =>$idPatrocinador,
              ':idIncremental' =>$cuantos__enviar,
              ':version1' =>'A',
              ':pdfComprobanteNombre' =>$pdfComprobanteNombre,
              ':rucXml' =>$post["razonSocialEmisorFactura"],
              ':razonSocialXml' =>$post["rucEmisorFactura"],
            ));


          }else{

            $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_tramite", ['codigo','numeroFactura','fechaEmision','subotal','iva','total','ivaPorcentaje','tipoComprobante','gastoRealizar','pdfFacturaNombre','xmlNombre','pdfNotaDeVentanNombre','estadoFactura','estadoRevision','estadoRecomendacion','estado','fecha','hora','idPatrocinador','idIncremental','version1','pdfComprobanteNombre','rucXml','razonSocialXml'], array(
                ':codigo' =>$codigoUsuario,
                ':numeroFactura' =>$numeroFactura,
                ':fechaEmision' =>$fechaEmision,
                ':subotal' =>$subotal,
                ':iva' =>$iva,
                ':total' =>$total,
                ':ivaPorcentaje' =>$ivaPorcentaje,
                ':tipoComprobante' =>$tipoComprobante,
                ':gastoRealizar' =>$gastoRealizar,
                ':pdfFacturaNombre' =>$pdfFacturaNombre,
                ':xmlNombre' =>$xmlNombre,
                ':pdfNotaDeVentanNombre' =>$pdfNotaDeVentanNombre,
                ':estadoFactura' =>NULL,
                ':estadoRevision' =>NULL,
                ':estadoRecomendacion' =>NULL,
                ':estado' =>'P',
                ':fecha' =>$this->fecha,
                ':hora' =>$this->hora,
                ':idPatrocinador' =>$idPatrocinador,
                ':idIncremental' =>$cuantos__enviar,
                ':version1' =>'A',
                ':pdfComprobanteNombre' =>$pdfComprobanteNombre,
              ':rucXml' =>$post["razonSocialEmisorFactura"],
              ':razonSocialXml' =>$post["rucEmisorFactura"],
              ));

          }

          $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigoUsuario';");
          foreach ($consulta as $valor) {
              $idEnviadoBd=$valor["id"];
          }

          if(empty($idEnviadoBd)){

            $this->constructor->inserta__general__incentivo("proyecto_enviado", ['codigo','codigoV1','idCredencial','fecha','hora'], array(
                ':codigo' =>$codigoUsuario,
                ':codigoV1' =>$codigoUsuario,
                ':idCredencial' =>$idCredencial,
                ':fecha' =>$this->fecha,
                ':hora' =>$this->hora,
              ));

          }

          return 1;

      }
      

    }   


    public function insertar__datos__certificacion($post){

    	/*========================================
    	=            Datos necesarios            =
    	========================================*/
    	
    	$codigoUsuario=$post["codigoUsuario"];
    	$codigoTabla=$post["codigoTabla"];
    	
    	/*=====  End of Datos necesarios  ======*/

      if(!empty($codigoUsuario)){

        /*==========================================
        =            Datos patrocinador            =
        ==========================================*/
        
        $ruc=$post["rucPatrocinador"];
        $razonSocial=$post["razonSocial"];
        $regimen=$post["regimen"];

        /*=====  End of Datos patrocinador  ======*/
        

        /*======================================
        =            Datos Factuara            =
        ======================================*/

        $numeroFactura=$post["numeroFactura"];
        $fechaEmision=$post["fechaEmision"];
        $subotal=$post["subotal"];
        $iva=$post["iva"];
        $total=$post["total"];
        $ivaPorcentaje=$post["ivaPorcentaje"];
        $tipoComprobante=$post["tipoComprobante"];
        $gastoRealizar=$post["gastoRealizar"];

        $pdfFacturaNombre=$post["pdfFacturaNombre"];
        $xmlNombre=$post["xmlNombre"];
        $pdfNotaDeVentanNombre=$post["pdfNotaDeVentanNombre"];
        $pdfComprobanteNombre=$post["pdfComprobanteNombre"];

        /*=====  End of Datos Factuara  ======*/
        

        /*================================
        =            Archivos            =
        ================================*/
        
        $pdfFacturaEnviado=$post["pdfFacturaEnviado"];
        $xmlEnviado=$post["xmlEnviado"];
        $pdfNotaDeVentanEnviado=$post["pdfNotaDeVentanEnviado"];
        $pdfComprobanteEnviado=$post["pdfComprobanteEnviado"];
        
        /*=====  End of Archivos  ======*/
        

        /*====================================
        =             Validadores            =
        ====================================*/
     
        $pdfFacturaValidador=$post["pdfFacturaValidador"];
        $xmlValidador=$post["xmlValidador"];
        $pdfNotaDeVentanValidador=$post["pdfNotaDeVentanValidador"];
        $pdfComprobanteValidador=$post["pdfComprobanteValidador"];
        
        /*=====  End of  Validadores  ======*/
        
        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM certificacion_patrocinadores WHERE ruc='$ruc';");
        foreach ($consulta as $valor) {
          $idPatrocinador=$valor["id"];
        }

        if (empty($idPatrocinador)) {
          
          $this->constructor->inserta__general__incentivo("certificacion_patrocinadores", ['ruc','razonSocial','regimen','estado','fecha','hora'], array(
            ':ruc' =>$ruc,
            ':razonSocial' =>$razonSocial,
            ':regimen' =>$regimen,
            ':estado' =>'A',
            ':fecha' =>$this->fecha,
            ':hora' =>$this->hora,
          ));

        }

        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM certificacion_patrocinadores WHERE ruc='$ruc';");
        foreach ($consulta as $valor) {
          $idPatrocinador=$valor["id"];
        }

        $rutaDefinitiva=$this->ruta."certificacion/";

        if($tipoComprobante!=="Comprobante físico"){
          $this->constructor->archivoCargar($_FILES['pdfFacturaEnviado']['tmp_name'],$_FILES['pdfFacturaEnviado']['size'],$rutaDefinitiva,$pdfFacturaNombre);
          $this->constructor->archivoCargar($_FILES['xmlEnviado']['tmp_name'],$_FILES['xmlEnviado']['size'],$rutaDefinitiva,$xmlNombre);
          $this->constructor->archivoCargar($_FILES['pdfComprobanteEnviado']['tmp_name'],$_FILES['pdfComprobanteEnviado']['size'],$rutaDefinitiva,$pdfComprobanteNombre);
        }else{
          $this->constructor->archivoCargar($_FILES['pdfNotaDeVentanEnviado']['tmp_name'],$_FILES['pdfNotaDeVentanEnviado']['size'],$rutaDefinitiva,$pdfNotaDeVentanNombre);
          $this->constructor->archivoCargar($_FILES['pdfComprobanteEnviado']['tmp_name'],$_FILES['pdfComprobanteEnviado']['size'],$rutaDefinitiva,$pdfComprobanteNombre);
        }

        if($iva==="null" || $subotal==="null"){
          $iva=0;
          $subotal=0;
          $ivaPorcentaje=0;
        }


          $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigoUsuario' GROUP BY codigo;");
          foreach ($consulta as $valor) {
              $cuantos=$valor["cuantos"];
          }

          $cuantos__enviar=0;

          if (empty($cuantos)) {
              $cuantos__enviar=1;
          }else{
              $cuantos__enviar=($cuantos + 1);
          }

          if($tipoComprobante==="Comprobante físico"){

            $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_tramite", ['codigo','numeroFactura','fechaEmision','subotal','iva','total','ivaPorcentaje','tipoComprobante','gastoRealizar','pdfFacturaNombre','xmlNombre','pdfNotaDeVentanNombre','estadoFactura','estadoRevision','estadoRecomendacion','estado','fecha','hora','idPatrocinador','idIncremental','pdfComprobanteNombre','rucXml','razonSocialXml'], array(
              ':codigo' =>$codigoUsuario,
              ':numeroFactura' =>$numeroFactura,
              ':fechaEmision' =>$fechaEmision,
              ':subotal' =>$total,
              ':iva' =>0,
              ':total' =>$total,
              ':ivaPorcentaje' =>$ivaPorcentaje,
              ':tipoComprobante' =>$tipoComprobante,
              ':gastoRealizar' =>$gastoRealizar,
              ':pdfFacturaNombre' =>$pdfFacturaNombre,
              ':xmlNombre' =>$xmlNombre,
              ':pdfNotaDeVentanNombre' =>$pdfNotaDeVentanNombre,
              ':estadoFactura' =>NULL,
              ':estadoRevision' =>NULL,
              ':estadoRecomendacion' =>NULL,
              ':estado' =>'P',
              ':fecha' =>$this->fecha,
              ':hora' =>$this->hora,
              ':idPatrocinador' =>$idPatrocinador,
              ':idIncremental' =>$cuantos__enviar,
              ':pdfComprobanteNombre' =>$pdfComprobanteNombre,
              ':rucXml' =>$post["razonSocialEmisorFactura"],
              ':razonSocialXml' =>$post["rucEmisorFactura"],
            ));


          }else{

            $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_tramite", ['codigo','numeroFactura','fechaEmision','subotal','iva','total','ivaPorcentaje','tipoComprobante','gastoRealizar','pdfFacturaNombre','xmlNombre','pdfNotaDeVentanNombre','estadoFactura','estadoRevision','estadoRecomendacion','estado','fecha','hora','idPatrocinador','idIncremental','pdfComprobanteNombre','rucXml','razonSocialXml'], array(
                ':codigo' =>$codigoUsuario,
                ':numeroFactura' =>$numeroFactura,
                ':fechaEmision' =>$fechaEmision,
                ':subotal' =>$subotal,
                ':iva' =>$iva,
                ':total' =>$total,
                ':ivaPorcentaje' =>$ivaPorcentaje,
                ':tipoComprobante' =>$tipoComprobante,
                ':gastoRealizar' =>$gastoRealizar,
                ':pdfFacturaNombre' =>$pdfFacturaNombre,
                ':xmlNombre' =>$xmlNombre,
                ':pdfNotaDeVentanNombre' =>$pdfNotaDeVentanNombre,
                ':estadoFactura' =>NULL,
                ':estadoRevision' =>NULL,
                ':estadoRecomendacion' =>NULL,
                ':estado' =>'P',
                ':fecha' =>$this->fecha,
                ':hora' =>$this->hora,
                ':idPatrocinador' =>$idPatrocinador,
                ':idIncremental' =>$cuantos__enviar,
                ':pdfComprobanteNombre' =>$pdfComprobanteNombre,
                ':rucXml' =>$post["razonSocialEmisorFactura"],
                ':razonSocialXml' =>$post["rucEmisorFactura"],
              ));

          }

          $consulta=$this->constructor->select__general__incentivo("SELECT MAX(id) AS idMaximo FROM proyecto_certificacion_factura_tramite;");
          foreach ($consulta as $valor) {
              $idMaximo=$valor["idMaximo"];
          }


          $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigoUsuario';");
          foreach ($consulta as $valor) {
              $idEnviado=$valor["id"];
          }

          $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idFactura','tipo','fecha','hora','observacionEnvio','idEnviado'],array(
              ':idFisicamenteActual' => 0,
              ':idUsuarioActual' => 0,
              ':idFisicamenteNuevo' => 0,
              ':idUsuarioNuevo' => 0,
              ':idFactura' => $idMaximo,
              ':tipo' => "Factura enviada",
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':observacionEnvio' =>'Factura ingresada',
              ':idEnviado' => null,
            ));


          return 1;

      }
    	

    }   


    public function informacionIva($post){

    	$consulta=$this->constructor->select__general__incentivo("SELECT iva FROM certificacion_iva WHERE estado='A' ORDER BY id DESC LIMIT 1;");
    	foreach ($consulta as $valor) {
    		$iva=$valor["iva"];
    	}

    	return $iva;

    }   

    public function sumaCertificacion($codigo){
    	return $this->constructor->select__general__incentivo("SELECT SUM(subotal) AS sumaCertificacion FROM proyecto_certificacion_factura_tramite WHERE (estado='A' OR estado='P') AND codigo='$codigo' GROUP BY codigo;");
    }

    public function sumaCertificacionAprobada($codigo){

    	$consulta=$this->constructor->select__general__incentivo("SELECT SUM(subotal) AS sumaCertificacion FROM proyecto_certificacion_factura_tramite WHERE estado='A' AND codigo='$codigo' GROUP BY codigo;");

    	foreach ($consulta as $valor) {
    		$sumaCertificacion=$valor["sumaCertificacion"];
    	}

    	if(empty($sumaCertificacion)){
    		$sumaCertificacion=0;
    	}

    	return $sumaCertificacion;

    }

    public function sumaCertificacionPendiente($codigo){

    	$consulta=$this->constructor->select__general__incentivo("SELECT SUM(subotal) AS sumaCertificacion FROM proyecto_certificacion_factura_tramite WHERE estado='P' AND codigo='$codigo' GROUP BY codigo;");

    	foreach ($consulta as $valor) {
    		$sumaCertificacion=$valor["sumaCertificacion"];
    	}

    	if(empty($sumaCertificacion)){
    		$sumaCertificacion=0;
    	}

    	return $sumaCertificacion;

    }

    public function sumaProyectoTotal($codigo){
    	return $this->constructor->select__general__incentivo("SELECT SUM(total) AS totalSuma FROM proyecto_presupuesto WHERE codigo='$codigo' AND nivel!=0 GROUP BY codigo;");
    }

    public function sumaProyectoTotal__v1($codigo){

      $consulta=$this->constructor->select__general__talento("SELECT IFNULL(CAST(presupuesto AS FLOAT), 0) + IFNULL(CAST(presupuesto2 AS FLOAT), 0) + IFNULL(CAST(presupuesto3 AS FLOAT), 0) + IFNULL(CAST(presupuestoCuatro AS FLOAT), 0) AS monto FROM pro_proyetosreferencias WHERE codigoProyecto = '$codigo';");

      foreach ($consulta as $valor) {
        $montoBd=$valor["monto"];
      }

      return $montoBd;

    }


    public function sumaCertificacion__v1($codigo){

      $consulta=$this->constructor->select__general__talento("SELECT SUM(monto) AS suma FROM pro_certificacion WHERE codigo='$codigo' GROUP BY codigo;");
      foreach ($consulta as  $valor) {
        $sumaBd=$valor["suma"];
      }

      if (empty($sumaBd)) {
        return 0;
      }else{
        return $sumaBd;
      }

    }


    public function montos__anuales__existentes__v1__verificar($codigo) {

      $anioDevuelto=0;

      $consulta=$this->constructor->select__general__talento("SELECT ROUND(SUM(presupuesto), 2) AS presupuestoAnio1,ROUND(SUM(presupuesto2), 2) AS presupuestoAnio2,ROUND(SUM(presupuesto3), 2) AS presupuestoAnio3,ROUND(SUM(presupuestoCuatro), 2) AS presupuestoAnio4,DATE_FORMAT(STR_TO_DATE(inicioPeriodos, '%d/%m/%Y'), '%Y-%m-%d') AS fechaInicio,DATE_FORMAT(STR_TO_DATE(finPeriodos, '%d/%m/%Y'), '%Y-%m-%d') AS fechaFin,DATE_FORMAT(STR_TO_DATE(inicioPeriodos, '%Y'), '%Y') AS anioInicio,DATE_FORMAT(STR_TO_DATE(finPeriodos, '%Y'), '%Y') AS anioFin FROM pro_proyetosreferencias WHERE codigoProyecto='$codigo' ORDER BY idProyectoReferencias;");

      foreach ($consulta as $valor) {
          $presupuestoAnio1=$valor["presupuestoAnio1"];
          $presupuestoAnio2=$valor["presupuestoAnio2"];
          $presupuestoAnio3=$valor["presupuestoAnio3"];
          $presupuestoAnio4=$valor["presupuestoAnio4"];
          $fechaInicio=$valor["fechaInicio"];
          $fechaFin=$valor["fechaFin"];
          $anioInicio=$valor["anioInicio"];
          $anioFin=$valor["anioFin"];
      }

      $array__FechaInicio = explode("-", $fechaInicio);
      $array__FechaFin = explode("-", $fechaFin);

      $restador=0;
      $restador=intval($array__FechaFin[0]) - intval($array__FechaInicio[0]);

      for($i=0;$i<=$restador;$i++){

          if($i===0 && intval($this->$array__FechaInicio[0])===intval($this->anio)){
            $anioDevuelto=$presupuestoAnio1;
          }

          if($i===1 && (intval($array__FechaInicio[0]) + 1)===intval($this->anio)){
            $anioDevuelto=$presupuestoAnio2;
          }

          if($i===1 && (intval($array__FechaInicio[0]) + 2)===intval($this->anio)){
            $anioDevuelto=$presupuestoAnio3;
          }

          if($i===1 && (intval($array__FechaInicio[0]) + 3)===intval($this->anio)){
            $anioDevuelto=$presupuestoAnio4;
          }

      }

      return $anioDevuelto;

    }


    public function montos__anuales__existentes__verificar($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND idNivel1 IS NOT NULL AND total>0 AND anio='".$this->anio."' GROUP BY codigo;");

      foreach ($consulta as $valor) {
        $total=$valor["total"];
      }

      return $total;

    }



    public function comparar__montos__sumador__certificacion($codigo,$anioAnualPermitido,$monto){

      $sumador=0;

      $consulta=$this->constructor->select__general__incentivo("SELECT subotal FROM proyecto_certificacion_factura_tramite WHERE (estado='A' OR estado='P') AND codigo='$codigo' AND YEAR(fechaEmision)='".$this->anio."' GROUP BY codigo;");

      foreach ($consulta as $valor) {
        $subotal=$valor["subotal"];
      }

      $sumador=floatval($monto) + floatval($subotal);

      if(floatval($sumador) > floatval($anioAnualPermitido)){
        return 1;
      }else{
        return 0;
      }

    }   



    public function consultar__monto__certificacion__v1($post){

      $codigoTramite=$post["codigoTramite"];
      $valorTraido=$post["valor"];

      foreach ($this->sumaCertificacion($codigoTramite) as $valor) {
        $sumaCertificacion=$valor["sumaCertificacion"];
      }

      if (empty($sumaCertificacion)) {
        $sumaCertificacion=0;
      }


      $verificarValorAnual=$this->montos__anuales__existentes__v1__verificar($codigoTramite);



      $sumaCF=floatval($sumaCertificacion) + floatval($valorTraido) + floatval($this->sumaCertificacion__v1($codigoTramite));


      // if(intval($this->comparar__montos__sumador__certificacion($codigoTramite,$verificarValorAnual,$valorTraido))===1){
      //   return ["1",$sumaCF,$totalSuma];
      // }else 

      if (floatval($sumaCF) > floatval($this->sumaProyectoTotal__v1($codigoTramite))) {
        return [false,$sumaCF,$totalSuma];
      }else{
        return [true,$sumaCF,$totalSuma];
      }

    }   

    public function consultar__monto__certificacion($post){

    	$codigoTramite=$post["codigoTramite"];
    	$valorTraido=$post["valor"];

    	foreach ($this->sumaCertificacion($codigoTramite) as $valor) {
    		$sumaCertificacion=$valor["sumaCertificacion"];
    	}

    	if (empty($sumaCertificacion)) {
    		$sumaCertificacion=0;
    	}

    	foreach ($this->sumaProyectoTotal($codigoTramite) as $valor) {
    		$totalSuma=$valor["totalSuma"];
    	}

    	$sumaCF=floatval($sumaCertificacion) + floatval($valorTraido);

      $verificarValorAnual=$this->montos__anuales__existentes__verificar($codigoTramite);

    	if(intval($this->comparar__montos__sumador__certificacion($codigoTramite,$verificarValorAnual,$valorTraido))===1){
        return ["1",$sumaCF,$totalSuma];
      }else if (floatval($sumaCF) > floatval($totalSuma)) {
    		return [false,$sumaCF,$totalSuma];
    	}else{
    		return [true,$sumaCF,$totalSuma];
    	}

    }   

     public function obtener__cuantos__tramies($post){

    	$codigoTramite=$post["codigoTramite"];

    	$consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigoTramite' GROUP BY codigo;");

    	foreach ($consulta as $valor) {
    		$cuantos=$valor["cuantos"];
    	}

    	if (empty($cuantos)) {
    		return 1;
    	}else{
    		return ($cuantos + 1);
    	}

    }   

     public function informacionCertificacion__v1($post){

      $input=$post["input"];
      $idCredencial=$post["idCredencial"];

      return $this->constructor->select__general__incentivo("SELECT id,informacion FROM certificacion_informacion WHERE estado='A';");

    }   


     public function informacionCertificacion($post){

    	$input=$post["input"];
    	$idCredencial=$post["idCredencial"];

    	return $this->constructor->select__general__incentivo("SELECT id,informacion FROM certificacion_informacion WHERE estado='A';");

    }   


     public function buscar__proyecto__existente__v1($post){

      $input=$post["input"];
      $idCredencial=$post["idCredencial"];


      $consulta=$this->constructor->select__general("SELECT IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)) AS usuario FROM credencial AS a WHERE a.idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $usuario=$valor["usuario"];
      }

      return $this->constructor->select__general__talento("SELECT a.idTramite AS a, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombre,(SELECT IF(a1.mensajePlurianual='normal' OR a1.mensajePlurianual=' ' OR a1.mensajePlurianual='' OR a1.mensajePlurianual IS NULL,'ANUAL','PLURIANUAL') FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=a.codigo ORDER BY idProyectoReferencias DESC LIMIT 1)  AS tipo,a.codigo, a.codigo AS codigoUsuario,(SELECT STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y') FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=a.codigo ORDER BY idProyectoReferencias DESC LIMIT 1) AS anioInicio,(SELECT STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y') FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=a.codigo ORDER BY idProyectoReferencias DESC LIMIT 1) AS anioFin FROM pro_proyecto AS a WHERE (a.codigo='$input' OR a.nombre LIKE '%$input%') AND a.idUsuario LIKE '%$usuario%' AND a.califica='A';");

    }   

     public function buscar__proyecto__existente($post){

    	$input=$post["input"];
    	$idCredencial=$post["idCredencial"];

    	return $this->constructor->select__general__incentivo("SELECT a.id,b.nombre,IF(b.tipo='ANUAL' OR b.tipo IS NULL,'ANUAL','PLURIANUAL') AS tipo,a.codigo,a.codigoUsuario,YEAR(b.fechaInicio) AS anioInicio, YEAR(b.fechaFin) AS anioFin FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE (a.codigo='$input' OR b.nombre LIKE '%$input%') AND a.idCredencial='$idCredencial'  AND a.estadoCalificacion='CALIFICADO' AND estadoSeguimiento IS NULL  ORDER BY a.id DESC LIMIT 1;");

    }   

    public function proyectos__aprobados__codigo__v1($post){

      $idCredencial=$post["idCredencial"];


      $consulta=$this->constructor->select__general("SELECT IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)) AS usuario FROM credencial AS a WHERE a.idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $usuario=$valor["usuario"];
      }

      return $this->constructor->select__general__talento("SELECT a.codigo FROM pro_proyecto AS a JOIN (SELECT b.codigoProyecto, IF(YEAR(STR_TO_DATE(b.inicioPeriodos, '%d/%m/%Y')) <= YEAR(CURDATE()) AND YEAR(STR_TO_DATE(b.finPeriodos, '%d/%m/%Y')) >= YEAR(CURDATE()), 'SI', 'NO') AS periodo_valido FROM pro_proyetosreferencias AS b WHERE YEAR(STR_TO_DATE(b.inicioPeriodos, '%d/%m/%Y')) <= YEAR(CURDATE())  AND YEAR(STR_TO_DATE(b.finPeriodos, '%d/%m/%Y')) >= YEAR(CURDATE())) AS valid_periods ON a.codigo = valid_periods.codigoProyecto WHERE a.codigo LIKE '%$usuario%' AND a.califica = 'A' AND valid_periods.periodo_valido = 'SI' GROUP BY a.codigo;");

    }    

    public function proyectos__aprobados__codigo($post){

    	$idCredencial=$post["idCredencial"];

    	return $this->constructor->select__general__incentivo("SELECT a.codigo FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE a.idCredencial='$idCredencial' AND a.estadoCalificacion='CALIFICADO' AND estadoSeguimiento IS NULL;");

    }

    public function proyectos__aprobados__nombres__v1($post){

      $idCredencial=$post["idCredencial"];


      $consulta=$this->constructor->select__general("SELECT IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)) AS usuario FROM credencial AS a WHERE a.idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $usuario=$valor["usuario"];
      }

      return $this->constructor->select__general__talento("SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto FROM pro_proyecto AS a JOIN (SELECT b.codigoProyecto, IF(YEAR(STR_TO_DATE(b.inicioPeriodos, '%d/%m/%Y')) <= YEAR(CURDATE()) AND YEAR(STR_TO_DATE(b.finPeriodos, '%d/%m/%Y')) >= YEAR(CURDATE()), 'SI', 'NO') AS periodo_valido FROM pro_proyetosreferencias AS b WHERE YEAR(STR_TO_DATE(b.inicioPeriodos, '%d/%m/%Y')) <= YEAR(CURDATE())  AND YEAR(STR_TO_DATE(b.finPeriodos, '%d/%m/%Y')) >= YEAR(CURDATE())) AS valid_periods ON a.codigo = valid_periods.codigoProyecto WHERE a.codigo LIKE '%$usuario%' AND a.califica = 'A' AND valid_periods.periodo_valido = 'SI' GROUP BY a.codigo;");

    }


    public function proyectos__aprobados__nombres($post){

    	$idCredencial=$post["idCredencial"];

    	return $this->constructor->select__general__incentivo("SELECT UPPER(b.nombre) AS nombreProyecto FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE a.idCredencial='$idCredencial' AND a.estadoCalificacion='CALIFICADO' AND estadoSeguimiento IS NULL;");

    }

    public function techo__presupuestario__general($codigo,$idFactura){

      $consulta=$this->constructor->select__general__incentivo("SELECT estado FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigo' AND id='$idFactura';");

      foreach ($consulta as $valor) {
        $estado=$valor["estado"];
      }

      if($estado==='A'){

        $consulta=$this->constructor->select__general__incentivo("SELECT idPresupuesto FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigo' AND id='$idFactura';");
        foreach ($consulta as $valor) {
          $idPresupuestoBd=$valor["idPresupuesto"];
        }

        return $this->constructor->select__general("SELECT techo,oficio FROM presupuesto_incentivo WHERE id='$idPresupuestoBd' ORDER BY id DESC LIMIT 1;");
      
      }else{

        $consulta=$this->constructor->select__general("SELECT id FROM presupuesto_incentivo WHERE estado='A' ORDER BY id DESC LIMIT 1;");
        foreach ($consulta as $valor) {
          $idBd=$valor["id"];
        }
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET idPresupuesto='$idBd' WHERE codigo='$codigo' AND id='$idFactura';");
        return $this->constructor->select__general("SELECT techo,oficio FROM presupuesto_incentivo WHERE estado='A' ORDER BY id DESC LIMIT 1;");

      }

      
    }    

    public function informacion__proyecto__enviado__certificacion($codigo){

        return $this->constructor->select__general__incentivo("SELECT id,idCredencial FROM proyecto_enviado WHERE codigoUsuario='$codigo';");

    }    

    public function fecha__calificacion__proyecto($idEnviado){

        return $this->constructor->select__general__incentivo("SELECT fechaCalifica AS fecha FROM proyecto_enviado WHERE id='$idEnviado';");

    }    


    public function observable__axios__informacion__facturas($idFactura){

        return $this->constructor->select__general__incentivo("(SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,d.nombre AS nombreProyecto,a.fecha FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo=a.codigo WHERE a.id='$idFactura') UNION (SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto,a.fecha FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=a.codigo WHERE a.id='$idFactura');");

    }    

    public function verificar__certificacion__recomendacion($idFactura){

        $consulta= $this->constructor->select__general__incentivo("SELECT a.id FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo=a.codigo WHERE a.id='$idFactura' AND estadoRecomendacion IS NOT NULL;");

        foreach ($consulta as $valor) {
            $id=$valor["id"];
        }

        if (empty($id)) {
            return 0;
        }else{
            return 1;
        }

    }    

    public function recuperarInformacionUsuario__general__correo($idCredencial){

      $consulta=$this->constructor->select__general("SELECT idTipoUsuario FROM credencial_tipo WHERE idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $idTipoUsuario_bd=$valor["idTipoUsuario"];
      }

      if (intval($idTipoUsuario_bd)===3 || intval($idTipoUsuario_bd)===4 || intval($idTipoUsuario_bd)===5) {
        return $this->constructor->select__general("SELECT a.correo1 AS correoEnvio, b.razonSocial AS nombreUsuario FROM representante AS a INNER JOIN organismo AS b ON a.idCredencial=b.idCredencial WHERE a.idCredencial='$idCredencial';");
      }else{
        return $this->constructor->select__general("SELECT a.email1 AS correoEnvio,b.nombre AS nombreUsuario FROM contacto AS a INNER JOIN usuario AS b ON a.idCredencial=b.idCredencial WHERE a.idCredencial='$idCredencial';");
      }

    }    

    public function observable__axios__informacion__facturas__al__analista($idFactura){

        return $this->constructor->select__general__incentivo("SELECT fecha FROM proyecto_enviado_antecedente WHERE idFactura='$idFactura' ORDER BY id DESC LIMIT 1;");

    }    

    public function obtener__informacion__certifiacion__id__usuario__recomienda($idFactura){

        return $this->constructor->select__general__incentivo("SELECT a.idUsuario,a.idUsuario2,a.texto,a.recomendacion,CONCAT_WS(' ',b.nombre,b.apellido) AS nombreAnalista FROM proyecto_enviado_recomendacion_certificacion AS a INNER JOIN ezonshar_mdepsaddb.th_usuario AS b ON a.idUsuario=b.id_usuario WHERE a.idFactura='$idFactura';");

    }   

    public function obtener__ideEnviado($codigoUsuario){

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigoUsuario';");
      foreach ($consulta as $valor) {
        $id=$valor["id"];
      }

      return $id;

    }   


}
