<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Application\Auth\Auth;
use App\Presentation\Controllers\ControllersBandeja;
use App\Presentation\Pdf\Base;
use App\Presentation\Pdf\InformeC;
use App\Presentation\Pdf\InformeSeguimiento;

class ControllersSeguimiento {

    private $fecha;
    private $hora;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->constructor = ServicesAdmin::getInstance();
        $this->constructor__auth =Auth::getInstance();
        $this->ruta='../repositorio/incentivo2.0/';
        // $this->ruta='repositorio/incentivo2.0/';

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

        $this->constructor__basePdf = Base::getInstance();

        $this->bandeja = new ControllersBandeja();


        $this->informePdf = InformeC::getInstance();
        $this->informePdf__seguimiento = InformeSeguimiento::getInstance();



    }


    public function enviarInformacionSeguimientoFinal($post){

      $archivo=$post["archivo"];
      $codigo=$post["codigo"];
      $idCredencial=$post["idCredencial"];
      
      $nombreArchivo=$codigo."__informe__v1__analista.pdf";
      $rutaDefinitiva=$this->ruta."seguimiento/";

      $rastreo=$this->constructor->archivoCargar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['size'],$rutaDefinitiva,$nombreArchivo);

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_documentos_v1 SET estado='T',informeFinalAnalista='$nombreArchivo',idAnalista='$idCredencial' WHERE codigo='$codigo';");


      return 1;

    }


    public function buscar__proyecto__existente__v1__seguimiento($post){

      $input=$post["input"];

      return $this->constructor->select__general__talento("SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto,a.codigo,IFNULL((SELECT CONCAT_WS(' ','SI') FROM incentivo.proyecto_seguimiento_documentos_v1 AS a1 WHERE a1.codigo=a.codigo),'NO') AS comparado,IFNULL((SELECT IF(a1.estado='E','ENVIADO',IF(a1.estado='C','REVISADO','N/A')) FROM incentivo.proyecto_seguimiento_documentos_v1 AS a1 WHERE a1.codigo=a.codigo AND a1.estado IS NOT NULL),'PENDIENTE ENVIAR') AS estado FROM pro_proyecto AS a INNER JOIN pro_certificacion_seguimiento AS b ON a.codigo=b.codigo WHERE a.codigo='$input'  GROUP BY a.codigo;");

    }


    public function obtener__proyectos__aprobados__codigo__v1__seguimiento($post){

      $idCredencial=$post["idCredencial"];


      $consulta=$this->constructor->select__general("SELECT IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial)) AS usuario FROM credencial AS a WHERE a.idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $usuario=$valor["usuario"];
      }

      return $this->constructor->select__general__talento("SELECT a.codigo FROM pro_proyecto AS a INNER JOIN pro_certificacion_seguimiento AS b ON a.codigo=b.codigo WHERE a.codigo LIKE '%$usuario%'  GROUP BY a.codigo;");

    }

    public function proyectos__aprobados__nombres__v1__seguimiento($post){

      $idCredencial=$post["idCredencial"];


      $consulta=$this->constructor->select__general("SELECT IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial)) AS usuario FROM credencial AS a WHERE a.idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $usuario=$valor["usuario"];
      }

      return $this->constructor->select__general__talento("SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombreProyecto FROM pro_proyecto AS a INNER JOIN pro_certificacion_seguimiento AS b ON a.codigo=b.codigo WHERE a.codigo LIKE '%$usuario%'  GROUP BY a.codigo;");

    }

    public function informe__tecnico__seguimiento($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];
      $tipo=$post["tipo"];
      
      $contenido=$this->informePdf__seguimiento->portada__informe__seguimiento__tecnico($this->informacion__general__proyecto__seguimiento($codigoUsuario),$tipo);
      $contenido.=$this->informePdf__seguimiento->antecedentes__informe__tecnico($this->informacion__general__proyecto__seguimiento($codigoUsuario),$tipo);
      $contenido.=$this->informePdf__seguimiento->base__legal__informe__tecnico();
      $contenido.=$this->informePdf__seguimiento->informacion__general__del__proyecto($this->informacion__general__proyecto__seguimiento($codigoUsuario));
      $contenido.=$this->informePdf__seguimiento->analisis__de__resultados($this->informacion__general__proyecto__seguimiento__recomendacion($codigoUsuario,$tipo));
      $contenido.=$this->informePdf__seguimiento->firma__de__resultados($this->informacion__general__proyecto__seguimiento__recomendacion($codigoUsuario,$tipo));

      $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

      return $pdfResult;

    } 

    public function informacion__general__proyecto__seguimiento__recomendacion($codigo,$tipo) {

      return $this->constructor->select__general__incentivo("SELECT (SELECT CONCAT_WS(' ', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'), REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idUsuario) AS nombreAnalista,(SELECT CONCAT_WS(' ', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'), REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idUsuario2) AS nombreDirector,a.fechaEjecucionProyecto,a.fechaEjecucionProyectoObservacion,a.objetivosAlineacion,a.objetivosAlineacionObservacion,a.beneficiariosProyecto,a.beneficiariosProyectoObservacion,a.presupuestoProyecto,a.presupuestoProyectoObservacion,a.metasIndicadores,a.metasIndicadoresObservacion,a.contribucionProyecto,a.contribucionProyectoObservacion,a.conclusiones,a.recomendaciones,a.estado,a.observaRegresa FROM proyecto_enviado_recomendacion_seguimiento AS a WHERE a.codigo='$codigo' AND a.tipo='$tipo';");

    }


    public function activar__informe__seguimiento($codigo) {

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT id AS idTecnico FROM proyecto_enviado_recomendacion_seguimiento WHERE codigo='$codigo' AND tipo='TECNICO';");
      foreach ($consulta as $valor) {
        $idTecnico=$valor["idTecnico"];
      }

      $consulta__2=$this->constructor->select__general__incentivo("SELECT id AS idINFRA FROM proyecto_enviado_recomendacion_seguimiento WHERE codigo='$codigo' AND tipo='INFRA';");
      foreach ($consulta__2 as $valor__2) {
        $idINFRA=$valor__2["idINFRA"];
      }

      if(empty($idTecnico)){
        $variableTecnico=0;
      }else{
        $variableTecnico=1;
      }

      if(empty($idINFRA)){
        $variableInfra=0;
      }else{
        $variableInfra=1;
      }

      array_push($array, $variableTecnico);
      array_push($array, $variableInfra);

      return $array;

    }

    public function bandeja__recomendados__comite($post) {

      $idCredencial=$post["idCredencial"];
      $idRol=$post["idRol"];
      $fisicamenteEstructura=$post["fisicamenteEstructura"];

      $idUsuario=$this->obtener__id__funcionario($idCredencial);

      return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, FORMAT(SUM(d.total), 2) AS monto,IFNULL(a.fechaCalifica,'2024-09-12') AS fechaCalifica,( SELECT a1.fecha FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id AND MONTH(a1.fecha) = MONTH(CURRENT_DATE) ORDER BY a1.id DESC LIMIT 1 ) AS fechaEnviaComite  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' WHERE estadoSeguimiento='TF' AND EXISTS ( SELECT a1.id FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id AND MONTH(a1.fecha) = MONTH(CURRENT_DATE) ORDER BY a1.id DESC LIMIT 1 )  GROUP BY c.codigo,d.codigo;");

    } 


    public function existe__componentes__infra($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto FROM proyecto_componente_usuario WHERE (idComponentes='5' OR idComponentes='7') AND codigo='$codigo';");
      foreach ($consulta as $valor) {
        $idComponentesProyecto=$valor["idComponentesProyecto"];
      }

      if(empty($idComponentesProyecto)){
        return 0;
      }else{
        return 1;
      }

    }

    public function insertaReenvio__seguimiento__regresar($post) {

      $tipoUsuario=$post["tipo"];
      $codigo=$post["codigo"];
      $codigoUsuario=$post["codigoUsuario"];
      $personaReasignar=$post["personaReasignar"];
      $idCredencial=$post["idCredencial"];
      $idRolActual=$post["idRol"];
      $fisicamenteEstructuraActual=$post["fisicamenteEstructura"];
      $estadoEnvia=$post["estadoEnvia"];
      $textoRegresar=$post["textoRegresar"];

      $estadoReenvio;

      foreach ($this->obtener__id__enviado__proyecto($codigoUsuario) as $valor) {
        $idEnviado=$valor["id"];
      }

      if(intval($fisicamenteEstructuraActual)===15){
        $tipoAsignado="INFRA";
      }else{
        $tipoAsignado="TECNICO";
      }

      $estadoReenvio="DEVUELTO EN ETAPA DE SEGUIMIENTO";

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idAnalistaSeguimiento='$personaReasignar',estadoSeguimiento='EA' WHERE id='$idEnviado';");
      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado_recomendacion_seguimiento SET observaRegresa='$textoRegresar',estado='O'  WHERE idEnviado='$idEnviado' AND  tipo='$tipoAsignado';");

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio'],array(':idFisicamenteActual' => $fisicamenteEstructuraActual,':idUsuarioActual' => $this->obtener__id__funcionario($idCredencial),':idFisicamenteNuevo' => $fisicamenteEstructuraActual,':idUsuarioNuevo' => $personaReasignar,':idEnviado' =>$idEnviado,':tipo' => $estadoReenvio,':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $textoRegresar));

    } 
    public function insertaReenvio($post) {

      $tipoUsuario=$post["tipo"];
      $codigo=$post["codigo"];
      $codigoUsuario=$post["codigoUsuario"];
      $personaReasignar=$post["personaReasignar"];
      $idCredencial=$post["idCredencial"];
      $idRolActual=$post["idRol"];
      $fisicamenteEstructuraActual=$post["fisicamenteEstructura"];
      $estadoEnvia=$post["estadoEnvia"];
      $textoRegresar=$post["textoRegresar"];

      $estadoReenvio;

      foreach ($this->obtener__id__enviado__proyecto($codigoUsuario) as $valor) {
        $idEnviado=$valor["id"];
      }

      if(intval($fisicamenteEstructuraActual)===15){
        $tipoAsignado="INFRA";
      }else{
        $tipoAsignado="TECNICO";
      }


      if((intval($idRolActual)===2 && $estadoEnvia==="RECIBIDO") || (intval($idRolActual)===2 && $estadoEnvia==="RECIBIDO AREA TÉCNICA" && intval($fisicamenteEstructuraActual)===15)){

        $estadoReenvio="REASIGNADO ETAPA DE SEGUIMIENTO";
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idAnalistaSeguimiento='$personaReasignar',estadoSeguimiento='EA' WHERE id='$idEnviado';");

      }else if(intval($idRolActual)===3 || intval($idRolActual)===13){

        $estadoReenvio="RECOMENDADO ANALISTA EN ETAPA DE SEGUIMIENTO";
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idAnalistaSeguimiento='0',estadoSeguimiento='R' WHERE id='$idEnviado';");
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_enviado_recomendacion_seguimiento WHERE idEnviado='$idEnviado' AND  tipo='$tipoAsignado';");

        $this->constructor->inserta__general__incentivo("proyecto_enviado_recomendacion_seguimiento",['idEnviado','idUsuario','idRol','texto','fecha','hora','tipo','codigo','fechaEjecucionProyecto','fechaEjecucionProyectoObservacion','objetivosAlineacion','objetivosAlineacionObservacion','beneficiariosProyecto','beneficiariosProyectoObservacion','presupuestoProyecto','presupuestoProyectoObservacion','metasIndicadores','metasIndicadoresObservacion','contribucionProyecto','contribucionProyectoObservacion','conclusiones','recomendaciones','estado'],array(':idEnviado' => $idEnviado,':idUsuario' => $this->obtener__id__funcionario($idCredencial),':idRol' => $idRolActual,':texto' => 'N/A',':fecha' =>$this->fecha,':hora' => $this->hora,':tipo' => $tipoAsignado,':codigo' => $codigoUsuario,':fechaEjecucionProyecto' => $post["fechaEjecucionProyecto"],':fechaEjecucionProyectoObservacion' => $post["fechaEjecucionProyectoObservacion"],':objetivosAlineacion' => $post["objetivosAlineacion"],':objetivosAlineacionObservacion' => $post["objetivosAlineacionObservacion"],':beneficiariosProyecto' => $post["beneficiariosProyecto"],':beneficiariosProyectoObservacion' => $post["beneficiariosProyectoObservacion"],':presupuestoProyecto' => $post["presupuestoProyecto"],':presupuestoProyectoObservacion' => $post["presupuestoProyectoObservacion"],':metasIndicadores' => $post["metasIndicadores"],':metasIndicadoresObservacion' => $post["metasIndicadoresObservacion"],':contribucionProyecto' => $post["contribucionProyecto"],':contribucionProyectoObservacion' => $post["contribucionProyectoObservacion"],':conclusiones' => $post["conclusiones"],':recomendaciones' => $post["recomendaciones"],':estado' => 'A'));

      }else if(intval($idRolActual)===2 && $estadoEnvia==="RECOMENDADO"){

        $obtenidoComparacion=$this->existe__componentes__infra($codigoUsuario);

        if ($obtenidoComparacion===1 && intval($fisicamenteEstructuraActual)!==15) {
          $estadoReenvio="RECOMENDADO INFRAESTRUCUTRA ETAPA DE SEGUIMIENTO";
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idAnalistaSeguimiento='0',estadoSeguimiento='EI' WHERE id='$idEnviado';");
        }else{
          $estadoReenvio="PROYECTO CULMINADO";
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idAnalistaSeguimiento='0',estadoSeguimiento='TF' WHERE id='$idEnviado';");
        }

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado_recomendacion_seguimiento SET idUsuario2='".$this->obtener__id__funcionario($idCredencial)."',idRol2='".$idRolActual."',fecha2='".$this->fecha."',hora2='".$this->hora."'  WHERE idEnviado='$idEnviado' AND  tipo='$tipoAsignado';");

      }

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio'],array(':idFisicamenteActual' => $fisicamenteEstructuraActual,':idUsuarioActual' => $this->obtener__id__funcionario($idCredencial),':idFisicamenteNuevo' => $fisicamenteEstructuraActual,':idUsuarioNuevo' => $personaReasignar,':idEnviado' =>$idEnviado,':tipo' => $estadoReenvio,':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $textoRegresar));

    } 

    public function obtener__id__funcionario($idCredencial){

      $funcionarioEnviado=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($funcionarioEnviado as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $idUsuarioBd;

    }


    public function orden__recibidos($orden,$idRol,$idUsuario,$fisicamenteEstructura) {


      if (intval($orden)===1 && (intval($idRol)===2 && intval($fisicamenteEstructura)===15)) {
        return "(idFisicamente IS NULL OR idFisicamente='$fisicamenteEstructura' AND a.estadoCalificacion IS NULL AND a.idUsuario IS NULL) AND (idFisicamenteN<>'$fisicamenteEstructura' OR idFisicamenteN IS NULL AND a.estadoCalificacion IS NULL  AND a.idUsuario IS NULL) OR (a.estadoCalificacion='ENVIADO INFRA' AND a.estadoCalificacion IS NOT NULL)";
      }else if (intval($orden)===1 && (intval($idRol)===7 || intval($idRol)===2 || intval($idRol)===4)) {
        return "(idFisicamente IS NULL OR idFisicamente='$fisicamenteEstructura' AND a.estadoCalificacion IS NULL AND a.idUsuario IS NULL) AND (idFisicamenteN<>'$fisicamenteEstructura' OR idFisicamenteN IS NULL AND a.estadoCalificacion IS NULL  AND a.idUsuario IS NULL)";
      }else if(intval($orden)===2 && intval($idRol)===2){
        return "idFisicamente='$fisicamenteEstructura' AND idFisicamenteN!='$fisicamenteEstructura'";
      }else if(intval($orden)===2 && intval($idRol)===3){
        return "idUsuario='$idUsuario' AND idFisicamenteN!='$fisicamenteEstructura'";
      }else{
        return "idUsuario='$idUsuario'";
      }

    }


    public function orden__obtenido($idRol,$fisicamenteEstructura) {

      $ordenBase=$this->constructor->select__general("SELECT b.orden FROM nivel_rol_fisicamente AS a INNER JOIN nivel_rol AS b ON a.idNivel=b.idNivel WHERE a.idFisicamente='$fisicamenteEstructura' AND a.idRol='$idRol';");

      foreach ($ordenBase as $valor) {
        $ordenBd=$valor["orden"];
      }

      return $ordenBd;

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

    public function bandeja__recibidos__seguimiento($idCredencial,$idRol,$fisicamenteEstructura) {

      $ordenBd=$this->orden__obtenido($idRol,$fisicamenteEstructura);

      $funcionarioEnviado=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($funcionarioEnviado as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      $consultaOrden=$this->orden__recibidos($ordenBd,$idRol,$idUsuarioBd,$fisicamenteEstructura);
      $consultaSectores=$this->sectores__recibidos($fisicamenteEstructura);

      if(intval($idRol)===4 && intval($fisicamenteEstructura)===1){

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, (SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha,IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA')) AS estado,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,IF(a.estadoSeguimiento='E','RECIBIDO',IF(a.estadoSeguimiento='EI','RECIBIDO AREA TÉCNICA',IF(a.estadoSeguimiento='R' OR a.estadoSeguimiento='RI','RECOMENDADO',IF(a.idAnalistaSeguimiento IS NOT NULL OR a.idAnalistaSeguimiento='0','ENVIADO A ANALISTA','SN')))) AS estadoSeguimiento,IF(a.idAnalistaSeguimiento IS NULL,'PENDIENTE ASIGNAR',IF(a.idAnalistaSeguimiento='0','RECOMENDADO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idAnalistaSeguimiento ORDER BY a1.id_usuario LIMIT 1))) AS nombreCompletoSeguimiento FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE a.estadoSeguimiento IS NOT NULL AND (a.estadoSeguimiento='E' OR a.estadoSeguimiento='EI' OR a.estadoSeguimiento='R' OR a.estadoSeguimiento='RI' OR a.estadoSeguimiento='EA') AND $consultaSectores AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigoUsuario;");

      }else if(intval($idRol)===2 && intval($fisicamenteEstructura)===15){

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, (SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha,IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA')) AS estado,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,IF((a.estadoSeguimiento IS NULL OR a.estadoSeguimiento!='EI') AND a.estadoSeguimiento!='EA' AND a.estadoSeguimiento!='R' AND (SELECT a1.id FROM proyecto_sector AS a1 WHERE a1.codigo=a.codigoUsuario LIMIT 1) IS NOT NULL,'PENDIENTE REVISIÓN TÉCNICA',IF(a.estadoSeguimiento='E','RECIBIDO',IF(a.estadoSeguimiento='EI','RECIBIDO AREA TÉCNICA',IF(a.estadoSeguimiento='R' OR a.estadoSeguimiento='RI','RECOMENDADO',IF(a.idAnalistaSeguimiento IS NOT NULL OR a.idAnalistaSeguimiento='0','ENVIADO A ANALISTA','SN'))))) AS estadoSeguimiento,IF(a.idAnalistaSeguimiento IS NULL,'PENDIENTE ASIGNAR',IF(a.idAnalistaSeguimiento='0','RECOMENDADO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idAnalistaSeguimiento ORDER BY a1.id_usuario LIMIT 1))) AS nombreCompletoSeguimiento FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE a.estadoSeguimiento IS NOT NULL AND (a.estadoSeguimiento='E' OR a.estadoSeguimiento='EI' OR a.estadoSeguimiento='R' OR a.estadoSeguimiento='RI' OR a.estadoSeguimiento='EA') AND $consultaSectores AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigoUsuario;");

      }else if(intval($idRol)===7 || intval($idRol)===2){

        return $this->constructor->select__general__incentivo("SELECT a.estadoCalificacion,a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion IS NULL,'RECIBIDO',(SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',IF(a1.tipo='Devuelto superior inmediato en etapa de calificación','DEVUELTO',IF(a.estadoCalificacion='ENVIADO INFRA','ENVIADO A INFRAESTRUCTURA',IF(a.estadoCalificacion='Recomendado','RECOMENDADO',''))))))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1)) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo,IF(a.estadoSeguimiento='E','RECIBIDO',IF(a.estadoSeguimiento='EI','RECIBIDO AREA TÉCNICA',IF(a.estadoSeguimiento='R' OR a.estadoSeguimiento='RI','RECOMENDADO',IF(a.idAnalistaSeguimiento IS NOT NULL OR a.idAnalistaSeguimiento='0','ENVIADO A ANALISTA','SN')))) AS estadoSeguimiento,IF(a.idAnalistaSeguimiento IS NULL,'PENDIENTE ASIGNAR',IF(a.idAnalistaSeguimiento='0','RECOMENDADO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idAnalistaSeguimiento ORDER BY a1.id_usuario LIMIT 1))) AS nombreCompletoSeguimiento FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE a.estadoSeguimiento IS NOT NULL AND (a.estadoSeguimiento='E' OR a.estadoSeguimiento='R' OR a.estadoSeguimiento='EA') AND $consultaSectores AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_recomendacion_seguimiento AS a1 WHERE a1.idEnviado=a.id AND a1.idUsuario2 IS NOT NULL) GROUP BY a.codigoUsuario;");

      }else{

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion IS NULL,'RECIBIDO',(SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',IF(a1.tipo='Devuelto superior inmediato en etapa de calificación','DEVUELTO',''))))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1)) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo,IF(a.estadoSeguimiento='E','RECIBIDO',IF(a.estadoSeguimiento='EI','RECIBIDO AREA TÉCNICA',IF(a.estadoSeguimiento='R' OR a.estadoSeguimiento='RI','RECOMENDADO',IF(a.idAnalistaSeguimiento IS NOT NULL OR a.idAnalistaSeguimiento='0','ENVIADO A ANALISTA','SN')))) AS estadoSeguimiento,IF(a.idAnalistaSeguimiento IS NULL,'PENDIENTE ASIGNAR',IF(a.idAnalistaSeguimiento='0','RECOMENDADO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idAnalistaSeguimiento ORDER BY a1.id_usuario LIMIT 1))) AS nombreCompletoSeguimiento  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE idAnalistaSeguimiento='$idUsuarioBd' AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigoUsuario;");

      }


    } 

    public function generar__informe__de__seguimiento($codigo) {
      
      $contenido=$this->informePdf__seguimiento->portada__informe__seguimiento($this->informacion__general__proyecto__seguimiento($codigo));
      $contenido.=$this->informePdf__seguimiento->datosGenerales_informe__seguimiento($this->informacion__general__proyecto__seguimiento($codigo));
      $contenido.=$this->informePdf__seguimiento->resumen_informe__seguimiento($this->informacion__general__proyecto__seguimiento($codigo),$this->recuperar__resumen($codigo));
      $contenido.=$this->informePdf__seguimiento->objetivo_informe__seguimiento($this->informacion__general__proyecto__seguimiento($codigo),$this->recuperar__objetivo__general($codigo),$this->objetivos__especificos($codigo));
      $contenido.=$this->informePdf__seguimiento->beneficiarios_informe__seguimiento($this->beneficiarios__seguimiento($codigo),$this->obtener__informacion__seguimiento__estados($codigo));
      $contenido.=$this->informePdf__seguimiento->presupuesto_informe__seguimiento($this->componentes__montos__asignados($codigo),$this->componentes__montos__asignados__femenino($codigo),$this->componentes__montos__asignados__priorizado($codigo),$this->obtener__informacion__montos__certificacion($codigo));
      $contenido.=$this->informePdf__seguimiento->seguimiento_informe__seguimiento($this->seguimiento__obtener__seguimiento($codigo),$this->obtener__informacion__seguimiento__estados($codigo));
      $contenido.=$this->informePdf__seguimiento->footer_informe__seguimiento($this->obtener__informacion__seguimiento__estados($codigo),$this->informacion__general__proyecto__seguimiento($codigo));
      $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

      return $pdfResult;

    }    

    public function obtener__informacion__montos__certificacion($codigo){

      foreach ($this->sumaProyectoTotal($codigo) as $valor) {
        $totalCalificado=$valor["totalSuma"];
      }

      $sumaCertificacionPendiente=$this->sumaCertificacionPendiente($codigo) ;
      $sumaCertificacionAprobada=$this->sumaCertificacionAprobada($codigo);

      $sumaPor__certificar=0;
      $sumaPor__certificar=floatval($totalCalificado) - (floatval($sumaCertificacionAprobada) + floatval($sumaCertificacionPendiente));

      return [$totalCalificado,$sumaCertificacionPendiente,$sumaCertificacionAprobada,$sumaPor__certificar];

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

    public function guardar__general__textos($post) {

      $codigo=$post["codigo"];
      $texto=$post["texto"];
      $actualizador=$post["actualizador"];
      $campoTexto=$post["campoTexto"];


      return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_estados SET $actualizador='A',$campoTexto='$texto'  WHERE codigo='$codigo';");
   
    }


    public function guardar__seguimiento__matriz($post) {

      $codigo=$post["codigo"];
      $justificacion__seguimiento=$post["justificacion__seguimiento"];

      $indicadorArray=json_decode($post["indicadorArray"], true);
      $periodicidadArray=json_decode($post["periodicidadArray"], true);
      $actividadSeguimientoArray=json_decode($post["actividadSeguimientoArray"], true);
      $medioVerficiacionArray=json_decode($post["medioVerficiacionArray"], true);
      $idSeguimientoArray=json_decode($post["idSeguimientoArray"], true);

       $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_informe_seguimiento WHERE codigo='$codigo';");

      foreach ($idSeguimientoArray as $clave => $valor) {
        
        $this->constructor->inserta__general__incentivo("proyecto_seguimiento_informe_seguimiento", ['codigo','idSeguimiento','indicadorArray','periodicidadArray','actividadSeguimientoArray','medioVerficiacionArray','fecha','hora'], array(
            ':codigo' =>$codigo,
            ':idSeguimiento' =>$valor,
            ':indicadorArray' =>$indicadorArray[$clave],
            ':periodicidadArray' =>$periodicidadArray[$clave],
            ':actividadSeguimientoArray' =>$actividadSeguimientoArray[$clave],
            ':medioVerficiacionArray' =>$medioVerficiacionArray[$clave],
            ':fecha' =>  $this->fecha,
            ':hora' =>  $this->hora,
        ));

      }

      return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_estados SET seguimientoControl='A',justificacion__seguimiento='$justificacion__seguimiento'  WHERE codigo='$codigo';");
   
    }


    public function guardar__presupuesto($post) {

      $codigo=$post["codigo"];
      $montoCertificadoEnviado=$post["montoCertificadoEnviado"];

      $sumador=0;

      if(!empty($codigo)){

        $montoCertificadoArray=json_decode($post["montoCertificadoArray"], true);
        $idPresupuestoArray=json_decode($post["idPresupuestoArray"], true);

        foreach ($montoCertificadoArray as $valor) {
          $sumador=floatval($valor) + floatval($sumador);
        }

        if(floatval($sumador)===floatval($montoCertificadoEnviado)){

          $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_informe_presupuesto WHERE codigo='$codigo';");

         
          foreach ($idPresupuestoArray as $clave => $valor) {
            
            $this->constructor->inserta__general__incentivo("proyecto_seguimiento_informe_presupuesto", ['codigo','idPresupuesto','montoCertificadoArray','fecha','hora'], array(
                ':codigo' =>$codigo,
                ':idPresupuesto' =>$valor,
                ':montoCertificadoArray' =>$montoCertificadoArray[$clave],
                ':fecha' =>  $this->fecha,
                ':hora' =>  $this->hora,
            ));

          }

          return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_estados SET presupuestoProyecto='A'  WHERE codigo='$codigo';");

        }else{
          return 2;
        }

   
      }


    }


    public function guardar__beneficiarios($post) {


      $codigo=$post["codigo"];
      $justificacion__beneficiarios=$post["justificacion__beneficiarios"];

      if (!empty($codigo)) {

        $beneficiarioArray=json_decode($post["beneficiarioArray"], true);
        $edadArray=json_decode($post["edadArray"], true);
        $generoArray=json_decode($post["generoArray"], true);
        $autentificacionArray=json_decode($post["autentificacionArray"], true);
        $discapacidadArray=json_decode($post["discapacidadArray"], true);
        $cantidadArray=json_decode($post["cantidadArray"], true);
        $idBeneficiariosArray=json_decode($post["idBeneficiariosArray"], true);

        
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_informe_beneficiario WHERE codigo='$codigo';");

        foreach ($idBeneficiariosArray as $clave => $valor) {
          
          $this->constructor->inserta__general__incentivo("proyecto_seguimiento_informe_beneficiario", ['codigo','idBeneficiario','beneficiarioArray','edadArray','generoArray','autentificacionArray','discapacidadArray','cantidadArray','fecha','hora'], array(
              ':codigo' =>$codigo,
              ':idBeneficiario' =>$valor,
              ':beneficiarioArray' =>$beneficiarioArray[$clave],
              ':edadArray' =>$edadArray[$clave],
              ':generoArray' =>$generoArray[$clave],
              ':autentificacionArray' =>$autentificacionArray[$clave],
              ':discapacidadArray' =>$discapacidadArray[$clave],
              ':cantidadArray' =>$cantidadArray[$clave],
              ':fecha' =>  $this->fecha,
              ':hora' =>  $this->hora,
          ));

        }

        return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_estados SET beneficiarios='A',justificacion__beneficiarios='$justificacion__beneficiarios' WHERE codigo='$codigo';");

      }
   
    }

    public function guardar__objetivos__generales($post) {

      $codigo=$post["codigo"];
      $cumpleObjetivoGeneral=$post["cumpleObjetivoGeneral"];
      $justificacionObjetivoGeneral=$post["justificacionObjetivoGeneral"];

      $cumpleObjetivoGeneralArray=json_decode($post["cumpleObjetivoGeneralArray"], true);
      $justificacionArray=json_decode($post["justificacionArray"], true);
      $idObjetivoGenera=json_decode($post["idObjetivoGenera"], true);

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_estados SET objetivo='A' WHERE codigo='$codigo';");

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_informe_objetivo_especifico WHERE codigo='$codigo';");
      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_informe_objetivo_general WHERE codigo='$codigo';");

      foreach ($idObjetivoGenera as $clave => $valor) {
        
        $this->constructor->inserta__general__incentivo("proyecto_seguimiento_informe_objetivo_especifico", ['codigo','idComponentesProyecto','cumple','justificacion','fecha','hora'], array(
            ':codigo' =>$codigo,
            ':idComponentesProyecto' =>$valor,
            ':cumple' =>$cumpleObjetivoGeneralArray[$clave],
            ':justificacion' =>$justificacionArray[$clave],
            ':fecha' =>  $this->fecha,
            ':hora' =>  $this->hora,
        ));

      }

      return $this->constructor->inserta__general__incentivo("proyecto_seguimiento_informe_objetivo_general", ['codigo','cumple','justificacion','fecha','hora'], array(
          ':codigo' =>$codigo,
          ':cumple' =>$cumpleObjetivoGeneral,
          ':justificacion' =>$justificacionObjetivoGeneral,
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
      ));

    }

    public function guardar__resumen($post) {

      $fechaInicio=$post["fechaInicio"];
      $fechaFin=$post["fechaFin"];
      $justificacion=$post["justificacion"];
      $codigo=$post["codigo"];

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_estados WHERE codigo='$codigo';");

      $this->constructor->inserta__general__incentivo("proyecto_seguimiento_estados", ['codigo','resumen','fecha','hora'], array(
          ':codigo' =>$codigo,
          ':resumen' =>'A',
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
      ));

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_seguimiento_informe_resumen WHERE codigo='$codigo';");

      return $this->constructor->inserta__general__incentivo("proyecto_seguimiento_informe_resumen", ['fechaInicio','fechaFin','justificacion','codigo','fecha','hora'], array(
          ':fechaInicio' =>$fechaInicio,
          ':fechaFin' =>$fechaFin,
          ':justificacion' =>$justificacion,
          ':codigo' =>$codigo,
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
      ));

    }

    public function obtener__informacion__seguimiento__estados($codigo) {

      return $this->constructor->select__general__incentivo("SELECT resumen,objetivo,beneficiarios,presupuestoProyecto,seguimientoControl,contribucionProyecto,conclusiones,recomendaciones,justificacion__beneficiarios,justificacion__seguimiento,contribucionProyectoTexto,conclusionesTexto,recomendacionesTexto FROM proyecto_seguimiento_estados WHERE codigo='$codigo';");

    }

    public function seguimiento__obtener__seguimiento($codigo) {

      return $this->constructor->select__general__incentivo("SELECT a.id,indicador,a.periodicidad,a.actividadSeguimiento,a.medioVerficiacion,(SELECT a1.indicadorArray FROM proyecto_seguimiento_informe_seguimiento AS a1 WHERE a1.idSeguimiento=a.id) AS indicadorArray,(SELECT a1.periodicidadArray FROM proyecto_seguimiento_informe_seguimiento AS a1 WHERE a1.idSeguimiento=a.id) AS periodicidadArray,(SELECT a1.actividadSeguimientoArray FROM proyecto_seguimiento_informe_seguimiento AS a1 WHERE a1.idSeguimiento=a.id) AS actividadSeguimientoArray,(SELECT a1.medioVerficiacionArray FROM proyecto_seguimiento_informe_seguimiento AS a1 WHERE a1.idSeguimiento=a.id) AS medioVerficiacionArray FROM proyecto_seguimiento AS a WHERE a.codigo='$codigo';");

    }

    public function componentes__montos__asignados__femenino($codigo) {

      return $this->constructor->select__general__incentivo("SELECT a.id, CONCAT_WS('','RAMA FEMENINA') AS componente,SUM(a.total) AS montoCalificado,a.idComponentes,(SELECT a1.montoCertificadoArray FROM proyecto_seguimiento_informe_presupuesto AS a1 WHERE a1.idPresupuesto=a.id) AS montoCertificadoArray FROM proyecto_presupuesto AS a WHERE a.codigo='$codigo' AND a.idNivel1 IS NOT NULL AND a.sector='femenino' GROUP BY a.idComponentes,a.codigo;");

    }

    public function componentes__montos__asignados__priorizado($codigo) {

      return $this->constructor->select__general__incentivo("SELECT a.id, CONCAT_WS('','SECTOR PRIORIZADO') AS componente,SUM(a.total) AS montoCalificado,a.idComponentes,(SELECT a1.montoCertificadoArray FROM proyecto_seguimiento_informe_presupuesto AS a1 WHERE a1.idPresupuesto=a.id) AS montoCertificadoArray FROM proyecto_presupuesto AS a WHERE a.codigo='$codigo' AND a.idNivel1 IS NOT NULL AND a.sector='priorizado' GROUP BY a.idComponentes,a.codigo;");

    }

    public function componentes__montos__asignados($codigo) {

      return $this->constructor->select__general__incentivo("SELECT IF(a.idNivel1 IS NULL,'COMPONENTE','RUBRO') AS idNivel1,a.id,IFNULL((SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes AND a.idNivel1 IS NULL),(SELECT a1.nombre FROM componentes_nivel AS a1 WHERE a1.idNivel1=a.idNivel1)) AS componente,a.total AS montoCalificado,a.idComponentes,IFNULL((SELECT a1.montoCertificadoArray FROM proyecto_seguimiento_informe_presupuesto AS a1 WHERE a1.idPresupuesto=a.id),0) AS montoCertificadoArray FROM proyecto_presupuesto AS a LEFT JOIN componentes_nivel AS b ON b.idNivel1=a.idNivel1 WHERE (b.rubros=1 OR a.idNivel1 IS NULL) AND  a.codigo='$codigo' AND a.sector='componente';");

    }


    public function beneficiarios__seguimiento($codigo) {

      return $this->constructor->select__general__incentivo("SELECT id, (SELECT a1.nombre FROM beneficiario AS a1 WHERE a1.idBeneficiario=a.idBeneficiario  LIMIT 1) AS beneficiario,(SELECT a1.nombre FROM rangoedad AS a1 WHERE a1.idRango=a.idRango LIMIT 1) AS edad,(SELECT a1.nombre FROM genero AS a1 WHERE a1.idGenero=a.idGenero  LIMIT 1) AS genero,(SELECT a1.nombre FROM autentificacion AS a1 WHERE a1.idAutentificacion=a.idAutentificacion  LIMIT 1) AS autentificacion,(SELECT a1.nombre FROM tipodiscapacidad AS a1 WHERE a1.idDiscapacidad=a.idDiscapacidad  LIMIT 1) AS discapacidad,cantidad,IFNULL((SELECT a1.beneficiarioArray FROM proyecto_seguimiento_informe_beneficiario AS a1 WHERE a1.idBeneficiario=a.id  LIMIT 1),'N') AS beneficiarioArray,IFNULL((SELECT a1.edadArray FROM proyecto_seguimiento_informe_beneficiario AS a1 WHERE a1.idBeneficiario=a.id  LIMIT 1),'N') AS edadArray,IFNULL((SELECT a1.generoArray FROM proyecto_seguimiento_informe_beneficiario AS a1 WHERE a1.idBeneficiario=a.id  LIMIT 1),'N') AS generoArray,IFNULL((SELECT a1.autentificacionArray FROM proyecto_seguimiento_informe_beneficiario AS a1 WHERE a1.idBeneficiario=a.id  LIMIT 1),'N') AS autentificacionArray,IFNULL((SELECT a1.discapacidadArray FROM proyecto_seguimiento_informe_beneficiario AS a1 WHERE a1.idBeneficiario=a.id  LIMIT 1),'N') AS discapacidadArray,IFNULL((SELECT a1.cantidadArray FROM proyecto_seguimiento_informe_beneficiario AS a1 WHERE a1.idBeneficiario=a.id  LIMIT 1),'N') AS cantidadArray FROM proyecto_beneficiarios AS a  WHERE codigo='$codigo';");

    }

    public function objetivos__especificos($codigo) {

      return $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto AS id,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes) AS componente,CONCAT_WS(' ',a.que,a.como,a.paraQue) AS objetivoEspecifico,IFNULL((SELECT a1.cumple FROM proyecto_seguimiento_informe_objetivo_especifico AS a1 WHERE a1.idComponentesProyecto=a.idComponentesProyecto),'N') AS cumple,IFNULL((SELECT a1.justificacion FROM proyecto_seguimiento_informe_objetivo_especifico AS a1 WHERE a1.idComponentesProyecto=a.idComponentesProyecto),'N') AS justificacion FROM proyecto_componente_usuario AS a WHERE a.codigo='$codigo';");

    }

    public function recuperar__objetivo__general($codigo) {

      return $this->constructor->select__general__incentivo("SELECT cumple,justificacion FROM proyecto_seguimiento_informe_objetivo_general WHERE codigo='$codigo';");

    }

    public function recuperar__resumen($codigo) {

      return $this->constructor->select__general__incentivo("SELECT fechaInicio,fechaFin,justificacion FROM proyecto_seguimiento_informe_resumen WHERE codigo='$codigo';");

    }


    public function informacion__general__proyecto__seguimiento($codigo) {

      return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,IF(b.nombre IS NOT NULL, UPPER(b.nombre), UPPER(z.razonSocial)) AS nombreSolicitante,IF(b.nombre IS NOT NULL, UPPER(b.cedula), UPPER(z.ruc)) AS credencialSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.email1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.correo1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial)) AS correoSolicitante,IF(b.nombre IS NOT NULL, (SELECT a1.celular1 FROM configuracion.contacto AS a1 WHERE a1.idCredencial=b.idCredencial),(SELECT a1.celular1 FROM configuracion.representante AS a1 WHERE a1.idCredencial=z.idCredencial)) AS celularSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,a.justificacionProyecto AS justificacion,c.idSector,a.justificacionProyecto,IF(b.nombre IS NULL,z.ruc,IF((SELECT a1.idRepresentante FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1) IS NOT NULL, CONCAT_WS('001',(SELECT a1.cedula FROM configuracion.representante AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)),CONCAT_WS('001',b.cedula))) AS rucProponenteIdentificado,a.fechaInicio AS fechaInicio_real, a.fechaFin AS fechaFin__real,(SELECT a1.codigo FROM proyecto_enviado AS a1 WHERE a1.codigoUsuario=a.codigo) AS codigoReal,(SELECT IF(a1.estadoSeguimiento IS NULL OR a1.estadoSeguimiento='P','SI','NO') FROM proyecto_enviado AS a1 WHERE a1.codigoUsuario=a.codigo) AS estadoSeguimiento,(SELECT a1.fechaCalifica FROM proyecto_enviado AS a1 WHERE a1.codigoUsuario=a.codigo) AS fechaCalifica FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo LEFT JOIN configuracion.organismo AS z ON z.idCredencial=a.idCredencial WHERE a.codigo='$codigo' GROUP BY a.codigo;");

    }

    public function estado__seguimiento($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT estadoSeguimiento FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
      foreach ($consulta as $valor) {
        $estadoSeguimiento=$valor["estadoSeguimiento"];
      }

      return $estadoSeguimiento;

    } 

    public function obtener__id__enviado__proyecto($codigo) {
      return $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
    }

    public function actualizar__informe__enviar($post) {

      $codigo=$post["codigo"];
      
      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoSeguimiento='E' WHERE codigoUsuario='$codigo';");
      foreach ($this->obtener__id__enviado__proyecto($codigo) as $valor) {
        $idBd=$valor["id"];
      }

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
          ':idFisicamenteActual' =>0,
          ':idUsuarioActual' => 0,
          ':idFisicamenteNuevo' =>0,
          ':idUsuarioNuevo' => 0,
          ':idEnviado' =>  $idBd,
          ':textoDevuelto' => null,
          ':tipo' =>  "Seguimiento",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
      ));

    }    


    public function activacion__documentos($codigo) {
      
      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos WHERE estado='A' AND codigo='$codigo';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 2;
      }else{
        return 1;
      }

    }    


    public function guardarDocumentosGenerales__seguimiento__v1($codigo) {
    
      $consulta__declaracionJuramentada=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo' AND declaracionJuramentada IS NOT NULL AND informeFinalCumplimiento IS NOT NULL;");

      foreach ($consulta__declaracionJuramentada as $valor) {
        $idBd__declaracionJuramentada=$valor["id"];
      }

    
      $consulta__documengosGenerales=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos_v1 WHERE (fotografias IS NOT NULL OR memorias IS NOT NULL OR certificaciones IS NOT NULL OR otros IS NOT NULL) AND codigo='$codigo';");

      foreach ($consulta__documengosGenerales as $valor) {
        $idBd__documengosGenerales=$valor["id"];
      }

      if(empty($idBd__declaracionJuramentada)){
        return 2;
      }else if(empty($idBd__documengosGenerales)){
        return 3;
      }else{
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_documentos_v1 SET estado='E' WHERE codigo='$codigo';");
        return 1;
      }

    }    

    public function guardarDocumentosGenerales__seguimiento($codigo) {
    
      $consulta__declaracionJuramentada=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos WHERE codigo='$codigo' AND declaracionJuramentada IS NOT NULL;");

      foreach ($consulta__declaracionJuramentada as $valor) {
        $idBd__declaracionJuramentada=$valor["id"];
      }

    
      $consulta__documengosGenerales=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos WHERE (fotografias IS NOT NULL OR memorias IS NOT NULL OR certificaciones IS NOT NULL OR otros IS NOT NULL) AND codigo='$codigo';");

      foreach ($consulta__documengosGenerales as $valor) {
        $idBd__documengosGenerales=$valor["id"];
      }

      if(empty($idBd__declaracionJuramentada)){
        return 2;
      }else if(empty($idBd__documengosGenerales)){
        return 3;
      }else{
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_documentos SET estado='A' WHERE codigo='$codigo';");
        return 1;
      }

    }    

    public function actualizarArchivosGenerales__v1($post) {

        $campo=$post["campo"];
        $codigo=$post["codigo"];

        if(!empty($codigo)){

          $nombreArchivo=$campo."__".$codigo."__v1.pdf";
          $rutaDefinitiva=$this->ruta."seguimiento/";
          $rastreo=$this->constructor->archivoCargar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['size'],$rutaDefinitiva,$nombreArchivo);

          if ($rastreo===1) {

              $archivoExistente=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';");

              foreach ($archivoExistente as $valor) {
                  $idDocumentos=$valor["id"];
              }

              if (empty($idDocumentos)) {

                $this->constructor->inserta__general__incentivo("proyecto_seguimiento_documentos_v1",["$campo",'fecha','hora','codigo'],array(":$campo" => $nombreArchivo,':fecha' => $this->fecha,':hora' => $this->hora,":codigo" => $codigo)); 

              }else{
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_documentos_v1 SET $campo='$nombreArchivo' WHERE codigo='$codigo';");
              }


              return $this->constructor->select__archivo__natural($nombreArchivo,$rutaDefinitiva);


          }else if($rastreo===2){
              return 2;
          }else if($rastreo===0){
              return 0;
          }

        }

    }


    public function actualizarArchivosGenerales($post) {

        $campo=$post["campo"];
        $codigo=$post["codigo"];

        if(!empty($codigo)){

          $nombreArchivo=$campo."__".$codigo.".pdf";
          $rutaDefinitiva=$this->ruta."seguimiento/";
          $rastreo=$this->constructor->archivoCargar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['size'],$rutaDefinitiva,$nombreArchivo);

          if ($rastreo===1) {

              $archivoExistente=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';");

              foreach ($archivoExistente as $valor) {
                  $idDocumentos=$valor["id"];
              }

              if (empty($idDocumentos)) {

                $this->constructor->inserta__general__incentivo("proyecto_seguimiento_documentos",["$campo",'fecha','hora','codigo'],array(":$campo" => $nombreArchivo,':fecha' => $this->fecha,':hora' => $this->hora,":codigo" => $codigo)); 

              }else{
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_seguimiento_documentos SET $campo='$nombreArchivo' WHERE codigo='$codigo';");
              }


              return $this->constructor->select__archivo__natural($nombreArchivo,$rutaDefinitiva);


          }else if($rastreo===2){
              return 2;
          }else if($rastreo===0){
              return 0;
          }

        }

    }

    public function seleccionaGlobales__declaracionJuramentada__v1($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT declaracionJuramentada FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';",$this->ruta."seguimiento/","declaracionJuramentada");
    } 

    public function seleccionaGlobales__fotografias__v1($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT fotografias FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';",$this->ruta."seguimiento/","fotografias");
    } 

    public function seleccionaGlobales__memorias__v1($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT memorias FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';",$this->ruta."seguimiento/","memorias");
    } 

    public function seleccionaGlobales__certificaciones__v1($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT certificaciones FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';",$this->ruta."seguimiento/","certificaciones");
    }     

    
    public function seleccionaGlobales_otros__v1($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT otros FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';",$this->ruta."seguimiento/","otros");
    }    

    public function seleccionaGlobales_informe__v1($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT informeFinalCumplimiento FROM proyecto_seguimiento_documentos_v1 WHERE codigo='$codigo';",$this->ruta."seguimiento/","informeFinalCumplimiento");
    }    




    public function seleccionaGlobales_otros($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT otros FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';",$this->ruta."seguimiento/","otros");
    }    

    public function seleccionaGlobales__certificaciones($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT certificaciones FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';",$this->ruta."seguimiento/","certificaciones");
    }     

    public function seleccionaGlobales__declaracionJuramentada($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT declaracionJuramentada FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';",$this->ruta."seguimiento/","declaracionJuramentada");
    } 

    public function seleccionaGlobales__fotografias($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT fotografias FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';",$this->ruta."seguimiento/","fotografias");
    } 

    public function seleccionaGlobales__memorias($codigo) {
      return $this->constructor->select__archivo__incentivo("SELECT memorias FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';",$this->ruta."seguimiento/","memorias");
    } 

    public function select__general__documentos__incentivo($post) {

      $campo=$post["campo"];
      $codigo=$post["codigo"];

      return $this->constructor->select__general__incentivo("SELECT $campo FROM proyecto_seguimiento_documentos WHERE codigo='$codigo';");

    }      

     public function buscar__proyecto__existente($post){

      $input=$post["input"];
      $idCredencial=$post["idCredencial"];

      return $this->constructor->select__general__incentivo("SELECT a.id,b.nombre,IF(b.tipo='ANUAL' OR b.tipo IS NULL,'ANUAL','PLURIANUAL') AS tipo,a.codigo,a.codigoUsuario,YEAR(b.fechaInicio) AS anioInicio, YEAR(b.fechaFin) AS anioFin,IF((SELECT a1.idSector FROM proyecto_sector AS a1 WHERE a1.codigo=a.codigoUsuario LIMIT 1) IS NOT NULL,(SELECT a2.nombre FROM proyecto_sector AS a1 INNER JOIN sector AS a2  ON a2.idSector=a1.idSector WHERE a1.codigo=a.codigoUsuario LIMIT 1),IF((SELECT a1.idComponentes FROM proyecto_componente_usuario AS a1 WHERE a1.codigo=a.codigoUsuario AND a1.idComponentes='5' LIMIT 1) IS NOT NULL,'CONSTRUCCION DE OBRA NUEVA, REHABILITACION, READECUACION Y O MANTENIMIENTO DE INFRAESTRUCTURA DEPORTIVA','COMPRA Y VENTA DE BIENES INMUEBLES CON INFRAESTRUCTURA DEPORTIVA CONSTRUIDA')) AS sector FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE (a.codigo='$input' OR b.nombre LIKE '%$input%') AND a.idCredencial='$idCredencial'  AND a.estadoSeguimiento='P'  ORDER BY a.id DESC LIMIT 1;");

    }   

    public function proyectos__aprobados__codigo($post){

      $idCredencial=$post["idCredencial"];

      return $this->constructor->select__general__incentivo("SELECT a.codigo FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE a.idCredencial='$idCredencial' AND a.estadoSeguimiento='P';");

    }

    public function proyectos__aprobados__nombres($post){

      $idCredencial=$post["idCredencial"];

      return $this->constructor->select__general__incentivo("SELECT UPPER(b.nombre) AS nombreProyecto FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE a.idCredencial='$idCredencial' AND a.estadoSeguimiento='P';");

    }

}
