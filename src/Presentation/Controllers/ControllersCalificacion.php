<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Application\Auth\Auth;
use App\Domain\Repositories\Dinardap;
use App\Presentation\Pdf\Base;
use App\Presentation\Pdf\Proyecto;
use App\utilities\validaciones\NumerosLetras;
use App\Presentation\Controllers\ControllersModificacion;
use App\Presentation\Controllers\ControllersAdmin;


class ControllersCalificacion{

    private $fecha;
    private $hora;
    private $anio;
    private $ruta;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->constructor = ServicesAdmin::getInstance();
        $this->constructor__auth = Auth::getInstance();
        $this->constructor__dinardap = Dinardap::getInstance();
        

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');
        $this->anio=date('Y');
        // $this->ruta='http://192.168.12.10/repositorio/incentivo2.0/';
        // $this->ruta='file:///C:/wamp64/www/repositorio/incentivo2.0/';
        // $this->ruta='../poa2/repositorio/incentivo2.0/';
        $this->ruta='../repositorio/incentivo2.0/';

        $this->constructor__basePdf = Base::getInstance();
        $this->proyectoPdf = Proyecto::getInstance();
        $this->numerosLetras = NumerosLetras::getInstance();

        $this->modificacion = new ControllersModificacion();

        $this->administradorController = new ControllersAdmin();

        $this->baseServidorFtp='/home/incentivoTributario__firma/';
        $this->baseServidorFtp__archivos='/home/repositorio/incentivoTributario/documentos/';


    }

    public function existente__preliminar__envio($post) {

        $consultaPrincipal=$this->constructor->select__general__incentivo("SELECT id,estadoCalificacion FROM proyecto_enviado WHERE codigoUsuario='".$post["codigo"]."';");

        foreach ($consultaPrincipal as $valorPrincipal) {
            $estadoCalificacion=$valorPrincipal["estadoCalificacion"];
            $id=$valorPrincipal["id"];
        }
        

        if(empty($id) || $estadoCalificacion==="observado"){
            return 1;
        }else{
            return 0;
        }

    } 


    public function eliminarAnexo__infra__adicional($post) {


        $consultaPrincipal=$this->constructor->select__general__incentivo("SELECT archivo FROM proyecto_documentos_infraestructura_anexos WHERE id='".$post["id"]."';");

        foreach ($consultaPrincipal as $valorPrincipal) {
            $archivo=$valorPrincipal["archivo"];
        }
        
        unlink($this->ruta."documentosInfraestructura/".$archivo);

        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_documentos_infraestructura_anexos WHERE id='".$post["id"]."';");
        return 1;

    } 

    public function documentosAnexosAdicionales__id($post) {

        $array=array();

        $consultaPrincipal=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_infraestructura_anexos WHERE codigo='".$post["codigo"]."' AND archivo IS NOT NULL ORDER BY id ASC;");

        foreach ($consultaPrincipal as $valorPrincipal) {
            array_push($array,$valorPrincipal["id"]);
        }
        
       
        return $array;

    } 

    public function documentosAnexosAdicionales__nombres($post) {

        $array=array();

        $consultaPrincipal=$this->constructor->select__general__incentivo("SELECT nombreAnexo FROM proyecto_documentos_infraestructura_anexos WHERE codigo='".$post["codigo"]."' AND archivo IS NOT NULL ORDER BY id ASC;");

        foreach ($consultaPrincipal as $valorPrincipal) {
            array_push($array,$valorPrincipal["nombreAnexo"]);
        }
        
       
        return $array;

    } 

    public function documentosAnexosAdicionales__unitario($post) {

        return $this->constructor->select__archivo__incentivo("SELECT archivo FROM proyecto_documentos_infraestructura_anexos WHERE id='".$post["id"]."' AND archivo IS NOT NULL ORDER BY id ASC;",$this->ruta."documentosInfraestructura/","archivo");

    } 


    public function documentosAnexosAdicionales($post) {

        $array=array();

        $consultaPrincipal=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_infraestructura_anexos WHERE codigo='".$post["codigo"]."' AND archivo IS NOT NULL ORDER BY id ASC;");

        foreach ($consultaPrincipal as $valorPrincipal) {

            array_push($array,$valorPrincipal["id"]);

        }
        
       
        return $array;


    } 

    public function obtener__id__enviado__proyecto($post) {

        $codigo=$post["codigo"];
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigo';");

    }


    public function tipoUsuario() {
         return $this->constructor->select__general__incentivo("SELECT idTipoUsuario,nombre,descripcion FROM incentivo.tipo_usuario;");
    }

    public function tipoUsuarioFederado($idTipo) {

        return $this->constructor->select__general("SELECT b.idSubTipo,b.nombre,b.descripcion FROM incentivo.tipo_usuario AS a INNER JOIN incentivo.sub_tipo_usuario AS b ON a.idTipoUsuario=b.idTipoUsuario WHERE a.idTipoUsuario='$idTipo';");

    }


    public function tipoUsuarioFederado__global() {

        return $this->constructor->select__general__incentivo("SELECT b.idSubTipo,b.nombre,b.descripcion FROM incentivo.tipo_usuario AS a INNER JOIN incentivo.sub_tipo_usuario AS b ON a.idTipoUsuario=b.idTipoUsuario;");

    }



    public function dinardap($cedula) {

        return $this->constructor__dinardap->dinardap($cedula);

    }

    public function comparar__comprobante__ruc__cedula($cedula,$idCredencial) {

        $bandera=false;

        $consulta=$this->constructor->select__general("SELECT idUsuario AS id FROM usuario WHERE cedula LIKE '%$cedula%' AND idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
            $id_usuario=$valor["id"];
        }

        $consulta=$this->constructor->select__general("SELECT idOrganismo AS id FROM organismo WHERE ruc LIKE '%$cedula%' AND idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
            $id_ruc=$valor["id"];
        }

        $consulta=$this->constructor->select__general("SELECT idRepresentante AS id FROM representante WHERE cedula='%$cedula%' AND idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
            $id_representante=$valor["id"];
        }

        if(!empty($id_usuario) || !empty($id_ruc) || !empty($id_representante)){

            $bandera=true;

        }

        return $bandera;

    }

    public function dinardap__ruc__certificacion__emisor($ruc,$idCredencial) {

        return $this->constructor__dinardap->dinardap__ruc__certificacion($ruc);

    }


    public function dinardap__ruc__certificacion($ruc,$idCredencial) {

        $comparador=$this->comparar__comprobante__ruc__cedula($ruc,$idCredencial);

        if ($comparador===true) {
           return 0;
        }else{
            return $this->constructor__dinardap->dinardap__ruc__certificacion($ruc);
        }

    }


    public function dinardap__ruc($ruc) {

        return $this->constructor__dinardap->dinardap__ruc($ruc);

    }

    public function genero() {

        return $this->constructor->select__general__incentivo("SELECT idGenero,nombre FROM incentivo.genero WHERE estado='A';");
        
    }

    public function orientacion__sexual() {

        return $this->constructor->select__general__incentivo("SELECT id,nombre FROM incentivo.genero_orientacion WHERE estado='A';");
        
    }

    public function paises() {

        return $this->constructor->select__general("SELECT id,nombre FROM paises WHERE estado='A';");
        
    }

    public function provincia() {

        return $this->constructor->select__general("SELECT idProvincia,nombre FROM provincia ORDER BY nombre ASC;");
        
    }

    public function canton($idProvincia) {

        return $this->constructor->select__general("SELECT depCanton,nombre FROM canton WHERE idProvincia='$idProvincia' ORDER BY nombre ASC;");
        
    }

    public function canton__sin() {

        return $this->constructor->select__general("SELECT depCanton,nombre FROM canton ORDER BY nombre ASC;");
        
    }

    public function parroquia($idCanton) {

        return $this->constructor->select__general("SELECT depParroquia,nombre FROM parroquia WHERE depCanton='$idCanton' ORDER BY nombre ASC;");
        
    }

    public function parroquia__sin() {

        return $this->constructor->select__general("SELECT depParroquia,nombre FROM parroquia ORDER BY nombre ASC;");
        
    }


    public function organismos() {

        return $this->constructor->select__general("SELECT a.idOrganismo,a.ruc,UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombreOrganismo, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreOrganismo FROM ezonshar_mdepsaddb.poa_organismo AS a INNER JOIN ezonshar_mdepsaddb.poa_competencia_organismo_competencia AS b ON b.IdOrganismo=a.idOrganismo WHERE (b.idTipoOrganismo='61' OR b.idTipoOrganismo='78' OR b.idTipoOrganismo='79' OR b.idTipoOrganismo='80' OR b.idTipoOrganismo='81' OR b.idTipoOrganismo='82' OR b.idTipoOrganismo='83' OR b.idTipoOrganismo='84' OR b.idTipoOrganismo='85' OR b.idTipoOrganismo='86' OR b.idTipoOrganismo='87' OR b.idTipoOrganismo='88' OR b.idTipoOrganismo='89' OR b.idTipoOrganismo='90') AND a.profesional IS NULL AND a.correoResponsablePoa!='duribe@deporte.gob.ec' AND a.correoResponsablePoa!='afernandez@deporte.gob.ec' AND a.nombreOrganismo NOT LIKE '%PRUEBA%' AND a.ruc!='' AND a.ruc IS NOT NULL AND a.correoResponsablePoa NOT LIKE '%aplicativopoa@gmail.com%' AND a.correoResponsablePoa NOT LIKE '%@deporte.gob.ec%' AND a.correoResponsablePoa NOT LIKE '%berecarrera@hotmail.com%' AND a.cedulaResponsable NOT LIKE '%1208012177%'  ORDER BY a.nombreOrganismo ASC;");
        
    }


    public function codigo($codigo) {

       return $this->constructor->select__general("SELECT idCredencial FROM codigo WHERE codigo='$codigo' AND estado='A';");
        
    }

    public function credencialUsuarioOb($idCredencial) {

        $obtenerCredencial=$this->constructor->select__general("SELECT usuario FROM credencial WHERE idCredencial='$idCredencial' AND estado='A';");
        foreach ($obtenerCredencial as $valor) {
            $usuarioBd=$valor["usuario"];
        }

        return $usuarioBd;

    }


    public function proyectoExistenteEliminarBase($campo,$tabla,$codigo,$idCredencial){


        $obtenerResultado=$this->constructor->select__general__incentivo("SELECT $campo AS obtenido FROM $tabla WHERE codigo='$codigo' AND idCredencial='$idCredencial';");
        foreach ($obtenerResultado as $valor) {
            $resultadoBd=$valor["obtenido"];
        }

        return $resultadoBd;

        return "SELECT $campo AS obtenido FROM $tabla WHERE codigo='$codigo' AND idCredencial='$idCredencial';";

    }

    public function obtenerProyecto($codigo){


        $obtenerResultado=$this->constructor->select__general__incentivo("SELECT idProyecto FROM proyecto WHERE codigo='$codigo';");
        foreach ($obtenerResultado as $valor) {
            $codigoBd=$valor["idProyecto"];
        }

        return $codigoBd;

    }

    public function obtenerHabilitacionProyecto($idCredencial){


        $obtenerResultado=$this->constructor->select__general__incentivo("SELECT idProyecto FROM proyecto WHERE idCredencial='$idCredencial' AND estado!='E';");
        foreach ($obtenerResultado as $valor) {
            $resultadoBd=$valor["idProyecto"];
        }

        return $resultadoBd;

    }

    public function obtenerProyectoCodigo($idCredencial){


        $obtenerResultado=$this->constructor->select__general__incentivo("SELECT codigo FROM proyecto WHERE idCredencial='$idCredencial';");
        foreach ($obtenerResultado as $valor) {
            $codigoBd=$valor["codigo"];
        }

        return $codigoBd;

    }


    public function insertaSector($post) {

        $codigo=$post["codigo"];
        $idCredencial=$post["idCredencial"];
        $idSectorArray=json_decode($post["idSectorArray"], true);
        $ocultado=$post["ocultado"];

        if(!empty($codigo)){

            if (!empty(self::proyectoExistenteEliminarBase("id","proyecto_sector",$codigo,$idCredencial))) {

                 $consultaSector=$this->constructor->select__general__incentivo("SELECT id,idSector,codigo,idCredencial FROM proyecto_sector WHERE codigo='$codigo' AND idCredencial='$idCredencial';");

                 foreach ($consultaSector as $valor) {

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_sector",['idSector','codigo','idCredencial','fecha','hora'],array(':idSector' => $valor["idSector"],':codigo' =>  $valor["codigo"],':idCredencial' =>  $valor["idCredencial"],':fecha' => $this->fecha,':hora' => $this->hora));

                    $valorE=$valor["id"];

                    $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_sector WHERE id='$valorE';");

                 }

            }
            
            if ($ocultado==="true"){
            
                foreach ($idSectorArray as $valor) {
                    $this->constructor->inserta__general__incentivo("proyecto_sector",['idSector','codigo','idCredencial','fecha','hora'],array(':idSector' => $valor,':codigo' =>  $codigo,':idCredencial' =>  $idCredencial,':fecha' => $this->fecha,':hora' => $this->hora));
                }

            }

             $estadoAc=$this->constructor->select__general__incentivo("SELECT estado FROM proyecto WHERE codigo='$codigo';");
             foreach ($estadoAc as $valor) {
                if (intval($valor["estado"])===1) {
                    $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='8' WHERE codigo='$codigo';");
                }
             }

             /*========================================================
             =            Ingresar componentes priorizados            =
             ========================================================*/
             
             $sectorProyectoPriorizado=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE idSector='3' AND codigo='$codigo';");
             foreach ($sectorProyectoPriorizado as $valor) {
                $idBdPriorizados=$valor["id"];
             }

            $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE (sector='priorizado' OR sector='femenino') AND codigo='$codigo';");

             if (!empty($idBdPriorizados)) {
                
                $consultaRespaldo__descripcion=$this->constructor->select__general__incentivo("SELECT fechaInicio,diferenciaAnios FROM proyecto_descripcion WHERE codigo='$codigo';");


                foreach ($consultaRespaldo__descripcion as $valor) {
                    $fechaInicioBd=$valor["fechaInicio"];
                    $diferenciaAnios=$valor["diferenciaAnios"];
                }
            
                $arrayAnios=array();
                $array__fecha = explode('-', $fechaInicioBd);

                $sumadorAnios=0;

                for ($i=0; $i <= intval($diferenciaAnios); $i++) { 
                    if ($i==0) {
                        array_push($arrayAnios, intval($array__fecha[0]));
                    }else{
                        $sumadorAnios=intval($array__fecha[0])+$i;
                        array_push($arrayAnios,$sumadorAnios);
                    }

                }



                $codigoFinal=$codigo;

                $arrayIdComponentes=array();
                $consultaRespaldo__componentes=$this->constructor->select__general__incentivo("SELECT idComponentes FROM proyecto_componente_usuario WHERE codigo='$codigo';");

                foreach ($consultaRespaldo__componentes as $valor) {
                    array_push($arrayIdComponentes,$valor["idComponentes"]);
                }


                for($i=0; $i<count($arrayAnios);$i++){

                    $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

                    foreach ($componetesV as $valor) {


                        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                            ':idComponentes' => $valor["idComponentes"],
                            ':idNivel1' => $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'priorizado'
                        ));


                        $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado');


                    }


                    if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                            ':idComponentes' => 6,
                            ':idNivel1' =>  $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'priorizado'
                        ));


                        $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado');

                    }


                }

                for($i=0; $i<count($arrayAnios);$i++){

                    $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

                    foreach ($componetesV as $valor) {


                        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                            ':idComponentes' => $valor["idComponentes"],
                            ':idNivel1' => $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'femenino'
                        ));


                        $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino');


                    }


                    if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                            ':idComponentes' => 6,
                            ':idNivel1' =>  $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'femenino'
                        ));


                        $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino');

                    }


                }


             }     


            $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE (sector='priorizado' OR sector='femenino') AND codigo='$codigoFinal';");    
             
             /*=====  End of Ingresar componentes priorizados  ======*/

             

            return 1;

        }

    }

    public function insertarDescripcionProyecto($post) {
   
        // credencial tipo
        $idCredencial=$post["idCredencial"];
        $nombreProyecto=$post["nombreProyecto"];
        $fechaInicio=$post["fechaInicio"];
        $fechaFin=$post["fechaFin"];
        $mensajePluri=$post["mensajePluri"];
        $objetivoGeneral=$post["objetivoGeneral"];
        $diferenciaAnios=$post["diferenciaAnios"];
        $justificacionProyecto=$post["justificacionProyecto"];

        $arrayIdComponentes=json_decode($post["arrayIdComponentes"], true);
        $arrayQue=json_decode($post["arrayQue"], true);
        $arraycomoO=json_decode($post["arraycomoO"], true);
        $arrayparaO=json_decode($post["arrayparaO"], true);
        $arrayqueRespaldo=json_decode($post["arrayqueRespaldo"], true);
        $arraycomoRespaldo=json_decode($post["arraycomoRespaldo"], true);
        $arrayparaRespaldo=json_decode($post["arrayparaRespaldo"], true);
        $selectedIdsArray=json_decode($post["selectedIdsArray"], true);


        if(!empty($justificacionProyecto)){

            $banderaMediante=false;

            foreach ($arrayparaO as $valor) {
                if ($valor==="mediante,  ") {
                  $banderaMediante=true;
                }
            }

            if ($banderaMediante===true) {

                return 2;
                
            }else{

                /*=========================================
                =            Sección inserción            =
                =========================================*/
                

                if (empty(self::obtenerHabilitacionProyecto($idCredencial))) {


                    $usuarioBd=self::credencialUsuarioOb($idCredencial);
                    $incrementalC=$this->constructor->select__general__incentivo("SELECT COUNT(idProyecto) AS contador FROM proyecto WHERE idCredencial='$idCredencial';");


                    foreach ($incrementalC as $valor) {
                        $incrementalB=$valor["contador"];
                    }


                    if (empty($incrementalB)) {
                       $incrementalB=1;
                    }else{
                        $incrementalB= intval($incrementalB) + 1;
                    }


                    $codigoFinal=$incrementalB."-".$usuarioBd."-".$this->anio;


                    $this->constructor->inserta__general__incentivo("proyecto",['codigo','idCredencial','estado','fecha','hora'],array(':codigo' => $codigoFinal,':idCredencial' => $idCredencial,':estado' => 1,':fecha' => $this->fecha,':hora' => $this->hora));


                }else{

                    $codigoFinal=self::obtenerProyectoCodigo($idCredencial);

                }

                /*========================================
                =            Sección respaldo            =
                ========================================*/
                
                $descripcionExiste=self::proyectoExistenteEliminarBase("idDescripcion","proyecto_descripcion",$codigoFinal,$idCredencial);

                if (!empty($descripcionExiste)) {
                    
                    $consultaRespaldo__descripcion=$this->constructor->select__general__incentivo("SELECT idDescripcion,nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,idCredencial,idProyecto,codigo,justificacionProyecto FROM proyecto_descripcion WHERE codigo='$codigoFinal' AND idCredencial='$idCredencial';");

                    foreach ($consultaRespaldo__descripcion as $valor) {

                        $idDescripcionBd=$valor["idDescripcion"];
                        $nombreBd=$valor["nombre"];
                        $fechaInicioBd=$valor["fechaInicio"];
                        $fechaFinBd=$valor["fechaFin"];
                        $tipoBd=$valor["tipo"];
                        $diferenciaAniosBd=$valor["diferenciaAnios"];
                        $objetivoGeneralBd=$valor["objetivoGeneral"];
                        $idCredencialBd=$valor["idCredencial"];
                        $idProyectoBd=$valor["idProyecto"];
                        $codigoBd=$valor["codigo"];
                        $justificacionProyectoBd=$valor["justificacionProyecto"];

                    }

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_descripcion",['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral','idCredencial','idProyecto','fecha','hora','codigo','justificacionProyecto'],array(':nombre' => $nombreBd,':fechaInicio' => $fechaInicioBd,':fechaFin' => $fechaFinBd,':tipo' => $tipoBd,':diferenciaAnios' => $diferenciaAniosBd,':objetivoGeneral' => $objetivoGeneralBd,':idCredencial' => $idCredencialBd,':idProyecto' => $idProyectoBd,':fecha' => $this->fecha,':hora' => $this->hora,':codigo' => $codigoBd,':justificacionProyecto' => $justificacionProyectoBd));



                    $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_descripcion WHERE idDescripcion='$idDescripcionBd';");

                }
                
                /*=====  End of Sección respaldo  ======*/


                $idProyectoBd=self::obtenerProyecto($codigoFinal);


                $this->constructor->inserta__general__incentivo("proyecto_descripcion",['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral','idCredencial','idProyecto','fecha','hora','codigo','justificacionProyecto'],array(':nombre' => $nombreProyecto,':fechaInicio' => $fechaInicio,':fechaFin' => $fechaFin,':tipo' => $mensajePluri,':diferenciaAnios' => $diferenciaAnios,':objetivoGeneral' => $objetivoGeneral,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoBd,':fecha' => $this->fecha,':hora' => $this->hora,':codigo' =>$codigoFinal,':justificacionProyecto' =>$justificacionProyecto));
                
                /*=====  End of Sección inserción  ======*/
                

                /*========================================
                =            Sección respaldo            =
                ========================================*/
                
                 $componenteExiste=self::proyectoExistenteEliminarBase("idComponentes","proyecto_componente_usuario",$codigoFinal,$idCredencial);

                 if (!empty($componenteExiste)) {

                    $consultaRespaldo_componente=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes,que,como,paraQue,idCredencial,idProyecto,codigo FROM proyecto_componente_usuario WHERE idCredencial='$idCredencial' AND codigo='$codigoFinal';");

                    foreach ($consultaRespaldo_componente as $valor) {


                        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_componente_usuario",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo'],array(':idComponentes' => $valor["idComponentes"],':que' => $valor["que"],':como' => $valor["como"],':paraQue' =>$valor["paraQue"],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $valor["idCredencial"],':idProyecto' => $valor["idProyecto"],':codigo' => $valor["codigo"]));


                        $idComponentesProyectoBd=$valor["idComponentesProyecto"];


                         $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_componente_usuario WHERE idComponentesProyecto='$idComponentesProyectoBd';");



                    }

                }

                
                /*=====  End of Sección respaldo  ======*/

                /*==========================================
                =            Sección respaldo 2            =
                ==========================================*/
                

                 $componenteRespaldoExiste=self::proyectoExistenteEliminarBase("idComponentes","proyecto_componente_usuario_2",$codigoFinal,$idCredencial);

                 if (!empty($componenteRespaldoExiste)) {

                    $consultaRespaldo_componente=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes,que,como,paraQue,idCredencial,idProyecto,codigo FROM proyecto_componente_usuario_2 WHERE idCredencial='$idCredencial' AND codigo='$codigoFinal';");

                    foreach ($consultaRespaldo_componente as $valor) {


                        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_componente_usuario_2",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo'],array(':idComponentes' => $valor["idComponentes"],':que' => $valor["que"],':como' => $valor["como"],':paraQue' =>$valor["paraQue"],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $valor["idCredencial"],':idProyecto' => $valor["idProyecto"],':codigo' => $valor["codigo"]));


                        $idComponentesProyectoBd=$valor["idComponentesProyecto"];

                        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_componente_usuario_2 WHERE idComponentesProyecto='$idComponentesProyectoBd';");


                    }

                }       
                
                
                /*=====  End of Sección respaldo 2  ======*/
                
                


                /*===================================
                =            Componentes            =
                ===================================*/
                

                foreach ($arrayIdComponentes as $clave => $valor) {
                    
                    $this->constructor->inserta__general__incentivo("proyecto_componente_usuario",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo'],array(':idComponentes' => $valor,':que' => $arrayQue[$clave],':como' => $arraycomoO[$clave],':paraQue' => $arrayparaO[$clave],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoBd,':codigo' => $codigoFinal));

                }


                foreach ($selectedIdsArray as $clave => $valor) {
                    $this->constructor->inserta__general__incentivo("proyecto_componente_usuario_2",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo'],array(':idComponentes' => $valor,':que' => $arrayqueRespaldo[$clave],':como' => $arraycomoRespaldo[$clave],':paraQue' => $arrayparaRespaldo[$clave],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoBd,':codigo' => $codigoFinal));
                }


                /*=====  End of Componentes  ======*/

                
                $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio FROM proyecto_descripcion WHERE codigo='$codigoFinal' AND idCredencial='$idCredencial';");

                foreach ($consultaRespaldo__descripcion__dos as $valor) {
                    $fechaInicioBd__dos=$valor["fechaInicio"];
                }


                $arrayAnios=array();
                $array__fecha = explode('-', $fechaInicioBd__dos);

                $sumadorAnios=0;

                for ($i=0; $i <= intval($diferenciaAnios); $i++) { 

                    if ($i==0) {
                        
                        array_push($arrayAnios, intval($array__fecha[0]));

                    }else{

                         $sumadorAnios=intval($array__fecha[0])+$i;

                         array_push($arrayAnios,$sumadorAnios);

                    }

                }



                

                /*===========================================
                =            Generar componentes            =
                ===========================================*/


               $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");


               for($i=0; $i<count($arrayAnios);$i++){

                    $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigoFinal' AND b.estado='A';");

                    foreach ($componetesV as $valor) {


                        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                            ':idComponentes' => $valor["idComponentes"],
                            ':idNivel1' => $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'componente'
                        ));


                        $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial);


                    }


                    if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                            ':idComponentes' => 6,
                            ':idNivel1' =>  $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'componente'
                        ));


                        $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial);

                    }


                }

                /*=====  End of Generar componentes  ======*/

                $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


                $estadoProyectos = $this->constructor->select__general__incentivo("SELECT estado FROM proyecto WHERE codigo='$codigoFinal';");

                foreach ($estadoProyectos as $valor) {
                    $estadoBdP=$valor["estado"];
                }


                $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='1' WHERE codigo='$codigoFinal';");


                /*===================================================
                =            Insertar resultados y metas            =
                ===================================================*/

                 $this->constructor->actualiza__general__incentivo("DELETE FROM  proyecto_resultados_metas WHERE codigo='$codigoFinal';");

                $objetivosEspecificos = $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',que,como,paraQue) AS objetivoEspecifico,idComponentes FROM proyecto_componente_usuario WHERE codigo='$codigoFinal';");


                foreach ($objetivosEspecificos as $valor) {

                    $this->constructor->inserta__general__incentivo("proyecto_resultados_metas", ['objetivoEspecifico','fecha','hora','idComponentes','codigo'], array(
                        ':objetivoEspecifico' => $valor["objetivoEspecifico"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':idComponentes' => $idComponentes,
                        ':codigo' => $codigoFinal,
                    ));

                }
                
                $objetivosEspecificos__respaldos = $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',que,como,paraQue) AS objetivoEspecifico,idComponentes FROM proyecto_componente_usuario_2 WHERE codigo='$codigoFinal';");


                foreach ($objetivosEspecificos__respaldos as $valor) {

                    $this->constructor->inserta__general__incentivo("proyecto_resultados_metas", ['objetivoEspecifico','codigo','fecha','hora','idComponentes'], array(
                        ':objetivoEspecifico' => $valor["objetivoEspecifico"],
                        ':codigo' => $codigoFinal,
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':idComponentes' => $idComponentes,
                    ));

                }
                
                /*=====  End of Insertar resultados y metas  ======*/
                

                return 1;


            }

        }
        
    }

    public function insertarNivel__1__sin($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente',$orden) {

        $array=array();

        $buscar__niveles=$this->constructor->select__general__incentivo("SELECT idNivel1 FROM proyecto_presupuesto WHERE idComponentes='$idComponentes' AND sector='$sector' AND total>0 AND idNivel1 IS NOT NULL AND idComponentes iS NOT NULL AND anio='$anio' AND codigo='$codigoFinal' GROUP BY idNivel1,idComponentes;");

        foreach ($buscar__niveles as $valor) {
            array_push($array, $valor["idNivel1"]);
        }

        $comparacion = implode(',', $array);

        foreach ($array as $valor) {
        
            $nivel__1 = $this->constructor->select__general__incentivo("SELECT a.idNivel1,b.id FROM componentes_nivel AS a INNER JOIN proyecto_presupuesto AS b ON a.idNivel1=b.idNivel1 WHERE a.idComponentes = '$idComponentes' AND a.idNivel1='$valor' AND a.estado='A' AND b.total>0 AND b.sector='$sector' AND a.idNivel1 IS NOT NULL AND a.idComponentes IS NOT NULL AND b.anio='$anio' AND b.codigo='$codigoFinal' GROUP BY a.idNivel1,a.idComponentes;");

            foreach ($nivel__1 as $valorNivel__1) {

                $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden'], array(
                    ':idComponentes' => $idComponentes,
                    ':idNivel1' => $valorNivel__1["idNivel1"],
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                    ':anio' => $anio,
                    ':codigo' => $codigoFinal,
                    ':idCredencial' => $idCredencial,
                    ':nivel' => 1,
                    ':sector' => $sector,
                    ':orden' => $orden
                ));

            }

        }




        return 1;

    }

    public function insertarNivel__1($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente') {

        $nivel__1 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND estado='A';");

        foreach ($nivel__1 as $valorNivel__1) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__1["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 1,
                ':sector' => $sector
            ));

            $this->insertarNivel__2($idComponentes, 2,$valorNivel__1["idNivel1"],$anio,$codigoFinal,$idCredencial,$sector);
        }

    }

    public function insertarNivel__2($idComponentes, $nivel,$idNivel1,$anio,$codigoFinal,$idCredencial,$sector) {

        $nivel__2 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND idNivelRelacion='$idNivel1' AND estado='A';");

        foreach ($nivel__2 as $valorNivel__2) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__2["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 2,
                ':sector' => $sector
            ));

            $this->insertarNivel__3($idComponentes, 3,$valorNivel__2["idNivel1"],$anio,$codigoFinal,$idCredencial,$sector);


        }

        return 1;
    }

    public function insertarNivel__3($idComponentes, $nivel,$idNivel1,$anio,$codigoFinal,$idCredencial,$sector) {

        $nivel__2 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND idNivelRelacion='$idNivel1' AND estado='A';");

        foreach ($nivel__2 as $valorNivel__2) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__2["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 3,
                ':sector' => $sector
            ));

        }

        return 1;
    }



    public function insertarUsuario($post) {

        // credencial tipo
        $tipoUsuario=$post["tipoUsuario"];

        //usuario
        $cedula=$post["cedula"];
        $correo1=$post["correo1"];
        $correo2=$post["correo2"];
        $nombre=$post["nombre"];

        // organismo
        $ruc=$post["ruc"];
        $correo1Organismo=$post["correo1Organismo"];
        $correo2Organismo=$post["correo2Organismo"];
        $razonSocial=$post["razonSocial"];

        if(!empty($tipoUsuario)){

             // contacto

            if (intval($tipoUsuario)==1 || intval($tipoUsuario)==2) {
                $usuario=$cedula;
                $emailD1=$correo1;
                $emailD2=$correo2;
                $nombreCompleto=$nombre;
            }else{
                $usuario=$ruc;
                $emailD1=$correo1Organismo;
                $emailD2=$correo2Organismo;
                $nombreCompleto=$razonSocial;
            }

            $credencialIgual=$this->constructor->select__general("SELECT idCredencial FROM credencial WHERE usuario='$usuario';");

            foreach ($credencialIgual as $valor) {
                $idCredencialBd=$valor["idCredencial"];
            }

            if (empty($idCredencialBd)) {


                $this->constructor->inserta__general("credencial",['usuario','fecha','hora','estado','estadoValidacion'],array(':usuario' => $usuario,':fecha' => $this->fecha,':hora' => $this->hora,':estado' => 'I',':estadoValidacion' => 1));
                $maximo=$this->constructor->select__general("SELECT MAX(idCredencial) AS maximo FROM credencial;");

                foreach ($maximo as $valor) {
                   $idMaximo=$valor["maximo"];
                }

                $codigo=$this->constructor->generarCodigo(5);

                $this->constructor->inserta__general("codigo",['codigo','estado','idCredencial','fecha','hora'],array(':codigo' => $codigo,':estado' => 'A',':idCredencial' => $idMaximo,':fecha' => $this->fecha,':hora' => $this->hora));


                $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Estimado/a usuario/a,</div><br><div style="font-size:10px;">'.$nombreCompleto.'</div><br><div style="font-size:10px;">Se ha creado un usuario en la plataforma de incentivo tributario del Ministerio del Deporte con credenciales:</div><br><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Usuario:</span>&nbsp;'.$usuario.'</div><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Código de validación:</span>&nbsp;'.$codigo.'</div><br><div style="font-size:10px;">El código tendrá una duración de 5 minutos, pasado este tiempo deberá generar un nuevo código.</div><br><div style="font-weight:bold; font-size:10px;">Atentamente, </div><br><br><div style="font-weight:bold; font-size:10px;">Ministerio del Deporte</div><br><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Dirección: </span><span style="font-size:10px;">Av.Gaspar de Villarroel E10-122 y 6 de Diciembre</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Código postal: </span><span style="font-size:10px;">170501 / Quito - Ecuador</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Teléfono: </span><span style="font-size:10px;">+593-23969200</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">www.deporte.gob.ec</span></div></body></html>';

                $this->constructor->enviarCorreo([$emailD1],$bodyMensaje);

                return 1;

            }else{

                return 2;

            }

        }


    }


    public function codigo__registro__final__usuario($codigo,$password,$post) {

        /*==========================================
        =            Sección de ingreso            =
        ==========================================*/
        
                // credencial tipo
        $tipoUsuario=$post["tipoUsuario"];
        $tipoUsuario__perteneciente=$post["tipoUsuario__perteneciente"];
        $organismoDeportivo=$post["organismoDeportivo"];
        

        // usuario pérfil        
        $aplicativo=$post["aplicativo"];

        // usuario
        $cedula=$post["cedula"];
        $nombre=$post["nombre"];
        $fechaNacimiento=$post["fechaNacimiento"];
        $sexo=$post["sexo"];
        $genero=$post["genero"];
        $orientacionSexual=$post["orientacionSexual"];
        $estadoCivil=$post["estadoCivil"];
        $nacionalidad=$post["nacionalidad"];
        $discapacidad=$post["discapacidad"];
        $fechaNacimientoG = date('Y-m-d', strtotime(str_replace('/', '-', $fechaNacimiento)));

        // organismo
        $ruc=$post["ruc"];
        $razonSocial=$post["razonSocial"];

        // contacto
        $celular1=$post["celular1"];
        $celular2=$post["celular2"];
        $correo1=$post["correo1"];
        $correo2=$post["correo2"];
        $correo1Organismo=$post["correo1Organismo"];
        $correo2Organismo=$post["correo2Organismo"];
        $provincia=$post["provincia"];
        $canton=$post["canton"];
        $parroquia=$post["parroquia"];
        $callePrincipal=$post["callePrincipal"];
        $calleSecundaria=$post["calleSecundaria"];
        $numeracion=$post["numeracion"];

        // representante
        $cedulaRepresentanteLegal=$post["cedulaRepresentanteLegal"];
        $nombreRepresentanteLegal=$post["nombreRepresentanteLegal"];
        $sexoRepresentanteLegal=$post["sexoRepresentanteLegal"];
        $celular1RepresentanteLegal=$post["celular1RepresentanteLegal"];
        $celular2RepresentanteLegal=$post["celular2RepresentanteLegal"];
        $correo1RepresentanteLegal=$post["correo1RepresentanteLegal"];
        $correo2RepresentanteLegal=$post["correo2RepresentanteLegal"];

        if (intval($tipoUsuario)==1 || intval($tipoUsuario)==2) {
            $usuario=$cedula;
            $emailD1=$correo1;
            $emailD2=$correo2;
        }else{
            $usuario=$ruc;
            $emailD1=$correo1Organismo;
            $emailD2=$correo2Organismo;
        }

        $credencialIgual=$this->constructor->select__general("SELECT idCredencial FROM credencial WHERE usuario='$usuario';");

        foreach ($credencialIgual as $valor) {
            $idMaximo=$valor["idCredencial"];
        }
        
        if (intval($tipoUsuario)==1 || intval($tipoUsuario)==2) {
            $this->constructor->inserta__general("usuario",['cedula','nombre','fechaNacimiento','sexo','genero','estadoCivil','nacionalidad','discapacidad','idCredencial','fecha','hora','orientacionSexual'],array(':cedula' => $cedula,':nombre' => $nombre,':fechaNacimiento' => $fechaNacimientoG,':sexo' => $sexo,':genero' => $genero,':estadoCivil' => $estadoCivil,':nacionalidad' => $nacionalidad,':discapacidad' => $discapacidad,':idCredencial' => $idMaximo,':fecha' => $this->fecha,':hora' => $this->hora,':orientacionSexual' => $orientacionSexual));
            $nombreUsuario=$nombre;
            $claveUsuario=$cedula;
        }else{
            $this->constructor->inserta__general("organismo",['ruc','razonSocial','idCredencial','fecha','hora'],array(':ruc' => $ruc,':razonSocial' => $razonSocial,':idCredencial' => $idMaximo,':fecha' => $this->fecha,':hora' => $this->hora));
            $nombreUsuario=$razonSocial;
            $claveUsuario=$ruc;
        }


        $this->constructor->inserta__general("contacto",['celular1','celular2','email1','email2','idProvincia','depCanton','depParroquia','callePrincipal','calleSecundaria','numeracion','idCredencial','fecha','hora'],array(':celular1' => $celular1,':celular2' => $celular2,':email1' => $emailD1,':email2' => $emailD2,':idProvincia' => $provincia,':depCanton' => $canton,':depParroquia' => $parroquia,':callePrincipal' => $callePrincipal,':calleSecundaria' => $calleSecundaria,':numeracion' => $numeracion,':idCredencial' => $idMaximo,':fecha' => $this->fecha,':hora' => $this->hora));

        if ((intval($post["necesitaRepresentanteLegal"])===1 && $discapacidad==1) || intval($post["edad"])<18 || intval($tipoUsuario)==3 || intval($tipoUsuario)==4) {
                
            $this->constructor->inserta__general("representante",['cedula','nombre','sexo','celular1','celular2','correo1','correo2','idCredencial','fecha','hora'],array(':cedula' => $cedulaRepresentanteLegal,':nombre' => $nombreRepresentanteLegal,':sexo' => $sexoRepresentanteLegal,':celular1' => $celular1RepresentanteLegal,':celular2' => $celular2RepresentanteLegal,':correo1' => $correo1RepresentanteLegal,':correo2' => $correo2RepresentanteLegal,':idCredencial' => $idMaximo,':fecha' => $this->fecha,':hora' => $this->hora));

        }


        $aplicativoNombre=$this->constructor->select__general("SELECT idAplicativo FROM aplicativo WHERE nombreAplicativo='$aplicativo';");

        foreach ($aplicativoNombre as $valor) {
            $idAplicativoBd=$valor["idAplicativo"];
        }


        $this->constructor->inserta__general("credencial_tipo",['idCredencial','idTipoUsuario','idSubTipo','idOrganismo','fecha','hora'],array(':idCredencial' => $idMaximo,':idTipoUsuario' => $tipoUsuario,':idSubTipo' => $tipoUsuario__perteneciente,':idOrganismo' => $organismoDeportivo,':fecha' => $this->fecha,':hora' => $this->hora));

        $this->constructor->inserta__general("usuarioperfil",['idCredencial','idPerfil','idAplicativo','fecha','hora','idRol'],array(':idCredencial' => $idMaximo,':idPerfil' => 4,':idAplicativo' => 1,':fecha' => $this->fecha,':hora' => $this->hora,':idRol' => 10));



        /*=====  End of Sección de ingreso  ======*/
        

       $codigoObtenido=$this->constructor->select__general("SELECT idCredencial FROM codigo WHERE codigo='$codigo' AND estado='A';");

       foreach ($codigoObtenido as $valor) {
            $idCredencialBd=$valor["idCredencial"];
       }

       $encriptado = md5($password);

       $this->constructor->actualiza__general("UPDATE credencial SET contrasena='$encriptado', estado='A', estadoValidacion='2' WHERE idCredencial='$idCredencialBd';");

       $this->constructor->actualiza__general("UPDATE codigo SET estado='I' WHERE idCredencial='$idCredencialBd';");

       return 1;
        
    }    




    public function eliminar__usuario__registro($usuario) {

       $codigoObtenido=$this->constructor->select__general("SELECT idCredencial,usuario,contrasena FROM credencial WHERE usuario='$usuario';");

       foreach ($codigoObtenido as $valor) {
            $idCredencialBd=$valor["idCredencial"];
            $usuarioBd=$valor["usuario"];
            $contrasenaBd=$valor["contrasena"];
       }

        $this->constructor->inserta__general("credencial_eliminados",['usuario','fecha','hora'],array(':usuario' => $usuarioBd,':fecha' => $this->fecha,':hora' => $this->hora));


       $this->constructor->actualiza__general("DELETE FROM codigo WHERE idCredencial='$idCredencialBd';");
       $this->constructor->actualiza__general("DELETE FROM credencial WHERE idCredencial='$idCredencialBd';");


       return 1;
        
    }    


    public function codigo__registro($codigo,$password) {

       $codigoObtenido=$this->constructor->select__general("SELECT idCredencial FROM codigo WHERE codigo='$codigo' AND estado='A';");

       foreach ($codigoObtenido as $valor) {
            $idCredencialBd=$valor["idCredencial"];
       }

       $encriptado = md5($password);

       $this->constructor->actualiza__general("UPDATE credencial SET contrasena='$encriptado', estado='A', estadoValidacion='2' WHERE idCredencial='$idCredencialBd';");

       $this->constructor->actualiza__general("UPDATE codigo SET estado='I' WHERE idCredencial='$idCredencialBd';");

       return 1;
        
    }    



    public function obtener__informacion__usuario($idCredencial) {

       return $this->constructor->select__general("SELECT idUsuario,cedula,nombre,fechaNacimiento,sexo,genero,estadoCivil,nacionalidad,discapacidad,fecha,hora,YEAR(CURDATE()) - YEAR(fechaNacimiento) - (RIGHT(CURDATE(), 5) < RIGHT(fechaNacimiento, 5)) AS edad FROM usuario WHERE idCredencial='$idCredencial';");
        
    } 



    public function obtener__informacion__organismo($idCredencial) {

       return $this->constructor->select__general("SELECT ruc,razonSocial FROM organismo WHERE idCredencial='$idCredencial';");
        
    } 

    public function obtener__informacion__contacto($idCredencial) {

       return $this->constructor->select__general("SELECT celular1,celular2,email1,email2,idProvincia,depCanton,depParroquia,callePrincipal,calleSecundaria,numeracion,fecha,hora FROM contacto WHERE idCredencial='$idCredencial';");
        
    } 

    public function obtener__informacion__representante($idCredencial) {

       return $this->constructor->select__general("SELECT cedula,nombre,sexo,celular1,celular2,correo1,correo2 FROM representante WHERE idCredencial='$idCredencial';");
        
    } 

    public function obtener__informacion__informacionTipo($idCredencial) {

       return $this->constructor->select__general("SELECT b.idTipoUsuario,b.nombre FROM credencial_tipo AS a INNER JOIN incentivo.tipo_usuario AS b ON a.idTipoUsuario=b.idTipoUsuario WHERE a.idCredencial='$idCredencial';");
        
    } 

    public function obtener__informacion__informacionSubTipo($idCredencial) {

       return $this->constructor->select__general("SELECT b.idSubTipo,b.nombre FROM credencial_tipo AS a INNER JOIN incentivo.sub_tipo_usuario AS b ON a.idSubTipo=b.idSubTipo WHERE a.idCredencial='$idCredencial';");
        
    } 

    public function componentes() {

       return $this->constructor->select__general__incentivo("SELECT idComponentes,nombre,descripcion FROM componentes WHERE estado='A' AND idComponentes!='6';");

    } 

    public function codigoProyecto($idCredencial) {

       return $this->constructor->select__general__incentivo("SELECT idProyecto,codigo,estado FROM proyecto WHERE estado!='E' AND idCredencial='$idCredencial';");

    } 



    public function descripcionProyecto($idCredencial,$url=null) {

      $consulta=$this->constructor->select__general__incentivo("SELECT tipoIngreso FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$idCredencial' AND tipoIngreso='modificacion' AND estado='A';");

      foreach ($consulta as $valor) {
          $tipoIngresoBd=$valor["tipoIngreso"];
      }

      $consulta__dos=$this->constructor->select__general__incentivo("SELECT estadoCalificacion FROM proyecto_enviado WHERE codigoUsuario='$idCredencial';");

      foreach ($consulta__dos as $valor__dos) {
          $estadoCalificacionBd=$valor__dos["estadoCalificacion"];
      }

      if(!empty($tipoIngresoBd) && $url==="EstadoProyectosA" && $estadoCalificacionBd==="CALIFICADO"){
        return $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,justificacionProyecto,justifiacionModificacion FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$idCredencial' AND estado='A' AND tipoIngreso='modificacion' ORDER BY idDescripcion DESC LIMIT 1;");
      }else if(!empty($tipoIngresoBd) && $url==="EstadoProyectosComite" && $estadoCalificacionBd==="CALIFICADO"){
        return $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,justificacionProyecto,justifiacionModificacion FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$idCredencial' AND estado='A' AND tipoIngreso='modificacion' ORDER BY idDescripcion DESC LIMIT 1;");
      }else if (!empty($tipoIngresoBd) && $url==="EstadoProyectosModificacion") {
        return $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,justificacionProyecto,justifiacionModificacion FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$idCredencial' AND estado='A' AND tipoIngreso='modificacion' ORDER BY idDescripcion DESC LIMIT 1;");
      }else{
        return $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,justificacionProyecto FROM proyecto_descripcion WHERE codigo='$idCredencial';");
      }

    } 

    public function componentes__principal($idCredencial) {

       return $this->constructor->select__general__incentivo("SELECT idComponentes,que,como,paraQue FROM proyecto_componente_usuario WHERE codigo='$idCredencial';");

    } 

    public function componentes__principal__pdf($idCredencial) {

       return $this->constructor->select__general__incentivo("(SELECT idComponentes,que,como,paraQue FROM proyecto_componente_usuario WHERE codigo='$idCredencial') UNION (SELECT idComponentes,que,como,paraQue FROM proyecto_componente_usuario_2 WHERE codigo='$idCredencial');");

    } 


    public function componentes__respaldos($idCredencial) {

       return $this->constructor->select__general__incentivo("SELECT idComponentes,que,como,paraQue FROM proyecto_componente_usuario_2 WHERE codigo='$idCredencial';");

    } 

    public function sectorProyecto() {

       return $this->constructor->select__general__incentivo("SELECT idSector, nombre, definicion,  GROUP_CONCAT(CONCAT(row_num, '- ', alineacion_nombre) ORDER BY row_num SEPARATOR '; ') AS alineacion FROM (SELECT a.idSector, a.nombre, a.definicion, b.nombre AS alineacion_nombre, @row_num := IF(@prev_sector = a.idSector, @row_num + 1, 1) AS row_num, @prev_sector := a.idSector FROM sector AS a INNER JOIN alineacion_estrategica AS b ON a.idSector = b.idSector CROSS JOIN (SELECT @row_num := 0, @prev_sector := '') AS vars WHERE a.estado = 'A' ORDER BY a.idSector, b.nombre) AS numbered_alineaciones GROUP BY idSector, nombre, definicion;");

    } 


    public function sectorObtener($codigo) {

       return $this->constructor->select__general__incentivo("SELECT a.idSector AS id,b.definicion FROM proyecto_sector AS a INNER JOIN sector AS b ON b.idSector=a.idSector WHERE a.codigo='$codigo';");

    } 

    public function justificacionObtener($codigo) {

       return $this->constructor->select__general__incentivo("SELECT id,nombre FROM proyecto_justificacion WHERE codigo='$codigo';");

    } 


    public function beneficiarios() {

       return $this->constructor->select__general__incentivo("SELECT idBeneficiario,nombre FROM beneficiario WHERE estado='A';");

    } 


    public function rangoEdad() {

       return $this->constructor->select__general__incentivo("SELECT idRango,nombre FROM rangoedad WHERE estado='A';");

    } 


    public function autoidentificacion() {

       return $this->constructor->select__general__incentivo("SELECT idAutentificacion,nombre FROM autentificacion WHERE estado='A';");

    } 

    public function discapacidad() {

       return $this->constructor->select__general__incentivo("SELECT idDiscapacidad,nombre FROM tipodiscapacidad WHERE estado='A';");

    } 


    public function insertaBeneficiario($post) {

        $idCredencial=$post["idCredencial"];
        $codigo=$post["codigo"];
        $beneficiariosArray=json_decode($post["beneficiarios"], true);
        $rangosEdadArray=json_decode($post["rangosEdad"], true);
        $generosArray=json_decode($post["generos"], true);
        $autoidentificacionesArray=json_decode($post["autoidentificaciones"], true);
        $tiposDiscapacidadArray=json_decode($post["tiposDiscapacidad"], true);
        $cantidadesArray=json_decode($post["cantidades"], true);

        if(!empty($codigo)){

            if (!empty(self::proyectoExistenteEliminarBase("id","proyecto_beneficiarios",$codigo,$idCredencial))) {

                 $consultaSector=$this->constructor->select__general__incentivo("SELECT id,idBeneficiario,idRango,idGenero,idAutentificacion,cantidad,codigo,idCredencial,idDiscapacidad FROM proyecto_beneficiarios WHERE idCredencial='$idCredencial' AND codigo='$codigo';");

                 foreach ($consultaSector as $valor) {

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_beneficiarios",['idBeneficiario','idRango','idGenero','idAutentificacion','cantidad','codigo','idCredencial','fecha','hora','idDiscapacidad'],array(':idBeneficiario' => $valor["idBeneficiario"],':idRango' =>  $valor["idRango"],':idGenero' =>  $valor["idGenero"],':idAutentificacion' =>  $valor["idAutentificacion"],':cantidad' =>  $valor["cantidad"],':codigo' =>  $valor["codigo"],':idCredencial' =>  $valor["idCredencial"],':fecha' => $this->fecha,':hora' => $this->hora,':idDiscapacidad' =>  $valor["idDiscapacidad"]));

                    $valorE=$valor["id"];
                    // $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_beneficiarios WHERE id='$valorE';");

                 }


            }

            foreach ($beneficiariosArray as $clave => $valor) {
                $this->constructor->inserta__general__incentivo("proyecto_beneficiarios",['idBeneficiario','idRango','idGenero','idAutentificacion','cantidad','codigo','idCredencial','fecha','hora','idDiscapacidad'],array(':idBeneficiario' => $valor,':idRango' =>  $rangosEdadArray[$clave],':idGenero' =>  $generosArray[$clave],':idAutentificacion' =>  $autoidentificacionesArray[$clave],':cantidad' =>  $cantidadesArray[$clave],':codigo' =>  $codigo,':idCredencial' =>  $idCredencial,':fecha' => $this->fecha,':hora' => $this->hora,':idDiscapacidad' =>  $tiposDiscapacidadArray[$clave])); 
            }

            $estadoAc=$this->constructor->select__general__incentivo("SELECT estado FROM proyecto WHERE codigo='$codigo';");
             foreach ($estadoAc as $valor) {
                if (intval($valor["estado"])===2) {
                    $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='3' WHERE codigo='$codigo';");
                }
             }

           return 1;

        }

    }     


    public function beneficiariosSelector($codigo) {

       return $this->constructor->select__general__incentivo("SELECT a.id,b.nombre AS idBeneficiario,c.nombre AS idRango,d.nombre AS idGenero,e.nombre AS idAutentificacion,f.nombre AS idDiscapacidad,cantidad FROM proyecto_beneficiarios AS a INNER JOIN beneficiario AS b ON a.idBeneficiario=b.idBeneficiario INNER JOIN rangoedad AS c ON c.idRango=a.idRango INNER JOIN genero AS d ON d.idGenero=a.idGenero INNER JOIN autentificacion AS e ON e.idAutentificacion=a.idAutentificacion INNER JOIN tipodiscapacidad AS f ON f.idDiscapacidad=a.idDiscapacidad  WHERE codigo='$codigo';");

    } 

    public function eliminarBeneficiariosSelector($id,$codigo) {

       $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_beneficiarios WHERE id='$id';");

       $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(idBeneficiario) AS contador FROM proyecto_beneficiarios WHERE codigo='$codigo';");


       foreach ($consulta as $valor) {
        $contador=$valor["contador"];
       }

       if (intval($contador)===0) {
            $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='2' WHERE codigo='$codigo';");
       }

       return 1;

    }     

    public function componentesPresupuesto($codigo) {

       return $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto,a.idComponentes,b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes WHERE a.codigo='$codigo';");

    } 

    public function obtenerPrimerNivel($idComponente) {

       return $this->constructor->select__general__incentivo("SELECT idNivel1,nombre,numeral,idComponentes,estado,nivel,color FROM componentes_nivel WHERE nivel='1' AND idComponentes='$idComponente';");

    } 


    public function obtenerPresupuestoNiveles($codigo,$anio,$tiposComponentes) {

       return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL OR ((a.nivel=0 OR a.nivel=1 OR a.nivel=2) AND rubros=0),'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.numeral FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.numeral) AS rotulo,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.detalle, a.justificacion,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.total,a.anio,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1 FROM proyecto_presupuesto AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' ORDER BY a.id;");

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos($codigo,$anio,$tiposComponentes) {

       return $this->constructor->select__general__incentivo("SELECT b.idComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idComponentes=b.idComponentes WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' GROUP BY b.idComponentes ORDER BY a.id;");

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos__footer($codigo,$anio,$tiposComponentes) {

       return $this->constructor->select__general__incentivo("SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total FROM proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");

    } 


    public function actualizarRubrosComponentes($post) {

        $codigo=$post["codigo"];
        $id=$post["id"];
        $mes=$post["mes"];
        $anio=$post["anio"];
        $valor=$post["valor"];
        $valorSuperior=$post["valorSuperior"];
        $atributoSuperior=$post["atributoSuperior"];
        $total=$post["total"];
        $arrayFooterArray=json_decode($post["arrayFooter"], true);
        $tiposComponentes=$post["tiposComponentes"];
        $totalAniosRecorridos=$post["totalAniosRecorridos"];

        if(!empty($codigo)){

            $consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS totalSuma FROM proyecto_presupuesto WHERE codigo='$codigo' AND nivel!=0 AND idComponentes!=6  GROUP BY codigo;");
            foreach ($consulta as $valorBd2) {
                $totalSumaBd=$valorBd2["totalSuma"];
            }

            $sumadorPasado=0;
            $sumadorPasado=floatval($totalSumaBd) + floatval($valor);

            $consultaId=$this->constructor->select__general__incentivo("SELECT idComponentes FROM proyecto_presupuesto WHERE id='$id';");
            foreach ($consultaId as $valorBd2Id) {
                $idComponentesBd=$valorBd2Id["idComponentes"];
            }

            $porcentaje=0;


            if(floatval($totalSumaBd)<=100000){
                $porcentaje=0.20;
            }else if(floatval($totalSumaBd)>=100001 && floatval($totalSumaBd)<=250000){
                $porcentaje=0.15;
            }else if(floatval($totalSumaBd)>=250001 && floatval($totalSumaBd)<=500000){
                $porcentaje=0.12;
            }else if(floatval($totalSumaBd)>=500001 && floatval($totalSumaBd)<=1000000){
                $porcentaje=0.10;
            }else{
                $porcentaje=0.75;
            }

            $valorPorciento=0;
            $valorPorciento = $totalSumaBd * $porcentaje;

            $consultaG=$this->constructor->select__general__incentivo("SELECT SUM(total) AS totalSumaComponentes FROM proyecto_presupuesto WHERE codigo='$codigo' AND nivel!=0 AND idComponentes=6 GROUP BY codigo;");
            foreach ($consultaG as $valorBd2G) {
                $totalSumaBdGastosAdministrativos=$valorBd2G["totalSumaComponentes"];
            }

            $sumandoGbd=0;
            $sumandoGbd=floatval($totalSumaBdGastosAdministrativos) + floatval($total);



            // if (floatval($sumadorPasado)>1000000) {

            //     $sumaMillon=0;
            //     $restaMillon=floatval($valorSuperior) - floatval($valor);

            //     $consulta2=$this->constructor->select__general__incentivo("SELECT $mes AS mes FROM proyecto_presupuesto WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");
            //     foreach ($consulta2 as $valorBd) {
            //         $mesBd=$valorBd["mes"];
            //     }

            //     $total_usado=abs(floatval($total) - floatval($valor));

                
            //     $this->constructor->inserta__general__incentivo("proyecto_presupuesto_footer", ['codigo', 'enero', 'febrero', 'marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector'], array(
            //         ':codigo' => $codigo,
            //         ':enero' => $arrayFooterArray[0],
            //         ':febrero' => $arrayFooterArray[1],
            //         ':marzo' => $arrayFooterArray[2],
            //         ':abril' => $arrayFooterArray[3],
            //         ':mayo' => $arrayFooterArray[4],
            //         ':junio' => $arrayFooterArray[5],
            //         ':julio' => $arrayFooterArray[6],
            //         ':agosto' => $arrayFooterArray[7],
            //         ':septiembre' => $arrayFooterArray[8],
            //         ':octubre' => $arrayFooterArray[9],
            //         ':noviembre' => $arrayFooterArray[10],
            //         ':diciembre' => $arrayFooterArray[11],
            //         ':total' => $arrayFooterArray[12],
            //         ':fecha' => $this->fecha,
            //         ':hora' => $this->hora,
            //         ':anio' => $anio,
            //         ':sector' => $tiposComponentes,
            //     ));


            //     return [$mesBd,$total_usado,$restaMillon,0,0];

            // }else 

            $consultaTiene=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE idSector='3' AND codigo='$codigo';");
            foreach ($consultaTiene as $valorTiene) {
                $idPriorizado=$valorTiene["id"];
            }


            if (intval($idComponentesBd)===6 && floatval($sumandoGbd)>floatval($valorPorciento) && floatval($sumandoGbd)>0 && empty($idPriorizado)) {


                $sumaMillon=0;
                $restaMillon=floatval($valorSuperior) - floatval($valor);

                $consulta2=$this->constructor->select__general__incentivo("SELECT $mes AS mes FROM proyecto_presupuesto WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");
                foreach ($consulta2 as $valorBd) {
                    $mesBd=$valorBd["mes"];
                }

                $total_usado=abs(floatval($total) - floatval($valor));

                $porcentajeNumerico = $porcentaje * 100;

                $this->constructor->inserta__general__incentivo("proyecto_presupuesto_footer", ['codigo', 'enero', 'febrero', 'marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector'], array(
                    ':codigo' => $codigo,
                    ':enero' => $arrayFooterArray[0],
                    ':febrero' => $arrayFooterArray[1],
                    ':marzo' => $arrayFooterArray[2],
                    ':abril' => $arrayFooterArray[3],
                    ':mayo' => $arrayFooterArray[4],
                    ':junio' => $arrayFooterArray[5],
                    ':julio' => $arrayFooterArray[6],
                    ':agosto' => $arrayFooterArray[7],
                    ':septiembre' => $arrayFooterArray[8],
                    ':octubre' => $arrayFooterArray[9],
                    ':noviembre' => $arrayFooterArray[10],
                    ':diciembre' => $arrayFooterArray[11],
                    ':total' => $arrayFooterArray[12],
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                    ':anio' => $anio,
                    ':sector' => $tiposComponentes,
                ));


                return [$mesBd,$total_usado,$restaMillon,1,$porcentajeNumerico];


            }else{


                $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_presupuesto SET $mes='$valor',total='$total' WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_presupuesto SET total='$valorSuperior' WHERE id='$atributoSuperior' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");


                $this->constructor->inserta__general__incentivo("proyecto_presupuesto_footer", ['codigo', 'enero', 'febrero', 'marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector'], array(
                    ':codigo' => $codigo,
                    ':enero' => $arrayFooterArray[0],
                    ':febrero' => $arrayFooterArray[1],
                    ':marzo' => $arrayFooterArray[2],
                    ':abril' => $arrayFooterArray[3],
                    ':mayo' => $arrayFooterArray[4],
                    ':junio' => $arrayFooterArray[5],
                    ':julio' => $arrayFooterArray[6],
                    ':agosto' => $arrayFooterArray[7],
                    ':septiembre' => $arrayFooterArray[8],
                    ':octubre' => $arrayFooterArray[9],
                    ':noviembre' => $arrayFooterArray[10],
                    ':diciembre' => $arrayFooterArray[11],
                    ':total' => $arrayFooterArray[12],
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                    ':anio' => $anio,
                    ':sector' => $tiposComponentes,
                ));

                return 1;

            }

        }

    } 


    public function actualizarRubrosComponentesTextos($post) {

        $valor=$post["valor"];
        $rotulo=$post["rotulo"];
        $id=$post["id"];
        $anioObtenido=$post["anioObtenido"];
        $codigo=$post["codigo"];
        $tiposComponentes=$post["tiposComponentes"];

        if($valor!=="null" && !empty($valor)){
            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_presupuesto SET $rotulo='$valor' WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes';");
        }else if($valor==="null" || empty($valor)){
            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_presupuesto SET $rotulo=NULL WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes';");
        }

        return 1;

    } 

    public function limpiarNombreAnexo($cadena) {
        $cadena = strtolower($cadena);
        $acentos = ['á','é','í','ó','ú','ñ','ü'];
        $sin_acentos = ['a','e','i','o','u','n','u'];
        $cadena = str_replace($acentos, $sin_acentos, $cadena);

        $cadena = preg_replace('/[^a-z0-9]/', '_', $cadena);
        $cadena = preg_replace('/_+/', '_', $cadena);

        return trim($cadena, '_');
    }

    public function normalizar($cadena) {
        $cadena = strtolower($cadena);
        $cadena = strtr($cadena, [
            'á' => 'a', 'é' => 'e', 'í' => 'i',
            'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i',
            'Ó' => 'o', 'Ú' => 'u'
        ]);
        return trim($cadena);
    }

    public function archivos__infraestructura__anexos__adicionales($post) {


        $consulta = $this->constructor->select__general__incentivo("
            SELECT 
                COUNT(*) AS total,
                SUM(LOWER(CONVERT(nombreAnexo USING ascii)) = LOWER(CONVERT('" . $post["nombreAnexo"] . "' USING ascii))) AS existe
            FROM proyecto_documentos_infraestructura_anexos
            WHERE codigo = '" . $post["codigo"] . "'
        ");

        $total = 0;
        $existe = 0;

        foreach ($consulta as $valor) {
            $total = intval($valor["total"]);
            $existe = intval($valor["existe"]);
        }

        if ($existe > 0) {
            return 200;
        }

        if ($total > 20) {
            return 10;
        }

        if ($_FILES['archivo']['size'] > 20 * 1024 * 1024) {
            return 200000;
        }

        $mimeType = mime_content_type($_FILES['archivo']['tmp_name']);
        if ($mimeType !== 'application/pdf') {
            return 40;
        }

        $nombreLimpiado = $this->limpiarNombreAnexo($post["nombreAnexo"]);
        $nombreArchivo = $nombreLimpiado . "__" . $post["codigo"] . "__" . $this->fecha . ".pdf";
        $rutaDefinitiva = $this->ruta . "documentosInfraestructura/";

        $rastreo = $this->constructor->archivoCargar__25__mb(
            $_FILES['archivo']['tmp_name'],
            $_FILES['archivo']['size'],
            $rutaDefinitiva,
            $nombreArchivo
        );

        if ($rastreo === 1) {
            $this->constructor->inserta__general__incentivo(
                "proyecto_documentos_infraestructura_anexos",
                ['nombreAnexo', 'archivo', 'fecha', 'hora', 'codigo'],
                array(
                    "nombreAnexo" => $post["nombreAnexo"],
                    "archivo" => $nombreArchivo,
                    ":fecha" => $this->fecha,
                    ":hora" => $this->hora,
                    "codigo" => $post["codigo"]
                )
            );
            return 1;
        } elseif ($rastreo === 2) {
            return 2;
        } else {
            return 0;
        }

    }

    public function actualizarArchivosInfra($post) {

        $campo=$post["campo"];
        $codigo=$post["codigo"];
        $anio=$post["anio"];

        if ($cronogramaValoradoExcel==="cronogramaValoradoExcel") {
            $nombreArchivo=$campo."__".$codigo.".xlsx";
        }else{
            $nombreArchivo=$campo."__".$codigo.".pdf";
        }


        $rutaDefinitiva=$this->ruta."documentosInfraestructura/";


        $rastreo=$this->constructor->archivoCargar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['size'],$rutaDefinitiva,$nombreArchivo);

        if ($rastreo===1) {

            $archivoExistente=$this->constructor->select__general__incentivo("SELECT idArchivosInfra FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';");

            foreach ($archivoExistente as $valor) {
                $idArchivosInfraBd=$valor["idArchivosInfra"];
            }

            if (empty($idArchivosInfraBd)) {

              $this->constructor->inserta__general__incentivo("proyecto_documentos_infraestructura",["$campo",'fecha','hora','codigo','anio'],array(":$campo" => $nombreArchivo,':fecha' => $this->fecha,':hora' => $this->hora,":codigo" => $codigo,":anio" => $anio)); 

            }else{
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_documentos_infraestructura SET $campo='$nombreArchivo',anio='$anio' WHERE codigo='$codigo' AND anio='$anio';");
            }


            return $this->constructor->select__archivo__natural($nombreArchivo,$rutaDefinitiva);

        }else if($rastreo===2){
            return 2;
        }else if($rastreo===0){
            return 0;
        }


    } 

    public function seleccionaArchivosInfras__cronogramaAnexo() {
        return $this->constructor->select__archivo__natural("cronograma.xlsx",'documentos/anexosInfraestructura/');
    } 


    public function seleccionaArchivosInfras__presupuestoAnexo() {
        return $this->constructor->select__archivo__natural("presupuesto.xlsx",'documentos/anexosInfraestructura/');
    } 


    public function seleccionaArchivosInfras__presupuesto($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT tituloPropiedad FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","tituloPropiedad");
    } 


    public function seleccionaArchivosInfras__cronogramaValorado($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT memoriaArquitectonica FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","memoriaArquitectonica");
    } 

    public function seleccionaArchivosInfras__apus($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT planosArquitectonicos FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","planosArquitectonicos");
    } 

    public function seleccionaArchivosInfras__planos($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT presupuestoRubro FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","presupuestoRubro");
    } 

    public function seleccionaArchivosInfras__docLegal($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT cronogramaValoradoPdf FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","cronogramaValoradoPdf");
    } 

    public function seleccionaArchivosInfras__otrosPendientes($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT cronogramaValoradoExcel FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","cronogramaValoradoExcel");
    } 

    public function seleccionaArchivosInfras__otrosRespaldosDigitales($codigo,$anio) {
        return $this->constructor->select__archivo__incentivo("SELECT respaldosDigitales FROM proyecto_documentos_infraestructura WHERE codigo='$codigo' AND anio='$anio';",$this->ruta."documentosInfraestructura/","respaldosDigitales");
    } 




    public function seleccionaArchivosInfras__otrosRespaldosDigitales__general($post) {

        $campo=$post["campo"];
        $codigo=$post["codigo"];

        return $this->constructor->select__general__incentivo("SELECT $campo FROM proyecto_documentos_infraestructura WHERE codigo='$codigo';");
    } 


    public function componentes__obligatorios__ingresar($codigo) {

        $array=array();

        $consulta=$this->constructor->select__general__incentivo("SELECT c.nombre,a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 INNER JOIN componentes AS c ON c.idComponentes=a.idComponentes WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='componente' AND a.idComponentes!='6' GROUP BY a.idComponentes;");


        foreach ($consulta as $valor) {

            if (intval($valor["total"])===0) {
                array_push($array,'Componente: '.$valor["nombre"]);
            }
        }

        if (count($array)>0) {
            $cadena = implode('; ', $array);
            return $cadena;
        }else{
            return "no";
        }

    } 

    public function obtenerSumasGlobalesAnuales($codigo) {
        return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='componente' GROUP BY a.anio;");
    } 

    public function obtenerSumasGlobalesAnuales__priorizados($codigo) {
        return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='priorizado' GROUP BY a.anio; ");
    } 


    public function obtenerSumasGlobalesAnuales__femeninos($codigo) {
        return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='femenino' GROUP BY a.anio; ");
    } 



    public function obtenerSumasGlobalesAnuales__priorizados__comparacion($codigo) {

        $porcentaje=$this->porcentaje__masculino();
        $porcentaje__2=$this->porcentaje__femenino();

        $entero = $porcentaje * 100;
        $entero__2 = $porcentaje__2 * 100;

        $restador=100 - $entero - $entero__2;

        $valorComoCadena = (string)$porcentaje; 
        $valorModificado = str_replace('0.', '1.', $valorComoCadena); 
        $valorFinal = (float)$valorModificado; 

        return $this->constructor->select__general__incentivo("SELECT a.anio, SUM(CASE WHEN a.sector = 'priorizado' THEN a.total ELSE 0 END) AS total_priorizado, ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90) + SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END),2) AS total_componente_rounded, CASE  WHEN ROUND(SUM(CASE WHEN a.sector = 'priorizado' THEN ROUND(a.total,2) ELSE 0 END),2) <> ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90),2) THEN CONCAT('No se ajusta al cinco por ciento para el año ', a.anio)  ELSE '1' END AS mensaje  FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1 = b.idNivel1 WHERE a.codigo = '$codigo' AND b.rubros = '1' AND (a.sector = 'priorizado' OR a.sector = 'componente') GROUP BY a.anio;");

    } 


    public function obtenerSumasGlobalesAnuales__femeninos__comparacion($codigo) {

        $porcentaje=$this->porcentaje__femenino();
        $porcentaje__2=$this->porcentaje__masculino();

        $entero = $porcentaje * 100;
        $entero__2 = $porcentaje__2 * 100;

        $restador=100 - $entero - $entero__2;

        $valorComoCadena = (string)$porcentaje; 
        $valorModificado = str_replace('0.', '1.', $valorComoCadena); 
        $valorFinal = (float)$valorModificado; 

        return $this->constructor->select__general__incentivo("SELECT a.anio, SUM(CASE WHEN a.sector = 'femenino' THEN a.total ELSE 0 END) AS total_priorizado, ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90) + SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END),2) AS total_componente_rounded, CASE  WHEN ROUND(SUM(CASE WHEN a.sector = 'femenino' THEN ROUND(a.total,2) ELSE 0 END),2) <> ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90),2) THEN CONCAT('No se ajusta al cinco por ciento para el año ', a.anio)  ELSE '1' END AS mensaje  FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1 = b.idNivel1 WHERE a.codigo = '$codigo' AND b.rubros = '1' AND (a.sector = 'femenino' OR a.sector = 'componente') GROUP BY a.anio;");

    } 


    public function obtenerArchivosComponentes__infras__5($codigo) {

        $componentesInfras= $this->constructor->select__general__incentivo("SELECT idComponentesProyecto FROM proyecto_componente_usuario WHERE codigo='$codigo' AND idComponentes='5';");

        foreach ($componentesInfras as $valor) {
            $idComponentesProyectoBd=$valor["idComponentesProyecto"];
        }

        if (!empty($idComponentesProyectoBd)) {
            return 1;
        }else{
            return 0;
        }

    } 


    public function componente__infra__seleccionado__compra__venta__bienes($codigo) {

        $componentesInfras= $this->constructor->select__general__incentivo("SELECT idComponentesProyecto FROM proyecto_componente_usuario WHERE codigo='$codigo' AND idComponentes='7';");

        foreach ($componentesInfras as $valor) {
            $idComponentesProyectoBd=$valor["idComponentesProyecto"];
        }

        if (!empty($idComponentesProyectoBd)) {
            return 1;
        }else{
            return 0;
        }

    } 

    public function obtenerArchivosComponentes__infras($codigo,$anio) {

        return $this->constructor->select__general__incentivo("SELECT IF(presupuestoRubro IS NULL, CONCAT_WS(' ','Presupuesto',' año', anio),1) AS presupuestoRubro, IF(cronogramaValoradoPdf IS NULL, CONCAT_WS(' ','Cronograma pdf',' año', anio),1) AS cronogramaValoradoPdf, IF(cronogramaValoradoExcel IS NULL, CONCAT_WS(' ','Cronograma excel',' año', anio),1) AS cronogramaValoradoExcel FROM proyecto_documentos_infraestructura WHERE codigo='$codigo';");

    } 

    public function guardarEstadoComponentes($codigo,$idCredencial) {

        if(!empty($codigo)){

            $codigoFinal=$codigo;

            $consultaRespaldo__descripcion=$this->constructor->select__general__incentivo("SELECT fechaInicio,diferenciaAnios FROM proyecto_descripcion WHERE codigo='$codigo';");

            foreach ($consultaRespaldo__descripcion as $valor) {
                $fechaInicioBd=$valor["fechaInicio"];
                $diferenciaAnios=$valor["diferenciaAnios"];
            }
            
            $arrayAnios=array();
            $array__fecha = explode('-', $fechaInicioBd);

            $sumadorAnios=0;

            for ($i=0; $i <= intval($diferenciaAnios); $i++) { 
                if ($i==0) {
                    array_push($arrayAnios, intval($array__fecha[0]));
                }else{
                    $sumadorAnios=intval($array__fecha[0])+$i;
                    array_push($arrayAnios,$sumadorAnios);
                }

            }

            /*=========================================================
            =            Generar Cronograma de actividades            =
            =========================================================*/
            
            $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_cronograma_actividades WHERE codigo='$codigoFinal';");
            
            $sumadorCronograma=0;

           for($i=0; $i<count($arrayAnios);$i++){

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='componente' AND z.idComponentes!='6' GROUP BY z.idComponentes;");

                foreach ($componetesV as $valor) {

                    $sumadorCronograma++;

                    $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'componente',
                        ':orden' => $sumadorCronograma
                    ));


                  $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$sumadorCronograma);


                }


            }



            /*=====  End of Generar Cronograma de actividades  ======*/


             
             $sectorProyectoPriorizado=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE idSector='3' AND codigo='$codigo';");
             foreach ($sectorProyectoPriorizado as $valor) {
                $idBdPriorizados=$valor["id"];
             }


             /*==========================================================
             =            Ingresar cronograma de actividades            =
             ==========================================================*/

             $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_cronograma_actividades WHERE  (sector='priorizado' OR sector='femenino') AND codigo='$codigoFinal';");
             
             if (!empty($idBdPriorizados)) {


                $arrayIdComponentes=array();
                $consultaRespaldo__componentes=$this->constructor->select__general__incentivo("SELECT idComponentes FROM proyecto_componente_usuario WHERE codigo='$codigo';");

                foreach ($consultaRespaldo__componentes as $valor) {
                    array_push($arrayIdComponentes,$valor["idComponentes"]);
                }

                $sumadorPriorisados=0;


                for($i=0; $i<count($arrayAnios);$i++){

                    $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='priorizado' AND z.idComponentes!='6' GROUP BY z.idComponentes;");

                    foreach ($componetesV as $valor) {

                        $sumadorPriorisados++;

                        $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden'], array(
                            ':idComponentes' => $valor["idComponentes"],
                            ':idNivel1' => $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'priorizado',
                            ':orden' => $sumadorPriorisados
                        ));


                        $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$sumadorPriorisados);


                    }


                }

                $sumadorFemenino=0;


                for($i=0; $i<count($arrayAnios);$i++){

                    $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='femenino' AND z.idComponentes!='6' GROUP BY z.idComponentes;");


                    foreach ($componetesV as $valor) {

                        $sumadorFemenino++;

                        $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden'], array(
                            ':idComponentes' => $valor["idComponentes"],
                            ':idNivel1' => $valorNivel__1["idNivel1"],
                            ':fecha' => $this->fecha,
                            ':hora' => $this->hora,
                            ':anio' => $arrayAnios[$i],
                            ':codigo' => $codigoFinal,
                            ':idCredencial' => $idCredencial,
                            ':nivel' => 0,
                            ':sector' => 'femenino',
                            ':orden' => $sumadorFemenino
                        ));


                        $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$sumadorFemenino);


                    }


                }

            }
             
             
             /*=====  End of Ingresar cronograma de actividades  ======*/
             

            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='4' WHERE codigo='$codigo';");

        }

    } 

     public function buscarProfecionales($codigo) {

        $profesional=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE idSector='3' AND codigo='$codigo';");

        foreach ($profesional as $valor) {
            $idBd=$valor["id"];
        }

        if (!empty($idBd)) {
            return 1;
        }else{
            return 0;
        }

    }    

    public function porcentaje($codigo) {
        return $this->constructor->select__general__incentivo("SELECT pFemenino,pMasculino FROM porcentajes WHERE estado='A';");
    } 


    public function componentes__descripcion__null($codigo) {
        return $this->constructor->select__general__incentivo("SELECT b.nombre AS componente, c.nombre AS rubro,a.id FROM proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes INNER JOIN componentes_nivel AS c ON c.idNivel1=a.idNivel1 WHERE a.codigo='$codigo' AND (a.enero>0 || a.febrero>0 || a.marzo>0 || a.abril>0 || a.mayo>0 || a.junio>0 || a.julio>0 || a.agosto>0 || a.septiembre>0 || a.octubre>0 || a.noviembre>0 || a.diciembre>0) AND (a.detalle IS NULL OR a.justificacion IS NULL);");
    } 


    public function porcentaje__femenino() {
        $porcentaje=$this->constructor->select__general__incentivo("SELECT pFemenino FROM porcentajes WHERE estado='A';");
        foreach ($porcentaje as $valor) {
            $porcentajeBd=$valor["pFemenino"];
        }
        return $porcentajeBd;
    } 


    public function porcentaje__masculino() {
        $porcentaje=$this->constructor->select__general__incentivo("SELECT pMasculino FROM porcentajes WHERE estado='A';");
        foreach ($porcentaje as $valor) {
            $porcentajeBd=$valor["pMasculino"];
        }
        return $porcentajeBd;
    } 


    public function cronogramaDeActividades($codigo,$anio,$tiposComponentes) {

        $array=array();

        $consultaComponentesEvaluos=$this->constructor->select__general__incentivo("SELECT idComponentes FROM proyecto_cronograma_actividades AS a WHERE a.anio='$anio' AND a.codigo='$codigo' AND a.sector='$tiposComponentes' GROUP BY a.idComponentes,a.idNivel1;");

        foreach ($consultaComponentesEvaluos as $valor) {
          array_push($array, $valor["idComponentes"]);
        }


        if(count($array)>1){

            $ocurrencias = array_count_values($array);
            $idComponente = array_search(max($ocurrencias), $ocurrencias);

            $unico = array_unique($array);

            $conteo = array_count_values($array);

            $repetidos = array_keys(array_filter($conteo, function($count) {
                return $count >= 2;
            }));

            $filtrados = array_filter($array, function($item) use ($conteo) {
                return $conteo[$item] >= 2;
            });

            $condiciones = [];

            $unicoFiltrados = array_unique($filtrados);


            foreach ($unicoFiltrados as $valor) {
                $condiciones[] = "a.idComponentes = '$valor'";
            }

            $consulta = '(' . implode(' OR ', $condiciones) . ')';


            return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.actividades,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.tipo,a.provincia,a.canton,a.parroquia,a.pais,a.ciudad,a.anio,a.creado,a.orden,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1,z.enero AS eneroP,z.febrero AS febreroP,z.marzo AS marzoP,z.abril AS abrilP,z.mayo AS mayoP,z.junio AS junioP,z.julio AS julioP,z.agosto AS agostoP,z.septiembre AS septiembreP,z.octubre AS octubreP,z.noviembre AS noviembreP,z.diciembre AS diciembreP, IF(a.enero=0,'NO','SI') AS eneroVariable, IF(a.febrero=0,'NO','SI') AS febreroVariable, IF(a.marzo=0,'NO','SI') AS marzoVariable, IF(a.abril=0,'NO','SI') AS abrilVariable, IF(a.mayo=0,'NO','SI') AS mayoVariable, IF(a.junio=0,'NO','SI') AS junioVariable, IF(a.julio=0,'NO','SI') AS julioVariable, IF(a.agosto=0,'NO','SI') AS agostoVariable, IF(a.septiembre=0,'NO','SI') AS septiembreVariable, IF(a.octubre=0,'NO','SI') AS octubreVariable, IF(a.noviembre=0,'NO','SI') AS noviembreVariable, IF(a.diciembre=0,'NO','SI') AS diciembreVariable,IF(a.tipo=0,'NACIONAL','INTERNACIONAL') AS tipoVariable, IFNULL(IF(a.tipo=1,(SELECT a1.nombre FROM configuracion.paises AS a1 WHERE a1.id=a.pais),'N-A'),'N-A') AS paisVariable, IFNULL(IF(a.tipo=1,a.ciudad,'N-A'),'N-A') AS ciudadVariable, IFNULL(IF(a.tipo=0,(SELECT a1.nombre FROM configuracion.provincia AS a1 WHERE a1.idProvincia=a.provincia),'N-A'),'N-A') AS provinciaNombre, IFNULL(IF(a.tipo=0,(SELECT a1.nombre FROM configuracion.canton AS a1 WHERE a1.depCanton=a.canton),'N-A'),'N-A') AS cantonVariable, IFNULL(IF(a.tipo=0,(SELECT a1.nombre FROM configuracion.parroquia AS a1 WHERE a1.depParroquia=a.parroquia),'N-A'),'N-A') AS parroquiaVariable FROM proyecto_cronograma_actividades AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 LEFT JOIN proyecto_presupuesto AS z ON z.idNivel1=a.idNivel1 AND a.idComponentes=z.idComponentes AND z.sector='$tiposComponentes' AND a.codigo=z.codigo AND z.anio='$anio'  WHERE $consulta AND a.codigo='$codigo' AND a.anio='$anio' AND a.sector='$tiposComponentes'   ORDER BY a.orden,a.idNivel1,a.id;");

        }else{
            return 0;
        }



    } 


    public function actualizarRubrosComponentesTextos__cronogramaDeActividades($post) {

        $valor=$post["valor"];
        $rotulo=$post["rotulo"];
        $id=$post["id"];
        $anioObtenido=$post["anioObtenido"];
        $codigo=$post["codigo"];
        $tiposComponentes=$post["tiposComponentes"];

        if(!empty($codigo)){

            if($valor!=="null" && !empty($valor)){
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_cronograma_actividades SET $rotulo='$valor' WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes';");
            }else if($valor==="null"){
                 $this->constructor->actualiza__general__incentivo("UPDATE proyecto_cronograma_actividades SET $rotulo=NULL WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes';");
            }

            return 1;

        }

    } 

    public function actualizarRubrosComponentesMeses__cronogramaDeActividades($post) {

        $codigo=$post["codigo"];
        $anioObtenido=$post["anioObtenido"];
        $tiposComponentes=$post["tiposComponentes"];
        $valorEnviar=$post["valorEnviar"];
        $mes=$post["mes"];
        $id=$post["id"];

        if(!empty($codigo)){

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_cronograma_actividades SET $mes='$valorEnviar' WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes';");

            return 1;

        }

    } 

    public function actualizarTipo__cronogramaDeActividades($post) {

        $codigo=$post["codigo"];
        $anioObtenido=$post["anioObtenido"];
        $tiposComponentes=$post["tiposComponentes"];
        $valor=$post["valor"];
        $id=$post["id"];

        if(!empty($codigo)){

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_cronograma_actividades SET tipo='$valor' WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes';");
            return 1;

        }


    } 

    public function guardarCronogramas__adicionales($post) {

        $codigo=$post["codigo"];
        $tipo=$post["tipo"];
        $campo=$post["campo"];
        $valor=$post["valor"];
        $id=$post["id"];
        $anio=$post["anio"];

        if(!empty($codigo)){

            if ($valor!=="" && !empty($valor) && $valor!=="null" && $valor!==null) {
               $this->constructor->actualiza__general__incentivo("UPDATE proyecto_cronograma_actividades SET $campo='$valor' WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tipo';");
            }else{
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_cronograma_actividades SET $campo=NULL WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tipo';");
            }
            
            return 1;

        }

    } 

    public function guardarCronogramas__agregadosAdicionales($post) {

        $codigo=$post["codigo"];
        $anio=$post["anio"];
        $tipo=$post["tipo"];
        $id=$post["id"];
        $creado=$post["creado"];
        $orden=$post["orden"];
        $idComponentes=$post["idComponentes"];
        $idNivel1=$post["idNivel1"];
        $idCredencial=$post["idCredencial"];
        $nivel=$post["nivel"];


        if(!empty($codigo)){

            $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','creado'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $idNivel1,
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigo,
                ':idCredencial' => $idCredencial,
                ':nivel' => $nivel,
                ':sector' => $tipo,
                ':orden' => $orden,
                ':creado' => 'A',
            ));


            return 1;

        }

    } 

    public function eliminarCronogramas__agregadosAdicionales($post) {

        $codigo=$post["codigo"];
        $anio=$post["anio"];
        $tipo=$post["tipo"];
        $id=$post["id"];
        $creado=$post["creado"];
        $orden=$post["orden"];
        $idComponentes=$post["idComponentes"];
        $idNivel1=$post["idNivel1"];
        $idCredencial=$post["idCredencial"];
        $nivel=$post["nivel"];

        if(!empty($codigo)){

            $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_cronograma_actividades WHERE id='$id';");
            return 1;

        }

    } 

    public function validarParroquia($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM proyecto_cronograma_actividades WHERE codigo='$codigo' AND parroquia IS NULL AND idNivel1 IS NOT NULL AND tipo=0;");
    } 

    public function validarCanton($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM proyecto_cronograma_actividades WHERE codigo='$codigo' AND canton IS NULL AND idNivel1 IS NOT NULL AND tipo=0;");
    } 


    public function validarProvincia($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM proyecto_cronograma_actividades WHERE codigo='$codigo' AND provincia IS NULL AND idNivel1 IS NOT NULL AND tipo=0;");
    } 

    public function validarPais($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM proyecto_cronograma_actividades WHERE codigo='$codigo' AND pais IS NULL AND idNivel1 IS NOT NULL AND tipo=1;");
    } 

    public function validarCiudad($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM proyecto_cronograma_actividades WHERE codigo='$codigo' AND ciudad IS NULL AND idNivel1 IS NOT NULL AND tipo=1;");
    } 

    public function validarComponentes($codigo) {
        return $this->constructor->select__general__incentivo("SELECT a.id,b.nombre AS componente,c.nombre AS nivel,a.anio FROM proyecto_cronograma_actividades AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes INNER JOIN componentes_nivel AS c ON a.idNivel1=c.idNivel1 WHERE a.codigo='$codigo' AND a.enero=0 AND a.febrero=0 AND a.marzo=0 AND a.abril=0 AND a.mayo=0 AND a.junio=0 AND a.julio=0 AND a.agosto=0 AND a.septiembre=0 AND a.octubre=0 AND a.septiembre=0 AND a.octubre=0 AND a.noviembre=0 AND a.diciembre=0 AND a.idNivel1 IS NOT NULL AND a.idComponentes!=5;");
    } 



    public function validarActividades($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM proyecto_cronograma_actividades WHERE codigo='$codigo' AND actividades IS NULL AND idNivel1 IS NOT NULL AND idComponentes!='5';");
    } 


    public function guardarCronogramaDeActividades($codigo) {

        if(!empty($codigo)){
            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='5' WHERE codigo='$codigo';");
        }

    } 

    public function resultadosEsperados__obtener($codigo) {

        return $this->constructor->select__general__incentivo("SELECT id,objetivoEspecifico,nombreIndicador,descripcion,metodoCalculo,metaFinal,periodicidad,medioVerificacion FROM proyecto_resultados_metas WHERE codigo='$codigo';");

    } 

    public function actualizarResultadosEsperados($post) {

        $valor=$post["valor"];
        $campo=$post["campo"];
        $codigo=$post["codigo"];
        $id=$post["id"];

        if(!empty($codigo)){
            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_resultados_metas SET $campo='$valor' WHERE codigo='$codigo' AND id='$id';");
        }

    } 

    public function obtenerPronosticos($codigo) {

        return $this->constructor->select__general__incentivo("SELECT id,deportistaOrganismo,disciplina,categoriaEdad,eventoParticipacion,pronosticoUbicacion FROM proyecto_pronostico WHERE codigo='$codigo';");

    } 


    public function agregarPronosticos($post) {

        $codigo=$post["codigo"];

        if(!empty($codigo)){

            $this->constructor->inserta__general__incentivo("proyecto_pronostico", ['codigo', 'fecha', 'hora'], array(
                ':codigo' => $codigo,
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
            ));

            return 1;

        }

    } 

    public function eliminarPronosticos($codigo,$id) {


        return $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_pronostico WHERE id='$id' AND codigo='$codigo';");

    } 


    public function actualizarPronosticos($post) {

        $valor=$post["valor"];
        $campo=$post["campo"];
        $codigo=$post["codigo"];
        $id=$post["id"];

        if(!empty($codigo)){
            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_pronostico SET $campo='$valor' WHERE codigo='$codigo' AND id='$id';");
        }

    } 



    public function guardarPronosticos($codigo) {

        if(!empty($codigo)){

            $this->constructor->actualiza__general__incentivo("DELETE FROM  proyecto_seguimiento WHERE codigo='$codigo';");

            $metasBd=$this->constructor->select__general__incentivo("SELECT nombreIndicador,periodicidad FROM proyecto_resultados_metas WHERE codigo='$codigo';");


            foreach ($metasBd as $valor) {
                    

                $this->constructor->inserta__general__incentivo("proyecto_seguimiento", ['indicador','periodicidad','codigo', 'fecha', 'hora'], array(
                    ':indicador' => $valor["nombreIndicador"],
                    ':periodicidad' => $valor["periodicidad"],
                    ':codigo' => $codigo,
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                ));


            }

            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='6' WHERE codigo='$codigo';");

        }


    } 



    public function seguimientoEvaluacion($codigo) {

        return $this->constructor->select__general__incentivo("SELECT id,indicador,periodicidad,actividadSeguimiento,medioVerficiacion,observacion FROM proyecto_seguimiento WHERE codigo='$codigo';");

    } 

    public function actualizarSeguimientoEvaluacion($post) {

        $valor=$post["valor"];
        $campo=$post["campo"];
        $codigo=$post["codigo"];
        $id=$post["id"];

        if(!empty($codigo)){
            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento SET $campo='$valor' WHERE codigo='$codigo' AND id='$id';");
        }

    } 


    public function guardarSeguimiento($codigo) {

        if(!empty($codigo)){
            return $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='7' WHERE codigo='$codigo';");
        }
       
    } 


    public function validarIndicador($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_resultados_metas WHERE codigo='$codigo' AND nombreIndicador IS NULL;");
    } 

    public function validarDescripcion($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_resultados_metas WHERE codigo='$codigo' AND descripcion IS NULL;");
    } 

    public function validarMetodoCalculo($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_resultados_metas WHERE codigo='$codigo' AND metodoCalculo IS NULL;");
    } 

    public function validarMetaFinal($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_resultados_metas WHERE codigo='$codigo' AND metaFinal IS NULL;");
    } 

    public function validarPeriodicidad($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_resultados_metas WHERE codigo='$codigo' AND periodicidad IS NULL;");
    } 

    public function validarMedioVerificacion($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_resultados_metas WHERE codigo='$codigo' AND medioVerificacion IS NULL;");
    } 

    public function validarGlobalIndicador($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_pronostico WHERE codigo='$codigo';");
    } 

    public function validarDeportistaOrganismo($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_pronostico WHERE codigo='$codigo' AND deportistaOrganismo IS NULL;");
    } 

    public function validarDisciplina($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_pronostico WHERE codigo='$codigo' AND disciplina IS NULL;");
    }     


    public function validarCategoriaDeEdad($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_pronostico WHERE codigo='$codigo' AND categoriaEdad IS NULL;");
    }  

    public function validarEventoParticipacion($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_pronostico WHERE codigo='$codigo' AND eventoParticipacion IS NULL;");
    }  

    public function validarPronosticoUbicacion($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_pronostico WHERE codigo='$codigo' AND pronosticoUbicacion IS NULL;");
    }  



    public function validarActividadSeguimientoSeguimiento($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento WHERE actividadSeguimiento IS NULL AND codigo='$codigo';");
    }  

    public function validarMedioVerificacionSeguimiento($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento WHERE medioVerficiacion IS NULL AND codigo='$codigo';");
    }  


    public function validarObservacionSeguimiento($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento WHERE observacion IS NULL AND codigo='$codigo';");
    }  


    public function actualizarArchivosGenerales($post) {

        $campo=$post["campo"];
        $codigo=$post["codigo"];

        if(!empty($codigo)){

            $nombreArchivo=$campo."__".$codigo.".pdf";


            $rutaDefinitiva=$this->ruta."requerimientos/";


            $rastreo=$this->constructor->archivoCargar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['size'],$rutaDefinitiva,$nombreArchivo);


            if ($rastreo===1) {

                $archivoExistente=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE codigo='$codigo';");

                foreach ($archivoExistente as $valor) {
                    $idDocumentos=$valor["id"];
                }

                if (empty($idDocumentos)) {

                  $this->constructor->inserta__general__incentivo("proyecto_documentos_requisitos",["$campo",'fecha','hora','codigo'],array(":$campo" => $nombreArchivo,':fecha' => $this->fecha,':hora' => $this->hora,":codigo" => $codigo)); 

                }else{
                    $this->constructor->actualiza__general__incentivo("UPDATE proyecto_documentos_requisitos SET $campo='$nombreArchivo' WHERE codigo='$codigo';");
                }


                return $this->constructor->select__archivo__natural($nombreArchivo,$rutaDefinitiva);


            }else if($rastreo===2){
                return 2;
            }else if($rastreo===0){
                return 0;
            }


        }

    } 

    public function select__general__documentos__incentivo($post) {

        $campo=$post["campo"];
        $codigo=$post["codigo"];

        return $this->constructor->select__general__incentivo("SELECT $campo FROM proyecto_documentos_requisitos WHERE codigo='$codigo';");
    } 

    public function buscarDocumentos__requisitos($codigo) {
        return $this->constructor->select__general__incentivo("SELECT id,proyecto,curriculoDeportivo,certificadoTrayectoria,documentoLegalProponente,ruc,tituloPropiedad,memoriaTecnica,planosArquitectonicos,presupuestoDetalle,respaldoDigitales,analisisPreciosUnitarios,especificacionesTecnicas FROM proyecto_documentos_requisitos WHERE codigo='$codigo';");
    } 

    public function seleccionaGlobales__proyecto($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT proyecto FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","proyecto");
    } 

    public function seleccionaGlobales__curriculoDeportivo($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT curriculoDeportivo FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","curriculoDeportivo");
    } 


    public function seleccionaGlobales__certificadoTrayectoria($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT certificadoTrayectoria FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","certificadoTrayectoria");
    } 


    public function seleccionaGlobales__documentoLegalProponente($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT documentoLegalProponente FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","documentoLegalProponente");
    } 

    public function seleccionaGlobales__ruc($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT ruc FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","ruc");
    } 

    public function seleccionaGlobales__tituloPropiedad($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT tituloPropiedad FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","tituloPropiedad");
    } 

    public function seleccionaGlobales__memoriaTecnica($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT memoriaTecnica FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","memoriaTecnica");
    } 

    public function seleccionaGlobales__planosArquitectonicos($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT planosArquitectonicos FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","planosArquitectonicos");
    } 


    public function seleccionaGlobales__presupuestoDetalle($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT presupuestoDetalle FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","presupuestoDetalle");
    } 


    public function seleccionaGlobales__respaldoDigitales($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT respaldoDigitales FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","respaldoDigitales");
    } 

    public function seleccionaGlobales__analisisPreciosDigitales($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT analisisPreciosUnitarios FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","analisisPreciosUnitarios");
    } 

    public function seleccionaGlobales__especificacionesTecnicas($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT especificacionesTecnicas FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","especificacionesTecnicas");
    } 

    public function propiedadInmueble__CompraVenta($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT propiedadInmueble__CompraVenta FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","propiedadInmueble__CompraVenta");
    } 

    public function compromiso__CompraVenta($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT compromiso__CompraVenta FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","compromiso__CompraVenta");
    } 

    public function avaluo__CompraVenta($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT avaluo__CompraVenta FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","avaluo__CompraVenta");
    } 

    public function informeEstadoActual__CompraVenta($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT informeEstadoActual__CompraVenta FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","informeEstadoActual__CompraVenta");
    } 


    public function permisos__CompraVenta($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT permisos__CompraVenta FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","permisos__CompraVenta");
    } 
    

    public function seleccionaGlobales__proyectoCargado($codigo) {
        return $this->constructor->select__archivo__natural__ruta('../../react/firmaElectronica/documentos/'.$codigo.'__proyecto.pdf');
        // return $this->constructor->select__archivo__natural__ruta('https://servicios.deporte.gob.ec/poa2/firmaRepositorio/documentos/'.$codigo.'__proyecto.pdf');
    } 

    public function seleccionaGlobales__curriculoExperiencia($codigo) {
        return $this->constructor->select__archivo__incentivo("SELECT curriculoExperiencia FROM proyecto_documentos_requisitos WHERE codigo='$codigo';",$this->ruta."requerimientos/","curriculoExperiencia");
    } 


    public function guarda__documentos__habilitantes($codigo,$tipoUsuario,$componente) {

        if(!empty($codigo)){

            $banderaCondicionante__general=1;
            $banderaCondicionante__infraestructura=1;

            $consultaInfraComponentes=$this->infraestructuraEscogido($codigo);
            foreach ($consultaInfraComponentes as $valorComponentesC) {
               $idComponentes=$valorComponentesC["idComponentes"];
            }

            $consulta=$this->sectorObtenerNombre($codigo);

            foreach ($consulta as $valor) {
                $idSectorBd=$valor["idSector"];
            }


             $consulta__general=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE codigo='$codigo';");
             foreach ($consulta__general as $valor) {
                $idGeneralBd=$valor["id"];
             }

             if (empty($idGeneralBd)) {
                $banderaCondicionante__general=0;
             }

            if (intval($tipoUsuario)===1) {

               $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE (curriculoDeportivo IS NULL OR certificadoTrayectoria IS NULL OR ruc IS NULL) AND codigo='$codigo';");
               foreach ($consulta as $valor) {
                    $idBd=$valor["id"];
               }

               if (!empty($idBd)) {
                    $banderaCondicionante__general=0;
               }

            }else if(intval($tipoUsuario)===2 || intval($tipoUsuario)===3 || intval($tipoUsuario)===5){

               $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE ruc IS NULL AND codigo='$codigo'; ");
               foreach ($consulta as $valor) {
                    $idBd=$valor["id"];
               }

               if (!empty($idBd)) {
                    $banderaCondicionante__general=0;
               }

            }else if(intval($tipoUsuario)===4){

               $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE (curriculoExperiencia IS NULL OR ruc IS NULL) AND codigo='$codigo';");
               foreach ($consulta as $valor) {
                    $idBd=$valor["id"];
               }

               if (!empty($idBd)) {
                    $banderaCondicionante__general=0;
               }

            }

            if (intval($idComponentes)===5  && intval($tipoUsuario)!==5) {

               $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE (tituloPropiedad IS NULL OR memoriaTecnica IS NULL OR planosArquitectonicos IS NULL OR respaldoDigitales IS NULL OR ruc IS NULL) AND codigo='$codigo';");
               foreach ($consulta as $valor) {
                    $idBd=$valor["id"];
               }

               if (!empty($idBd)) {
                    $banderaCondicionante__infraestructura=0;
               }

            }

            $banderaCondicionante__infraestructuraProponente=1;


            if (intval($idComponentes)===5 && intval($tipoUsuario)===5) {

               $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE (tituloPropiedad IS NULL OR memoriaTecnica IS NULL OR planosArquitectonicos IS NULL OR respaldoDigitales IS NULL OR ruc IS NULL OR analisisPreciosUnitarios IS NULL OR especificacionesTecnicas IS NULL) AND codigo='$codigo';");
               foreach ($consulta as $valor) {
                    $idBd=$valor["id"];
               }

               if (!empty($idBd)) {
                    $banderaCondicionante__infraestructuraProponente=0;
               }

            }

            $consultaProfesional=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_documentos_requisitos WHERE documentoLegalProponente IS NULL AND codigo='$codigo';");
            foreach ($consultaProfesional as $valor) {
               $idProfesionalRequisitos=$valor["id"];
            }


            if (intval($idSectorBd)===3 && !empty($idProfesionalRequisitos)) {
                 $banderaCondicionante__general=0;
            }

            if ($banderaCondicionante__general===0 || $banderaCondicionante__infraestructura===0 || $banderaCondicionante__infraestructuraProponente===0) {
                return 0;
            }else{
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='2' WHERE codigo='$codigo';");
                return 1;
            }


        }

    } 

    public function sectorObtenerNombre($codigo) {

       return $this->constructor->select__general__incentivo("SELECT a.idSector, b.nombre FROM proyecto_sector AS a INNER JOIN sector AS b ON a.idSector=b.idSector WHERE codigo='$codigo';");

    } 

    public function infraestructuraEscogido($codigo) {
       return $this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes FROM proyecto_componente_usuario WHERE idComponentes='5' AND codigo='$codigo';");
    } 

    public function infraestructuraEscogido__compraBienesInmuebles($codigo) {
       return $this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes FROM proyecto_componente_usuario WHERE idComponentes='7' AND codigo='$codigo';");
    } 

    public function baseLegal($codigo) {
       return $this->constructor->select__general__incentivo("SELECT a.nombre AS ley, b.nombre AS baseLegal FROM ley AS a INNER JOIN baselegal AS b ON a.idLey=b.idLey;");
    } 



    public function montoTotal($codigo) {
       return $this->constructor->select__general__incentivo("SELECT SUM(total) AS totalPresupuesto FROM proyecto_presupuesto WHERE codigo='$codigo' AND idNivel1 IS NOT NULL GROUP BY codigo;");
    } 


    public function obtener__infraestructuraObligatorios($codigo) {
       return $this->constructor->select__general__incentivo("SELECT SUM(total) AS suma FROM proyecto_presupuesto WHERE codigo='$codigo' AND idComponentes='5' GROUP BY idComponentes; ");
    } 



    public function observableCargaCredencial__general($idCredencial) {


        $tipoOrganismo=self::obtener__informacion__informacionTipo($idCredencial);
        foreach ($tipoOrganismo as $valor) {
            $idTipoUsuarioBd=$valor["idTipoUsuario"];
        }


        if (intval($idTipoUsuarioBd)===1 || intval($idTipoUsuarioBd)===2) {

            $informacionUsuario=self::obtener__informacion__usuario($idCredencial);
            foreach ($informacionUsuario as $valor) {
                $credencialProponente=$valor["cedula"];
                $nombreProponente=$valor["nombre"];
            }


        }else{

            $informacionUsuario=self::obtener__informacion__organismo($idCredencial);
            foreach ($informacionUsuario as $valor) {
                $credencialProponente=$valor["ruc"];
                $nombreProponente=$valor["razonSocial"];
            }


        }

        $arrayP=array();

        array_push($arrayP, $credencialProponente);
        array_push($arrayP, $nombreProponente);

        return $arrayP;


    } 

    public function codigoFinal__version__1($version) {

        // $contador=$this->constructor->select__general__incentivo("SELECT CASE WHEN COUNT(id) + 2 = 0 THEN 1 WHEN COUNT(id) + 2 = 1 THEN 2 WHEN COUNT(id) + 2 < 10 THEN LPAD(COUNT(id) + 2, 2, '0') WHEN COUNT(id) + 2 < 100 THEN LPAD(COUNT(id) + 2, 2, '0') ELSE COUNT(id) + 2 END AS contador FROM proyecto_enviado WHERE codigoUsuario IS NOT NULL;");
        
        $contador=$this->constructor->select__general__incentivo("SELECT LPAD(MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, '-', -2), '-', 1) AS UNSIGNED)) + 1,3,'0') AS contador FROM proyecto_enviado WHERE codigoUsuario IS NOT NULL;");

        foreach ($contador as $valor) {
            $contadorBd=$valor["contador"];
        }

        $codigo="041-01-PIT-". $this->anio."-".$contadorBd."-".$version;

        return $codigo;

    } 

    public function obtener__calificacionEstado($codigo) {

        $consulta=$this->constructor->select__general__incentivo("SELECT estadoCalificacion FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
        
        foreach ($consulta as $valor) {
            $estadoCalificacion=$valor["estadoCalificacion"];
        }


        return $estadoCalificacion;

    } 

     public function generarProyecto($codigo,$idCredencial,$tipo,$version) {

        if(!empty($codigo)){

            if ($tipo==="borrador") {
                $codigoEnviar=$codigo."__temporal";
            }else if($tipo==="observado"){

                $consultaCodigoEnviado=$this->constructor->select__general__incentivo("SELECT codigo,id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
                foreach ($consultaCodigoEnviado as $valor) {
                    $codigoString=$valor["codigo"];
                    $idEnviadoBd=$valor["id"];
                }

                $consultaCodigoEnviado=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS contador FROM proyecto_enviado_versiones WHERE idEnviado='$idEnviadoBd' GROUP BY idEnviado;");
                foreach ($consultaCodigoEnviado as $valor) {
                   $contadorBd=$valor["contador"];
                }

                if (empty($contadorBd)) {
                   $contadorBd= 2;
                }else{
                    $contadorBd=intval($contadorBd) + 2;
                }

                $array = explode("-", $codigoString);

                $codigoEnviar=$array[0]."-".$array[1]."-".$array[2]."-".$array[3]."-".$array[4]."-".$contadorBd;

            }else{
                $codigoEnviar=$this->codigoFinal__version__1($version);
            }


            $usuarioCredencial=$this->constructor->select__general("SELECT idCredencial FROM credencial WHERE usuario='$idCredencial';");
            foreach ($usuarioCredencial as $valor) {
                $idCredencial=$valor["idCredencial"];
            }

            $tipoOrganismo=self::obtener__informacion__informacionTipo($idCredencial);
            foreach ($tipoOrganismo as $valor) {
                $idTipoUsuarioBd=$valor["idTipoUsuario"];
                $tipoUsuario=$valor["nombre"];
            }


            if (intval($idTipoUsuarioBd)===1 || intval($idTipoUsuarioBd)===2) {

                $informacionUsuario=self::obtener__informacion__usuario($idCredencial);
                foreach ($informacionUsuario as $valor) {
                    $credencialProponente=$valor["cedula"];
                    $nombreProponente=$valor["nombre"];
                    $fechaNacimiento=$valor["fechaNacimiento"];
                    $sexo=$valor["sexo"];
                    $genero=$valor["genero"];
                    $estadoCivil=$valor["estadoCivil"];
                    $nacionalidad=$valor["nacionalidad"];
                    $discapacidad=$valor["discapacidad"];
                    $fecha=$valor["fecha"];
                    $hora=$valor["hora"];
                    $edadBd=$valor["edad"];
                }


            }else{

                $informacionUsuario=self::obtener__informacion__organismo($idCredencial);
                foreach ($informacionUsuario as $valor) {
                    $credencialProponente=$valor["ruc"];
                    $nombreProponente=$valor["razonSocial"];
                }


            }


            $dscripcionProyecto=self::descripcionProyecto($codigo);
            foreach ($dscripcionProyecto as $valor) {
                $nombreProyecto=$valor["nombre"];
                $fechaInicioProyecto=$valor["fechaInicio"];
                $fechaFinProyecto=$valor["fechaFin"];
                $tipo=$valor["tipo"];
                $diferenciaAnios=$valor["diferenciaAnios"];
                $objetivoGeneral=$valor["objetivoGeneral"];
                $justificacionProyectoOriginal=$valor["justificacionProyecto"];
            }


            $obtener__informacion__contacto=self::obtener__informacion__contacto($idCredencial);
            foreach ($obtener__informacion__contacto as $valor) {
                $celular1=$valor["celular1"];
                $celular2=$valor["celular2"];
                $email1=$valor["email1"];
                $email2=$valor["email2"];
                $idProvincia=$valor["idProvincia"];
                $depCanton=$valor["depCanton"];
                $depParroquia=$valor["depParroquia"];
                $direccion=$valor["callePrincipal"]." ".$valor["numeracion"]." ".$valor["calleSecundaria"];
            }


            $obtener__informacion__representante=self::obtener__informacion__representante($idCredencial);
            foreach ($obtener__informacion__representante as $valor) {
                $cedulaR=$valor["cedula"];
                $nombreR=$valor["nombre"];
                $sexoR=$valor["sexo"];
                $celular1R=$valor["celular1"];
                $celular2R=$valor["celular2"];
                $correo1R=$valor["correo1"];
                $correo2R=$valor["correo2"];
            }


            $arrayObjetivosE=array();

            $componentes__principal=self::componentes__principal__pdf($codigo);
            foreach ($componentes__principal as $valor) {
                array_push($arrayObjetivosE, $valor["que"]." ".$valor["como"]." ".$valor["paraQue"]);
            }



            $sectorObtenerNombre=self::sectorObtenerNombre($codigo);
            foreach ($sectorObtenerNombre as $valor) {
                $sector=$valor["idSector"];
                $nombreSector=$valor["nombre"];
            }


            $infraestructuraEscogido=self::infraestructuraEscogido($codigo);
            foreach ($infraestructuraEscogido as $valor) {
                $componentesPro=$valor["idComponentesProyecto"];
            }



            $infraestructuraEscogido__bienes=self::infraestructuraEscogido__compraBienesInmuebles($codigo);
            foreach ($infraestructuraEscogido__bienes as $valor) {
                $componentesPro__bienes=$valor["idComponentesProyecto"];
            }


            $justificacionObtener=self::justificacionObtener($codigo);
            foreach ($justificacionObtener as $valor) {
                $justificacion=$valor["nombre"];
            }

            $leyArray=array();
            $baseLegalArray=array();

            $baseLegal=self::baseLegal($codigo);
            foreach ($baseLegal as $valor) {
                array_push($leyArray, $valor["ley"]);
                array_push($baseLegalArray, $valor["baseLegal"]);
            }

            $nombreArray=array();
            $rangoArray=array();
            $generoArray=array();
            $autentificacionArray=array();
            $discapacidadArray=array();
            $cantidadArray=array();

            $beneficiariosSelector=self::beneficiariosSelector($codigo);
            foreach ($beneficiariosSelector as $valor) {
                array_push($nombreArray, $valor["idBeneficiario"]);
                array_push($rangoArray, $valor["idRango"]);
                array_push($generoArray, $valor["idGenero"]);
                array_push($autentificacionArray, $valor["idAutentificacion"]);
                array_push($discapacidadArray, $valor["idDiscapacidad"]);
                array_push($cantidadArray, $valor["cantidad"]);
            }

            $montoTotal=self::montoTotal($codigo);
            foreach ($montoTotal as $valor) {
                $totalPresupuestoBd=$valor["totalPresupuesto"];
            }

            $asignadorReal=strtolower($this->numerosLetras->toWords($totalPresupuestoBd));


            $objetivoEspecificoArray=array();
            $nombreIndicadorArray=array();
            $descripcionArray=array();
            $metodoCalculoArray=array();
            $metaFinalArray=array();
            $periodicidadArray=array();
            $medioVerificacionArray=array();

            $resultadosEsperados__obtener=self::resultadosEsperados__obtener($codigo);
            foreach ($resultadosEsperados__obtener as $valor) {

                array_push($objetivoEspecificoArray, $valor["objetivoEspecifico"]);
                array_push($nombreIndicadorArray, $valor["nombreIndicador"]);
                array_push($descripcionArray, $valor["descripcion"]);
                array_push($metodoCalculoArray, $valor["metodoCalculo"]);
                array_push($metaFinalArray, $valor["metaFinal"]);
                array_push($periodicidadArray, $valor["periodicidad"]);
                array_push($medioVerificacionArray, $valor["medioVerificacion"]);

            }

            $deportistaOrganismoArray=array();
            $disciplinaArray=array();
            $categoriaEdadArray=array();
            $eventoParticipacionArray=array();
            $pronosticoUbicacionArray=array();

            $obtenerPronosticos=self::obtenerPronosticos($codigo);
            foreach ($obtenerPronosticos as $valor) {

                array_push($deportistaOrganismoArray, $valor["deportistaOrganismo"]);
                array_push($disciplinaArray, $valor["disciplina"]);
                array_push($categoriaEdadArray, $valor["categoriaEdad"]);
                array_push($eventoParticipacionArray, $valor["eventoParticipacion"]);
                array_push($pronosticoUbicacionArray, $valor["pronosticoUbicacion"]);

            }


            $indicadorArray=array();
            $periodicidadArray=array();
            $actividadSeguimientoArray=array();
            $medioVerficiacionArray=array();
            $observacionArray=array();

            $seguimientoEvaluacion=self::seguimientoEvaluacion($codigo);
            foreach ($seguimientoEvaluacion as $valor) {

                array_push($indicadorArray, $valor["indicador"]);
                array_push($periodicidadArray, $valor["periodicidad"]);
                array_push($actividadSeguimientoArray, $valor["actividadSeguimiento"]);
                array_push($medioVerficiacionArray, $valor["medioVerficiacion"]);
                array_push($observacionArray, $valor["observacion"]);

            }

            if ((!empty($cedulaR) && intval($discapacidad)===1) || (!empty($cedulaR) && intval($edadBd)<18) || intval($idTipoUsuarioBd)===3 || intval($idTipoUsuarioBd)===3) {
               $nombreFirmar=$nombreR;
               $cedulaFirmar=$cedulaR;
               $indentificadorPr=true;
            }else{
               $nombreFirmar=$nombreProponente;
               $cedulaFirmar=$credencialProponente;
               $indentificadorPr=false;
            }

            /*==========================================
            =            Datos modificación            =
            ==========================================*/

            
            if($this->obtener__calificacionEstado($codigo)==="CALIFICADO"){

                $dscripcionProyectoModificacion=$this->modificacion->descripcionProyecto($codigo);
                foreach ($dscripcionProyectoModificacion as $valor) {
                    $fechaFinProyecto=$valor["fechaFin"];
                    $tipo=$valor["tipo"];
                    $diferenciaAnios=$valor["diferenciaAnios"];
                }

                $montoTotal=$this->modificacion->montoTotal($codigo);
                foreach ($montoTotal as $valor) {
                    $totalPresupuestoBd=$valor["totalPresupuesto"];
                }

                $asignadorReal=strtolower($this->numerosLetras->toWords($totalPresupuestoBd));

            }
            
            /*=====  End of Datos modificación  ======*/
            

            if($this->obtener__calificacionEstado($codigo)==="CALIFICADO"){

                $consultaSolicitudId=$this->modificacion->codigo__modificacion($codigo);
                foreach ($consultaSolicitudId as $valorCIdModificacion) {
                    $idSolicitudBd=$valorCIdModificacion["idSolicitud"];
                }
                $contenido.=$this->proyectoPdf->datos__generales__modificado($codigo,$idSolicitudBd);
                $contenido.=$this->proyectoPdf->presupuesto__modificacion__2($codigo,$idSolicitudBd);
                $contenido.=$this->proyectoPdf->declaracionVeracidad__modificacion($codigo,$idSolicitudBd);

            }else{

                $contenido=$this->proyectoPdf->portada($tipoUsuario,$credencialProponente,$nombreProponente, $this->fecha,$nombreProyecto,$codigoEnviar);
                $contenido.=$this->proyectoPdf->datosSolicitante($nombreProponente,$credencialProponente,$direccion,$celular1,$email1,$nombreR,$cedulaR,$celular1R,$correo1R,$tipoUsuario,$indentificadorPr);
                $contenido.=$this->proyectoPdf->descripcionProyecto($nombreProyecto,$tipo,$fechaInicioProyecto,$fechaFinProyecto,$objetivoGeneral,$arrayObjetivosE,$sector,$componentesPro,$nombreSector,$justificacionProyectoOriginal,$componentesPro__bienes);
                $contenido.=$this->proyectoPdf->baseLegal($leyArray,$baseLegalArray);
                $contenido.=$this->proyectoPdf->beneficiarios($nombreArray,$rangoArray,$generoArray,$autentificacionArray,$discapacidadArray,$cantidadArray);
                $contenido.=$this->proyectoPdf->presupuesto($totalPresupuestoBd,strtolower($asignadorReal),$codigo,$sector);
                $contenido.=$this->proyectoPdf->resultadosEsperados($objetivoEspecificoArray,$nombreIndicadorArray,$descripcionArray,$metodoCalculoArray,$metaFinalArray,$periodicidadArray,$medioVerificacionArray);
                if (count($deportistaOrganismoArray)>0) {
                    $contenido.=$this->proyectoPdf->pronosticos__resultados($deportistaOrganismoArray,$disciplinaArray,$categoriaEdadArray,$eventoParticipacionArray,$pronosticoUbicacionArray);
                }
                $contenido.=$this->proyectoPdf->seguimientoEvaluacion($indicadorArray,$periodicidadArray,$actividadSeguimientoArray,$medioVerficiacionArray,$observacionArray);
                $contenido.=$this->proyectoPdf->declaracionVeracidad($credencialProponente,$nombreProponente, $this->constructor->fecha__letras(),$nombreR);

            }

            
            $fileName = $codigo.'.pdf'; 
            $rutaS=$this->ruta.'pdfGenerados/proyecto/';
            $filePath = $this->ruta.'pdfGenerados/proyecto/' . $fileName; 

            $pdfResult = $this->constructor__basePdf->generatePdf($contenido, $filePath, $codigo);


            return array(
                'pdfResult' => $pdfResult,
                'codigo' => $codigoEnviar
            );

        }

    } 
   
   
    public function observable__obtener__documentos__versiones__observaciones__proyecto__array($codigo) {

        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
        foreach ($consulta as $valor) {
            $idEnviadoBd=$valor["id"];
        }


        $consulta__2=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS contador FROM proyecto_enviado_versiones WHERE idEnviado='$idEnviadoBd' AND tipo='rectificado' GROUP BY idEnviado;");
        foreach ($consulta__2 as $valor__2) {
            $contador=$valor__2["contador"];
        }

        $array=array();

        $contadorGlobal=1;

        for ($i=1; $i < ($contador + 1); $i++) { 

            $remote_file = $this->baseServidorFtp.'proyectos'.'/'.$codigo."-".($i+1)."__proyecto.pdf";
            $local_file =$codigo."-".($i+1)."__proyecto.pdf";

            $base64=$this->constructor->sftp__servicios($remote_file,$local_file);


            $resul=$base64."__".($i+1);
            array_push($array, $resul);

            $contadorGlobal++;

        }



        $consulta__3=$this->constructor->select__general__incentivo("SELECT a.idSolicitud FROM proyecto_enviado_versiones AS a INNER JOIN proyecto_modificacion_solicitud AS b ON a.idSolicitud=b.idSolicitud WHERE b.codigo='$codigo' AND b.estadoModificacion='APROBADO';");
        foreach ($consulta__3 as $valor__3) {

            $remote_file = $this->baseServidorFtp.'proyectos_modificacion'.'/'.$codigo."__".$valor__3["idSolicitud"]."__modificacion.pdf";
            $local_file =$codigo."__".$valor__3["idSolicitud"]."__modificacion.pdf";


            $base64=$this->constructor->sftp__servicios($remote_file,$local_file);
            $resul=$base64."__".($contadorGlobal + 1);
            array_push($array, $resul);

            $contadorGlobal++;

        }


        return $array;

    } 


    public function obtenerExistente__r__obtener__documentos($docu,$carpeta) {

        if(!empty($docu)){
            $remote_file = $this->baseServidorFtp__archivos.$carpeta.'/'.$docu;
            $local_file = $docu;

            return $this->constructor->sftp__servicios($remote_file,$local_file);
            // return $this->constructor->select__archivo__natural__ruta('repositorio/incentivo2.0/seguimiento/'.$docu);
        }


    } 

    public function obtenerExistente__r($docu,$carpeta) {

        $remote_file = $this->baseServidorFtp.$carpeta.'/'.$docu;
        $local_file = $docu;

        return $this->constructor->sftp__servicios($remote_file,$local_file);

    } 

    public function obtenerExistente($ruta) {
       return $this->constructor->select__archivo__natural__ruta('../../react/firmaElectronica/documentos/'.$ruta);
       // return $this->constructor->select__archivo__natural__ruta('https://servicios.deporte.gob.ec/poa2/firmaRepositorio/documentos/'.$ruta);
    } 

    public function enviarCodigoFinal($codigo, $codigoEnviado, $idCredencial) {

        if (!empty($idCredencial)) {

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto SET estado='E' WHERE codigo='$codigo';");

            $consulta = $this->constructor->select__general__incentivo("SELECT codigo FROM proyecto_enviado WHERE codigo LIKE '041-01-PIT-" . $this->anio . "-%' ORDER BY id DESC LIMIT 1;");
            if (!empty($consulta)) {
                $ultimoCodigo = $consulta[0]["codigo"];
                $partes = explode("-", $ultimoCodigo);
                $numeroAIncrementar = (int)$partes[4];
                $numeroAIncrementar += 1;
            } else {
                $numeroAIncrementar = 1;
            }

            $formatted_count = str_pad($numeroAIncrementar, 3, '0', STR_PAD_LEFT);

            $codigoFActualizado = "041-01-PIT-" . $this->anio . "-" . $formatted_count . "-1";

            $consulta_existente = $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigoFActualizado';");
            if (!empty($consulta_existente)) {
                return "Error: código duplicado generado ($codigoFActualizado). Intente de nuevo.";
            }

            $consulta__validar = $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
            foreach ($consulta__validar as $valorValidar) {
                $idValidar = $valorValidar["id"];
            }

            $this->administradorController->enviarCorreo__general($idCredencial, $codigoFActualizado);

            if (empty($idValidar)) {
                return $this->constructor->inserta__general__incentivo("proyecto_enviado", ['codigo', 'codigoUsuario', 'idCredencial', 'fecha', 'hora'], array(
                    ':codigo' => $codigoFActualizado,
                    ':codigoUsuario' => $codigo,
                    ':idCredencial' => $idCredencial,
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                ));
            }
        }
    }

}



