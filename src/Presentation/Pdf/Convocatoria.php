<?php


namespace App\Presentation\Pdf;

use App\Domain\Services\ServicesAdmin;



class Convocatoria {

    private $anio;

    private static $instance = null;

    public function __construct() {

        $this->constructor = ServicesAdmin::getInstance();
        $this->anio=date('Y');

    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Convocatoria();
        }
        return self::$instance;
    }    

    public function informacionProyecto__datosGenerales() {

        $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM comite WHERE YEAR(fecha)='".$this->anio."' AND activo='I';");
        foreach ($consulta as $valor) {
            $cuantosBd=$valor["cuantos"];
        }

        $cuantosBd=intval($cuantosBd) + 1;

        return $cuantosBd;

    }

    public function fecha__nombres($fecha) {

        $array=array();

        list($anio, $mes, $dia) = explode('-', $fecha);
        $timestamp = strtotime($fecha);

        $diaSemanaIngles = date('l', $timestamp); 

        $mesIngles = date('F', $timestamp);

        $diasEnEspanol = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        $mesesEnEspanol = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        $diaSemanaEspañol = $diasEnEspanol[$diaSemanaIngles];
        $mesEspañol = $mesesEnEspanol[$mesIngles];


        array_push($array,$diaSemanaEspañol);
        array_push($array,$mesEspañol);

        return $array;


    }


    public function convocatoria($fecha,$hora,$asunto,$ordenDia) {


        $arrayFecha = explode('-', $fecha);

        $numeroDeSesion=$this->informacionProyecto__datosGenerales();

        $numeroFormateado = ($numeroDeSesion >= 1 && $numeroDeSesion <= 10) ? sprintf('%03d', $numeroDeSesion) : (($numeroDeSesion >= 11 && $numeroDeSesion <= 99) ? sprintf('%02d', $numeroDeSesion) : (string) $numeroDeSesion);

        $diaMesLetra=$this->fecha__nombres($fecha);

        $horaFormato12 = date('h:i A', strtotime($hora));
        $zona = date('A', strtotime($hora));
        $horaSinFormato = date('H:i', strtotime($hora)); 
        $horaConH = str_replace(':', 'h', $horaSinFormato); 

        $htm= "

           <div class='font-bold text-center w-full text-12'>
            CONVOCATORIA $numeroFormateado - ".$this->anio."
           </div>

           <div class='justify__normal w-full mt-1 text-12'>
                ASUNTO: ".nl2br($asunto)."
           </div>

           <div class='mt-2 text-12'>

            De mi consideración:

           </div>

           <div class='justify__normal w-full mt-2 text-12'>
                De conformidad con lo dispuesto en el Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, mediante el cual se expidió la “CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA, LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O EVENTOS DEPORTIVOS”, y de conformidad al artículo 5 literal a), se CONVOCA a los/las señores/as miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario, a la sesión ordinaria, que se efectuará de manera presencial en la sala general de reuniones de la institución o telemática, para lo cual se remitirá el link previamente de conexión, el día ".$diaMesLetra[0]." ".$arrayFecha[2]." de ".$diaMesLetra[1]." de ".$arrayFecha[0].", a las $horaConH $zona. en la que se tratarán los siguientes puntos:  
           </div>

           <div class='mt-2 font-bold text-12'>

            ORDEN DEL DÍA  

           </div>


           <div class='justify__normal w-full mt-1 text-12'>

                ".nl2br($ordenDia)."

           </div>


           <div class='justify__normal w-full mt-2 text-12'>

            Conforme lo determina el artículo 5 literal b), del acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023, me permito indicar que deberá remitirse a esta Secretaría la información que recopila los informes técnicos y financieros previos, reportes; y, demás documentación relevante, para su conocimiento y análisis, previo a la realización del Comité. 

           </div>


        ";


        return $htm;


    }


    public function informacionProyecto__convocatoria($idComite) {

        $array=array();

        $consulta=$this->constructor->select__general__incentivo("SELECT a.fechaConvocatoria,a.horaConvocatoria,a.asunto,a.ordenDia,b.numeroComite FROM comite_convocatoria AS a INNER JOIN comite AS b ON a.idComite=b.id WHERE a.idComite='$idComite';");
        foreach ($consulta as $valor) {
            array_push($array,$valor["fechaConvocatoria"]);
            array_push($array,$valor["horaConvocatoria"]);
            array_push($array,$valor["asunto"]);
            array_push($array,$valor["ordenDia"]);
            array_push($array,$valor["numeroComite"]);
        }


        return $array;

    }

    public function convocatoria__existente($idComite) {

        $informacionArray=$this->informacionProyecto__convocatoria($idComite);
        $fecha=$informacionArray[0];
        $hora=$informacionArray[1];

        $arrayFecha = explode('-', $fecha);


        $diaMesLetra=$this->fecha__nombres($fecha);

        $horaFormato12 = date('h:i A', strtotime($hora));
        $zona = date('A', strtotime($hora));
        $horaSinFormato = date('H:i', strtotime($hora)); 
        $horaConH = str_replace(':', 'h', $horaSinFormato); 

        $htm= "

           <div class='font-bold text-center w-full text-12'>
            CONVOCATORIA ".$informacionArray[4]."
           </div>

           <div class='justify__normal w-full mt-1 text-12'>
                ASUNTO: ".nl2br($informacionArray[2])."
           </div>

           <div class='mt-2 text-12'>

            De mi consideración:

           </div>

           <div class='justify__normal w-full mt-2 text-12'>
                De conformidad con lo dispuesto en el Acuerdo Ministerial Nro. 0243 de 21 de noviembre de 2023, mediante el cual se expidió la “CODIFICACIÓN DE LA NORMA PARA LA CALIFICACIÓN DE PRIORIDAD, ASÍ COMO PARA LA EMISIÓN DE LA CERTIFICACIÓN DE BENEFICIARIOS QUE PUEDEN ACOGERSE A LA DEDUCCIÓN DEL CIENTO CINCUENTA POR CIENTO (150%) ADICIONAL PARA EL CÁLCULO DE LA BASE IMPONIBLE DEL IMPUESTO A LA RENTA, LOS GASTOS DE PUBLICIDAD, PROMOCIÓN Y PATROCINIO, REALIZADOS A FAVOR DE DEPORTISTAS Y PROGRAMAS, PROYECTOS O EVENTOS DEPORTIVOS”, y de conformidad al artículo 5 literal a), se CONVOCA a los/las señores/as miembros del Comité de Calificación y Certificación para acceder al Incentivo Tributario, a la sesión ordinaria, que se efectuará de manera presencial en la sala general de reuniones de la institución o telemática, para lo cual se remitirá el link previamente de conexión, el día ".$diaMesLetra[0]." ".$arrayFecha[2]." de ".$diaMesLetra[1]." de ".$arrayFecha[0].", a las $horaConH $zona. en la que se tratarán los siguientes puntos:  
           </div>

           <div class='mt-2 font-bold text-12'>

            ORDEN DEL DÍA  

           </div>


           <div class='justify__normal w-full mt-1 text-12'>

                ".nl2br($informacionArray[3])."

           </div>


           <div class='justify__normal w-full mt-2 text-12'>

            Conforme lo determina el artículo 5 literal b), del acuerdo Ministerial Nro. 0243, de 21 de noviembre de 2023, me permito indicar que deberá remitirse a esta Secretaría la información que recopila los informes técnicos y financieros previos, reportes; y, demás documentación relevante, para su conocimiento y análisis, previo a la realización del Comité. 

           </div>


        ";


        return $htm;


    }



}