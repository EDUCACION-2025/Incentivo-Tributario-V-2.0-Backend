<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;
use App\utilities\validaciones\NumerosLetras;

class Solicitud {

	private static $instance = null;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');
        $this->anio=date('Y');

        $this->constructor = ServicesAdmin::getInstance();
        $this->numerosLetras = NumerosLetras::getInstance();

    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Solicitud();
        }
        return self::$instance;
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

    public function informacionProyecto__datosGenerales__v1($codigo) {

        return $this->constructor->select__general__talento("SELECT UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto, IFNULL((SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombreOrganismo, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombreCompleto, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS nombreSolicitante, IFNULL((SELECT a1.rucOrganismo FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.cedulaUsuario FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS credencialSolicitante, IFNULL((SELECT a1.email FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.email FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS correoSolicitante, IFNULL((SELECT a1.telefono FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.telefono FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS celularSolicitante, IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(a.tipoDeportistas = 'actividadFisica', UPPER('Educación Física'), IF(a.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(a.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreación'))))) AS sector, (SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), '%d '), CASE MONTH(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y')) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), ' %Y')) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaInicioCLetras,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica,IFNULL((SELECT CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')) FROM pro_infraselects AS a1 WHERE a1.codigo=a.codigo AND a1.tipoTramite LIKE '%infra%' ORDER BY a1.idProyectoSeleccionas DESC LIMIT 1),' ') AS alineacionInfra, ROUND(SUM(a.monto),2) AS monto,(SELECT COUNT(a1.codigo) FROM pro_beneficiarios_directos AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.codigo) AS beneficiarios,(SELECT IF(a1.mensajePlurianual IS NULL OR a1.mensajePlurianual='normal','NO','SI') AS plurianual FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS plurianual,(SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), '%d '), CASE MONTH(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y')) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), ' %Y')) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaFinCLetras,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.objetivoGeneralCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS objetivoGeneral,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.justificacionCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS justificacion,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad',2, IF(a.tipoDeportistas = 'actividadFisica',4, IF(a.tipoDeportistas = 'formativo',1, IF(a.tipoDeportistas = 'profesional', 3, 5)))) AS idSector,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.justificacionCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS justificacionProyecto,IFNULL((SELECT a1.rucOrganismo FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT CONCAT_WS('','001',a1.cedulaUsuario) FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS rucProponenteIdentificado,a.fechaCalifica,(SELECT DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), '%Y-%m-%d')  FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaInicioC,(SELECT DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), '%Y-%m-%d')  FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaFinC FROM pro_proyecto AS a WHERE a.codigo = '$codigo' GROUP BY a.codigo;");


    }


    public function informacionProyecto__datosGenerales($codigo) {

        return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,IF(b.nombre IS NOT NULL, UPPER(b.nombre), UPPER(z.razonSocial)) AS nombreSolicitante,IF(b.nombre IS NOT NULL, UPPER(b.cedula), UPPER(z.ruc)) AS credencialSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.email1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.correo1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial)) AS correoSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.celular1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.celular1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial)) AS celularSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector,a.justificacionProyecto,IF(b.nombre IS NULL,z.ruc,IF((SELECT a1.idRepresentante FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1) IS NOT NULL, CONCAT_WS('001',(SELECT a1.cedula FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)),CONCAT_WS('001',b.cedula))) AS rucProponenteIdentificado,IF((SELECT a1.idRepresentante FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial) IS NOT NULL,(SELECT a1.nombre FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial) ,b.nombre) AS nombreRepresentanteP,IF((SELECT a1.idRepresentante FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial) IS NOT NULL,(SELECT a1.cedula FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial) ,b.nombre) AS cedulaRepresentanteP,a.fechaInicio AS fechaInicioC, a.fechaFin AS fechaFinC  FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo LEFT JOIN configuracion.organismo AS z ON z.idCredencial=a.idCredencial WHERE a.codigo='$codigo' GROUP BY a.codigo;");


    }

    public function proyecto__enviado__comite($idEnviado) {
        return $this->constructor->select__general__incentivo("SELECT idEnviado,idComite,fecha,hora FROM proyecto_enviado_comite_finalizado WHERE idEnviado='$idEnviado';");
    }

    public function suma__anual__proyectos($anio,$codigo) {

     	$consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE idNivel1 IS NOT NULL AND total>0 AND anio='$anio' AND codigo='$codigo' GROUP BY codigo,anio;");
     	foreach ($consulta as $valor) {
     		$total=$valor["total"];
     	}

     	return$total;

    }


    public function montos__anuales__existentes__v1($codigo) {

        $array=array();

        $consulta=$this->constructor->select__general__talento("SELECT ROUND(presupuesto, 2) AS presupuestoAnio1,ROUND(presupuesto2, 2) AS presupuestoAnio2,ROUND(presupuesto3, 2) AS presupuestoAnio3,ROUND(presupuestoCuatro, 2) AS presupuestoAnio4,DATE_FORMAT(STR_TO_DATE(inicioPeriodos, '%d/%m/%Y'), '%Y-%m-%d') AS fechaInicio,DATE_FORMAT(STR_TO_DATE(finPeriodos, '%d/%m/%Y'), '%Y-%m-%d') AS fechaFin,DATE_FORMAT(STR_TO_DATE(inicioPeriodos, '%Y'), '%Y') AS anioInicio,DATE_FORMAT(STR_TO_DATE(finPeriodos, '%Y'), '%Y') AS anioFin FROM pro_proyetosreferencias WHERE codigoProyecto='$codigo' ORDER BY idProyectoReferencias DESC LIMIT 1;");

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
                array_push($array, $presupuestoAnio1."__".intval($array__FechaInicio[0]));
            }

            if($i===1){
                array_push($array, $presupuestoAnio2."__".(intval($array__FechaInicio[0]) + 1));
            }

            if($i===2){
                array_push($array, $presupuestoAnio3."__".(intval($array__FechaInicio[0]) + 2));
            }

            if($i===3){
                array_push($array, $presupuestoAnio4."__".(intval($array__FechaInicio[0]) + 3));
            }

        }

        return $array;

    }

    public function montos__anuales__existentes($codigo) {

        $array=array();

        $consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total,anio FROM proyecto_presupuesto WHERE idNivel1 IS NOT NULL AND total>0 AND codigo='$codigo' GROUP BY anio;");
        foreach ($consulta as $valor) {
            array_push($array, $valor["total"]."__".$valor["anio"]);
        }

        return $array;

    }

    public function proyecto__enviado($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,codigo FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
    }


    public function pro_repreentante__elegible($idCredencial) {
        return $this->constructor->select__general("SELECT IFNULL((SELECT nombre FROM representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),IFNULL((SELECT a1.razonSocial FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.nombre FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1))) AS nombreRepresentanteP,IFNULL((SELECT cedula FROM representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1))) AS cedulaRepresentanteP FROM credencial AS a WHERE a.idCredencial='$idCredencial';");
    }

    public function solicitud__de__continuidad__v1($codigo,$anio,$idCredencial) {

        $consulta=$this->proyecto__enviado($codigo);
        foreach ($consulta as $valor) {
            $idEnviadoBd=$valor["id"];
        }


        $fecha__califiacion__comite =$this->formatearFecha($fechaBd);

        $informacionGeneral=$this->informacionProyecto__datosGenerales__v1($codigo);
        $informacionGeneral__representante__legal=$this->pro_repreentante__elegible($idCredencial);

        $formatearFecha__actual=$this->formatearFecha($this->fecha);


        $nomenclatura="";
        if (count($informacionGeneral__representante__legal[0]['cedulaRepresentanteP'])<=10) {
           $nomenclatura="número de cédula de ciudadanía";
        }else{
            $nomenclatura="RUC";
        }

        $arrayMontos=$this->montos__anuales__existentes__v1($codigo);

        $valorEncontrado = array_values(array_filter($arrayMontos, function($v) {return strpos($v, (string)$this->anio) !== false;}))[0] ?? "Año no encontrado";
        $arrayValores = explode("__", $valorEncontrado);


        $htm.= "

           <div class='font-size__14 text-center mt-2 font-bold'>
            Solicitud de continuidad 
           </div>

           <div class='text-right font-bold'>
                Quito, $formatearFecha__actual
           </div>

           <div class='font-size__14 mt-4 texto-justificado font-bold'>
            Estimados
           </div>

           <div class='font-size__14 mt-1 texto-justificado'>
            Ministerio del Deporte,
           </div>


           <div class='font-size__14 mt-1 texto-justificado'>
             De mi consideración: Yo,  ".$informacionGeneral__representante__legal[0]['nombreRepresentanteP'].", con $nomenclatura ".$informacionGeneral__representante__legal[0]['cedulaRepresentanteP'].", en relación con el programa y/o proyecto deportivo denominado ".$informacionGeneral[0]['nombreProyecto'].", manifiesto lo siguiente: El referido proyecto fue calificado por el Comité de Calificación y Certificación para Acceder al Incentivo Tributario el ".$informacionGeneral[0]['fechaCalifica'].", y contempla la ejecución de componentes en más de un ejercicio fiscal, es decir del ".$this->formatearFecha($informacionGeneral[0]['fechaInicioC'])." al ".$this->formatearFecha($informacionGeneral[0]['fechaFinC']).", siendo en el año ".$arrayValores[1]." un valor de USD ".number_format($arrayValores[0], 2, ',', '.')."; en cumplimiento del artículo 38 del Acuerdo Ministerial No. 0243 de 21 de noviembre de 2023, confirmo mi intención de continuar con la ejecución del programa y/o proyecto deportivo ".$informacionGeneral[0]['nombreProyecto'].", para tal efecto, solicitó la renovación de la calificación de prioridad para el presente ejercicio fiscal, conforme se detalla el presupuesto por años: 
           </div>

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center'>
                            MONTO
                        </th>

                        <th class='background-color__blue text-center'>
                            AÑO
                        </th>


                    </tr>

                </thead>

                <tbody>

           ";

        $sumador=0;

        foreach ($this->montos__anuales__existentes__v1($codigo) as $valor) {
            
            $array = explode('__', $valor);

            $htm.="

                <tr>

                    <td align='center'>
                        ".$array[1]."
                    </td>

                    <td  align='center'>
                       ".number_format($array[0], 2, ',', '.')."
                    </td>

                </tr>

            ";

            $sumador=intval($array[0]) + intval($sumador);

        }

        $htm.="

                </tbody>

                <tfoot>

                    <tr>

                        <td class='font-bold' align='center'>
                            Total
                        </td>


                        <td class='font-bold' align='center'>
                            ".number_format($sumador, 2, ',', '.')."
                        </td>

                    </tr>

                 </tbody>

            </table>

           <div class='font-size__14 mt-4 texto-justificado font-bold'>
            Con sentimientos de distinguida consideración.  
           </div>

           <div class='font-size__14 mt-4 texto-justificado font-bold'>
            Atentamente, 
           </div>

        ";


        return $htm;

    }


    public function solicitud__de__continuidad($codigo,$anio) {

        $consulta=$this->proyecto__enviado($codigo);
        foreach ($consulta as $valor) {
            $idEnviadoBd=$valor["id"];
        }

        $consulta=$this->proyecto__enviado__comite($idEnviadoBd);
        foreach ($consulta as $valor) {
            $fechaBd=$valor["fecha"];
            $horaBd=$valor["hora"];
            $idComiteBd=$valor["idComite"];
        }

        $fecha__califiacion__comite =$this->formatearFecha($fechaBd);

        $informacionGeneral=$this->informacionProyecto__datosGenerales($codigo);

        $formatearFecha__actual=$this->formatearFecha($this->fecha);


        $nomenclatura="";
        if (count($$informacionGeneral[0]['cedulaRepresentanteP'])<=10) {
           $nomenclatura="número de cédula de ciudadanía";
        }else{
            $nomenclatura="RUC";
        }

        $htm.= "

           <div class='font-size__14 text-center mt-2 font-bold'>
           	Solicitud de continuidad 
           </div>

           <div class='text-right font-bold'>
                Quito, $formatearFecha__actual
           </div>

           <div class='font-size__14 mt-4 texto-justificado font-bold'>
           	Estimados
           </div>

           <div class='font-size__14 mt-1 texto-justificado'>
           	Ministerio del Deporte,
           </div>


           <div class='font-size__14 mt-1 texto-justificado'>
             De mi consideración: Yo,  ".$informacionGeneral[0]['nombreRepresentanteP'].", con $nomenclatura ".$informacionGeneral[0]['cedulaRepresentanteP'].", en relación con el programa y/o proyecto deportivo denominado ".$informacionGeneral[0]['nombreProyecto'].", manifiesto lo siguiente: El referido proyecto fue calificado por el Comité de Calificación y Certificación para Acceder al Incentivo Tributario el $fecha__califiacion__comite, y contempla la ejecución de componentes en más de un ejercicio fiscal, es decir del ".$this->formatearFecha($informacionGeneral[0]['fechaInicioC'])." al ".$this->formatearFecha($informacionGeneral[0]['fechaFinC']).", por un valor de USD ".number_format($informacionGeneral[0]['monto'], 2, ',', '.')."; en cumplimiento del artículo 38 del Acuerdo Ministerial No. 0243 de 21 de noviembre de 2023, confirmo mi intención de continuar con la ejecución del programa y/o proyecto deportivo ".$informacionGeneral[0]['nombreProyecto'].", para tal efecto, solicitó la renovación de la calificación de prioridad para el presente ejercicio fiscal, conforme se detalla el presupuesto por años: 
           </div>

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center'>
                            MONTO
                        </th>

                        <th class='background-color__blue text-center'>
                            AÑO
                        </th>


                    </tr>

                </thead>

                <tbody>

           ";

        $sumador=0;

        foreach ($this->montos__anuales__existentes($codigo) as $valor) {
            
            $array = explode('__', $valor);

            $htm.="

                <tr>

                    <td align='center'>
                        ".$array[1]."
                    </td>

                    <td  align='center'>
                       ".number_format($array[0], 2, ',', '.')."
                    </td>

                </tr>

            ";

            $sumador=intval($array[0]) + intval($sumador);

        }

        $htm.="

                </tbody>

                <tfoot>

                    <tr>

                        <td class='font-bold' align='center'>
                            Total
                        </td>


                        <td class='font-bold' align='center'>
                            $sumador
                        </td>

                    </tr>

                 </tbody>

            </table>

           <div class='font-size__14 mt-4 texto-justificado font-bold'>
            Con sentimientos de distinguida consideración.  
           </div>

           <div class='font-size__14 mt-4 texto-justificado font-bold'>
           	Atentamente, 
           </div>

        ";


        return $htm;

    }



}