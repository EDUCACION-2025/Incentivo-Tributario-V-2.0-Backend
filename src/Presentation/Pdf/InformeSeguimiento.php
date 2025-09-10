<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;
use App\utilities\validaciones\NumerosLetras;

class InformeSeguimiento {

	private static $instance = null;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

        $this->constructor = ServicesAdmin::getInstance();
        $this->numerosLetras = NumerosLetras::getInstance();

    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new InformeSeguimiento();
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

    public function footer_informe__seguimiento($consulta,$consulta2) {

        foreach ($consulta as $valor) {
            $contribucionProyectoTexto=$valor["contribucionProyectoTexto"];
            $conclusionesTexto=$valor["conclusionesTexto"];
            $recomendacionesTexto=$valor["recomendacionesTexto"];
        }

        $htm.= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left'>
                            7. CONTRIBUCIÓN DEL PROYECTO EN EL SECTOR DEPORTIVO 
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>

                            $contribucionProyectoTexto 

                        </td>

                    </tr>

                </tbody>

            </table>

        ";

        $htm.= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left'>
                           8. CONCLUSIONES 
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>

                            $conclusionesTexto 

                        </td>

                    </tr>

                </tbody>

            </table>

        ";

        $htm.= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left'>
                           9. RECOMENDACIONES  
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>

                            $recomendacionesTexto 

                        </td>

                    </tr>

                </tbody>

            </table>

        ";

        return $htm;

    }

    public function seguimiento_informe__seguimiento($consulta,$consultaEstados) {

        $htm= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left' colspan='4'>
                           6. SEGUIMIENTO Y CONTROL 
                        </th>

                    </tr>

                    <tr>

                        <td class='font-bold text-center'>

                            INDICADOR  

                        </td>

                        <td class='font-bold text-center'>

                            PERIODICIDAD 

                        </td>


                        <td class='font-bold text-center'>

                            ACTIVIDAD DE SEGUIMIENTO / EVALUACIÓN  

                        </td>


                        <td class='font-bold text-center'>

                            MEDIO DE VERIFICACIÓN  

                        </td>

                    </tr>


                </thead>


                <tbody>";

        foreach ($consulta as $valor) {
        
        $htm.="

            <tr>

                <td class='background-color__cyan'>

                    ".$valor["indicador"]."

                </td>

                <td class='background-color__cyan'>

                    ".$valor["periodicidad"]."

                </td>

                <td class='background-color__cyan'>

                    ".$valor["actividadSeguimiento"]."

                </td>

                <td class='background-color__cyan'>

                    ".$valor["medioVerficiacion"]."

                </td>

            </tr>


            <tr>

                <td>

                    ".$valor["indicadorArray"]."

                </td>

                <td>

                    ".$valor["periodicidadArray"]."

                </td>

                <td>

                    ".$valor["actividadSeguimientoArray"]."

                </td>

                <td>

                    ".$valor["medioVerficiacionArray"]."

                </td>

            </tr>

        ";

        }

        foreach ($consultaEstados as $valor) {
           $justificacion__seguimiento=$valor["justificacion__seguimiento"];
        }


        $htm.="

                <tr>

                    <td>
                        JUSTIFICACIÓN
                    </td>


                    <td colspan='3'>
                        $justificacion__seguimiento
                    </td>

                </tr>

                </tbody>

            </table>

        ";


        return $htm;

    }

    public function presupuesto_informe__seguimiento($consultaComponentes,$consultaFemeninos,$consultaPriorizados,$informacionCertificacion) {


        $htm= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left' colspan='3'>
                          5. PRESUPUESTO DEL PROYECTO 
                        </th>

                    </tr>

                    <tr>

                        <td class='font-bold text-center'>

                            MONTO CALIFICADO  

                        </td>

                        <td class='font-bold text-center'>

                            MONTO CERTIFICADO

                        </td>


                        <td class='font-bold text-center'>

                            DIFERENCIA 

                        </td>

                    </tr>


                </thead>


                <tbody>

                    <tr>

                        <td>

                            $informacionCertificacion[0] 

                        </td>

                        <td>

                            $informacionCertificacion[2]

                        </td>


                        <td>

                            ".($informacionCertificacion[0] - $informacionCertificacion[2])."

                        </td>

                    </tr>


                </tbody>

            </table>

        ";


        $htm.= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <td class='font-bold text-center'>

                            COMPONENTES

                        </td>

                        <td class='font-bold text-center'>

                            MONTO CALIFICADO

                        </td>


                        <td class='font-bold text-center'>

                            DISTRIBUCIÓN DEL MONTO CERTIFICADO  

                        </td>

                    </tr>


                </thead>


                <tbody>";

        $sumarCalificado=0;
        $sumarCertificado=0;


        foreach ($consultaComponentes as $valor) {

            if (floatval($valor["montoCertificadoArray"])>0) {

                $htm.="

                    <tr>

                        <td>

                            ".$valor["componente"]."

                        </td>

                        <td>

                            ".$valor["montoCalificado"]."

                        </td>


                        <td>

                            ".$valor["montoCertificadoArray"]."

                        </td>

                    </tr>


                ";

            }


             $sumarCalificado=floatval($sumarCalificado) + floatval($valor["montoCalificado"]);
             $sumarCertificado=floatval($sumarCertificado) + floatval($valor["montoCertificadoArray"]);

        }


        foreach ($consultaFemeninos as $valor) {

            if (floatval($valor["montoCertificadoArray"])>0) {
              
                $htm.="

                    <tr>

                        <td>

                            RAMA FEMENINA 

                        </td>

                        <td>

                            ".$valor["montoCalificado"]."

                        </td>


                        <td>

                            ".$valor["montoCertificadoArray"]."

                        </td>

                    </tr>


                ";

            }


             $sumarCalificado=floatval($sumarCalificado) + floatval($valor["montoCalificado"]);
             $sumarCertificado=floatval($sumarCertificado) + floatval($valor["montoCertificadoArray"]);


        }

       foreach ($consultaPriorizados as $valor) {

            if (floatval($valor["montoCertificadoArray"])>0) {
          
                $htm.="

                    <tr>

                        <td>

                            SECTOR PRIORIZADO 

                        </td>

                        <td>

                            ".$valor["montoCalificado"]."

                        </td>


                        <td>

                            ".$valor["montoCertificadoArray"]."

                        </td>

                    </tr>


                ";

            }


         $sumarCalificado=floatval($sumarCalificado) + floatval($valor["montoCalificado"]);
         $sumarCertificado=floatval($sumarCertificado) + floatval($valor["montoCertificadoArray"]);


        }

        $htm.="

                </tbody>

                <tfoot>

                    <tr>

                        <td class='font-bold'>TOTAL</td>
                        <td class='font-bold'>".number_format($sumarCalificado, 2, ',', '.')."</td>
                        <td class='font-bold'>".number_format($sumarCertificado, 2, ',', '.')."</td>

                    </tr>


                </tfoot>

            </table>

        ";


        return $htm;

    }

    public function beneficiarios_informe__seguimiento($consulta,$consulta2) {

        $htm= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left' colspan='6'>
                          4. BENEFICIARIOS 
                        </th>

                    </tr>


                    <tr>

                        <th class='background-color__blue text-center'>
                          BENEFICIARIOS 
                        </th>

                        <th class='background-color__blue text-center'>
                          EDAD  
                        </th>

                        <th class='background-color__blue text-center'>
                          GENERO   
                        </th>

                        <th class='background-color__blue text-center'>
                          AUTOIDENTIFICACIÓN    
                        </th>

                        <th class='background-color__blue text-center'>
                          DISCAPACIDAD     
                        </th>

                        <th class='background-color__blue text-center'>
                          CANTIDAD      
                        </th>

                    </tr>

                </thead>

                <tbody>";

        foreach ($consulta as $valor) {
        
             $htm.="

                    <tr>

                        <td class='background-color__cyan'>

                            ".$valor["beneficiario"]."

                        </td>


                        <td class='background-color__cyan'>

                            ".$valor["edad"]."

                        </td>

                        <td class='background-color__cyan'>

                            ".$valor["genero"]."

                        </td>


                        <td class='background-color__cyan'>

                            ".$valor["autentificacion"]."

                        </td>


                        <td class='background-color__cyan'>

                            ".$valor["discapacidad"]."

                        </td>

                        <td class='background-color__cyan'>

                            ".$valor["cantidad"]."

                        </td>

                    </tr>";


                 $htm.="

                    <tr>

                        <td>

                            ".$valor["beneficiarioArray"]."

                        </td>


                        <td>

                            ".$valor["edadArray"]."

                        </td>

                        <td>

                             ".$valor["generoArray"]."

                        </td>


                        <td>

                            ".$valor["autentificacionArray"]."

                        </td>


                        <td>

                            ".$valor["discapacidadArray"]."
                            
                        </td>



                        <td>

                            ".$valor["cantidadArray"]."
                            
                        </td>

                    </tr>";

        }

        foreach ($consulta2 as $valor) {
            $justificacion__beneficiarios=$valor["justificacion__beneficiarios"];
        }

        $htm.="

                <tr>

                    <td>
                        JUSTIFICACIÓN
                    </td>


                    <td colspan='5'>
                        $justificacion__beneficiarios
                    </td>

                </tr>


                </tbody>

            </table>

        ";


        return $htm;

    }

    public function objetivo_informe__seguimiento($consulta,$consultaGeneral,$consultaEspecificos) {

        foreach ($consulta as $valor) {
            $objetivoGeneral__inicial=$valor["objetivoGeneral"];
        }

        foreach ($consultaGeneral as $valor) {
            $cumple=$valor["cumple"];
            $justificacion=$valor["justificacion"];
        }

        $htm= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left' colspan='2'>
                          3. OBJETIVOS DEL PROYECTO 
                        </th>

                        <th class='background-color__blue text-center'>
                          CUMPLE / NO CUMPLE
                        </th>

                        <th class='background-color__blue text-center'>
                          JUSTIFICACIÓN
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>

                            OBJETIVO GENERAL 

                        </td>

                        <td>

                            $objetivoGeneral__inicial 

                        </td>

                        <td>

                            $cumple

                        </td>


                        <td>

                            $justificacion

                        </td>

                    </tr>


                </tbody>

            </table>

        ";


        $htm.= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left'>
                          COMPONENTES
                        </th>

                        <th class='background-color__blue text-left'>
                          OBJETIVOS ESPECÍFICOS
                        </th>

                        <th class='background-color__blue text-center'>
                          CUMPLE / NO CUMPLE
                        </th>

                        <th class='background-color__blue text-center'>
                          JUSTIFICACIÓN
                        </th>

                    </tr>

                </thead>


                <tbody>";


            foreach ($consultaEspecificos as $valor) {

            $htm.="

                    <tr>

                        <td>

                            ".$valor["componente"]."

                        </td>

                        <td>

                            ".$valor["objetivoEspecifico"]."

                        </td>

                        <td>

                             ".$valor["cumple"]."

                        </td>


                        <td>

                             ".$valor["justificacion"]."

                        </td>

                    </tr>

            ";

            }


            $htm.="



                </tbody>

            </table>

        ";

        return $htm;

    }

    public function resumen_informe__seguimiento($consulta,$consultaResumen) {

        foreach ($consulta as $valor) {
            $fechaInicio_real=$valor["fechaInicio_real"];
            $fechaFin__real=$valor["fechaFin__real"];
        }

        foreach ($consultaResumen as $valor) {
            $fechaInicio=$valor["fechaInicio"];
            $fechaFin=$valor["fechaFin"];
            $justificacion=$valor["justificacion"];
        }


        $htm= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left' colspan='4'>
                           2. RESUMEN DEL PROYECTO 
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td colspan='4' class='text-center'>
                            PLAZO DE EJECUCIÓN
                        </td>

                    </tr>

                    <tr>

                        <td>

                            FECHA INICIO 

                        </td>

                        <td>

                            $fechaInicio_real

                        </td>


                        <td>

                            FECHA FIN  

                        </td>

                        <td>

                            $fechaFin__real

                        </td>

                    </tr>

                    <tr>

                        <td>

                            FECHA DE INICIO REAL 

                        </td>

                        <td>

                            $fechaInicio

                        </td>


                        <td>

                            FECHA FIN REAL  

                        </td>

                        <td>

                            $fechaFin

                        </td>

                    </tr>

                    <tr>

                        <td>

                            JUSTIFICACIÓN 

                        </td>

                        <td colspan='3'>

                            $justificacion

                        </td>

                    </tr>


                </tbody>

            </table>

        ";


        return $htm;

    }

    public function datosGenerales_informe__seguimiento($consulta) {

        foreach ($consulta as $valor) {
            $codigoReal=$valor["codigoReal"];
            $nombreProyecto=$valor["nombreProyecto"];
        }

        $htm= "

            <table class='styled-table mt-2'>

                <thead>

                    <tr>

                        <th class='background-color__blue text-left' colspan='2'>
                           1. DATOS GENERALES 
                        </th>

                    </tr>

                    <tr>

                        <td class='font-bold text-center'>

                            NÚMERO 

                        </td>

                        <td class='font-bold text-center'>

                            NOMBRE DEL PROYECTO 

                        </td>


                    </tr>


                </thead>


                <tbody>

                    <tr>

                        <td>

                            $codigoReal 

                        </td>

                        <td>

                            $nombreProyecto

                        </td>

                    </tr>


                </tbody>

            </table>

        ";


        return $htm;

    }

    public function portada__informe__seguimiento($consulta) {

        foreach ($consulta as $valor) {
            $codigoReal=$valor["codigoReal"];
            $sector=$valor["sector"];
        }

        $htm= "

           <div class='font-bold text-14 text-center'>
            INFORME FINAL DE CUMPLIMIENTO
           </div>
           <div class='font-bold font-size__12 text-center'>
            $codigoReal
           </div>
           <div class='font-bold font-size__12 text-center'>
            $sector
           </div>

        ";


        return $htm;

    }


    public function portada__informe__seguimiento__tecnico($consulta,$tipo) {

        foreach ($consulta as $valor) {
            $codigoReal=$valor["codigoReal"];
            $sector=$valor["sector"];
        }

        if ($tipo==="TECNICO") {
            $areaRotulo="(AREA TÉCNICA)";
        }else{
            $areaRotulo="(AREA DE INFRAESTRUCTURA)";
        }

        $fechaDefinida=$this->formatearFecha($this->fecha);

        $htm= "

           <div class='font-bold text-14 text-center'>
            INFORME DE CUMPLIMIENTO TÉCNICO DE PROGRAMAS Y/O PROYECTOS DEPORTIVOS
           </div>
           <div class='font-bold font-size__12 text-center'>
            $areaRotulo
           </div>
           <div class='font-bold font-size__12 text-right mt-2'>
            $fechaDefinida
           </div>

        ";


        return $htm;

    }

    public function informacion__general__del__proyecto($consulta) {

        foreach ($consulta as $valor) {
            
            
            $nombreSolicitante=$valor["nombreSolicitante"];
            $credencialSolicitante=$valor["credencialSolicitante"];
            $codigoReal=$valor["codigoReal"];
            $nombreProyecto=$valor["nombreProyecto"];
            $sector=$valor["sector"];
            $plurianual=$valor["plurianual"];
            $monto=$valor["monto"];
            $fechaCalifica=$valor["fechaCalifica"];
        }

        $htm= "

           <div class='font-bold text-11 text-left mt-2'>
            3.  ANÁLISIS
           </div>
  
           <div class='font-bold text-11 text-left mt-2'>
            3.1. INFORMACIÓN GENERAL DEL PROYECTO
           </div>

           <div class='text-11 text-left mt-1'>
            
            <table class='styled-table mt-2'>

                <tbody>

                    <tr>

                        <td class='font-bold'>

                            Nombre del solicitante del proyecto

                        </td>

                        <td>

                            $nombreSolicitante

                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Nro. Identificación

                        </td>

                        <td>

                            $credencialSolicitante

                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>

                            Código del proyecto

                        </td>

                        <td>

                            $codigoReal

                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>

                            Nombre del proyecto

                        </td>

                        <td>

                            $nombreProyecto

                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Sector al que contribuye

                        </td>

                        <td>

                            $sector

                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Tipo de proyecto 

                        </td>

                        <td>

                            $plurianual

                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Monto calificado

                        </td>

                        <td>

                            $monto

                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                           Fecha de calificación del proyecto

                        </td>

                        <td>

                            $fechaCalifica

                        </td>

                    </tr>

                </tbody>

            </table>

           </div>

        ";


        return $htm;

    }

    public function firma__de__resultados($consulta) {

        foreach ($consulta as  $valor) {
            $conclusiones=$valor["conclusiones"];
            $recomendaciones=$valor["recomendaciones"];
            $nombreAnalista=$valor["nombreAnalista"];
            $nombreDirector=$valor["nombreDirector"];
        }

        $htm= "

           <div class='font-bold text-11 text-left mt-2'>
            4.  CONCLUSIONES
           </div>

           <div class='texto-justificado mt-1'>$conclusiones</div>


           <div class='font-bold text-11 text-left mt-2'>
            5.  RECOMENDACIONES
           </div>

           <div class='texto-justificado mt-1'>$recomendaciones</div>

           <div class='text-11 text-left mt-4'>
            
            <table class='styled-table'>

                <tbody>

                    <tr>

                        <td class='font-bold'>

                            Director área técnica APROBADO POR

                        </td>

                        <td>
                            $nombreDirector
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>
                            Analista área técnica ELABORADO POR
                        </td>

                        <td>
                            $nombreAnalista
                        </td>

                    </tr>

                </tbody>

            </table>

           </div>

        ";


        return $htm;

    }

    public function analisis__de__resultados($consulta) {

        foreach ($consulta as  $valor) {
            $fechaEjecucionProyecto=$valor["fechaEjecucionProyecto"];
            $fechaEjecucionProyectoObservacion=$valor["fechaEjecucionProyectoObservacion"];
            $objetivosAlineacion=$valor["objetivosAlineacion"];
            $objetivosAlineacionObservacion=$valor["objetivosAlineacionObservacion"];
            $beneficiariosProyecto=$valor["beneficiariosProyecto"];
            $beneficiariosProyectoObservacion=$valor["beneficiariosProyectoObservacion"];
            $presupuestoProyecto=$valor["presupuestoProyecto"];
            $presupuestoProyectoObservacion=$valor["presupuestoProyectoObservacion"];
            $metasIndicadores=$valor["metasIndicadores"];
            $metasIndicadoresObservacion=$valor["metasIndicadoresObservacion"];
            $contribucionProyecto=$valor["contribucionProyecto"];
            $contribucionProyectoObservacion=$valor["contribucionProyectoObservacion"];
        }

        $htm= "

           <div class='font-bold text-11 text-left mt-2'>
            3.2. ANÁLISIS RESULTADOS
           </div>

           <div class='text-11 text-left mt-1'>
            
            <table class='styled-table mt-2'>

                <thead>

                    <td class='font-bold text-center'>
                        PARÁMETROS DE EVALUACIÓN
                    </td>

                    <td class='font-bold text-center'>
                        CUMPLIMIENTO</br>(Cumple o No cumple)
                    </td>

                    <td class='font-bold text-center'>
                        OBSERVACIÓN
                    </td>

                </thead>

                <tbody>

                    <tr>

                        <td class='font-bold'>

                            Fechas de ejecución del proyecto

                        </td>

                        <td>
                            $fechaEjecucionProyecto
                        </td>

                        <td>
                            $fechaEjecucionProyectoObservacion
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Objetivos y alineación estratégica

                        </td>

                        <td>
                            $objetivosAlineacion
                        </td>

                        <td>
                            $objetivosAlineacionObservacion
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>

                            Beneficiarios del proyecto

                        </td>

                        <td>
                            $beneficiariosProyecto
                        </td>

                        <td>
                            $beneficiariosProyectoObservacion
                        </td>

                    </tr>


                    <tr>

                        <td class='font-bold'>

                            Presupuesto del proyecto

                        </td>

                        <td>
                            $presupuestoProyecto
                        </td>

                        <td>
                            $presupuestoProyectoObservacion
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Metas e indicadores

                        </td>

                        <td>
                            $metasIndicadores
                        </td>

                        <td>
                            $metasIndicadoresObservacion
                        </td>

                    </tr>

                    <tr>

                        <td class='font-bold'>

                            Contribución del proyecto

                        </td>

                        <td>
                            $contribucionProyecto
                        </td>

                        <td>
                            $contribucionProyectoObservacion
                        </td>

                    </tr>

                </tbody>

            </table>

           </div>

        ";


        return $htm;

    }

    public function antecedentes__informe__tecnico($consulta,$tipo) {

        $htm= "

           <div class='font-bold text-11 text-left mt-4'>
            1.  ANTECEDENTES
           </div>

        ";

        return $htm;

    }

    public function base__legal__informe__tecnico() {

        $htm= "

           <div class='font-bold text-11 text-left mt-4'>
            2.  BASE LEGAL
           </div>


           <div class='text-11 texto-justificado mt-2'>
            Mediante Acuerdo Ministerial Nro. 0434 de fecha 17 de noviembre de 2021, se expide la “Norma para la calificación de prioridad, así como para la emisión de la certificación de beneficiarios que pueden acogerse a la deducción del 100% adicional para el cálculo de la base imponible del impuesto a la renta de los gastos de patrocinio, promoción o publicidad realizados a favor de deportistas y organizadores de programas y/o proyectos deportivos”; en el cual establece: 
           </div>

           <div class='text-11 texto-justificado mt-1'>
            <span class='font-bold'>Artículo 50.-</span> “Del informe final de cumplimiento. - Una vez ejecutado el programa y/o proyecto deportivo y generadas las certificaciones que correspondan, el/la solicitante presentará al Comité un informe final que dé cuenta del cumplimiento de los objetivos, metas, componentes, montos, plazos y demás datos relevantes establecidos en los mismos. Dicho informe será cargado a través del aplicativo informático adjuntando las evidencias fotográficas, memorias, certificaciones, u otros documentos que permitan contrastar la información proporcionada. Adicionalmente, adjuntará una declaración juramentada celebrada ante notario público en la cual se detalle, al menos, lo siguiente: a) Que los comprobantes de venta de publicidad, patrocinio y o promoción fueron generados guardando relación directa con los proyectos y/o programa cuya prioridad ha sido calificada por el Ministerio del Deporte; b) Que los recursos obtenidos producto del patrocinio, promoción o publicidad fueron empleados exclusivamente en los componentes y actividades mencionados en el programa y/o proyecto cuya prioridad ha sido calificada por el Ministerio del Deporte; c) Que los recursos recibidos son lícitos, razón por la cual faculta a los organismos de control competentes a verificar en cualquier momento su proveniencia; d) Que se responsabiliza por el contenido, veracidad y autenticidad de la información y documentación cargada en el aplicativo informático durante las fases establecidas para la obtención de la calificación de prioridad de los programas y/o proyectos, así como de la certificación de beneficiarios para acceder a la deducibilidad; e) Que no se halla inmerso en las prohibiciones establecidas en la norma expedida por el Ministerio del Deporte, incluyendo aquellas excepciones para beneficiarse del incentivo tributario establecidos en la Ley de Régimen Tributario Interno y su Reglamento de aplicación; f) Que conoce y se somete a los controles que pudieren realizar los entes de control en la materia; g) Que conoce y se sujeta al cumplimiento de la normativa legal vigente aplicable en estos casos, y declara expresamente el cumplimiento de los criterios establecidos en el artículo 28 del Reglamento para la aplicación de la Ley de Régimen Tributario Interno, en lo referente a programas y/o proyectos deportivos y a las condiciones establecidas en la citada norma para acceder al proceso de deducibilidad tributaria; h) Que declara y conoce que deberá realizar las retenciones y declaraciones de impuestos cuando corresponda; y, i) Los demás establecidos por el Comité de Calificación y Certificación para acceder al incentivo tributario. El término concedido para la presentación del informe final será de 45 días contado desde la fecha en la que el programa y/o proyecto deportivo concluyó su ejecución” 
           </div>

           <div class='text-11 texto-justificado mt-1'>
                <span class='font-bold'>Artículo 51.-</span> “Del análisis del informe final de cumplimiento. - Los informes finales serán direccionados a través del aplicativo informático a las áreas técnicas que conocieron sobre la postulación de los programas y/o proyectos deportivos, con el fin de que en el marco de sus competencias procedan con el análisis y contraste de la información proporcionada. En caso de detectarse observaciones o inconsistencias en el informe final, éstas deberán ser remitidas al solicitante con el fin de que proceda con la subsanación de las mismas en el término de 5 días. Hecho esto, emitirán un informe que será puesto en conocimiento del Comité y de la Dirección de Seguimiento de Planes, Programas y Proyectos, en el que se hará constar los aportes que los citados programas y/o proyectos deportivos han dado a la política pública, beneficios y demás aspectos que considere relevantes mencionar; o en su defecto, las observaciones o inconsistencias no subsanadas. En el mismo sentido, se generará el informe correspondiente en el caso de que el informe final no haya sido presentado”
           </div>

        ";

        return $htm;

    }


}