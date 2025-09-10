<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;




class Proyecto {

    private static $instance = null;

    public function __construct() {

        $this->constructor = ServicesAdmin::getInstance();

    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Proyecto();
        }
        return self::$instance;
    }

    public function informacionProyecto__solicitud__modificacion($idSolicitud) {

        return $this->constructor->select__general__incentivo("SELECT IF(casoModificar='a','Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(casoModificar='b','Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','Modificaciones de la vigencia del proyecto de anual a plurianual')) AS caso,casoModificar AS casoModificarReal FROM proyecto_modificacion_solicitud WHERE idSolicitud='$idSolicitud';");

    }


    public function informacionProyecto__datosGenerales__modificado($codigo,$idSolicitud) {

        $consulta=$this->constructor->select__general__incentivo("SELECT a.idDescripcion FROM incentivorespaldo.proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idSolicitud' GROUP BY a.codigo;");

        foreach ($consulta as $valor) {
            $idDescripcionBd=$valor["idDescripcion"];
        }


        if (!empty($idDescripcionBd)) {
           return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,UPPER(b.nombre) AS nombreSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector FROM incentivorespaldo.proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idSolicitud' GROUP BY a.codigo;");
        }else{
            return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,UPPER(b.nombre) AS nombreSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' GROUP BY a.codigo;");
        }


    }

    public function informacionProyecto__datosGenerales($codigo) {

        return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,UPPER(b.nombre) AS nombreSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' GROUP BY a.codigo;");


    }

    public function justificacionDescripcion__modificacion($codigo,$idSolicitud) {

        $consulta=$this->constructor->select__general__incentivo("SELECT justifiacionModificacion FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo' AND idSolicitud='$idSolicitud' AND tipoIngreso='modificacion';");
        foreach ($consulta as $valor) {
            $justifiacionModificacion=$valor["justifiacionModificacion"];
        }

        return $justifiacionModificacion;

    }

    public function justificacionPresupuesto__modificacion($codigo,$idSolicitud) {

        $consulta=$this->constructor->select__general__incentivo("SELECT justificacion AS justifiacionModificacion FROM incentivorespaldo.proyecto_presupuesto_modificacion_justificacion WHERE idSolicitud='$idSolicitud' AND codigo='$codigo';");
        foreach ($consulta as $valor) {
            $justifiacionModificacion=$valor["justifiacionModificacion"];
        }

        return $justifiacionModificacion;

    }

    public function justificacionCronogramadDeActividades__modificacion($codigo,$idSolicitud) {

        $consulta=$this->constructor->select__general__incentivo("SELECT justificacion AS justifiacionModificacion FROM incentivorespaldo.proyecto_cronograma_actividades_modificacion_justificacion WHERE idSolicitud='$idSolicitud' AND codigo='$codigo';");
        foreach ($consulta as $valor) {
            $justifiacionModificacion=$valor["justifiacionModificacion"];
        }

        return $justifiacionModificacion;

    }

    public function declaracionVeracidad__modificacion($codigo,$idSolicitudBd) {


        $consulta=$this->informacionProyecto__solicitud__modificacion($idSolicitudBd);
        foreach ($consulta as $valor) {
            $casoModificarRealB=$valor["casoModificarReal"];
        }

        $htm="

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-center'>
                            JUSTIFICACIÓN DE LA MODIFICACIÓN 
                        </th>

                    </tr>


                </thead>

                <tbody>


                ";



        if ($casoModificarRealB==="c") {
            $htm.="

                    <tr>

                        <td>
                             ".$this->justificacionDescripcion__modificacion($codigo,$idSolicitudBd)."
                        </td>

                    </tr>


            ";
        }
          

         $htm.=" 

                    <tr>

                        <td>
                             ".$this->justificacionPresupuesto__modificacion($codigo,$idSolicitudBd)."
                        </td>

                    </tr>



                    <tr>

                        <td>
                             ".$this->justificacionCronogramadDeActividades__modificacion($codigo,$idSolicitudBd)."
                        </td>

                    </tr>

                </tbody>


                <thead>

                    <tr>

                        <th class='background-color__blue text-center'>
                            DECLARACIÓN 
                        </th>

                    </tr>


                </thead>

                <tbody

                    <tr>

                        <td class='font-bold text-center'>
                            'Declaro bajo mi responsabilidad, que las modificaciones realizadas no afectan el objeto o metas del programa y/o proyecto deportivo'
                        </td>

                    </tr>

                </tbody>


            </table>

            <div class='mt-100 text-center font-bold w-full'>
                FIRMA DEL SOLICITANTE
            </div>

        ";


        return $htm;


    }



    public function presupuesto__modificacion__2($codigo,$idSolicitud) {

        $informacionGeneral=$this->informacionProyecto__datosGenerales($codigo);

        $idSector=intval($informacionGeneral[0]['idSector']);

        $aniosComponentes=$this->constructor->select__general__incentivo("SELECT anio FROM proyecto_presupuesto WHERE codigo='$codigo' GROUP BY anio;");

        foreach ($aniosComponentes as $clave => $valor) {
    
            $aniosComponentesM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $aniosFemeninoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");


            $aniosPriorizadoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $montoComponentes=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC=$valorC["total"];
            }

            $montoFemeninos=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF=$valorF["total"];
            }


            $montoPriorizado=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP=$valorP["total"];
            }

            $htm.="

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

            $htm.="

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO ".$valor["anio"]."</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";

            }

            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='right'>".number_format($totalC, 2, '.', ',')."</td>

            ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='right' class='font-bold'>".number_format($totalF, 2, '.', ',')."</td>
                    <td align='right' class='font-bold'>".number_format($totalP, 2, '.', ',')."</td>

                ";

            }


            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector)===3) {
                $htm.="

                    <td></td>
                    <td></td>

                ";
            }

            $htm.="


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador=0;

                $sumadorComparador=$sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador=$sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador>1) {

                    $htm.="

                        <tr>

                            <td>".$valorCom["componente"]."</td>
                            <td align='right'>".number_format($valorCom["totalComponentes"], 2, '.', ',')."</td>

                    ";

                   if (intval($sector)===3) {
                        $htm.="

                            <td align='right'>".number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',')."</td>
                            <td align='right'>".number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',')."</td>

                        ";
                    }

                    $html."

                        </tr>

                    ";

                }


            }


            $htm.="

                </table>


            ";


        }


        $aniosComponentes=$this->constructor->select__general__incentivo("SELECT anio FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY anio;");

        foreach ($aniosComponentes as $clave => $valor) {
    
            $aniosComponentesM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL AND a.estado='A' AND  a.tipoIngreso='modificacion' AND  a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");

            $aniosFemeninoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL AND a.estado='A' AND  a.tipoIngreso='modificacion' AND  a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");


            $aniosPriorizadoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL  AND a.estado='A' AND  a.tipoIngreso='modificacion' AND  a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");

            $montoComponentes=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC=$valorC["total"];
            }

            $montoFemeninos=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF=$valorF["total"];
            }


            $montoPriorizado=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idSolicitud' GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP=$valorP["total"];
            }

            $htm.="

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

            $htm.="

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO ".$valor["anio"]."</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";

            }

            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='right'>".number_format($totalC, 2, '.', ',')."</td>

            ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='right' class='font-bold'>".number_format($totalF, 2, '.', ',')."</td>
                    <td align='right' class='font-bold'>".number_format($totalP, 2, '.', ',')."</td>

                ";

            }


            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector)===3) {
                $htm.="

                    <td></td>
                    <td></td>

                ";
            }

            $htm.="


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador=0;

                $sumadorComparador=$sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador=$sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador>1) {

                    $htm.="

                        <tr>

                            <td>".$valorCom["componente"]."</td>
                            <td align='right'>".number_format($valorCom["totalComponentes"], 2, '.', ',')."</td>

                    ";

                   if (intval($sector)===3) {
                        $htm.="

                            <td align='right'>".number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',')."</td>
                            <td align='right'>".number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',')."</td>

                        ";
                    }

                    $html."

                        </tr>

                    ";

                }


            }


            $htm.="

                </table>


            ";


        }

        return $htm;

    }

    public function datos__generales__modificado($codigo,$idSolicitud) {

        $informacionGeneral=$this->informacionProyecto__datosGenerales__modificado($codigo,$idSolicitud);

        $informacionTipoProyecto=$this->informacionProyecto__solicitud__modificacion($idSolicitud);

        $htm="

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
                            ".$informacionGeneral[0]['nombreProyecto']."
                        </td>


                        <td class='font-bold width__25'>
                            MONTO SOLICITADO PARA EL PROYECTO
                        </td>
                        <td class='width__25'>
                            ".$informacionGeneral[0]['monto']."
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>
                            NOMBRE DEL SOLICITANTE
                        </td>
                        <td>
                            ".$informacionGeneral[0]['nombreSolicitante']."
                        </td>


                        <td class='font-bold'>
                            NÚMERO DE BENEFICIARIOS
                        </td>
                        <td>
                            ".$informacionGeneral[0]['beneficiarios']."
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                            SECTOR AL QUE CONTRIBUYE
                        </td>
                        <td>
                            ".$informacionGeneral[0]['sector']." 
                        </td>

                        <td class='font-bold'>
                            PLURIANUAL
                        </td>
                        <td>
                            ".$informacionGeneral[0]['plurianual']."
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>
                            FECHA INICIO
                        </td>
                        <td>
                            ".$informacionGeneral[0]['fechaInicio']." 
                        </td>

                        <td class='font-bold'>
                           FECHA FIN
                        </td>
                        <td>
                            ".$informacionGeneral[0]['fechaFin']."
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                           ALINEACIÓN AL PLAN ESTRATÉGICO
                        </td>
                        <td colspan='3' align='left'>
                           ".$informacionGeneral[0]['alineacionTecnica']." ".$informacionGeneral[0]['alineacionInfra']."
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
                            ".$informacionTipoProyecto[0]['caso']."
                        </td>

                    </tr>


                </tbody>

            </table>


        ";




        return $htm;


    }



      public function declaracionVeracidad($credencialProponente,$nombreProponente,$fecha,$nombreR) {


        $htm= "

           <div class='font-bold mt-2'>8. DECLARACIÓN DE VERACIDAD DE LA INFORMACIÓN </div>

           <div class='mt-1 texto-justificado'>

                En virtud de la potestad del Ministerio del Deporte para dictar las normas pertinentes para la ejecución y cumplimiento de los beneficios tributarios que prevé la Ley de Régimen Tributario Interno; y, el Reglamento para la aplicación de la Ley de Régimen Tributario Interno, la/el Sr/a. $nombreProponente, con C.I. $credencialProponente, en relación a la solicitud de acogerse al beneficio de los incentivos tributarios que brinda el Estado ecuatoriano a los gastos de publicidad y patrocinio realizados a favor de deportistas, programas y proyectos deportivos; y, en cumplimiento a los principios de ética, probidad, buena fe, respeto al ordenamiento jurídico y a la autoridad legítima, entre otros, que rigen los deberes de las personas y la relación entre éstas y la administración pública; así como a lo establecido en el artículo 10 de la Ley Orgánica para la Optimización y Eficiencia de Trámites Administrativos respecto a la veracidad de la información: “Las entidades reguladas por esta Ley presumirán que las declaraciones, documentos y actuaciones de las personas efectuadas en virtud de trámites administrativos son verdaderas, bajo aviso a la o al administrado de que, en caso de verificarse lo contrario, el trámite y resultado final de la gestión podrán ser negados y archivados, o los documentos emitidos carecerán de validez alguna, sin perjuicio de las sanciones y otros efectos jurídicos establecidos en la ley. El listado de actuaciones anuladas por la entidad en virtud de lo establecido en este inciso estará disponible para las demás entidades del Estado (...)”, DECLARA que toda la documentación, datos e información proporcionados dentro del presente trámite, así como aquella que le sea requerida durante la tramitación de la misma, es veraz, legítima y auténtica; y se compromete a dar estricto cumplimiento a los compromisos y obligaciones contenidos en el respectivo programa o proyecto deportivo. En ese sentido, reconoce y acepta que el COMITÉ DE CALIFICACIÓN Y CERTIFICACIÓN PARA ACCEDER AL INCENTIVO TRIBUTARIO se pronunciará sobre la base de la información presentada y cuyo contenido son de su exclusiva responsabilidad. Razón por la cual, ni los miembros del referido cuerpo colegiado, ni los funcionarios intervinientes en el análisis del presente expediente, podrán ser considerados como responsables por eventuales inconsistencias que pudieren existir entre la información presentada y los datos originales; así como por incumplimiento de los términos y condiciones constantes en los citados programas o proyectos deportivos.  

           </div>

           <div class='mt-1 texto-justificado texto-cursiva font-bold'>

                “Autorizo al Ministerio del Deporte a compartir la información básica del presente proyecto y mis datos de contacto, en su página web para obtener una mejor exposición del proyecto y mayor posibilidad de inversión privada”. 

           </div>

           <div class='mt-100 font-bold w-full text-center'>

            <div>PROPONENTE $nombreProponente</div>

        ";

        if(!empty($nombreR)){
            $htm.="            
                <div>REPRESENTANTE LEGAL $nombreR</div>
            ";
        }

        $htm.="

            <div>$fecha</div>

           </div>
        ";



        return $htm;


    }


    public function seguimientoEvaluacion($indicadorArray,$periodicidadArray,$actividadSeguimientoArray,$medioVerficiacionArray,$observacionArray) {


        $htm= "

           <div class='font-bold mt-2'>7. SEGUIMIENTO Y EVALUACIÓN </div>

           <div class='mt-1 texto-justificado'>
            Las acciones específicas tanto para el seguimiento como para la evaluación del proyecto por parte del ejecutor del proyecto son las siguientes:                  
           </div>


           <table class='styled-table mt-1'>

              <tr>
                <td class='font-bold' align='center'>INDICADOR</td>
                <td class='font-bold' align='center'>PERIODICIDAD</td>
                <td class='font-bold' align='center'>ACTIVIDAD DE SEGUIMIENTO / EVALUACION</td>
                <td class='font-bold' align='center'>MEDIO DE VERIFICACIÓN</td>
              </tr>

        ";


        foreach ($indicadorArray as $clave => $valor) {
    
        $htm.="

            <tr>

                <td>".$indicadorArray[$clave]."</td>
                <td>".$periodicidadArray[$clave]."</td>
                <td>".$actividadSeguimientoArray[$clave]."</td>
                <td>".$medioVerficiacionArray[$clave]."</td>

            </tr>

        ";

        }

        $htm.="


            </table>
        ";

        return $htm;


    }


    public function pronosticos__resultados($deportistaOrganismoArray,$disciplinaArray,$categoriaEdadArray,$eventoParticipacionArray,$pronosticoUbicacionArray) {


        $htm= "

           <div class='font-bold mt-1 texto-cursiva'>6.2 Pronóstico de resultados</div>


           <table class='styled-table mt-1'>

              <tr>
                <td class='font-bold' align='center'>N°</td>
                <td class='font-bold' align='center'>DEPORTISTA / ORGANISMO</td>
                <td class='font-bold' align='center'>DISCIPLINA</td>
                <td class='font-bold' align='center'>CATEGORÍA DE EDAD</td>
                <td class='font-bold' align='center'>EVENTO DE PARTICIPACIÓN</td>
                <td class='font-bold' align='center'>PRONÓSTICO DE UBICACIÓN</td>
              </tr>

        ";


        foreach ($deportistaOrganismoArray as $clave => $valor) {
    
        $htm.="

            <tr>

                <td>".(intval($clave)+1)."</td>
                <td>".$deportistaOrganismoArray[$clave]."</td>
                <td>".$disciplinaArray[$clave]."</td>
                <td>".$categoriaEdadArray[$clave]."</td>
                <td>".$eventoParticipacionArray[$clave]."</td>
                <td>".$pronosticoUbicacionArray[$clave]."</td>

            </tr>

        ";

        }

        $htm.="


            </table>
        ";

        return $htm;


    }


    public function resultadosEsperados($objetivoEspecificoArray,$nombreIndicadorArray,$descripcionArray,$metodoCalculoArray,$metaFinalArray,$periodicidadArray,$medioVerificacionArray) {


        $htm= "

            <style>
                .styled-table {
                    width: 100%; 
                    table-layout: fixed;
                }
                .styled-table td, .styled-table th {
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }
                .styled-table .metodo-calculo-col {
                    width: 15%;
                }
            </style>

           <div class='font-bold mt-2'>6. RESULTADOS ESPERADOS O METAS </div>

           <div class='font-bold mt-1 texto-cursiva'>6.1 Metas</div>


           <table class='styled-table mt-1'>

              <tr>
                <td class='font-bold' align='center'>OBJETIVO ESPECÍFICO</td>
                <td class='font-bold' align='center'>NOMBRE DEL INDICADOR</td>
                <td class='font-bold' align='center'>DESCRIPCION</td>
                <td class='font-bold' align='center'>METODO CALCULO</td>
                <td class='font-bold' align='center'>META FINAL</td>
                <td class='font-bold' align='center'>PERIODICIDAD</td>
              </tr>

        ";


        foreach ($objetivoEspecificoArray as $clave => $valor) {
    
        $htm.="

            <tr>

                <td>".$objetivoEspecificoArray[$clave]."</td>
                <td>".$nombreIndicadorArray[$clave]."</td>
                <td>".$descripcionArray[$clave]."</td>
                <td>".$metodoCalculoArray[$clave]."</td>
                <td>".$metaFinalArray[$clave]."</td>
                <td>".$periodicidadArray[$clave]."</td>

            </tr>

        ";

        }

        $htm.="


            </table>
        ";

        return $htm;


    }



    public function presupuesto__modificacion($totalPresupuestoBd,$letrasMonto,$codigo,$sector) {

        $aniosComponentes=$this->constructor->select__general__incentivo("SELECT anio FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion' GROUP BY anio;");


        $htm= '

           <div class="font-bold mt-2">5. PRESUPUESTO</div>

           <div class="mt-1">

                El presupuesto requerido para la ejecución del proyecto es de USD <span class="font-bold">$ '.number_format($totalPresupuestoBd, 2, '.', ',').'</span> (<span class="font-bold">'.strtolower($letrasMonto).'</span> dólares de los Estados Unidos de Norteamérica) 

           </div>

        ';

        foreach ($aniosComponentes as $clave => $valor) {
    
            $aniosComponentesM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL AND a.estado='A' AND a.tipoIngreso='modificacion' GROUP BY a.idComponentes;");

            $aniosFemeninoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL AND a.estado='A' AND a.tipoIngreso='modificacion' GROUP BY a.idComponentes;");


            $aniosPriorizadoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL AND a.estado='A' AND a.tipoIngreso='modificacion' GROUP BY a.idComponentes;");

            $montoComponentes=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC=$valorC["total"];
            }

            $montoFemeninos=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF=$valorF["total"];
            }


            $montoPriorizado=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP=$valorP["total"];
            }

            $htm.="

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO ".$valor["anio"]."</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";

            }

            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='right'>".number_format($totalC, 2, '.', ',')."</td>

            ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='right' class='font-bold'>".number_format($totalF, 2, '.', ',')."</td>
                    <td align='right' class='font-bold'>".number_format($totalP, 2, '.', ',')."</td>

                ";

            }


            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector)===3) {
                $htm.="

                    <td></td>
                    <td></td>

                ";
            }

            $htm.="


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador=0;

                $sumadorComparador=$sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador=$sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador>1) {

                    $htm.="

                        <tr>

                            <td>".$valorCom["componente"]."</td>
                            <td align='right'>".number_format($valorCom["totalComponentes"], 2, '.', ',')."</td>

                    ";

                   if (intval($sector)===3) {
                        $htm.="

                            <td align='right'>".number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',')."</td>
                            <td align='right'>".number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',')."</td>

                        ";
                    }

                    $html."

                        </tr>

                    ";

                }


            }


            $htm.="

                </table>


            ";


        }

  
        return $htm;


    }


    public function presupuesto($totalPresupuestoBd,$letrasMonto,$codigo,$sector) {

        $aniosComponentes=$this->constructor->select__general__incentivo("SELECT anio FROM proyecto_presupuesto WHERE codigo='$codigo' GROUP BY anio;");


        $htm= '

           <div class="font-bold mt-2">5. PRESUPUESTO</div>

           <div class="mt-1">

                El presupuesto requerido para la ejecución del proyecto es de USD <span class="font-bold">$ '.number_format($totalPresupuestoBd, 2, '.', ',').'</span> (<span class="font-bold">'.strtolower($letrasMonto).'</span> dólares de los Estados Unidos de Norteamérica) 

           </div>

        ';

        foreach ($aniosComponentes as $clave => $valor) {
    
            $aniosComponentesM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='componente' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $aniosFemeninoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='femenino' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");


            $aniosPriorizadoM=$this->constructor->select__general__incentivo("SELECT b.nombre AS componente,SUM(a.total) AS totalComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.anio='".$valor["anio"]."' AND a.codigo='$codigo' AND sector='priorizado' AND idNivel1 IS NOT NULL GROUP BY a.idComponentes;");

            $montoComponentes=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='componente' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL GROUP BY codigo;");

            foreach ($montoComponentes as $valorC) {
                $totalC=$valorC["total"];
            }

            $montoFemeninos=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='femenino' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoFemeninos as $valorF) {
                $totalF=$valorF["total"];
            }


            $montoPriorizado=$this->constructor->select__general__incentivo("SELECT SUM(total) AS total FROM proyecto_presupuesto WHERE codigo='$codigo' AND sector='priorizado' AND anio='".$valor["anio"]."' AND idNivel1 IS NOT NULL GROUP BY codigo;");
            foreach ($montoPriorizado as $valorP) {
                $totalP=$valorP["total"];
            }

            $htm.="

                <table class='styled-table mt-2'>

                <tr>

                    <td class='font-bold' rowspan='2' align='center'>AÑO ".$valor["anio"]."</td>

                    <td class='font-bold' align='center'></td>

                    ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='center' class='font-bold'>Sector Priorizado</td>
                    <td align='center' class='font-bold'>Sector Rama Femenina</td>

                ";

            }

            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='right'>".number_format($totalC, 2, '.', ',')."</td>

            ";

            if (intval($sector)===3) {
               
                $htm.="

                    <td align='right' class='font-bold'>".number_format($totalF, 2, '.', ',')."</td>
                    <td align='right' class='font-bold'>".number_format($totalP, 2, '.', ',')."</td>

                ";

            }


            $htm.="

                </tr>

                <tr>

                    <td class='font-bold' align='center'>
                        COMPONENTES
                    </td>
                    <td></td>

            ";

            if (intval($sector)===3) {
                $htm.="

                    <td></td>
                    <td></td>

                ";
            }

            $htm.="


                </tr>

            ";

            foreach ($aniosComponentesM as $clave => $valorCom) {

                $sumadorComparador=0;

                $sumadorComparador=$sumadorComparador + floatval($valorCom["totalComponentes"]);

                if ($sector) {
                    $sumadorComparador=$sumadorComparador + floatval($aniosFemeninoM[$clave]["totalComponentes"]) + floatval($aniosPriorizadoM[$clave]["totalComponentes"]);
                }

                if ($sumadorComparador>1) {

                    $htm.="

                        <tr>

                            <td>".$valorCom["componente"]."</td>
                            <td align='right'>".number_format($valorCom["totalComponentes"], 2, '.', ',')."</td>

                    ";

                   if (intval($sector)===3) {
                        $htm.="

                            <td align='right'>".number_format($aniosFemeninoM[$clave]["totalComponentes"], 2, '.', ',')."</td>
                            <td align='right'>".number_format($aniosPriorizadoM[$clave]["totalComponentes"], 2, '.', ',')."</td>

                        ";
                    }

                    $html."

                        </tr>

                    ";

                }


            }


            $htm.="

                </table>


            ";


        }

  
        return $htm;


    }

    public function portada($tipoUsuario,$credencialProponente,$nombreProponente,$fecha,$nombreProyecto,$codigo) {


        $htm= "



            <div class='w-50 transform__1000 color__blue font-bold text-center font-bold  uppercase'>
               <div class='w-full text-right font-size color__blue'> $nombreProyecto</div>
               <div class='w-full text-right font-size__12 color__blue'>$codigo</div>
             </div>


            <div class='w-35 transform__500 color__blue font-bold'>

                <div class='text-right color__blue'>
                    $nombreProponente
                </div>

                <div class='text-right color__blue'>
                    $tipoUsuario
                </div>

                <div class='text-right color__blue'>
                    $fecha
                </div>

             </div>


             <div class='salto__pagina'></div>

        ";

        return $htm;


    }

    public function beneficiarios($nombreArray,$rangoArray,$generoArray,$autentificacionArray,$discapacidadArray,$cantidadArray) {


        $htm= "


        <div>

           <div class='font-bold mt-2'>4. BENEFICIARIOS:</div>

           <div class='font-bold mt-1 texto-cursiva'>4.1 Beneficiarios directos</div>

           <div class='mt-1'>
            Son las personas que se benefician directamente de la ejecución del proyecto.  
           </div>


            <table class='styled-table mt-2'>

            <tr>

                <td class='font-bold' align='center'>
                    BENEFICIARIOS 
                </td>

                <td class='font-bold' align='center'>
                   RANGO DE EDAD 
                </td>

               <td class='font-bold' align='center'>
                   GÉNERO 
                </td>

                <td class='font-bold' align='center'>
                   AUTOIDENTIFICACIÓN  
                </td>

                <td class='font-bold' align='center'>
                   TIPO DE DISCAPACIDAD   
                </td>

                <td class='font-bold' align='center'>
                   CANTIDAD  
                </td>

            </tr>

        ";

        $sumaBeneficiarios=0;

        foreach ($nombreArray as $clave => $valor) {

        $htm.= "

            <tr>

                <td>".$nombreArray[$clave]."</td>
                <td>".$rangoArray[$clave]."</td>
                <td>".$generoArray[$clave]."</td>
                <td>".$autentificacionArray[$clave]."</td>
                <td>".$discapacidadArray[$clave]."</td>
                <td align='center'>".$cantidadArray[$clave]."</td>

            </tr>

        ";

        $sumaBeneficiarios=intval($sumaBeneficiarios) + intval($cantidadArray[$clave]);

        }

        $htm.= "

            <tr>

                <td colspan='5' align='center' class='font-bold'>

                    TOTAL BENEFICIARIOS 

                </td>

                <td colspan='1' align='center' class='font-bold'>

                   $sumaBeneficiarios

                </td>

            </tr>

            </table>

        ";

        return $htm;


    }



    public function baseLegal($ley,$baseLegal) {


        $htm= "


        <div>

           <div class='font-bold mt-2'>3. BASE LEGAL :</div>

        ";

        foreach ($ley as $clave => $valor) {

            $htm.="

                <div class='font-bold mt-1 texto-cursiva'>".$valor."</div>

            ";
            $htm .= 
            "
            <div class='mt-1 texto-justificado'>" 
                . nl2br($baseLegal[$clave]) . "
            </div>
            ";

        }

        $htm.= "

        </div>

        ";

        return $htm;


    }


    public function datosSolicitante($nombreProponente,$credencialProponente,$direccion,$celular,$correo,$nombreR,$cedulaR,$celular1R,$correo1R,$tipoUsuario,$indentificadorPr) {


        $htm= "

        <div class='font-bold mt-2'>1. DATOS DEL SOLICITANTE:</div>

        <table class='styled-table mt-1'>

          <tr>
            <td class='column1 font-bold bg-gris'>Nombre solicitante</td>
            <td class='column2 bg-gris'>$nombreProponente</td>
          </tr>

          <tr>
            <td class='column1 font-bold'>RUC / C.I.</td>
            <td class='column2'>$credencialProponente</td>
          </tr>
                
          <tr>
            <td class='column1 font-bold bg-gris'>Dirección</td>
            <td class='column2 bg-gris'>$direccion</td>
          </tr>

          <tr>
            <td class='column1 font-bold'>Celular</td>
            <td class='column2'>$celular</td>
          </tr>

          <tr>
            <td class='column1 font-bold bg-gris'>Correo</td>
            <td class='column2 bg-gris'>$correo</td>
          </tr>

        ";

        if (!empty($nombreR) && $indentificadorPr===true) {
          
        $htm.= "

          <tr>
            <td class='column1 font-bold'>Representante legal</td>
            <td class='column2'>$nombreR</td>
          </tr>

          <tr>
            <td class='column1 font-bold bg-gris'>C.I.</td>
            <td class='column2 bg-gris'>$cedulaR</td>
          </tr>

          <tr>
            <td class='column1 font-bold'>Celular</td>
            <td class='column2'>$celular1R</td>
          </tr> 

          <tr>
            <td class='column1 font-bold bg-gris'>Correo</td>
            <td class='column2 bg-gris'>$correo1R</td>
          </tr> 

        ";

        }

        $htm.="

          <tr>
            <td class='column1 font-bold'>Tipo de usuario</td>
            <td class='column2'>$tipoUsuario</td>
          </tr> 

        </table>

        ";

        return $htm;


    }

    public function descripcionProyecto($nombreProyecto,$tipo,$fechaInicioProyecto,$fechaFinProyecto,$objetivoGeneral,$objetivosEspecificos,$sector,$componentesPro,$nombreSector,$justificacion,$componentesPro__bienes) {

        $htm.=
        "
            <div class='font-bold mt-2'>2. DESCRIPCIÓN DEL PROYECTO</div>

            <div class='font-bold mt-1 texto-cursiva'>2.1. Nombre del proyecto:</div>

            <div class='mt-1 uppercase'>$nombreProyecto</div>

            <div class='font-bold mt-1 texto-cursiva'>2.2 Plazo de ejecución del proyecto</div>

            <table class='styled-table mt-1'>

              <tr>  
                <td colspan='4' align='center' class='font-bold'>
                    $tipo
                </td>
              </tr>

              <tr>
                <td class='column1 font-bold'>Fecha inicio</td>
                <td class='column2'>$fechaInicioProyecto</td>
                <td class='column1 font-bold'>Fecha fin</td>
                <td class='column2'>$fechaFinProyecto</td>
              </tr> 

            </table>

            <div class='font-bold mt-1 texto-cursiva'>2.3 Objetivos</div>

            <div class='font-bold mt-1 texto-cursiva'>2.3.1 Objetivo General</div>


            <table class='mt-1'>

              <tr>
                <td class='column1'>$objetivoGeneral</td>
              </tr> 

            </table>

            <div class='font-bold mt-1 texto-cursiva'>2.3.2 Objetivos específicos</div>

            <table class='mt-1'>

        ";

        foreach ($objetivosEspecificos as $valor) {
         $htm.=
         "
            <tr>

                <td class='column1 vertical'>
                    <span class='circulo-vineta'></span>$valor
                </td>

            </tr> 

         ";   
        }

        $htm.="

            </table>

            <div class='font-bold mt-1 texto-cursiva'>2.4 Alineación estratégica</div>

            <div class='mt-1 texto-cursiva'>Este programa/proyecto se alinea a la Planificación estratégica del Ministerio del Deporte a través del siguiente Objetivo Estratégico: </div>

            <table class='styled-table mt-1'>

            <tr>

                <td class='font-bold' align='center'>
                    Objetivo estratégico 
                </td>


                <td class='font-bold' align='center'>
                   Campo de acción
                </td>

            </tr>

        ";

        if(!empty($sector)){

             if(intval($sector)===1 || intval($sector)===2 || intval($sector)===3){

                $htm.="

                <tr>

                    <td>
                        Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales. 
                    </td>

                    <td>
                        $nombreSector
                    </td>

                 </tr>

                 ";


             }

             if(intval($sector)===4 || intval($sector)===5){

                $htm.="

                 <tr>

                    <td>
                        Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  
                    </td>

                    <td>
                        $nombreSector
                    </td>

                 </tr>

                 ";


             }


        }

        if(!empty($componentesPro) || !empty($componentesPro__bienes)){


            $htm.="

             <tr>

                <td>
                    Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional.
                </td>

                <td>
                   Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva 
                </td>

             </tr>

            ";


        }

        $htm.="

            </table>

            <div class='font-bold mt-1 texto-cursiva'>2.5 Justificación y beneficio del proyecto</div>

            <div class='mt-1'>$justificacion</div>


        ";

        return $htm;

    }

}