<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;
use App\utilities\validaciones\NumerosLetras;

class InformeC
{

    private static $instance = null;

    public function __construct()
    {

        date_default_timezone_set("America/Guayaquil");

        $this->fecha = date('Y-m-d');
        $this->hora = date('H:i:s');

        $this->constructor = ServicesAdmin::getInstance();
        $this->numerosLetras = NumerosLetras::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new InformeC();
        }
        return self::$instance;
    }



    public function informacionProyecto__solicitud__modificacion($idSolicitud)
    {

        return $this->constructor->select__general__incentivo("SELECT IF(casoModificar='a','Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(casoModificar='b','Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','Modificaciones de la vigencia del proyecto de anual a plurianual')) AS caso FROM proyecto_modificacion_solicitud WHERE idSolicitud='$idSolicitud';");
    }

    public function justificacion__modificacion__componetnes__presupuestos($codigo)
    {

        $consulta = $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_componentes_modificacion WHERE codigoUsuario='$codigo';");
        foreach ($consulta as  $valor) {
            $textoObservacion = $valor["textoObservacion"];
        }

        return $textoObservacion;
    }


    public function justificacion__modificacion__cronograma__de__actividades($codigo)
    {

        $consulta = $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_cronogramaactividades_modificacion WHERE codigoUsuario='$codigo';");
        foreach ($consulta as  $valor) {
            $textoObservacion = $valor["textoObservacion"];
        }

        return $textoObservacion;
    }

    public function informacionProyecto__datosGenerales__modificado($codigo, $idSolicitud)
    {

        $consulta = $this->constructor->select__general__incentivo("SELECT a.idDescripcion FROM incentivorespaldo.proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idSolicitud' GROUP BY a.codigo;");

        foreach ($consulta as $valor) {
            $idDescripcionBd = $valor["idDescripcion"];
        }


        if (!empty($idDescripcionBd)) {
            return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,UPPER(b.nombre) AS nombreSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector FROM incentivorespaldo.proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idSolicitud' GROUP BY a.codigo;");
        } else {
            return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,UPPER(b.nombre) AS nombreSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' GROUP BY a.codigo;");
        }
    }

    public function informacionProyecto__datosGenerales__v1($codigo)
    {

        return $this->constructor->select__general__talento("SELECT UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto, IFNULL((SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombreOrganismo, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombreCompleto, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS nombreSolicitante, IFNULL((SELECT a1.rucOrganismo FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.cedulaUsuario FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS credencialSolicitante, IFNULL((SELECT a1.email FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.email FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS correoSolicitante, IFNULL((SELECT a1.telefono FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT a1.telefono FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS celularSolicitante, IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(a.tipoDeportistas = 'actividadFisica', UPPER('Educación Física'), IF(a.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(a.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreación'))))) AS sector, (SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), '%d '), CASE MONTH(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y')) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y'), ' %Y')) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaInicio,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica,IFNULL((SELECT CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')) FROM pro_infraselects AS a1 WHERE a1.codigo=a.codigo AND a1.tipoTramite LIKE '%infra%' ORDER BY a1.idProyectoSeleccionas DESC LIMIT 1),' ') AS alineacionInfra, ROUND(SUM(a.monto),2) AS monto,(SELECT COUNT(a1.codigo) FROM pro_beneficiarios_directos AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.codigo) AS beneficiarios,(SELECT IF(a1.mensajePlurianual IS NULL OR a1.mensajePlurianual='normal','NO','SI') AS plurianual FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS plurianual,(SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), '%d '), CASE MONTH(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y')) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y'), ' %Y')) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaFin,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.objetivoGeneralCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS objetivoGeneral,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.justificacionCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS justificacion,IF(a.tipoDeportistas = 'alto' OR a.tipoDeportistas = 'alto2' OR a.tipoDeportistas = 'altoRendimiento' OR a.tipoDeportistas = 'altoRendimientoDiscapacidad',2, IF(a.tipoDeportistas = 'actividadFisica',4, IF(a.tipoDeportistas = 'formativo',1, IF(a.tipoDeportistas = 'profesional', 3, 5)))) AS idSector,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.justificacionCaracterizacion, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM pro_caracterizacion AS a1 WHERE a1.codigo=a.codigo ORDER BY a1.idCaracterizacion DESC LIMIT 1) AS justificacionProyecto,IFNULL((SELECT a1.rucOrganismo FROM pro_federacion AS a1 WHERE a1.usuario = a.idUsuario), (SELECT CONCAT_WS('',a1.cedulaUsuario,'001') FROM pro_deportistaorganismo AS a1 WHERE a1.usuario = a.idUsuario)) AS rucProponenteIdentificado,a.fechaCalifica FROM pro_proyecto AS a WHERE a.codigo = '$codigo' GROUP BY a.codigo;");
    }

    public function informacionProyecto__datosGenerales($codigo)
    {

        return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,IF(b.nombre IS NOT NULL, UPPER(b.nombre), UPPER(z.razonSocial)) AS nombreSolicitante,IF(b.nombre IS NOT NULL, UPPER(b.cedula), UPPER(z.ruc)) AS credencialSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.email1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial LIMIT 1),(SELECT a1.correo1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial LIMIT 1)) AS correoSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.celular1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial LIMIT 1),(SELECT a1.celular1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial LIMIT 1)) AS celularSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, (SELECT SUM(a1.total) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.codigo LIMIT 1) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.codigo LIMIT 1)  AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,a.justificacionProyecto AS justificacion,c.idSector,a.justificacionProyecto,IF(b.nombre IS NULL,z.ruc,IF((SELECT a1.idRepresentante FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1) IS NOT NULL, CONCAT_WS('001',(SELECT a1.cedula FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)),CONCAT_WS('',b.cedula,'001'))) AS rucProponenteIdentificado FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo LEFT JOIN configuracion.organismo AS z ON z.idCredencial=a.idCredencial WHERE a.codigo='$codigo' GROUP BY a.codigo;");
    }

    public function requisitos__documentos($codigo)
    {

        $array = array();

        $consultaRequisitos = $this->constructor->select__general__incentivo("SELECT IF(curriculoDeportivo IS NOT NULL,'Currículo deportivo justificado documentadamente','NO') AS curriculoDeportivo,IF(certificadoTrayectoria IS NOT NULL,'Certificaciones que acrediten su trayectoria deportiva','NO') AS certificadoTrayectoria,IF(documentoLegalProponente IS NOT NULL,'Deporte profesional: documentación legal del proponente, establecido en el manual técnico','NO') AS documentoLegalProponente,IF(ruc IS NOT NULL,'RUC','NO') AS ruc,IF(curriculoExperiencia IS NOT NULL,'Currículo de experiencia afín comprobable','NO') AS curriculoExperiencia,IF(tituloPropiedad IS NOT NULL,'Título de propiedad o carta de autorización del propietario del inmueble en el cual se implantará la obra','NO') AS tituloPropiedad,IF(memoriaTecnica IS NOT NULL,'Memoria técnica arquitectónica','NO') AS memoriaTecnica,IF(planosArquitectonicos IS NOT NULL,'Planos arquitectónicos o el que corresponda según el objeto del programa y/o proyecto, con la firma de responsabilidad del/la profesional responsable del diseño','NO') AS planosArquitectonicos,IF(respaldoDigitales IS NOT NULL,'Respaldos digitales del proyecto','NO') AS respaldoDigitales  FROM proyecto_documentos_requisitos WHERE codigo='$codigo';");

        foreach ($consultaRequisitos as $valor) {
            if ($valor["curriculoDeportivo"] !== "NO") {
                array_push($array, $valor["curriculoDeportivo"]);
            }
            if ($valor["certificadoTrayectoria"] !== "NO") {
                array_push($array, $valor["certificadoTrayectoria"]);
            }
            if ($valor["documentoLegalProponente"] !== "NO") {
                array_push($array, $valor["documentoLegalProponente"]);
            }
            if ($valor["ruc"] !== "NO") {
                array_push($array, $valor["ruc"]);
            }
            if ($valor["curriculoExperiencia"] !== "NO") {
                array_push($array, $valor["curriculoExperiencia"]);
            }
            if ($valor["tituloPropiedad"] !== "NO") {
                array_push($array, $valor["tituloPropiedad"]);
            }
            if ($valor["memoriaTecnica"] !== "NO") {
                array_push($array, $valor["memoriaTecnica"]);
            }
            if ($valor["planosArquitectonicos"] !== "NO") {
                array_push($array, $valor["planosArquitectonicos"]);
            }
            if ($valor["respaldoDigitales"] !== "NO") {
                array_push($array, $valor["respaldoDigitales"]);
            }
        }

        return $array;
    }


    public function proyecto__enviado($codigo)
    {
        return $this->constructor->select__general__incentivo("SELECT id,codigo FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
    }

    public function proyecto__enviado__comite($idEnviado)
    {
        return $this->constructor->select__general__incentivo("SELECT idEnviado,idComite,fecha,hora FROM proyecto_enviado_comite_finalizado WHERE id='$idEnviado';");
    }

    public function proyecto__comite__creado($idComite)
    {
        return $this->constructor->select__general__incentivo("SELECT numeroComite,fecha,hora FROM comite WHERE id='$idComite';");
    }


    public function formatearFecha($fecha)
    {

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

    public function monto__comite__aprobado($codigo)
    {
        $consulta = $this->constructor->select__general__incentivo("SELECT SUM(a.total) AS totalSuma FROM proyecto_presupuesto AS a INNER JOIN proyecto_enviado AS b ON a.codigo=b.codigoUsuario WHERE b.codigoUsuario='$codigo' AND a.nivel!=0 GROUP BY a.codigo;");
        foreach ($consulta as $valor) {
            $totalSuma = $valor["totalSuma"];
        }
        return $totalSuma;
    }


    public function comite__acta__contenido($idComite)
    {
        return $this->constructor->select__general__incentivo("SELECT lugarActa,horaFinalizacion,ordenDia,desarrollo,primerPunto,asistentes,segundoPunto FROM comite_acta_contenido WHERE idComite='$idComite';");
    }

    public function buscarPalabra($texto)
    {

        $textoNormalizado = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
            ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'],
            mb_strtolower($texto)
        );

        if (strpos($textoNormalizado, 'extraordinaria') !== false) {
            return "extraordinaria";
        } else {
            return "ordinaria";
        }
    }

    public function observable__axios__informacion__facturas__v1($idFactura)
    {

        return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto,a.fecha,a.rucXml,a.razonSocialXml FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=a.codigo  WHERE a.id='$idFactura';");
    }

    public function observable__axios__informacion__facturas($idFactura)
    {

        return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo AS codigoUsuario,b.ruc,b.razonSocial,b.regimen, a.numeroFactura,a.fechaEmision,a.subotal,a.iva,a.total,a.ivaPorcentaje,a.tipoComprobante,a.gastoRealizar,a.idIncremental,a.fecha,a.hora,c.codigo AS codigoProyecto,d.nombre AS nombreProyecto,a.fecha,a.rucXml,a.razonSocialXml FROM proyecto_certificacion_factura_tramite AS a INNER JOIN certificacion_patrocinadores AS b ON a.idPatrocinador=b.id INNER JOIN proyecto_enviado AS c ON c.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS d ON d.codigo=a.codigo WHERE a.id='$idFactura';");
    }

    public function informacion__fechaComite($idEnviado)
    {
        return $this->constructor->select__general__incentivo("SELECT fecha FROM comite_proyectos WHERE idEnviado='$idEnviado' ORDER BY id LIMIT 1;");
    }

    public function antecedente__final__certificacion($idFactura)
    {

        $informacionGeneral = $this->observable__axios__informacion__facturas($idFactura);

        if ($informacionGeneral[0]['tipoComprobante'] === "Comprobante físico") {

            $htm.="
            
                <div class='font-size__10 texto-justificado mb-2 mt-2'>
                    ****Mediante OFICIO No. 917012023OJUR001042 del 02 de mayo de 2023, la Dirección Nacional Jurídica del Servicio de Rentas Internas, señala: “Las notas de venta emitidas por los contribuyentes sujetos al RIMPE Negocio Popular, no reflejan un IVA desglosado. Sin perjuicio de ello, es menester señalar que el flujo de recursos para gastos de publicidad, promoción y patrocinio, realizados a favor de deportistas y programas, proyectos o eventos deportivos, no se encuentran comprendidos en el objeto imponible del IVA, ya que no se perfecciona la prestación de algún servicio, transferencia de dominio o importación de bienes muebles de naturaleza corporal, o de derechos de autor, de propiedad industrial y derechos conexos, gravados con dicho impuesto.”
                </div>

            ";

        }

        $htm .= "

            <div class='font-size__10 texto-justificado mt-2'>
                ***Mediante Oficio Nro. SRI-NAC-DNC-2022-0047-OF del 23 de marzo de 2022, la Dirección Nacional de Control Tributario del Servicio de Rentas Internas, señala: “(…) los valores que se acogerán a la deducción adicional no deben incluir el IVA, sino solamente debe considerarse el subtotal de los comprobantes de venta relacionados a ese gasto.”; en este sentido se certifica el valor correspondiente del subtotal de los comprobantes de venta emitidos.
            </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            De conformidad a las competencias establecidas para el efecto, se ha procedido a verificar la validez de los citados comprobantes de venta en el portal WEB del Servicio de Rentas Internas, y se ha determinado que los comprobantes de venta son VÁLIDOS, ya que CUMPLEN con las disposiciones emitidas en el Capítulo III, Artículo 18 del Reglamento de comprobantes de venta, retención y documentos complementarios, que establece los REQUISITOS Y CARACTERÍSTICAS PARA LA EMISIÓN DE LOS COMPROBANTES DE VENTA, NOTAS DE CRÉDITO Y NOTAS DE DEBITO, y CUMPLEN con los demás criterios establecidos en los artículos 45, 47 y 48 del Acuerdo Ministerial Nro. 0243.

           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-2 font-bold'>
            RECOMENDACIONES
           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            Sobre lo expuesto y una vez que se ha analizado y verificado que los comprobantes de venta son válidos, y están llenados correctamente y que cumplen con los requisitos, características y demás criterios de la normativa legal vigente, se RECOMIENDA la emisión de la certificación de beneficiarios de la deducibilidad por parte del Comité de Calificación y Certificación para acceder al incentivo tributario.

           </div>

        ";


        return $htm;
    }

    public function antecedente__final__certificacion__v1($idFactura)
    {

        $informacionGeneral = $this->observable__axios__informacion__facturas__v1($idFactura);

        if ($informacionGeneral[0]['tipoComprobante'] === "Comprobante físico") {

            $htm.="

                <div class='font-size__10 texto-justificado mb-2 mt-2'>
                    ****Mediante OFICIO No. 917012023OJUR001042 del 02 de mayo de 2023, la Dirección Nacional Jurídica del Servicio de Rentas Internas, señala: “Las notas de venta emitidas por los contribuyentes sujetos al RIMPE Negocio Popular, no reflejan un IVA desglosado. Sin perjuicio de ello, es menester señalar que el flujo de recursos para gastos de publicidad, promoción y patrocinio, realizados a favor de deportistas y programas, proyectos o eventos deportivos, no se encuentran comprendidos en el objeto imponible del IVA, ya que no se perfecciona la prestación de algún servicio, transferencia de dominio o importación de bienes muebles de naturaleza corporal, o de derechos de autor, de propiedad industrial y derechos conexos, gravados con dicho impuesto.”
                </div>

            ";

        }

        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            ***Mediante Oficio Nro. SRI-NAC-DNC-2022-0047-OF del 23 de marzo de 2022, la Dirección Nacional de Control Tributario del Servicio de Rentas Internas, señala: “(…) los valores que se acogerán a la deducción adicional no deben incluir el IVA, sino solamente debe considerarse el subtotal de los comprobantes de venta relacionados a ese gasto.”; en este sentido se certifica el valor correspondiente del subtotal de los comprobantes de venta emitidos.
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            De conformidad a las competencias establecidas para el efecto, se ha procedido a verificar la validez de los citados comprobantes de venta en el portal WEB del Servicio de Rentas Internas, y se ha determinado que los comprobantes de venta son VÁLIDOS, ya que CUMPLEN con las disposiciones emitidas en el Capítulo III, Artículo 18 del Reglamento de comprobantes de venta, retención y documentos complementarios, que establece los REQUISITOS Y CARACTERÍSTICAS PARA LA EMISIÓN DE LOS COMPROBANTES DE VENTA, NOTAS DE CRÉDITO Y NOTAS DE DEBITO, y CUMPLEN con los demás criterios establecidos en los artículos 45, 47 y 48 del Acuerdo Ministerial Nro. 0243.

           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-2 font-bold'>
            RECOMENDACIONES
           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            Sobre lo expuesto y una vez que se ha analizado y verificado que los comprobantes de venta son válidos, y están llenados correctamente y que cumplen con los requisitos, características y demás criterios de la normativa legal vigente, se RECOMIENDA la emisión de la certificación de beneficiarios de la deducibilidad por parte del Comité de Calificación y Certificación para acceder al incentivo tributario.

           </div>

        ";


        return $htm;
    }

    public function datos__informativos__de__factura__v1($idFactura, $codigo, $codigoProyecto)
    {

        $informacionGeneral = $this->observable__axios__informacion__facturas__v1($idFactura);
        $informacionGeneral__2 = $this->informacionProyecto__datosGenerales__v1($codigo);

        if (!empty($informacionGeneral[0]['rucXml']) && !empty($informacionGeneral[0]['razonSocialXml']) && $informacionGeneral[0]['rucXml'] !== null && $informacionGeneral[0]['rucXml'] !== "null") {

            $nombreEmisor = $informacionGeneral[0]['rucXml'];
            $rucEmisor = $informacionGeneral[0]['razonSocialXml'];
        } else {

            $nombreEmisor = $informacionGeneral__2[0]['nombreSolicitante'];
            $rucEmisor = $informacionGeneral__2[0]['rucProponenteIdentificado'];
        }

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            TRÁMITE $codigoProyecto-" . $informacionGeneral[0]['idIncremental'] . "
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL EMISOR DEL COMPROBANTE
                        </td>
                        <td class='width__25'>
                            " . $nombreEmisor . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            RUC
                        </td>
                        <td class='width__25'>
                            " . $rucEmisor . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL RECEPTOR DEL COMPROBANTE
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['razonSocial'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            RUC DEL RECEPTOR
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['ruc'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            COMPROBANTE DE VENTA
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['numeroFactura'] . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            FECHA DE EMISIÓN
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['fechaEmision'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            MONTO
                        </td>
                        <td class='width__25'>
                            " . number_format($informacionGeneral[0]['subotal'], 2, ',', '.') . "
                        </td>

                    </tr>

                </tbody>

            </table>


        ";




        return $htm;
    }

    public function datos__informativos__de__factura($idFactura, $codigo, $codigoProyecto)
    {

        $informacionGeneral = $this->observable__axios__informacion__facturas($idFactura);
        $informacionGeneral__2 = $this->informacionProyecto__datosGenerales($codigo);

        if (!empty($informacionGeneral[0]['rucXml']) && !empty($informacionGeneral[0]['razonSocialXml']) && $informacionGeneral[0]['rucXml'] !== null && $informacionGeneral[0]['rucXml'] !== "null") {

            $nombreEmisor = $informacionGeneral[0]['rucXml'];
            $rucEmisor = $informacionGeneral[0]['razonSocialXml'];
        } else {

            $nombreEmisor = $informacionGeneral__2[0]['nombreSolicitante'];
            $rucEmisor = $informacionGeneral__2[0]['rucProponenteIdentificado'];
        }


        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            TRÁMITE $codigoProyecto-" . $informacionGeneral[0]['idIncremental'] . "
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL EMISOR DEL COMPROBANTE
                        </td>
                        <td class='width__25'>
                            " . $nombreEmisor . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            RUC
                        </td>
                        <td class='width__25'>
                            " . $rucEmisor . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL RECEPTOR DEL COMPROBANTE
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['razonSocial'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            RUC DEL RECEPTOR
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['ruc'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            COMPROBANTE DE VENTA
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['numeroFactura'] . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            FECHA DE EMISIÓN
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['fechaEmision'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            MONTO
                        </td>
                        <td class='width__25'>
                            " . number_format($informacionGeneral[0]['subotal'], 2, ',', '.') . "
                        </td>

                    </tr>

                </tbody>

            </table>


        ";




        return $htm;
    }

    public function antecedentes__certificacion($codigo)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            Los proyectos presentados por los solicitantes son revisados, analizados y calificados por las áreas técnicas del Ministerio del Deporte dentro del aplicativo del Incentivo Tributario; esto es previo a la verificación y análisis de la información referente a los comprobantes de venta por parte de la Dirección Financiera.
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            Específicamente, en lo relacionado con la emisión del presente Informe por parte de la Dirección Financiera, se consideran las disposiciones emitidas en los artículos 45, 47 y 48 del Acuerdo Ministerial Nro. 0243.
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
                Al respecto, se informa que los comprobantes cargados en el aplicativo del Ministerio del Deporte para los PROGRAMAS Y PROYECTOS PARA EXENCIÓN TRIBUTARIA del 150% del Impuesto a la renta, relacionados con el proyecto previamente calificado y denominado: <span class='font-bold'>" . $informacionGeneral[0]['nombreProyecto'] . "</span>, son los que se detallan a continuación:
           </div>

        ";


        return $htm;
    }

    public function datos__informativos__del__proyecto__v1($codigo, $formateado, $oficio, $codigoProyecto, $fechaComite, $fechaEnviaFactura, $fechaEnviaAAnalsita)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales__v1($codigo);

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            DATOS INFORMATIVOS DEL PROYECTO
                        </th>

                    </tr>


                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            TECHO PRESUPUESTARIO: USD$ $formateado/ CONFORME OFICIO NRO. $oficio
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            CÓDIGO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $codigoProyecto . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['nombreProyecto'] . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL SOLICITANTE DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['nombreSolicitante'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            NO. IDENTIFICACION
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['credencialSolicitante'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            DESCRIPCION DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['justificacionProyecto'] . "
                        </td>
                        
                    </tr>


                   <tr>

                        <td class='font-bold width__25'>
                            FECHA DE INICIO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['fechaInicio'] . "
                        </td>
                        
                    </tr>

                   <tr>

                        <td class='font-bold width__25'>
                            FECHA DE FIN DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['fechaFin'] . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            CATEGORÍA
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['sector'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            MONTO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                              " . number_format($informacionGeneral[0]['monto'], 2, ',', '.') . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            FECHA DE CALIFICACIÓN DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['fechaCalifica'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            NÚMERO DE TELÉFONO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['celularSolicitante'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            CORREO ELECTRÓNICO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['correoSolicitante'] . "
                        </td>
                        
                    </tr>

                   <tr>

                        <td class='font-bold width__25'>
                            FECHA DE CARGA DE FACTURAS AL APLICATIVO
                        </td>
                        <td class='width__25'>
                             " . $this->formatearFecha($fechaEnviaFactura) . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            FECHA DE ENVIÓ DE FACTURAS AL ANALISTA
                        </td>
                        <td class='width__25'>
                             " . $this->formatearFecha($fechaEnviaAAnalsita) . "
                        </td>
                        
                    </tr>


                </tbody>

            </table>


        ";




        return $htm;
    }

    public function datos__informativos__del__proyecto($codigo, $formateado, $oficio, $codigoProyecto, $fechaComite, $fechaEnviaFactura, $fechaEnviaAAnalsita)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $consulta = $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
        foreach ($consulta as $valor) {
            $idEnviado = $valor["id"];
        }


        foreach ($this->informacion__fechaComite($idEnviado) as $valor) {
            $fechaBd = $valor["fecha"];
        }

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            DATOS INFORMATIVOS DEL PROYECTO $idEnviado kimberli
                        </th>

                    </tr>


                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            TECHO PRESUPUESTARIO: USD$ $formateado/ CONFORME OFICIO NRO. $oficio
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            CÓDIGO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $codigoProyecto . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['nombreProyecto'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL SOLICITANTE DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['nombreSolicitante'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            NO. IDENTIFICACION
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['credencialSolicitante'] . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            DESCRIPCION DEL PROYECTO
                        </td>
                        <td class='width__25 texto-justificado'>
                             " . $informacionGeneral[0]['justificacion'] . "
                        </td>
                        
                    </tr>


                   <tr>

                        <td class='font-bold width__25'>
                            FECHA DE INICIO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['fechaInicio'] . "
                        </td>
                        
                    </tr>

                   <tr>

                        <td class='font-bold width__25'>
                            FECHA DE FIN DEL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['fechaFin'] . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            CATEGORÍA
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['sector'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            MONTO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                              " . number_format($informacionGeneral[0]['monto'], 2, ',', '.') . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            FECHA DE CALIFICACIÓN DEL PROYECTO
                        </td>
                        <td class='width__25'>
                              " . $fechaBd . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            NÚMERO DE TELÉFONO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['celularSolicitante'] . "
                        </td>
                        
                    </tr>


                    <tr>

                        <td class='font-bold width__25'>
                            CORREO ELECTRÓNICO
                        </td>
                        <td class='width__25'>
                             " . $informacionGeneral[0]['correoSolicitante'] . "
                        </td>
                        
                    </tr>

                   <tr>

                        <td class='font-bold width__25'>
                            FECHA DE CARGA DE FACTURAS AL APLICATIVO
                        </td>
                        <td class='width__25'>
                             " . $this->formatearFecha($fechaEnviaFactura) . "
                        </td>
                        
                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            FECHA DE ENVIÓ DE FACTURAS AL ANALISTA
                        </td>
                        <td class='width__25'>
                             " . $this->formatearFecha($fechaEnviaAAnalsita) . "
                        </td>
                        
                    </tr>


                </tbody>

            </table>


        ";




        return $htm;
    }

    public function portada__informe__de__certificacion($codigo)
    {

        $fechaFormateada = $this->formatearFecha($this->fecha);

        $htm = "

           <div class='font-bold text-14 text-center'>
            INFORME PREVIO A LA CERTIFICACIÓN
           </div>
           <div class='font-bold font-size__12 text-right mt-2'>
             <span class='font-bold ml-1'>Fecha de elaboración:</span><span>$fechaFormateada</span>
           </div>
        ";


        return $htm;
    }

    public function base__legal__de__certificacion()
    {


        $htm .= "

           <div class='font-size__10 texto-justificado mt-2'>
            Mediante Acuerdo Ministerial Nro. 0243 de fecha 21 de noviembre de 2023 y sus reformas, se expide la “Codificación de la norma para la calificación de prioridad, así como para la emisión de la certificación de beneficiarios que pueden acogerse a la deducción del ciento cincuenta por ciento (150%) adicional para el cálculo de la base imponible del impuesto a la renta de los gastos de publicidad, promoción y patrocinio, realizados a favor de deportistas y programas, proyectos o eventos deportivos”; en el cual establece:
           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            “Artículo <span class='font-bold'>12.-</span> De los componentes de los programas y/o proyectos deportivos. - Los programas y/o proyectos deportivos, contendrán uno o más de los siguientes componentes, a través de los cuales se definirá la asignación de los valores que serán utilizados en cada actividad, debiendo aclarar que el componente de Gastos Administrativos podrá incluirse siempre y cuando el programa y/o proyecto deportivo contemple la ejecución de otro/s componente/s (…)”
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            “Artículo <span class='font-bold'>38.-</span> De la calificación de programas y/o proyectos plurianuales. - Podrá emitirse la calificación de prioridad de programas y/o proyectos deportivos que contemplen la ejecución de componentes en más de un ejercicio fiscal, siendo cuatro (4) el máximo de años de vigencia de la calificación. Para tal efecto, y en cumplimiento a los requisitos establecidos en cada caso, bastará la realización del procedimiento de solicitud de calificación por una sola vez al inicio de sus actividades, especificando los valores asignados a cada componente por cada ejercicio fiscal.
           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            Durante el primer trimestre de cada año, el/la solicitante deberá confirmar su intención de continuar la ejecución de los programas y/o proyectos deportivos; para tal efecto, a través del aplicativo informático, requerirá al Comité la renovación de la calificación de prioridad. El monitoreo y seguimiento al cumplimiento de este proceso estará a cargo de las áreas técnicas que conocieron de manera inicial el proceso la calificación de prioridad.
           </div>

        ";


        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            En caso de no efectuar el procedimiento establecido en el inciso precedente, la calificación de prioridad del programa y/o proyecto plurianual se eliminará de manera automática sin que medie procedimiento alguno. El/la secretario del Comité será el responsable de efectuar la notificación al peticionario sobre la referida eliminación.”
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
           Mediante Acuerdo Ministerial Nro. 0038 de fecha 24 de junio de 2025, en el cual se incluye un párrafo que reforma al artículo 43 del Acuerdo Ministerial 0243 que dispone lo siguiente: 
           </div>

        ";



        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            <span class='font-bold'>“Artículo 3.-</span> De la Certificación de beneficiarios.- Una vez cumplidos los procedimientos establecidos en los artículos precedentes, los/las solicitantes podrán requerir al Comité la emisión de la certificación a favor de los beneficiarios de la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta, entendiéndose por tales a quienes hubiesen efectuado gastos por concepto de promoción, publicidad y/o patrocinio a favor de deportistas u organizadores de programas y/o proyectos deportivos.
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            Se exceptúa de este proceso a las personas naturales o jurídicas que actúen como proponentes o que hayan presentado programas y/o proyectos deportivos que se encuentren vigentes al momento de la emisión del o los comprobantes de venta para su certificación. En caso de que no se cumpla con lo dispuesto, dichos comprobantes serán archivados sin que se requiera procedimiento adicional. Esta exclusión tiene como finalidad garantizar un proceso transparente, orientado al fomento del desarrollo deportivo y alineado con las necesidades reales del sector. 
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            Para aplicar a este proceso, se podrán reconocer los gastos en promoción, publicidad y/o patrocinio generados dentro del ejercicio fiscal correspondiente y siempre que los mismos guarden relación con los componentes establecidos en los programas y/o proyectos cuya prioridad haya sido calificada por el Ministerio del Deporte. En ese sentido, se aplicarán las definiciones contenidas en el Reglamento para la aplicación de la Ley de Régimen Tributario Interno referentes a los costos y gastos de promoción, publicidad y patrocinio. 
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
           En cumplimiento a la Ley de Régimen Tributario Interno y su Reglamento, las certificaciones deberán emitirse para cada ejercicio fiscal. Este principio aplica también para los procesos de certificación relacionados a los programas y/o proyectos plurianuales, debiendo cumplirse de manera adicional el procedimiento establecido en el artículo 38 de la presente norma. En tal sentido, no podrán emitirse certificaciones que contemplen gastos generados en varios años.  
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
           Finalmente, para la certificación de proyectos o programas deportivos del deporte profesional, primero se verificará el cumplimiento del financiamiento del 5% del monto total proyectado a la ejecución de actividades de proyectos definidos por el ente rector del Deporte y que conste dentro de los niveles y sectores priorizados; y al menos el 5% restante, a la ejecución de componentes para el deporte femenino, estipulado en el artículo 29 del presente Acuerdo Ministerial.” 
           </div>

        ";

        $htm .= "

           <div class='font-size__10 texto-justificado mt-1'>
            “DISPOSICIONES GENERALES: <span class='font-bold'>DÉCIMA CUARTA. -</span> Para la emisión de la certificación a favor de beneficiarios de la deducción del 150% adicional para el cálculo de la base imponible del impuesto a la renta, se respetará el orden de ingreso de las solicitudes de certificación a través del aplicativo informático, así como el monto anual autorizado de conformidad al dictamen emitido por el ente rector de Economía y Finanzas Públicas. En caso de que se complete dicho monto no se podrán emitir certificaciones adicionales, situación que no constituirá causal para reclamaciones de carácter administrativas o judiciales en contra del Ministerio del Deporte.”
           </div>

        ";

        $htm .= '

           <div class="font-size__10 texto-justificado mt-1">
           
                Mediante Memorando Nro. MD-DAJ-2023-0764-MEM de fecha 11 de julio 2023, la Directora de Asesoría Jurídica informa que en sesión extraordinaria de fecha 30 de junio de 2023, los miembros del comité solicitaron que con la finalidad de poder iniciar la aprobación de las certificaciones 2023, en el aplicativo informático del Incentivo Tributario se debe visualizar las fechas de ingreso de las facturas en el mismo, por lo que solicita: que en el informe previo a la certificación que emite la Dirección Financiera para conocimiento del Comité de Calificación y Certificación para Acceder el Incentivo Tributario, se haga constar la fecha de ingreso de las facturas en el aplicativo informático acompañado del print de pantalla, con la finalidad de dar cumplimiento a la disposición general décimo cuarta, esto es "se respetará el orden de ingreso de las solicitudes de certificación a través del aplicativo informático".

           </div>


           <div class="font-size__10 texto-justificado mt-1">
           
                Finalmente, para el proceso de elaboración, revisión y aprobación de la Emisión de las Certificaciones se actuará mediante el Acuerdo Ministerial Nro. MD-DM-2025-0038-A de fecha 24 de junio de 2025, expedida por el Abg. José David Jiménez Vásquez, Ministro del Deporte, que reforma al Acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023, en los artículos 45, 46 y 49 inherentes al proceso de certificación de Comprobantes de Venta de los Programas y/o Proyectos Deportivos.

           </div>

        ';

        return $htm;
    }


    public function portada__informe__antecedente($codigo, $subsecretariaCoordinacion = null)
    {

        $consulta = $this->informacionProyecto__datosGenerales($codigo);
        foreach ($consulta as $valor) {
            $nombreProyecto = $valor["nombreProyecto"];
        }

        $consulta = $this->proyecto__enviado($codigo);
        foreach ($consulta as $valor) {
            $idEnviadoBd = $valor["id"];
        }


        $consulta = $this->proyecto__enviado__comite($idEnviadoBd);
        foreach ($consulta as $valor) {
            $fechaBd = $valor["fecha"];
            $horaBd = $valor["hora"];
            $idComiteBd = $valor["idComite"];
        }

        $consulta = $this->proyecto__comite__creado($idComiteBd);
        foreach ($consulta as $valor) {
            $numeroComite = $valor["numeroComite"];
            $fechaBd__comite = $valor["fecha"];
            $horaBd__comite = $valor["hora"];
        }

        $fecha__califiacion__comite = $this->formatearFecha($fechaBd);
        $fecha__reunion__comite = $this->formatearFecha($fechaBd__comite);
        $montoTotal__asignado = $this->monto__comite__aprobado($codigo);

        $asignadorReal = strtolower($this->numerosLetras->toWords($montoTotal__asignado));


        $htm = '

           <div class="font-bold text-12 text-left mt-2">
            I ANTECEDENTE:  
           </div>

          <div class="text-12 text-left mt-1">

            Con fecha ' . $fecha__califiacion__comite . ' se notificó la calificación del proyecto denominado ' . $nombreProyecto . ', señalando lo siguiente: 

           </div>

          <div class="text-12 mt-1 texto-justificado">

            "(...) en Sesión Ordinaria/Extraordinaria de ' . $fecha__reunion__comite . ', cumplo con notificar a usted que el proyecto: ' . $nombreProyecto . ' por el monto de USD $ ' . number_format($montoTotal__asignado, 2, '.', ',') . ' (' . $asignadorReal . ') ha sido CALIFICADO (...)". 

           </div>


        ';


        return $htm;
    }

    public function notificacion_legal_notificacion__caso__a__c($codigo, $subsecretariaCoordinacion, $evaludador, $informacionUsuario)
    {

        $consulta = $this->informacionProyecto__datosGenerales($codigo);
        foreach ($consulta as $valor) {
            $nombreProyecto = $valor["nombreProyecto"];
        }


        $consulta = $this->proyecto__enviado($codigo);
        foreach ($consulta as $valor) {
            $idEnviadoBd = $valor["id"];
        }


        $consulta = $this->proyecto__enviado__comite($idEnviadoBd);
        foreach ($consulta as $valor) {
            $idComiteBd = $valor["idComite"];
        }

        $consulta = $this->proyecto__comite__creado($idComiteBd);
        foreach ($consulta as $valor) {
            $numeroComite = $valor["numeroComite"];
            $fechaBd__comite = $valor["fecha"];
            $horaBd__comite = $valor["hora"];
        }


        $consulta = $this->comite__acta__contenido($idComiteBd);
        foreach ($consulta as $valor) {
            $desarrolloBd = $valor["desarrollo"];
        }

        $tipoDeSesion = $this->buscarPalabra($desarrolloBd);
        $fecha__reunion__comite = $this->formatearFecha($fechaBd__comite);

        if ($evaludador === "aprobado") {
            $evaluadorS = "cumple";
            $evaluadorSA = "APROBADA";
        } else {
            $evaluadorS = "cumple";
            $evaluadorSA = "NEGADA";
        }

        $htm = '

           <div class="font-bold text-12 text-left mt-2">
            III NOTIFICACIÓN: 
           </div>

          <div class="text-12  mt-1 texto-justificado">
            En virtud de la normativa expuesta y acorde a las resoluciones adoptadas por la ' . $informacionUsuario[1] . ', luego de la revisión pertinente y una vez que se ha constatado que el proyecto ' . $evaluadorS . ' los parámetros establecidos en la normativa legal vigente,  me permito notificar a usted que su solicitud de modificación para el proyecto: ' . $nombreProyecto . ', ha sido ' . $evaluadorSA . '. 
           </div>

          <div class="text-12  mt-2 texto-justificado">
            Por lo expuesto, se pone en conocimiento lo anteriormente señalado para los fines pertinentes.  
           </div>

          <div class="text-12  mt-2 texto-justificado">
            Con sentimientos de distinguida consideración. 
           </div>

          <div class="text-12  mt-2 texto-justificado font-bold">
            Atentamente,  
           </div>


        ';


        return $htm;
    }

    public function notificacion_legal_notificacion__caso__b($codigo, $evaludador)
    {

        $consulta = $this->informacionProyecto__datosGenerales($codigo);
        foreach ($consulta as $valor) {
            $nombreProyecto = $valor["nombreProyecto"];
        }


        $consulta = $this->proyecto__enviado($codigo);
        foreach ($consulta as $valor) {
            $idEnviadoBd = $valor["id"];
        }


        $consulta = $this->proyecto__enviado__comite($idEnviadoBd);
        foreach ($consulta as $valor) {
            $idComiteBd = $valor["idComite"];
        }

        $consulta = $this->proyecto__comite__creado($idComiteBd);
        foreach ($consulta as $valor) {
            $numeroComite = $valor["numeroComite"];
            $fechaBd__comite = $valor["fecha"];
            $horaBd__comite = $valor["hora"];
        }


        $consulta = $this->comite__acta__contenido($idComiteBd);
        foreach ($consulta as $valor) {
            $desarrolloBd = $valor["desarrollo"];
        }

        $tipoDeSesion = $this->buscarPalabra($desarrolloBd);
        $fecha__reunion__comite = $this->formatearFecha($fechaBd__comite);

        if ($evaludador === "CALIFICADO") {
            $evaluadorS = "cumple";
            $evaluadorSA = "APROBADA";
            $evaluadorSA__dos = "APROBAR";
        } else {
            $evaluadorS = "cumple";
            $evaluadorSA = "NEGADA";
            $evaluadorSA__dos = "NEGAR";
        }

        $montoTotal__asignado = $this->monto__comite__aprobado($codigo);
        $asignadorReal = strtolower($this->numerosLetras->toWords($montoTotal__asignado));


        $htm = '

           <div class="font-bold text-12 text-left mt-2">
            III NOTIFICACIÓN: 
           </div>

          <div class="text-12  mt-1 texto-justificado">
            Mediante sesión ' . $tipoDeSesion . ' de ' . $fecha__reunion__comite . ', el Comité de Calificación y Certificación para acceder al Incentivo Tributario del Ministerio del Deporte, mediante solicitud de modificación se manifiesta que:
           </div>

           <div class="text-12  mt-1 texto-justificado">
            <span class="font-bold">RESUELVEN:</span> ' . $evaluadorSA__dos . ' la modificación del proyecto ' . $nombreProyecto . ' por un monto de ' . number_format($montoTotal__asignado, 2, '.', ',') . ' (' . strtoupper($asignadorReal) . '). 
           </div>

          <div class="text-12  mt-2 texto-justificado">
            Por lo expuesto, se pone en conocimiento lo anteriormente señalado para los fines pertinentes.  
           </div>

          <div class="text-12  mt-2 texto-justificado">
            Con sentimientos de distinguida consideración. 
           </div>

          <div class="text-12  mt-2 texto-justificado font-bold">
            Atentamente,  
           </div>

           <div class="mt-100">Firma director de la <span class="font-bold">Secretaria de comité</span></div>

        ';


        return $htm;
    }

    public function base__legal__notifiacion($codigo, $subsecretariaCoordinacion = null)
    {

        $htm = '

           <div class="font-bold text-12 text-left mt-2">
            II BASE LEGAL: 
           </div>

          <div class="text-12  mt-1 texto-justificado">
            Del Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023 y sus reforma señala:  
           </div>

          <div class="text-12  mt-1 texto-justificado">
            “… Artículo 40.- <span class="font-bold">De las modificaciones de programas y/o proyectos calificados. -</span> Una vez calificada la prioridad de los programas y/o proyectos deportivos éstos podrán ser modificados por dos ocasiones únicamente, observando los requisitos, condiciones y límites establecidos en el presente Acuerdo Ministerial, tales como aquellos constantes en el Componente de Gastos Administrativos u otros, en los siguientes casos:  
           </div>

           <div class="text-12 mt-1 texto-justificado">
                <span class="font-bold">a)</span>    Modificación de valores y/o modificaciones programáticas; dentro del mismo componente, 
           </div>

           <div class="text-12 mt-1 texto-justificado">
                <span class="font-bold">b)</span>    Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente 
           </div>

           <div class="text-12 mt-1 texto-justificado">
                <span class="font-bold">c)</span>    Modificación de la vigencia del proyecto de anual a plurianual conforme las justificaciones que correspondan y el complemento de la descripción de nuevos componentes y programación. 
           </div>

          <div class="text-12 mt-1 texto-justificado">
            En los casos que implique la modificación de Componentes, se entenderá los cambios en las actividades o gastos que se desglosan del Componente General, por cuanto el objetivo del proyecto está vinculado a él o los componentes elegidos en la etapa de creación del proyecto y estos no podrán ser cambiados durante la vigencia del proyecto…”  
           </div>

          <div class="text-12 mt-1 texto-justificado">
            Artículo 41.- <span class="font-bold">De las modificaciones de valores entre componentes y/o modificaciones programáticas. -</span> En cumplimiento a las condiciones establecidas en el artículo 40 de la presente norma, podrán modificarse los valores asignados a cada componente siempre que se mantenga el valor total establecido en los programas y/o proyectos deportivos. De igual forma, podrán realizarse modificaciones relacionadas a la programación a las fechas en las cuales se planificó la ejecución de una actividad o componente. Para tal efecto y sin que medie una nueva aprobación por parte del Comité, el/la solicitante 
           </div>

          <div class="text-12 mt-1 texto-justificado">
            La solicitud de modificatoria a la que hace referencia este artículo seguirá las reglas de calificación de prioridad contenidas en la presente norma, incluyendo el cumplimiento de los términos para su tramitación, los informes generados por las áreas técnicas correspondientes, la aprobación por parte del Comité y demás procedimientos aplicables al caso". 
           </div>

          <div class="text-12 mt-1 texto-justificado">
            Artículo 42.- <span class="font-bold">De las modificaciones de valores y/o componentes por un monto inferior o superior al inicialmente contemplado. -</span> En cumplimiento a las condiciones establecidas en el artículo 40 del presente Acuerdo Ministerial, se podrá solicitar la aprobación de la modificación del programa y/o proyecto deportivo calificados por un monto inferior o superior al inicialmente previsto. Para tal efecto, el/la solicitante de manera motivada justificará las modificaciones planteadas, incluyendo el detalle y nuevo desglose de componentes y/o valores; así como la declaración en la que conste que dicha modificatoria no afecta el objeto y metas del programa y/o proyecto deportivo. En el primer caso, deberá evidenciarse que el mismo puede cumplirse a pesar de no haber obtenido el patrocinio o la publicidad planificados; mientras que, en el segundo caso deberá evidenciarse que el incremento de componentes y/o valores son necesarios para el cumplimiento de los objetivos y metas. 
           </div>

          <div class="text-12 mt-1 texto-justificado">
            La solicitud de modificatoria a la que hace referencia este artículo seguirá las reglas de calificación de prioridad contenidas en la presente norma, incluyendo el cumplimiento de los términos para su tramitación, los informes generados por las áreas técnicas correspondientes, la aprobación por parte del Comité y demás procedimientos aplicables al caso 
           </div>

        ';


        return $htm;
    }

    public function portada__informe__notificacion($codigo, $subsecretariaCoordinacion = null)
    {

        $consulta = $this->informacionProyecto__datosGenerales($codigo);
        foreach ($consulta as $valor) {
            $nombreProyecto = $valor["nombreProyecto"];
        }

        $consulta = $this->proyecto__enviado($codigo);
        foreach ($consulta as $valor) {
            $codigoProyecto = $valor["codigo"];
        }


        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $htm = "

           <div class='font-bold text-14 text-center'>
            NOTIFICACIÓN DE MODIFICACIÓN 
           </div>
           <div class='font-size__12 mt-2'>
            <span class='font-bold'>Asunto:</span><span class='ml-1'>NOTIFICACIÓN DE MODIFICACIÓN DEL PROYECTO $nombreProyecto $codigoProyecto</span>
           </div>
           <div class='font-bold font-size__12 text-left mt-4'>
            Señor/a 
           </div>
           <div class='font-size__12'>
            " . $informacionGeneral[0]['nombreSolicitante'] . "
           </div>
           <div class='font-bold font-size__12'>
            En su Despacho 
           </div>
           <div class='font-bold font-size__12 mt-4'>
            De mi consideración:  
           </div>
        ";


        return $htm;
    }

    public function portada__informe__modificacion($codigo, $subsecretariaCoordinacion)
    {


        $htm = "

           <div class='font-bold text-14 text-center'>
            INFORME DE RECOMENDACIÓN TÉCNICA PARA MODIFICACIÓN
           </div>
           <div class='font-bold font-size__12 text-center'>
            $codigo
           </div>
           <div class='font-bold font-size__12 text-center'>
            $subsecretariaCoordinacion
           </div>
        ";


        return $htm;
    }



    public function portada__informe($codigo, $subsecretariaCoordinacion)
    {


        $htm = "

           <div class='font-bold text-14 text-center'>
            RECOMENDACIÓN TÉCNICA PARA EMITIR CALIFICACIÓN DE PROGRAMAS O PROYECTOS DEPORTIVOS
           </div>
           <div class='font-bold font-size__12 text-center'>
            $codigo
           </div>
           <div class='font-bold font-size__12 text-center'>
            $subsecretariaCoordinacion
           </div>
        ";


        return $htm;
    }


    public function datos__generales($codigo)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='4'>
                            DATOS GENERALES
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['nombreProyecto'] . "
                        </td>


                        <td class='font-bold width__25'>
                            MONTO SOLICITADO PARA EL PROYECTO
                        </td>
                        <td class='width__25'>
                             " . number_format($informacionGeneral[0]['monto'], 2, ',', '.') . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>
                            NOMBRE DEL SOLICITANTE
                        </td>
                        <td>
                            " . $informacionGeneral[0]['nombreSolicitante'] . "
                        </td>


                        <td class='font-bold'>
                            NÚMERO DE BENEFICIARIOS
                        </td>
                        <td>
                            " . $informacionGeneral[0]['beneficiarios'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                            SECTOR AL QUE CONTRIBUYE
                        </td>
                        <td>
                            " . $informacionGeneral[0]['sector'] . " 
                        </td>

                        <td class='font-bold'>
                            PLURIANUAL
                        </td>
                        <td>
                            " . $informacionGeneral[0]['plurianual'] . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>
                            FECHA INICIO
                        </td>
                        <td>
                            " . $informacionGeneral[0]['fechaInicio'] . " 
                        </td>

                        <td class='font-bold'>
                           FECHA FIN
                        </td>
                        <td>
                            " . $informacionGeneral[0]['fechaFin'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                           ALINEACIÓN AL PLAN ESTRATÉGICO
                        </td>
                        <td colspan='3' align='left'>
                           " . $informacionGeneral[0]['alineacionTecnica'] . " " . $informacionGeneral[0]['alineacionInfra'] . "
                        </td>

                    </tr>


                </tbody>


            </table>


        ";




        return $htm;
    }

    public function datos__generales__modificado($codigo, $idSolicitud)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales__modificado($codigo, $idSolicitud);
        $informacionTipoProyecto = $this->informacionProyecto__solicitud__modificacion($idSolicitud);
        $informacionTipoProyecto__original = $this->informacionProyecto__datosGenerales($codigo);


        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='4'>
                            DATOS GENERALES
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            NOMBRE DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['nombreProyecto'] . "
                        </td>


                        <td class='font-bold width__25'>
                            MONTO CALIFICADO (INICIAL)
                        </td>
                        <td class='width__25'>
                             " . number_format($informacionTipoProyecto__original[0]['monto'], 2, ',', '.') . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>
                            NOMBRE DEL SOLICITANTE
                        </td>
                        <td>
                            " . $informacionGeneral[0]['nombreSolicitante'] . "
                        </td>


                        <td class='font-bold width__25'>
                            MONTO A CALIFICAR (MODIFICADO) 
                        </td>
                        <td class='width__25'>
                             " . number_format($informacionGeneral[0]['monto'], 2, ',', '.') . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                            SECTOR AL QUE CONTRIBUYE
                        </td>
                        <td>
                            " . $informacionGeneral[0]['sector'] . " 
                        </td>

                        <td class='font-bold'>
                            PLURIANUAL
                        </td>
                        <td>
                            " . $informacionGeneral[0]['plurianual'] . "
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>
                            FECHA DE INICIO 
                        </td>
                        <td>
                            " . $informacionGeneral[0]['fechaInicio'] . " 
                        </td>

                        <td class='font-bold'>
                           FECHA DE FIN 
                        </td>
                        <td>
                            " . $informacionGeneral[0]['fechaFin'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                           ALINEACIÓN AL PLAN ESTRATÉGICO
                        </td>
                        <td colspan='3' align='left'>
                           " . $informacionGeneral[0]['alineacionTecnica'] . " " . $informacionGeneral[0]['alineacionInfra'] . "
                        </td>

                    </tr>


                </tbody>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='4'>
                            CASO DE MODIFICACIÓN
                        </th>

                    </tr>


                </thead>


                <tbody>

                    <tr>

                        <td colspan='4'>
                            " . $informacionTipoProyecto[0]['caso'] . "
                        </td>

                    </tr>


                </tbody>

            </table>


        ";




        return $htm;
    }

    public function requisitos($codigo)
    {



        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                           REQUISITOS
                        </th>

                    </tr>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                           (Indicar CUMPLE o NO CUMPLE)
                        </th>

                    </tr>


                </thead>

                <tbody>

                <tr>

                    <td class='font-bold width__25'>
                        PROYECTO
                    </td>
                    <td class='width__25'>
                        CUMPLE
                    </td>

                </tr>

        ";

        foreach ($this->requisitos__documentos($codigo) as $valor) {
            $htm .= "

                <tr>

                    <td class='font-bold width__25'>
                        $valor
                    </td>
                    <td class='width__25'>
                        CUMPLE
                    </td>

                </tr>


            ";
        }


        $htm .= "

                </tbody>


            </table>


        ";


        return $htm;
    }

    public function resumenProyecto($codigo)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                           RESUMEN DEL PROYECTO
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold width__25'>
                            OBJETIVO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['objetivoGeneral'] . "
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold width__25'>
                            JUSTIFICACIÓN Y BENEFICIO DEL PROYECTO
                        </td>
                        <td class='width__25'>
                            " . $informacionGeneral[0]['justificacion'] . "
                        </td>

                    </tr>

                </tbody>


            </table>

        ";


        return $htm;
    }

    public function presupuesto__modificacion($codigo, $idSolicitud)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $idSector = intval($informacionGeneral[0]['idSector']);

        $aniosComponentes = $this->constructor->select__general__incentivo("SELECT anio FROM proyecto_presupuesto WHERE codigo='$codigo' GROUP BY anio;");

        foreach ($aniosComponentes as $clave => $valor) {

            $aniosComponentesM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $aniosFemeninoM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");


            $aniosPriorizadoM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $montoComponentes = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC = $valorC["total"];
            }

            $montoFemeninos = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF = $valorF["total"];
            }


            $montoPriorizado = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP = $valorP["total"];
            }

            $htm .= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            PRESUPUESTO INICIAL
                        </th>

                    </tr>


                </thead>

            </table>

            ";

            $htm .= "

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO " . $valor["anio"] . "</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector) === 3) {

                $htm .= "

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";
            }

            $htm .= "

                </tr>

                <tr>

                    <td class='font-bold' align='right'>" . number_format($totalC, 2, '.', ',') . "</td>

            ";

            if (intval($sector) === 3) {

                $htm .= "

                    <td align='right' class='font-bold'>" . number_format($totalF, 2, '.', ',') . "</td>
                    <td align='right' class='font-bold'>" . number_format($totalP, 2, '.', ',') . "</td>

                ";
            }


            $htm .= "

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector) === 3) {
                $htm .= "

                    <td></td>
                    <td></td>

                ";
            }

            $htm .= "


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador = 0;

                $sumadorComparador = $sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador = $sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador > 1) {

                    $htm .= "

                        <tr>

                            <td>" . $valorCom["componente"] . "</td>
                            <td align='right'>" . number_format($valorCom["totalComponentes"], 2, '.', ',') . "</td>

                    ";

                    if (intval($sector) === 3) {
                        $htm .= "

                            <td align='right'>" . number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',') . "</td>
                            <td align='right'>" . number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',') . "</td>

                        ";
                    }

                    $html . "

                        </tr>

                    ";
                }
            }


            $htm .= "

                </table>


            ";
        }


        $aniosComponentes = $this->constructor->select__general__incentivo("SELECT anio FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY anio;");

        foreach ($aniosComponentes as $clave => $valor) {

            $aniosComponentesM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL AND a.estado='A' AND  a.tipoIngreso='modificacion' AND  a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");

            $aniosFemeninoM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL AND a.estado='A' AND  a.tipoIngreso='modificacion' AND  a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");


            $aniosPriorizadoM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL  AND a.estado='A' AND  a.tipoIngreso='modificacion' AND  a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");

            $montoComponentes = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC = $valorC["total"];
            }

            $montoFemeninos = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF = $valorF["total"];
            }


            $montoPriorizado = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP = $valorP["total"];
            }

            $htm .= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            PRESUPUESTO MODIFICADO
                        </th>

                    </tr>


                </thead>

            </table>

            ";

            $htm .= "

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO " . $valor["anio"] . "</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector) === 3) {

                $htm .= "

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";
            }

            $htm .= "

                </tr>

                <tr>

                    <td class='font-bold' align='right'>" . number_format($totalC, 2, '.', ',') . "</td>

            ";

            if (intval($sector) === 3) {

                $htm .= "

                    <td align='right' class='font-bold'>" . number_format($totalF, 2, '.', ',') . "</td>
                    <td align='right' class='font-bold'>" . number_format($totalP, 2, '.', ',') . "</td>

                ";
            }


            $htm .= "

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector) === 3) {
                $htm .= "

                    <td></td>
                    <td></td>

                ";
            }

            $htm .= "


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador = 0;

                $sumadorComparador = $sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador = $sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador > 1) {

                    $htm .= "

                        <tr>

                            <td>" . $valorCom["componente"] . "</td>
                            <td align='right'>" . number_format($valorCom["totalComponentes"], 2, '.', ',') . "</td>

                    ";

                    if (intval($sector) === 3) {
                        $htm .= "

                            <td align='right'>" . number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',') . "</td>
                            <td align='right'>" . number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',') . "</td>

                        ";
                    }

                    $html . "

                        </tr>

                    ";
                }
            }


            $htm .= "

                </table>


            ";
        }

        return $htm;
    }


    public function presupuesto($codigo)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        $idSector = intval($informacionGeneral[0]['idSector']);

        $aniosComponentes = $this->constructor->select__general__incentivo("SELECT anio FROM proyecto_presupuesto WHERE codigo='$codigo' GROUP BY anio;");

        foreach ($aniosComponentes as $clave => $valor) {

            $aniosComponentesM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $aniosFemeninoM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");


            $aniosPriorizadoM = $this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='" . $valor["anio"] . "' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $montoComponentes = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC = $valorC["total"];
            }

            $montoFemeninos = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF = $valorF["total"];
            }


            $montoPriorizado = $this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='" . $valor["anio"] . "' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP = $valorP["total"];
            }

            $htm .= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            PRESUPUESTO
                        </th>

                    </tr>


                </thead>

            </table>

            ";

            $htm .= "

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO " . $valor["anio"] . "</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector) === 3) {

                $htm .= "

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";
            }

            $htm .= "

                </tr>

                <tr>

                    <td class='font-bold' align='right'>" . number_format($totalC, 2, '.', ',') . "</td>

            ";

            if (intval($sector) === 3) {

                $htm .= "

                    <td align='right' class='font-bold'>" . number_format($totalF, 2, '.', ',') . "</td>
                    <td align='right' class='font-bold'>" . number_format($totalP, 2, '.', ',') . "</td>

                ";
            }


            $htm .= "

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector) === 3) {
                $htm .= "

                    <td></td>
                    <td></td>

                ";
            }

            $htm .= "


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador = 0;

                $sumadorComparador = $sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador = $sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador > 1) {

                    $htm .= "

                        <tr>

                            <td>" . $valorCom["componente"] . "</td>
                            <td align='right'>" . number_format($valorCom["totalComponentes"], 2, '.', ',') . "</td>

                    ";

                    if (intval($sector) === 3) {
                        $htm .= "

                            <td align='right'>" . number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',') . "</td>
                            <td align='right'>" . number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',') . "</td>

                        ";
                    }

                    $html . "

                        </tr>

                    ";
                }
            }


            $htm .= "

                </table>


            ";
        }

        return $htm;
    }

    public function analisisTecnico__modificacion($codigo, $informacionUsuario, $opcionRecomendacion, $textoRecomendacion)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        if ($opcionRecomendacion === "aprobado") {
            $opcionRecomendacion = "SI";
        } else {
            $opcionRecomendacion = "NO";
        }

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            JUSTIFICACIÓN DE LA MODIFICACIÓN
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td colspan='2'>
                            " . $this->justificacion__modificacion__componetnes__presupuestos($codigo) . "
                        </td>

                    </tr>



                    <tr>

                        <td colspan='2'>
                            " . $this->justificacion__modificacion__cronograma__de__actividades($codigo) . "
                        </td>

                    </tr>


                </tbody>


                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            Recomendación técnica 
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td colspan='2'>
                            $textoRecomendacion
                        </td>

                    </tr>


                </tbody>


            </table>

        ";


        return $htm;
    }

    public function analisisTecnico($codigo, $informacionUsuario, $opcionRecomendacion, $textoRecomendacion)
    {

        $informacionGeneral = $this->informacionProyecto__datosGenerales($codigo);

        if ($opcionRecomendacion === "noFavorable") {
            $opcionRecomendacion = "NO";
        } else {
            $opcionRecomendacion = "SI";
        }

        $htm = "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            ANÁLISIS TÉCNICO
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td colspan='2'>
                            En atención al proyecto presentado susceptible de consideración para la aplicación de la deducción del 150% adicional para el cálculo de la base imponible del Impuesto a la Renta, por " . $informacionUsuario[0] . ", mediante el aplicativo del Ministerio del Deporte, denominado " . $informacionGeneral[0]['nombreProyecto'] . ".
                        </td>

                    </tr>


                    <tr>

                        <td colspan='1' class='font-bold'>
                           RECOMIENDO
                        </td>

                        <td colspan='1' class='font-bold'>
                           $opcionRecomendacion
                        </td>

                    </tr>


                </tbody>


                <thead>

                    <tr>

                        <th class='background-color__blue text-center' colspan='2'>
                            RAZONES DE RECOMENDACIÓN
                        </th>

                    </tr>


                </thead>

                <tbody>

                    <tr>

                        <td colspan='2'>
                            $textoRecomendacion
                        </td>

                    </tr>


                </tbody>


            </table>

        ";


        return $htm;
    }

    public function pieDeFirma($informacionUsuario, $informacionUsuario__superiorInmediato)
    {


        $htm = "

            <div class='border__blue__div width__25 mt-30 padding-4'>

                <div class='w-full text-10 font-bold text-center'>$informacionUsuario[0]</div>
                <div class='w-full text-10 font-bold text-center'>$informacionUsuario[1]</div>
                <div class='w-full text-10 font-bold text-center mt-2'>ELABORADO</div>

            </div>

        ";

        if (count($informacionUsuario__superiorInmediato) > 0) {

            $htm .= "

                <div class='border__blue__div width__25 mt-30 padding-4'>

                    <div class='w-full text-10 font-bold text-center'>$informacionUsuario__superiorInmediato[0]</div>
                    <div class='w-full text-10 font-bold text-center'>$informacionUsuario__superiorInmediato[1]</div>
                    <div class='w-full text-10 font-bold text-center mt-2'>APROBADO</div>

                </div>

            ";
        }


        return $htm;
    }
}
