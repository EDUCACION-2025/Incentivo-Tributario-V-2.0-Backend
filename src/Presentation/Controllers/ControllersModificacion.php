<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Application\Auth\Auth;
use App\Domain\Repositories\Dinardap;
use App\Presentation\Pdf\Base;
use App\Presentation\Pdf\InformeC;

class ControllersModificacion {

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

        $this->informePdf = InformeC::getInstance();
        $this->constructor__basePdf = Base::getInstance();

    }

    public function proyectosEnviadosCiudadano($idCredencial) {

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigoUsuario,a.codigo, IF(a.estadoCalificacion IS NULL,'ENVIADO',IF(a.estadoCalificacion='Devuelto' OR a.estadoCalificacion='Comite','RECOMENDADO',UPPER(a.estadoCalificacion))) AS estadoCalificacion,(SELECT a1.tipo FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS estado, IF(a.estadoCalificacion='observado' OR a.estadoCalificacion='modificacion','EstadoProyectosO','EstadoProyectosP') AS componente,(SELECT a1.texto FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id ORDER BY id DESC LIMIT 1) AS observacionBaja,(SELECT a1.nombre FROM proyecto_descripcion AS a1 WHERE a1.codigo=a.codigoUsuario  LIMIT 1) AS nombreProyecto,CONCAT_WS('','EstadoProyectosModificacion') AS componenteModificacion,IFNULL((SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=a.codigoUsuario LIMIT 1),'N') AS modificacion,IFNULL((SELECT a1.estado FROM proyecto_modificacion_solicitud AS a1 WHERE (a1.estado='PENDIENTE' || a1.estado='ENVIADO' || a1.estado='APROBADO') AND  a1.codigo=a.codigoUsuario  LIMIT 1),'N') AS modificacionCer,CONCAT_WS('','EstadoProyectosCertificacion') AS componenteCertificacion,IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='TB','Cerrado-No ejecutado',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='T','Cerrado',IF(a.estadoSeguimiento IS NOT NULL,'P','N/A'))) AS estadoSeguimiento, IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='P','PENDIENTE ENVIAR INFORME',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='E','ENVIADO',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='O','OBSERVADO',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='A','APROBADO',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='C','CERRADO',' '))))) AS estadoProyectoSeguimiento,CONCAT_WS('','EstadoProyectosSeguimiento') AS componenteSeguimiento,b.estadoModificacion FROM proyecto_enviado AS a INNER JOIN proyecto_modificacion_solicitud AS b ON b.codigo=a.codigoUsuario WHERE (b.estadoModificacion IS NULL OR b.estadoModificacion='ENVIADO') AND a.idCredencial='$idCredencial' GROUP BY a.codigo;");

    }

    public function obtener__proyectos__recomendados__analistas($idComite,$estado){

      if ($estado==="FINALIZADA" || $estado==="CERRADA") {
       
        return $this->constructor->select__general__incentivo("SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,c.documento,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=a.codigo AND estado='APROBADO' LIMIT 1) AS idIncrementalModificacion FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id WHERE c.idComite = '$idComite' AND c.modulo='MODIFICACION';");

      }else{

        return $this->constructor->select__general__incentivo("SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComiteModificacion IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=a.codigo AND estado='APROBADO' LIMIT 1) AS idIncrementalModificacion FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.escogidoComiteModificacion IS NOT NULL AND (a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.idComite = '$idComite';");

      }


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


    public function despriozar__proyectos($valor,$proyectoId,$idComite){


      $this->constructor->actualiza__general__incentivo("DELETE FROM comite_proyectos WHERE idEnviado='$proyectoId';");
      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComiteModificacion=NULL,idComite=NULL WHERE id='$proyectoId';"); 
      $mensaje="Se quito la priorización";

      
      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, textoDevuelto, tipo, fecha,hora  FROM proyecto_enviado_antecedente WHERE idEnviado='$proyectoId' ORDER BY id DESC LIMIT 1;");

      foreach ($consultaEnviado as $valor) {

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
          ':idFisicamenteActual' =>$valor["idFisicamenteActual"],
          ':idUsuarioActual' => $valor["idUsuarioActual"],
          ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
          ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
          ':idEnviado' =>  $valor["idEnviado"],
          ':textoDevuelto' =>  $valor["textoDevuelto"],
          ':tipo' =>  "Se quito la priorización",
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

    public function obtener__proyectos__recomendados(){

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo,UPPER(b.nombre) AS nombre,IF(a.escogidoComiteModificacion IS NULL,0,1) AS escogido,a.codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b  ON a.codigoUsuario=b.codigo WHERE a.escogidoComiteModificacion IS NOT NULL AND  a.estadoModificacion='Comite' OR a.estadoModificacion='Comite Priorizado' OR a.estadoModificacion='Comite Observado';");

    }

    public function priorizar__proyectos($valor,$proyectoId,$idComite){


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
        ':modulo' =>'MODIFICACION',
      ));   


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComiteModificacion=1,idComite='$idComite' WHERE id='$proyectoId';"); 
      $mensaje="Priorizado comité";


      
      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, textoDevuelto, tipo, fecha,hora  FROM proyecto_enviado_antecedente WHERE idEnviado='$proyectoId' ORDER BY id DESC LIMIT 1;");

      foreach ($consultaEnviado as $valor) {

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
          ':idFisicamenteActual' =>$valor["idFisicamenteActual"],
          ':idUsuarioActual' => $valor["idUsuarioActual"],
          ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
          ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
          ':idEnviado' =>  $valor["idEnviado"],
          ':textoDevuelto' =>  $valor["textoDevuelto"],
          ':tipo' =>  "Priorizado comité",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));

      }

      $cuantos=$this->configuracion__acreditada($idComite);

      $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='configurado' WHERE id='$idComite';"); 

      return 1;

    }


    public function obtenerProyectos__comite(){

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo,b.nombre,IF(a.escogidoComiteModificacion IS NULL,0,1) AS escogido,a.codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b  ON a.codigoUsuario=b.codigo WHERE a.escogidoComiteModificacion IS NULL AND  a.estadoModificacion='Comite' OR a.estadoModificacion='Comite Priorizado' OR a.estadoModificacion='Comite Observado';");

    }

    public function bandeja__recomendados__comite($post) {

      $idCredencial=$post["idCredencial"];
      $idRol=$post["idRol"];
      $fisicamenteEstructura=$post["fisicamenteEstructura"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);

      return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, FORMAT(SUM(d.total), 2) AS monto,a.fecha,IF(a.estadoModificacion='Comite','RECOMENDADO',IF(a.estadoModificacion='Comite priorizado','EN COMITÉ',IF(a.estadoModificacion='Comite Observado','Observado',''))) AS estado,a.estadoCalificacion  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' WHERE idUsuarioModificacion=0 AND idUsuarioModificacionRecomienda=0 AND estadoModificacion='Comite' GROUP BY c.codigo,d.codigo;");


    } 



    public function obtener__texto__recomendacion($tipo,$idEnviado,$idSolicitud) {

      $array=array();

      if ($tipo==="tecnico" ) {
       $tabla="proyecto_enviado_recomendacion_modificacion";
      }else{
        $tabla="proyecto_enviado_recomendacion_infraestructura_modificacion";
      }
      
      $consulta=$this->constructor->select__general__incentivo("SELECT recomendacion,texto FROM $tabla WHERE idSolicitud='$idSolicitud' AND idEnviado='$idEnviado';");

      foreach ($consulta as $valor) {
        array_push($array, $valor["recomendacion"]);
        array_push($array, $valor["texto"]);
      }

      return $array;

    }

    public function pie__de__pagina__roles($idSolicitud,$idRol) {

      $consulta=$this->constructor->select__general__incentivo("SELECT a.idUsuario FROM proyecto_enviado_recomendacion_modificacion AS a INNER JOIN ezonshar_mdepsaddb.th_usuario AS b ON a.idUsuario=b.id_usuario INNER JOIN ezonshar_mdepsaddb.th_usuario_roles AS c ON c .id_usuario=a.idUsuario WHERE a.idSolicitud='$idSolicitud' AND c.id_rol='$idRol';");
      foreach ($consulta as $valor) {
        $idUsuario=$valor["idUsuario"];
      }


      return $idUsuario;

    }

    public function generar__informe__comite($post) {

      $codigo=$post["codigo"];
      $codigoProyecto=$post["codigoProyecto"];
      $idCredencial=$post["idCredencial"];
      $nomenclatura=$post["nomenclatura"];

      $consulta=$this->codigo__modificacion($codigo);
      foreach ($consulta as $valor) {
        $idSolicitud=$valor["idSolicitud"];
      }

      $consulta=$this->obtenerUsuario__porCodigo($codigo);
      foreach ($consulta as $valor) {
        $idEnviado=$valor["id"];
      }

      $camposArray=$this->obtener__texto__recomendacion($nomenclatura,$idEnviado,$idSolicitud);

      $opcionRecomendacion=$camposArray[0];
      $textoRecomendacion=$camposArray[1];

      $consultaSolicitud=$this->codigo__modificacion($codigo);

      foreach ($consultaSolicitud as $valorC) {
        $idSolicitudBdC=$valorC["idSolicitud"];
      }


      $consultaCasos=$this->codigo__modificacion($codigo);

      foreach ($consultaCasos as $valorCaso) {
        $casoModificarBd=$valorCaso["casoModificar"];
      }

      $idUsuario_analista=$this->pie__de__pagina__roles($idSolicitudBdC,3);
      $idUsuario_nuevo__llamado=$this->pie__de__pagina__roles($idSolicitudBdC,2);



      $idUsuario=$this->obtener__id__usuario($idCredencial);
      

      if(empty($idUsuario_nuevo__llamado)){
        $informacionUsuario=$this->obtener__usuario($idUsuario);
      }else{
        $informacionUsuario=$this->obtener__usuario($idUsuario_nuevo__llamado);
      }
      



      $informacionUsuario__analista=$this->obtener__usuario($idUsuario_analista);
      $informacionUsuario__superiorInmediato=$this->obtener__usuario($informacionUsuario[3]);

      $informacionUsuario__superiorInmediatoFinal=$this->obtener__usuario($informacionUsuario__superiorInmediato[3]);


      if ($casoModificarBd==="a" || $casoModificarBd==="c") {
  
        $contenido=$this->informePdf->portada__informe__notificacion($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->portada__informe__antecedente($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->base__legal__notifiacion($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->notificacion_legal_notificacion__caso__a__c($codigo,$informacionUsuario__superiorInmediatoFinal[1],$opcionRecomendacion,$informacionUsuario);

        $contenido.=$this->informePdf->pieDeFirma($informacionUsuario__analista,$informacionUsuario);

      }else{

        $contenido=$this->informePdf->portada__informe($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->datos__generales__modificado($codigo,$idSolicitudBdC);
        $contenido.=$this->informePdf->presupuesto__modificacion($codigo,$idSolicitudBdC);
        $contenido.=$this->informePdf->analisisTecnico__modificacion($codigo,$informacionUsuario,$opcionRecomendacion,$textoRecomendacion);

        if ($comite===false && intval($informacionUsuario[4])===3) {
            $informacionUsuario__superiorInmediato=[];
        }

        $contenido.=$this->informePdf->pieDeFirma($informacionUsuario__analista,$informacionUsuario);

      }


      $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

      return $pdfResult;

    } 

    public function componente__calificado__comite($codigo){

      $consulta=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto FROM proyecto_componente_usuario WHERE (idComponentes='5' OR idComponentes='7') AND codigo='$codigo';");
      foreach ($consulta as $valor) {
        $idBd=$valor["idComponentesProyecto"];
      }

      if (empty($idBd)) {
        return 0;
      }else{
        return 1;
      }

    }

    public function sector__calificado__comite($codigo){

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE codigo='$codigo';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 0;
      }else{
        return 1;
      }

    }

    public function bandeja__recomendados__informacion__modificacion__recomendacion__analistas($post) {

      $fisicamente=$post["fisicamente"];
      $codigo=$post["codigo"];

      $consultaSolicitud=$this->codigo__modificacion($codigo);
      foreach ($consultaSolicitud as $valor) {
        $idSolicitudBd=$valor["idSolicitud"];
      }

      if(intval($fisicamente)===15){
        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado_recomendacion_infraestructura_modificacion WHERE idSolicitud='$idSolicitudBd';");
      }else{
        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado_recomendacion_modificacion WHERE idSolicitud='$idSolicitudBd';");
      }

      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return "no";
      }else{
        return "si";
      }

    } 


    public function bandeja__recomendados__informacion($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigo=$post["codigo"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigo);

      $consulta=$this->codigo__modificacion($codigoUsuario);
      foreach ($consulta as $valor) {
        $idSolicitud=$valor["idSolicitud"];
      }

      return $this->constructor->select__general__incentivo("SELECT idEnviado,idUsuario,idRol,texto,fecha,hora,idUsuario2,idRol2,fecha2,hora2,estado,tipo,recomendacion FROM proyecto_enviado_recomendacion_modificacion WHERE idEnviado='$idBdEnviado' AND idSolicitud='$idSolicitud';");


    } 


    public function bandeja__recomendados__informacion__infraestructura($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigo=$post["codigo"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigo);

      $consulta=$this->codigo__modificacion($codigoUsuario);
      foreach ($consulta as $valor) {
        $idSolicitud=$valor["idSolicitud"];
      }


      return $this->constructor->select__general__incentivo("SELECT idEnviado,idUsuario,idRol,texto,fecha,hora,idUsuario2,idRol2,fecha2,hora2,estado,tipo,recomendacion FROM proyecto_enviado_recomendacion_infraestructura_modificacion WHERE idEnviado='$idBdEnviado' AND idSolicitud='$idSolicitud';");


    } 

    public function obtener__codigo__usuario($codigo) {
      return $this->constructor->select__general__incentivo("SELECT codigoUsuario FROM proyecto_enviado WHERE codigo='$codigo';");
    } 


    public function generarPdf__notificacion($camposArray,$codigo,$idCredencial){

      $opcionRecomendacion=$camposArray[0];
      $textoRecomendacion=$camposArray[1];

      $consultaSolicitud=$this->codigo__modificacion($codigo);

      foreach ($consultaSolicitud as $valorC) {
        $idSolicitudBdC=$valorC["idSolicitud"];
      }

      $idUsuario_analista=$this->pie__de__pagina__roles($idSolicitudBdC,3);

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);
      $informacionUsuario__analista=$this->obtener__usuario($idUsuario_analista);
      $informacionUsuario__superiorInmediato=$this->obtener__usuario($informacionUsuario[3]);
      $informacionUsuario__superiorInmediatoFinal=$this->obtener__usuario($informacionUsuario__superiorInmediato[3]);

  
      $contenido=$this->informePdf->portada__informe__notificacion($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
      $contenido.=$this->informePdf->portada__informe__antecedente($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
      $contenido.=$this->informePdf->base__legal__notifiacion($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
      $contenido.=$this->informePdf->notificacion_legal_notificacion__caso__a__c($codigo,$informacionUsuario__superiorInmediatoFinal[1],$opcionRecomendacion,$informacionUsuario);


      $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo); 

      return $pdfResult;

    }

    public function notificacion__generar($post) {


      $idCredencial=$post["idCredencial"];
      $codigoProyecto=$post["codigoUsuario"];
      $enviarInfra=$post["enviarInfra"];
      $fisicamente=$post["fisicamente"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      $codigoUsuarioObtenido=$this->obtener__codigo__borrador($codigoProyecto);
      $banderaComponentes=$this->obtener__componentes__necesarios($codigoUsuarioObtenido);
      $banderaSector=$this->obtener__sector__necesarios($codigoUsuarioObtenido);

      $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        $nombreTabla__recomendacion="proyecto_enviado_recomendacion_infraestructura";
        $nomenclatura="tecnico";
      }else{
        $nombreTabla__recomendacion="proyecto_enviado_recomendacion";
        $nomenclatura="infraestructura";
      }

      $consultaCodigoUsuario__traido=$this->obtener__codigo__usuario($codigoProyecto);
      foreach ($consultaCodigoUsuario__traido as $valor__traido) {
        $codigoUsuario__traido=$valor__traido["codigoUsuario"];
      }


      $consultaCasoModificar=$this->codigo__modificacion($codigoUsuario__traido);
      foreach ($consultaCasoModificar as $valorCasoModificar) {
        $casoModificarBd__1=$valorCasoModificar["casoModificar"];
      }

      $consulta=$this->codigo__modificacion($codigo);
      foreach ($consulta as $valor) {
        $idSolicitud=$valor["idSolicitud"];
      }

      $consulta=$this->obtenerUsuario__porCodigo($codigo);
      foreach ($consulta as $valor) {
        $idEnviado=$valor["id"];
      }


      $codigo=$codigoUsuario__traido;

      $bandera__final=true;

      $camposArray=$this->obtener__texto__recomendacion($nomenclatura,$idEnviado,$idSolicitud);

      $pdfResult=$this->generarPdf__notificacion($camposArray,$codigo,$idCredencial);

      return $pdfResult;

    }

    public function actualizar__descripcion__proyecto__en__modificacion($codigo,$idSolicitud) {

      $consulta = $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,idCredencial,idProyecto,codigo,fecha,hora,justificacionProyecto FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A';");

      foreach ($consulta as $valor) {

        $nombre__respaldo=$valor["nombre"];
        $fechaInicio__respaldo=$valor["fechaInicio"];
        $fechaFin__respaldo=$valor["fechaFin"];
        $tipo__respaldo=$valor["tipo"];
        $diferenciaAnios__respaldo=$valor["diferenciaAnios"];
        $objetivoGeneral__respaldo=$valor["objetivoGeneral"];
        $idCredencial__respaldo=$valor["idCredencial"];
        $idProyecto__respaldo=$valor["idProyecto"];
        $codigo__respaldo=$valor["codigo"];
        $fecha__respaldo=$valor["fecha"];
        $hora__respaldo=$valor["hora"];
        $justificacionProyecto__respaldo=$valor["justificacionProyecto"];

      }

      $consulta = $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,idCredencial,idProyecto,codigo,fecha,hora,justificacionProyecto FROM proyecto_descripcion WHERE codigo='$codigo';");

      foreach ($consulta as $valor) {

        $nombre__original=$valor["nombre"];
        $fechaInicio__original=$valor["fechaInicio"];
        $fechaFin__original=$valor["fechaFin"];
        $tipo__original=$valor["tipo"];
        $diferenciaAnios__original=$valor["diferenciaAnios"];
        $objetivoGeneral__original=$valor["objetivoGeneral"];
        $idCredencial__original=$valor["idCredencial"];
        $idProyecto__original=$valor["idProyecto"];
        $codigo__original=$valor["codigo"];
        $fecha__original=$valor["fecha"];
        $hora__original=$valor["hora"];
        $justificacionProyecto__original=$valor["justificacionProyecto"];

      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_descripcion WHERE codigo='$codigo';");
      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_descripcion SET estado='T',idSolicitud='$idSolicitud' WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A';");

      $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_descripcion", ['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral', 'idCredencial', 'idProyecto','codigo','fecha','hora','tipoIngreso','estado','idSolicitud','justificacionProyecto'], array(
        ':nombre' =>$nombre__original,
        ':fechaInicio' =>$fechaInicio__original,
        ':fechaFin' =>$fechaFin__original,
        ':tipo' =>$tipo__original,
        ':diferenciaAnios' =>$diferenciaAnios__original,
        ':objetivoGeneral' =>$objetivoGeneral__original,
        ':idCredencial' =>$idCredencial__original,
        ':idProyecto' =>$idProyecto__original,
        ':codigo' =>$codigo,
        ':fecha' =>$this->fecha,
        ':hora' =>$this->hora,
        ':tipoIngreso' =>'modificacionOriginal',
        ':estado' =>'I',
        ':idSolicitud' =>$idSolicitud,
        ':justificacionProyecto' =>$justificacionProyecto__original,
      ));


      $this->constructor->inserta__general__incentivo("proyecto_descripcion", ['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral', 'idCredencial', 'idProyecto','codigo','fecha','hora','justificacionProyecto'], array(
        ':nombre' =>$nombre__respaldo,
        ':fechaInicio' =>$fechaInicio__respaldo,
        ':fechaFin' =>$fechaFin__respaldo,
        ':tipo' =>$tipo__respaldo,
        ':diferenciaAnios' =>$diferenciaAnios__respaldo,
        ':objetivoGeneral' =>$objetivoGeneral__respaldo,
        ':idCredencial' =>$idCredencial__respaldo,
        ':idProyecto' =>$idProyecto__respaldo,
        ':codigo' =>$codigo,
        ':fecha' =>$this->fecha,
        ':hora' =>$this->hora,
        ':justificacionProyecto' =>$justificacionProyecto__respaldo,
      ));

      return 1;

    }

    public function actualizar__presupuesto__proyecto__en__modificacion($codigo,$idSolicitud) {

      $idComponentes__respaldoArray=array();
      $idNivel1__respaldoArray=array();
      $detalle__respaldoArray=array();
      $justificacion__respaldoArray=array();
      $enero__respaldoArray=array();
      $febrero__respaldoArray=array();
      $marzo__respaldoArray=array();
      $abril__respaldoArray=array();
      $mayo__respaldoArray=array();
      $junio__respaldoArray=array();
      $julio__respaldoArray=array();
      $agosto__respaldoArray=array();
      $septiembre__respaldoArray=array();
      $octubre__respaldoArray=array();
      $noviembre__respaldoArray=array();
      $diciembre__respaldoArray=array();
      $total__respaldoArray=array();
      $fecha__respaldoArray=array();
      $hora__respaldoArray=array();
      $activo__respaldoArray=array();
      $anio__respaldoArray=array();
      $codigo__respaldoArray=array();
      $idCredencial__respaldoArray=array();
      $nivel__respaldoArray=array();
      $sector__respaldoArray=array();

      $consulta = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector FROM incentivorespaldo.proyecto_presupuesto WHERE estado='A' AND tipoIngreso='modificacion' AND codigo='$codigo';");

      foreach ($consulta as $valor) {

        array_push($idComponentes__respaldoArray, $valor["idComponentes"]);
        array_push($idNivel1__respaldoArray, $valor["idNivel1"]);
        array_push($detalle__respaldoArray, $valor["detalle"]);
        array_push($justificacion__respaldoArray, $valor["justificacion"]);
        array_push($enero__respaldoArray, $valor["enero"]);
        array_push($febrero__respaldoArray, $valor["febrero"]);
        array_push($marzo__respaldoArray, $valor["marzo"]);
        array_push($abril__respaldoArray, $valor["abril"]);
        array_push($mayo__respaldoArray, $valor["mayo"]);
        array_push($junio__respaldoArray, $valor["junio"]);
        array_push($julio__respaldoArray, $valor["julio"]);
        array_push($agosto__respaldoArray, $valor["agosto"]);
        array_push($septiembre__respaldoArray, $valor["septiembre"]);
        array_push($octubre__respaldoArray, $valor["octubre"]);
        array_push($noviembre__respaldoArray, $valor["noviembre"]);
        array_push($diciembre__respaldoArray, $valor["diciembre"]);
        array_push($total__respaldoArray, $valor["total"]);
        array_push($fecha__respaldoArray, $valor["fecha"]);
        array_push($hora__respaldoArray, $valor["hora"]);
        array_push($activo__respaldoArray, $valor["activo"]);
        array_push($anio__respaldoArray, $valor["anio"]);
        array_push($codigo__respaldoArray, $valor["codigo"]);
        array_push($idCredencial__respaldoArray, $valor["idCredencial"]);
        array_push($nivel__respaldoArray, $valor["nivel"]);
        array_push($sector__respaldoArray, $valor["sector"]);

      }

      $idComponentes__originalArray=array();
      $idNivel1__originalArray=array();
      $detalle__originalArray=array();
      $justificacion__originalArray=array();
      $enero__originalArray=array();
      $febrero__originalArray=array();
      $marzo__originalArray=array();
      $abril__originalArray=array();
      $mayo__originalArray=array();
      $junio__originalArray=array();
      $julio__originalArray=array();
      $agosto__originalArray=array();
      $septiembre__originalArray=array();
      $octubre__originalArray=array();
      $noviembre__originalArray=array();
      $diciembre__originalArray=array();
      $total__originalArray=array();
      $fecha__originalArray=array();
      $hora__originalArray=array();
      $activo__originalArray=array();
      $anio__originalArray=array();
      $codigo__originalArray=array();
      $idCredencial__originalArray=array();
      $nivel__originalArray=array();
      $sector__originalArray=array();      

      $consulta = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector FROM proyecto_presupuesto WHERE codigo='$codigo';");

      foreach ($consulta as $valor) {

        array_push($idComponentes__originalArray, $valor["idComponentes"]);
        array_push($idNivel1__originalArray, $valor["idNivel1"]);
        array_push($detalle__originalArray, $valor["detalle"]);
        array_push($justificacion__originalArray, $valor["justificacion"]);
        array_push($enero__originalArray, $valor["enero"]);
        array_push($febrero__originalArray, $valor["febrero"]);
        array_push($marzo__originalArray, $valor["marzo"]);
        array_push($abril__originalArray, $valor["abril"]);
        array_push($mayo__originalArray, $valor["mayo"]);
        array_push($junio__originalArray, $valor["junio"]);
        array_push($julio__originalArray, $valor["julio"]);
        array_push($agosto__originalArray, $valor["agosto"]);
        array_push($septiembre__originalArray, $valor["septiembre"]);
        array_push($octubre__originalArray, $valor["octubre"]);
        array_push($noviembre__originalArray, $valor["noviembre"]);
        array_push($diciembre__originalArray, $valor["diciembre"]);
        array_push($total__originalArray, $valor["total"]);
        array_push($fecha__originalArray, $valor["fecha"]);
        array_push($hora__originalArray, $valor["hora"]);
        array_push($activo__originalArray, $valor["activo"]);
        array_push($anio__originalArray, $valor["anio"]);
        array_push($codigo__originalArray, $valor["codigo"]);
        array_push($idCredencial__originalArray, $valor["idCredencial"]);
        array_push($nivel__originalArray, $valor["nivel"]);
        array_push($sector__originalArray, $valor["sector"]);

      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigo';");
      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET estado='T',idSolicitud='$idSolicitud' WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A';");

      foreach ($idComponentes__originalArray as  $clave =>  $valor) {
       
        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes','idNivel1','detalle','justificacion','enero','febrero', 'marzo', 'abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado','idSolicitud'], array(

          ':idComponentes' =>$idComponentes__originalArray[$clave],
          ':idNivel1' =>$idNivel1__originalArray[$clave],
          ':detalle' =>$detalle__originalArray[$clave],
          ':justificacion' =>$justificacion__originalArray[$clave],
          ':enero' =>$enero__originalArray[$clave],
          ':febrero' =>$febrero__originalArray[$clave],
          ':marzo' =>$marzo__originalArray[$clave],
          ':abril' =>$abril__originalArray[$clave],
          ':mayo' =>$mayo__originalArray[$clave],
          ':junio' =>$junio__originalArray[$clave],
          ':julio' =>$julio__originalArray[$clave],
          ':agosto' =>$agosto__originalArray[$clave],
          ':septiembre' =>$septiembre__originalArray[$clave],
          ':octubre' =>$octubre__originalArray[$clave],
          ':noviembre' =>$noviembre__originalArray[$clave],
          ':diciembre' =>$diciembre__originalArray[$clave],
          ':total' =>$total__originalArray[$clave],
          ':fecha' =>$fecha__originalArray[$clave],
          ':hora' =>$hora__originalArray[$clave],
          ':activo' =>$activo__originalArray[$clave],
          ':anio' =>$anio__originalArray[$clave],
          ':codigo' =>$codigo__originalArray[$clave],
          ':idCredencial' =>$idCredencial__originalArray[$clave],
          ':nivel' =>$nivel__originalArray[$clave],
          ':sector' =>$sector__originalArray[$clave],
          ':tipoIngreso' =>'modificacionOriginal',
          ':estado' =>'I',
          ':idSolicitud' =>$idSolicitud,

        ));

      }


      foreach ($idComponentes__respaldoArray as  $clave =>  $valor) {
       
        $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes','idNivel1','detalle','justificacion','enero','febrero', 'marzo', 'abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector'], array(

          ':idComponentes' =>$idComponentes__respaldoArray[$clave],
          ':idNivel1' =>$idNivel1__respaldoArray[$clave],
          ':detalle' =>$detalle__respaldoArray[$clave],
          ':justificacion' =>$justificacion__respaldoArray[$clave],
          ':enero' =>$enero__respaldoArray[$clave],
          ':febrero' =>$febrero__respaldoArray[$clave],
          ':marzo' =>$marzo__respaldoArray[$clave],
          ':abril' =>$abril__respaldoArray[$clave],
          ':mayo' =>$mayo__respaldoArray[$clave],
          ':junio' =>$junio__respaldoArray[$clave],
          ':julio' =>$julio__respaldoArray[$clave],
          ':agosto' =>$agosto__respaldoArray[$clave],
          ':septiembre' =>$septiembre__respaldoArray[$clave],
          ':octubre' =>$octubre__respaldoArray[$clave],
          ':noviembre' =>$noviembre__respaldoArray[$clave],
          ':diciembre' =>$diciembre__respaldoArray[$clave],
          ':total' =>$total__respaldoArray[$clave],
          ':fecha' =>$fecha__respaldoArray[$clave],
          ':hora' =>$hora__respaldoArray[$clave],
          ':activo' =>$activo__respaldoArray[$clave],
          ':anio' =>$anio__respaldoArray[$clave],
          ':codigo' =>$codigo__respaldoArray[$clave],
          ':idCredencial' =>$idCredencial__respaldoArray[$clave],
          ':nivel' =>$nivel__respaldoArray[$clave],
          ':sector' =>$sector__respaldoArray[$clave],

        ));

      }

      return 1;

    }

    public function actualizar__cronogramaDeActividades__proyecto__en__modificacion($codigo,$idSolicitud) {


      $idComponentes__respaldoArray=array();
      $idNivel1__respaldoArray=array();
      $actividades__respaldoArray=array();
      $enero__respaldoArray=array();
      $febrero__respaldoArray=array();
      $marzo__respaldoArray=array();
      $abril__respaldoArray=array();
      $mayo__respaldoArray=array();
      $junio__respaldoArray=array();
      $julio__respaldoArray=array();
      $agosto__respaldoArray=array();
      $septiembre__respaldoArray=array();
      $octubre__respaldoArray=array();
      $noviembre__respaldoArray=array();
      $diciembre__respaldoArray=array();
      $tipo__respaldoArray=array();
      $provincia__respaldoArray=array();
      $canton__respaldoArray=array();
      $parroquia__respaldoArray=array();
      $pais__respaldoArray=array();
      $ciudad__respaldoArray=array();
      $fecha__respaldoArray=array();
      $hora__respaldoArray=array();
      $activo__respaldoArray=array();
      $anio__respaldoArray=array();
      $codigo__respaldoArray=array();
      $idCredencial__respaldoArray=array();
      $nivel__respaldoArray=array();
      $sector__respaldoArray=array();
      $orden__respaldoArray=array();
      $creado__respaldoArray=array();

      $consulta = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,actividades,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,tipo,provincia,canton,parroquia,pais,ciudad,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,orden,creado FROM incentivorespaldo.proyecto_cronograma_actividades WHERE estado='A' AND tipoIngreso='modificacion' AND codigo='$codigo';");

      foreach ($consulta as $valor) {

        array_push($idComponentes__respaldoArray, $valor["idComponentes"]);
        array_push($idNivel1__respaldoArray, $valor["idNivel1"]);
        array_push($detalle__respaldoArray, $valor["detalle"]);
        array_push($actividades__respaldoArray, $valor["actividades"]);
        array_push($enero__respaldoArray, $valor["enero"]);
        array_push($febrero__respaldoArray, $valor["febrero"]);
        array_push($marzo__respaldoArray, $valor["marzo"]);
        array_push($abril__respaldoArray, $valor["abril"]);
        array_push($mayo__respaldoArray, $valor["mayo"]);
        array_push($junio__respaldoArray, $valor["junio"]);
        array_push($julio__respaldoArray, $valor["julio"]);
        array_push($agosto__respaldoArray, $valor["agosto"]);
        array_push($septiembre__respaldoArray, $valor["septiembre"]);
        array_push($octubre__respaldoArray, $valor["octubre"]);
        array_push($noviembre__respaldoArray, $valor["noviembre"]);
        array_push($diciembre__respaldoArray, $valor["diciembre"]);
        array_push($tipo__respaldoArray, $valor["tipo"]);
        array_push($provincia__respaldoArray, $valor["provincia"]);
        array_push($canton__respaldoArray, $valor["canton"]);
        array_push($parroquia__respaldoArray, $valor["parroquia"]);
        array_push($pais__respaldoArray, $valor["pais"]);
        array_push($ciudad__respaldoArray, $valor["ciudad"]);
        array_push($fecha__respaldoArray, $valor["fecha"]);
        array_push($hora__respaldoArray, $valor["hora"]);
        array_push($activo__respaldoArray, $valor["activo"]);
        array_push($anio__respaldoArray, $valor["anio"]);
        array_push($codigo__respaldoArray, $valor["codigo"]);
        array_push($idCredencial__respaldoArray, $valor["idCredencial"]);
        array_push($nivel__respaldoArray, $valor["nivel"]);
        array_push($sector__respaldoArray, $valor["sector"]);
        array_push($orden__respaldoArray, $valor["orden"]);
        array_push($creado__respaldoArray, $valor["creado"]);

      }

      $idComponentes__originalArray=array();
      $idNivel1__originalArray=array();
      $actividades__originalArray=array();
      $enero__originalArray=array();
      $febrero__originalArray=array();
      $marzo__originalArray=array();
      $abril__originalArray=array();
      $mayo__originalArray=array();
      $junio__originalArray=array();
      $julio__originalArray=array();
      $agosto__originalArray=array();
      $septiembre__originalArray=array();
      $octubre__originalArray=array();
      $noviembre__originalArray=array();
      $diciembre__originalArray=array();
      $tipo__originalArray=array();
      $provincia__originalArray=array();
      $canton__originalArray=array();
      $parroquia__originalArray=array();
      $pais__originalArray=array();
      $ciudad__originalArray=array();
      $fecha__originalArray=array();
      $hora__originalArray=array();
      $activo__originalArray=array();
      $anio__originalArray=array();
      $codigo__originalArray=array();
      $idCredencial__originalArray=array();
      $nivel__originalArray=array();
      $sector__originalArray=array();
      $orden__originalArray=array();
      $creado__originalArray=array();

      $consulta = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,actividades,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,tipo,provincia,canton,parroquia,pais,ciudad,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,orden,creado FROM proyecto_cronograma_actividades WHERE codigo='$codigo';");

      foreach ($consulta as $valor) {

        array_push($idComponentes__originalArray, $valor["idComponentes"]);
        array_push($idNivel1__originalArray, $valor["idNivel1"]);
        array_push($detalle__originalArray, $valor["detalle"]);
        array_push($actividades__originalArray, $valor["actividades"]);
        array_push($enero__originalArray, $valor["enero"]);
        array_push($febrero__originalArray, $valor["febrero"]);
        array_push($marzo__originalArray, $valor["marzo"]);
        array_push($abril__originalArray, $valor["abril"]);
        array_push($mayo__originalArray, $valor["mayo"]);
        array_push($junio__originalArray, $valor["junio"]);
        array_push($julio__originalArray, $valor["julio"]);
        array_push($agosto__originalArray, $valor["agosto"]);
        array_push($septiembre__originalArray, $valor["septiembre"]);
        array_push($octubre__originalArray, $valor["octubre"]);
        array_push($noviembre__originalArray, $valor["noviembre"]);
        array_push($diciembre__originalArray, $valor["diciembre"]);
        array_push($tipo__originalArray, $valor["tipo"]);
        array_push($provincia__originalArray, $valor["provincia"]);
        array_push($canton__originalArray, $valor["canton"]);
        array_push($parroquia__originalArray, $valor["parroquia"]);
        array_push($pais__originalArray, $valor["pais"]);
        array_push($ciudad__originalArray, $valor["ciudad"]);
        array_push($fecha__originalArray, $valor["fecha"]);
        array_push($hora__originalArray, $valor["hora"]);
        array_push($activo__originalArray, $valor["activo"]);
        array_push($anio__originalArray, $valor["anio"]);
        array_push($codigo__originalArray, $valor["codigo"]);
        array_push($idCredencial__originalArray, $valor["idCredencial"]);
        array_push($nivel__originalArray, $valor["nivel"]);
        array_push($sector__originalArray, $valor["sector"]);
        array_push($orden__originalArray, $valor["orden"]);
        array_push($creado__originalArray, $valor["creado"]);

      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_cronograma_actividades WHERE codigo='$codigo';");
      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET estado='T',idSolicitud='$idSolicitud' WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A';");

      foreach ($idComponentes__originalArray as  $clave =>  $valor) {
       
        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes','idNivel1','actividades','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','tipo','provincia','canton','parroquia','pais','ciudad','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','orden','creado','tipoIngreso','estado','idSolicitud'], array(

          ':idComponentes' =>$idComponentes__originalArray[$clave],
          ':idNivel1' =>$idNivel1__originalArray[$clave],
          ':actividades' =>$actividades__originalArray[$clave],
          ':enero' =>$enero__originalArray[$clave],
          ':febrero' =>$febrero__originalArray[$clave],
          ':marzo' =>$marzo__originalArray[$clave],
          ':abril' =>$abril__originalArray[$clave],
          ':mayo' =>$mayo__originalArray[$clave],
          ':junio' =>$junio__originalArray[$clave],
          ':julio' =>$julio__originalArray[$clave],
          ':agosto' =>$agosto__originalArray[$clave],
          ':septiembre' =>$septiembre__originalArray[$clave],
          ':octubre' =>$octubre__originalArray[$clave],
          ':noviembre' =>$noviembre__originalArray[$clave],
          ':diciembre' =>$diciembre__originalArray[$clave],
          ':tipo' =>$tipo__originalArray[$clave],
          ':provincia' =>$provincia__originalArray[$clave],
          ':canton' =>$canton__originalArray[$clave],
          ':parroquia' =>$parroquia__originalArray[$clave],
          ':pais' =>$pais__originalArray[$clave],
          ':ciudad' =>$ciudad__originalArray[$clave],
          ':fecha' =>$fecha__originalArray[$clave],
          ':hora' =>$hora__originalArray[$clave],
          ':activo' =>$activo__originalArray[$clave],
          ':anio' =>$anio__originalArray[$clave],
          ':codigo' =>$codigo__originalArray[$clave],
          ':idCredencial' =>$idCredencial__originalArray[$clave],
          ':nivel' =>$nivel__originalArray[$clave],
          ':sector' =>$sector__originalArray[$clave],
          ':orden' =>$orden__originalArray[$clave],
          ':creado' =>$creado__originalArray[$clave],
          ':tipoIngreso' =>'modificacionOriginal',
          ':estado' =>'I',
          ':idSolicitud' =>$idSolicitud,

        ));

      }


      foreach ($idComponentes__respaldoArray as  $clave =>  $valor) {
       
        $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes','idNivel1','actividades','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','tipo','provincia','canton','parroquia','pais','ciudad','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','orden','creado'], array(

          ':idComponentes' =>$idComponentes__respaldoArray[$clave],
          ':idNivel1' =>$idNivel1__respaldoArray[$clave],
          ':actividades' =>$actividades__respaldoArray[$clave],
          ':enero' =>$enero__respaldoArray[$clave],
          ':febrero' =>$febrero__respaldoArray[$clave],
          ':marzo' =>$marzo__respaldoArray[$clave],
          ':abril' =>$abril__respaldoArray[$clave],
          ':mayo' =>$mayo__respaldoArray[$clave],
          ':junio' =>$junio__respaldoArray[$clave],
          ':julio' =>$julio__respaldoArray[$clave],
          ':agosto' =>$agosto__respaldoArray[$clave],
          ':septiembre' =>$septiembre__respaldoArray[$clave],
          ':octubre' =>$octubre__respaldoArray[$clave],
          ':noviembre' =>$noviembre__respaldoArray[$clave],
          ':diciembre' =>$diciembre__respaldoArray[$clave],
          ':tipo' =>$tipo__respaldoArray[$clave],
          ':provincia' =>$provincia__respaldoArray[$clave],
          ':canton' =>$canton__respaldoArray[$clave],
          ':parroquia' =>$parroquia__respaldoArray[$clave],
          ':pais' =>$pais__respaldoArray[$clave],
          ':ciudad' =>$ciudad__respaldoArray[$clave],
          ':fecha' =>$fecha__respaldoArray[$clave],
          ':hora' =>$hora__respaldoArray[$clave],
          ':activo' =>$activo__respaldoArray[$clave],
          ':anio' =>$anio__respaldoArray[$clave],
          ':codigo' =>$codigo__respaldoArray[$clave],
          ':idCredencial' =>$idCredencial__respaldoArray[$clave],
          ':nivel' =>$nivel__respaldoArray[$clave],
          ':sector' =>$sector__respaldoArray[$clave],
          ':orden' =>$orden__respaldoArray[$clave],
          ':creado' =>$creado__respaldoArray[$clave],

        ));

      }

      return 1;

    }

    public function enviar__proyecto__comite__calificacion__a__b__c__negacion($post) {


      $idCredencial=$post["idCredencial"];
      $codigoProyecto=$post["codigoProyecto"];
      $codigoUsuario=$post["codigoUsuario"];
      $enviarInfra=$post["enviarInfra"];
      $fisicamente=$post["fisicamente"];
      $idRol=$post["idRol"];
      $textoNegacion=$post["textoNegacion"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigoProyecto';");      
      foreach ($consulta as $valor) {
          $idBdEnviado=$valor["id"];
      }

      $consulta__2=$this->constructor->select__general__incentivo("SELECT idSolicitud,casoModificar FROM proyecto_modificacion_solicitud WHERE codigo='$codigoUsuario' AND estado='APROBADO';");      
      foreach ($consulta__2 as $valor) {
          $idSolicitud=$valor["idSolicitud"];
      }


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='TERMINADA', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");
      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estado='TERMINADO',estadoModificacion='NEGADO', observacionFinal='$textoNegacion' WHERE idSolicitud='$idSolicitud';");
      $mensajeTipo='Modificación culminada';  


      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado';");

      foreach ($consultaEnviado as $valor) {

        $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
        $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

      }

      $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora'], array(
        ':idFisicamenteActual' =>$fisicamente,
        ':idUsuarioActual' => $idUsuario,
        ':idFisicamenteNuevo' => 0,
        ':idUsuarioNuevo' => 0,
        ':idEnviado' => $idBdEnviado,
        ':tipo' =>$mensajeTipo,
        ':fecha' => $this->fecha,
        ':hora' => $this->hora,
      ));


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado_recomendacion_modificacion SET idUsuario2='$idUsuario',idRol2='$idRol', fecha2='".$this->fecha."',hora2='".$this->hora."' WHERE idEnviado='$idBdEnviado' AND idSolicitud='$idSolicitud';");

      return 1;

    } 

    public function enviar__proyecto__comite__calificacion__a__c($post) {


      $idCredencial=$post["idCredencial"];
      $codigoProyecto=$post["codigoUsuario"];
      $enviarInfra=$post["enviarInfra"];
      $fisicamente=$post["fisicamente"];
      $idRol=$post["idRol"];


      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);


      $codigoUsuarioObtenido=$this->obtener__codigo__borrador($codigoProyecto);
      $banderaComponentes=$this->obtener__componentes__necesarios($codigoUsuarioObtenido);
      $banderaSector=$this->obtener__sector__necesarios($codigoUsuarioObtenido);

      $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);


      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        $nombreTabla__recomendacion="proyecto_enviado_recomendacion_infraestructura";
        $nomenclatura="infraestructura";
      }else{
        $nombreTabla__recomendacion="proyecto_enviado_recomendacion";
        $nomenclatura="tecnico";
      }

      $consultaCodigoUsuario__traido=$this->obtener__codigo__usuario($codigoProyecto);
      foreach ($consultaCodigoUsuario__traido as $valor__traido) {
        $codigoUsuario__traido=$valor__traido["codigoUsuario"];
      }

      $codigo=$codigoUsuario__traido;

      $consultaCasoModificar=$this->codigo__modificacion($codigoUsuario__traido);
      foreach ($consultaCasoModificar as $valorCasoModificar) {
        $casoModificarBd__1=$valorCasoModificar["casoModificar"];
      }

      $consulta=$this->codigo__modificacion($codigo);
      foreach ($consulta as $valor) {
        $idSolicitud=$valor["idSolicitud"];
      }

      $consulta=$this->obtenerUsuario__porCodigo($codigo);
      foreach ($consulta as $valor) {
        $idEnviado=$valor["id"];
      }


      $camposArray=$this->obtener__texto__recomendacion($nomenclatura,$idEnviado,$idSolicitud);


      if($enviarInfra==="true" && $casoModificarBd__1==='b'){

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='ENVIADO INFRA', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Enviado a infraestructura en etapa de modificación';

      }else if($enviarInfra==="true" && ($casoModificarBd__1==='a' || $casoModificarBd__1==='c')){

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='ENVIADO INFRA', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Enviado a infraestructura en etapa de modificación';

      }else if (intval($informacionUsuario[5])===15 && intval($banderaComponentes)===1 && intval($banderaSector)===0 && $casoModificarBd__1==='b') {

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='Comite', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Asignado al cómite en etapa de modificación';

      }else if(intval($informacionUsuario[5])!==15 && intval($banderaComponentes)===1 && intval($banderaSector)===1){

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='ENVIADO INFRA', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Enviado a infraestructura en etapa de modificación';

      }else if($casoModificarBd__1==='b'){

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='Comite', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Asignado al cómite en etapa de modificación';

      }else{

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='TERMINADA', idUsuarioModificacionRecomienda='0',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");

        if($camposArray[0]==="aprobado"){
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estado='TERMINADO',estadoModificacion='APROBADO' WHERE idSolicitud='$idSolicitud';");
        }else{
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estado='TERMINADO',estadoModificacion='NEGADO' WHERE idSolicitud='$idSolicitud';");
        }

        if($camposArray[0]==="aprobado" && $casoModificarBd__1==='c'){
          $this->actualizar__descripcion__proyecto__en__modificacion($codigo,$idSolicitud);
        }

        if($camposArray[0]==="aprobado"){

          $this->actualizar__presupuesto__proyecto__en__modificacion($codigo,$idSolicitud);
          $this->actualizar__cronogramaDeActividades__proyecto__en__modificacion($codigo,$idSolicitud);

        }
        
        $mensajeTipo='Modificación culminada';  

      }


      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado';");

      foreach ($consultaEnviado as $valor) {

        $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
        $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

      }


      $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora'], array(
        ':idFisicamenteActual' =>$fisicamente,
        ':idUsuarioActual' => $idUsuario,
        ':idFisicamenteNuevo' => 0,
        ':idUsuarioNuevo' => 0,
        ':idEnviado' => $idBdEnviado,
        ':tipo' =>$mensajeTipo,
        ':fecha' => $this->fecha,
        ':hora' => $this->hora,
      ));

      if (intval($fisicamente)===15) {
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado_recomendacion_infraestructura_modificacion SET idUsuario2='$idUsuario',idRol2='$idRol', fecha2='".$this->fecha."',hora2='".$this->hora."' WHERE idEnviado='$idBdEnviado' AND idSolicitud='$idSolicitud';");
      }else{
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado_recomendacion_modificacion SET idUsuario2='$idUsuario',idRol2='$idRol', fecha2='".$this->fecha."',hora2='".$this->hora."' WHERE idEnviado='$idBdEnviado' AND idSolicitud='$idSolicitud';");
      }

      return 1;

    } 


    public function enviar__proyecto__comite__calificacion($post) {


      $idCredencial=$post["idCredencial"];
      $codigoProyecto=$post["codigoUsuario"];
      $enviarInfra=$post["enviarInfra"];
      $fisicamente=$post["fisicamente"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);


      $codigoUsuarioObtenido=$this->obtener__codigo__borrador($codigoProyecto);
      $banderaComponentes=$this->obtener__componentes__necesarios($codigoUsuarioObtenido);
      $banderaSector=$this->obtener__sector__necesarios($codigoUsuarioObtenido);

      $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);


      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        $nombreTabla__recomendacion="proyecto_enviado_recomendacion_infraestructura";
      }else{
        $nombreTabla__recomendacion="proyecto_enviado_recomendacion";
      }

      if($enviarInfra==="true"){
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='ENVIADO INFRA', idUsuarioModificacionRecomienda='0',idUsuario='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Enviado a infraestructura en etapa de modificación';
      }else if (intval($informacionUsuario[5])===15 && intval($banderaComponentes)===1 && intval($banderaSector)===0) {
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='Comite', idUsuarioModificacionRecomienda='0',idUsuario='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Asignado al cómite en etapa de modificación';
      }else if(intval($informacionUsuario[5])!==15 && intval($banderaComponentes)===1 && intval($banderaSector)===1){
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='ENVIADO INFRA', idUsuarioModificacionRecomienda='0',idUsuario='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Enviado a infraestructura en etapa de modificación';
      }else{
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='Comite', idUsuarioModificacionRecomienda='0',idUsuario='0' WHERE id='$idBdEnviado';");
        $mensajeTipo='Asignado al cómite en etapa de modificación';
      }

      
      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado';");

      foreach ($consultaEnviado as $valor) {

        $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
        $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

      }



      $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora'], array(
        ':idFisicamenteActual' =>$fisicamente,
        ':idUsuarioActual' => $idUsuario,
        ':idFisicamenteNuevo' => 0,
        ':idUsuarioNuevo' => 0,
        ':idEnviado' => $idBdEnviado,
        ':tipo' =>$mensajeTipo,
        ':fecha' => $this->fecha,
        ':hora' => $this->hora,
      ));




      $informacionRecomendacion = $this->constructor->select__general__incentivo("SELECT idEnviado, idUsuario, idRol, texto, fecha, hora,recomendacion FROM $nombreTabla__recomendacion WHERE idEnviado='$idBdEnviado' AND tipo='MODIFICACION';");

      foreach ($informacionRecomendacion as $valor) {
        $idEnviadoBd=$valor["idEnviado"];
        $idUsuarioBd=$valor["idUsuario"];
        $idRolBd=$valor["idRol"];
        $textoBd=$valor["texto"];
        $fechaBd=$valor["fecha"];
        $horaBd=$valor["hora"];
        $recomendacionBd=$valor["recomendacion"];
      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."' AND tipo='MODIFICACION' AND estado='A';");


      return $this->constructor->inserta__general__incentivo("$nombreTabla__recomendacion", ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','idUsuario2','idRol2','fecha2','hora2','recomendacion'], array(
          ':idEnviado' =>$idBdEnviado,
          ':idUsuario'=>$idUsuarioBd,
          ':idRol'=>intval($idRolBd),
          ':texto'=>$textoBd,
          ':fecha'=>$fechaBd,
          ':hora'=>$horaBd,
          ':estado'=>'A',
          ':tipo'=>'MODIFICACION',
          ':idUsuario2'=>$idUsuario,
          ':idRol2'=>intval($informacionUsuario[4]),
          ':fecha2'=>$this->fecha,
          ':hora2'=>$this->hora,
          ':recomendacion'=>$recomendacionBd,
      ));


    } 

    public function obtener__codigo__borrador($codigoProyecto) {

      $consulta__obtener=$this->constructor->select__general__incentivo("SELECT codigoUsuario FROM proyecto_enviado WHERE codigo='$codigoProyecto';");
      foreach ($consulta__obtener as $valor) {
        $codigoUsuarioBd=$valor["codigoUsuario"];
      }

      return $codigoUsuarioBd;

    } 

    public function obtener__componentes__necesarios($codigo) {

      $consulta__obtener=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto FROM proyecto_componente_usuario WHERE codigo='$codigo' AND idComponentes='5';");
      foreach ($consulta__obtener as $valor) {
        $idComponentesProyectoBd=$valor["idComponentesProyecto"];
      }

      if (empty($idComponentesProyectoBd)) {
        return 0;
      }else{
        return 1;
      }

    } 

    public function obtener__sector__necesarios($codigo) {

      $consulta__obtener=$this->constructor->select__general__incentivo("SELECT idSector FROM proyecto_sector WHERE codigo='$codigo';");
      foreach ($consulta__obtener as $valor) {
        $idSectorBd=$valor["idSector"];
      }

      if (empty($idSectorBd)) {
        return 0;
      }else{
        return 1;
      }

    } 

   public function regresar__analista__recomendacion($post) {

      $codigoUsuario=$post["codigo"];
      $codigoProyecto=$post["codigoUsuario"];
      $personaReasignar=$post["personaReasignar"];
      $textoRegresar=$post["textoRegresar"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='RECOMENDADO', idUsuarioModificacionRecomienda=NULL,idUsuarioModificacion='$personaReasignar' WHERE id='$idBdEnviado';");

      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado';");

      foreach ($consultaEnviado as $valor) {

        $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
        $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

      }

      $informacionUsuario=$this->obtener__usuario($personaReasignar);

      $consultaIdSolicitud=$this->codigo__modificacion($codigoUsuario);
      foreach ($consultaIdSolicitud as $valorE) {
        $idSolicitudBd=$valorE["idSolicitud"];
      }


      if (intval($informacionUsuario[5])===15) {
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_enviado_recomendacion_infraestructura_modificacion WHERE idSolicitud='$idSolicitudBd' AND idEnviado='$idBdEnviado';");
      }else{
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_enviado_recomendacion_modificacion WHERE idSolicitud='$idSolicitudBd' AND idEnviado='$idBdEnviado';");
      }

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora', 'textoDevuelto'], array(
        ':idFisicamenteActual' =>$idFisicamenteNuevoBd,
        ':idUsuarioActual' => $idUsuarioNuevoBd,
        ':idFisicamenteNuevo' => $informacionUsuario[5],
        ':idUsuarioNuevo' => $personaReasignar,
        ':idEnviado' => $idBdEnviado,
        ':tipo' =>'Devuelto superior inmediato en etapa de modificación',
        ':fecha' => $this->fecha,
        ':hora' => $this->hora,
        ':textoDevuelto' => $textoRegresar,
      ));


      return 1;

    } 


    public function bandeja__recomendados($post) {

      $idCredencial=$post["idCredencial"];
      $idRol=$post["idRol"];
      $fisicamenteEstructura=$post["fisicamenteEstructura"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);

      return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ') AS sector, FORMAT(SUM(d.total), 2) AS monto,a.fecha, (SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',' ')))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,a.estadoCalificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_modificacion_solicitud AS j ON j.codigo=a.codigoUsuario WHERE idUsuarioModificacionRecomienda='$idUsuario' GROUP BY c.codigo,d.codigo;");


    } 

    public function recomendar__analista($post){

      $codigo=$post["codigo"];
      $codigoProyecto=$post["codigoProyecto"];
      $idCredencial=$post["idCredencial"];
      $textoRecomendacion=$post["textoRecomendacion"];
      $opcionRecomendacion=$post["opcionRecomendacion"];

      if(!empty($codigo)){

        $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);

        $idUsuario=$this->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->obtener__usuario($idUsuario);
        $informacionUsuario__superiorInmediato=$this->obtener__usuario($informacionUsuario[3]);

         $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado' ORDER BY id DESC LIMIT 1;");

        foreach ($consultaEnviado as $valor) {

          $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
          $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

        }

        $consultaIdSolicitud=$this->codigo__modificacion($codigo);
        foreach ($consultaIdSolicitud as $valorRecibido) {
          $idSolicitudBd=$valorRecibido["idSolicitud"];
        }

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='Recomendado Modificación', idUsuarioModificacionRecomienda='$informacionUsuario__superiorInmediato[6]',idUsuarioModificacion='0' WHERE id='$idBdEnviado';");


        if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
          $nombreTabla__recomendacion="proyecto_enviado_recomendacion_infraestructura_modificacion";
        }else{
          $nombreTabla__recomendacion="proyecto_enviado_recomendacion_modificacion";
        }


        $this->constructor->actualiza__general__incentivo("DELETE FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."' AND idSolicitud='$idSolicitudBd';");

        $this->constructor->inserta__general__incentivo($nombreTabla__recomendacion, ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','idUsuario2','idRol2','fecha2','hora2','recomendacion','idSolicitud'], array(
          ':idEnviado' =>$this->funcion__obtener__enviado($codigoProyecto),
          ':idUsuario'=>$informacionUsuario[6],
          ':idRol'=>intval($informacionUsuario[4]),
          ':texto'=>$textoRecomendacion,
          ':fecha'=>$this->fecha,
          ':hora'=>$this->hora,
          ':estado'=>'A',
          ':tipo'=>'MODIFICACION',
          ':idUsuario2'=>NULL,
          ':idRol2'=>NULL,
          ':fecha2'=>NULL,
          ':hora2'=>NULL,
          ':recomendacion'=>$opcionRecomendacion,
          ':idSolicitud'=>$idSolicitudBd,
        ));

        return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora', 'textoDevuelto'], array(
          ':idFisicamenteActual' =>$idFisicamenteNuevoBd,
          ':idUsuarioActual' => $idUsuarioNuevoBd,
          ':idFisicamenteNuevo' => $informacionUsuario__superiorInmediato[5],
          ':idUsuarioNuevo' => $informacionUsuario__superiorInmediato[6],
          ':idEnviado' => $idBdEnviado,
          ':tipo' =>'Recomendado analista etapa modificación',
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':textoDevuelto' => $textoRecomendacion,
        ));

      }


    }


    public function generar__informe($post,$comite=false) {

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $opcionRecomendacion=$post["opcionRecomendacion"];
        $textoRecomendacion=$post["textoRecomendacion"];

        $consultaSolicitud=$this->codigo__modificacion($codigo);

        foreach ($consultaSolicitud as $valorC) {
          $idSolicitudBdC=$valorC["idSolicitud"];
        }

        $consultaCasos=$this->codigo__modificacion($codigo);

        foreach ($consultaCasos as $valorCaso) {
          $casoModificarBd=$valorCaso["casoModificar"];
        }


        $idUsuario=$this->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->obtener__usuario($idUsuario);
        $informacionUsuario__superiorInmediato=$this->obtener__usuario($informacionUsuario[3]);
        $informacionUsuario__superiorInmediatoFinal=$this->obtener__usuario($informacionUsuario__superiorInmediato[3]);


        if ($casoModificarBd==="a" || $casoModificarBd==="c") {
    
          $contenido=$this->informePdf->portada__informe__notificacion($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
          $contenido.=$this->informePdf->portada__informe__antecedente($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
          $contenido.=$this->informePdf->base__legal__notifiacion($codigo,$informacionUsuario__superiorInmediatoFinal[1]);
          $contenido.=$this->informePdf->notificacion_legal_notificacion__caso__a__c($codigo,$informacionUsuario__superiorInmediatoFinal[1],$opcionRecomendacion,$informacionUsuario);

        }else{

          $contenido=$this->informePdf->portada__informe__modificacion($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
          $contenido.=$this->informePdf->datos__generales__modificado($codigo,$idSolicitudBdC);
          $contenido.=$this->informePdf->presupuesto__modificacion($codigo,$idSolicitudBdC);
          $contenido.=$this->informePdf->analisisTecnico__modificacion($codigo,$informacionUsuario,$opcionRecomendacion,$textoRecomendacion);

          if ($comite===false && intval($informacionUsuario[4])===3) {
              $informacionUsuario__superiorInmediato=[];
          }

          $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

        }


        $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

        return $pdfResult;

    } 


    public function obtener__id__usuario($idCredencial) {

      $consultaUsuario = $this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($consultaUsuario as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $idUsuarioBd;

    }

    public function seleccionarFuncionario__id($idCredencial) {

      $personCargo=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($personCargo as $valor) {
        $idFuncionarioBd=$valor["idFuncionario"];
      }

      return $idFuncionarioBd;

    }



    public function obtener__nombreTabla__observacion($tipo) {

      switch ($tipo) {

        case 'descripcion':
          return 'proyecto_observacion_descripcion_modificacion';
        break;

        case 'componentesValorizados':
          return 'proyecto_observacion_componentes_modificacion';
        break;

        case 'cronogramaActividades':
          return 'proyecto_observacion_cronogramaactividades_modificacion';
        break;

      }

    } 


    public function observacion__ministerio($post) {

      $idCredencial=$post["idCredencial"];
      $tipo=$post["tipo"];
      $calificacion=$post["calificacion"];
      $textoObservacion=$post["textoObservacion"];
      $codigo=$post["codigo"];
      $codigoProyecto=$post["codigoProyecto"];

      $nombreTabla=$this->obtener__nombreTabla__observacion($tipo);
      $idFuncionario=$this->seleccionarFuncionario__id($idCredencial);

      if ($textoObservacion==="null" || empty($textoObservacion)) {
        $textoObservacion=null;
      }


      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      $this->constructor->actualiza__general__incentivo("UPDATE $nombreTabla SET estado='I' WHERE codigo='$codigoProyecto';");

      if ($textoObservacion===null && $calificacion==="noValidar") {
        return 0;
      }else{

        if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
          return $this->constructor->inserta__general__incentivo($nombreTabla,['idFuncionario','calificacion','textoObservacion','codigo','codigoUsuario','estado','fecha','hora','infra'],array(':idFuncionario' => $idFuncionario,':calificacion' => $calificacion,':textoObservacion' => $textoObservacion,':codigo' => $codigoProyecto,':codigoUsuario' => $codigo,':estado' => "A",':fecha' => $this->fecha,':hora' => $this->hora,':infra' => 'A'));
        }else{
          return $this->constructor->inserta__general__incentivo($nombreTabla,['idFuncionario','calificacion','textoObservacion','codigo','codigoUsuario','estado','fecha','hora'],array(':idFuncionario' => $idFuncionario,':calificacion' => $calificacion,':textoObservacion' => $textoObservacion,':codigo' => $codigoProyecto,':codigoUsuario' => $codigo,':estado' => "A",':fecha' => $this->fecha,':hora' => $this->hora));
        }

      }

    }     

    public function observacion__cronogramaActividades($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','NO VALIDADO','VALIDADO') AS calificacionTexto  FROM proyecto_observacion_cronogramaactividades_modificacion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','NO VALIDADO','VALIDADO') AS calificacionTexto  FROM proyecto_observacion_cronogramaactividades_modificacion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    } 

    public function observacion__componentes($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','NO VALIDADO','VALIDADO') AS calificacionTexto  FROM proyecto_observacion_componentes_modificacion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','NO VALIDADO','VALIDADO') AS calificacionTexto  FROM proyecto_observacion_componentes_modificacion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    }     

    public function observacion__descripcion($post) {

      $idCredencial=$post["idCredencial"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
          return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','NO VALIDADO','VALIDADO') AS calificacionTexto  FROM proyecto_observacion_descripcion_modificacion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','NO VALIDADO','VALIDADO') AS calificacionTexto  FROM proyecto_observacion_descripcion_modificacion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra IS NULL;");
      }

    } 


    public function funcion__obtener__informacion__tipo__recibidos($codigo){

        $idUsuarioConsulta=$this->constructor->select__general__incentivo("SELECT IF(a.estadoCalificacion='ENVIADO INFRA','RECIBIDO DIRECCIÓN TÉCNICA',IF(e.idComponentes='5' AND c.idSector IS NULL,'RECIBIDO',IF(e.idComponentes='5' AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA'))) AS estado FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE a.codigo='$codigo'   GROUP BY c.codigo,d.codigo;");      
        foreach ($idUsuarioConsulta as $valor) {
          $estadoBd=$valor["estado"];
        }

        return $estadoBd;

    }  

    public function seleccionarProfesional($idCredencial) {

      $personCargo=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($personCargo as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $idUsuarioBd;

    }

    public function obtener__usuario($idUsuario) {

      $array=array();
      $consultaUsuario = $this->constructor->select__general__talento("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionFisicamenteEstructura, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionFisicamente,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(e.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS puestoInstitucional,a.PersonaACargo,b.id_rol,a.fisicamenteEstructura,a.id_usuario  FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_roles AS c ON c.id_rol=b.id_rol INNER JOIN th_fisicamenteestructura AS d ON a.fisicamenteEstructura=d.id_FisicamenteEstructura INNER JOIN th_puestoinstitucional AS e ON e.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='$idUsuario';");

      foreach ($consultaUsuario as $valor) {
        array_push($array,$valor["nombreCompleto"]);
        array_push($array,$valor["descripcionFisicamente"]);
        array_push($array,$valor["puestoInstitucional"]);
        array_push($array,$valor["PersonaACargo"]);
        array_push($array,$valor["id_rol"]);
        array_push($array,$valor["fisicamenteEstructura"]);
        array_push($array,$valor["id_usuario"]);
      }

      return $array;

    }

    public function funcion__obtener__enviado($codigo){

        $idUsuarioConsulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigo';");      
        foreach ($idUsuarioConsulta as $valor) {
          $idBd=$valor["id"];
        }

        return $idBd;

    }

    public function obtener__fisicamenteUsuarios($idUsuario) {

      $idUsuarioConsulta=$this->constructor->select__general__talento("SELECT fisicamenteEstructura FROM th_usuario WHERE id_usuario='$idUsuario';");

      foreach ($idUsuarioConsulta as $valor) {
        $fisicamenteEstructuraBd=$valor["fisicamenteEstructura"];
      }

      return $fisicamenteEstructuraBd;

    }


    public function funcion__asignar($idUsuarioResignar,$idRol,$codigo,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar) {

      $fisicamenteEnviar=$this->obtener__fisicamenteUsuarios($idUsuarioResignar);
      $idEnviado=$this->funcion__obtener__enviado($codigo);

      if($estadoEnviado==="RECIBIDO DIRECCIÓN TÉCNICA"){
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idUsuarioModificacion='$idUsuarioResignar',estadoModificacion='ENVIADO' WHERE codigo='$codigo';");
      }else if($estadoEnviado==="PENDIENTE REVISIÓN TÉCNICA"){
         $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idUsuarioModificacion='$idUsuarioResignar',estadoModificacion='ENVIADO' WHERE codigo='$codigo';");
      }else{
         $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idUsuarioModificacion='$idUsuarioResignar',estadoModificacion='ENVIADO' WHERE codigo='$codigo';");
      }

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio'],array(':idFisicamenteActual' => $fisicamenteEstructuraActual,':idUsuarioActual' => $idUsuario,':idFisicamenteNuevo' => $fisicamenteEnviar,':idUsuarioNuevo' => $idUsuarioResignar,':idEnviado' => $idEnviado,':tipo' => "Reasignado etapa de Modificación",':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $textoRegresar));

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

      $estadoEnviado=$this->funcion__obtener__informacion__tipo__recibidos($codigo);

      $idUsuario=$this->seleccionarProfesional($post["idCredencial"]);
      $informacionUsuario=$this->obtener__usuario($idUsuario);
      $idEnviado=$this->funcion__obtener__enviado($codigo);


      return $this->funcion__asignar($personaReasignar,$idRolActual,$codigo,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar);


    } 

    public function obtenerPresupuestoNiveles__componentesUnicos__footer__2($codigo,$anio,$tiposComponentes,$idTramite) {

      if(!empty($idTramite) && $idTramite!=="null"){
        return $this->constructor->select__general__incentivo("SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total FROM incentivorespaldo.proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND estado='A' AND tipoIngreso='modificacion';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total FROM proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");
      }

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos__2($codigo,$anio,$tiposComponentes,$idTramite) {

      if(!empty($idTramite) && $idTramite!=="null"){
        return $this->constructor->select__general__incentivo("SELECT b.idComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idComponentes=b.idComponentes WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idTramite' GROUP BY b.idComponentes ORDER BY a.id;");
      }else{
        return $this->constructor->select__general__incentivo("SELECT b.idComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idComponentes=b.idComponentes WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' GROUP BY b.idComponentes ORDER BY a.id;");
      }

    } 

    public function obtenerPresupuestoNiveles__2($codigo,$anio,$tiposComponentes,$idTramite) {

      if(!empty($idTramite) && $idTramite!=="null"){
        return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.numeral FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.numeral) AS rotulo,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.detalle, a.justificacion,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.total,a.anio,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1 FROM incentivorespaldo.proyecto_presupuesto AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idTramite' ORDER BY a.id;");
      }else{
        return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.numeral FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.numeral) AS rotulo,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.detalle, a.justificacion,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.total,a.anio,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1 FROM proyecto_presupuesto AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' ORDER BY a.id;");
      }

    } 


    public function orden__recibidos($orden,$idRol,$idUsuario,$fisicamenteEstructura) {


      if (intval($orden)===1 && (intval($idRol)===2 && intval($fisicamenteEstructura)===15)) {
        return "((estadoModificacion='ENVIADO' OR estadoModificacion='ENVIADO INFRA') AND (idUsuarioModificacion IS NULL OR idUsuarioModificacion='0')) OR (a.estadoModificacion='ENVIADO INFRA')";
      }else if (intval($orden)===1 && (intval($idRol)===7 || intval($idRol)===2 || intval($idRol)===4)) {
        return "(estadoModificacion='ENVIADO' AND idUsuarioModificacion IS NULL)";
      }else if(intval($orden)===2 && intval($idRol)===2){
        return "(estadoModificacion='ENVIADO' AND idUsuarioModificacion IS NULL)";
      }else if(intval($orden)===2 && intval($idRol)===3){
        return "idUsuarioModificacion='$idUsuario' AND (estadoModificacion='ENVIADO' OR estadoModificacion='ENVIADO INFRA')";
      }else{
        return "idUsuarioModificacion='$idUsuario'";
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
      }else if(intval($fisicamenteEstructura)===43){
        return "(EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='5') AND c.idSector=a1.idSector) || EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='4') AND c.idSector=a1.idSector))";
      }else if(intval($fisicamenteEstructura)===40){
        return "(EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='1') AND c.idSector=a1.idSector) || EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='2' OR c.idSector='3') AND c.idSector=a1.idSector))";
      }

    }

    public function bandeja__recibidos__modificacion($idCredencial,$idRol,$fisicamenteEstructura) {

      $ordenBd=$this->orden__obtenido($idRol,$fisicamenteEstructura);

      $funcionarioEnviado=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($funcionarioEnviado as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      $consultaOrden=$this->orden__recibidos($ordenBd,$idRol,$idUsuarioBd,$fisicamenteEstructura);
      $consultaSectores=$this->sectores__recibidos($fisicamenteEstructura);

      if(intval($idRol)===4 && intval($fisicamenteEstructura)===1 ){

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, (SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha,IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA')) AS estado,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,a.estadoCalificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE  (a.estadoCalificacion IS NULL OR a.estadoCalificacion='ENVIADO INFRA' OR a.estadoCalificacion='observado' OR a.estadoCalificacion='Recomendado' OR a.estadoCalificacion='rectificado') AND $consultaSectores AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigo;");

      }else if (intval($fisicamenteEstructura)===15 && intval($idRol)===2) {

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, (SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha,IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF(a.idUsuarioModificacion='0' AND a.idUsuarioModificacionRecomienda='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA')) AS estado,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,a.estadoModificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE $consultaOrden AND $consultaSectores OR (a.idFisicamente='$fisicamenteEstructura' AND a.idUsuario='$idUsuarioBd' AND a.estadoCalificacion!='Comite') AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigo;");

      }else if(intval($idRol)===7){


        return $this->constructor->select__general__incentivo("SELECT a.estadoCalificacion,a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion IS NULL,'RECIBIDO',(SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',IF(a1.tipo='Devuelto superior inmediato en etapa de calificación','DEVUELTO',IF(a.estadoCalificacion='ENVIADO INFRA','ENVIADO A INFRAESTRUCTURA',IF(a.estadoCalificacion='Recomendado','RECOMENDADO',''))))))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1)) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo,a.estadoCalificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE a.estadoCalificacion='CALIFICADO' AND (a.estadoModificacion='ENVIADO' OR a.estadoModificacion!='TERMINADO') AND $consultaSectores AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigo;");

      }else if (intval($fisicamenteEstructura)===15 && intval($idRol)===3) {

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion IS NULL,'RECIBIDO',(SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',IF(a1.tipo='Devuelto superior inmediato en etapa de calificación','DEVUELTO',''))))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1)) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo,a.estadoCalificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE $consultaOrden  AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) AND estadoCalificacion='CALIFICADO' GROUP BY a.codigo;");


      }else if(intval($idRol)===2 && (intval($fisicamenteEstructura)===43 || intval($fisicamenteEstructura)===40)){

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion IS NULL,'RECIBIDO',(SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',IF(a1.tipo='Devuelto superior inmediato en etapa de calificación','DEVUELTO',''))))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1)) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo,a.estadoCalificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE $consultaSectores AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) AND estadoCalificacion='CALIFICADO' AND estadoModificacion='ENVIADO' AND a.estadoModificacion IS NOT NULL AND idUsuarioModificacion IS NULL GROUP BY a.codigo;");

      }else{

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion IS NULL,'RECIBIDO',(SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',IF(a1.tipo='Devuelto superior inmediato en etapa de calificación','DEVUELTO',''))))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1)) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo,a.estadoCalificacion,(SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS idSolicitud,(SELECT a1.casoModificar FROM proyecto_modificacion_solicitud AS a1 WHERE a1.estadoModificacion='ENVIADO' AND a1.codigo=a.codigoUsuario) AS casoModificar  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE $consultaOrden AND $consultaSectores OR (a.idFisicamente='$fisicamenteEstructura' AND a.idUsuario='$idUsuarioBd') AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) AND estadoCalificacion='CALIFICADO' GROUP BY a.codigo;");

      }


    } 

    
    public function informacionGeneralModificacion($post) {

      $idTramite=$post["idTramite"];

      return  $this->constructor->select__general__incentivo("SELECT idSolicitud,codigo,justificacion,detalle,fecha,hora,estado,horaCalifica,fechaCalifica,observacion,IF(estadoModificacion IS NULL,'PENDIENTE ENVIAR',estadoModificacion) AS estadoModificacion,fechaTramiteModificacion,horaTramiteModificacion,IF(casoModificar='a','a) Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(casoModificar='b','b) Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','c) Modificaciones de la vigencia del proyecto de anual a plurianual')) AS casoModificar FROM proyecto_modificacion_solicitud WHERE idSolicitud='$idTramite';");

    } 
    

    public function actualizar__proyecto__enviar__observar__modificacion($post){

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $estado=$post["estado"];
        $idTramite=$post["idTramite"];

        $bandera=false;

        if ($bandera===false) {
        
            if($estado==="observado"){
                $estadoCreador="rectificado";
                $estadoCreador__2="Rectificado calificacion";
            }else{
                $estadoCreador="modificado";
                $estadoCreador__2="Modificación calificacion";
            }

            
            $consultaEnviado = $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
            foreach ($consultaEnviado as $valor) {
                $idBdEnviado=$valor["id"];
            }


            if ($estadoCreador==="modificado") {
              
              $consultaSolicitud = $this->constructor->select__general__incentivo("SELECT idSolicitud FROM proyecto_modificacion_solicitud WHERE codigo='$codigo' AND estado='APROBADO' AND estadoModificacion IS NULL;");
              foreach ($consultaSolicitud as $valorSolicitud) {
                $idSolicitudBd=$valorSolicitud["idSolicitud"];
              }


              $this->constructor->inserta__general__incentivo("proyecto_enviado_versiones", ['idEnviado', 'fecha', 'hora', 'tipo', 'idSolicitud'], array(
                  ':idEnviado' =>$idBdEnviado,
                  ':fecha' => $this->fecha,
                  ':hora' => $this->hora,
                  ':tipo' => $estadoCreador,
                  ':idSolicitud' => $idSolicitudBd,
              ));


            }else{

              $this->constructor->inserta__general__incentivo("proyecto_enviado_versiones", ['idEnviado', 'fecha', 'hora', 'tipo'], array(
                  ':idEnviado' =>$idBdEnviado,
                  ':fecha' => $this->fecha,
                  ':hora' => $this->hora,
                  ':tipo' => $estadoCreador,
              ));

            }

            $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual, idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado' ORDER BY id DESC LIMIT 1;");
            foreach ($consultaEnviado as $valor) {

                $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora','idSolicitud'], array(
                    ':idFisicamenteActual' =>$valor["idFisicamenteNuevo"],
                    ':idUsuarioActual' => $valor["idUsuarioNuevo"],
                    ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
                    ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
                    ':idEnviado' => $valor["idEnviado"],
                    ':tipo' =>$estadoCreador__2,
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                    ':idSolicitud' => $idTramite,
                ));

            }

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estadoModificacion='ENVIADO', fechaTramiteModificacion='".$this->fecha."', horaTramiteModificacion='".$this->hora."' WHERE idSolicitud='$idTramite';");

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='ENVIADO',idUsuarioModificacion=NULL WHERE codigoUsuario='$codigo';");

            $bandera=true;

        }


        return 1;

    }

    /*=======================================
    =            Justificaciones            =
    =======================================*/
    
    public function justificaciones__actividades($post) {

      $idTramite=$post["idTramite"];
      $codigo=$post["codigo"];

      $consulta=$this->constructor->select__general__incentivo("SELECT justificacion FROM incentivorespaldo.proyecto_cronograma_actividades_modificacion_justificacion WHERE codigo='$codigo' AND idSolicitud='$idTramite';");
      foreach ($consulta as $valor) {
        $justificacion=$valor["justificacion"];
      }

      if(empty($justificacion)){
        $justificacion="no";
      }


      return $justificacion;

    } 
    

    public function justificaciones__presupuesto($post) {

      $idTramite=$post["idTramite"];
      $codigo=$post["codigo"];

      $consulta=$this->constructor->select__general__incentivo("SELECT justificacion FROM incentivorespaldo.proyecto_presupuesto_modificacion_justificacion WHERE codigo='$codigo' AND idSolicitud='$idTramite';");
      foreach ($consulta as $valor) {
        $justificacion=$valor["justificacion"];
      }

      if(empty($justificacion)){
        $justificacion="no";
      }

      return $justificacion;

    } 
    
    public function justificaciones__descripcion($post) {

      $idTramite=$post["idTramite"];
      $codigo=$post["codigo"];

      $consulta=$this->constructor->select__general__incentivo("SELECT justificacionProyecto AS justificacion FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo' AND estado='A' AND idSolicitud='$idTramite';");
      foreach ($consulta as $valor) {
        $justificacion=$valor["justificacion"];
      }

      if(empty($justificacion)){
        $justificacion="no";
      }

      return $justificacion;

    } 
    
    /*=====  End of Justificaciones  ======*/
    


    /*==============================================
    =             Consultas para el pdf            =
    ==============================================*/
    
    public function descripcionProyecto($idCredencial) {

      return $this->constructor->select__general__incentivo("SELECT nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,justificacionProyecto,justifiacionModificacion FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$idCredencial' AND estado='A' AND tipoIngreso='modificacion' ORDER BY idDescripcion DESC LIMIT 1;");

    } 

    public function montoTotal($codigo) {
       return $this->constructor->select__general__incentivo("SELECT SUM(total) AS totalPresupuesto FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND idNivel1 IS NOT NULL AND estado='A' AND tipoIngreso='modificacion' GROUP BY codigo;");
    } 

    
    /*=====  End of  Consultas para el pdf  ======*/
    

    public function justificacion__cronogramaDeActividades($post) {

        $idTramite=$post["idTramite"];
        $codigo=$post["codigo"];

        return $this->constructor->select__general__incentivo("SELECT justificacion FROM incentivorespaldo.proyecto_cronograma_actividades_modificacion_justificacion WHERE codigo LIKE '$codigo' AND idSolicitud='$idTramite';");

    } 


    public function guardarCronogramaDeActividades__respaldo__modificacion($post) {

      $justificacionModificacion=$post["justificacionModificacion"];
      $codigo=$post["codigo"];
      $idTramite=$post["idTramite"];

      $this->constructor->actualiza__general__incentivo("DELETE FROM incentivorespaldo.proyecto_cronograma_actividades_modificacion_justificacion WHERE codigo='$codigo' AND idSolicitud='$idTramite';");
       
      $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades_modificacion_justificacion", ['justificacion', 'fecha', 'hora', 'codigo','idSolicitud'], array(
          ':justificacion' => $justificacionModificacion,
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':codigo' => $codigo,
          ':idSolicitud' => $idTramite,
      ));

      return 1;

    } 


    public function validarCiudad($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND ciudad IS NULL AND idNivel1 IS NOT NULL AND tipo=1 AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite';");
    } 

    public function validarPais($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND pais IS NULL AND idNivel1 IS NOT NULL AND tipo=1 AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite';");
    } 

    public function validarParroquia($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND parroquia IS NULL AND idNivel1 IS NOT NULL AND tipo=0 AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite';");
    } 

    public function validarCanton($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND canton IS NULL AND idNivel1 IS NOT NULL AND tipo=0 AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite';");
    } 

    public function validarProvincia($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND provincia IS NULL AND idNivel1 IS NOT NULL AND tipo=0 AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite';");
    } 


    public function validarComponentes($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT a.id,b.nombre AS componente,c.nombre AS nivel,a.anio FROM incentivorespaldo.proyecto_cronograma_actividades AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes INNER JOIN componentes_nivel AS c ON a.idNivel1=c.idNivel1 WHERE a.codigo='$codigo' AND a.enero=0 AND a.febrero=0 AND a.marzo=0 AND a.abril=0 AND a.mayo=0 AND a.junio=0 AND a.julio=0 AND a.agosto=0 AND a.septiembre=0 AND a.octubre=0 AND a.septiembre=0 AND a.octubre=0 AND a.noviembre=0 AND a.diciembre=0 AND a.idNivel1 IS NOT NULL AND a.idComponentes!=5 AND a.estado='A' AND a.tipoIngreso='modificacion' AND a.idSolicitud='$idTramite';");
    } 


    public function validarActividades($codigo,$idTramite) {
        return $this->constructor->select__general__incentivo("SELECT id,anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND actividades IS NULL AND idNivel1 IS NOT NULL AND idComponentes!='5' AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite';");
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
        $idTramite=$post["idTramite"];

        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','creado','tipoIngreso','idSolicitud','estado'], array(
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
            ':tipoIngreso' => 'modificacion',
            ':idSolicitud' => $idTramite,
            ':estado' => 'A',
        ));


        return 1;

    } 

    public function eliminarCronogramas__agregadosAdicionales($post) {

        $id=$post["id"];

        $this->constructor->actualiza__general__incentivo("DELETE FROM incentivorespaldo.proyecto_cronograma_actividades WHERE id='$id';");

        return 1;

    } 

    public function guardarCronogramas__adicionales($post) {

        $codigo=$post["codigo"];
        $tipo=$post["tipo"];
        $campo=$post["campo"];
        $valor=$post["valor"];
        $id=$post["id"];
        $anio=$post["anio"];

        if ($valor!=="" && !empty($valor) && $valor!=="null" && $valor!==null) {
           $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET $campo='$valor' WHERE id='$id';");
        }else{
            $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET $campo=NULL WHERE id='$id';");
        }
        

        return 1;

    } 

    public function actualizarTipo__cronogramaDeActividades($post) {

        $codigo=$post["codigo"];
        $anioObtenido=$post["anioObtenido"];
        $tiposComponentes=$post["tiposComponentes"];
        $valor=$post["valor"];
        $id=$post["id"];


        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET tipo='$valor' WHERE id='$id';");

        return 1;

    } 

    public function actualizarRubrosComponentesMeses__cronogramaDeActividades($post) {

        $codigo=$post["codigo"];
        $anioObtenido=$post["anioObtenido"];
        $tiposComponentes=$post["tiposComponentes"];
        $valorEnviar=$post["valorEnviar"];
        $mes=$post["mes"];
        $id=$post["id"];


        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET $mes='$valorEnviar' WHERE id='$id';");

        return 1;

    } 

    public function actualizarRubrosComponentesTextos__cronogramaDeActividades($post) {

        $valor=$post["valor"];
        $rotulo=$post["rotulo"];
        $id=$post["id"];
        $anioObtenido=$post["anioObtenido"];
        $codigo=$post["codigo"];
        $tiposComponentes=$post["tiposComponentes"];
        $idTramite=$post["idTramite"];

        if($valor!=="null" && !empty($valor)){
            $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET $rotulo='$valor' WHERE id='$id';");
        }else if($valor==="null"){
             $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET $rotulo=NULL WHERE id='$id';");
        }

        return 1;

    } 


    public function cronogramaDeActividades($codigo,$anio,$tiposComponentes,$idTramite) {

      if($idTramite===null || $idTramite==="null" || empty($idTramite)){

        return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.actividades,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.tipo,a.provincia,a.canton,a.parroquia,a.pais,a.ciudad,a.anio,a.creado,a.orden,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1,z.enero AS eneroP,z.febrero AS febreroP,z.marzo AS marzoP,z.abril AS abrilP,z.mayo AS mayoP,z.junio AS junioP,z.julio AS julioP,z.agosto AS agostoP,z.septiembre AS septiembreP,z.octubre AS octubreP,z.noviembre AS noviembreP,z.diciembre AS diciembreP FROM proyecto_cronograma_actividades AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 LEFT JOIN proyecto_presupuesto AS z ON z.idNivel1=a.idNivel1 AND a.idComponentes=z.idComponentes AND z.sector='$tiposComponentes' AND a.codigo=z.codigo AND z.anio='$anio'  WHERE a.codigo='$codigo' AND a.anio='$anio' AND a.sector='$tiposComponentes' ORDER BY a.orden,a.idNivel1,a.id;");

      }else{

        return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.actividades,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.tipo,a.provincia,a.canton,a.parroquia,a.pais,a.ciudad,a.anio,a.creado,a.orden,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1,z.enero AS eneroP,z.febrero AS febreroP,z.marzo AS marzoP,z.abril AS abrilP,z.mayo AS mayoP,z.junio AS junioP,z.julio AS julioP,z.agosto AS agostoP,z.septiembre AS septiembreP,z.octubre AS octubreP,z.noviembre AS noviembreP,z.diciembre AS diciembreP FROM incentivorespaldo.proyecto_cronograma_actividades AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 LEFT JOIN proyecto_presupuesto AS z ON z.idNivel1=a.idNivel1 AND a.idComponentes=z.idComponentes AND z.sector='$tiposComponentes' AND a.codigo=z.codigo AND z.anio='$anio'  WHERE a.codigo='$codigo' AND a.anio='$anio' AND a.sector='$tiposComponentes' AND a.estado='A' AND a.tipoIngreso='modificacion' ORDER BY a.orden,a.idNivel1,a.id;");

      }



    } 


    public function insertarDescripcionProyecto__modificacion__observacion__modificacion($post) {

      $idCredencial=$post["idCredencial"];
      $nombreProyecto=$post["nombreProyecto"];
      $fechaInicio=$post["fechaInicio"];
      $fechaFin=$post["fechaFin"];
      $mensajePluri=$post["mensajePluri"];
      $objetivoGeneral=$post["objetivoGeneral"];
      $diferenciaAnios=$post["diferenciaAnios"];
      $estadoCalificacion=$post["estadoCalificacion"];
      $justificacionProyecto=$post["justificacionProyecto"];
      $justificacionModificacion=$post["justificacionModificacion"];
      $codigo=$post["codigo"];
      $idTramite=$post["idTramite"];

      $arrayIdComponentes=json_decode($post["arrayIdComponentes"], true);

      $codigoFinal=$codigo;

      $consultaFechaFin=$this->constructor->select__general__incentivo("SELECT fechaFin FROM proyecto_descripcion WHERE codigo='$codigo';");
      foreach ($consultaFechaFin as $valor) {
        $fechaFinBd__comparativa=$valor["fechaFin"];
      }

      $fechaFin__comparativaA = strtotime($fechaFin);
      $fechaFinBd__comparativaA = strtotime($fechaFinBd__comparativa);

      if($fechaFin__comparativaA < $fechaFinBd__comparativaA){

        return 2;

      }else{

        $consulta=$this->constructor->select__general__incentivo("SELECT tipoIngreso FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A';");

        foreach ($consulta as $valor) {
          $tipoIngresoBdComparacion=$valor["tipoIngreso"];
        }


        if (empty($tipoIngresoBdComparacion)) {
          $consultaRespaldo__descripcion=$this->constructor->select__general__incentivo("SELECT idDescripcion,nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora,justificacionProyecto FROM proyecto_descripcion WHERE codigo='$codigo';");
        }else{
          $consultaRespaldo__descripcion=$this->constructor->select__general__incentivo("SELECT idDescripcion,nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora,justificacionProyecto FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A';");
        }

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
          $tipoIngresoBd=$valor["tipoIngreso"];
          $fechaBdI=$valor["fecha"];
          $horaBdI=$valor["hora"];
          $justificacionProyectoBd=$valor["justificacionProyecto"];

        }

        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_descripcion SET estado='I' WHERE codigo='$codigo';");

        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_descripcion",['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral','idCredencial','idProyecto','fecha','hora','codigo','tipoIngreso','estado','justificacionProyecto','justifiacionModificacion','idSolicitud'],array(':nombre' => $nombreBd,':fechaInicio' => $fechaInicioBd,':fechaFin' => $fechaFin,':tipo' => $mensajePluri,':diferenciaAnios' => $diferenciaAnios,':objetivoGeneral' => $objetivoGeneralBd,':idCredencial' => $idCredencialBd,':idProyecto' => $idProyectoBd,':fecha' => $fechaBdI,':hora' => $horaBdI,':codigo' => $codigoBd,':tipoIngreso' => 'modificacion',':estado' => 'A',':justificacionProyecto' => $justificacionProyectoBd,':justifiacionModificacion' => $justificacionModificacion,':idSolicitud' => $idTramite));

        








            /*==================================================
            =            Obtener fecha plurianuales            =
            ==================================================*/
        

            $consulta=$this->codigo__modificacion($codigo);

            foreach ($consulta as $valorDevuelto) {
              $casoModificar=$valorDevuelto["casoModificar"];
            }

            if($casoModificar==="a" || $casoModificar==="c"){
              $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio FROM proyecto_descripcion WHERE codigo='$codigo';");
            }else{
              $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo';");
            }
            

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


            
            /*=====  End of Obtener fecha plurianuales  ======*/      

            /*===========================================
            =            Generar componentes            =
            ===========================================*/

             $this->constructor->actualiza__general__incentivo("DELETE FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND estado='A' AND idSolicitud='$idTramite' AND tipoIngreso='modificacion';");

            /*====================================================================
            =             Construir componentes asociados al respaldo            =
            ====================================================================*/
            
             $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,tipoIngreso FROM proyecto_presupuesto WHERE codigo='$codigo';");

            foreach ($consultaRespaldo as $valor) {

                $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto",['idComponentes','idNivel1','detalle','justificacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado','idSolicitud','inicial'],array(':idComponentes' => $valor["idComponentes"],':idNivel1' => $valor["idNivel1"],':detalle' =>  $valor["detalle"],':justificacion' =>  $valor["justificacion"],':enero' =>  $valor["enero"],':febrero' =>  $valor["febrero"],':marzo' =>  $valor["marzo"],':abril' =>  $valor["abril"],':mayo' =>  $valor["mayo"],':junio' =>  $valor["junio"],':julio' =>  $valor["julio"],':agosto' =>  $valor["agosto"],':septiembre' =>  $valor["septiembre"],':octubre' =>  $valor["octubre"],':noviembre' =>  $valor["noviembre"],':diciembre' =>  $valor["diciembre"],':total' =>  $valor["total"],':fecha' =>  $this->fecha,':hora' =>  $this->hora,':activo' =>  $valor["activo"],':anio' =>  $valor["anio"],':codigo' =>  $valor["codigo"],':idCredencial' =>  $valor["idCredencial"],':nivel' =>  $valor["nivel"],':sector' =>  $valor["sector"],':tipoIngreso' => 'modificacion',':estado' => 'A',':idSolicitud' => $idTramite,':inicial' => 'A'));

            }
            
            /*=====  End of  Construir componentes asociados al respaldo  ======*/
            

             $arrayAnios__no=array();
             $consulta__aniosNo = $this->constructor->select__general__incentivo("SELECT anio FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion' AND idSolicitud='$idTramite' GROUP BY anio;");

             foreach ($consulta__aniosNo as $valor) {
                array_push($arrayAnios__no, $valor["anio"]);
             }


            $arrayAnios__noConvertido = array_map('strval', $arrayAnios__no);


            for($i=0; $i<count($arrayAnios);$i++){

                if (!in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {
        
                  $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");


                  foreach ($componetesV as $valor) {

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'componente',
                      ':tipoIngreso' => 'modificacion',
                      ':idSolicitud' => $idTramite,
                      ':estado' => 'A',
                    ));

                    $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion,$idTramite);


                  }


                  if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                      ':idComponentes' => 6,
                      ':idNivel1' =>  $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'componente',
                      ':tipoIngreso' => 'modificacion',
                      ':idSolicitud' => $idTramite,
                      ':estado' => 'A',
                    ));

                    $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion,$idTramite);

                  }

                } 


            }

            /*=====  End of Generar componentes  ======*/

            /*===============================================
            =            Componentes priorizados            =
            ===============================================*/
            
            $sectorProyectoPriorizado=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE idSector='3' AND codigo='$codigo';");
            foreach ($sectorProyectoPriorizado as $valor) {
                $idBdPriorizados=$valor["id"];
            }

            if (!empty($idBdPriorizados)) {

                for($i=0; $i<count($arrayAnios);$i++){

                    if (!in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {
            
                      $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");


                      foreach ($componetesV as $valor) {

                        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                          ':idComponentes' => $valor["idComponentes"],
                          ':idNivel1' => $valorNivel__1["idNivel1"],
                          ':fecha' => $this->fecha,
                          ':hora' => $this->hora,
                          ':anio' => $arrayAnios[$i],
                          ':codigo' => $codigoFinal,
                          ':idCredencial' => $idCredencial,
                          ':nivel' => 0,
                          ':sector' => 'priorizado',
                          ':tipoIngreso' => 'modificacion',
                          ':idSolicitud' => $idTramite,
                          ':estado' => 'A',
                        ));

                        $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estadoCalificacion,$idTramite);


                      }


                      if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                          ':idComponentes' => 6,
                          ':idNivel1' =>  $valorNivel__1["idNivel1"],
                          ':fecha' => $this->fecha,
                          ':hora' => $this->hora,
                          ':anio' => $arrayAnios[$i],
                          ':codigo' => $codigoFinal,
                          ':idCredencial' => $idCredencial,
                          ':nivel' => 0,
                          ':sector' => 'priorizado',
                          ':tipoIngreso' => 'modificacion',
                          ':idSolicitud' => $idTramite,
                          ':estado' => 'A',
                        ));

                        $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estadoCalificacion,$idTramite);

                      }

                    } 


                }


                for($i=0; $i<count($arrayAnios);$i++){

                    if (!in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {
            
                      $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");


                      foreach ($componetesV as $valor) {

                        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                          ':idComponentes' => $valor["idComponentes"],
                          ':idNivel1' => $valorNivel__1["idNivel1"],
                          ':fecha' => $this->fecha,
                          ':hora' => $this->hora,
                          ':anio' => $arrayAnios[$i],
                          ':codigo' => $codigoFinal,
                          ':idCredencial' => $idCredencial,
                          ':nivel' => 0,
                          ':sector' => 'femenino',
                          ':tipoIngreso' => 'modificacion',
                          ':idSolicitud' => $idTramite,
                          ':estado' => 'A',
                        ));

                        $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estadoCalificacion,$idTramite);


                      }


                      if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                          ':idComponentes' => 6,
                          ':idNivel1' =>  $valorNivel__1["idNivel1"],
                          ':fecha' => $this->fecha,
                          ':hora' => $this->hora,
                          ':anio' => $arrayAnios[$i],
                          ':codigo' => $codigoFinal,
                          ':idCredencial' => $idCredencial,
                          ':nivel' => 0,
                          ':sector' => 'femenino',
                          ':tipoIngreso' => 'modificacion',
                          ':idSolicitud' => $idTramite,
                          ':estado' => 'A',
                        ));

                        $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estadoCalificacion,$idTramite);

                      }

                    } 


                }

            }

            /*=====  End of Componentes priorizados  ======*/

            
            return 1;



      }
      
    }   


    public function insertarNivel__1($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente',$estadoCalificacion,$idTramite) {

        $nivel__1 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND estado='A';");

        foreach ($nivel__1 as $valorNivel__1) {

            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__1["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 1,
                ':sector' => $sector,
                ':tipoIngreso' => 'modificacion',
                ':idSolicitud' => $idTramite,
                ':estado' => 'A',
            ));

            $this->insertarNivel__2($idComponentes, 2,$valorNivel__1["idNivel1"],$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion,$idTramite);
        }

      return 1;

    }

    public function insertarNivel__2($idComponentes, $nivel,$idNivel1,$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion,$idTramite) {

        $nivel__2 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND idNivelRelacion='$idNivel1' AND estado='A';");

        foreach ($nivel__2 as $valorNivel__2) {

            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__2["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 2,
                ':sector' => $sector,
                ':tipoIngreso' => 'modificacion',
                ':idSolicitud' => $idTramite,
                ':estado' => 'A',
            ));

            $this->insertarNivel__3($idComponentes, 3,$valorNivel__2["idNivel1"],$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion,$idTramite);


        }

        return 1;
    }


    public function insertarNivel__3($idComponentes, $nivel,$idNivel1,$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion,$idTramite) {

        $nivel__2 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND idNivelRelacion='$idNivel1' AND estado='A';");

        foreach ($nivel__2 as $valorNivel__2) {

            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso','idSolicitud','estado'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__2["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 3,
                ':sector' => $sector,
                ':tipoIngreso' => 'modificacion',
                ':idSolicitud' => $idTramite,
                ':estado' => 'A',
            ));

        }

        return 1;
    }

    public function porcentaje__masculino() {
        $porcentaje=$this->constructor->select__general__incentivo("SELECT pMasculino FROM porcentajes WHERE estado='A';");
        foreach ($porcentaje as $valor) {
            $porcentajeBd=$valor["pMasculino"];
        }
        return $porcentajeBd;
    } 

    public function porcentaje__femenino() {
        $porcentaje=$this->constructor->select__general__incentivo("SELECT pFemenino FROM porcentajes WHERE estado='A';");
        foreach ($porcentaje as $valor) {
            $porcentajeBd=$valor["pFemenino"];
        }
        return $porcentajeBd;
    } 


    public function componentes__obligatorios__ingresar($codigo,$idSolicitud) {

        $array=array();

        $consulta=$this->constructor->select__general__incentivo("SELECT c.nombre,a.anio,SUM(a.total) AS total FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 INNER JOIN componentes AS c ON c.idComponentes=a.idComponentes WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='componente' AND a.idComponentes!='6' AND a.idSolicitud='$idSolicitud' GROUP BY a.idComponentes;");


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


    public function componentes__descripcion__null($codigo,$idSolicitud) {
        return $this->constructor->select__general__incentivo("SELECT b.nombre AS componente, c.nombre AS rubro,a.id FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes AS b ON a.idComponentes=b.idComponentes INNER JOIN componentes_nivel AS c ON c.idNivel1=a.idNivel1 WHERE a.codigo='$codigo' AND (a.enero>0 || a.febrero>0 || a.marzo>0 || a.abril>0 || a.mayo>0 || a.junio>0 || a.julio>0 || a.agosto>0 || a.septiembre>0 || a.octubre>0 || a.noviembre>0 || a.diciembre>0) AND (a.detalle IS NULL OR a.justificacion IS NULL) AND a.idSolicitud='$idSolicitud';");
    } 



    public function obtenerSumasGlobalesAnuales__femeninos__comparacion($codigo,$idSolicitud) {

        $porcentaje=$this->porcentaje__femenino();
        $porcentaje__2=$this->porcentaje__masculino();

        $entero = $porcentaje * 100;
        $entero__2 = $porcentaje__2 * 100;

        $restador=100 - $entero - $entero__2;

        $valorComoCadena = (string)$porcentaje; 
        $valorModificado = str_replace('0.', '1.', $valorComoCadena); 
        $valorFinal = (float)$valorModificado; 

        return $this->constructor->select__general__incentivo("SELECT a.anio, SUM(CASE WHEN a.sector = 'femenino' THEN a.total ELSE 0 END) AS total_priorizado, ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90) + SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END),2) AS total_componente_rounded, CASE  WHEN ROUND(SUM(CASE WHEN a.sector = 'femenino' THEN ROUND(a.total,2) ELSE 0 END),2) <> ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90),2) THEN CONCAT('No se ajusta al cinco por ciento para el año ', a.anio)  ELSE '1' END AS mensaje  FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1 = b.idNivel1 WHERE a.codigo = '$codigo' AND b.rubros = '1' AND (a.sector = 'femenino' OR a.sector = 'componente') AND  a.idSolicitud='$idSolicitud' GROUP BY a.anio;");

    } 


    public function obtenerSumasGlobalesAnuales__priorizados__comparacion($codigo,$idSolicitud) {

        $porcentaje=$this->porcentaje__masculino();
        $porcentaje__2=$this->porcentaje__femenino();

        $entero = $porcentaje * 100;
        $entero__2 = $porcentaje__2 * 100;

        $restador=100 - $entero - $entero__2;

        $valorComoCadena = (string)$porcentaje; 
        $valorModificado = str_replace('0.', '1.', $valorComoCadena); 
        $valorFinal = (float)$valorModificado; 


        return $this->constructor->select__general__incentivo("SELECT a.anio, SUM(CASE WHEN a.sector = 'priorizado' THEN a.total ELSE 0 END) AS total_priorizado, ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90) + SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END),2) AS total_componente_rounded, CASE  WHEN ROUND(SUM(CASE WHEN a.sector = 'priorizado' THEN ROUND(a.total,2) ELSE 0 END),2) <> ROUND(((SUM(CASE WHEN a.sector = 'componente' THEN ROUND(a.total,2)  ELSE 0 END) * $entero)/90),2) THEN CONCAT('No se ajusta al cinco por ciento para el año ', a.anio)  ELSE '1' END AS mensaje  FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1 = b.idNivel1 WHERE a.codigo = '$codigo' AND b.rubros = '1' AND (a.sector = 'priorizado' OR a.sector = 'componente') AND  a.idSolicitud='$idSolicitud' GROUP BY a.anio;");

    } 


    public function obtenerArchivosComponentes__infras__5($codigo,$idSolicitud) {

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


    public function obtenerArchivosComponentes__infras($codigo,$anio,$idSolicitud) {

        return $this->constructor->select__general__incentivo("SELECT IF(presupuestoRubro IS NULL, CONCAT_WS(' ','Presupuesto',' año', anio),1) AS presupuestoRubro, IF(cronogramaValoradoPdf IS NULL, CONCAT_WS(' ','Cronograma pdf',' año', anio),1) AS cronogramaValoradoPdf, IF(cronogramaValoradoExcel IS NULL, CONCAT_WS(' ','Cronograma excel',' año', anio),1) AS cronogramaValoradoExcel FROM proyecto_documentos_infraestructura WHERE codigo='$codigo';");

    } 

    public function justificacion__componentes($post) {

        $idTramite=$post["idTramite"];
        $codigo=$post["codigo"];

        return $this->constructor->select__general__incentivo("SELECT justificacion FROM incentivorespaldo.proyecto_presupuesto_modificacion_justificacion WHERE codigo LIKE '$codigo' AND idSolicitud='$idTramite';");

    } 


    public function suma11__orginal($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS sumaRespaldado FROM proyecto_presupuesto WHERE idNivel1!='0' AND codigo='$codigo'  GROUP BY codigo;");
      foreach ($consulta as $valor) {
        $sumaRespaldado=$valor["sumaRespaldado"];
      }

      return $sumaRespaldado;

    } 

    public function suma11__respaldo($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS sumaRespaldado FROM incentivorespaldo.proyecto_presupuesto WHERE idNivel1!='0' AND codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion'  GROUP BY codigo;");
      foreach ($consulta as $valor) {
        $sumaRespaldado=$valor["sumaRespaldado"];
      }

      return $sumaRespaldado;

    } 


    public function guardarEstadoComponentes__respaldo($codigo,$idCredencial,$estadoCalificacion,$justificacionModificacion,$idTramite) {


        $suma1Original=$this->suma11__orginal($codigo);
        $suma2Respaldo=$this->suma11__respaldo($codigo);
        $consultaIdSolicitud=$this->codigo__modificacion($codigo);

        foreach ($consultaIdSolicitud as $valor) {
          $casoModificar=$valor["casoModificar"];
        }


        if(($casoModificar==="a" OR $casoModificar==="c") && floatval($suma1Original)!=floatval($suma2Respaldo)){

          return 2000;

        }else{

          /*=================================================
          =            Cronograma de actividades            =
          =================================================*/
          
          $this->constructor->actualiza__general__incentivo("DELETE FROM incentivorespaldo.proyecto_cronograma_actividades  WHERE codigo='$codigo' AND estado='A' AND idSolicitud='$idTramite' AND tipoIngreso='modificacion';");

          /*===================================================================
          =            construir cronogramas asociados al respaldo            =
          ===================================================================*/
          
                   
           $consultaRespaldo__cronogramaActividades = $this->constructor->select__general__incentivo("SELECT id,idComponentes,idNivel1,actividades,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,tipo,provincia,canton,parroquia,pais,ciudad,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,orden,creado FROM proyecto_cronograma_actividades WHERE codigo='$codigo';");

          foreach ($consultaRespaldo__cronogramaActividades as $valor) {

              $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades",['idComponentes','idNivel1','actividades','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','tipo','provincia','canton','parroquia','pais','ciudad','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','orden','creado','tipoIngreso','estado','idSolicitud'],array(':idComponentes' => $valor["idComponentes"],':idNivel1' => $valor["idNivel1"],':actividades' =>  $valor["actividades"],':enero' =>  $valor["enero"],':febrero' =>  $valor["febrero"],':marzo' =>  $valor["marzo"],':abril' =>  $valor["abril"],':mayo' =>  $valor["mayo"],':junio' =>  $valor["junio"],':julio' =>  $valor["julio"],':agosto' =>  $valor["agosto"],':septiembre' =>  $valor["septiembre"],':octubre' =>  $valor["octubre"],':noviembre' =>  $valor["noviembre"],':diciembre' =>  $valor["diciembre"],':tipo' =>  $valor["tipo"],':provincia' =>  $valor["provincia"],':canton' =>  $valor["canton"],':parroquia' =>  $valor["parroquia"],':pais' =>  $valor["pais"],':ciudad' =>  $valor["ciudad"],':fecha' =>  $this->fecha,':hora' =>  $this->hora,':activo' =>  $valor["activo"],':anio' =>  $valor["anio"],':codigo' =>  $valor["codigo"],':idCredencial' =>  $valor["idCredencial"],':nivel' =>  $valor["nivel"],':sector' =>  $valor["sector"],':orden' =>  $valor["orden"],':creado' =>  $valor["creado"],':tipoIngreso' =>  'modificacion',':estado' =>  'A',':idSolicitud' =>  $idTramite));

          }
          
          /*=====  End of construir cronogramas asociados al respaldo  ======*/


          $consulta=$this->codigo__modificacion($codigo);

          foreach ($consulta as $valorDevuelto) {
            $casoModificar=$valorDevuelto["casoModificar"];
          }

          if($casoModificar==="a" || $casoModificar==="b"){
            $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio,diferenciaAnios FROM proyecto_descripcion WHERE codigo='$codigo';");
          }else{
            $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio,diferenciaAnios FROM incentivorespaldo.proyecto_descripcion WHERE codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion';");
          }
          

          foreach ($consultaRespaldo__descripcion__dos as $valor) {
            $fechaInicioBd__dos=$valor["fechaInicio"];
            $diferenciaAnios=$valor["diferenciaAnios"];
          }

          
          $sumadorCronograma=0;

          $codigoFinal=$codigo;

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

         $arrayAnios__no=array();
         $consulta__aniosNo = $this->constructor->select__general__incentivo("SELECT anio FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigo' AND estado='A' AND tipoIngreso='modificacion' GROUP BY anio;");

         foreach ($consulta__aniosNo as $valor) {
            array_push($arrayAnios__no, $valor["anio"]);
         }


          $arrayAnios__noConvertido = array_map('strval', $arrayAnios__no);


          for($i=0; $i<count($arrayAnios);$i++){

            if (in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {

              $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM incentivorespaldo.proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='componente' AND z.idComponentes!='6' AND anio='$arrayAnios[$i]' GROUP BY z.idComponentes;");

              foreach ($componetesV as $valor) {

                $componetesV__recursivo = $this->constructor->select__general__incentivo("SELECT id FROM incentivorespaldo.proyecto_cronograma_actividades WHERE idComponentes='".$valor["idComponentes"]."' AND codigo='$codigoFinal' AND estado='A' AND tipoIngreso='modificacion' AND anio='$arrayAnios[$i]';");

                foreach ($componetesV__recursivo as $valorRecursivo) {
                  $idRecursivo=$valorRecursivo["id"];
                }

                if(empty($idRecursivo)){

                  $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'componente',
                      ':orden' => $sumadorCronograma,
                      ':tipoIngreso' => 'modificacion',
                      ':idSolicitud' => $idTramite,
                      ':estado' => 'A',
                  ));

                }

                $sumadorCronograma++;

                $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$sumadorCronograma,$estadoCalificacion,$idTramite);

              }

            }

          }
              
          for($i=0; $i<count($arrayAnios);$i++){

            if (!in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {

              $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM incentivorespaldo.proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='componente' AND z.idComponentes!='6' AND anio='$arrayAnios[$i]' GROUP BY z.idComponentes;");


              foreach ($componetesV as $valor) {

                  $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'componente',
                      ':orden' => $sumadorCronograma,
                      ':tipoIngreso' => 'modificacion',
                      ':idSolicitud' => $idTramite,
                      ':estado' => 'A',
                  ));

                  $sumadorCronograma++;

                  $this->insertarNivel__1__sin__2($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$sumadorCronograma,$estadoCalificacion,$idTramite);
              }

            }

          }

          /*=====  End of Cronograma de actividades  ======*/
                  
          /*===============================================
          =            Componentes priorizados            =
          ===============================================*/
          
          $sectorProyectoPriorizado=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE idSector='3' AND codigo='$codigo';");
          foreach ($sectorProyectoPriorizado as $valor) {
              $idBdPriorizados=$valor["id"];
          }

          if (!empty($idBdPriorizados)) {

            for($i=0; $i<count($arrayAnios);$i++){

              if (in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM incentivorespaldo.proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='priorizado' AND z.idComponentes!='6' AND anio='$arrayAnios[$i]' GROUP BY z.idComponentes;");

                foreach ($componetesV as $valor) {

                  $componetesV__recursivo = $this->constructor->select__general__incentivo("SELECT id FROM incentivorespaldo.proyecto_cronograma_actividades WHERE idComponentes='".$valor["idComponentes"]."' AND codigo='$codigoFinal' AND estado='A' AND tipoIngreso='modificacion' AND anio='$arrayAnios[$i]';");

                  foreach ($componetesV__recursivo as $valorRecursivo) {
                    $idRecursivo=$valorRecursivo["id"];
                  }

                  if(empty($idRecursivo)){

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'priorizado',
                        ':orden' => $sumadorCronograma,
                        ':tipoIngreso' => 'modificacion',
                        ':idSolicitud' => $idTramite,
                        ':estado' => 'A',
                    ));

                  }

                  $sumadorCronograma++;

                  $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$sumadorCronograma,$estadoCalificacion,$idTramite);

                }

              }

            }
                
            for($i=0; $i<count($arrayAnios);$i++){

              if (!in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM incentivorespaldo.proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='priorizado' AND z.idComponentes!='6' AND anio='$arrayAnios[$i]' GROUP BY z.idComponentes;");


                foreach ($componetesV as $valor) {

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'priorizado',
                        ':orden' => $sumadorCronograma,
                        ':tipoIngreso' => 'modificacion',
                        ':idSolicitud' => $idTramite,
                        ':estado' => 'A',
                    ));

                    $sumadorCronograma++;

                    $this->insertarNivel__1__sin__2($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$sumadorCronograma,$estadoCalificacion,$idTramite);
                }

              }

            }

            for($i=0; $i<count($arrayAnios);$i++){

              if (in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM incentivorespaldo.proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='femenino' AND z.idComponentes!='6' AND anio='$arrayAnios[$i]' GROUP BY z.idComponentes;");

                foreach ($componetesV as $valor) {

                  $componetesV__recursivo = $this->constructor->select__general__incentivo("SELECT id FROM incentivorespaldo.proyecto_cronograma_actividades WHERE idComponentes='".$valor["idComponentes"]."' AND codigo='$codigoFinal' AND estado='A' AND tipoIngreso='modificacion' AND anio='$arrayAnios[$i]';");

                  foreach ($componetesV__recursivo as $valorRecursivo) {
                    $idRecursivo=$valorRecursivo["id"];
                  }

                  if(empty($idRecursivo)){

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'femenino',
                        ':orden' => $sumadorCronograma,
                        ':tipoIngreso' => 'modificacion',
                        ':idSolicitud' => $idTramite,
                        ':estado' => 'A',
                    ));

                  }

                  $sumadorCronograma++;

                  $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$sumadorCronograma,$estadoCalificacion,$idTramite);

                }

              }

            }
              
            for($i=0; $i<count($arrayAnios);$i++){

              if (!in_array(strval($arrayAnios[$i]), $arrayAnios__noConvertido)) {

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM incentivorespaldo.proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='femenino' AND z.idComponentes!='6' AND anio='$arrayAnios[$i]' GROUP BY z.idComponentes;");


                foreach ($componetesV as $valor) {

                    $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'femenino',
                        ':orden' => $sumadorCronograma,
                        ':tipoIngreso' => 'modificacion',
                        ':idSolicitud' => $idTramite,
                        ':estado' => 'A',
                    ));

                    $sumadorCronograma++;

                    $this->insertarNivel__1__sin__2($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$sumadorCronograma,$estadoCalificacion,$idTramite);
                }

              }

            }

          }
          
          /*=====  End of Componentes priorizados  ======*/
          


          $this->constructor->actualiza__general__incentivo("DELETE FROM incentivorespaldo.proyecto_presupuesto_modificacion_justificacion WHERE codigo='$codigo' AND idSolicitud='$idTramite';");
           
          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto_modificacion_justificacion", ['justificacion', 'fecha', 'hora', 'codigo','idSolicitud'], array(
              ':justificacion' => $justificacionModificacion,
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':codigo' => $codigo,
              ':idSolicitud' => $idTramite,
          ));

          return 1;

        }

    } 

    public function insertarNivel__1__sin($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente',$orden,$estadoCalificacion,$idTramite) {

        $array = array();
        $array__2 = array();

        $consultaGeneral = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM incentivorespaldo.proyecto_cronograma_actividades WHERE codigo='$codigoFinal' AND estado='A' AND tipoIngreso='modificacion';");

        foreach ($consultaGeneral as $valorA) {
            // Agregar solo si el valor no es vacío o nulo
            if (!empty($valorA["idNivel1"])) {
                array_push($array__2, $valorA["idNivel1"]);
            }
        }

        // Verificar si hay elementos antes de hacer implode
        if (!empty($array__2)) {
            $list = implode(',', $array__2);
        } else {
            $list = ''; // O puedes asignar un valor por defecto
        }

        // Asegurarse de que $list no tenga comas adicionales
        $list = trim($list, ',');

        $buscar__niveles=$this->constructor->select__general__incentivo("SELECT idNivel1 FROM incentivorespaldo.proyecto_presupuesto WHERE idComponentes='$idComponentes' AND sector='$sector' AND total>0 AND idNivel1 IS NOT NULL AND idComponentes iS NOT NULL AND anio='$anio' AND codigo='$codigoFinal' AND estado='A' AND tipoIngreso='modificacion' AND idNivel1 NOT IN ($list)  GROUP BY idNivel1,idComponentes;");

        foreach ($buscar__niveles as $valor) {
            array_push($array, $valor["idNivel1"]);
        }

        $comparacion = implode(',', $array);

        foreach ($array as $valor) {
        
            $nivel__1 = $this->constructor->select__general__incentivo("SELECT a.idNivel1,b.id FROM componentes_nivel AS a INNER JOIN incentivorespaldo.proyecto_presupuesto AS b ON a.idNivel1=b.idNivel1 WHERE a.idComponentes = '$idComponentes' AND a.idNivel1='$valor' AND a.estado='A' AND b.total>0 AND b.sector='$sector' AND a.idNivel1 IS NOT NULL AND a.idComponentes IS NOT NULL AND b.anio='$anio' AND b.codigo='$codigoFinal' GROUP BY a.idNivel1,a.idComponentes;");

            foreach ($nivel__1 as $valorNivel__1) {

                $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                    ':idComponentes' => $idComponentes,
                    ':idNivel1' => $valorNivel__1["idNivel1"],
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                    ':anio' => $anio,
                    ':codigo' => $codigoFinal,
                    ':idCredencial' => $idCredencial,
                    ':nivel' => 1,
                    ':sector' => $sector,
                    ':orden' => $orden,
                    ':tipoIngreso' => 'modificacion',
                    ':idSolicitud' => $idTramite,
                    ':estado' => 'A',
                ));

            }

        }

        return 1;

    }  

    public function insertarNivel__1__sin__2($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente',$orden,$estadoCalificacion,$idTramite) {

        $array=array();

        $buscar__niveles=$this->constructor->select__general__incentivo("SELECT idNivel1 FROM incentivorespaldo.proyecto_presupuesto WHERE idComponentes='$idComponentes' AND sector='$sector' AND total>0 AND idNivel1 IS NOT NULL AND idComponentes iS NOT NULL AND anio='$anio' AND codigo='$codigoFinal' AND estado='A' AND tipoIngreso='modificacion'GROUP BY idNivel1,idComponentes;");

        foreach ($buscar__niveles as $valor) {
            array_push($array, $valor["idNivel1"]);
        }

        $comparacion = implode(',', $array);

        foreach ($array as $valor) {
        
            $nivel__1 = $this->constructor->select__general__incentivo("SELECT a.idNivel1,b.id FROM componentes_nivel AS a INNER JOIN incentivorespaldo.proyecto_presupuesto AS b ON a.idNivel1=b.idNivel1 WHERE a.idComponentes = '$idComponentes' AND a.idNivel1='$valor' AND a.estado='A' AND b.total>0 AND b.sector='$sector' AND a.idNivel1 IS NOT NULL AND a.idComponentes IS NOT NULL AND b.anio='$anio' AND b.codigo='$codigoFinal' GROUP BY a.idNivel1,a.idComponentes;");

            foreach ($nivel__1 as $valorNivel__1) {

                $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso','idSolicitud','estado'], array(
                    ':idComponentes' => $idComponentes,
                    ':idNivel1' => $valorNivel__1["idNivel1"],
                    ':fecha' => $this->fecha,
                    ':hora' => $this->hora,
                    ':anio' => $anio,
                    ':codigo' => $codigoFinal,
                    ':idCredencial' => $idCredencial,
                    ':nivel' => 1,
                    ':sector' => $sector,
                    ':orden' => $orden,
                    ':tipoIngreso' => 'modificacion',
                    ':idSolicitud' => $idTramite,
                    ':estado' => 'A',
                ));

            }

        }

        return 1;

    }  


    public function obtenerSumasGlobalesAnuales($codigo,$idTramite) {

        if(!empty($idTramite) && $idTramite!==null && $idTramite!=="null"){
          return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN incentivo.componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='componente' AND a.idSolicitud='$idTramite' AND a.tipoIngreso='modificacion' AND a.estado='A' GROUP BY a.anio;");
        }else{
          return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='componente'  GROUP BY a.anio;");
        }
        
    } 

    public function obtenerSumasGlobalesAnuales__priorizados($codigo,$idTramite) {

        if(!empty($idTramite) && $idTramite!==null && $idTramite!=="null"){
          return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN incentivo.componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='priorizado' AND a.idSolicitud='$idTramite' AND a.tipoIngreso='modificacion' AND a.estado='A' GROUP BY a.anio;");
        }else{
          return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='priorizado' GROUP BY a.anio;");
        }
    } 


    public function obtenerSumasGlobalesAnuales__femeninos($codigo,$idTramite) {
      
        if(!empty($idTramite) && $idTramite!==null && $idTramite!=="null"){
          return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN incentivo.componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='femenino' AND a.idSolicitud='$idTramite' AND a.tipoIngreso='modificacion' AND a.estado='A' GROUP BY a.anio;");
        }else{
          return $this->constructor->select__general__incentivo("SELECT a.anio,SUM(a.total) AS total FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND b.rubros='1' AND a.sector='femenino' GROUP BY a.anio;");
        }
        
    } 

    public function obtener__infraestructuraObligatorios($codigo,$idTramite) {
       return $this->constructor->select__general__incentivo("SELECT SUM(total) AS suma FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND idComponentes='5'  AND idSolicitud='$idTramite' AND tipoIngreso='modificacion' AND estado='A' GROUP BY idComponentes;");
    } 


    public function actualizarRubrosComponentesTextos__modificacion($post) {

        $valor=$post["valor"];
        $rotulo=$post["rotulo"];
        $id=$post["id"];
        $anioObtenido=$post["anioObtenido"];
        $codigo=$post["codigo"];
        $tiposComponentes=$post["tiposComponentes"];

        if($valor!=="null" && !empty($valor)){
            $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET $rotulo='$valor' WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");
        }else if($valor==="null" || empty($valor)){
            $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET $rotulo=NULL WHERE id='$id' AND codigo='$codigo' AND anio='$anioObtenido' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");
        }

        return 1;

    }     

    public function presupuesto__real__proyecto($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT total FROM proyecto_presupuesto_footer WHERE codigo='$codigo';");
      foreach ($consulta as $valor) {
        $totalBd=$valor["total"];
      }

      return $totalBd;

    }

    public function actualizarRubrosComponentes__modificacion($post) {

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

        $idSolicitudBd__ar=$this->codigo__modificacion($codigo);
        foreach ($idSolicitudBd__ar as $valor__atipico) {
          $idSolicitud__modificacion=$valor__atipico["idSolicitud"];
        }

        $consultaModificacion=$this->informacionSolicitud__generalEsperada($codigo);
        foreach ($consultaModificacion as $valorEsperado) {
         $casoModificarRealBd=$valorEsperado["casoModificarReal"];
        }

        $banderaPasaPresupuesto=0;
        $presupuestoReasignado=0;

        if (!empty($casoModificarRealBd)) {
          $presupuestoReasignado=$this->presupuesto__real__proyecto($codigo);
        }


        $consulta=$this->constructor->select__general__incentivo("SELECT SUM(total) AS totalSuma,$mes FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND nivel!=0 AND idComponentes!=6 AND tipoIngreso='modificacion' AND estado='A'  GROUP BY codigo;");
        foreach ($consulta as $valorBd2) {
          $totalSumaBd=$valorBd2["totalSuma"];
        }

        $sumadorPasado=0;
        $sumadorPasado=floatval($totalSumaBd) + floatval($valor);

        $consultaId=$this->constructor->select__general__incentivo("SELECT idComponentes FROM incentivorespaldo.proyecto_presupuesto WHERE id='$id';");
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

        $consultaG=$this->constructor->select__general__incentivo("SELECT SUM(total) AS totalSumaComponentes FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND nivel!=0 AND idComponentes=6 AND tipoIngreso='modificacion' AND estado='A' GROUP BY codigo;");
        foreach ($consultaG as $valorBd2G) {
            $totalSumaBdGastosAdministrativos=$valorBd2G["totalSumaComponentes"];
        }

        $sumandoGbd=0;
        $sumandoGbd=floatval($totalSumaBdGastosAdministrativos) + floatval($valor);

        $consultaModificacion=$this->constructor->select__general__incentivo("SELECT SUM(a.total) AS totalSuma FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN incentivo.componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND a.nivel!=0  AND a.tipoIngreso='modificacion' AND a.estado='A' GROUP BY a.codigo;");
        foreach ($consultaModificacion as $valorBd2__m) {
          $totalSumaBd__md=$valorBd2__m["totalSuma"];
        }

        $consulta__mo=$this->constructor->select__general__incentivo("SELECT $mes AS mes FROM incentivorespaldo.proyecto_presupuesto WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");
        foreach ($consulta__mo as $valorConsultarM) {
          $mesBd__m=$valorConsultarM["mes"];
        }


        $restarModificatorios=0;
        $presupuestoReasignado__md=0;

        $restarModificatorios=floatval($totalSumaBd__md) - floatval($mesBd__m);

        $presupuestoReasignado__md=floatval($restarModificatorios) + floatval($valor);



        if(($casoModificarRealBd==='a' || $casoModificarRealBd==='c') && floatval($presupuestoReasignado__md)>$presupuestoReasignado){
          $banderaPasaPresupuesto=1;
        }

        if (floatval($sumadorPasado)>1000000) {

            $sumaMillon=0;
            $restaMillon=floatval($valorSuperior) - floatval($valor);

            $consulta2=$this->constructor->select__general__incentivo("SELECT $mes AS mes FROM incentivorespaldo.proyecto_presupuesto WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");
            foreach ($consulta2 as $valorBd) {
                $mesBd=$valorBd["mes"];
            }

            $total_usado=abs(floatval($total) - floatval($valor));

            return [$mesBd,$total_usado,$restaMillon,0,0];

        }else if (intval($idComponentesBd)===6 && floatval($sumandoGbd)>floatval($valorPorciento)) {

            
            $sumaMillon=0;
            $restaMillon=floatval($valorSuperior) - floatval($valor);

            $consulta2=$this->constructor->select__general__incentivo("SELECT $mes AS mes FROM incentivorespaldo.proyecto_presupuesto WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");
            foreach ($consulta2 as $valorBd) {
                $mesBd=$valorBd["mes"];
            }

            $total_usado=abs(floatval($total) - floatval($valor));

            $porcentajeNumerico = $porcentaje * 100;


            return [$mesBd,$total_usado,$restaMillon,1,$porcentajeNumerico];


        }else{


            $this->constructor->actualiza__general__incentivo("DELETE FROM incentivorespaldo.proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");

              $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET $mes='$valor',total='$total' WHERE id='$id' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");


            $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET total='$valorSuperior' WHERE id='$atributoSuperior' AND codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");



            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto_footer", ['codigo', 'enero', 'febrero', 'marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector','estado','tipoIngreso'], array(
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
                ':estado' => 'A',
                ':tipoIngreso' => 'modificacion'
            ));

            return 1;


        }

    } 

    public function obtenerPresupuestoNiveles($codigo,$anio,$tiposComponentes,$idTramite) {

       $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idSolicitud FROM proyecto_modificacion_solicitud WHERE codigo='$codigo' AND estado='APROBADO';");
       foreach ($consultaRespaldo as $valorD) {
        $idTramite=$valorD["idSolicitud"];
       }

        $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT id FROM incentivorespaldo.proyecto_presupuesto WHERE codigo='$codigo' AND tipoIngreso='modificacion' AND estado='A' AND idSolicitud='$idTramite';");
        foreach ($consultaRespaldo as $valor) {
           $idRespaldo=$valor["id"];
        }


        if (empty($idRespaldo)) {

           $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET estado='I' WHERE codigo='$codigo' AND tipoIngreso='modificacion';");

           $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,tipoIngreso FROM proyecto_presupuesto WHERE codigo='$codigo';");

           foreach ($consultaRespaldo as $valor) {
             $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto",['idComponentes','idNivel1','detalle','justificacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado','idSolicitud','inicial'],array(':idComponentes' => $valor["idComponentes"],':idNivel1' => $valor["idNivel1"],':detalle' =>  $valor["detalle"],':justificacion' =>  $valor["justificacion"],':enero' =>  $valor["enero"],':febrero' =>  $valor["febrero"],':marzo' =>  $valor["marzo"],':abril' =>  $valor["abril"],':mayo' =>  $valor["mayo"],':junio' =>  $valor["junio"],':julio' =>  $valor["julio"],':agosto' =>  $valor["agosto"],':septiembre' =>  $valor["septiembre"],':octubre' =>  $valor["octubre"],':noviembre' =>  $valor["noviembre"],':diciembre' =>  $valor["diciembre"],':total' =>  $valor["total"],':fecha' =>  $this->fecha,':hora' =>  $this->hora,':activo' =>  $valor["activo"],':anio' =>  $valor["anio"],':codigo' =>  $valor["codigo"],':idCredencial' =>  $valor["idCredencial"],':nivel' =>  $valor["nivel"],':sector' =>  $valor["sector"],':tipoIngreso' => 'modificacion',':estado' => 'A',':idSolicitud' => $idTramite,':inicial' => 'A'));
           }

        }


       return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.numeral FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.numeral) AS rotulo,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.detalle, a.justificacion,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.total,a.anio,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1 FROM incentivorespaldo.proyecto_presupuesto AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND a.estado='A' AND a.tipoIngreso='modificacion' AND idSolicitud='$idTramite' ORDER BY a.id;");

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos($codigo,$anio,$tiposComponentes,$idSolicitud) {


       $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idSolicitud FROM proyecto_modificacion_solicitud WHERE codigo='$codigo' AND estado='APROBADO';");
       foreach ($consultaRespaldo as $valorD) {
        $idSolicitud=$valorD["idSolicitud"];
       }

       return $this->constructor->select__general__incentivo("SELECT b.idComponentes FROM incentivorespaldo.proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idComponentes=b.idComponentes WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND a.idSolicitud='$idSolicitud' GROUP BY b.idComponentes ORDER BY a.id;");

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos__footer($codigo,$anio,$tiposComponentes,$idSolicitud) {


       $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idSolicitud FROM proyecto_modificacion_solicitud WHERE codigo='$codigo' AND estado='APROBADO';");
       foreach ($consultaRespaldo as $valorD) {
        $idSolicitud=$valorD["idSolicitud"];
       }


        $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idFooter FROM incentivorespaldo.proyecto_presupuesto_footer WHERE tipoIngreso='modificacion' AND estado='A' AND codigo='$codigo';");
        foreach ($consultaRespaldo as $valor) {
           $idFooterBd=$valor["idFooter"];
        }

        if (empty($idFooterBd)) {
           
            $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto_footer SET estado='I' WHERE codigo='$codigo' AND tipoIngreso='modificacion';");

            $consultaRespaldo = $this->constructor->select__general__incentivo("SELECT idFooter,codigo,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,anio,sector,tipoIngreso FROM proyecto_presupuesto_footer WHERE  codigo='$codigo';");

            foreach ($consultaRespaldo as $valor) {
                $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto_footer",['codigo','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector','tipoIngreso','estado','idSolicitud','inicial'],array(':codigo' => $valor["codigo"],':enero' =>  $valor["enero"],':febrero' =>  $valor["febrero"],':marzo' =>  $valor["marzo"],':abril' =>  $valor["abril"],':mayo' =>  $valor["mayo"],':junio' =>  $valor["junio"],':julio' =>  $valor["julio"],':agosto' =>  $valor["agosto"],':septiembre' =>  $valor["septiembre"],':octubre' =>  $valor["octubre"],':noviembre' =>  $valor["noviembre"],':diciembre' =>  $valor["diciembre"],':total' =>  $valor["total"],':fecha' =>  $this->fecha,':hora' =>  $this->hora,':anio' =>  $valor["anio"],':sector' =>  $valor["sector"],':tipoIngreso' =>  'modificacion',':estado' => 'A',':idSolicitud' => $idSolicitud,':inicial' => 'A'));
           }


        }

       return $this->constructor->select__general__incentivo("SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total FROM incentivorespaldo.proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' AND tipoIngreso='modificacion' AND estado='A';");

    } 



    public function calificar__solicitud__de__modificacion($post) {

        $codigo=$post["codigo"];
        $calificacion=$post["calificacion"];
        $observacion=$post["observacion"];
        $idSolicitud=$post["idSolicitud"];
        $fisicamenteEstructura=$post["fisicamenteEstructura"];
        $idCredencial=$post["idCredencial"];


        $funcionarioEnviado=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");

        foreach ($funcionarioEnviado as $valor) {
          $idUsuarioBd=$valor["idUsuario"];
        }



        $consultaEnviado=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");

        foreach ($consultaEnviado as $valorConsultado) {
          $idEnviado=$valorConsultado["id"];
        }


        foreach ($this->obtenerUsuario__porCodigo($codigo) as $valor) {
            $idCredencial=$valor["idCredencial"];
        }

        foreach ($this->obtenerCorreo__porIdCredencial($idCredencial) as $valor) {
            $email1Bd=$valor["email1"];
            $email2Bd=$valor["email2"];
        }

        $array=$this->obtenerNombre__proponente($idCredencial);

        if ($calificacion==="APROBADO") {
           $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Estimado/a usuario/a,</div><br><div style="font-size:10px;">'.$array[1].'</div><br><div style="font-size:10px;">Su solicitud de apertura del módulo modificaciones ha sido <span style="font-weight:bold;">'.$calificacion.'</span>, ingrese con sus credenciales al aplicativo.</div><br><div style="font-weight:bold; font-size:10px;">Atentamente, </div><br><br><div style="font-weight:bold; font-size:10px;">Ministerio del Deporte</div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Dirección: </span><span style="font-size:10px;">Av.Gaspar de Villarroel E10-122 y 6 de Diciembre</span></div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Código postal: </span><span style="font-size:10px;">170501 / Quito - Ecuador</span></div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Teléfono: </span><span style="font-size:10px;">+593-23969200</span></div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">www.deporte.gob.ec</span></div></body></html>';

            $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
              ':idFisicamenteActual' =>$fisicamenteEstructura,
              ':idUsuarioActual' => $idUsuarioBd,
              ':idFisicamenteNuevo' => $fisicamenteEstructura,
              ':idUsuarioNuevo' => $idUsuarioBd,
              ':idEnviado' =>  $idEnviado,
              ':textoDevuelto' => 'SOLICITUD DE MODIFICACIÓN APROBADA',
              ':tipo' =>  "SOLICITUD DE MODIFICACIÓN APROBADA",
              ':fecha' =>  $this->fecha,
              ':hora' =>  $this->hora,
            ));

        }else{

            $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Estimado/a usuario/a,</div><br><div style="font-size:10px;">'.$array[1].'</div><br><div style="font-size:10px;">Su solicitud de apertura del módulo modificaciones ha sido <span style="font-weight:bold;">'.$calificacion.'</span>, por las siguientes razones: <br></div><div style="font-size:10px;">'.$observacion.'</div><br><div style="font-weight:bold; font-size:10px;">Atentamente, </div><br><div style="font-weight:bold; font-size:15px;">Ministerio del Deporte</div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Dirección: </span><span style="font-size:10px;">Av.Gaspar de Villarroel E10-122 y 6 de Diciembre</span></div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Código postal: </span><span style="font-size:10px;">170501 / Quito - Ecuador</span></div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Teléfono: </span><span style="font-size:10px;">+593-23969200</span></div><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">www.deporte.gob.ec</span></div></body></html>';

              $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
                ':idFisicamenteActual' =>$fisicamenteEstructura,
                ':idUsuarioActual' => $idUsuarioBd,
                ':idFisicamenteNuevo' => $fisicamenteEstructura,
                ':idUsuarioNuevo' => $idUsuarioBd,
                ':idEnviado' =>  $idEnviado,
                ':textoDevuelto' => 'SOLICITUD DE MODIFICACIÓN NEGADA',
                ':tipo' =>  "SOLICITUD DE MODIFICACIÓN NEGADA",
                ':fecha' =>  $this->fecha,
                ':hora' =>  $this->hora,
              ));

        }

        

        $this->constructor->enviarCorreo([$email1Bd],$bodyMensaje);


        return $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estado='$calificacion',horaCalifica='".$this->hora."',fechaCalifica='".$this->fecha."',observacion='$observacion' WHERE codigo='$codigo' AND idSolicitud='$idSolicitud';");

    }


    public function informacionSolicitud__general__calificar__analista($post) {

        $fisicamenteEstructura=$post["fisicamenteEstructura"];

        $consultaSectores=$this->sectores__recibidos($fisicamenteEstructura);

        return $this->constructor->select__general__incentivo("SELECT a.idSolicitud,a.codigo,IF(a.casoModificar='a','a) Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(a.casoModificar='b','b) Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','c) Modificaciones de la vigencia del proyecto de anual a plurianual')) AS casoModificar,a.justificacion,a.detalle,a.fecha,a.hora,a.estado, b.codigo AS codigoProyecto,UPPER(f.nombre) AS proyecto,UPPER(IF(c.id IS NOT NULL,z.nombre,'CONSTRUCCIÓN DE OBRA NUEVA, REHABILITACIÓN, READECUACIÓN Y/OMANTENIMIENTO DE INFRAESTRUCTURA DEPORTIVA')) AS sector FROM proyecto_modificacion_solicitud AS a INNER JOIN proyecto_enviado AS b ON b.codigoUsuario=a.codigo INNER JOIN proyecto_descripcion AS f ON f.codigo=b.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigo LEFT JOIN sector AS z ON z.idSector=c.idSector WHERE $consultaSectores AND a.estado='PENDIENTE' GROUP BY b.codigoUsuario;");

    }

    public function informacionSolicitud__generalEsperada($codigo) {


        return $this->constructor->select__general__incentivo("SELECT a.codigo,IF(a.casoModificar='a','a) Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(a.casoModificar='b','b) Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','c) Modificaciones de la vigencia del proyecto de anual a plurianual')) AS casoModificar,a.justificacion,a.detalle,a.fecha,a.hora,a.estado, b.codigo AS codigoProyecto,a.horaCalifica,a.fechaCalifica,IF(a.estadoModificacion IS NULL,'PENDIENTE ENVIAR',a.estadoModificacion) AS estadoModificacion,a.casoModificar AS casoModificarReal,a.idIncremental FROM proyecto_modificacion_solicitud AS a INNER JOIN proyecto_enviado AS b ON b.codigoUsuario=a.codigo WHERE (a.estadoModificacion IS NULL OR a.estadoModificacion='ENVIADO') AND a.estado='APROBADO' AND a.codigo='$codigo';");

    }

    public function informacionSolicitud__general($codigo) {

        return $this->constructor->select__general__incentivo("SELECT a.codigo,IF(a.casoModificar='a','a) Modificación de valores y/o modificaciones programáticas, dentro del mismo componente',IF(a.casoModificar='b','b) Modificación de valores y/o actividades o gastos por un monto inferior o superior al inicialmente contemplado, dentro del mismo componente','c) Modificaciones de la vigencia del proyecto de anual a plurianual')) AS casoModificar,a.justificacion,a.detalle,a.fecha,a.hora,a.estado, b.codigo AS codigoProyecto,a.horaCalifica,a.fechaCalifica,IF(a.estado='NEGADO','NEGADO',IF(a.estadoModificacion IS NULL,'PENDIENTE ENVIAR',a.estadoModificacion)) AS estadoModificacion,a.observacion,b.codigoUsuario,a.idSolicitud,a.idIncremental,IFNULL(a.observacionFinal,'') AS observacionNegacion  FROM proyecto_modificacion_solicitud AS a INNER JOIN proyecto_enviado AS b ON b.codigoUsuario=a.codigo WHERE a.codigo='$codigo';");

    }

    public function informacionSolicitud__pendiente($codigo) {

        return $this->constructor->select__general__incentivo("SELECT codigo,casoModificar,justificacion,detalle,fecha,hora,estado,estadoModificacion FROM proyecto_modificacion_solicitud WHERE (estado='PENDIENTE' OR estado='ENVIADO' OR estado='APROBADO') AND  codigo='$codigo';");

    }


    public function informacionSolicitud__pendiente__cuantos($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(codigo) AS contador FROM proyecto_modificacion_solicitud WHERE estadoModificacion='APROBADO' AND  codigo='$codigo' GROUP BY codigo;");
      foreach ($consulta as $valor) {
        $contador=$valor["contador"];
      }

      return $contador;

    }


    public function cuantos__tramitesTieneModificaciones($codigo) {

      $contador=0;

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
      foreach ($consulta as $valor) {
        $id=$valor["id"];
      }

      $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS contadorVersiones FROM proyecto_enviado_versiones WHERE idEnviado='$id' GROUP BY idEnviado;");
      foreach ($consulta as $valor) {
        $contadorVersiones=$valor["contadorVersiones"];
      }

      $contador=intval($contador) + intval($contadorVersiones) + 2;

      return $contador;

    }


    public function registroSolicitudDeModificacion($post) {

        $codigo=$post["codigo"];
        $casoModificar=$post["casoModificar"];
        $justificacion=$post["justificacion"];
        $detalle=$post["detalle"];

        $cuantos=$this->cuantos__tramitesTieneModificaciones($codigo);


        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
        foreach ($consulta as $valor) {
          $idEnviado=$valor["id"];
        }


        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
          ':idFisicamenteActual' =>0,
          ':idUsuarioActual' => 0,
          ':idFisicamenteNuevo' => 0,
          ':idUsuarioNuevo' => 0,
          ':idEnviado' =>  $idEnviado,
          ':textoDevuelto' => 'SOLICITUD DE MODIFICACIÓN ENVIADA',
          ':tipo' =>  "SOLICITUD DE MODIFICACIÓN ENVIADA",
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));

        return $this->constructor->inserta__general__incentivo("proyecto_modificacion_solicitud",['codigo','casoModificar','justificacion','detalle','fecha','hora','estado','idIncremental'],array(':codigo' => $codigo,':casoModificar' =>  $casoModificar,':justificacion' =>  $justificacion,':detalle' =>  $detalle,':fecha' => $this->fecha,':hora' => $this->hora,':estado' =>  'PENDIENTE',':idIncremental' => $cuantos));

    }

    public function obtenerNombre__proponente($idCredencial) {

        $array=array();

        $consultaOrganismo=$this->constructor->select__general("SELECT idCredencial FROM organismo WHERE idCredencial='$idCredencial';");
    
        foreach ($consultaOrganismo as $valor) {
            $idCredencialOrganismo=$valor["idCredencial"];
        }

        if (!empty($consultaOrganismo)) {
            
            $consulta=$this->constructor->select__general("SELECT razonSocial FROM organismo WHERE idCredencial='$idCredencial';");
    
            foreach ($consulta as $valor) {
              array_push($array,'ORGANISMO');
              array_push($array,$valor['razonSocial']);
            }

        }else{

            $consulta=$this->constructor->select__general("SELECT nombre FROM usuario WHERE idCredencial='$idCredencial';");
    
            foreach ($consulta as $valor) {
              array_push($array,'USUARIO');
              array_push($array,$valor['nombre']);
            }

        }

        return $array;

    }

    public function obtenerUsuario__porCodigo($codigo) {
        return $this->constructor->select__general__incentivo("SELECT b.idCredencial,a.id FROM proyecto_enviado AS a INNER JOIN proyecto AS b ON a.codigoUsuario=b.codigo WHERE a.codigoUsuario='$codigo';");
    }

    public function obtenerCorreo__porIdCredencial($idCredencial) {
        return $this->constructor->select__general("SELECT email1,email2 FROM contacto WHERE idCredencial='$idCredencial';");
    }

    public function codigo__modificacion($codigo) {
        return $this->constructor->select__general__incentivo("SELECT idSolicitud,casoModificar FROM proyecto_modificacion_solicitud WHERE codigo='$codigo' AND estado='APROBADO';");
    } 


    public function obtenerInformacionGeneral__proyecto($codigo) {
      return $this->constructor->select__general__incentivo("SELECT nombre AS nombreProyecto,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,justificacionProyecto FROM proyecto_descripcion WHERE codigo='$codigo';");
    } 

}



