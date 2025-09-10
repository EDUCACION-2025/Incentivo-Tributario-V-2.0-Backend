<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;
use App\utilities\validaciones\NumerosLetras;

class Notificacion {

    private $anio;
    private static $instance = null;

    public function __construct() {


        date_default_timezone_set("America/Guayaquil");

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

        $this->constructor = ServicesAdmin::getInstance();
        $this->anio=date('Y');
        $this->numerosLetras = NumerosLetras::getInstance();

    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Notificacion();
        }
        return self::$instance;
    }


    public function informacionProyecto__datosGenerales__v1($codigo) {

        return $this->constructor->select__general__talento("SELECT UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto, IFNULL((SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombreOrganismo, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombreCompleto, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS nombreSolicitante, IFNULL((SELECT a1.rucOrganismo FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.cedulaUsuario FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS credencialSolicitante, IFNULL((SELECT a1.email FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.email FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS correoSolicitante, IFNULL((SELECT a1.telefono FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.telefono FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS celularSolicitante, IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(a.tipoDeportistas = 'actividadFisica', UPPER('Educación Física'), IF(a.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(a.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreación'))))) AS sector, (SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), '%d '), CASE MONTH(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y')) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), ' %Y')) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaInicio,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica,IFNULL((SELECT CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')) FROM pro_infraselects AS a1 WHERE a1.codigo=a.codigo AND a1.tipoTramite LIKE '%infra%' ORDER BY a1.idProyectoSeleccionas DESC LIMIT 1),' ') AS alineacionInfra, ROUND(SUM(a.monto),2) AS monto,(SELECT COUNT(a1.codigo) FROM pro_beneficiarios_directos AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.codigo) AS beneficiarios,(SELECT IF(a1.mensajePlurianual IS NULL OR a1.mensajePlurianual='normal','NO','SI') AS plurianual FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS plurianual,(SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), '%d '), CASE MONTH(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y')) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), ' %Y')) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaFin,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.objetivoGeneralCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS objetivoGeneral,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.justificacionCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS justificacion,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad',2, IF(a.tipoDeportistas = 'actividadFisica',4, IF(a.tipoDeportistas = 'formativo',1, IF(a.tipoDeportistas = 'profesional', 3, 5)))) AS idSector,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.justificacionCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS justificacionProyecto,IFNULL((SELECT a1.rucOrganismo FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT CONCAT_WS('',a1.cedulaUsuario,'001') FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS rucProponenteIdentificado,a.fechaCalifica FROM pro_proyecto AS a WHERE a.codigo = '$codigo' GROUP BY a.codigo;");


    }


    public function informacionProyecto__datosGenerales($codigo) {

        return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,IF(b.nombre IS NOT NULL, UPPER(b.nombre), UPPER(z.razonSocial)) AS nombreSolicitante,IF(b.nombre IS NOT NULL, UPPER(b.cedula), UPPER(z.ruc)) AS credencialSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.email1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.email1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=z.idCredencial)) AS correoSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.celular1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.celular1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=z.idCredencial)) AS celularSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector,a.justificacionProyecto FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo LEFT JOIN configuracion.organismo AS z ON z.idCredencial=a.idCredencial  WHERE a.codigo='$codigo' GROUP BY a.codigo;");


    }

    public function informacionInicial($idEnviado) {
      return $this->constructor->select__general__incentivo("SELECT UPPER(b.nombre) AS nombre,a.idCredencial,c.numeroComite,c.fecha,c.hora,c.estado,(SELECT SUM(a1.total)  FROM proyecto_presupuesto AS a1 WHERE a.codigoUsuario=a1.codigo AND a1.nivel>0 AND a1.total>0 GROUP BY a1.codigo) AS suma FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo INNER JOIN comite AS c ON c.id=a.idComite WHERE a.id='$idEnviado';");
    }

    public function informacion__fechaComite($idEnviado) {
      return $this->constructor->select__general__incentivo("SELECT fecha FROM comite_proyectos WHERE idEnviado='$idEnviado'  ORDER BY id LIMIT 1;");
    }



    public function organismoPostulante($idCredencial) {

      $consulta=$this->constructor->select__general("SELECT nombre AS proponente FROM usuario WHERE idCredencial='$idCredencial';");
      foreach ($consulta as $valor) {
        $proponenteBd=$valor["proponente"];
      }

      if (!empty($proponenteBd)) {
        return $this->constructor->select__general("SELECT nombre AS proponente FROM usuario WHERE idCredencial='$idCredencial';");
      }else{
        return $this->constructor->select__general("SELECT razonSocial AS proponente FROM organismo WHERE idCredencial='$idCredencial';");
      }

    }    


    public function representantePostulante($idCredencial) {

      return $this->constructor->select__general("SELECT nombre AS representante FROM representante WHERE idCredencial='$idCredencial';");

    }    


    public function proyecto__enviado__comite($idEnviado) {
        return $this->constructor->select__general__incentivo("SELECT idEnviado,idComite,fecha,hora FROM proyecto_enviado_comite_finalizado WHERE id='$idEnviado';");
    }

    public function proyecto__comite__creado($idComite) {
        return $this->constructor->select__general__incentivo("SELECT numeroComite,fecha,hora FROM comite WHERE id='$idComite';");
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


    public function representante__asunto__comite($idEnviado) {

      // $consulta=$this->constructor->select__general__incentivo("SELECT c.asunto FROM proyecto_enviado AS a INNER JOIN comite AS b ON a.idComite=b.id INNER JOIN comite_convocatoria AS c ON c.idComite=b.id WHERE a.id='$idEnviado';");
      $consulta=$this->constructor->select__general__incentivo("SELECT asunto FROM comite_convocatoria WHERE idComite='$idEnviado' AND asunto LIKE '%extra%';");
      foreach ($consulta as $valor) {
        $asuntoBd=$valor["asunto"];
      }

      // $asuntoBd = strtolower($asuntoBd);

      // $pos = strpos($asuntoBd, "ordi");

      if (!empty($asuntoBd)) {
        return "extraordinaria";
      } else {
        return "ordinaria";
      }

    }    



    public function notificacion($idEnviado,$idComite,$estado) {

      $asunto=$this->representante__asunto__comite($idComite);

      foreach ($this->informacionInicial($idEnviado) as $valor) {
        $numeroComiteBd=$valor["numeroComite"];
        $fechaBd=$valor["fecha"];
        $horaBd=$valor["hora"];
        $estadoBd=$valor["estado"];
        $nombreBd=$valor["nombre"];
        $sumaBd=$valor["suma"];
      }

      $asignadorReal=strtolower($this->numerosLetras->toWords($sumaBd));

      $estado=strtoupper($estado);

      if ($estado==="CALIFICADO") {
        $visualizado="CALIFICADO";
      }else{
        $visualizado="NEGADO";
      }

      $htm= '

        <div class="font-bold text-left w-full text-12 mt-2">
          II. NOTIFICACIÓN:  
        </div>


        <div class="mt-2 justify__normal">

          Luego del análisis realizado por el Comité de Calificación y Certificación para acceder al Incentivo Tributario, en sesión '.$asunto.' del '.$fechaBd.' ha resuelto que el proyecto '.$nombreBd.', por un monto de USD $ '.number_format($sumaBd, 2, '.', ',').' ha sido <span class="font-bold">'.$visualizado.'</span>.

        </div>

      ';


      if ($estado==="CALIFICADO") {
        
        $htm.='


        <div class="mt-1 justify__normal">

          Por consiguiente; el proceso de certificación deberá efectuarse de conformidad con lo establecido en el Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023. 

        </div>

        <div class="mt-1 justify__normal">

          En este sentido, se le recuerda al solicitante que, la emisión de la calificación de prioridad de programas y/o proyectos deportivos no constituye obligación ni derecho adquirido alguno para que el Ministerio del Deporte emita la certificación de beneficiarios de la deducibilidad, proceso que se efectuará siempre y cuando se cuente con el monto aprobado por el ente rector de las finanzas públicas a través del dictamen correspondiente. 

        </div>

        <div class="mt-1 justify__normal">

          Una vez calificado la prioridad del proyecto deportivo, si el solicitante desea realizar modificaciones a los componentes del proyecto, este deberá dar cumplimiento a los artículos 40, 41 y 42 del Acuerdo Ministerial Nro. 0243, de 21 de noviembre del 2023 y sus reformas. 

        </div>

        <div class="mt-1 justify__normal">

          De igual forma, para la emisión de la certificación a favor de beneficiarios de la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta, se respetará el orden de ingreso de las solicitudes de certificación a través del aplicativo informático, así como el monto anual autorizado de conformidad al dictamen emitido por el ente rector de Economía y Finanzas Públicas. 

        </div>

        <div class="mt-1 justify__normal">

          En caso de que se complete dicho monto no se podrán emitir certificaciones adicionales, situación que no constituirá causal para reclamaciones de carácter administrativas o judiciales en contra del Ministerio del Deporte, por lo que, una vez certificado el monto total aprobado por el ente rector de las finanzas públicas al que hace referencia el Reglamento a la Ley de Régimen Tributario Interno, se notificará de dicho particular a los representantes de los programas y/o proyectos deportivos calificados que no lograron obtener la certificación, informando la imposibilidad de emitir nuevas certificaciones dentro del correspondiente ejercicio fiscal. 

        </div>

        <div class="mt-1 justify__normal">

          Es importante señalar que, en el caso de programas o proyectos deportivos plurianuales, el proponente deberá notificar al Ministerio del Deporte su intención de continuar con la ejecución de los mismos, en concordancia a lo establecido en el artículo 38 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, que manifiesta lo siguiente: 

         </div>

         <div class="mt-1 justify__normal">

          “Artículo 38.- De la calificación de programas y/o proyectos plurianuales. - Podrá emitirse la calificación de prioridad de programas y/o proyectos deportivos que contemplen la ejecución de componentes en más de un ejercicio fiscal, siendo cuatro (4) el máximo de años de vigencia de la calificación. Para tal efecto, y en cumplimiento a los requisitos establecidos en cada caso, bastará la realización del procedimiento de solicitud de calificación por una sola vez al inicio de sus actividades, especificando los valores asignados a cada componente por cada ejercicio fiscal. 

         </div>

         <div class="mt-1 justify__normal">

          Durante el primer trimestre de cada año, el/la solicitante deberá confirmar su intención de continuar la ejecución de los programas y/o proyectos deportivos; para tal efecto, a través del aplicativo informático, requerirá al Comité la renovación de la calificación de prioridad. El monitoreo y seguimiento al cumplimiento de este proceso estará a cargo de las áreas técnicas que conocieron de manera inicial el proceso la calificación de prioridad. 

         </div>

         <div class="mt-1 justify__normal">

          En caso de no efectuar el procedimiento establecido en el inciso precedente, la calificación de prioridad del programa y/o proyecto plurianual se eliminará de manera automática sin que medie procedimiento alguno. El/la secretario del Comité será el responsable de efectuar la notificación al peticionario sobre la referida eliminación. La certificación de beneficiarios de la deducibilidad en estos programas y/o proyectos plurianuales seguirá las reglas y procedimientos contemplados en los artículos siguientes.". 

          </div>

          <div class="mt-1 justify__normal">

          De la misma manera se le recuerda que una vez ejecutado el programa y/o proyecto deportivo y generadas las certificaciones, se deberá dar estricto cumplimiento a lo establecido en el artículo 50 del acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023. 

          </div>

          <div class="mt-1 justify__normal">

          Por lo expuesto se le recuerda, su obligación de cumplir la normativa legal vigente expedida para estos casos, librando al Ministerio del Deporte de cualquier reclamación. 

           </div>

           <div class="mt-1 justify__normal">

            Finalmente se señala que el Comité de Calificación y Certificación para acceder al Incentivo Tributario, se ha pronunciado sobre la base de la información presentada y cuyo contenido, veracidad y legitimidad es de exclusiva responsabilidad del peticionario, de conformidad a lo establecido por la Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos; razón por la cual, no se responsabiliza por eventuales inconsistencias que pudieren existir entre la información presentada y los datos originales. 

          </div>

        ';

      }

      $htm.='

        <div class="mt-1 justify__normal">

          Particular que informo para los fines pertinentes. 

        </div>

      ';

      $htm.='


        <div class="mt-1 justify__normal">

          Atentamente,

        </div>

      ';

      return $htm;

    }

    public function portadaInicial__certificacion__v1($codigo,$idEnviado) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales__v1($codigo);
      $sesionOr=$this->representante__asunto__comite($idEnviado);


      $fecha__reunion__comite =$this->formatearFecha($this->fecha);

      $htm= "

        <div class='text-left w-full text-12'>
          <span class='font-bold'>Asunto:</span> NOTIFICACIÓN DE CERTIFICACIÓN DEL PROYECTO ".$informacionGeneral[0]['nombreProyecto']."
        </div>

        <div class='text-left w-full text-10 mt-4'>
          <span class='font-bold'>Sr.</span>
        </div>

        <div class='text-left w-full text-10 mt-1'>
          PROPONENTE O 
        </div>

        <div class='text-left w-full text-10 mt-1'>
          REPRESENTANTE LEGAL
        </div>


        <div class='text-left w-full text-10 mt-1'>
          En su Despacho
        </div>

        <div class='text-left w-full text-10 mt-4'>
          <span class='font-bold'>De mi consideración:</span>
        </div>

        <div class='text-left w-full text-10 mt-1'>
          Por medio del presente, me permito informar lo siguiente:
        </div>

        <div class='text-left w-full text-10 mt-4'>
          <span class='font-bold'>I.  ANTECEDENTE:</span>
        </div>


        <div class='text-left w-full text-10 mt-1'>
          El Comité de Calificación y Certificación para acceder al Incentivo Tributario, llevó a cabo la sesión ".$sesionOr." el ".$fecha__reunion__comite.", dentro de las resoluciones adoptadas se consideró lo establecido en el artículo 49 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023.
        </div>

      ";

      return $htm;


    }

    public function portadaInicial__certificacion($codigo,$idEnviado) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales($codigo);
      $sesionOr=$this->representante__asunto__comite($idEnviado);


      $fecha__reunion__comite =$this->formatearFecha($this->fecha);

      $htm= "

        <div class='text-left w-full text-12'>
          <span class='font-bold'>Asunto:</span> NOTIFICACIÓN DE CERTIFICACIÓN DEL PROYECTO ".$informacionGeneral[0]['nombreProyecto']."
        </div>

        <div class='text-left w-full text-10 mt-4'>
          <span class='font-bold'>Sr.</span>
        </div>

        <div class='text-left w-full text-10 mt-1'>
          PROPONENTE O 
        </div>

        <div class='text-left w-full text-10 mt-1'>
          REPRESENTANTE LEGAL
        </div>


        <div class='text-left w-full text-10 mt-1'>
          En su Despacho
        </div>

        <div class='text-left w-full text-10 mt-4'>
          <span class='font-bold'>De mi consideración:</span>
        </div>

        <div class='text-left w-full text-10 mt-1'>
          Por medio del presente, me permito informar lo siguiente:
        </div>

        <div class='text-left w-full text-10 mt-4'>
          <span class='font-bold'>I.  ANTECEDENTE:</span>
        </div>


        <div class='text-left w-full text-10 mt-1'>
          El Comité de Calificación y Certificación para acceder al Incentivo Tributario, llevó a cabo la sesión ".$sesionOr." el ".$fecha__reunion__comite.", dentro de las resoluciones adoptadas se consideró lo establecido en el artículo 49 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023.
        </div>

      ";

      return $htm;


    }

    public function techo__presupuestario__general(){
        return $this->constructor->select__general("SELECT techo,oficio FROM presupuesto_incentivo WHERE estado='A' ORDER BY id DESC LIMIT 1; ");
    }    

    public function informacion__comtie__existente($idComite){
        return $this->constructor->select__general__incentivo("SELECT numeroComite,fecha FROM comite WHERE id='$idComite';");
    }    

    public function informacionProyecto__datosGenerales__certificacion($codigo) {

        return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,IF(b.nombre IS NOT NULL, UPPER(b.nombre), UPPER(z.razonSocial)) AS nombreSolicitante,IF(b.nombre IS NOT NULL, UPPER(b.cedula), UPPER(z.ruc)) AS credencialSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.email1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.correo1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial)) AS correoSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.celular1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.celular1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial)) AS celularSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, (SELECT SUM(a1.total) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.codigo) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector,a.justificacionProyecto,IF(b.nombre IS NULL,z.ruc,IF((SELECT a1.idRepresentante FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1) IS NOT NULL, CONCAT_WS('001',(SELECT a1.cedula FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)),CONCAT_WS('001',b.cedula))) AS rucProponenteIdentificado  FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo LEFT JOIN configuracion.organismo AS z ON z.idCredencial=a.idCredencial WHERE a.codigo='$codigo' GROUP BY a.codigo;");


    }


    public function fecha__calificacion__proyecto($idEnviado){
      return $this->constructor->select__general__incentivo("SELECT fecha FROM proyecto_enviado_comite_finalizado WHERE idEnviado='$idEnviado';");
    }    


    public function fecha__calificacion__proyecto__v1($codigo){
      return $this->constructor->select__general__incentivo("SELECT fechaCalifica AS fecha FROM ezonshar_mdepsaddb.pro_proyecto WHERE codigo='$codigo';");
    }    

    public function observable__axios__informacion__facturas($idFactura){

        return $this->constructor->select__general__incentivo("SELECT a.id, a.codigo AS codigoUsuario, b.ruc, b.razonSocial, b.regimen, a.numeroFactura, a.fechaEmision, a.subotal, (CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal * 0.15 ELSE a.iva END) AS iva, (CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal + (a.subotal * 0.15) ELSE a.total END) AS total, a.ivaPorcentaje, a.tipoComprobante, a.gastoRealizar, a.idIncremental, a.fecha, a.hora, c.codigo AS codigoProyecto, d.nombre AS nombreProyecto, a.fecha, a.rucXml, a.razonSocialXml FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador = b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario = a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo = a.codigo WHERE a.id = '$idFactura';");

    }    


    public function observable__axios__informacion__facturas__v1($idFactura){

        return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal, (CASE WHEN a.gastoRealizar = 'auspicio' AND a.iva=0 THEN a.subotal * 0.15 ELSE a.iva END) AS iva, (CASE WHEN a.gastoRealizar = 'auspicio'  AND a.iva=0 THEN a.subotal + (a.subotal * 0.15) ELSE a.total END) AS total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto,a.fecha,a.rucXml,a.razonSocialXml FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=a.codigo WHERE a.id='$idFactura';");

    }    

    public function nombres__miembros__comite($idComite){

        return $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombre,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS cargo,IF(a.delegado='A',1,0) AS presidenteComite FROM comite_delegados AS a INNER JOIN ezonshar_mdepsaddb.th_usuario AS b ON a.idUsuario=b.id_usuario INNER JOIN ezonshar_mdepsaddb.th_puestoinstitucional AS c ON b.puestoInstitucional=c.id_PuestoInstitucional WHERE a.idComite='$idComite' ORDER BY a.delegado DESC;");

    }    

    public function secretario__comite($idComite){

        return $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS secretariaComite FROM comite AS a INNER JOIN configuracion.funcionario AS b ON a.idCredencial=b.idCredencial INNER JOIN ezonshar_mdepsaddb.th_usuario AS c ON c.id_usuario=b.idUsuario WHERE a.id='$idComite';");

    }    



    public function condiciones__comprobante__v1($idFactura,$codigo,$idComite) {


      $informacionGeneral=$this->observable__axios__informacion__facturas__v1($idFactura);
      $informacionGeneral__2=$this->informacionProyecto__datosGenerales__v1($codigo);

      foreach ($this->informacion__comtie__existente($idComite) as $valor) {
          $numeroComite=$valor["numeroComite"];
          $fecha=$valor["fecha"];
      }



      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          4. EMISIÓN DE LA CERTIFICACIÓN y FECHA DE EXPEDICIÓN:
        </div>


        <div class='texto-justificado w-full text-10 mt-1'>
          El Comité de Calificación y Certificación, para acceder al Incentivo Tributario, mediante sesión llevada a cabo el $fecha <span class='font-bold'>CERTIFICA</span> al patrocinador, gestor de la promoción o publicidad ".$informacionGeneral[0]['razonSocial']." para aplicar la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta de los gastos de patrocinio o publicidad realizados a favor del proyecto: ".$informacionGeneral__2[0]['nombreProyecto'].", dicha aprobación fue realizada en el aplicativo informático en cumplimiento de lo establecido en el Art. 49 del Acuerdo Ministerial 0243, y sus reformas. . 
        </div>

      ";

      return $htm;


    }

    public function encontrar__codigo__v1($codigo) {

      $codigoConsultar=$this->constructor->select__general__incentivo("SELECT codigoUsuario FROM proyecto_enviado WHERE codigo='$codigo';");
      foreach ($codigoConsultar as $valor) {
        $codigoGenerado=$valor["codigoUsuario"];
      }

      if(!empty($codigoGenerado)){
        return $codigoGenerado;
      }else{
        return 0;
      }

    }


    public function declaracion__condiciones__certificacion($idFactura,$codigo,$idComite) {

      $codigoUsuar=$this->encontrar__codigo__v1($codigo);


      foreach ($this->informacion__comtie__existente($idComite) as $valor) {
        $numeroComite=$valor["numeroComite"];
        $fecha=$valor["fecha"];
      }

      $array=array();

      foreach ($this->nombres__miembros__comite($idComite) as $valor) {

        if(intval($valor["presidenteComite"])===1){
          array_push($array,$valor["nombre"]." (PRESIDENTE DEL COMITE)");
        }else{
          array_push($array,$valor["nombre"]." (".$valor["cargo"].")");
        }
        
      }

      if (count($array) > 1) {
        $ultimo = array_pop($array);
        $miembrosString = implode(', ', $array) . ' y ' . $ultimo;
      } else {
        $miembrosString = implode('', $array);
      }

      if(intval($codigoUsuar)===0){

        $informacionGeneral__v1=$this->informacionProyecto__datosGenerales__v1($codigoUsuar);
        $nombreProyecto__carga=$informacionGeneral__v1[0]['nombreProyecto'];
        $solicitante=$informacionGeneral__v1[0]['nombreSolicitante'];

      }else{

        $informacionGeneral=$this->informacionProyecto__datosGenerales__certificacion($codigoUsuar);
        $nombreProyecto__carga=$informacionGeneral[0]['nombreProyecto'];
        $solicitante=$informacionGeneral[0]['nombreSolicitante'];

      }
      
      foreach ($this->secretario__comite($idComite) as $valor) {
        $secretarioComite=$valor["secretariaComite"];
      }
      
      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          5. DECLARACIÓN Y CONDICIONES: 
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          En caso de verificarse que la información presentada por el solicitante no se sujeta a la realidad o que ha incumplido con los requisitos o el procedimiento establecido en la normativa para la obtención del certificado de calificación, la autoridad emisora de dicho título podrá dejarlo sin efecto hasta que el solicitante cumpla con la normativa respectiva, sin perjuicio del inicio de los procesos o la aplicación de las sanciones que correspondan de conformidad con el ordenamiento jurídico vigente. 
        </div>

         <div class='texto-justificado w-full text-10 mt-1'>
         La veracidad de la información y documentación cargada, será de única y exclusiva responsabilidad de los proponentes conforme lo establecido en el artículo 34 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reformas. En caso de detectarse falsedad o inconsistencias, el trámite podrá ser negado o archivado, y dichos documentos carecerán de validez alguna y se podrán iniciar las acciones correspondientes.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
         Así mismo, mediante oficio Nro. SRI-NAC-DNC-2022-0047-OF, de 23 de marzo de 2022, la Dirección Nacional de Control Tributario del Servicio de Rentas Internas, señala: “los valores que se acogerán a la deducción adicional no deben incluir el IVA, sino solamente debe considerarse el subtotal de los comprobantes de venta relacionados a este gasto.”; En este sentido se certifica el valor correspondiente del Subtotal de los comprobantes de ventas emitidos.
        </div>

        <div class='text-left w-full text-10 mt-2 font-bold'>
          6. ACUERDO DE RESPONSABILIDAD: 
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
         El solicitante asume la responsabilidad total de la veracidad de la información ingresada durante el proceso de calificación, sin perjuicio de las acciones judiciales a que hubiere lugar, de conformidad a lo dispuesto en el primer inciso del artículo 270 del Código Orgánico Integral Penal.
        </div>

         <div class='text-left w-full text-10 mt-2 font-bold'>
          7.- PROTECCIÓN DE DATOS PERSONALES:
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
           En cumplimiento de lo dispuesto en la Ley Orgánica de Protección de Datos Personales, publicada en el Suplemento del Registro Oficial Nro. 459 de 26 de mayo de 2021, se garantiza el tratamiento lícito, leal, transparente y proporcional de los datos personales suministrados por los proponentes o solicitantes, los cuales serán utilizados exclusivamente para los fines relacionados con el presente procedimiento administrativo, conforme a los principios y derechos establecidos en dicha normativa.
        </div>

        <div class='text-left w-full text-10 mt-1'>
           En virtud del artículo 7 de la citada ley, los titulares de los datos personales conservan el derecho a acceder, rectificar, actualizar, eliminar, oponerse y limitar el tratamiento de su información, así como a ejercer cualquier otro derecho reconocido en la legislación vigente.
        </div>

        <div class='text-left w-full text-10 mt-1'>
           El Ministerio del Deporte, en su calidad de responsable del tratamiento, se compromete a implementar las medidas técnicas y organizativas adecuadas para garantizar la seguridad, confidencialidad e integridad de los datos personales, conforme a lo establecido en los artículos 8, 9 y 10 de la referida ley.
        </div>

         <div class='text-center w-full text-10 mt-2 font-bold'>
           Comité de Calificación y Certificación para Acceder al Incentivo Tributario
        </div> 

        <div class='text-left w-full text-10 mt-2 font-bold'>
          8. RAZÓN DE CERTIFICACIÓN:
        </div>

         <div class='texto-justificado w-full text-10 mt-1'>
          <span class='text-left w-full text-10 font-bold'>Lo Certifico.- </span> Que el contenido del presente documento corresponde a lo resuelto por el Comité de Calificación y Certificación para Acceder al Incentivo Tributario, en la Sesión Nro. $numeroComite, celebrada el ".$this->formatearFecha($fecha).", con la presencia del quórum reglamentario establecido en la normativa interna del ente rector del deporte, en la cual los señores $miembrosString, en su calidad de miembros del Comité, aprobaron, mediante el aplicativo informático institucional habilitado para el efecto, la certificación del proyecto denominado “".$nombreProyecto__carga."”, presentado por $solicitante, conforme al procedimiento previsto en la normativa aplicable. Esta actuación se enmarca en lo dispuesto en el artículo 49 reformado del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, cuya redacción fue sustituida por el artículo 6 del Acuerdo Ministerial Nro. MD-DM-2025-0038-A del 24 de junio de 2025 de, el cual establece que las decisiones del Comité pueden adoptarse a través del aplicativo informático o por medios electrónicos habilitados oficialmente.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
           La presente certificación se expide al amparo de lo dispuesto en el artículo 5 del Acuerdo Ministerial Nro. 0243 de 21 del noviembre de 2023, reformado por el artículo 1 del Acuerdo Ministerial Nro. MD-DM-2025-0038-A del 24 de junio de 2025, que delega expresamente al Secretario/a del Comité la facultad para suscribir los certificados aprobados por dicho órgano colegiado.
        </div>
        <div class='texto-justificado w-full text-10 mt-1'>
          Adicionalmente, la emisión del presente instrumento se ajusta a lo previsto en los artículos 43, 45 y 49 del referido Acuerdo Ministerial y su reforma, los cuales regulan el procedimiento, los requisitos formales, los medios de verificación y los responsables de la emisión de certificaciones, conforme al régimen de deducibilidad adicional del ciento cincuenta por ciento (150%) previsto en el artículo 10, numeral 19, de la Ley de Régimen Tributario Interno y su Reglamento de aplicación.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          En consecuencia, esta Secretaría actúa en representación del Comité de Calificación y Certificación para Acceder al Incentivo Tributario, conforme a las atribuciones delegadas, siendo la responsabilidad del contenido del presente documento de los miembros del Comité que aprobaron la certificación en el ejercicio de sus competencias y facultades legales.
        </div>

        <div class='text-center w-full text-10 mt-4 font-bold'>
          $secretarioComite
        </div>
        <div class='text-center w-full text-10 font-bold'>
          Secretaria del Comité de Calificación y Certificación para Acceder al Incentivo
        </div>
        <div class='text-center w-full text-10 font-bold'>
          Tributario
        </div>

      ";

      return $htm;

    }

    public function condiciones__comprobante($idFactura,$codigo,$idComite) {


      $informacionGeneral=$this->observable__axios__informacion__facturas($idFactura);
      $informacionGeneral__2=$this->informacionProyecto__datosGenerales__certificacion($codigo);

      foreach ($this->informacion__comtie__existente($idComite) as $valor) {
          $numeroComite=$valor["numeroComite"];
          $fecha=$valor["fecha"];
      }


      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
           4. EMISIÓN DE LA CERTIFICACIÓN y FECHA DE EXPEDICIÓN:
        </div>


        <div class='texto-justificado w-full text-10 mt-1'>
          El Comité de Calificación y Certificación, para acceder al Incentivo Tributario, mediante sesión llevada a cabo el $fecha <span class='font-bold'>CERTIFICA</span> al patrocinador, gestor de la promoción o publicidad ".$informacionGeneral[0]['razonSocial']." para aplicar la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta de los gastos de patrocinio o publicidad realizados a favor del proyecto: ".$informacionGeneral__2[0]['nombreProyecto'].", dicha aprobación fue realizada en el aplicativo informático en cumplimiento de lo establecido en el Art. 49 del Acuerdo Ministerial 0243, y sus reformas. .
        </div>


      ";

      return $htm;


    }

    public function datos__comprobante__de__venta__v1($idFactura,$codigo) {


      $informacionGeneral=$this->observable__axios__informacion__facturas__v1($idFactura);
      $informacionGeneral__2=$this->informacionProyecto__datosGenerales__v1($codigo);

      $credencialSolicitante = $informacionGeneral__2[0]['credencialSolicitante'];


      if(!empty($informacionGeneral[0]['rucXml']) && !empty($informacionGeneral[0]['razonSocialXml']) && $informacionGeneral[0]['rucXml']!==null && $informacionGeneral[0]['rucXml']!=="null"){

          $nombreEmisor=$informacionGeneral[0]['rucXml'];
          $rucEmisor=$informacionGeneral[0]['razonSocialXml'];

      }else{

          $nombreEmisor=$informacionGeneral__2[0]['nombreSolicitante'];
          $rucEmisor=$informacionGeneral__2[0]['rucProponenteIdentificado'];

      }


      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          3.  DATOS DEL COMPROBANTE DE VENTA:
        </div>

        <table class='styled-table mt-2'>

            <tr>

              <td class='font-bold'>
                NOMBRE EL EMISOR DEL COMPROBANTE (DEPORTISTA U ORGANIZADOR DEL PROGRAMA Y/O PROYECTO):
              </td>
              <td>
                  ".$nombreEmisor."
              </td>

            </tr>


            <tr>

              <td class='font-bold'>
                RUC EMISOR DEL COMPROBANTE:
              </td>
              <td>
                ".$rucEmisor."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                NOMBRE DEL RECEPTOR DEL COMPROBANTE (PATROCINADOR, GESTOR DE LA PROMOCIÓN O PUBLICIDAD):
              </td>
              <td>
                  ".$informacionGeneral[0]['razonSocial']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                RUC DEL RECEPTOR:
              </td>
              <td>
                ".$informacionGeneral[0]['ruc']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                NUMERO DE COMPROBANTE DE VENTA:
              </td>
              <td>
                  ".$informacionGeneral[0]['numeroFactura']."
              </td>

            </tr>


            <tr>

              <td class='font-bold'>
                MONTO SUBTOTAL:
              </td>
              <td>
                  ".$informacionGeneral[0]['subotal']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                15% IVA:
              </td>
              <td>
                  ".$informacionGeneral[0]['iva']."
              </td>

            </tr>


            <tr>

              <td class='font-bold'>
                TOTAL:
              </td>
              <td>
                  ".$informacionGeneral[0]['total']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                FECHA DE EMISIÓN DEL COMPROBANTE:

              </td>
              <td>
                  ".$this->formatearFecha($informacionGeneral[0]['fechaEmision'])."
              </td>

            </tr>

        </table>


      ";

      return $htm;


    }

    public function datos__comprobante__de__venta($idFactura,$codigo) {


      $informacionGeneral=$this->observable__axios__informacion__facturas($idFactura);
      $informacionGeneral__2=$this->informacionProyecto__datosGenerales__certificacion($codigo);

      $credencialSolicitante = $informacionGeneral__2[0]['credencialSolicitante'];

      if (strlen($credencialSolicitante) < 13) {
          $credencialSolicitante .= '001';
      }


      if(!empty($informacionGeneral[0]['rucXml']) && !empty($informacionGeneral[0]['razonSocialXml']) && $informacionGeneral[0]['rucXml']!==null && $informacionGeneral[0]['rucXml']!=="null"){

          $nombreEmisor=$informacionGeneral[0]['rucXml'];
          $rucEmisor=$informacionGeneral[0]['razonSocialXml'];

      }else{

          $nombreEmisor=$informacionGeneral__2[0]['nombreSolicitante'];
          $rucEmisor=$informacionGeneral__2[0]['rucProponenteIdentificado'];

      }

      if (strlen($rucEmisor) < 13) {
          $rucEmisor .= '001';
      }

      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          3.  DATOS DEL COMPROBANTE DE VENTA:
        </div>

        <table class='styled-table mt-2'>

            <tr>

              <td class='font-bold'>
                NOMBRE EL EMISOR DEL COMPROBANTE (DEPORTISTA U ORGANIZADOR DEL PROGRAMA Y/O PROYECTO):
              </td>
              <td>
                  ".$nombreEmisor."
              </td>

            </tr>


            <tr>

              <td class='font-bold'>
                RUC EMISOR DEL COMPROBANTE:
              </td>
              <td>
                 ".$rucEmisor."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                NOMBRE DEL RECEPTOR DEL COMPROBANTE (PATROCINADOR, GESTOR DE LA PROMOCIÓN O PUBLICIDAD):
              </td>
              <td>
                  ".$informacionGeneral[0]['razonSocial']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                RUC DEL RECEPTOR:
              </td>
              <td>
                 ".$informacionGeneral[0]['ruc']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                NUMERO DE COMPROBANTE DE VENTA:
              </td>
              <td>
                  ".$informacionGeneral[0]['numeroFactura']."
              </td>

            </tr>


            <tr>

              <td class='font-bold'>
                MONTO SUBTOTAL:
              </td>
              <td>
                  ".$informacionGeneral[0]['subotal']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                15% IVA:
              </td>
              <td>
                  ".$informacionGeneral[0]['iva']."
              </td>

            </tr>


            <tr>

              <td class='font-bold'>
                TOTAL:
              </td>
              <td>
                  ".$informacionGeneral[0]['total']."
              </td>

            </tr>

            <tr>

              <td class='font-bold'>
                FECHA DE EMISIÓN DEL COMPROBANTE:

              </td>
              <td>
                  ".$this->formatearFecha($informacionGeneral[0]['fechaEmision'])."
              </td>

            </tr>

        </table>


      ";

      return $htm;


    }

    public function datos__solicitante__v1($codigo,$idEnviado) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales__v1($codigo);


      foreach ($this->fecha__calificacion__proyecto__v1($codigo) as $valor) {
          $fechaComite=$valor["fecha"];
      }

      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          2. DATOS DEL SOLICITANTE:
        </div>

            <table class='styled-table mt-2'>

                <tbody>

                   <tr>

                      <td class='font-bold'>
                          DATOS DEL SOLICITANTE IDENTIFICACIÓN:

                      </td>
                      <td>
                          ".$informacionGeneral[0]['credencialSolicitante']."
                      </td>

                   </tr>

                   <tr>

                      <td class='font-bold'>
                          NOMBRE DEL SOLICITANTE
                      </td>
                      <td>
                          ".$informacionGeneral[0]['nombreSolicitante']."
                      </td>

                   </tr>

                   <tr>

                      <td class='font-bold'>
                          TELÉFONO:
                      </td>
                      <td>
                          ".$informacionGeneral[0]['celularSolicitante']."
                      </td>

                   </tr>

                    <tr>

                        <td class='font-bold'>
                            CORREO ELECTRÓNICO:
                        </td>
                        <td>
                            ".$informacionGeneral[0]['correoSolicitante']."
                        </td>

                     </tr>

                      <tr>

                        <td class='font-bold'>
                            NOMBRE DEL PROYECTO:
                        </td>
                        <td>
                            ".$informacionGeneral[0]['nombreProyecto']."
                        </td>

                     </tr>


                      <tr>

                        <td class='font-bold'>
                            SECTOR AL QUE CONTRIBUYE:
                        </td>
                        <td>
                            ".$informacionGeneral[0]['sector']."
                        </td>

                      </tr>


                      <tr>

                        <td class='font-bold'>
                            MONTO DEL PROYECTO:
                        </td>
                        <td>
                            ".number_format($informacionGeneral[0]['monto'], 2, ',', '.')."
                        </td>

                      </tr>


                      <tr>

                        <td class='font-bold'>
                            FECHA DE CALIFICACIÓN:
                        </td>
                        <td>
                            ".$fechaComite."
                        </td>

                      </tr>


                </tbody>


            </table>


      ";

      return $htm;


    }

    public function datos__solicitante($codigo,$idEnviado) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales__certificacion($codigo);


      foreach ($this->fecha__calificacion__proyecto($idEnviado) as $valor) {
          $fechaComite=$valor["fecha"];
      }

      foreach ($this->informacion__fechaComite($idEnviado) as $valor) {
        $fechaBd=$valor["fecha"];
      }


      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          2. DATOS DEL SOLICITANTE:
        </div>

            <table class='styled-table mt-2'>

                <tbody>

                   <tr>

                      <td class='font-bold'>
                          DATOS DEL SOLICITANTE IDENTIFICACIÓN:

                      </td>
                      <td>
                          ".$informacionGeneral[0]['credencialSolicitante']."
                      </td>

                   </tr>

                   <tr>

                      <td class='font-bold'>
                          NOMBRE DEL SOLICITANTE
                      </td>
                      <td>
                          ".$informacionGeneral[0]['nombreSolicitante']."
                      </td>

                   </tr>

                   <tr>

                      <td class='font-bold'>
                          TELÉFONO:
                      </td>
                      <td>
                          ".$informacionGeneral[0]['celularSolicitante']."
                      </td>

                   </tr>

                    <tr>

                        <td class='font-bold'>
                            CORREO ELECTRÓNICO:
                        </td>
                        <td>
                            ".$informacionGeneral[0]['correoSolicitante']."
                        </td>

                     </tr>

                      <tr>

                        <td class='font-bold'>
                            NOMBRE DEL PROYECTO:
                        </td>
                        <td>
                            ".$informacionGeneral[0]['nombreProyecto']."
                        </td>

                     </tr>


                      <tr>

                        <td class='font-bold'>
                            SECTOR AL QUE CONTRIBUYE:
                        </td>
                        <td>
                            ".$informacionGeneral[0]['sector']."
                        </td>

                      </tr>


                      <tr>

                        <td class='font-bold'>
                            MONTO DEL PROYECTO:
                        </td>
                        <td>
                            ".number_format($informacionGeneral[0]['monto'], 2, ',', '.')."
                        </td>

                      </tr>


                      <tr>

                        <td class='font-bold'>
                            FECHA DE CALIFICACIÓN:
                        </td>
                        <td>
                            ".$fechaBd."
                        </td>

                      </tr>


                </tbody>


            </table>


      ";

      return $htm;


    }

    public function baseLegal__certificado() {


      $htm= "

        <div class='text-left w-full text-10 mt-2 font-bold'>
          1.  MARCO NORMATIVO:
        </div>

        <div class='text-left w-full text-10 mt-1'>
          - Constitución de la República del Ecuador: Art. 226, 227, 381.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Código Orgánico Administrativo: Art. 65, 164.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Ley Orgánica para el Desarrollo Económico y Sostenibilidad Fiscal tras la Pandemia COVID-19: Art.39.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos: Art.10.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Ley Orgánica de Régimen Tributario Interno: Art.10.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Ley de Deporte Educación Física y Recreación: Art.13
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Reglamento a la Ley Orgánica para el Desarrollo Económico y Sostenibilidad Fiscal tras la Pandemia COVID-19: Art.36.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Reglamento para la Aplicación de la Ley Orgánica de Régimen Tributario Interno: Art. 28.
        </div>
        <div class='text-left w-full text-10 mt-1'>
            Normativa Interna:
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Acuerdo Ministerial Nro.0243 de 21 de noviembre de 2023.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Acuerdo Ministerial Nro. 0113 de 05 de julio de 2024.
        </div>
        <div class='text-left w-full text-10 mt-1'>
          - Acuerdo Ministerial Nro. 0136 de 06 de noviembre de 2024
        </div>
        <div class='text-left w-full text-10 mt-1'>
         -  Acuerdo Ministerial Nro. 0038 de 24 de junio de 2025
        </div>

      ";

      return $htm;


    }

    public function portadaInicial__certificado__v1($idComite,$codigo) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales__v1($codigo);

      foreach ($this->techo__presupuestario__general() as $valor) {
          $techo=$valor["techo"];
          $oficio=$valor["oficio"];
      }

      foreach ($this->informacion__comtie__existente($idComite) as $valor) {
          $numeroComite=$valor["numeroComite"];
          $fecha=$valor["fecha"];
      }


      $formateado = number_format($techo, 2, ',', '.');

      $htm= "

        <div class='text-center w-full text-10 mt-1 font-bold'>
          CERTIFICADO DE BENEFICIARIO PARA ACCEDER A LA DEDUCCIÓN DEL 150% ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA DE LOS GASTOS DE PATROCINIO, PROMOCIÓN O PUBLICIDAD REALIZADOS A FAVOR DE DEPORTISTAS Y ORGANIZADORES DE PROGRAMAS Y/O PROYECTOS DEPORTIVOS PARA LA DECLARACIÓN DEL IMPUESTO A LA RENTA DEL EJERCICIO FISCAL ".$this->anio." A DECLARARSE EN EL AÑO ".($this->anio + 1)."
        </div>

        <div class='text-center w-full text-10 mt-1 font-bold'>
          MONTO APROBADO POR EL MINISTERIO DE FINANZAS: USD $formateado
        </div>

        <div class='text-center w-full text-10 mt-1 font-bold'>
          CONFORME OFICIO $oficio
        </div>

        <div class='text-center w-full text-10 mt-1 font-bold'>
          Sesión Nro. $numeroComite de $fecha
        </div>

        <div class='text-left w-full text-10 mt-1'>
          Estimado(a) Solicitante
        </div>

        <div class='text-left w-full text-10 mt-1'>
          ".$informacionGeneral[0]['nombreSolicitante']."
        </div>

       <div class='text-left w-full text-10 mt-1 font-bold'>
          De nuestras consideraciones
        </div>

       <div class='texto-justificado w-full text-10 mt-1'>
         El Comité de Calificación y Certificación para Acceder al Incentivo Tributario, en Sesión Nro. $numeroComite de $fecha, certificó el proyecto o programa denominado “".$informacionGeneral[0]['nombreProyecto']."”, en ese sentido se informa lo siguiente:
        </div>

      ";

      return $htm;


    }

    public function portadaInicial__certificado($idComite,$codigo) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales__certificacion($codigo);

      foreach ($this->techo__presupuestario__general() as $valor) {
          $techo=$valor["techo"];
          $oficio=$valor["oficio"];
      }

      foreach ($this->informacion__comtie__existente($idComite) as $valor) {
          $numeroComite=$valor["numeroComite"];
          $fecha=$valor["fecha"];
      }


      $formateado = number_format($techo, 2, ',', '.');

      $htm= "

        <div class='text-center w-full text-10 mt-1 font-bold'>
          CERTIFICADO DE BENEFICIARIO PARA ACCEDER A LA DEDUCCIÓN DEL 150% ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA DE LOS GASTOS DE PATROCINIO, PROMOCIÓN O PUBLICIDAD REALIZADOS A FAVOR DE DEPORTISTAS Y ORGANIZADORES DE PROGRAMAS Y/O PROYECTOS DEPORTIVOS PARA LA DECLARACIÓN DEL IMPUESTO A LA RENTA DEL EJERCICIO FISCAL ".$this->anio." A DECLARARSE EN EL AÑO ".($this->anio + 1)."
        </div>

        <div class='text-center w-full text-10 mt-1 font-bold'>
          MONTO APROBADO POR EL MINISTERIO DE FINANZAS: USD $formateado
        </div>

        <div class='text-center w-full text-10 mt-1 font-bold'>
          CONFORME OFICIO $oficio 
        </div>

        <div class='text-center w-full text-10 mt-1 font-bold'>
          Sesión Nro. $numeroComite de $fecha
        </div>

        <div class='text-left w-full text-10 mt-1'>
          Estimado(a) Solicitante
        </div>

        <div class='text-left w-full text-10 mt-1'>
          ".$informacionGeneral[0]['nombreSolicitante']."
        </div>

       <div class='text-left w-full text-10 mt-1 font-bold'>
          De nuestras consideraciones
        </div>

       <div class='texto-justificado w-full text-10 mt-1'>
         El Comité de Calificación y Certificación para Acceder al Incentivo Tributario, en Sesión Nro. $numeroComite de $fecha, certificó el proyecto o programa denominado “".$informacionGeneral[0]['nombreProyecto']."”, en ese sentido se informa lo siguiente:
        </div>

      ";

      return $htm;


    }

    public function notificacion__certificacion__v1($codigo,$idEnviado) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales__v1($codigo);
      $sesionOr=$this->representante__asunto__comite($idEnviado);


      foreach ($this->informacionInicial($idEnviado) as $valor) {
        $nombreBd=$valor["nombre"];
        $idCredencialBd=$valor["idCredencial"];
        $fechaBd=$valor["fecha"];
      }


      $fecha__reunion__comite =$this->formatearFecha($fechaBd);

      $htm= "

        <div class='text-left w-full text-10 mt-1 font-bold'>
          III.  NOTIFICACIÓN:
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          En virtud de la normativa expuesta y acorde a las resoluciones adoptadas por el Comité de Calificación y Certificación para acceder al Incentivo Tributario, en sesión $sesionOr de $fecha__reunion__comite, cumplo con notificar a usted en mi calidad de Secretaria del Comité de Calificación y Certificación para acceder al Incentivo Tributario, conforme lo dispone la normativa legal vigente que los miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario resolvieron emitir el certificado respectivo conforme documento adjunto.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Cabe indicar que, la certificación emitida por los miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario se sustenta en la Ley del Régimen Tributario Interno, Reglamento de Aplicación de la Ley de Régimen Tributario Interno; y Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reformas expedido por esta Cartera de Estado.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Asimismo, una vez ejecutado el programa/proyecto deportivo, se deberá dar cumplimiento a lo establecido en el artículo 50 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reformas.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Finalmente se señala que el Comité de Calificación y Certificación para acceder al Incentivo Tributario se ha pronunciado sobre la base de la información presentada y cuyo contenido, veracidad y legitimidad es de exclusiva responsabilidad del peticionario, de conformidad a lo establecido por la Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Razón por la cual, no se responsabiliza por eventuales inconsistencias que pudieren existir entre la información presentada y los datos originales.
        </div>


        <div class='texto-justificado w-full text-10 mt-1'>
         Con sentimientos de distinguida consideración.
        </div>


        <div class='texto-justificado w-full text-10 mt-100 font-bold'>
         Atentamente,
        </div>

      ";

      return $htm;


    }

    public function notificacion__certificacion($codigo,$idEnviado) {

      $informacionGeneral=$this->informacionProyecto__datosGenerales($codigo);
      $sesionOr=$this->representante__asunto__comite($idEnviado);


      foreach ($this->informacionInicial($idEnviado) as $valor) {
        $nombreBd=$valor["nombre"];
        $idCredencialBd=$valor["idCredencial"];
        $fechaBd=$valor["fecha"];
      }


      $fecha__reunion__comite =$this->formatearFecha($fechaBd);


      $htm= "

        <div class='text-left w-full text-10 mt-1 font-bold'>
          III.  NOTIFICACIÓN:
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          En virtud de la normativa expuesta y acorde a las resoluciones adoptadas por el Comité de Calificación y Certificación para acceder al Incentivo Tributario, en sesión $sesionOr de $fecha__reunion__comite, cumplo con notificar a usted en mi calidad de Secretaria del Comité de Calificación y Certificación para acceder al Incentivo Tributario, conforme lo dispone la normativa legal vigente que los miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario resolvieron emitir el certificado respectivo conforme documento adjunto.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Cabe indicar que, la certificación emitida por los miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario se sustenta en la Ley del Régimen Tributario Interno, Reglamento de Aplicación de la Ley de Régimen Tributario Interno; y Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reformas expedido por esta Cartera de Estado.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Asimismo, una vez ejecutado el programa/proyecto deportivo, se deberá dar cumplimiento a lo establecido en el artículo 50 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reformas.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Finalmente se señala que el Comité de Calificación y Certificación para acceder al Incentivo Tributario se ha pronunciado sobre la base de la información presentada y cuyo contenido, veracidad y legitimidad es de exclusiva responsabilidad del peticionario, de conformidad a lo establecido por la Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos.
        </div>

        <div class='texto-justificado w-full text-10 mt-1'>
          Razón por la cual, no se responsabiliza por eventuales inconsistencias que pudieren existir entre la información presentada y los datos originales.
        </div>


        <div class='texto-justificado w-full text-10 mt-1'>
         Con sentimientos de distinguida consideración.
        </div>


        <div class='texto-justificado w-full text-10 mt-100 font-bold'>
         Atentamente,
        </div>

      ";

      return $htm;


    }

    public function base__legal__certificacion() {

      $htm= '

        <div class="texto-justificado w-full text-10 mt-2">
          <span class="font-bold">II. BASE LEGAL:</span>
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 226 de la Constitución de la República del Ecuador señala que: “Las instituciones del Estado, sus organismos, dependencias, las servidoras o servidores públicos y las personas que actúen en virtud de una potestad estatal ejercerán solamente las competencias y facultades que les sean atribuidas en la Constitución y la ley. Tendrán el deber de coordinar acciones para el cumplimiento de sus fines y hacer efectivo el goce y ejercicio de los derechos reconocidos en la Constitución".        
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 227 ibidem, establece que: “La administración pública constituye un servicio a la colectividad que se rige por los principios de eficacia, eficiencia, calidad, Jerarquía, desconcentración, descentralización, coordinación, participación, planificación, transparencia y evaluación".  
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 65 del Código Orgánico Administrativo, dispone: “La competencia es la medida en la que la Constitución y la ley habilitan a un órgano para obrar y cumplir sus fines, en razón de la materia, el territorio, el tiempo y el grado".
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 164, ibidem, establece: “Notificación. Es el acto por el cual se comunica a la persona interesada o a un conjunto indeterminado de personas, el contenido de un acto administrativo para que las personas interesadas estén en condiciones de ejercer sus derechos. La notificación de la primera actuación de las administraciones públicas se realizará personalmente, por boleta o a través del medio de comunicación, ordenado por estas. La notificación de las actuaciones de las administraciones públicas se practica por cualquier medio, físico o digital, que permita tener constancia de la transmisión y recepción de su contenido".
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 10 de la Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos respecto a la veracidad de la información prescribe que: “Las entidades reguladas por esta Ley presumirán que las declaraciones, documentos y actuaciones de las personas efectuadas en virtud de trámites administrativos son verdaderas, bajo aviso a la o al administrado de que, en caso de verificarse lo contrario, el trámite y resultado final de la gestión podrán ser negados y archivados, o los documentos emitidos carecerán de validez alguna, sin perjuicio de las sanciones y otros efectos jurídicos establecidos en la ley. El listado de actuaciones anuladas por la entidad en virtud de lo establecido en este inciso estará disponible para las demás entidades del Estado (...)”.
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 10 de   la   Ley   Orgánica   de   Régimen   Tributario   Interno   establece que: “Deducciones. En general, con el propósito de determinar la base imponible sujeta a este impuesto se deducirán los gastos e inversiones que se efectúen con el propósito de obtener, mantener y mejorar los ingresos de fuente ecuatoriana que no estén exentos. En particular se aplicarán las siguientes deducciones: 19. Los costos y gastos por promoción y publicidad de conformidad con las excepciones, límites, segmentación y condiciones establecidas en el Reglamento (...).
        </div>


        <div class="texto-justificado w-full text-10 mt-1">
         Se deducirá el ciento cincuenta por ciento (150%) adicional para el cálculo de la base imponible del impuesto a la renta, los gastos de publicidad, promoción y patrocinio, realizados a favor de deportistas, y programas, proyectos o eventos deportivos calificados por la entidad rectora competente en la materia. El reglamento a esta ley definirá los parámetros técnicos y requisitos formales a cumplirse para acceder a esta deducción adicional.”.
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          El artículo 28 del Reglamento para la Aplicación de la Ley de Régimen Tributario Interno, dispone: “Gastos generales deducibles. - Bajo las condiciones descritas en el artículo precedente y siempre que no hubieren sido aplicados al costo de producción, son deducibles los gastos previstos por la Ley de Régimen Tributario Interno, en los términos señalados en ella y en este reglamento, tales como: 11. Promoción, publicidad y patrocinio. - Para la deducibilidad de costos y gastos incurridos para la promoción, publicidad y patrocinio se aplicarán las siguientes definiciones: e.) Se podrá deducir el 150% adicional para el cálculo de la base imponible del impuesto a la renta, los gastos de publicidad, promoción y patrocinio realizados a favor de deportistas, y programas, proyectos o eventos deportivos calificados por la entidad rectora competente en la materia, según lo previsto en el respectivo documento de planificación estratégica, así como con los límites y condiciones que esta emita para el efecto. 
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          Para acceder a esta deducción adicional, se deberá considerar lo siguiente:
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          1.  Previo a aplicar la deducibilidad el beneficiario de la deducción adicional debe contar con una certificación emitida por el ente rector del deporte en la que, por cada beneficiario, conste al menos: a) Los datos del deportista y organizador del programa o proyecto que recibe el aporte, junto con la identificación del proyecto, programa o evento cuando corresponda; b) Los datos del patrocinador; y, c) El monto y fecha del patrocinio. d) La indicación de que:

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          i.  El aporte se efectúa en apoyo a deportistas ecuatorianos; o, en apoyo a proyectos, programas o eventos deportivos realizados en el Ecuador; y,

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          ii. El aporte se efectúa directamente al deportista o al organizador de los proyectos, programas o eventos deportivos, sin la participación de intermediarios. En tales casos, previo a la emisión de la certificación se deberá contar con el dictamen favorable del ente rector de las finanzas públicas. Para el efecto el ente rector del deporte solicitará al organismo rector de las finanzas públicas, hasta el mes de noviembre de cada año, un dictamen a aplicarse para el ejercicio posterior, sobre el rango o valor máximo global anual de aprobación de proyectos, programas o eventos, con el fin de establecer el impacto fiscal correspondiente. En caso de que no se obtenga el dictamen del ente rector de las finanzas públicas hasta el mes de diciembre del año en el que se presentó la solicitud, el último monto aprobado se entenderá prorrogado para el siguiente ejercicio fiscal.

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          2.  Los desembolsos efectivamente realizados por concepto del patrocinio, promoción o publicidad deberán estar debidamente sustentados en los respectivos comprobantes de venta de acuerdo con lo establecido en la ley; además deberá realizarse las retenciones de impuestos cuando corresponda.

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          3.  En el caso de aporte en bienes o servicios, éstos deberán ser valorados al precio comercial, cumpliendo el pago de los impuestos indirectos que correspondan por este aporte. En estos casos dicha valoración deberá constar en el certificado referido en el número 1.

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          4. El ingreso (en dinero, bienes o servicios) que reciban los deportistas o los organizadores de los proyectos o programas deportivos por este patrocinio, atenderán al concepto de renta de sujetos residentes en el Ecuador establecido en la Ley de Régimen Tributario Interno. La deducción adicional establecida no podrá generar una pérdida tributaria sujeta a amortización. En el caso de que la asignación de recursos a los que se refiere este numeral se la efectúe en varios ejercicios fiscales, para utilizar la deducción, se deberá contar por cada ejercicio fiscal con el certificado antes mencionado.
          
        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
         e.1) .- Para aplicar las deducciones adicionales previstas a partir del cuarto inciso del numeral 19 del artículo 10 de la Ley de Régimen Tributario Interno se deberá considerar lo siguiente:

        </div>

       <div class="texto-justificado w-full text-10 mt-1">
          
         1. El beneficiario de la deducibilidad deberá contar, con una certificación emitida por el ente rector en la materia, en la que, por cada beneficiario, conste al menos:
         
        </div>

       <div class="texto-justificado w-full text-10 mt-1">
          
         a. Los datos de la persona o institución que recibe el aporte, junto con la identificación del proyecto o programa cuando corresponda;
         
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
         b. Los datos del aportante;
         
        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
         c. El monto y fecha del aporte; y,
         
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
         d. La indicación de que:
         
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
         i. El aporte se efectúa en favor de personas o instituciones domiciliadas o localizadas en el Ecuador; y,
         
        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
         ii.  El aporte se efectúa directamente a la persona o institución, sin la participación de intermediarios.
         
        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
        En tales casos, previo a la emisión de la certificación se deberá contar con el dictamen favorable del ente rector de las finanzas públicas. Para el efecto la entidad rectora de la materia solicitará al organismo rector de las finanzas públicas, hasta el mes de noviembre de cada año, un dictamen a aplicarse para el ejercicio posterior, sobre el rango o valor máximo global anual de aprobación de proyectos o programas para los proyectos de auspicios o patrocinios, con el fin de establecer el impacto fiscal correspondiente. En caso de que no se obtenga la certificación del ente rector de las finanzas públicas hasta el mes de diciembre del año en el que se presentó la solicitud, se entenderá prorrogada para el siguiente ejercicio fiscal.
         
        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          2.  Los desembolsos efectivamente realizados por concepto del aporte deberán estar debidamente sustentados en los respectivos comprobantes de venta, además deberá realizarse las retenciones de impuestos cuando corresponda.

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          3.  En el caso de aporte en bienes o servicios, éstos deberán ser valorados al precio comercial, cumpliendo el pago de los impuestos indirectos que correspondan por este aporte. En estos casos dicha valoración deberá constar en el certificado referido en el número 1.

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          4.  El ingreso (en dinero, bienes o servicios) que reciban las personas o instituciones por estos aportes, atenderán al concepto de renta establecido en la Ley de Régimen Tributario Interno.

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          5.  Cuando se traten de programas y proyectos estos deberán ser calificados por la entidad rectora competente en la materia.

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          La deducción adicional establecida no podrá generar una pérdida tributaria sujeta a amortización.

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          En el caso de que la asignación de recursos a los que se refiere este numeral se la efectúe en varios ejercicios fiscales, para utilizar la deducción, se deberá contar por cada ejercicio fiscal con el certificado antes mencionado.”.

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          El artículo 13 de la Ley del Deporte, Educación Física y Recreación, establece: “El Ministerio Sectorial es el órgano rector y planificador del deporte, educación física y recreación; le corresponde establecer, ejercer, garantizar y aplicar las políticas, directrices y planes aplicables en las áreas correspondientes para el desarrollo del sector de conformidad con lo dispuesto en la Constitución, las leyes, instrumentos internacionales y reglamentos aplicables. Tendrá dos objetivos principales, la activación de la población para asegurar la salud de las y los ciudadanos y facilitar la consecución de logros deportivos a nivel nacional e internacional de las y los deportistas incluyendo, aquellos que tengan algún tipo de discapacidad".

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
          NORMATIVA INTERNA 

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
         Mediante Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, el licenciado Juan Sebastián Palacios Muñoz, en calidad de Ministro del Deporte expidió: “LA CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA DE LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O EVENTOS DEPORTIVOS”, y derogó al Acuerdo Nro. 0434 de 17 de noviembre de 2021.

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
         Mediante Acuerdo Ministerial Nro. 0113 de 05 de julio de 2024, el licenciado Marcelo Andrés Guschmer Tamariz, en calidad de Ministro del Deporte expidió: “ LA REFORMA A LA CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA, LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O EVENTOS DEPORTIVOS (Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023) ”.

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
         Mediante Acuerdo Ministerial Nro. 0136 de 06 de noviembre de 2024, el sr. Ab. José David Jiménez Vásquez, en calidad de Ministro del Deporte Encargado expidió: “LA REFORMA A LA CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA, LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O EVENTOS DEPORTIVOS (Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023)”.

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
         Mediante Acuerdo Ministerial Nro. 0038 de 24 de junio de 2025, el Abogado José David Jiménez Vásquez, en calidad de Ministro del Deporte expidió: “EXPEDIR LA REFORMA A LA CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA, LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O EVENTOS DEPORTIVOS (Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023).

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          El artículo 4 de mencionado Acuerdo Ministerial y sus reformas establece como atribuciones del Comité: “(...) 4) Certificar o negar la certificación a beneficiarios para la obtención del incentivo tributario a nombre del Ministerio del Deporte;(...)”.

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          El artículo 5 de citado Acuerdo Ministerial y sus reformas indica: “De la designación del/la secretario/a del Comité.- El/la presidente/a del Comité designará a un/a servidor/a público/a que ejerza las funciones de secretario/a, pudiendo ser reemplazado/a en cualquier momento; cuyas responsabilidades a su cargo son, entre otras, las siguientes: ( ... ) g) Realizar las notificaciones sobre las decisiones adoptadas por el Comité a través del aplicativo informático; h) Suscribir los certificados, aprobados por el Comité, así como certificar los actos, resoluciones, y demás documentos aprobados por el Comité, i) Las demás que le sean asignadas en cumplimiento de sus funciones. (... )”.

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
          El artículo 43 del referido Acuerdo Ministerial y sus reformas dispone: “De la Certificación de beneficiarios.- Una vez cumplidos los procedimientos establecidos en los artículos precedentes, los/las solicitantes podrán requerir al Comité la emisión de la certificación a favor de los beneficiarios de la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta, entendiéndose por tales a quienes hubiesen efectuado gastos por concepto de promoción, publicidad y/o patrocinio a favor de deportistas u organizadores de programas y/o proyectos deportivos. 


        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
          Se exceptúa de este proceso a las personas naturales o jurídicas que actúen como proponentes o que hayan presentado programas y/o proyectos deportivos que se encuentren vigentes al momento de la emisión del o los comprobantes de venta para su certificación. En caso de que no se cumpla con lo dispuesto, dichos comprobantes serán archivados sin que se requiera procedimiento adicional. Esta exclusión tiene como finalidad garantizar un proceso transparente, orientado al fomento del desarrollo deportivo y alineado con las necesidades reales del sector. 


        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          Para aplicar a este proceso, se podrán reconocer los gastos en promoción, publicidad y/o patrocinio generados dentro del ejercicio fiscal correspondiente y siempre que los mismos guarden relación con los componentes establecidos en los programas y/o proyectos cuya prioridad haya sido calificada por el Ministerio del Deporte. En ese sentido, se aplicarán las definiciones contenidas en el Reglamento para la aplicación de la Ley de Régimen Tributario Interno referentes a los costos y gastos de promoción, publicidad y patrocinio. 

        </div>



        <div class="texto-justificado w-full text-10 mt-1">
          
         En cumplimiento a la Ley de Régimen Tributario Interno y su Reglamento, las certificaciones deberán emitirse para cada ejercicio fiscal. Este principio aplica también para los procesos de certificación relacionados a los programas y/o proyectos plurianuales, debiendo cumplirse de manera adicional el procedimiento establecido en el artículo 38 de la presente norma. En tal sentido, no podrán emitirse certificaciones que contemplen gastos generados en varios años. 

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
        Finalmente, para la certificación de proyectos o programas deportivos del deporte profesional, primero se verificará el cumplimiento del financiamiento del 5% del monto total proyectado a la ejecución de actividades de proyectos definidos por el ente rector del Deporte y que conste dentro de los niveles y sectores priorizados; y al menos el 5% restante, a la ejecución de componentes para el deporte femenino, estipulado en el artículo 29 del presente Acuerdo Ministerial.”

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
         El artículo 45 de mencionado Acuerdo Ministerial y sus reformas señala: “Del procedimiento para obtener la certificación. - Para la emisión de la certificación, el/la solicitante deberá ingresar al aplicativo informático y cargar el o los comprobantes de venta de patrocinio, promoción o publicidad emitidos a favor del beneficiario de la deducibilidad. Dichos comprobantes deberán cumplir los requisitos, características y demás criterios establecidos en la normativa legal vigente

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
         Los comprobantes de venta guardarán relación directa con el monto establecido en los programas y/o proyectos calificados como prioritarios. En tal virtud los proponentes estarán obligados a presentar al memos uno de los siguientes documentos de respaldo: 

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
         a) Comprobante de transferencia bancaria emitido por la entidad financiera correspondiente, con los datos del emisor, receptor, fecha, monto y concepto de la operación.

        </div>

         <div class="texto-justificado w-full text-10">
          
         b) Depósito bancario con copia del comprobante sellado por la institución financiera. 

        </div>

         <div class="texto-justificado w-full text-10">
          
         c) Comprobante de pago electrónico emitido por plataformas autorizadas.  

        </div>

         <div class="texto-justificado w-full text-10">
          
         d) Certificación bancaria suscrita por la entidad financiera que acredite el movimiento realizado.   

        </div>

        <div class="texto-justificado w-full text-10">
          
         e) Guía de remisión y acta de entrega-recepción, en el caso de donaciones en especie (bienes muebles o equipos), adjuntando inventario valorado.    

        </div>

        <div class="texto-justificado w-full text-10">
          
         f) Cualquier otro documento financiero o contable de carácter oficial que permita verificar de manera clara la ejecución del aporte o donación.    

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
          Adicionalmente, el Comité estará facultado para requerir información o aclaraciones complementarias que estime necesarias, con el fin de resolver cualquier duda que pudiera surgir en relación con la documentación presentada por el proponente. 

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
        En caso de no adjuntarse alguno de los instrumentos antes mencionados, o si la documentación presentada no permite establecer una relación clara entre el comprobante de venta y el aporte efectuado, dichos comprobantes no continuarán con el proceso de certificación, y se actuará conforme lo establece el artículo 5, inciso segundo del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reformas”. 

        </div>



        <div class="texto-justificado w-full text-10 mt-1">
          
          El artículo 49 de la normativa en mención indica: “De la emisión de la certificación. - El Comité procederá con el análisis de la información provista por la Dirección Financiera con el fin de proceder con la emisión o no de la certificación de beneficiarios de la deducibilidad. La decisión adoptada por el Comité será en el aplicativo informático o por medios electrónicos, y notificada por el/la secretario/a del Comité. La certificación contendrá, al menos, lo siguiente

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          a) El señalamiento del dictamen favorable emitido por el ente rector de Economía y Finanzas Públicas sobre el rango o valor máximo anual de aprobación de proyectos; 

        </div>


        <div class="texto-justificado w-full text-10">
          
         b) Los datos del deportista u organizador del programa y/o proyecto que recibe el aporte, junto con la identificación del proyecto y/o programa cuya prioridad ha sido calificada por el Ministerio del Deporte; 

        </div>


        <div class="texto-justificado w-full text-10">
          
          c) Los datos del patrocinador, gestor de la promoción o publicidad; 

        </div>

        <div class="texto-justificado w-full text-10">
          
          d) Los montos y fechas del patrocinio, promoción o publicidad; 

        </div>


        <div class="texto-justificado w-full text-10">
          
          e) En caso de aporte en bienes y/o servicios, la valoración de los mismos al precio de mercado; y, 

        </div>

         <div class="texto-justificado w-full text-10">
          
          f) Los demás que sean definidos por el Comité. 

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
          La notificación de la certificación de beneficiario será emitida a través del aplicativo informático o por otros medios electrónicos, y estará a cargo del/la secretario/a del Comité.  

        </div>

         <div class="texto-justificado w-full text-10 mt-1">
          
          Los certificados aprobados por el Comité serán suscritos por el/la Secretario/a, sentará razón de lo actuado por el Comité.  

        </div>


        <div class="texto-justificado w-full text-10 mt-1">
          
        Sin perjuicio de lo mencionado, y en el caso de que existiere pronunciamiento específico emitido por el Servicio de Rentas Internas, el Comité someterá su criterio al emitido por el referido ente de control para la emisión de las correspondientes certificaciones".

        </div>

        <div class="texto-justificado w-full text-10 mt-1">
          
        "Art. 50.- Del Informe de Cumplimiento. - Una vez ejecutado el programa y/o proyecto deportivo y generadas las certificaciones que correspondan, el/la solicitante presentarán al Comité un informe final que dé cuenta del cumplimiento de los objetivos, metas, componentes, montos, plazos, y demás datos relevantes establecidos en los mismos. Dicho informe será cargado a través del aplicativo informático adjuntando las evidencias fotográficas, memorias, certificaciones, u otros datos que permitan contrastar la información proporcionada. Adicionalmente, adjuntará una declaración juramentada celebrada ante notario público en la cual detalle, al menos, lo siguiente(...). El término concedido para la presentación del informe final será de 45 días contado desde la fecha en que el programa y/o proyecto deportivo concluyó su ejecución.".

        </div>

      ';

      return $htm;


    }

    public function portadaInicial($idEnviado,$idComite=null) {

      foreach ($this->informacionInicial($idEnviado) as $valor) {
        $nombreBd=$valor["nombre"];
        $idCredencialBd=$valor["idCredencial"];
        $fechaBd=$valor["fecha"];
      }

      foreach ($this->organismoPostulante($idCredencialBd) as $valor) {
        $proponenteBd=$valor["proponente"];
      }

      foreach ($this->representantePostulante($idCredencialBd) as $valor) {
        $representanteBd=$valor["representante"];
      }


      if(!empty($representanteBd)){
        $repreVariable="<span class='font-bold'>REPRESENTANTE:</span>&nbsp;$representanteBd";
      }else{
        $repreVariable="";
      }

      $asunto=$this->representante__asunto__comite($idComite);


      // $letraFecha=$this->formatearFecha($this->fecha);
      $letraFecha=$this->formatearFecha($fechaBd);

      $htm= "

        <div class='font-bold text-center w-full text-12'>
          NOTIFICACIÓN DE CALIFICACIÓN 
        </div>

        <div class='mt-2'>

          <span class='font-bold ml-1'>Asunto:</span> NOTIFICACIÓN DE CALIFICACIÓN DEL PROYECTO $nombreBd

        </div>

        <div class='mt-4'>

          <span class='font-bold'>PROPONENTE:</span>&nbsp;$proponenteBd

        </div>


        <div class='mt-1'>

          $repreVariable

        </div>


        <div class='mt-2'>

          <span class='font-bold'>De mi consideración: </span>

        </div>

        <div class='mt-2 justify__normal'>

          Mediante sesión $asunto de $letraFecha, el Comité de Calificación y Certificación para acceder al Incentivo Tributario del Ministerio del Deporte, conoció y analizó los proyectos o programas deportivos, con la finalidad de que se proceda con la deducción del 150% adicional, para el cálculo de la base imponible del impuesto a la renta establecido en el artículo 39 de la Ley Orgánica para el Desarrollo Económico y Sostenibilidad Fiscal tras la Pandemia Covid-19; artículo 10 numeral 19 inciso tercero de la Ley del Régimen Tributario Interno; y, artículo 28 numeral 11 literal e) del Reglamento para la Aplicación de la Ley de Régimen Tributario Interno. 

        </div>


        <div class='inline-block mt-1 justify__normal'>

          Al respecto, me permito informar lo siguiente: 

        </div>

      ";

      return $htm;


    }


    public function baseLegal() {

      $htm= '

        <div class="font-bold text-left w-full text-12 mt-2">
           I. BASE LEGAL:  
        </div>


        <div class="inline-block mt-2 justify__normal">

          El artículo 226 de la Constitución de la República del Ecuador señala que: “Las instituciones del Estado, sus organismos, dependencias, las servidoras o servidores públicos y las personas que actúen en virtud de una potestad estatal ejercerán solamente las competencias y facultades que les sean atribuidas en la Constitución y la ley. Tendrán el deber de coordinar acciones para el cumplimiento de sus fines y hacer efectivo el goce y ejercicio de los derechos reconocidos en la Constitución"; 

        </div>

        <div class="inline-block mt-1 justify__normal">

          El artículo 227 de la Constitución de la República del Ecuador, establece que: “La administración pública constituye un servicio a la colectividad que se rige por los principios de eficacia, eficiencia, calidad, jerarquía, desconcentración, descentralización, coordinación, participación, planificación, transparencia y evaluación"; 

        </div>

        <div class="inline-block mt-1 justify__normal">

          El artículo 381 de la Constitución de la República del Ecuador, determina: “El Estado protegerá, promoverá y coordinará la cultura física que comprende el deporte, la educación física y la recreación, como actividades que contribuyen a la salud, formación y desarrollo integral de las personas; impulsará el acceso masivo al deporte y a las actividades deportivas a nivel formativo, barrial y parroquial; auspiciará la preparación y participación de los deportistas en competencias nacionales e internacionales, que incluyen los Juegos Olímpicos y Paraolímpicos; y fomentará la participación de las personas con discapacidad. El Estado garantizará los recursos y la infraestructura necesaria para estas actividades. Los recursos se sujetarán al control estatal, rendición de cuentas y deberán distribuirse de forma equitativa";

        </div>

        <div class="inline-block mt-1 justify__normal">

          El artículo 65 del Código Orgánico Administrativo, dispone: “La competencia es la medida en la que la Constitución y la ley habilitan a un órgano para obrar y cumplir sus fines, en razón de la materia, el territorio, el tiempo y el grado"; 

         </div>

         <div class="inline-block mt-1 justify__normal">

          El artículo 164 del Código Orgánico Administrativo, establece: “Notificación. Es el acto por el cual se comunica a la persona interesada o a un conjunto indeterminado de personas, el contenido de un acto administrativo para que las personas interesadas estén en condiciones de ejercer sus derechos. La notificación de la primera actuación de las administraciones públicas se realizará personalmente, por boleta o a través del medio de comunicación, ordenado por estas. La notificación de las actuaciones de las administraciones públicas se practica por cualquier medio, físico o digital, que permita tener constancia de la transmisión y recepción de su contenido"; 

         </div>

         <div class="inline-block mt-1 justify__normal">

          El artículo 39 de la Ley Orgánica para el Desarrollo Económico y Sostenibilidad Fiscal tras la Pandemia Covid-19, determina que: “() Se deducirá el ciento cincuenta por ciento (150%) adicional para el cálculo dé la base imponible del impuesto a la renta, los gastos de publicidad, promoción y patrocinio, realizados a favor de deportistas, y programas, proyectos o eventos deportivos calificados por la entidad rectora competente en la materia. El reglamento a esta ley definirá los parámetros técnicos y requisitos formales a cumplirse para acceder a esta deducción adicional. (..)”; 

         </div>

         <div class="inline-block mt-1 justify__normal">

          El artículo 10 de la Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos respecto a la veracidad de la información prescribe que: “Las entidades reguladas por esta Ley presumirán que las declaraciones, documentos y actuaciones de las personas efectuadas en virtud de trámites administrativos son verdaderas, bajo aviso a la o al administrado de que, en caso de verificarse lo contrario, el trámite y resultado final de la gestión podrán ser negados y archivados, o los documentos emitidos carecerán de validez alguna, sin perjuicio de las sanciones y otros efectos jurídicos establecidos en la ley. El listado de actuaciones anuladas por la entidad en virtud de lo establecido en este inciso estará disponible para las demás entidades del Estado.”; 

         </div>

         <div class="inline-block mt-1 justify__normal">

          El artículo 36 del Reglamento a La Ley Orgánica para el Desarrollo Económico y Sostenibilidad Fiscal tras la Pandemia Covid-19, establece que: “(…) "e) Se podrá deducir el 150% adicional para el cálculo de la base imponible del impuesto a la renta, los gastos de publicidad, promoción y patrocinio realizados a favor de deportistas, y programas, proyectos o eventos deportivos calificados por la entidad rectora competente en la materia, según lo previsto en el respectivo documento de planificación estratégica así como con los límites y condiciones que esta emita para el efecto. 

         </div>

         <div class="inline-block mt-1 justify__normal">

          Para acceder a esta deducción adicional, se deberá considerar lo siguiente: 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">1.</span>&nbsp;Previo a aplicar la deducibilidad el beneficiario de la deducción adicional debe contar con una certificación emitida por el ente rector del deporte en la que, por cada beneficiario, conste al menos: 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold">a)</span>&nbsp;Los datos del deportista y organizador del programa o proyecto que recibe el aporte, junto con la identificación del proyecto, programa o evento cuando corresponda; 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold">b)</span>&nbsp;Los datos del patrocinador;

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold">c)</span>&nbsp;El monto y fecha del patrocinio; y, 

         </div>

         <div class="inline-block mt-1 justify__normal">

          <span class="font-bold">d)</span>&nbsp;La indicación de que: 

         </div>

         <div class="inline-block mt-1 justify__normal">

          <span class="font-bold">I. </span>&nbsp;El aporte se efectúa en apoyo a deportistas ecuatorianos; o, en apoyo a proyectos, programas o eventos deportivos realizados en el Ecuador; y, 

         </div>

         <div class="inline-block mt-1 justify__normal">

          <span class="font-bold">II. </span>&nbsp;El aporte se efectúa directamente al deportista o al organizador de los proyectos, programas o eventos deportivos, sin la participación de intermediarios. 

         </div>

         <div class="inline-block mt-1 justify__normal">

          En tales casos, previo a la emisión de la certificación se deberá contar con el dictamen favorable del ente rector de las finanzas públicas. Para el efecto el ente rector del deporte solicitará al organismo rector de las finanzas públicas, hasta el mes de noviembre de cada año, un dictamen a aplicarse para el ejercicio posterior, sobre el rango o valor máximo global anual de aprobación de proyectos, programas o eventos, con el fin de establecer el impacto fiscal correspondiente. (…)”; 

         </div>

         <div class="mt-1 justify__normal">

          El artículo 10 de la Ley Orgánica de Régimen Tributario Interno establece que: “Deducciones. - En general, con el propósito de determinar la base imponible sujeta a este impuesto se deducirán los gastos e inversiones que se efectúen con el propósito de obtener, mantener y mejorar los ingresos de fuente ecuatoriana que no estén exentos. 

         </div>

         <div class="mt-1 justify__normal">

          19. Los costos y gastos por promoción y publicidad de conformidad con las excepciones, límites, segmentación y condiciones establecidas en el Reglamento. (…) Se deducirá el ciento cincuenta por ciento (150%) adicional para el cálculo de la base imponible del impuesto a la renta, los gastos de publicidad, promoción y patrocinio, realizados a favor de deportistas, y programas, proyectos o eventos deportivos calificados por la entidad rectora competente en la materia. El reglamento a esta ley definirá los parámetros técnicos y requisitos formales a cumplirse para acceder a esta deducción adicional. (…)”; 

         </div>

         <div class="mt-1 justify__normal">

          El artículo 28 del Reglamento para la Aplicación de la Ley de Régimen Tributario Interno, dispone: “Gastos generales deducibles. - Bajo las condiciones descritas en el artículo precedente y siempre que no hubieren sido aplicados al costo de producción, son deducibles los gastos previstos por la Ley de Régimen Tributario Interno, en los términos señalados en ella y en este reglamento, tales como: (…) 11.- Promoción, publicidad y patrocinio. e.) Se podrá deducir el 150% adicional para el cálculo de la base imponible del impuesto a la renta, los gastos de publicidad, promoción y patrocinio realizados a favor de deportistas, y programas, proyectos o eventos deportivos calificados por la entidad rectora competente en la materia, según lo previsto en el respectivo documento de planificación estratégica, así como con los límites y condiciones que esta emita para el efecto. 

         </div>

         <div class="mt-1 justify__normal">

          Para acceder a esta deducción adicional, se deberá considerar lo siguiente: 

         </div>


         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">1.</span>Previo a aplicar la deducibilidad el beneficiario de la deducción adicional debe contar con una certificación emitida por el ente rector del deporte en la que, por cada beneficiario, conste al menos: 

         </div>
                   
        <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">a)</span>Los datos del deportista y organizador del programa o proyecto que recibe el aporte, junto con la identificación del proyecto, programa o evento cuando corresponda; 

         </div>
 
         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">b)</span>Los datos del patrocinador; 

         </div>
          
         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">c)</span>El monto y fecha del patrocinio; y, 

         </div>

 
         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">d)</span>La indicación de que: 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">I.</span>&nbsp;El aporte se efectúa en apoyo a deportistas ecuatorianos; o, en apoyo a proyectos, programas o eventos deportivos realizados en el Ecuador; y, 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">II.</span>&nbsp;El aporte se efectúa directamente al deportista o al organizador de los proyectos, programas o eventos deportivos, sin la participación de intermediarios. 

         </div>

         <div class="mt-1 justify__normal">

          En tales casos, previo a la emisión de la certificación se deberá contar con el dictamen favorable del ente rector de las finanzas públicas. Para el efecto el ente rector del deporte solicitará al organismo rector de las finanzas públicas, hasta el mes de noviembre de cada año, un dictamen a aplicarse para el ejercicio posterior, sobre el rango o valor máximo global anual de aprobación de proyectos, programas o eventos, con el fin de establecer el impacto fiscal correspondiente. En caso de que no se obtenga el dictamen del ente rector de las finanzas públicas hasta el mes de diciembre del año en el que se presentó la solicitud, el último monto aprobado se entenderá prorrogado para el siguiente ejercicio fiscal. 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">2.</span>&nbsp;Los desembolsos efectivamente realizados por concepto del patrocinio, promoción o publicidad deberán estar debidamente sustentados en los respectivos comprobantes de venta de acuerdo con lo establecido en la ley; además deberá realizarse las retenciones de impuestos cuando corresponda. 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">3.</span>&nbsp;En el caso de aporte en bienes o servicios, éstos deberán ser valorados al precio comercial, cumpliendo el pago de los impuestos indirectos que correspondan por este aporte. En estos casos dicha valoración deberá constar en el certificado referido en el número 1. 

         </div>


         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">4.</span>&nbsp;El ingreso (en dinero, bienes o servicios) que reciban los deportistas o los organizadores de los proyectos o programas deportivos por este patrocinio, atenderán al concepto de renta de sujetos residentes en el Ecuador establecido en la Ley de Régimen Tributario Interno. 

         </div>


         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">5.</span>&nbsp;Cuando se traten de programas y proyectos estos deberán ser calificados por la entidad rectora competente en la materia. La deducción adicional establecida no podrá generar una pérdida tributaria sujeta a amortización. En el caso de que la asignación de recursos a los que se refiere este numeral se la efectúe en varios ejercicios fiscales, para utilizar la deducción, se deberá contar por cada ejercicio fiscal con el certificado antes mencionado”; 

         </div>


         <div class="mt-1 justify__normal">

          El artículo 13 de la Ley del Deporte, Educación Física y Recreación, establece: “El Ministerio Sectorial es el órgano rector y planificador del deporte, educación física y recreación; le corresponde establecer, ejercer, garantizar y aplicar las políticas, directrices y planes aplicables en las áreas correspondientes para el desarrollo del sector de conformidad con lo dispuesto en la Constitución, las leyes, instrumentos internacionales y reglamentos aplicables. Tendrá dos objetivos principales, la activación de la población para asegurar la salud de las y los ciudadanos y facilitar la consecución de logros deportivos a nivel nacional e internacional de las y los deportistas incluyendo, aquellos que tengan algún tipo de discapacidad"; 

         </div>


         <div class="mt-1 justify__normal">

          Mediante Acuerdo Nro. 0243 de 21 de noviembre de 2023, el licenciado Juan Sebastián Palacios Muñoz, en calidad de Ministro del Deporte expidió: <span class="font-bold ml-1">“LA CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA DE LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O  EVENTOS DEPORTIVOS”</span>, y derogó al Acuerdo Nro. 0434 y sus reformas; 

         </div>


         <div class="mt-1 justify__normal">

          El artículo 4 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, establece: <span class="font-bold ml-1">“De las atribuciones del Comité.</span> - Son atribuciones del Comité las siguientes: 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">a)</span>Declarar la prioridad de sectores y niveles deportivos a nombre del Ministerio del Deporte; pudiendo adicionalmente definir techos presupuestarios para cada uno de ellos, y/o establecer límites a los montos de certificación por programa y/o proyecto deportivo; 

         </div>

         <div class="mt-1 justify__normal">

          <span class="font-bold ml-1">b)</span>Declarar la prioridad de sectores y niveles deportivos a nombre del Ministerio del Deporte; pudiendo adicionalmente definir techos presupuestarios para cada uno de ellos, y/o establecer límites a los montos de certificación por programa y/o proyecto deportivo; 

         </div>

         <div class="mt-1 justify__normal">

          El artículo 9, ibidem, señala:<span class="ml-1">“De la priorización de sectores y niveles deportivos.</span>- El Ministerio del Deporte a través del Comité, priorizará los sectores y niveles deportivos que requieren impulso. Dicha priorización será emitida mediante resolución y puesta en conocimiento del Sistema Deportivo Nacional y de la ciudadanía en general a través de la página web institucional, con el fin de promover la generación de programas y/o proyectos deportivos que coadyuven al cumplimiento de las metas y objetivos institucionales.”; 

         </div>


         <div class="mt-1 justify__normal">

          El artículo 24 ut supra, indica: “De la calificación de prioridad de los programas y/o proyectos deportivos: Podrán ser calificados como prioritarios los programas y/o proyectos deportivos que se ajusten a los criterios y componentes señalados en los artículos precedentes. Dicha calificación habilitará a los peticionarios postular a la siguiente fase del proceso. En cumplimiento al Reglamento a la Ley de Régimen Tributario interno, se excluirán de la deducción adicional el monto de publicidad y/o patrocinios efectuados en favor de deportistas, programas y/o proyectos deportivos cuya prioridad no sea calificada por el Ministerio del Deporte a través del Comité de Calificación y Certificación para acceder al incentivo tributario. El proceso de calificación seguirá las reglas contenidas en los artículos siguientes.”; 

         </div>

         <div class="mt-1 justify__normal">

          El artículo 43 del precitado Acuerdo Ministerial dispone: “De la Certificación de beneficiarios.- Una vez cumplidos los procedimientos establecidos en los artículos precedentes, los/las solicitantes podrán requerir al Comité la emisión de la certificación a favor de los beneficiarios de la deducción del 100% adicional para el cálculo de la base imponible del impuesto a la renta, entendiéndose por tales a quienes hubiesen efectuado gastos por concepto de promoción, publicidad y/o patrocinio a favor de deportistas u organizadores de programas y/o proyectos deportivos. 

         </div>

         <div class="mt-1 justify__normal">

          Para aplicar a este proceso, se podrán reconocer los gastos en promoción, publicidad y/o patrocinios generados dentro del ejercicio fiscal correspondiente y siempre que los mismos guarden relación con los componentes establecidos en los programas y/o proyectos cuya prioridad haya sido calificada por el Ministerio del Deporte. En ese sentido, se aplicarán las definiciones contenidas en el Reglamento para la aplicación de la Ley de Régimen Tributario Interno referentes a los costos y gastos de promoción, publicidad y patrocinio. 

         </div>

          <div class="mt-1 justify__normal">

            En cumplimiento a la Ley de Régimen Tributario Interno y su Reglamento, las certificaciones deberán emitirse para cada ejercicio fiscal. Este principio aplica también para los procesos de certificación relacionados a los programas y/o proyectos plurianuales, debiendo cumplirse de manera adicional el procedimiento establecido en el artículo 38 de la presente norma. En tal sentido, no podrán emitirse certificaciones que contemplen gastos generados en varios años. En tal sentido, no podrán emitirse certificaciones que contemplen gastos generados en varios años.”; 

          </div>

          <div class="mt-1 justify__normal">

            El artículo 45 del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, señala:<span class="ml-1">“Del procedimiento para obtener la certificación. </span>- Para la emisión de la certificación, el/la solicitante deberá ingresar al aplicativo informático y cargar el o los comprobantes de venta de patrocinio, promoción o publicidad emitidos a favor del beneficiario de la deducibilidad. Dichos comprobantes deberán cumplir los requisitos, características y demás criterios establecidos en la normativa legal vigente. 

          </div>

          <div class="mt-1 justify__normal">

            Los comprobantes de venta guardarán relación directa con el monto establecido en los programas y/o proyectos calificados como prioritarios. 

          </div>

          <div class="mt-1 justify__normal">

            En su descripción o concepto se hará referencia de manera expresa a la ejecución de los citados programas y/o proyectos. Con base a lo establecido en el Reglamento para la aplicación de la Ley de Régimen Tributario Interno, en el caso de aportes en bienes o servicios, éstos deberán ser valorados al precio de mercado, cumpliendo el pago de los impuestos indirectos que correspondan por este aporte. En estos casos, se deberá agregar la evidencia documental que avale el valor del aporte realizado por medio de bienes o servicios. No se reconocerá comprobantes de venta duplicados.”; 

          </div>


          <div class="mt-1 justify__normal">

            El artículo 50 ibidem, dispone lo siguiente: <span class="ml-1">"Del informe final de cumplimiento.</span> - Una vez ejecutado el programa y/o proyecto deportivo y generadas las certificaciones que correspondan, el/la solicitante presentará al Comité un informe final que dé cuenta del cumplimiento de los objetivos, metas, componentes, montos, plazos y demás datos relevantes establecidos en los mismos. Dicho informe será cargado a través del aplicativo informático adjuntando las evidencias fotográficas, memorias, certificaciones, u otros documentos que permitan contrastar la información proporcionada. Adicionalmente, adjuntará una declaración juramentada celebrada ante notario público”. 

          </div>


          <div class="mt-1 justify__normal">

            La Disposición General Décima Tercera del Acuerdo Ministerial utsupra, establece: “La emisión de la calificación de prioridad de programas y/o proyectos deportivos no constituye obligación ni derecho adquirido alguno para que el Ministerio del Deporte emita la certificación de beneficiarios de la deducibilidad, proceso que se efectuará siempre y cuando se cuente con el monto aprobado por el ente rector de las finanzas públicas a través del dictamen correspondiente.”; 

          </div>

          <div class="mt-1 justify__normal">

            La Disposición General Décima Cuarta del precitado Acuerdo Ministerial, dispone: “Para la emisión de la certificación a favor de beneficiarios de la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta, se respetará el orden de ingreso de las solicitudes de certificación a través del aplicativo informático, así como el monto anual autorizado de conformidad al dictamen emitido por el ente rector de Economía y Finanzas Públicas. En caso de que se complete dicho monto no se podrán emitir certificaciones adicionales, situación que no constituirá causal para reclamaciones de carácter administrativas o judiciales en contra del Ministerio del Deporte.”; 

          </div>


          <div class="mt-1 justify__normal">

            La Disposición General Décima Quinta, del Acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023, manifiesta: “Una vez certificado el monto total aprobado por el ente rector de las finanzas públicas al que hace referencia el Reglamento a la Ley de Régimen Tributario Interno, se notificará de dicho particular a los representantes de los programas y/o proyectos deportivos calificados que no lograron obtener la certificación, informando la imposibilidad de emitir nuevas certificaciones dentro del correspondiente ejercicio fiscal.”; 

          </div>


          <div class="mt-1 justify__normal">

            Conforme el Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, se estable en Disposición Décima Sexta lo siguiente "Con la finalidad de garantizar la transparencia en el manejo de la información y la correcta tramitación de los requerimientos, las comunicaciones y notificaciones entre los actores que intervienen en el proceso de incentivo tributario en relación a las actividades y funciones de la Secretaría del Comité, se podrá realizar el uso de otros canales digitales.”; 

          </div>

          <div class="mt-1 justify__normal">

            Mediante Acta Nro. 001-2023, de 31 de enero de 2023, de sesión Ordinaria del Comité de Calificación y Certificación para Acceder al Incentivo Tributario se resolvió lo siguiente: “(…) con el fin de promover la generación de programas y/o proyectos deportivos que coadyuven al cumplimiento de las metas y objetivos institucionales; y, principalmente, democratizar el acceso al incentivo tributario, procurando que este beneficio pueda ser distribuido de manera equitativa entre los solicitantes y procurando que este beneficio pueda ser distribuido de maneta equitativa entre los solicitante y procurando que el mayor número de programas y/o proyectos resulte beneficiado del nuevo porcentaje en la Ley Orgánica para el Desarrollo Económico y Sostenibilidad Fiscal tras la Pandemia Covid-19 y su Reglamento, en cumplimiento de las atribuciones conferidas a través del Acuerdo Ministerial Nro. 0434 de 17 de noviembre de 2021, reformado mediante Acuerdo Ministerial Nro. 0035 de 3 de febrero de 2022, sugiere al Comité de Calificación y Certificación para acceder al Incentivo Tributario, establecer como monto máximo de certificación de USD$ 1´000.000,00 ( Un millón de dólares de los Estados Unidos de América) por solicitante y por programa y/o proyecto deportivo.”. 

          </div>


      ';

      return $htm;


    }


}