<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;



class ActaReunion {

    private $anio;
    private static $instance = null;

    public function __construct() {

        $this->constructor =ServicesAdmin::getInstance();
        $this->anio=date('Y');

    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ActaReunion();
        }
        return self::$instance;
    }


    public function actas__cuantas__Anio() {

        $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM comite_acta WHERE YEAR(fecha)='".$this->anio."';");
        foreach ($consulta as $valor) {
            $cuantosBd=$valor["cuantos"];
        }

        $cuantosBd=intval($cuantosBd) + 1;

        return $cuantosBd;

    }

    public function tipo__sesion__comite($idComite) {

      $consulta=$this->constructor->select__general__incentivo("SELECT b.asunto FROM comite AS a INNER JOIN comite_convocatoria AS b ON a.id=b.idComite WHERE a.id='$idComite';");
      foreach ($consulta as $valor) {
        $asuntoBd=$valor["asunto"];
      }

      $asuntoBd = strtolower($asuntoBd);

      $pos = strpos($asuntoBd, "ordi");

      if ($pos !== false) {
        return "ORDINARIA";
      } else {
        return "EXTRAORDINARIA";
      }

    }    

    public function informacion__convocatoria($idComite) {

      return $this->constructor->select__general__incentivo("SELECT idComite,fechaConvocatoria,horaConvocatoria,asunto,ordenDia FROM comite_convocatoria WHERE idComite='$idComite';");

    }    

    public function informacion__acta__del__comite($idComite) {

      return $this->constructor->select__general__incentivo("SELECT lugarActa,horaFinalizacion,ordenDia,desarrollo,primerPunto,asistentes,segundoPunto FROM comite_acta_contenido WHERE idComite='$idComite';");

    }    


    public function informacion__acta__delegados($idComite) {

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario,id_PuestoInstitucional,IF(participa IS NOT NULL,'SI','NO') AS participa FROM comite_delegados WHERE idComite='$idComite' AND participante IS NULL AND idUsuario IS NOT NULL;");

      foreach ($consulta as $valor) {
      
        $consulta__2=$this->constructor->select__general__talento("SELECT a.cedula,UPPER(CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'))) AS nombreCompleto,d.descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='".$valor["idUsuario"]."';");

        foreach ($consulta__2 as $valor__2) {

          if(intval($valor["id_PuestoInstitucional"])===9999999){
            array_push($array, $valor__2["nombreCompleto"]."__"."DELEGADO DE LA MÁXIMA AUTORIDAD"."__".$valor["participa"]);
          }else{
            array_push($array, $valor__2["nombreCompleto"]."__".$valor__2["descripcionPuestoInstitucional"]."__".$valor["participa"]);
          }
         
        }

      }

      return $array;

    }        

    public function informacion__acta__delegados__asistentes($idComite) {

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario,id_PuestoInstitucional,IF(participa IS NOT NULL,'SI','NO') AS participa FROM comite_delegados WHERE idComite='$idComite' AND participante IS NOT NULL;");

      foreach ($consulta as $valor) {
      
        $consulta__2=$this->constructor->select__general__talento("SELECT a.cedula,UPPER(CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'))) AS nombreCompleto,d.descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='".$valor["idUsuario"]."';");

        foreach ($consulta__2 as $valor__2) {
          array_push($array, $valor__2["nombreCompleto"].", ".$valor__2["descripcionPuestoInstitucional"]);
        }

      }

      return $array;

    }       

    public function proyectos__calificados__modificacion($idComite,$rotulo) {

      return $this->constructor->select__general__incentivo("SELECT a.codigo,b.nombre AS nombreProyecto,c.nombre AS proponente,(SELECT SUM(a1.total) FROM proyecto_presupuesto AS a1 WHERE a1.codigo=a.codigoUsuario AND a1.total>0 AND a1.idNivel1 IS NOT NULL GROUP BY a1.codigo) AS sumaProyecto FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo INNER JOIN configuracion.usuario AS c ON c.idCredencial=a.idCredencial WHERE estadoCalificacion='$rotulo' AND idComite='$idComite';");

    }    


    public function proyectos__en__modificacion__acta__de__reunion($idComite) {

      return $this->constructor->select__general__incentivo("SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,c.documento,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,IF(d.casoModificar='a','a) Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(d.casoModificar='b','b) Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','c) Modificaciones de la vigencia del proyecto de anual a plurianual')) AS razonModificacion,IFNULL((SELECT a1.nombre FROM configuracion.usuario AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.razonSocial FROM configuracion.organismo AS a1 WHERE a1.idCredencial=a.idCredencial)) AS proponente FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id INNER JOIN proyecto_modificacion_solicitud AS d ON d.codigo=a.codigoUsuario WHERE c.idComite = '$idComite' AND c.modulo='MODIFICACION';");

    }    

    public function proyectos__en__certificacion__acta__de__reunion($idComite) {

      return $this->constructor->select__general__incentivo("(SELECT DISTINCT a.id, a.codigo, UPPER(b.nombre) AS nombreProyecto, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,c.documento,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),'NO') AS version,IFNULL((SELECT a1.idIncremental FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),0) AS idIncremental,IFNULL((SELECT a1.nombre FROM configuracion.usuario AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.razonSocial FROM configuracion.organismo AS a1 WHERE a1.idCredencial=a.idCredencial)) AS proponente,(SELECT a2.nombre FROM proyecto_sector AS a1 INNER JOIN sector AS a2 ON a1.idSector=a2.idSector WHERE a1.codigo=a.codigoUsuario ORDER BY a1.id DESC LIMIT 1) AS sector, (SELECT  SUM(a1.total) AS totalSuma FROM proyecto_presupuesto AS a1 WHERE a1.codigo=a.codigoUsuario AND a1.nivel!=0 GROUP BY a1.codigo) AS montoProyecto,e.razonSocial,IFNULL((SELECT CONCAT_WS('',a1.cedula,'001') AS rucProponenteIdentificado FROM configuracion.usuario AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.ruc FROM configuracion.organismo AS a1 WHERE a1.idCredencial=a.idCredencial)) AS rucProponenteIdentificado,d.numeroFactura,d.subotal,d.fechaEmision FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id INNER JOIN proyecto_certificacion_factura_tramite AS d ON d.codigo=a.codigoUsuario INNER JOIN certificacion_patrocinadores AS e ON e.id=d.idPatrocinador WHERE c.idComite = '$idComite' AND c.modulo='CERTIFICACION') UNION (SELECT DISTINCT a.id, a.codigo, UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') )  AS nombreProyecto, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigo AS codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,c.documento,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),'NO') AS version,IFNULL((SELECT a1.idIncremental FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=a.codigo LIMIT 1),0) AS idIncremental,IFNULL((SELECT a1.nombre FROM configuracion.usuario AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.razonSocial FROM configuracion.organismo AS a1 WHERE a1.idCredencial=a.idCredencial)) AS proponente, IF(b.tipoDeportistas = 'alto' OR b.tipoDeportistas = 'alto2' OR b.tipoDeportistas = 'altoRendimiento' OR b.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(b.tipoDeportistas = 'actividadFisica', UPPER('Educacion Fisica'), IF(b.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(b.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreacion'))))) AS sector, b.monto AS montoProyecto,e.razonSocial,IFNULL((SELECT CONCAT_WS('',a1.cedula,'001') AS rucProponenteIdentificado FROM configuracion.usuario AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.ruc FROM configuracion.organismo AS a1 WHERE a1.idCredencial=a.idCredencial)) AS rucProponenteIdentificado,d.numeroFactura,d.subotal,d.fechaEmision FROM proyecto_enviado AS a INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS b ON a.codigo = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id INNER JOIN proyecto_certificacion_factura_tramite AS d ON d.codigo=b.codigo INNER JOIN certificacion_patrocinadores AS e ON e.id=d.idPatrocinador WHERE c.idComite = '$idComite' AND c.modulo='CERTIFICACION' GROUP BY a.id);");

    }    


    public function proyectos__calificados__negados($idComite,$rotulo) {

      return $this->constructor->select__general__incentivo("SELECT a.codigo,b.nombre AS nombreProyecto,IFNULL(c.nombre,d.razonSocial) AS proponente,(SELECT SUM(a1.total) FROM proyecto_presupuesto AS a1 WHERE a1.codigo=a.codigoUsuario AND a1.total>0 AND a1.idNivel1 IS NOT NULL GROUP BY a1.codigo) AS sumaProyecto FROM proyecto_enviado AS a LEFT JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN configuracion.usuario AS c ON c.idCredencial=a.idCredencial LEFT JOIN configuracion.organismo AS d ON d.idCredencial=a.idCredencial WHERE estadoCalificacion='$rotulo' AND idComite='$idComite' GROUP BY b.nombre ORDER BY a.codigo DESC;");

    }    


    public function secretarioComite__logueado($idCredencial) {

      $consulta=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($consulta as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      $consulta__2=$this->constructor->select__general("SELECT CONCAT_WS(' ', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto FROM ezonshar_mdepsaddb.th_usuario WHERE id_usuario='$idUsuarioBd';");
      foreach ($consulta__2 as $valor__2) {
        $nombreCompleto=$valor__2["nombreCompleto"];
      }

      return $nombreCompleto;

    }    



    public function formatearFecha($fecha) {

        date_default_timezone_set("America/Guayaquil");

        $timestamp = strtotime($fecha);

        $anio = date('Y', $timestamp);
        $dia = date('d', $timestamp);

        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $numeroMes = (int)date('m', $timestamp);
        $mes = $meses[$numeroMes];

        return "$dia de $mes del $anio";
    }


    public function informacion__acta__quinto__punto($idComite,$idCredencial=null) {

      $consulta=$this->informacion__acta__del__comite($idComite);


       $consulta__2=$this->informacion__convocatoria($idComite);
       foreach ($consulta__2 as $valor) {
         $fechaConvocatoriaBd=$valor["fechaConvocatoria"];
         $horaConvocatoriaBd=$valor["horaConvocatoria"];
       }


      foreach ($consulta as $valor) {
         $lugarActaBd=$valor["lugarActa"];
         $horaFinalizacionBd=$valor["horaFinalizacion"];
         $ordenDiaBd=$valor["ordenDia"];
         $desarrolloBd=$valor["desarrollo"];
         $primerPuntoBd=$valor["primerPunto"];
       }

      $array=$this->informacion__acta__delegados($idComite);



      $htm="

        <div class='w-full text-12 mt-2 texto-justificado'>
           <span class='font-bold'>QUINTO PUNTO. –</span>  Lineamientos o directrices en los procesos de calificación, certificación, seguimiento y control. 
        </div>";

      $htm="

        <div class='w-full text-12 mt-2 font-bold texto-justificado'>
           RESOLUCIONES DEL COMITE DE CALIFICACIÓN Y CERTIFICACIÓN PARA ACCEDER AL INCENTIVO TRIBUTARIO. 
        </div>";


      $htm="

        <div class='w-full text-12 mt-2 font-bold texto-justificado'>
           El Comité resuelve por unanimidad lo siguiente: 
        </div>";

      $htm="

        <div class='w-full text-12 mt-2 texto-justificado'>
           <span class='font-bold'>PRIMERA. –</span> Los miembros del Comité en ese sentido RESUELVEN: APROBAR por Unanimidad el Orden del día propuesto, en la convocatoria enviada mediante memorando Nro. MD-DAJ-2024-0737-M, de 12 de julio de 2024. 
        </div>";

      $htm="

        <div class='w-full text-12 mt-1 texto-justificado'>
           <span class='font-bold'>SEGUNDA. -</span> Calificar los siguientes programas y/o proyectos deportivos, ingresados a las diferentes áreas técnicas, de acuerdo al siguiente detalle: 
        </div>";


      $htm="

        <div class='w-full text-12 mt-1 texto-justificado'>
           <span class='font-bold'>TERCERA. -</span>  Certificar Acorde los informes previos a la certificación presentados por la Dirección Financiera, encargados del levantamiento de información y presentada mediante el memorando Nro. MD-DF-2024-1013-M, de 15 de julio de 2024, la cual fue generada para la emisión del proceso de certificación y que fue levantado de conformidad a la normativa aplicable, y con énfasis a lo establecido en los artículos 46 y 47 del Acuerdo Ministerial Nro. 0243, de fecha 21 de noviembre de 2023. Los miembros del comité luego del análisis respectivo proceden a certificar.
        </div>";



      $htm="

        <div class='w-full text-12 mt-1 texto-justificado'>
           Sin más temas que tratar se levanta la sesión siendo las $horaFinalizacionBd con fecha ".$this->formatearFecha($fechaConvocatoriaBd)." 
        </div>";


      $htm="

        <div class='w-full text-12 mt-1 texto-justificado'>
           Para constancia de lo actuado firman la presente acta los miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario. 
        </div>";

      $htm.= "

          <table class='styled-table mt-2'>

          <thead>

            <tr>

              <td class='font-bold small-column font-size-8px' align='center'>MIEMBROS</td>
              <td class='font-bold small-column font-size-8px' align='center'>CARGO</td>

            </tr>

          </thead>

          <tbody>
        ";


        foreach ($array as $valor) {

          $arrayString = explode('__', $valor);

          
          $htm.="

            <tr>

              <td class='small-column font-size-8px'>".$arrayString[0]."</td>
              <td class='small-column font-size-8px'>".$arrayString[1]."</td>
              
            </tr>

          ";

        }

        $htm.= "

          <tr>

            <td class='font-bold small-column font-bold' align='center' colspan='2'>Secretaría de comité</td>

          </tr>


          <tr>

            <td colspan='1'>
              ".$this->secretarioComite__logueado($idCredencial)." 
            </td>

            <td colspan='1'></td>

          </tr>


          </tbody>

          </table>

        ";


      return $htm;

    }


    public function informacion__acta__cuarto__punto($idComite) {

      if (count($this->proyectos__en__certificacion__acta__de__reunion($idComite,'CALIFICADO'))>0) {

        $sumador=0;


        $htm="

        <div class='w-full text-12 mt-2'>
           <span class='font-bold'> CUARTO PUNTO. - </span> Conocimiento y análisis de los informes emitidos por la Dirección Financiera, en el cual recomiendan o no la emisión de certificación de beneficiarios para acceder al Incentivo Tributario, conforme el literal b) del artículo 4 del Acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023: 
        </div>";


        $htm.= "

          <table class='styled-table mt-2'>

          <thead>

            <tr>

              <td class='font-bold small-column font-size-8px' align='center'>POSTULANTE O SOLICITANTE DEL PROYECTO</td>
              <td class='font-bold small-column font-size-8px' align='center'>PROYECTO</td>
              <td class='font-bold small-column font-size-8px' align='center'>SECTOR AL QUE CONTRIBUYE</td>
              <td class='font-bold small-column font-size-8px' align='center'>MONTO DEL PROYECTO</td>
              <td class='font-bold small-column font-size-8px' align='center'>NOMBRE DEL PATROCINADOR </td>
              <td class='font-bold small-column font-size-8px' align='center'>RUC DEL RECEPTOR</td>
              <td class='font-bold small-column font-size-8px' align='center'>NÚMERO DEL COMPROBANTE DE VENTA</td>
              <td class='font-bold small-column font-size-8px' align='center'>MONTO SUBTOTAL</td>
              <td class='font-bold small-column font-size-8px' align='center'>FECHA DE EMISIÓN DEL COMPROBANTE </td>

            </tr>

          </thead>

          <tbody>
        ";

         foreach ($this->proyectos__en__certificacion__acta__de__reunion($idComite,'CALIFICADO') as $valor) {
          
          $htm.="

            <tr>

              <td class='small-column font-size-8px'>".$valor["proponente"]."</td>
              <td class='small-column font-size-8px'>".$valor["nombreProyecto"]."</td>
              <td class='small-column font-size-8px'>".$valor["sector"]."</td>
              <td class='small-column font-size-8px'>".number_format((float)$valor["montoProyecto"], 2, '.', ',')."</td>
              <td class='small-column font-size-8px'>".$valor["razonSocial"]."</td>
              <td class='small-column font-size-8px'>".$valor["rucProponenteIdentificado"]."</td>
              <td class='small-column font-size-8px'>".$valor["numeroFactura"]."</td>
              <td class='small-column font-size-8px'>".number_format((float)$valor["subotal"], 2, '.', ',')."</td>
              <td class='small-column font-size-8px'>".$valor["fechaEmision"]."</td>
              
            </tr>

          ";

          $sumador=floatval($sumador) + floatval($valor["subotal"]);

         }


         $htm.= "

          </tbody>

          </table>

         ";


        $htm.="

          <div class='w-full text-12 mt-2 texto-justificado'>
            Asimismo, la Dirección Financiera menciona que se ha procedido a verificar la validez de los citados comprobantes de venta en el portal WEB del Servicio de Rentas Internas, y se ha determinado que los comprobantes de venta son VÁLIDOS, conforme las disposiciones emitidas en el Capítulo III, artículo 18 del Reglamento de comprobantes de venta, retención y documentos complementarios, que establece los REQUISITOS Y CARACTERÍSTICAS DE LOS COMPROBANTES DE VENTA, NOTAS DE CRÉDITO Y NOTAS DE DEBITO y CUMPLEN con lo establecido en los artículos 45, 47 y 48 del Acuerdo Ministerial Nro. 0243, de fecha 21 de noviembre de 2023.  
          </div>";


        $htm.="

          <div class='w-full text-12 mt-1 texto-justificado'>
            Sobre lo expuesto, y una vez que se ha verificado que el/los comprobantes han sido llenados correctamente, la Coordinación Administrativa Financiera, a través de la Dirección Financiera, recomienda la emisión de la certificación por parte del Comité.
          </div>";


        $htm.="

          <div class='w-full text-12 mt-2 texto-justificado'>
            Total, monto a certificar es <span class='font-bold'>".number_format((float)$sumador, 2, '.', ',')."</span>
          </div>";


        $htm.="

          <div class='w-full text-12 mt-2 texto-justificado'>
           En virtud de los Informes favorables emitidos por la Dirección Financiera los miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario deliberan; y, mediante votación unánime, RESUELVEN: Certificar a los patrocinadores de los proyectos enunciados por la Dirección Financiera y se procederá a emitir el certificado correspondiente. 
          </div>";



      }



      return $htm;

    }


    public function informacion__acta__tercer__punto($idComite) {


      $htm="

        <div class='w-full text-12 mt-2'>
           <span class='font-bold'>TERCER PUNTO.-</span> Conocimiento de los programas y/o proyectos deportivos, ingresados a las diferentes áreas técnicas; para calificar o negar los mismos conforme el literal b) del artículo 4 del Acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023; y, conocimiento de los Informes de las áreas técnicas en relación a los programas o proyectos deportivos que no cumplieron con el artículo 36 del mencionado acuerdo: 
        </div>";

      if (count($this->proyectos__calificados__negados($idComite,'CALIFICADO'))>0) {

        $htm.="  <div class='w-full text-12 mt-2'>
             <span class='font-bold'>Proyectos Calificados:</span> 
          </div>

          <div class='w-full text-12 mt-2'>
             Los miembros del comité luego de la revisión pertinente y una vez que se ha constatado el cumplimiento de los parámetros establecidos en la normativa legal vigente, resuelven por mayoría de votos CALIFICAR los siguientes proyectos: 
          </div>

         ";


        $htm.= "

          <table class='styled-table mt-2'>

          <thead>

            <tr>
              <td class='font-bold' align='center'>CÓDIGO</td>
              <td class='font-bold' align='center'>PROYECTO</td>
              <td class='font-bold' align='center'>PROPONENTE</td>
              <td class='font-bold' align='center'>MONTO</td>
            </tr>

          </thead>

          <tbody>
        ";

         foreach ($this->proyectos__calificados__negados($idComite,'CALIFICADO') as $valor) {
          
          $htm.="

            <tr>

              <td>".$valor["codigo"]."</td>
              <td>".$valor["nombreProyecto"]."</td>
              <td>".$valor["proponente"]."</td>
              <td>".number_format((float)$valor["sumaProyecto"], 2, '.', ',')."</td>
              
            </tr>

          ";

         }


         $htm.= "

          </tbody>

          </table>

         ";

      }


       if (count($this->proyectos__calificados__negados($idComite,'NEGADO'))>0) {

        $htm.="

          <div class='w-full text-12 mt-2'>
             <span class='font-bold'>Proyectos Negados: </span> 
          </div>

          <div class='w-full text-12 mt-2'>
             Los miembros del comité luego de la revisión pertinente y una vez que se ha constatado el incumplimiento de los parámetros establecidos en la normativa legal vigente, resuelven por mayoría de votos NEGAR los siguientes proyectos: 
          </div>

         ";

        $htm.= "

          <table class='styled-table mt-2'>

          <thead>

            <tr>
              <td class='font-bold' align='center'>CÓDIGO</td>
              <td class='font-bold' align='center'>PROYECTO</td>
              <td class='font-bold' align='center'>PROPONENTE</td>
              <td class='font-bold' align='center'>MONTO</td>
            </tr>

          </thead>

          <tbody>
        ";

         foreach ($this->proyectos__calificados__negados($idComite,'NEGADO') as $valor) {
          
        $htm.="

            <tr>

              <td>".$valor["codigo"]."</td>
              <td>".$valor["nombreProyecto"]."</td>
              <td>".$valor["proponente"]."</td>
              <td>".number_format((float)$valor["sumaProyecto"], 2, '.', ',')."</td>
              
            </tr>

          ";

         }


        $htm.= "

          </tbody>

          </table>

         ";


       }

      if (count($this->proyectos__en__modificacion__acta__de__reunion($idComite))>0) {


        $htm.="

          <div class='w-full text-12 mt-2'>
             <span class='font-bold'>Proyectos para modificar: </span> 
          </div>

          <div class='w-full text-12 mt-2'>
             Los miembros del comité luego de la revisión pertinente y una vez que se ha constatado el cumplimiento de los parámetros establecidos en la normativa legal vigente, resuelven por mayoría de votos calificar los siguientes proyectos: 
          </div>

         ";


          $htm.= "

            <table class='styled-table mt-2'>

            <thead>

              <tr>
                <td class='font-bold' align='center'>CÓDIGO</td>
                <td class='font-bold' align='center'>PROYECTO</td>
                <td class='font-bold' align='center'>PROPONENTE</td>
                <td class='font-bold' align='center'>RAZÓN DE LA MODIFICACIÓN</td>
              </tr>

            </thead>

            <tbody>
          ";

           foreach ($this->proyectos__en__modificacion__acta__de__reunion($idComite) as $valor) {
            
          $htm.="

              <tr>

                <td>".$valor["codigo"]."</td>
                <td>".$valor["nombre"]."</td>
                <td>".$valor["proponente"]."</td>
                <td>".$valor["razonModificacion"]."</td>
                
              </tr>

            ";

           }


          $htm.= "

            </tbody>

            </table>

           ";


      }

      return $htm;

    }

    public function informacion__acta__segundo__punto($idComite) {


       $consulta=$this->informacion__acta__del__comite($idComite);

       foreach ($consulta as $valor) {
         $segundoPuntoBd=$valor["segundoPunto"];
       }

      $htm="

        <div class='w-full text-12 mt-2'>
          <span class='font-bold'>SEGUNDO PUNTO.–</span> Información del saldo disponible para certificación de programas y proyectos deportivos calificados para el Incentivo Tributario, por parte de la Dirección de Seguimientos de Planes, Programas y Proyectos; según lo establecido en el artículo 10 inciso segundo del Acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023: 
        </div>

        <div class='w-full text-12 mt-2'>
          ".nl2br($segundoPuntoBd)."
        </div>


       ";

       return $htm;

    }

    public function informacion__acta__asistentes($idComite) {


       $consulta=$this->informacion__acta__del__comite($idComite);

       foreach ($consulta as $valor) {
         $asistentesBd=$valor["asistentes"];
       }

       $htm="

        <div class='w-full text-12 mt-2'>
          $asistentesBd
        </div>


        <div class='w-full text-12 mt-2 font-bold'>
          (DETALLE DE ASISTENTES) 
        </div>


       ";

       $clave=0;

       foreach ($this->informacion__acta__delegados__asistentes($idComite) as  $valor) {

        $clave++;

        $htm.="

        <div class='w-full text-12'>
          $clave. $valor
        </div>

       ";

       }

       return $htm;

    }


    public function acta__participantes__delegados($idComite) {


      $htm= "

        <table class='styled-table mt-2'>

        <thead>

          <tr>
            <td class='font-bold' align='center'>NOMBRES</td>
            <td class='font-bold' align='center'>MIEMBROS</td>
            <td class='font-bold' align='center'>AISTENCIA</td>
          </tr>

        </thead>

        <tbody>
      ";

      foreach ($this->informacion__acta__delegados($idComite) as $valor) {
      
      $array = explode("__", $valor);

      $htm.="

        <tr>

          <td>".$array[0]."</td>
          <td>".$array[1]."</td>
          <td>".$array[2]."</td>

        </tr>

      ";

      }


       $htm.= "

        </tbody>

        </table>

       ";

      return $htm;


    }


    public function acta__portada($idComite) {


      $numeroDeSesion=$this->actas__cuantas__Anio();
      $numeroFormateado = ($numeroDeSesion >= 1 && $numeroDeSesion <= 10) ? sprintf('%03d', $numeroDeSesion) : (($numeroDeSesion >= 11 && $numeroDeSesion <= 99) ? sprintf('%02d', $numeroDeSesion) : (string) $numeroDeSesion);

       $asunto=$this->tipo__sesion__comite($idComite);

       $consulta__2=$this->informacion__convocatoria($idComite);
       foreach ($consulta__2 as $valor) {
         $fechaConvocatoriaBd=$valor["fechaConvocatoria"];
         $horaConvocatoriaBd=$valor["horaConvocatoria"];
       }

      $htm="

        <div class='font-bold text-center w-full text-12'>
          ACTA Nro - $numeroFormateado - ".$this->anio."
        </div>

        <div class='font-bold text-center w-full text-12 mt-1'>
          SESIÓN $asunto
        </div>

        <div class='font-bold text-center w-full text-12 mt-1'>
          COMITÉ DE CALIFICACIÓN Y CERTIFICACIÓN PARA ACCEDER AL INCENTIVO TRIBUTARIO. 
        </div>

        <hr class='mt-2 border-3'>

      ";

      return $htm;


    }


    public function acta__desarrollo($idComite) {


       $consulta=$this->informacion__acta__del__comite($idComite);

       $consulta__2=$this->informacion__convocatoria($idComite);
       foreach ($consulta__2 as $valor) {
         $fechaConvocatoriaBd=$valor["fechaConvocatoria"];
         $horaConvocatoriaBd=$valor["horaConvocatoria"];
       }


       foreach ($consulta as $valor) {
         $lugarActaBd=$valor["lugarActa"];
         $horaFinalizacionBd=$valor["horaFinalizacion"];
         $ordenDiaBd=$valor["ordenDia"];
         $desarrolloBd=$valor["desarrollo"];
         $primerPuntoBd=$valor["primerPunto"];
       }

      $htm="
        <div class='w-full text-12 mt-2'>
          <span class='font-bold'>Lugar:</span>&nbsp;$lugarActaBd
        </div>

       <div class='w-full text-12'>
          <span class='font-bold'>Fecha:</span>&nbsp;$fechaConvocatoriaBd
        </div>

       <div class='w-full text-12'>
          <span class='font-bold'>Hora de Inicio:</span>&nbsp;$horaConvocatoriaBd
        </div>

       <div class='w-full text-12'>
          <span class='font-bold'>Hora de Finalización:</span>&nbsp;$horaFinalizacionBd
        </div>

        <div class='w-full text-12 mt-4 font-bold'>
          ORDEN DEL DÍA
        </div>

        <div class='w-full text-12 mt-2'>
          ".nl2br($ordenDiaBd)."
        </div>

        <div class='w-full text-12 mt-2 font-bold'>
          DESARROLLO: ASPECTOS PRINCIPALES DE LOS DEBATES Y DELIBERACIONES: 
        </div>

        <div class='w-full text-12 mt-1'>
          ".nl2br($desarrolloBd)."
        </div>

        <div class='w-full text-12 mt-2 font-bold'>
          PRIMER PUNTO. - Constatación de quórum:  
        </div>

        <div class='w-full text-12 mt-1'>
          ".nl2br($primerPuntoBd)."
        </div>

      ";

      return $htm;

    }

}