<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Application\Auth\Auth;
use App\Presentation\Pdf\Base;
use App\Presentation\Pdf\InformeC;
use App\Presentation\Pdf\Convocatoria;
use App\Presentation\Pdf\Notificacion;
use App\Presentation\Pdf\ActaReunion;
use App\Presentation\Word\ActaReunionWord;
use App\Presentation\Controllers\ControllersAdmin;

class ControllersBandeja {

    private $fecha;
    private $hora;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->constructor = ServicesAdmin::getInstance();
        $this->constructor__auth = Auth::getInstance();

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

        $this->constructor__basePdf = Base::getInstance();
        $this->informePdf = InformeC::getInstance();
        $this->convocatoriaPdf = Convocatoria::getInstance();
        $this->notificacionPdf = Notificacion::getInstance();
        $this->actaReunionPdf = ActaReunion::getInstance();

        $this->anio=date('Y');

        $this->actaReunionWord = ActaReunionWord::getInstance();

        $this->administradorController = new ControllersAdmin();

        // $this->ruta='http://192.168.12.10/repositorio/incentivo2.0/';d
        // $this->ruta='file:///C:/wamp64/www/repositorio/incentivo2.0/';
        // $this->ruta='../poa2/repositorio/incentivo2.0/';
        $this->ruta='../repositorio/incentivo2.0/';


    }
    public function obtener__negacion__del__analista($post) {

      $consulta=$this->constructor->select__general__incentivo("SELECT observacionAnalista FROM proyecto_enviado WHERE codigoUsuario='".$post["codigo"]."' AND observacionAnalista IS NOT NULL;");
      foreach ($consulta as $valor) {
        $observacionAnalista=$valor["observacionAnalista"];
      }

      if(!empty($observacionAnalista)){
        return $observacionAnalista;
      }else{
        return "no";
      }


    } 

    public function actualizarNegacionAnalistaProyectoCalificacion($post) {

      $consulta=$this->constructor->select__general__incentivo("SELECT id_usuario,fisicamenteEstructura FROM ezonshar_mdepsaddb.th_usuario WHERE usuario='".$post["usuario"]."';");
      foreach ($consulta as $valor) {
        $id_usuario=$valor["id_usuario"];
        $fisicamenteEstructura=$valor["fisicamenteEstructura"];
      }

      $consulta__2=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='".$post["codigo"]."';");
      foreach ($consulta__2 as $valorEnviado) {
        $idEnviado=$valorEnviado["id"];
      }      


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='NEGADO',fechaCalifica='".$this->fecha."',horaCalifica='".$this->hora."', observacionAnalista='".$post["razonNegacion"]."',idUsuarioRecomiendaCalificacion='0',idUsuario='0' WHERE codigoUsuario='".$post["codigoUsuario"]."';");
      $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
        ':idFisicamenteActual' =>$fisicamenteEstructura,
        ':idUsuarioActual' => $id_usuario,
        ':idFisicamenteNuevo' => $fisicamenteEstructura,
        ':idUsuarioNuevo' => $id_usuario,
        ':idEnviado' =>  $idEnviado,
        ':textoDevuelto' => $post["razonNegacion"],
        ':tipo' =>  "PROYECTO NEGADO POR ANALISTA",
        ':fecha' =>  $this->fecha,
        ':hora' =>  $this->hora,
      ));

      return 1;

    } 


    public function observable__obtener__documentos__versiones__observaciones($codigo) {

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      $consulta__2=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS contador FROM proyecto_enviado_versiones WHERE idEnviado='$idBd' AND tipo='rectificado' GROUP BY idEnviado;");
      foreach ($consulta__2 as $valor__2) {
        $contadorBd=$valor__2["contador"];
      }

      return $contadorBd;

    } 


    public function consultar__auxiliar__comites() {

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM comite_delegados_auxiliar LIMIT 1;");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 0; 
      }else{
        return 1;
      }

    } 

    public function actualizar__nombres__notificaciones($post) {

      $idComite=$post["idComite"];
      $codigo=$post["codigo"];
      $nombrePdf=$post["nombrePdf"];

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo' OR codigo='$codigo';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }


      return $this->constructor->actualiza__general__incentivo("UPDATE comite_proyectos SET documento='$nombrePdf' WHERE idComite='$idComite' AND idEnviado='$idBd';"); 
    } 


    public function obtenerInformacionGeneral__proyecto($codigo) {

        return $this->constructor->select__general__incentivo("SELECT UPPER(a.nombre) AS nombreProyecto,UPPER(b.nombre) AS nombreSolicitante,UPPER(d.nombre) AS sector, CONCAT( DATE_FORMAT(a.fechaInicio, '%d '), CASE MONTH(a.fechaInicio) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaInicio, ' %Y')) AS fechaInicio,IF(c.idSector='1' || c.idSector='2' || c.idSector='3',UPPER('Mantener la participación de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad física que promueve el Ministerio del Deporte  ')) AS alineacionTecnica, IF(e.idComponentes='5',CONCAT_WS(' ',UPPER('Incrementar la infraestructura deportiva con condiciones óptimas a nivel nacional'),UPPER('Construcción de obra nueva, rehabilitación, readecuación y/o mantenimiento de infraestructura deportiva ')),' ') AS alineacionInfra, ROUND(SUM(f.total),2) AS monto,(SELECT SUM(a1.cantidad) FROM proyecto_beneficiarios AS a1 WHERE a1.codigo=a.codigo GROUP BY a1.cantidad LIMIT 1) AS beneficiarios, IF(a.tipo='ANUAL','NO','SI') AS plurianual, CONCAT( DATE_FORMAT(a.fechaFin, '%d '), CASE MONTH(a.fechaFin) WHEN 1 THEN 'Enero' WHEN 2 THEN 'Febrero' WHEN 3 THEN 'Marzo' WHEN 4 THEN 'Abril' WHEN 5 THEN 'Mayo' WHEN 6 THEN 'Junio' WHEN 7 THEN 'Julio' WHEN 8 THEN 'Agosto' WHEN 9 THEN 'Septiembre' WHEN 10 THEN 'Octubre' WHEN 11 THEN 'Noviembre' WHEN 12 THEN 'Diciembre' END, DATE_FORMAT(a.fechaFin, ' %Y')) AS fechaFin,a.objetivoGeneral,g.nombre AS justificacion,c.idSector FROM proyecto_descripcion AS a LEFT JOIN configuracion.usuario AS b ON b.idCredencial=a.idCredencial LEFT JOIN proyecto_sector AS c ON a.codigo=c.codigo LEFT JOIN sector AS d ON d.idSector=c.idSector LEFT JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigo LEFT JOIN proyecto_presupuesto AS f ON f.codigo=a.codigo AND f.idNivel1 IS NOT NULL LEFT JOIN proyecto_justificacion AS g ON g.codigo=a.codigo WHERE a.codigo='$codigo' GROUP BY a.codigo;");


    }

    public function proyectoExistenteEliminarBase($campo,$tabla,$codigo){
      return $this->constructor->actualiza__general__incentivo("DELETE FROM $tabla WHERE $campo='$codigo';");
    }

    public function informacion__observacion__infra($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigo=$post["codigo"];

      $idEnviado=$this->obtener__idEnviado($codigo);


      $consulta=$this->constructor->select__general__incentivo("SELECT IF(tipo LIKE '%Devuelto a %',observacionEnvio,'no') AS observacionEnvio FROM proyecto_enviado_antecedente WHERE idEnviado='$idEnviado'  ORDER BY id DESC LIMIT 1;");
      foreach ($consulta as $valor) {
        $observacionEnvioBd=$valor["observacionEnvio"];
      }


      if (!empty($observacionEnvioBd) && $observacionEnvioBd!=="no") {
        return $observacionEnvioBd;
      }else{
        return "no";
      }

    } 


    public function actas__cuantas__Anio() {

        $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM comite_acta WHERE YEAR(fecha)='".$this->anio."';");
        foreach ($consulta as $valor) {
            $cuantosBd=$valor["cuantos"];
        }

        $cuantosBd=intval($cuantosBd) + 1;

        return $cuantosBd;

    }

    public function enviar__acta__final($post) {

      $idComite=$post["idComite"];

      $numeroDeSesion=$this->actas__cuantas__Anio();

      $numeroFormateado = ($numeroDeSesion >= 1 && $numeroDeSesion <= 10) ? sprintf('%03d', $numeroDeSesion) : (($numeroDeSesion >= 11 && $numeroDeSesion <= 99) ? sprintf('%02d', $numeroDeSesion) : (string) $numeroDeSesion);

      $numeroActa=$numeroFormateado."- ".$this->anio;

      $this->constructor->actualiza__general__incentivo("UPDATE comite_acta SET fecha='$this->fecha', hora='$this->hora',numeroActa='$numeroActa' WHERE idComite='$idComite';");

      return $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='cerrada' WHERE id='$idComite';");

    } 


    public function obtener__documento__acta($post) {

      $idComite=$post["idComite"];

      return $this->constructor->select__archivo__incentivo("SELECT documento FROM comite_acta WHERE idComite='$idComite';",$this->ruta."actasComite/","documento");

    } 

    public function enviar__acta($post) {

        $idComite=$post["idComite"];

        $nombreArchivo=$idComite.".pdf";

        $rutaDefinitiva=$this->ruta."actasComite/";

        $rastreo=$this->constructor->archivoCargar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['size'],$rutaDefinitiva,$nombreArchivo);


        if ($rastreo===1) {
  
          $this->constructor->actualiza__general__incentivo("DELETE FROM comite_acta WHERE idComite='$idComite';");
     
          $this->constructor->inserta__general__incentivo("comite_acta", ['documento','idComite','fecha','hora'], array(
            ':documento' =>$nombreArchivo,
            ':idComite' => $idComite,
            ':fecha' => $this->fecha,
            ':hora' =>  $this->hora
          ));

          return $this->constructor->select__archivo__natural($nombreArchivo,$rutaDefinitiva);

        }else if($rastreo===2){
            return 2;
        }else if($rastreo===0){
            return 0;
        }


    } 


    public function comite__informacion__acta__word($post) {

      $idComite=$post["idComite"];
      $lugar=$post["lugar"];
      $horaFinalizacion=$post["hora"];
      $ordenDia=$post["ordenDia"];
      $desarrollo=$post["desarrollo"];
      $primerPunto=$post["primerPunto"];
      $asistentes=$post["asistentes"];
      $segundoPunto=$post["segundoPunto"];

      $this->constructor->actualiza__general__incentivo("DELETE FROM comite_acta_contenido WHERE idComite='$idComite' AND estado IS NULL;");
 
      $this->constructor->inserta__general__incentivo("comite_acta_contenido", ['lugarActa','idComite','fecha','hora','horaFinalizacion','ordenDia','desarrollo','primerPunto','asistentes','segundoPunto'], array(
        ':lugarActa' =>$lugar,
        ':idComite' => $idComite,
        ':fecha' => $this->fecha,
        ':hora' =>  $this->hora,
        ':horaFinalizacion' => $horaFinalizacion,
        ':ordenDia' => $ordenDia,
        ':desarrollo' => $desarrollo,
        ':primerPunto' => $primerPunto,
        ':asistentes' => $asistentes,
        ':segundoPunto' => $segundoPunto
      ));

      $contenido=$this->actaReunionWord->acta__portada($idComite);
      $contenido.=$this->actaReunionPdf->acta__desarrollo($idComite);
      $contenido.=$this->actaReunionPdf->acta__participantes__delegados($idComite);
      $contenido.=$this->actaReunionPdf->informacion__acta__asistentes($idComite);
      $contenido.=$this->actaReunionPdf->informacion__acta__segundo__punto($idComite);
      $contenido.=$this->actaReunionPdf->informacion__acta__tercer__punto($idComite);
      $contenido.=$this->actaReunionPdf->informacion__acta__cuarto__punto($idComite);
      $contenido.=$this->actaReunionPdf->informacion__acta__quinto__punto($idComite,$idCredencial);

      $wordResult = $this->constructor__basePdf->generateWord__comite($contenido);

      return $wordResult;
    } 


    public function comite__informacion__acta__pdf($post) {

      $idComite=$post["idComite"];
      $lugar=$post["lugar"];
      $horaFinalizacion=$post["hora"];
      $ordenDia=$post["ordenDia"];
      $desarrollo=$post["desarrollo"];
      $primerPunto=$post["primerPunto"];
      $asistentes=$post["asistentes"];
      $segundoPunto=$post["segundoPunto"];
      $idCredencial=$post["idCredencial"];

      if(!empty($idComite)){

        $this->constructor->actualiza__general__incentivo("DELETE FROM comite_acta_contenido WHERE idComite='$idComite' AND estado IS NULL;");
   
        $this->constructor->inserta__general__incentivo("comite_acta_contenido", ['lugarActa','idComite','fecha','hora','horaFinalizacion','ordenDia','desarrollo','primerPunto','asistentes','segundoPunto'], array(
          ':lugarActa' =>$lugar,
          ':idComite' => $idComite,
          ':fecha' => $this->fecha,
          ':hora' =>  $this->hora,
          ':horaFinalizacion' => $horaFinalizacion,
          ':ordenDia' => $ordenDia,
          ':desarrollo' => $desarrollo,
          ':primerPunto' => $primerPunto,
          ':asistentes' => $asistentes,
          ':segundoPunto' => $segundoPunto
        ));

        $contenido=$this->actaReunionPdf->acta__portada($idComite);
        $contenido.=$this->actaReunionPdf->acta__desarrollo($idComite);
        $contenido.=$this->actaReunionPdf->acta__participantes__delegados($idComite);
        $contenido.=$this->actaReunionPdf->informacion__acta__asistentes($idComite);
        $contenido.=$this->actaReunionPdf->informacion__acta__segundo__punto($idComite);
        $contenido.=$this->actaReunionPdf->informacion__acta__tercer__punto($idComite);
        $contenido.=$this->actaReunionPdf->informacion__acta__cuarto__punto($idComite);
        $contenido.=$this->actaReunionPdf->informacion__acta__quinto__punto($idComite,$idCredencial);

        $pdfResult = $this->constructor__basePdf->generatePdf__comite($contenido);


        return $pdfResult;

      }

    } 

    public function informacion__contenido__acta($post){

      $idComite=$post["idComite"];

      return $this->constructor->select__general__incentivo("SELECT lugarActa,horaFinalizacion,ordenDia,desarrollo,primerPunto,asistentes,segundoPunto FROM comite_acta_contenido WHERE idComite='$idComite';");

    }


    public function informacion__secretaria__comite($post){

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);


      return $this->constructor->select__general__talento("SELECT a.cedula,UPPER(CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'))) AS nombreCompleto FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='$idUsuario';");

    }


    public function comite__informacion__proyectos__selectivo($post){

      $idComite=$post["idComite"];

      return $this->constructor->select__general__incentivo("SELECT a.id,a.idCredencial,a.fecha,a.hora,UPPER(a.estado) AS estado,b.asunto,b.fechaConvocatoria,b.horaConvocatoria,a.numeroComite,b.ordenDia FROM comite AS a INNER JOIN comite_convocatoria AS b ON a.id=b.idComite WHERE a.id='$idComite' ORDER BY a.id DESC;");

    }

    public function informacion__ministro(){

      return $this->constructor->select__general__talento("SELECT a.cedula,UPPER(CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'))) AS nombreCompleto FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.estadoUsuario='A' AND b.id_rol='5' ORDER BY a.id_usuario DESC LIMIT 1;");

    }


    public function informacion__presidente__del__comite($post){

      $idComite=$post["idComite"];

      $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario FROM comite_delegados WHERE idComite='$idComite' AND id_PuestoInstitucional='9999999';");
      foreach ($consulta as $valor) {
        $idUsuarioBd__1=$valor["idUsuario"];
      }


      return $this->constructor->select__general__talento("SELECT a.cedula,UPPER(CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'))) AS nombreCompleto FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='$idUsuarioBd__1';");


    }

    public function sumaProyectoTotal__v1($codigo){

      $consulta=$this->constructor->select__general__talento("SELECT monto FROM pro_proyecto WHERE codigo='$codigo';");

      foreach ($consulta as $valor) {
        $montoBd=$valor["monto"];
      }

      return $montoBd;

    }

    public function sumaCertificacion__v1($codigo){

      $consulta=$this->constructor->select__general__talento("SELECT SUM(monto) AS suma FROM pro_certificacion WHERE codigo='$codigo' GROUP BY codigo;");
      foreach ($consulta as  $valor) {
        $sumaBd=$valor["suma"];
      }

      if (empty($sumaBd)) {
        return 0;
      }else{
        return $sumaBd;
      }

    }


    public function actualizar__proyecto__con__notificacion($post) {

      $idComite=$post["idComite"];

      if(!empty($idComite)){

        /*====================================
        =            Calificación            =
        ====================================*/
        
        $arrayCalificados=array();
        $comiteCalificados=$this->constructor->select__general__incentivo("SELECT b.codigoUsuario FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id WHERE a.idComite='$idComite' AND b.estadoCalificacion='Comite' AND b.escogidoComite='1';");

        foreach ($comiteCalificados as $valor) {
          array_push($arrayCalificados, $valor["codigoUsuario"]);
        }

        foreach ($arrayCalificados as $valor) {
          
          $consulta=$this->constructor->select__general__incentivo("SELECT IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT UPPER(a1.estado) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.codigoUsuario='$valor';");

          foreach ($consulta as $valor__2) {
            $estadoBd=$valor__2["estado"];
          }

          $consultaObtenerId=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$valor'");
          foreach ($consultaObtenerId as $valor__enviado) {
            $idEnviado=$valor__enviado["id"];
          }

          if(!empty($estadoBd)){

            if ($estadoBd==="CALIFICAR") {

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='CALIFICADO', escogidoComite=NULL,fechaCalifica='".$this->fecha."',horaCalifica='".$this->hora."'  WHERE codigoUsuario='$valor';");

              $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
                ':idFisicamenteActual' =>0,
                ':idUsuarioActual' => 0,
                ':idFisicamenteNuevo' => 0,
                ':idUsuarioNuevo' => 0,
                ':idEnviado' =>  $idEnviado,
                ':textoDevuelto' => 'PROYECTO CALIFICADO',
                ':tipo' =>  "PROYECTO CALIFICADO",
                ':fecha' =>  $this->fecha,
                ':hora' =>  $this->hora,
              ));

            }else{

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='NEGADO', escogidoComite=NULL,fechaCalifica='".$this->fecha."',horaCalifica='".$this->hora."'  WHERE codigoUsuario='$valor';");

              $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
                ':idFisicamenteActual' =>0,
                ':idUsuarioActual' => 0,
                ':idFisicamenteNuevo' => 0,
                ':idUsuarioNuevo' => 0,
                ':idEnviado' =>  $idEnviado,
                ':textoDevuelto' => 'PROYECTO NEGADO',
                ':tipo' =>  "PROYECTO NEGADO",
                ':fecha' =>  $this->fecha,
                ':hora' =>  $this->hora,
              ));

            }

            $this->constructor->inserta__general__incentivo("proyecto_enviado_comite_finalizado", ['idEnviado','idComite','fecha','hora','tipo'], array(
              ':idEnviado' =>$idEnviado,
              ':idComite' => $idComite,
              ':fecha' => $this->fecha,
              ':hora' =>  $this->hora,
              ':tipo' => 'CALIFICACIÓN',
            ));

          }



        }
        
        /*=====  End of Calificación  ======*/
      
        /*====================================
        =            Modificación            =
        ====================================*/
                   
        $arrayModificados=array();
        $comiteCertificados=$this->constructor->select__general__incentivo("SELECT b.codigoUsuario FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id WHERE a.idComite='$idComite' AND b.estadoCalificacion='CALIFICADO' AND b.escogidoComiteModificacion='1';");

        foreach ($comiteCertificados as $valor) {
          array_push($arrayModificados, $valor["codigoUsuario"]);
        }

        
        foreach ($arrayModificados as $valor) {
          
          $consulta=$this->constructor->select__general__incentivo("SELECT IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT UPPER(a1.estado) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.codigoUsuario='$valor' AND a.estadoCalificacion='CALIFICADO';");

          foreach ($consulta as $valor__2) {
            $estadoBd=$valor__2["estado"];
          }

          $consultaObtenerId=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$valor'");
          foreach ($consultaObtenerId as $valor__enviado) {
            $idEnviado=$valor__enviado["id"];
          }

          if(!empty($estadoBd)){

            if ($estadoBd==="CALIFICAR") {

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estadoModificacion='APROBADO', estado='TERMINADO' WHERE estado='APROBADO' AND codigo='$valor';");
              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='TERMINADO',escogidoComiteModificacion=NULL WHERE codigoUsuario='$valor';");

              $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
                ':idFisicamenteActual' =>0,
                ':idUsuarioActual' => 0,
                ':idFisicamenteNuevo' => 0,
                ':idUsuarioNuevo' => 0,
                ':idEnviado' =>  $idEnviado,
                ':textoDevuelto' => 'MODIFICACIÓN APROBADA',
                ':tipo' =>  "MODIFICACIÓN APROBADA",
                ':fecha' =>  $this->fecha,
                ':hora' =>  $this->hora,
              ));


            }else{

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET estadoModificacion='NEGADO', estado='TERMINADO' WHERE estado='APROBADO' AND codigo='$valor';");
              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoModificacion='TERMINADO',escogidoComiteModificacion=NULL WHERE codigoUsuario='$valor';");

              $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha','hora'], array(
                ':idFisicamenteActual' =>0,
                ':idUsuarioActual' => 0,
                ':idFisicamenteNuevo' => 0,
                ':idUsuarioNuevo' => 0,
                ':idEnviado' =>  $idEnviado,
                ':textoDevuelto' => 'MODIFICACIÓN NEGADA',
                ':tipo' =>  "MODIFICACIÓN NEGADA",
                ':fecha' =>  $this->fecha,
                ':hora' =>  $this->hora,
              ));


            }

            $this->constructor->inserta__general__incentivo("proyecto_enviado_comite_finalizado", ['idEnviado','idComite','fecha','hora','tipo'], array(
              ':idEnviado' =>$idEnviado,
              ':idComite' => $idComite,
              ':fecha' => $this->fecha,
              ':hora' =>  $this->hora,
              ':tipo' =>  'MODIFICACIÓN',
            ));

          }

        }

        /*=====  End of Modificación  ======*/



        /*=================================================
        =            Certificación versión 1.0            =
        =================================================*/
        
        $consulta=$this->constructor->select__general__incentivo("SELECT a.codigo AS codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_certificacion_factura_tramite AS b ON a.codigo=b.codigo WHERE a.escogidoComiteCertificacion IS NOT NULL AND b.estadoRecomendacion='1' AND b.version1 IS NOT NULL GROUP BY a.codigoUsuario;");

        foreach ($consulta as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_codigo", ['codigoV1','fecha','hora'], array(
            ':codigoV1' =>$valor['codigoUsuario'],
            ':fecha' =>$this->fecha,
            ':hora' =>$this->hora,
          ));

        }

        $consulta=$this->constructor->select__general__incentivo("SELECT b.id,a.id AS idEnviado FROM proyecto_enviado AS a INNER JOIN proyecto_certificacion_factura_tramite AS b ON a.codigo=b.codigo WHERE a.escogidoComiteCertificacion IS NOT NULL AND b.estadoRecomendacion='1' AND b.version1 IS NOT NULL;");

        $bandera__certifica=false;

        foreach ($consulta as $valor) {

           $consulta__anadida=$this->constructor->select__general__incentivo("SELECT IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT UPPER(a1.estado) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,a.codigo AS codigoUsuario FROM proyecto_enviado AS a INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS b ON a.codigo = b.codigo WHERE a.id='".$valor["idEnviado"]."';");

           foreach ($consulta__anadida as $valor__anadida) {
            $estadoBd=$valor__anadida["estado"];
            $codigoUsuarioBd=$valor__anadida["codigoUsuario"];
           }

          $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_comite", ['idFactura','idComite','fecha','hora','tipo'], array(
            ':idFactura' =>$valor['id'],
            ':idComite' =>$idComite,
            ':fecha' =>$this->fecha,
            ':hora' =>$this->hora,
            ':tipo' =>  'CERTIFICACIÓN',
          ));

           $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET estado='A',estadoRevision=0, estadoRecomendacion=0 WHERE id='".$valor["id"]."'");

           
           if ($estadoBd==="NEGAR") {

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET estado='N',estadoRevision=0, estadoRecomendacion=0 WHERE id='".$valor["id"]."'");

            $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idFactura','textoDevuelto','tipo', 'fecha','hora'], array(
              ':idFisicamenteActual' =>0,
              ':idUsuarioActual' => 0,
              ':idFisicamenteNuevo' => 0,
              ':idUsuarioNuevo' => 0,
              ':idFactura' =>  $valor["idEnviado"],
              ':textoDevuelto' => 'CERTIFICADO NEGADO',
              ':tipo' =>  "CERTIFICADO NEGADO",
              ':fecha' =>  $this->fecha,
              ':hora' =>  $this->hora,
            ));

           }else{

            $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idFactura','textoDevuelto','tipo', 'fecha','hora'], array(
              ':idFisicamenteActual' =>0,
              ':idUsuarioActual' => 0,
              ':idFisicamenteNuevo' => 0,
              ':idUsuarioNuevo' => 0,
              ':idFactura' =>  $valor["idEnviado"],
              ':textoDevuelto' => 'CERTIFICADO APROBADO',
              ':tipo' =>  "CERTIFICADO APROBADO",
              ':fecha' =>  $this->fecha,
              ':hora' =>  $this->hora,
            ));

            foreach ($this->sumaCertificacion($codigoUsuarioBd) as $valorCertificacionAdicional) {
              $sumaCertificacion=$valorCertificacionAdicional["sumaCertificacion"];
            }

            $sumaCertificacion=floatval($sumaCertificacion) + floatval($this->sumaCertificacion__v1($codigoUsuarioBd));

            $totalSuma=$this->sumaProyectoTotal__v1($codigoUsuarioBd);


            if (floatval($sumaCertificacion) >= floatval($totalSuma) && $bandera__certifica===false) {


              foreach ($this->informacion__proyecto__enviado__certificacion($codigoUsuarioBd) as $valorCertificacionAdicional) {
                $idCredencial=$valorCertificacionAdicional["idCredencial"];
              }


              foreach ($this->recuperarInformacionUsuario__general__correo($idCredencial) as $valorCertificacionAdicional) {
                $correoEnvio=$valorCertificacionAdicional["correoEnvio"];
                $nombreUsuario=$valorCertificacionAdicional["nombreUsuario"];
              }

              $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Ministerio del Deporte,</div><br><div style="font-size:10px; font-weight:bold;">Estimado '.$nombreUsuario.',</div><br><div style="font-size:10px;">Conforme el Acuerdo Ministerial Nro. 0243 del 21 de noviembre de 2023 y sus reformas, el artículo 50.- Del informe final de cumplimiento señala: “… El término concedido para la presentación del informe final será de sesenta (60) días contados desde la fecha en la que el programa y/o proyecto deportivo concluyó su proceso de certificación…”</div><br><br><div style="font-size:10px; font-weight:bold;">Ingrese al menú Seguimiento y cargue su informe</div></body></html>';

              $this->constructor->enviarCorreo([$correoEnvio],$bodyMensaje);

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoSeguimiento='P' WHERE codigoUsuario='$codigoUsuarioBd';"); 

              $this->constructor->inserta__general__incentivo("proyecto_seguimiento_final", ['codigoV1','tipo','razon','fecha','hora'], array(

                ':codigoV1' =>$codigoUsuarioBd,
                ':tipo' =>'MONTO COMPLETADO',
                ':razon' =>'MONTO COMPLETADO',
                ':fecha' =>$this->fecha,
                ':hora' =>$this->hora,

              ));

              $bandera__certifica=true;

            }


           }

        }

        
        /*=====  End of Certificación versión 1.0  ======*/

      
        /*=====================================
        =            Certificación            =
        =====================================*/
        
        $consulta=$this->constructor->select__general__incentivo("SELECT a.codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_certificacion_factura_tramite AS b ON a.codigoUsuario=b.codigo WHERE a.escogidoComiteCertificacion IS NOT NULL AND b.estadoRecomendacion='1' AND b.version1 IS NULL GROUP BY a.codigoUsuario;");


        foreach ($consulta as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_codigo", ['codigo','fecha','hora'], array(
            ':codigo' =>$valor['codigoUsuario'],
            ':fecha' =>$this->fecha,
            ':hora' =>$this->hora,
          ));

        }

        $consulta=$this->constructor->select__general__incentivo("SELECT b.id,a.id AS idEnviado FROM proyecto_enviado AS a INNER JOIN proyecto_certificacion_factura_tramite AS b ON a.codigoUsuario=b.codigo WHERE a.escogidoComiteCertificacion IS NOT NULL AND b.estadoRecomendacion='1' AND b.version1 IS NULL;");

        $bandera__certifica=false;


        foreach ($consulta as $valor) {

           $consulta__anadida=$this->constructor->select__general__incentivo("SELECT IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT UPPER(a1.estado) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,a.codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.id='".$valor["idEnviado"]."';");

           foreach ($consulta__anadida as $valor__anadida) {
            $estadoBd=$valor__anadida["estado"];
            $codigoUsuarioBd=$valor__anadida["codigoUsuario"];
           }


            $this->constructor->inserta__general__incentivo("proyecto_certificacion_factura_comite", ['idFactura','idComite','fecha','hora','tipo'], array(
              ':idFactura' =>$valor['id'],
              ':idComite' =>$idComite,
              ':fecha' =>$this->fecha,
              ':hora' =>$this->hora,
              ':tipo' =>  'CERTIFICACIÓN',
            ));


           $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET estado='A',estadoRevision=0, estadoRecomendacion=0 WHERE id='".$valor["id"]."'");

           if ($estadoBd==="NEGAR") {

            $this->constructor->actualiza__general__incentivo("UPDATE proyecto_certificacion_factura_tramite SET estado='N',estadoRevision=0, estadoRecomendacion=0 WHERE id='".$valor["id"]."'");

            $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idFactura','textoDevuelto','tipo', 'fecha','hora'], array(
              ':idFisicamenteActual' =>0,
              ':idUsuarioActual' => 0,
              ':idFisicamenteNuevo' => 0,
              ':idUsuarioNuevo' => 0,
              ':idFactura' =>  $valor["idEnviado"],
              ':textoDevuelto' => 'CERTIFICADO NEGADO',
              ':tipo' =>  "CERTIFICADO NEGADO",
              ':fecha' =>  $this->fecha,
              ':hora' =>  $this->hora,
            ));

           }else{


            $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idFactura','textoDevuelto','tipo', 'fecha','hora'], array(
              ':idFisicamenteActual' =>0,
              ':idUsuarioActual' => 0,
              ':idFisicamenteNuevo' => 0,
              ':idUsuarioNuevo' => 0,
              ':idFactura' =>  $valor["idEnviado"],
              ':textoDevuelto' => 'CERTIFICADO APROBADO',
              ':tipo' =>  "CERTIFICADO APROBADO",
              ':fecha' =>  $this->fecha,
              ':hora' =>  $this->hora,
            ));

            foreach ($this->sumaCertificacion($codigoUsuarioBd) as $valorCertificacionAdicional) {
              $sumaCertificacion=$valorCertificacionAdicional["sumaCertificacion"];
            }

            foreach ($this->sumaProyectoTotal($codigoUsuarioBd) as $valorCertificacionAdicional) {
              $totalSuma=$valorCertificacionAdicional["totalSuma"];
            }


            if (floatval($sumaCertificacion) >= floatval($totalSuma) && $bandera__certifica===false) {


              foreach ($this->informacion__proyecto__enviado__certificacion($codigoUsuarioBd) as $valorCertificacionAdicional) {
                $idCredencial=$valorCertificacionAdicional["idCredencial"];
              }


              foreach ($this->recuperarInformacionUsuario__general__correo($idCredencial) as $valorCertificacionAdicional) {
                $correoEnvio=$valorCertificacionAdicional["correoEnvio"];
                $nombreUsuario=$valorCertificacionAdicional["nombreUsuario"];
              }

              $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Ministerio del Deporte,</div><br><div style="font-size:10px; font-weight:bold;">Estimado '.$nombreUsuario.',</div><br><div style="font-size:10px;">Conforme el Acuerdo Ministerial Nro. 0243 del 21 de noviembre de 2023 y sus reformas, el artículo 50.- Del informe final de cumplimiento señala: “… El término concedido para la presentación del informe final será de sesenta (60) días contados desde la fecha en la que el programa y/o proyecto deportivo concluyó su proceso de certificación…”</div><br><br><div style="font-size:10px; font-weight:bold;">Ingrese al menú Seguimiento y cargue su informe</div></body></html>';

              $this->constructor->enviarCorreo([$correoEnvio],$bodyMensaje);

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoSeguimiento='P' WHERE codigoUsuario='$codigoUsuarioBd';"); 

              $this->constructor->inserta__general__incentivo("proyecto_seguimiento_final", ['codigo','tipo','razon','fecha','hora'], array(

                ':codigo' =>$codigoUsuarioBd,
                ':tipo' =>'MONTO COMPLETADO',
                ':razon' =>'MONTO COMPLETADO',
                ':fecha' =>$this->fecha,
                ':hora' =>$this->hora,

              ));

              $bandera__certifica=true;

            }


           }

          
          $this->constructor->actualiza__general__incentivo("UPDATE comite_proyectos SET modulo='CERTIFICACION' WHERE idComite='$idComite' AND idEnviado='".$valor["id"]."';");


        }


        
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComiteCertificacion=NULL;");


        /*=====  End of Certificación  ======*/


        $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='finalizada', activo='I' WHERE id='$idComite';");


        return 1;

      }


    } 


    public function recuperarInformacionUsuario__general__correo($idCredencial){

      $consulta=$this->constructor->select__general("SELECT idTipoUsuario FROM credencial_tipo WHERE idCredencial='$idCredencial';");

      foreach ($consulta as $valor) {
        $idTipoUsuario_bd=$valor["idTipoUsuario"];
      }

      if (intval($idTipoUsuario_bd)===3 || intval($idTipoUsuario_bd)===4 || intval($idTipoUsuario_bd)===5) {
        return $this->constructor->select__general("SELECT a.correo1 AS correoEnvio, b.razonSocial AS nombreUsuario FROM representante AS a INNER JOIN organismo AS b ON a.idCredencial=b.idCredencial WHERE a.idCredencial='$idCredencial';");
      }else{
        return $this->constructor->select__general("SELECT a.email1 AS correoEnvio,b.nombre AS nombreUsuario FROM contacto AS a INNER JOIN usuario AS b ON a.idCredencial=b.idCredencial WHERE a.idCredencial='$idCredencial';");
      }

    }    


    public function informacion__proyecto__enviado__certificacion($codigo){
      return $this->constructor->select__general__incentivo("SELECT id,idCredencial FROM proyecto_enviado WHERE codigoUsuario='$codigo' OR codigo='$codigo';");
    }    

    public function sumaProyectoTotal($codigo){
      return $this->constructor->select__general__incentivo("SELECT SUM(total) AS totalSuma FROM proyecto_presupuesto WHERE codigo='$codigo' AND nivel!=0 GROUP BY codigo;");
    }

    public function sumaCertificacion($codigo){
      return $this->constructor->select__general__incentivo("SELECT SUM(subotal) AS sumaCertificacion FROM proyecto_certificacion_factura_tramite WHERE estado='A' AND codigo='$codigo' GROUP BY codigo;");
    }


    public function obtenerExistente__notificacion($post) {
      $docuRuta=$post["docuRuta"];
      return $this->constructor->select__archivo__natural__ruta('../../react/firmaElectronica/documentos/'.$docuRuta);
      // return $this->constructor->select__archivo__natural__ruta('https://servicios.deporte.gob.ec/poa2/firmaRepositorio/documentos/'.$docuRuta);
    } 

    public function obtener__informacion__funcionario($post){

      $idCredencial=$post["idCredencial"];
      $consulta=$this->constructor->select__general("SELECT idUsuario FROM funcionario AS a WHERE idCredencial='$idCredencial';");
      foreach ($consulta as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $this->constructor->select__general__talento("SELECT a.cedula FROM th_usuario AS a WHERE a.id_usuario='$idUsuarioBd';");

    }


    public function notificacion__comite__favorable__conjunto__pdf__contenido($post) {

      $contenido=$post["contenido"];
      $pdfResult = $this->constructor__basePdf->generatePdf__comite($contenido);

      return $pdfResult;

    } 


    public function notificacion__comite__favorable__conjunto($post) {

      $idComite=$post["idComite"];

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT a.id AS idEnviado, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) = (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT UPPER(a1.estado) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.escogidoComite IS NOT NULL AND (a.estadoCalificacion = 'Comite' OR a.estadoCalificacion = 'Comite Priorizado' OR a.estadoCalificacion = 'Comite Observado') AND a.idComite = '$idComite';");


      foreach ($consulta as  $clave =>  $valor) {

          $contenido=$this->notificacionPdf->portadaInicial($valor["idEnviado"]);
          $contenido.=$this->notificacionPdf->baseLegal($valor["idEnviado"]);
          $contenido.=$this->notificacionPdf->notificacion($valor["idEnviado"],$idComite,$valor["estado"]);

          array_push($array, $valor["idEnviado"]."__".$contenido);

      }


      return $array;

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

     $consulta = $this->constructor->select__general__incentivo("SELECT idComponentes FROM incentivorespaldo.proyecto_presupuesto WHERE estado='A' AND tipoIngreso='modificacion' AND codigo='$codigo' LIMIT 1;");

     foreach ($consulta as $valor) {
       $idComponentes=$valor["idComponentes"];
     }

     if(!empty($idComponentes)){


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

     $consulta = $this->constructor->select__general__incentivo("SELECT idComponentes FROM incentivorespaldo.proyecto_cronograma_actividades WHERE estado='A' AND tipoIngreso='modificacion' AND codigo='$codigo' LIMIT 1;");

     foreach ($consulta as $valor) {
       $idComponentes=$valor["idComponentes"];
     }

     if (!empty($idComponentes)) {

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


     }

     return 1;

    }

    public function codigo__modificacion($codigo) {
        return $this->constructor->select__general__incentivo("SELECT idSolicitud,casoModificar FROM proyecto_modificacion_solicitud WHERE codigo='$codigo' AND estado='APROBADO';");
    } 

    public function obtener__certificacion__accion($idEnviado) {

        $consulta=$this->constructor->select__general__incentivo("SELECT a.id FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=a.codigoUsuario WHERE a.id='$idEnviado' GROUP BY e.codigo;");

        foreach ($consulta as $valor) {
          $id=$valor["id"]; 
        }


        if (empty($id)) {
          return 0;
        }else{
          return 1;
        }

    } 

    public function verificar__cuantos__tramites__certificacion($codigo){

      $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS contador FROM proyecto_certificacion_factura_codigo WHERE codigo='$codigo';");

      foreach ($consulta as $valor) {
        $contador=$valor["contador"];
      }

      if (empty($contador)) {
        return 2;
      }else{
        return (intval($consulta) + 2);
      }

    }
    
    public function generar__notificacion__comite__favorable__certificados__realizados($post) {

      $idFactura=$post["idFactura"];
      $idComite=$post["idComite"];
      $idEnviado=$post["valor"];
 
      $consulta=$this->estado__calificacion__general__idProyectoEnviado($idEnviado);
      foreach ($consulta as $valor) {
        $codigo=$valor["codigoUsuario"];
        $codigoComparar=$valor["codigo"];
      }

      if($this->obtener__version__en__certificacion($codigoComparar)==="SI"){
         $contenido.=$this->notificacionPdf->portadaInicial__certificado__v1($idComite,$codigoComparar);
      }else{
         $contenido.=$this->notificacionPdf->portadaInicial__certificado($idComite,$codigo);
      }
     
      $contenido.=$this->notificacionPdf->baseLegal__certificado();

      if($this->obtener__version__en__certificacion($codigoComparar)==="SI"){
         $contenido.=$this->notificacionPdf->datos__solicitante__v1($codigoComparar,$idEnviado);
         $contenido.=$this->notificacionPdf->datos__comprobante__de__venta__v1($idFactura,$codigoComparar);
         $contenido.=$this->notificacionPdf->condiciones__comprobante__v1($idFactura,$codigoComparar,$idComite);
         $contenido.=$this->notificacionPdf->declaracion__condiciones__certificacion($idFactura,$codigoComparar,$idComite);
      }else{
         $contenido.=$this->notificacionPdf->datos__solicitante($codigo,$idEnviado);
         $contenido.=$this->notificacionPdf->datos__comprobante__de__venta($idFactura,$codigo);
         $contenido.=$this->notificacionPdf->condiciones__comprobante($idFactura,$codigo,$idComite);
         $contenido.=$this->notificacionPdf->declaracion__condiciones__certificacion($idFactura,$codigoComparar,$idComite);

      }
     
      
      $pdfResult = $this->constructor__basePdf->generatePdf__comite($contenido);

      $tipo="certificacion__comprobante";

      return [$pdfResult,$idFactura,$tipo]; 

    } 


    public function notificacion__comite__favorable__certificacion($post) {

      $idComite=$post["idComite"];
      $idEnviado=$post["idEnviado"];
      $estado=$post["estado"];
      $generar=$post["generar"];

      $array=array();
      $array__pdf=array();

      $consulta=$this->estado__calificacion__general__idProyectoEnviado($idEnviado);
      foreach ($consulta as $valor) {
        $codigo=$valor["codigoUsuario"];
        $codigoComparador=$valor["codigo"];
      }

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_certificacion_factura_tramite AS a WHERE (codigo='$codigo' OR codigo='$codigoComparador') AND estadoRecomendacion='1';");

      foreach ($consulta as $valor) {
        array_push($array, $valor["id"]);
      }

      if(count($array)>0){
        return $array;
      }else{
        return "no";
      }


    } 


    public function notificacion__comite__favorable($post) {

      $idComite=$post["idComite"];
      $idEnviado=$post["idEnviado"];
      $estado=$post["estado"];
      $generar=$post["generar"];

      $consulta=$this->estado__calificacion__general__idProyectoEnviado($idEnviado);
      foreach ($consulta as $valor) {
        $estadoCalificacion=$valor["estadoCalificacion"];
        $codigo=$valor["codigoUsuario"];
        $codigoComparar=$valor["codigo"];
      }

      $consultaModificas=$this->constructor->select__general__incentivo("SELECT escogidoComiteModificacion,escogidoComiteCertificacion FROM proyecto_enviado WHERE id='$idEnviado';");
      foreach ($consultaModificas as $valorM) {
        $escogidoComiteModificacionBd=$valorM["escogidoComiteModificacion"];
        $escogidoComiteCertificacionBd=$valorM["escogidoComiteCertificacion"];
      }

      $consultaModificas__2=$this->constructor->select__general__incentivo("SELECT idSolicitud FROM proyecto_modificacion_solicitud WHERE estadoModificacion='ENVIADO' AND codigo='$codigo' AND activacionModificacion IS NULL;");
      foreach ($consultaModificas__2 as $valor__modificas) {
        $idSolicitudBdModificas=$valor__modificas["idSolicitud"];
      }


      if($this->obtener__version__en__certificacion($codigoComparar)==="SI"){

        $contenido.=$this->notificacionPdf->portadaInicial__certificacion__v1($codigoComparar,$idEnviado);
        $contenido.=$this->notificacionPdf->base__legal__certificacion();
        $contenido.=$this->notificacionPdf->notificacion__certificacion__v1($codigoComparar,$idEnviado);

        $contadorRecibidoSolicitud=$this->verificar__cuantos__tramites__certificacion($codigoComparar);

        $idSolicitudEnviar=$contadorRecibidoSolicitud;
        $tipo="certificacion";


      }else if($estadoCalificacion==="CALIFICADO" && intval($escogidoComiteModificacionBd)===1 && intval($escogidoComiteCertificacionBd)!==1){


        $consulta=$this->codigo__modificacion($codigo);
        foreach ($consulta as $valor) {
          $idSolicitud=$valor["idSolicitud"];
        }

        $contenido=$this->informePdf->portada__informe__notificacion($codigo);
        $contenido.=$this->informePdf->portada__informe__antecedente($codigo);
        $contenido.=$this->informePdf->base__legal__notifiacion($codigo);
        $contenido.=$this->informePdf->notificacion_legal_notificacion__caso__b($codigo,$estado);

        if ($estado!=="NEGADO" && !empty($idSolicitudBdModificas)) {
          $this->actualizar__presupuesto__proyecto__en__modificacion($codigo,$idSolicitud);
          $this->actualizar__cronogramaDeActividades__proyecto__en__modificacion($codigo,$idSolicitud);
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_modificacion_solicitud SET activacionModificacion='A' WHERE estadoModificacion='ENVIADO' AND codigo='$codigo';");
        }

        $idSolicitudEnviar=$idSolicitud;
        $tipo="modificacion";


      }else if($this->obtener__certificacion__accion($idEnviado)===1 || intval($escogidoComiteCertificacionBd)===1){


        $contenido.=$this->notificacionPdf->portadaInicial__certificacion($codigo,$idEnviado);
        $contenido.=$this->notificacionPdf->base__legal__certificacion();
        $contenido.=$this->notificacionPdf->notificacion__certificacion($codigo,$idEnviado);

        $contadorRecibidoSolicitud=$this->verificar__cuantos__tramites__certificacion($codigo);

        $idSolicitudEnviar=$contadorRecibidoSolicitud;
        $tipo="certificacion";


      }else{

        $contenido=$this->notificacionPdf->portadaInicial($idEnviado,$idComite);
        $contenido.=$this->notificacionPdf->baseLegal($idEnviado);
        $contenido.=$this->notificacionPdf->notificacion($idEnviado,$idComite,$estado);
        $tipo="calificacion";

        $idSolicitudEnviar=0;

      }


      $pdfResult = $this->constructor__basePdf->generatePdf__comite($contenido);

      return [$pdfResult,$idSolicitudEnviar,$tipo];

    } 


    public function informacionAnalista__calificacion__general($post){

      $idComite=$post["idComite"];
      $id=$post["id"];

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT a.idUsuario,IF((SELECT UPPER(a1.estado) FROM comite_proyectos_calificadores AS a1 WHERE a1.idComite=a.idComite AND a1.idEnviado='$id' AND a.idUsuario=a1.idUsuario LIMIT 1) IS NULL,'PENDIENTE' ,(SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idComite=a.idComite AND a1.idEnviado='$id' AND a.idUsuario=a1.idUsuario LIMIT 1)) AS estado FROM comite_delegados AS a WHERE a.participa='A' AND  a.idComite='$idComite';");

      foreach ($consulta as $valor) {
        

          $consultaUsuario=$this->constructor->select__general__talento("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='".$valor["idUsuario"]."';");


          $consultaEnviado__calificacion=$this->constructor->select__general__incentivo("SELECT id FROM comite_proyectos_calificadores_enviado WHERE idUsuario='".$valor["idUsuario"]."' AND idComite='$idComite';");

          $idConsultaEnvio="";

          foreach ($consultaEnviado__calificacion as $valor__consultarEnvio) {
            $idConsultaEnvio=$valor__consultarEnvio["id"];
          }

          foreach ($consultaUsuario as $valor2) {

            if(empty($idConsultaEnvio)){
              array_push($array,$valor2["nombreCompleto"]."__".$valor2["descripcionPuestoInstitucional"]."__"."PENDIENTE");
            }else{
              array_push($array,$valor2["nombreCompleto"]."__".$valor2["descripcionPuestoInstitucional"]."__".$valor["estado"]);
            }

          }
       
      }

      return $array;

    }

    public function recibir__estado__calificacion__ciudadanos($post){

      $idComite=$post["idComite"];
      $idCredencial=$post["idCredencial"];

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM comite_proyectos_calificadores_enviado WHERE idCredencial='$idCredencial' AND idComite='$idComite';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 0;
      }else{
        return 1;
      }

    }

    public function inserta__ratificacion__ingreso($post){

      $idCredencial=$post["idCredencial"];
      $idComite=$post["idComite"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);

      $this->constructor->actualiza__general__incentivo("UPDATE comite_proyectos_calificadores SET enviado='A' WHERE idComite='$idComite' AND idCredencial='$idCredencial';");

      return $this->constructor->inserta__general__incentivo("comite_proyectos_calificadores_enviado", ['idCredencial','idComite','fecha','hora','idUsuario'], array(
        ':idCredencial' =>$idCredencial,
        ':idComite' => $idComite,
        ':fecha' => $this->fecha,
        ':hora' =>  $this->hora,
        ':idUsuario' =>  $idUsuario,
      ));

    }


    public function cuantos__proyectos__asociados($post){

      $idComite=$post["idComite"];
      $idCredencial=$post["idCredencial"];

      $sumador=0;

      $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM comite_proyectos_calificadores WHERE idComite='$idComite' AND idCredencial='$idCredencial' GROUP BY idEnviado;");
      foreach ($consulta as $valor) {
        $sumador=1+$sumador;
      }


      return $sumador;

    }


    public function cuantos__proyectos($post){

      $idComite=$post["idComite"];
      $idCredencial=$post["idCredencial"];



      $consulta=$this->constructor->select__general__incentivo("SELECT COUNT(*) AS total_registros FROM ((SELECT a.id, a.idEnviado, b.codigoUsuario, b.codigo, c.nombre, IF(d.estado IS NULL, 'PENDIENTE', IF(UPPER(d.estado)='CALIFICAR', 'CALIFICADO', 'NEGADO')) AS estado, IF((b.idUsuarioModificacion = 'Comite' OR b.idUsuarioModificacion = 'Comite Priorizado' OR b.idUsuarioModificacion = 'Comite Observado') AND b.estadoModificacion != 'TERMINADA' AND b.escogidoComiteCertificacion != '1', 'MODIFICACIÓN', IF(b.escogidoComiteCertificacion IS NOT NULL, 'CERTIFICACIÓN', 'CALIFICACIÓN')) AS estadoAnalisis, (SELECT GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ') FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo=b.codigoUsuario AND z1.estado='P' GROUP BY z1.codigo) AS ids, (SELECT GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ') FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo=b.codigoUsuario AND z1.estado='P' GROUP BY z1.codigo) AS idsIncremental, IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=b.codigoUsuario LIMIT 1), 'NO') AS version FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado = b.id INNER JOIN proyecto_descripcion AS c ON c.codigo = b.codigoUsuario LEFT JOIN comite_proyectos_calificadores AS d ON d.idEnviado = a.idEnviado AND d.idCredencial = '$idCredencial' AND a.idComite = d.idComite WHERE a.idComite = '$idComite' GROUP BY b.codigo) UNION (SELECT a.id, a.idEnviado, b.codigo AS codigoUsuario, b.codigo, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombre, IF(d.estado IS NULL, 'PENDIENTE', IF(UPPER(d.estado)='CALIFICAR', 'CALIFICADO', 'NEGADO')) AS estado, IF((b.idUsuarioModificacion = 'Comite' OR b.idUsuarioModificacion = 'Comite Priorizado' OR b.idUsuarioModificacion = 'Comite Observado') AND b.estadoModificacion != 'TERMINADA' AND b.escogidoComiteCertificacion != '1', 'MODIFICACIÓN', IF(b.escogidoComiteCertificacion IS NOT NULL, 'CERTIFICACIÓN', 'CALIFICACIÓN')) AS estadoAnalisis, (SELECT GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ') FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo = b.codigo AND z1.estado = 'P' GROUP BY z1.codigo) AS ids, (SELECT GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ') FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo = b.codigo AND z1.estado = 'P' GROUP BY z1.codigo) AS idsIncremental, IFNULL((SELECT IF(a1.version1 IS NULL, 'NO', 'SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo = b.codigo LIMIT 1), 'NO') AS version FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado = b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo = b.codigo LEFT JOIN comite_proyectos_calificadores AS d ON d.idEnviado = a.idEnviado AND d.idCredencial = '$idCredencial' AND a.idComite = d.idComite WHERE a.idComite = '$idComite' GROUP BY b.codigo)) AS subconsulta;");

      foreach ($consulta as $valor) {
        $total_registros=$valor["total_registros"];
      }

      return $total_registros;

    }

    public function iniciarSesion__comite($post){

      $idComite=$post["idComite"];
      return $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='iniciada' WHERE id='$idComite';");

    }


    public function participar__comite($post){

      $idDelegado=$post["idDelegado"];
      $idComite=$post["idComite"];
      $valor=$post["valor"];

      if(intval($valor)===0){
        return $this->constructor->actualiza__general__incentivo("UPDATE comite_delegados SET participa=NULL WHERE id='$idDelegado' AND idComite='$idComite';");
      }else{
        return $this->constructor->actualiza__general__incentivo("UPDATE comite_delegados SET participa='A' WHERE id='$idDelegado' AND idComite='$idComite';");
      }
      

    }


    public function obtener__idEnviado($codigoProyecto){

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigoProyecto';");
      foreach ($consulta as $valor) {
       $idBd=$valor["id"];
      }

      return $idBd;

    }

    public function comite__informacion__proyectos__miembros($post){

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);

      return $this->constructor->select__general__incentivo("SELECT a.id,a.idCredencial,a.fecha,a.hora,UPPER(a.estado) AS estado,b.asunto,b.fechaConvocatoria,b.horaConvocatoria,a.numeroComite  FROM comite AS a INNER JOIN comite_convocatoria AS b ON a.id=b.idComite INNER JOIN comite_delegados AS c ON c.idComite=a.id AND c.idUsuario='$idUsuario' WHERE a.estado='iniciada' GROUP BY a.id  ORDER BY a.id DESC;");

    }


    public function comite__informacion__proyectos($post){


      return $this->constructor->select__general__incentivo("SELECT a.id,a.idCredencial,a.fecha,a.hora,UPPER(a.estado) AS estado,b.asunto,b.fechaConvocatoria,b.horaConvocatoria,a.numeroComite  FROM comite AS a INNER JOIN comite_convocatoria AS b ON a.id=b.idComite ORDER BY a.id DESC;");

    }


    public function consultar__observacion__proyecto__comite__u($post){

       $codigoProyecto=$post["codigoProyecto"];
       $idEnviado=$this->obtener__idEnviado($codigoProyecto);

      return $this->constructor->select__general__incentivo("SELECT estado,textoNegacion,textoObservacion FROM comite_proyectos_calificadores WHERE idEnviado='".$idEnviado."' AND idComite='".$post["idComite"]."' AND idCredencial='".$post["idCredencial"]."';");

    }


    public function insertar__observacion__proyecto__comite__u($post){

      $codigoProyecto=$post["codigoProyecto"];
      $codigoUsuario=$post["codigoUsuario"];
      $texto__observarNegacion=$post["texto__observarNegacion"];
      $idCredencial=$post["idCredencial"];
      $idComite=$post["idComite"];
      $valorRadio=$post["valorRadio"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $idEnviado=$this->obtener__idEnviado($codigoProyecto);

      $this->constructor->actualiza__general__incentivo("DELETE FROM comite_proyectos_calificadores WHERE idCredencial='$idCredencial' AND idComite='$idComite' AND idEnviado='$idEnviado';");

      if($valorRadio==="calificar"){
        $texto__observarNegacion=NULL;
      }


      $this->constructor->inserta__general__incentivo("comite_proyectos_calificadores", ['idEnviado','idComite','fecha','hora','tipo','estado', 'idCredencial', 'textoNegacion', 'idUsuario'], array(
        ':idEnviado' =>$idEnviado,
        ':idComite' => $idComite,
        ':fecha' => $this->fecha,
        ':hora' =>  $this->hora,
        ':tipo' => 'calificacion',
        ':estado' =>$valorRadio,
        ':idCredencial' => $idCredencial,
        ':textoNegacion' =>$texto__observarNegacion,
        ':idUsuario' =>$idUsuario,
      ));


      return 1;

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

    public function proyectos__revisar__comite__revisar($idComite,$idCredencial){

      return $this->constructor->select__general__incentivo("(SELECT a.id,a.idEnviado,b.codigoUsuario,b.codigo,c.nombre, IF(d.estado IS NULL,'PENDIENTE',IF(UPPER(d.estado)='CALIFICAR','CALIFICADO','NEGADO')) AS estado,IF((b.idUsuarioModificacion = 'Comite' OR b.idUsuarioModificacion = 'Comite Priorizado' OR b.idUsuarioModificacion = 'Comite Observado') AND b.estadoModificacion!='TERMINADA' AND b.escogidoComiteCertificacion!='1','MODIFICACIÓN',IF(b.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,(SELECT GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ')  FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo=b.codigoUsuario AND z1.estado='P' GROUP BY z1.codigo) AS ids,(SELECT GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ')  FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo=b.codigoUsuario AND z1.estado='P' GROUP BY z1.codigo) AS idsIncremental,IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=b.codigoUsuario LIMIT 1),'NO') AS version FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id INNER JOIN proyecto_descripcion AS c ON c.codigo=b.codigoUsuario LEFT JOIN comite_proyectos_calificadores AS d ON d.idEnviado=a.idEnviado AND d.idCredencial='$idCredencial'  AND a.idComite=d.idComite WHERE a.idComite='$idComite' GROUP BY b.codigo) UNION (SELECT a.id,a.idEnviado,b.codigo AS codigoUsuario,b.codigo,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombre, IF(d.estado IS NULL,'PENDIENTE',IF(UPPER(d.estado)='CALIFICAR','CALIFICADO','NEGADO')) AS estado,IF((b.idUsuarioModificacion = 'Comite' OR b.idUsuarioModificacion = 'Comite Priorizado' OR b.idUsuarioModificacion = 'Comite Observado') AND b.estadoModificacion!='TERMINADA' AND b.escogidoComiteCertificacion!='1','MODIFICACIÓN',IF(b.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis,(SELECT GROUP_CONCAT(DISTINCT z1.id SEPARATOR ', ')  FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo=b.codigo AND z1.estado='P' GROUP BY z1.codigo) AS ids,(SELECT GROUP_CONCAT(DISTINCT z1.idIncremental SEPARATOR ', ')  FROM proyecto_certificacion_factura_tramite AS z1 WHERE z1.codigo=b.codigo AND z1.estado='P' GROUP BY z1.codigo) AS idsIncremental,IFNULL((SELECT IF(a1.version1 IS NULL,'NO','SI') FROM proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo=b.codigo LIMIT 1),'NO') AS version FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON c.codigo=b.codigo LEFT JOIN comite_proyectos_calificadores AS d ON d.idEnviado=a.idEnviado AND d.idCredencial='$idCredencial'  AND a.idComite=d.idComite WHERE a.idComite='$idComite' GROUP BY b.codigo);");

    }

    public function proyectos__revisar__comite($idComite){

      return $this->constructor->select__general__incentivo("SELECT a.id,a.idEnviado,b.codigoUsuario,b.codigo,c.nombre,IF(a.estado IS NULL,'PENDIENTE',a.estado) AS estado FROM comite_proyectos AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id INNER JOIN proyecto_descripcion AS c ON c.codigo=b.codigoUsuario WHERE a.idComite='$idComite';");

    }


    public function personas__convocadas($valor,$proyectoId,$idComite){

      if (intval($valor)===0) {
        $this->constructor->actualiza__general__incentivo("UPDATE comite_delegados SET sesion=NULL WHERE id='$proyectoId' AND idComite='$idComite';"); 
      }else{
        $this->constructor->actualiza__general__incentivo("UPDATE comite_delegados SET sesion='1' WHERE id='$proyectoId' AND idComite='$idComite';"); 
      }

      return 1;

    }

    public function iniciar__sesion($idComite){

      return $this->constructor->actualiza__general__incentivo("UPDATE comite SET estado='sesón iniciada' WHERE id='$idComite';");

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
      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComite=NULL,idComite=NULL WHERE id='$proyectoId';"); 
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
        ':modulo' =>'CALIFICACION',
      ));   

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET escogidoComite=1,idComite='$idComite' WHERE id='$proyectoId';"); 
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

    public function obtener__proyectos__recomendados__analistas($idComite,$estado){

      if ($estado==="FINALIZADA" || $estado==="CERRADA") {
       
        return $this->constructor->select__general__incentivo("SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) >= (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo INNER JOIN comite_proyectos AS c ON c.idEnviado=a.id WHERE c.idComite = '$idComite' AND c.modulo='CALIFICACION';");

      }else{

        return $this->constructor->select__general__incentivo("SELECT a.id, a.codigo, UPPER(b.nombre) AS nombre, IF(a.escogidoComite IS NULL, 0, 1) AS escogido, a.codigoUsuario, IF(IFNULL((SELECT COUNT(a1.id) FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.idEnviado), 0 ) >= (SELECT COUNT(a1.id) FROM comite_delegados AS a1 WHERE a1.idComite = a.idComite AND a1.participa = 'A' GROUP BY a1.idComite), (SELECT IF(UPPER(a1.estado)='CALIFICAR','CALIFICADO','NEGADO') FROM comite_proyectos_calificadores AS a1 WHERE a1.idEnviado = a.id AND a1.idComite = a.idComite AND a1.enviado IS NOT NULL GROUP BY a1.estado ORDER BY COUNT(a1.estado) DESC LIMIT 1), 'PENDIENTE') AS estado,IF((a.idUsuarioModificacion = 'Comite' OR a.idUsuarioModificacion = 'Comite Priorizado' OR a.idUsuarioModificacion = 'Comite Observado') AND a.estadoModificacion!='TERMINADA','MODIFICACIÓN',IF(a.escogidoComiteCertificacion IS NOT NULL,'CERTIFICACIÓN','CALIFICACIÓN')) AS estadoAnalisis FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario = b.codigo WHERE a.escogidoComite IS NOT NULL AND (a.estadoCalificacion = 'Comite' OR a.estadoCalificacion = 'Comite Priorizado' OR a.estadoCalificacion = 'Comite Observado') AND a.idComite = '$idComite';");

      }


    }


    public function obtener__proyectos__recomendados(){

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo,UPPER(b.nombre) AS nombre,IF(a.escogidoComite IS NULL,0,1) AS escogido,a.codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b  ON a.codigoUsuario=b.codigo WHERE a.escogidoComite IS NOT NULL AND  a.estadoCalificacion='Comite' OR a.estadoCalificacion='Comite Priorizado' OR a.estadoCalificacion='Comite Observado';");

    }

    public function obtenerProyectos__comite(){

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigo,b.nombre,IF(a.escogidoComite IS NULL,0,1) AS escogido,a.codigoUsuario FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b  ON a.codigoUsuario=b.codigo WHERE a.escogidoComite IS NULL AND  a.estadoCalificacion='Comite' OR a.estadoCalificacion='Comite Priorizado' OR a.estadoCalificacion='Comite Observado';");

    }

    public function id__comite__obtenido(){

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM comite WHERE activo='A';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 0;
      }else{
        return $idBd;
      }

    }

    public function existencia__comite($idComite){

      $consulta=$this->constructor->select__general__incentivo("SELECT id FROM comite WHERE id='$idComite';");
      foreach ($consulta as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 0;
      }else{
        return $idBd;
      }

    }

    public function estadoComite($idComite){

      $consulta=$this->constructor->select__general__incentivo("SELECT estado FROM comite WHERE id='$idComite';");
      foreach ($consulta as $valor) {
        $estadoBd=$valor["estado"];
      }

      return $estadoBd;

    }

    public function informacionProyecto__datosGenerales() {

        //$consulta=$this->constructor->select__general__incentivo("SELECT COUNT(id) AS cuantos FROM comite WHERE YEAR(fecha)='".$this->anio."' AND activo='I';");

        $consulta=$this->constructor->select__general__incentivo("SELECT numeroComite FROM comite WHERE YEAR(fecha)='".$this->anio."' ORDER BY id DESC LIMIT 1;");

        foreach ($consulta as $valor) {
            $numeroComite=$valor["numeroComite"];
        }

        if(empty($numeroComite)){
          $cuantosBd=0;
        }else{
          $cuantosBd=intval($numeroComite) + 1;
        }

        return $cuantosBd;

    }


    public function enviar__convocatoria($post){

      $idCredencial=$post["idCredencial"];
      $fecha=$post["fecha"];
      $hora=$post["hora"];
      $idComite=$post["idComite"];
      $asunto=$post["asunto"];
      $ordenDia=$post["ordenDia"];


      $comiteExiste=$this->existencia__comite($idComite);

      if (intval($comiteExiste)!==0) {

        $this->constructor->actualiza__general__incentivo("UPDATE comite_convocatoria SET fechaConvocatoria='$fecha',horaConvocatoria='$hora',asunto='$asunto', ordenDia='$ordenDia'  WHERE idComite='$comiteExiste';"); 

      }else if(!empty($idCredencial) && !empty($idComite)){


        $numeroDeSesion=$this->informacionProyecto__datosGenerales();

        $numeroFormateado = ($numeroDeSesion >= 1 && $numeroDeSesion <= 10) ? sprintf('%03d', $numeroDeSesion) : (($numeroDeSesion >= 11 && $numeroDeSesion <= 99) ? sprintf('%02d', $numeroDeSesion) : (string) $numeroDeSesion);


        $this->constructor->actualiza__general__incentivo("DELETE FROM comite WHERE activo='A';");

        $this->constructor->inserta__general__incentivo("comite", ['idCredencial', 'fecha', 'hora','estado','activo','numeroComite'], array(
          ':idCredencial' =>$idCredencial,
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':estado' =>'creado',
          ':activo' =>'A',
          ':numeroComite' => $numeroFormateado."-".$this->anio,
        ));

        $consulta__maximoComite= $this->constructor->select__general__incentivo("SELECT MAX(id) AS id FROM comite;");
        foreach ($consulta__maximoComite as $value) {
          $maximoBd=$value["id"];
        }

        $valorMaximoS=$maximoBd;

        $this->constructor->actualiza__general__incentivo("DELETE FROM comite_convocatoria WHERE idComite IS NULL;");
        $this->constructor->inserta__general__incentivo("comite_convocatoria", ['idCredencial', 'fechaConvocatoria', 'horaConvocatoria','fecha','hora','idComite','asunto','ordenDia'], array(
          ':idCredencial' =>$idCredencial,
          ':fechaConvocatoria' => $fecha,
          ':horaConvocatoria' => $hora,
          ':fecha' =>$this->fecha,
          ':hora' =>$this->hora,
          ':idComite' => $maximoBd,
          ':asunto' => $asunto,
          ':ordenDia' => $ordenDia,
        ));

        $consulta__personal= $this->constructor->select__general__incentivo("SELECT idUsuario,fecha,hora,delegado,id_PuestoInstitucional,idCredencial FROM comite_delegados_auxiliar;");
        foreach ($consulta__personal as $valor) {

          $consulta__correo= $this->constructor->select__general__talento("SELECT email,nombre,apellido FROM th_usuario WHERE id_usuario='".$valor["idUsuario"]."';");
          foreach ($consulta__correo as $valorCorreo) {
            $emailBd=$valorCorreo["email"];
            $nombreBd=$valorCorreo["nombre"];
            $apellidoBd=$valorCorreo["apellido"];
          }


          $this->constructor->inserta__general__incentivo("comite_delegados", ['idUsuario', 'fecha', 'hora','delegado','id_PuestoInstitucional','idCredencial','idComite'], array(
            ':idUsuario' =>$valor["idUsuario"],
            ':fecha' =>$this->fecha,
            ':hora' =>$this->hora,
            ':delegado' =>$valor["delegado"],
            ':id_PuestoInstitucional' =>$valor["id_PuestoInstitucional"],
            ':idCredencial' =>$idCredencial,
            ':idComite' =>$valorMaximoS,          
          ));

          $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Ministerio del Deporte,</div><br><div style="font-size:10px;">Estimado '.$nombreBd.' '.$apellidoBd.' ha sido convocado a la sesión del cómite de fecha '.$fecha.' y hora '.$hora.'.</div></body></html>';

          $this->constructor->enviarCorreo([$email1Bd],$bodyMensaje);


        }

        $this->constructor->actualiza__general__incentivo("DELETE FROM comite_delegados_auxiliar;");



      }

      return 1;

    }


    public function comite__informacion__convocatoria($post){

      $idComite=$post["idComite"];
      return $this->constructor->select__general__incentivo("SELECT fechaConvocatoria,horaConvocatoria,asunto,ordenDia FROM comite_convocatoria WHERE idComite='$idComite' OR idComite IS NULL;");

    }



    public function comite__informacion__convocatoria__pdf($post) {

      $idComite=$post["idComite"];
 
      $contenido=$this->convocatoriaPdf->convocatoria__existente($idComite);
      $pdfResult = $this->constructor__basePdf->generatePdf__comite($contenido);

      return $pdfResult;
    } 

    public function generar__convocatoria__textual($post) {

      $idCredencial=$post["idCredencial"];
      $fecha=$post["fecha"];
      $hora=$post["hora"];
      $idComite=$post["idComite"];
      $asunto=$post["asunto"];
      $ordenDia=$post["ordenDia"];

      $contenido=$this->convocatoriaPdf->convocatoria($fecha,$hora,$asunto,$ordenDia);

      return $contenido;

    } 

    public function generar__convocatoria($post) {

      $idCredencial=$post["idCredencial"];
      $fecha=$post["fecha"];
      $hora=$post["hora"];
      $idComite=$post["idComite"];
      $asunto=$post["asunto"];
      $ordenDia=$post["ordenDia"];

      $contenido=$this->convocatoriaPdf->convocatoria($fecha,$hora,$asunto,$ordenDia);
      $pdfResult = $this->constructor__basePdf->generatePdf__comite($contenido);

      $comiteExiste=$this->existencia__comite($idComite);


      if (intval($comiteExiste)===0) {

        $this->constructor->actualiza__general__incentivo("DELETE FROM comite_convocatoria WHERE idComite IS NULL;");
        $this->constructor->inserta__general__incentivo("comite_convocatoria", ['idCredencial', 'fechaConvocatoria', 'horaConvocatoria','fecha','hora','asunto','ordenDia'], array(
          ':idCredencial' =>$idCredencial,
          ':fechaConvocatoria' => $fecha,
          ':horaConvocatoria' => $hora,
          ':fecha' =>$this->fecha,
          ':hora' =>$this->hora,
          ':asunto' => $asunto,
          ':ordenDia' => $ordenDia,
        ));

      }

      return $pdfResult;

    } 

    public function eliminar__personal__comite__participantes($post) {

      $id=$post["id"];
      $idComite=$post["idComite"];

      return $this->constructor->actualiza__general__incentivo("DELETE FROM comite_delegados WHERE id='$id' AND idComite='$idComite';");

    } 


    public function eliminar__personal__comite($post) {

      $id=$post["id"];
      $idComite=$post["idComite"];

      if (empty($idComite) || $idComite==="null") {
       return $this->constructor->actualiza__general__incentivo("DELETE FROM comite_delegados_auxiliar WHERE id='$id';");
      }else{
        return $this->constructor->actualiza__general__incentivo("DELETE FROM comite_delegados WHERE id='$id' AND idComite='$idComite';");
      }


    } 



    public function personal__comite__editado__participantes($post) {

      $idComite=$post["idComite"];

      $consulta=$this->constructor->select__general__incentivo("SELECT id,idUsuario,delegado,IF(sesion IS NULL,0,1) AS estados FROM comite_delegados WHERE idComite='$idComite' AND participante IS NOT NULL;");


      $array=array();

      foreach ($consulta as $valor) {
       
          $consultaUsuario=$this->constructor->select__general__talento("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='".$valor["idUsuario"]."';");

          foreach ($consultaUsuario as $valor2) {

              array_push($array,$valor2["nombreCompleto"]."__".$valor2["descripcionPuestoInstitucional"]."__".$valor["id"]."__".$valor["estados"]);

          }

      }

      return $array;

    } 


    public function personal__comite__editado($post) {

      $idComite=$post["idComite"];


      if ($idComite!=="null" && !is_null($idComite) && !empty($idComite)) {
        $consulta=$this->constructor->select__general__incentivo("SELECT id,idUsuario,delegado,IF(sesion IS NULL,0,1) AS estados,IF(participa IS NULL,0,1) AS participa FROM comite_delegados WHERE idComite='$idComite' AND participante IS NULL;");
      }else{
        $consulta=$this->constructor->select__general__incentivo("SELECT id,idUsuario,delegado FROM comite_delegados_auxiliar;");
      }

      $array=array();

      


      foreach ($consulta as $valor) {
       
          $consultaUsuario=$this->constructor->select__general__talento("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.id_usuario='".$valor["idUsuario"]."';");

          foreach ($consultaUsuario as $valor2) {

            if ($valor["delegado"]==="A") {
              array_push($array,$valor2["nombreCompleto"]."__DELEGADO DE LA MÁXIMA AUTORIDAD"."__".$valor["id"]."__".$valor["estados"]."__".$valor["participa"]);
            }else{
              array_push($array,$valor2["nombreCompleto"]."__".$valor2["descripcionPuestoInstitucional"]."__".$valor["id"]."__".$valor["estados"]."__".$valor["participa"]);
            }

          }

      }

      return $array;

    } 


    public function agregarPersonal__comite__sesion__participantes($post) {

      $id_PuestoInstitucional=$post["id_PuestoInstitucional"];
      $idUsuario=$post["idUsuario"];
      $idCredencial=$post["idCredencial"];
      $idComite=$post["idComite"];

      return $this->constructor->inserta__general__incentivo("comite_delegados", ['idUsuario','fecha','hora','id_PuestoInstitucional','idCredencial','idComite','participante'], array(
        ':idUsuario' =>$idUsuario,
        ':fecha'=>$this->fecha,
        ':hora'=>$this->hora,
        ':id_PuestoInstitucional'=>$id_PuestoInstitucional,
        ':idCredencial'=>$idCredencial,
        ':idComite'=>$idComite,
        ':participante'=>'A',
      ));

      return 1;

    } 


    public function agregarPersonal__comite__sesion($post) {

      $puestoInstitucional=$post["puestoInstitucional"];
      $idUsuario=$post["idUsuario"];
      $idCredencial=$post["idCredencial"];
      $idComite=$post["idComite"];

      if ($idComite==="null" || empty($idComite)) {
        $tabla="comite_delegados_auxiliar";
      }else{
        $tabla="comite_delegados";
      }

      if (intval($puestoInstitucional)===9999999){
    
        return $this->constructor->inserta__general__incentivo("$tabla", ['idUsuario','fecha','hora','delegado','id_PuestoInstitucional','idCredencial'], array(
            ':idUsuario' =>$idUsuario,
            ':fecha'=>$this->fecha,
            ':hora'=>$this->hora,
            ':delegado'=>'A',
            ':id_PuestoInstitucional'=>$puestoInstitucional,
            ':idCredencial'=>$idCredencial
        ));

      }else{

        return $this->constructor->inserta__general__incentivo("$tabla", ['idUsuario','fecha','hora','id_PuestoInstitucional','idCredencial'], array(
            ':idUsuario' =>$idUsuario,
            ':fecha'=>$this->fecha,
            ':hora'=>$this->hora,
            ':id_PuestoInstitucional'=>$puestoInstitucional,
            ':idCredencial'=>$idCredencial
        ));

      }

      return $idCredencial;

    } 


    public function funcionarios__ministerio($post) {

      return $this->constructor->select__general__talento("SELECT a.id_usuario,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.descripcionFisicamenteEstructura, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionFisicamenteEstructura, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE a.estadoUsuario='A'  ORDER BY a.apellido ASC;");

    } 


    public function delegadoMaximaAutoridad($post) {

      $obtener__delegado=$this->constructor->select__general__incentivo("SELECT id FROM comite_delegados_auxiliar WHERE delegado IS NOT NULL;");

      foreach ($obtener__delegado as $valor) {
        $idBd=$valor["id"];
      }

      if (empty($idBd)) {
        return 0;
      }else{
        return 1;
      }

    } 


    public function personalComite($post) {

      $array=array();

      $idComite=$post["idComite"];

      if($idComite!=="null" && !empty($idComite) && $idComite!==null){
        $tabla="comite_delegados";
      }else{
        $tabla="comite_delegados_auxiliar";
      }
      $consultaPersonalIngresado=$this->constructor->select__general__incentivo("SELECT id_PuestoInstitucional FROM $tabla;");


      foreach ($consultaPersonalIngresado as $valor) { 
          if (!empty($valor["id_PuestoInstitucional"])) {
              array_push($array, $valor["id_PuestoInstitucional"]);
          }
      }

      $puestosEscogidos = implode(', ', $array);


      if (count($array)>0) {

        return $this->constructor->select__general__talento("SELECT a.id_usuario,d.id_PuestoInstitucional,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE (b.id_rol='7' OR b.id_rol='4') AND a.estadoUsuario='A' AND a.zonal='1' AND d.id_PuestoInstitucional NOT IN($puestosEscogidos) GROUP BY d.id_PuestoInstitucional ORDER BY d.descripcionPuestoInstitucional ASC;");


      }else{

        return $this->constructor->select__general__talento("SELECT a.id_usuario,d.id_PuestoInstitucional,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionPuestoInstitucional FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura INNER JOIN th_puestoinstitucional AS d ON d.id_PuestoInstitucional=a.puestoInstitucional WHERE (b.id_rol='7' OR b.id_rol='4') AND a.estadoUsuario='A' AND a.zonal='1' GROUP BY d.id_PuestoInstitucional ORDER BY d.descripcionPuestoInstitucional ASC;");

      }


    } 


    public function obtener__informacion__analistas($post) {

      $idCredencial=$post["idCredencial"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      return $informacionUsuario;


    } 


    public function bandeja__recomendados__comite($post) {

      $idCredencial=$post["idCredencial"];
      $idRol=$post["idRol"];
      $fisicamenteEstructura=$post["fisicamenteEstructura"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);

      return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, FORMAT(SUM(d.total), 2) AS monto,a.fecha,IF(a.estadoCalificacion='Comite','RECOMENDADO',IF(a.estadoCalificacion='Comite priorizado','EN COMITÉ',IF(a.estadoCalificacion='Comite Observado','Observado',''))) AS estado  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' WHERE idUsuario=0 AND idUsuarioRecomiendaCalificacion=0 AND estadoCalificacion='Comite' GROUP BY c.codigo,d.codigo;");


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


    public function enviar__proyecto__comite__calificacion($post) {


      $idCredencial=$post["idCredencial"];
      $codigoProyecto=$post["codigoUsuario"];
      $enviarInfra=$post["enviarInfra"];
      $fisicamente=$post["fisicamente"];

      if(!empty($codigoProyecto)){

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
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='ENVIADO INFRA', idUsuarioRecomiendaCalificacion='0',idUsuario='0' WHERE id='$idBdEnviado';");
          $mensajeTipo='Enviado a infraestructura en etapa de calificación';
        }else if (intval($informacionUsuario[5])===15 && intval($banderaComponentes)===1 && intval($banderaSector)===0) {
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='Comite', idUsuarioRecomiendaCalificacion='0',idUsuario='0' WHERE id='$idBdEnviado';");
          $mensajeTipo='Asignado al cómite en etapa de calificación';
        }else if(intval($informacionUsuario[5])!==15 && intval($banderaComponentes)===1 && intval($banderaSector)===1){
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='ENVIADO INFRA', idUsuarioRecomiendaCalificacion='0',idUsuario='0' WHERE id='$idBdEnviado';");
          $mensajeTipo='Enviado a infraestructura en etapa de calificación';
        }else{
          $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='Comite', idUsuarioRecomiendaCalificacion='0',idUsuario='0' WHERE id='$idBdEnviado';");
          $mensajeTipo='Asignado al cómite en etapa de calificación';
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




        $informacionRecomendacion = $this->constructor->select__general__incentivo("SELECT idEnviado, idUsuario, idRol, texto, fecha, hora,recomendacion FROM $nombreTabla__recomendacion WHERE idEnviado='$idBdEnviado';");

        foreach ($informacionRecomendacion as $valor) {
          $idEnviadoBd=$valor["idEnviado"];
          $idUsuarioBd=$valor["idUsuario"];
          $idRolBd=$valor["idRol"];
          $textoBd=$valor["texto"];
          $fechaBd=$valor["fecha"];
          $horaBd=$valor["hora"];
          $recomendacionBd=$valor["recomendacion"];
        }

        $this->constructor->actualiza__general__incentivo("DELETE FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."';");


        return $this->constructor->inserta__general__incentivo("$nombreTabla__recomendacion", ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','idUsuario2','idRol2','fecha2','hora2','recomendacion'], array(
            ':idEnviado' =>$idBdEnviado,
            ':idUsuario'=>$idUsuarioBd,
            ':idRol'=>intval($idRolBd),
            ':texto'=>$textoBd,
            ':fecha'=>$fechaBd,
            ':hora'=>$horaBd,
            ':estado'=>'A',
            ':tipo'=>'CALIFICACION',
            ':idUsuario2'=>$idUsuario,
            ':idRol2'=>intval($informacionUsuario[4]),
            ':fecha2'=>$this->fecha,
            ':hora2'=>$this->hora,
            ':recomendacion'=>$recomendacionBd,
        ));

      }

    } 



    public function informacion__texto__devuelto($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);


      return  $this->constructor->select__general__incentivo("SELECT textoDevuelto FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado' ORDER BY id DESC LIMIT 1;");

    } 


    public function regresar__analista__recomendacion($post) {

      $codigoUsuario=$post["codigo"];
      $codigoProyecto=$post["codigoUsuario"];
      $personaReasignar=$post["personaReasignar"];
      $textoRegresar=$post["textoRegresar"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigoProyecto);

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='Devuelto', idUsuarioRecomiendaCalificacion=NULL,idUsuario='$personaReasignar' WHERE id='$idBdEnviado';");



      $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado';");

      foreach ($consultaEnviado as $valor) {

        $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
        $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

      }

      $informacionUsuario=$this->obtener__usuario($personaReasignar);

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora', 'textoDevuelto'], array(
        ':idFisicamenteActual' =>$idFisicamenteNuevoBd,
        ':idUsuarioActual' => $idUsuarioNuevoBd,
        ':idFisicamenteNuevo' => $informacionUsuario[5],
        ':idUsuarioNuevo' => $personaReasignar,
        ':idEnviado' => $idBdEnviado,
        ':tipo' =>'Devuelto  al analista etapa de calificación',
        ':fecha' => $this->fecha,
        ':hora' => $this->hora,
        ':textoDevuelto' => $textoRegresar,
      ));



      return 1;

    } 


    public function bandeja__recomendados__informacion__infraestructura($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigo=$post["codigo"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigo);

      return $this->constructor->select__general__incentivo("SELECT idEnviado,idUsuario,idRol,texto,fecha,hora,idUsuario2,idRol2,fecha2,hora2,estado,tipo,recomendacion FROM proyecto_enviado_recomendacion_infraestructura WHERE estado='A' AND tipo='CALIFICACION' AND idEnviado='$idBdEnviado';");


    } 

    public function bandeja__recomendados__informacion($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigo=$post["codigo"];

      $idBdEnviado=$this->funcion__obtener__enviado($codigo);

      return $this->constructor->select__general__incentivo("SELECT idEnviado,idUsuario,idRol,texto,fecha,hora,idUsuario2,idRol2,fecha2,hora2,estado,tipo,recomendacion FROM proyecto_enviado_recomendacion WHERE estado='A' AND tipo='CALIFICACION' AND idEnviado='$idBdEnviado';");


    } 


    public function bandeja__recomendados($post) {

      $idCredencial=$post["idCredencial"];
      $idRol=$post["idRol"];
      $fisicamenteEstructura=$post["fisicamenteEstructura"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);

      return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ') AS sector, FORMAT(SUM(d.total), 2) AS monto,a.fecha, (SELECT IF(a1.tipo='Redirigido','REASIGNADO',IF(a1.tipo='Reasignado','ASIGNADO',IF(a1.tipo='Observado calificacion','OBSERVADO',IF(a1.tipo='Rectificado calificacion','RECTIFICADO',' ')))) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' WHERE idUsuarioRecomiendaCalificacion='$idUsuario' GROUP BY c.codigo,d.codigo;");


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

         $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual,idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado';");

        foreach ($consultaEnviado as $valor) {

          $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
          $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];

        }

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='Recomendado', idUsuarioRecomiendaCalificacion='$informacionUsuario__superiorInmediato[6]',idUsuario='0' WHERE id='$idBdEnviado';");


        if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
          $nombreTabla__recomendacion="proyecto_enviado_recomendacion_infraestructura";
        }else{
          $nombreTabla__recomendacion="proyecto_enviado_recomendacion";
        }


        $this->constructor->actualiza__general__incentivo("DELETE FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."';");

        $this->constructor->inserta__general__incentivo($nombreTabla__recomendacion, ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','idUsuario2','idRol2','fecha2','hora2','recomendacion'], array(
          ':idEnviado' =>$this->funcion__obtener__enviado($codigoProyecto),
          ':idUsuario'=>$informacionUsuario[6],
          ':idRol'=>intval($informacionUsuario[4]),
          ':texto'=>$textoRecomendacion,
          ':fecha'=>$this->fecha,
          ':hora'=>$this->hora,
          ':estado'=>'A',
          ':tipo'=>'CALIFICACION',
          ':idUsuario2'=>NULL,
          ':idRol2'=>NULL,
          ':fecha2'=>NULL,
          ':hora2'=>NULL,
          ':recomendacion'=>$opcionRecomendacion,
        ));

        return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora', 'textoDevuelto'], array(
          ':idFisicamenteActual' =>$idFisicamenteNuevoBd,
          ':idUsuarioActual' => $idUsuarioNuevoBd,
          ':idFisicamenteNuevo' => $informacionUsuario__superiorInmediato[5],
          ':idUsuarioNuevo' => $informacionUsuario__superiorInmediato[6],
          ':idEnviado' => $idBdEnviado,
          ':tipo' =>'Recomendado analista en etapa calificación',
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':textoDevuelto' => $textoRecomendacion,
        ));


      }


    }


    public function insertar__informe($post){

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $opcionRecomendacion=$post["opcionRecomendacion"];
        $textoRecomendacion=$post["textoRecomendacion"];

        $idUsuario=$this->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->obtener__usuario($idUsuario);

        if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
          $nombreTabla__recomendacion="proyecto_enviado_recomendacion_infraestructura";
        }else{
          $nombreTabla__recomendacion="proyecto_enviado_recomendacion";
        }

        if (intval($informacionUsuario[4])===3) {

          $this->constructor->actualiza__general__incentivo("DELETE FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."';");

          return $this->constructor->inserta__general__incentivo("$nombreTabla__recomendacion", ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','recomendacion'], array(
            ':idEnviado' =>$this->funcion__obtener__enviado($codigoProyecto),
            ':idUsuario'=>$idUsuario,
            ':idRol'=>intval($informacionUsuario[4]),
            ':texto'=>$textoRecomendacion,
            ':fecha'=>$this->fecha,
            ':hora'=>$this->hora,
            ':estado'=>'A',
            ':tipo'=>'CALIFICACION',
            ':recomendacion'=>$opcionRecomendacion,
          ));


        }else if(intval($informacionUsuario[4])===2){

          $informacionRecomendacion = $this->constructor->select__general__incentivo("SELECT idEnviado, idUsuario, idRol, texto, fecha, hora FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."';");

          foreach ($informacionRecomendacion as $valor) {
            $idEnviadoBd=$valor["idEnviado"];
            $idUsuarioBd=$valor["idUsuario"];
            $idRolBd=$valor["idRol"];
            $textoBd=$valor["texto"];
            $fechaBd=$valor["fecha"];
            $horaBd=$valor["hora"];
          }

          $this->constructor->actualiza__general__incentivo("DELETE FROM $nombreTabla__recomendacion WHERE idEnviado='".$this->funcion__obtener__enviado($codigoProyecto)."';");


          return $this->constructor->inserta__general__incentivo($nombreTabla__recomendacion, ['idEnviado','idUsuario','idRol','texto','fecha','hora', 'estado', 'tipo','idUsuario2','idRol2','fecha2','hora2','recomendacion'], array(
            ':idEnviado' =>$this->funcion__obtener__enviado($codigoProyecto),
            ':idUsuario'=>$idUsuarioBd,
            ':idRol'=>intval($idRolBd),
            ':texto'=>$textoRecomendacion,
            ':fecha'=>$fechaBd,
            ':hora'=>$horaBd,
            ':estado'=>'A',
            ':tipo'=>'CALIFICACION',
            ':idUsuario2'=>$idUsuario,
            ':idRol2'=>intval($informacionUsuario[4]),
            ':fecha2'=>$this->fecha,
            ':hora2'=>$this->hora,
            ':recomendacion'=>$opcionRecomendacion,
          ));


        }

    }


    public function estado__calificacion__general__modificacion__llamado($codigoUsuario) {

      $consulta=$this->constructor->select__general__incentivo("SELECT estadoCalificacion FROM proyecto_enviado WHERE codigoUsuario='$codigoUsuario';");

      foreach ($consulta as $valor) {
        $estadoCalificacion=$valor["estadoCalificacion"];
      }

      return $estadoCalificacion;

    } 

    public function informacionDeRecomendacion($codigoUsuario,$codigoProyecto,$tabla) {

      $estadoClificacion=$this->estado__calificacion__general__modificacion__llamado($codigoUsuario);

      if($estadoCalificacion==="CALIFICADO"){
        return $this->constructor->select__general__incentivo("SELECT a.idEnviado,a.idUsuario,a.idRol,a.texto,a.idUsuario2,a.idRol2,a.recomendacion FROM $tabla AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id WHERE b.codigo='$codigoProyecto' AND b.codigoUsuario='$codigoUsuario' AND a.estado='A' AND a.tipo='MODIFICACION' LIMIT 1;");
      }else{
        return $this->constructor->select__general__incentivo("SELECT a.idEnviado,a.idUsuario,a.idRol,a.texto,a.idUsuario2,a.idRol2,a.recomendacion FROM $tabla AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id WHERE b.codigo='$codigoProyecto' AND b.codigoUsuario='$codigoUsuario' AND a.estado='A' AND a.tipo='CALIFICACION' LIMIT 1;");
      }

    } 


    public function generar__informe__comite($post) {

        $codigoUsuario=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $nomenclatura=$post["nomenclatura"];

        if ($nomenclatura==="tecnico" ) {
         $tabla="proyecto_enviado_recomendacion";
        }else{
          $tabla="proyecto_enviado_recomendacion_infraestructura";
        }


        foreach ($this->informacionDeRecomendacion($codigoUsuario,$codigoProyecto,$tabla) as $value) {
          $idUsuarioBd=$value["idUsuario"];
          $idUsuario2Bd=$value["idUsuario2"];
        }

        $informacionUsuario=$this->obtener__usuario($idUsuarioBd);
        $informacionUsuario__superiorInmediato=$this->obtener__usuario($informacionUsuario[3]);
        $informacionUsuario__superiorInmediatoFinal=$this->obtener__usuario($informacionUsuario__superiorInmediato[3]);

        $contenido=$this->informePdf->portada__informe($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->datos__generales($codigoUsuario);
        $contenido.=$this->informePdf->requisitos($codigoUsuario);
        $contenido.=$this->informePdf->resumenProyecto($codigoUsuario);
        $contenido.=$this->informePdf->presupuesto($codigoUsuario);
        $contenido.=$this->informePdf->analisisTecnico($codigoUsuario,$informacionUsuario,$opcionRecomendacion,$textoRecomendacion);
        $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);
        $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigoUsuario);

        return $pdfResult;

    } 

    public function generar__informe($post,$comite=false) {

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $opcionRecomendacion=$post["opcionRecomendacion"];
        $textoRecomendacion=$post["textoRecomendacion"];

        $idUsuario=$this->obtener__id__usuario($idCredencial);
        $informacionUsuario=$this->obtener__usuario($idUsuario);
        $informacionUsuario__superiorInmediato=$this->obtener__usuario($informacionUsuario[3]);
        $informacionUsuario__superiorInmediatoFinal=$this->obtener__usuario($informacionUsuario__superiorInmediato[3]);

        $contenido=$this->informePdf->portada__informe($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
        $contenido.=$this->informePdf->datos__generales($codigo);
        $contenido.=$this->informePdf->requisitos($codigo);
        $contenido.=$this->informePdf->resumenProyecto($codigo);
        $contenido.=$this->informePdf->presupuesto($codigo);
        $contenido.=$this->informePdf->analisisTecnico($codigo,$informacionUsuario,$opcionRecomendacion,$textoRecomendacion);

        if ($comite===false && intval($informacionUsuario[4])===3) {
            $informacionUsuario__superiorInmediato=[];
        }

        $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);


        $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

        return $pdfResult;

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

    public function obtener__id__usuario($idCredencial) {

      $consultaUsuario = $this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($consultaUsuario as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $idUsuarioBd;

    }


    public function baja__proyecto($post) {

      $codigo=$post["codigo"];
      $codigoProyecto=$post["codigoProyecto"];
      $texto=$post["texto"];

      if(!empty($codigo)){

        $consultaId = $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigoProyecto' AND codigoUsuario='$codigo';");
        foreach ($consultaId as $valor) {
          $idBdEnviado=$valor["id"];
        }

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='baja' WHERE codigo='$codigoProyecto' AND codigoUsuario='$codigo';");

        $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual, idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado' ORDER BY id DESC LIMIT 1;");
        foreach ($consultaEnviado as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora'], array(
              ':idFisicamenteActual' =>$valor["idFisicamenteNuevo"],
              ':idUsuarioActual' => $valor["idUsuarioNuevo"],
              ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
              ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
              ':idEnviado' => $idBdEnviado,
              ':tipo' =>'PROYECTO DADO DE BAJA POR EL PROPONENTE',
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
          ));

        }

        $this->constructor->inserta__general__incentivo("proyecto_enviado_baja", ['idEnviado', 'fecha', 'hora','texto'], array(
          ':idEnviado' =>$idBdEnviado,
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
          ':texto' =>$texto,
        ));

        return 1;

      }


    } 

    public function obtener__observacion__descripcion($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_descripcion WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    } 

    public function obtener__observacion__requisitos($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_requisitos WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    } 

    public function obtener__observacion__sector($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_sector WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    } 

    public function obtener__observacion__beneficiarios($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_beneficiarios WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    }     


    public function obtener__observacion__presupuesto($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_componentes WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    }     


    public function obtener__observacion__cronograma($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_cronogramaactividades WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    }     


    public function obtener__observacion__resultado($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_resultadometas WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    }     


    public function obtener__observacion__seguimiento($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      return $this->constructor->select__general__incentivo("SELECT textoObservacion FROM proyecto_observacion_seguimiento WHERE calificacion='noValidar' AND codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario' AND estado='A';");

    }     

    public function actualizar__proyecto__enviar__observar($post){

        $codigo=$post["codigo"];
        $codigoProyecto=$post["codigoProyecto"];
        $idCredencial=$post["idCredencial"];
        $estado=$post["estado"];

        if(!empty($codigo)){

          $bandera=false;

          if ($bandera===false) {
          
              if($estado==="observado"){
                  $estadoCreador="rectificado";
                  $estadoCreador__2="Rectificado en etapa de calificación";
              }else{
                  $estadoCreador="modificado";
                  $estadoCreador__2="Modificación asignada por el organismo deportivo";
              }

              $consultaEnviado = $this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
              foreach ($consultaEnviado as $valor) {
                  $idBdEnviado=$valor["id"];
              }


              $this->constructor->inserta__general__incentivo("proyecto_enviado_versiones", ['idEnviado', 'fecha', 'hora', 'tipo'], array(
                  ':idEnviado' =>$idBdEnviado,
                  ':fecha' => $this->fecha,
                  ':hora' => $this->hora,
                  ':tipo' => $estadoCreador,
              ));


              $consultaEnviado = $this->constructor->select__general__incentivo("SELECT idFisicamenteActual, idUsuarioActual, idFisicamenteNuevo, idUsuarioNuevo, idEnviado, tipo, fecha, hora FROM proyecto_enviado_antecedente WHERE idEnviado='$idBdEnviado' ORDER BY id DESC LIMIT 1;");
              foreach ($consultaEnviado as $valor) {

                  $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora'], array(
                      ':idFisicamenteActual' =>$valor["idFisicamenteNuevo"],
                      ':idUsuarioActual' => $valor["idUsuarioNuevo"],
                      ':idFisicamenteNuevo' => $valor["idFisicamenteNuevo"],
                      ':idUsuarioNuevo' => $valor["idUsuarioNuevo"],
                      ':idEnviado' => $valor["idEnviado"],
                      ':tipo' =>$estadoCreador__2,
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                  ));

              }

              $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='rectificado' WHERE codigoUsuario='$codigo';");

              $bandera=true;

          }


          return 1;

        }

    }

    public function validarSeguimiento__respaldo($codigo){
      return 1;
    }

    public function guardarPronosticos__respaldo($codigo,$estadoCalificacion) {

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_pronostico SET estado='I' WHERE codigo='$codigo';");

      $cronogramaRespaldar = $this->constructor->select__general__incentivo("SELECT deportistaOrganismo,disciplina,categoriaEdad,eventoParticipacion,pronosticoUbicacion,codigo,fecha,hora,tipoIngreso FROM proyecto_pronostico WHERE codigo='$codigo';");

      foreach ($cronogramaRespaldar as $valor) {
      
          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_pronostico", ['deportistaOrganismo', 'disciplina', 'categoriaEdad', 'eventoParticipacion','pronosticoUbicacion','codigo','fecha','hora','tipoIngreso','estado'], array(
            ':deportistaOrganismo' => $valor["deportistaOrganismo"],
            ':disciplina' => $valor["disciplina"],
            ':categoriaEdad' => $valor["categoriaEdad"],
            ':eventoParticipacion' => $valor["eventoParticipacion"],
            ':pronosticoUbicacion' => $valor["pronosticoUbicacion"],
            ':codigo' => $valor["codigo"],
            ':fecha' => $valor["fecha"],
            ':hora' => $valor["hora"],
            ':tipoIngreso' => $estadoCalificacion,
            ':estado' => 'A',
          ));


      }

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_seguimiento SET estado='I' WHERE codigo='$codigo';");

       $segumientoRespaldar = $this->constructor->select__general__incentivo("SELECT indicador,periodicidad,actividadSeguimiento,medioVerficiacion,observacion,codigo,fecha,hora,tipoIngreso FROM proyecto_seguimiento WHERE codigo='$codigo';");

       foreach ($segumientoRespaldar as $valor) {

          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_seguimiento", ['indicador', 'periodicidad', 'actividadSeguimiento', 'medioVerficiacion','observacion','codigo','fecha','hora','tipoIngreso','estado'], array(
           
            ':indicador' => $valor["indicador"],
            ':periodicidad' => $valor["periodicidad"],
            ':actividadSeguimiento' => $valor["actividadSeguimiento"],
            ':medioVerficiacion' => $valor["medioVerficiacion"],
            ':observacion' => $valor["observacion"],
            ':codigo' => $valor["codigo"],
            ':fecha' => $valor["fecha"],
            ':hora' => $valor["hora"],
            ':tipoIngreso' => $valor["tipoIngreso"],
            ':estado' => 'A',

          ));

       }

      $this->constructor->actualiza__general__incentivo("DELETE FROM  proyecto_seguimiento WHERE codigo='$codigo';");


        $metasBd=$this->constructor->select__general__incentivo("SELECT nombreIndicador,periodicidad FROM proyecto_resultados_metas WHERE codigo='$codigo';");


        foreach ($metasBd as $valor) {
                

            $this->constructor->inserta__general__incentivo("proyecto_seguimiento", ['indicador','periodicidad','codigo', 'fecha', 'hora', 'tipoIngreso'], array(
                ':indicador' => $valor["nombreIndicador"],
                ':periodicidad' => $valor["periodicidad"],
                ':codigo' => $codigo,
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':tipoIngreso' => $estadoCalificacion,
            ));


        }


      return 1;

    } 


    public function guardarCronogramaDeActividades__respaldo($codigo,$estadoCalificacion) {

      return 1;

    } 


    public function guardarEstadoComponentes__respaldo($codigo,$idCredencial,$estadoCalificacion) {

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

        /*==========================================
        =             Generar respaldos            =
        ==========================================*/
        
        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_cronograma_actividades SET estado='I' WHERE codigo='$codigo';");

        $cronogramaRespaldar = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,actividades,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,tipo,provincia,canton,parroquia,pais,ciudad,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,orden,creado,tipoIngreso FROM proyecto_cronograma_actividades WHERE codigo='$codigo';");

        foreach ($cronogramaRespaldar as $valor) {
         
          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'actividades', 'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','tipo','provincia','canton','parroquia','pais','ciudad','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','orden','creado','tipoIngreso','estado'], array(
            ':idComponentes' => $valor["idComponentes"],
            ':idNivel1' => $valor["idNivel1"],
            ':actividades' => $valor["actividades"],
            ':enero' => $valor["enero"],
            ':febrero' => $valor["febrero"],
            ':marzo' => $valor["marzo"],
            ':abril' => $valor["abril"],
            ':mayo' => $valor["mayo"],
            ':junio' => $valor["junio"],
            ':julio' => $valor["julio"],
            ':agosto' => $valor["agosto"],
            ':septiembre' => $valor["septiembre"],
            ':octubre' => $valor["octubre"],
            ':noviembre' => $valor["noviembre"],
            ':diciembre' => $valor["diciembre"],
            ':tipo' => $valor["tipo"],
            ':provincia' => $valor["provincia"],
            ':canton' => $valor["canton"],
            ':parroquia' => $valor["parroquia"],
            ':pais' => $valor["pais"],
            ':ciudad' => $valor["ciudad"],
            ':fecha' => $valor["fecha"],
            ':hora' => $valor["hora"],
            ':activo' => $valor["activo"],
            ':anio' => $valor["anio"],
            ':codigo' => $valor["codigo"],
            ':idCredencial' => $valor["idCredencial"],
            ':nivel' => $valor["nivel"],
            ':sector' => $valor["sector"],
            ':orden' => $valor["orden"],
            ':creado' => $valor["creado"],
            ':tipoIngreso' => $valor["tipoIngreso"],
            ':estado' => 'A',
          ));


        }
        
        /*=====  End of  Generar respaldos  ======*/
        

        /*=========================================================
        =            Generar Cronograma de actividades            =
        =========================================================*/
        
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_cronograma_actividades WHERE codigo='$codigoFinal';");
        
        $sumadorCronograma=0;

       for($i=0; $i<count($arrayAnios);$i++){

            $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='componente' AND z.idComponentes!='6' GROUP BY z.idComponentes;");

            foreach ($componetesV as $valor) {

                $sumadorCronograma++;

                $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso'], array(
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
                    ':tipoIngreso' => $estadoCalificacion
                ));


              $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$sumadorCronograma,$estadoCalificacion);


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

                    $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'priorizado',
                        ':orden' => $sumadorPriorisados,
                        ':tipoIngreso' => $estadoCalificacion
                    ));


                    $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$sumadorPriorisados,$estadoCalificacion);


                }


            }

            $sumadorFemenino=0;


            for($i=0; $i<count($arrayAnios);$i++){

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre,z.id FROM proyecto_presupuesto AS z INNER JOIN proyecto_componente_usuario AS a ON z.idComponentes=a.idComponentes INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE z.codigo='$codigoFinal' AND z.total>0 AND z.idNivel1 IS NOT NULL AND z.sector='femenino' AND z.idComponentes!='6' GROUP BY z.idComponentes;");


                foreach ($componetesV as $valor) {

                    $sumadorFemenino++;

                    $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'femenino',
                        ':orden' => $sumadorFemenino,
                        ':tipoIngreso' => $estadoCalificacion
                    ));


                    $this->insertarNivel__1__sin($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$sumadorFemenino,$estadoCalificacion);


                }


            }

        }
         
         
         /*=====  End of Ingresar cronograma de actividades  ======*/
         

        return 1;

    } 

    public function insertarNivel__1__sin($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente',$orden,$estadoCalificacion) {

        $array=array();

        $buscar__niveles=$this->constructor->select__general__incentivo("SELECT idNivel1 FROM proyecto_presupuesto WHERE idComponentes='$idComponentes' AND sector='$sector' AND total>0 AND idNivel1 IS NOT NULL AND idComponentes iS NOT NULL AND anio='$anio' AND codigo='$codigoFinal' GROUP BY idNivel1,idComponentes;");

        foreach ($buscar__niveles as $valor) {
            array_push($array, $valor["idNivel1"]);
        }

        $comparacion = implode(',', $array);

        foreach ($array as $valor) {
        
            $nivel__1 = $this->constructor->select__general__incentivo("SELECT a.idNivel1,b.id FROM componentes_nivel AS a INNER JOIN proyecto_presupuesto AS b ON a.idNivel1=b.idNivel1 WHERE a.idComponentes = '$idComponentes' AND a.idNivel1='$valor' AND a.estado='A' AND b.total>0 AND b.sector='$sector' AND a.idNivel1 IS NOT NULL AND a.idComponentes IS NOT NULL AND b.anio='$anio' AND b.codigo='$codigoFinal' GROUP BY a.idNivel1,a.idComponentes;");

            foreach ($nivel__1 as $valorNivel__1) {

                $this->constructor->inserta__general__incentivo("proyecto_cronograma_actividades", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','orden','tipoIngreso'], array(
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
                    ':tipoIngreso' => $estadoCalificacion
                ));

            }

        }

        return 1;

    }


    public function insertaBeneficiario__respaldo($post) {

        $idCredencial=$post["idCredencial"];
        $codigo=$post["codigo"];
        $beneficiariosArray=json_decode($post["beneficiarios"], true);
        $rangosEdadArray=json_decode($post["rangosEdad"], true);
        $generosArray=json_decode($post["generos"], true);
        $autoidentificacionesArray=json_decode($post["autoidentificaciones"], true);
        $tiposDiscapacidadArray=json_decode($post["tiposDiscapacidad"], true);
        $cantidadesArray=json_decode($post["cantidades"], true);
        $estado=$post["estado"];

        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_beneficiarios SET estado='I' WHERE codigo='$codigo';");

        $consultaSector=$this->constructor->select__general__incentivo("SELECT id,idBeneficiario,idRango,idGenero,idAutentificacion,cantidad,codigo,idCredencial,idDiscapacidad,tipoIngreso FROM proyecto_beneficiarios WHERE codigo='$codigo';");

        foreach ($consultaSector as $valor) {

          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_beneficiarios",['idBeneficiario','idRango','idGenero','idAutentificacion','cantidad','codigo','idCredencial','fecha','hora','idDiscapacidad','tipoIngreso','estado'],array(':idBeneficiario' => $valor["idBeneficiario"],':idRango' =>  $valor["idRango"],':idGenero' =>  $valor["idGenero"],':idAutentificacion' =>  $valor["idAutentificacion"],':cantidad' =>  $valor["cantidad"],':codigo' =>  $valor["codigo"],':idCredencial' =>  $valor["idCredencial"],':fecha' => $this->fecha,':hora' => $this->hora,':idDiscapacidad' =>  $valor["idDiscapacidad"],':tipoIngreso' =>  $valor["tipoIngreso"],':estado' =>  'A'));

        }

        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_beneficiarios WHERE codigo='$codigo';");

        foreach ($beneficiariosArray as $clave => $valor) {
            $this->constructor->inserta__general__incentivo("proyecto_beneficiarios",['idBeneficiario','idRango','idGenero','idAutentificacion','cantidad','codigo','idCredencial','fecha','hora','idDiscapacidad','tipoIngreso'],array(':idBeneficiario' => $valor,':idRango' =>  $rangosEdadArray[$clave],':idGenero' =>  $generosArray[$clave],':idAutentificacion' =>  $autoidentificacionesArray[$clave],':cantidad' =>  $cantidadesArray[$clave],':codigo' =>  $codigo,':idCredencial' =>  $idCredencial,':fecha' => $this->fecha,':hora' => $this->hora,':idDiscapacidad' =>  $tiposDiscapacidadArray[$clave],':tipoIngreso' =>  $estado)); 
        }


       return 1;

    }     


    public function insertaSector__respaldos($post) {

        $codigo=$post["codigo"];
        $idCredencial=$post["idCredencial"];
        $idSectorArray=json_decode($post["idSectorArray"], true);
        $ocultado=$post["ocultado"];
        $estado=$post["estado"];
        $chequed=$post["chequed"];


       if ($chequed==="true") {

          $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_sector SET estado='I' WHERE codigo='$codigo';");
         
          $consulta__sector=$this->constructor->select__general__incentivo("SELECT  idSector,codigo, idCredencial, fecha, hora,tipoIngreso FROM proyecto_sector WHERE codigo='$codigo';");

          foreach ($consulta__sector as $valor) {
          
            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_sector", ['idSector','codigo', 'idCredencial', 'fecha','hora','tipoIngreso','estado'], array(
              ':idSector' => $valor["idSector"],
              ':codigo' => $valor["codigo"],
              ':idCredencial' => $valor["idCredencial"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' =>'A',
   
            ));

          }

          $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_sector WHERE codigo='$codigo';");

          foreach ($idSectorArray as  $valor) {
          
            $this->constructor->inserta__general__incentivo("proyecto_sector", ['idSector','codigo', 'idCredencial', 'fecha','hora','tipoIngreso'], array(

              ':idSector' => $valor,
              ':codigo' => $codigo,
              ':idCredencial' => $idCredencial,
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':tipoIngreso' => $estado,
     
            ));

          }

       
        /*====================================================
        =            Generar componentes respaldo            =
        ====================================================*/

        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET estado='I' WHERE codigo='$codigoFinal';");
        
         $informacionRespaldoComponentes = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,tipoIngreso FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");


         foreach ($informacionRespaldoComponentes as $valor) {

            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'detalle', 'justificacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado'], array(
                ':idComponentes' => $valor["idComponentes"],
                ':idNivel1' => $valor["idNivel1"],
                ':detalle' => $valor["detalle"],
                ':justificacion' => $valor["justificacion"],
                ':enero' => $valor["enero"],
                ':febrero' => $valor["febrero"],
                ':marzo' => $valor["marzo"],
                ':abril' => $valor["abril"],
                ':mayo' => $valor["mayo"],
                ':junio' => $valor["junio"],
                ':julio' => $valor["julio"],
                ':agosto' => $valor["agosto"],
                ':septiembre' => $valor["septiembre"],
                ':octubre' => $valor["octubre"],
                ':noviembre' => $valor["noviembre"],
                ':diciembre' => $valor["diciembre"],
                ':total' => $valor["total"],
                ':fecha' => $valor["fecha"],
                ':hora' => $valor["hora"],
                ':activo' => $valor["activo"],
                ':anio' => $valor["anio"],
                ':codigo' => $valor["codigo"],
                ':idCredencial' => $valor["idCredencial"],
                ':nivel' => $valor["nivel"],
                ':sector' => $valor["sector"],
                ':tipoIngreso' => $valor["tipoIngreso"],
                ':estado' => 'A'
  
            ));

         }

        /*=====  End of Generar componentes respaldo  ======*/
        
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


                    $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'priorizado',
                        ':tipoIngreso' => $estado,
                    ));


                    $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estado);


                }


                if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                    $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                        ':idComponentes' => 6,
                        ':idNivel1' =>  $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'priorizado',
                        ':tipoIngreso' => $estado,
                    ));


                    $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estado);

                }


            }

            for($i=0; $i<count($arrayAnios);$i++){

                $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

                foreach ($componetesV as $valor) {


                    $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                        ':idComponentes' => $valor["idComponentes"],
                        ':idNivel1' => $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'femenino',
                        ':tipoIngreso' => $estado,
                    ));


                    $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estado);


                }


                if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                    $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                        ':idComponentes' => 6,
                        ':idNivel1' =>  $valorNivel__1["idNivel1"],
                        ':fecha' => $this->fecha,
                        ':hora' => $this->hora,
                        ':anio' => $arrayAnios[$i],
                        ':codigo' => $codigoFinal,
                        ':idCredencial' => $idCredencial,
                        ':nivel' => 0,
                        ':sector' => 'femenino',
                        ':tipoIngreso' => $estado,
                    ));


                    $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estado);

                }


            }


         }     


        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE (sector='priorizado' OR sector='femenino') AND codigo='$codigoFinal';");    
         
         /*=====  End of Ingresar componentes priorizados  ======*/


       }
        

        return 1;

    }

    public function guarda__documentos__habilitantes__respaldos($codigo,$estado) {

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_documentos_requisitos SET estado2='I' WHERE codigo='$codigo';");

       $consulta__registros=$this->constructor->select__general__incentivo("SELECT proyecto,curriculoDeportivo,certificadoTrayectoria,documentoLegalProponente,ruc,curriculoExperiencia,tituloPropiedad,memoriaTecnica,planosArquitectonicos,presupuestoDetalle,respaldoDigitales,estado,fecha,hora,codigo,tipoIngreso FROM proyecto_documentos_requisitos WHERE codigo='$codigo';");

       foreach ($consulta__registros as $valor) {
        
          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_documentos_requisitos", ['proyecto', 'curriculoDeportivo', 'certificadoTrayectoria', 'documentoLegalProponente','ruc','curriculoExperiencia','tituloPropiedad','memoriaTecnica','planosArquitectonicos','presupuestoDetalle','respaldoDigitales','estado','fecha','hora','codigo','tipoIngreso','estado2'], array(
            ':proyecto' => $valor["proyecto"],
            ':curriculoDeportivo' => $valor["curriculoDeportivo"],
            ':certificadoTrayectoria' => $valor["certificadoTrayectoria"],
            ':documentoLegalProponente' => $valor["documentoLegalProponente"],
            ':ruc' => $valor["ruc"],
            ':curriculoExperiencia' => $valor["curriculoExperiencia"],
            ':tituloPropiedad' => $valor["tituloPropiedad"],
            ':memoriaTecnica' => $valor["memoriaTecnica"],
            ':planosArquitectonicos' => $valor["planosArquitectonicos"],
            ':presupuestoDetalle' => $valor["presupuestoDetalle"],
            ':respaldoDigitales' => $valor["respaldoDigitales"],
            ':estado' => $valor["estado"],
            ':fecha' => $valor["fecha"],
            ':hora' => $valor["hora"],
            ':codigo' => $valor["codigo"],
            ':tipoIngreso' => $estado,
            ':estado2' => 'A',
           ));

       }

       return 1;

    } 

    public function insertaInformacionGeneral__bandejas($post) {

      $objetivoGeneral=$post["objetivoGeneral"];
      $justificacionProyecto=$post["justificacionProyecto"];
      $nombreProyecto=$post["nombreProyecto"];
      $idCredencial=$post["idCredencial"];
      $codigo=$post["codigo"];

      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_descripcion SET objetivoGeneral='$objetivoGeneral', justificacionProyecto='$justificacionProyecto', nombre='$nombreProyecto' WHERE codigo='$codigo';");

      return 1;

    }


    public function insertaObjetivosEspecificos__bandejas($post) {

      $fechaInicio=$post["fechaInicio"];
      $fechaFin=$post["fechaFin"];
      $mensajePluri=$post["mensajePluri"];
      $idTramite=$post["idTramite"];
      $idCredencial=$post["idCredencial"];
      $codigoBd=$post["codigo"];
      $codigo=$post["codigo"];
      $codigoFinal=$post["codigo"];
      $diferenciaAnios=intval($post["diferenciaAnios"]);
      $arrayIdComponentes=json_decode($post["arrayIdComponentes"], true);


      $arrayQue=json_decode($post["arrayQue"], true);
      $arraycomoO=json_decode($post["arraycomoO"], true);
      $arrayparaO=json_decode($post["arrayparaO"], true);
      $arrayqueRespaldo=json_decode($post["arrayqueRespaldo"], true);
      $arraycomoRespaldo=json_decode($post["arraycomoRespaldo"], true);
      $arrayparaRespaldo=json_decode($post["arrayparaRespaldo"], true);
      $selectedIdsArray=json_decode($post["selectedIdsArray"], true);



      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_descripcion SET fechaInicio='$fechaInicio', fechaFin='$fechaFin', tipo='$mensajePluri', diferenciaAnios='".$diferenciaAnios."' WHERE codigo='$codigoBd';");


      /*=====================================
      =            Componentes 1            =
      =====================================*/
      
      
      $consultaRespaldo_componente=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes,que,como,paraQue,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora FROM proyecto_componente_usuario WHERE idCredencial='$idCredencial' AND codigo='$codigo';");

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_componente_usuario SET estado='I' WHERE codigo='$codigo';");

      foreach ($consultaRespaldo_componente as $valor) {


        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_componente_usuario",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso','estado'],array(':idComponentes' => $valor["idComponentes"],':que' => $valor["que"],':como' => $valor["como"],':paraQue' =>$valor["paraQue"],':fecha' => $valor["fecha"],':hora' => $valor["hora"],':idCredencial' => $valor["idCredencial"],':idProyecto' => $valor["idProyecto"],':codigo' => $valor["codigo"],':tipoIngreso' => $valor["tipoIngreso"],':estado' => 'A'));

        $idComponentesProyectoBd=$valor["idComponentesProyecto"];

      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_componente_usuario WHERE codigo='$codigo';");


      foreach ($arrayQue as $clave => $valor) {
          
          $this->constructor->inserta__general__incentivo("proyecto_componente_usuario",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso'],array(':idComponentes' => $arrayIdComponentes[$clave],':que' => $arrayQue[$clave],':como' => $arraycomoO[$clave],':paraQue' => $arrayparaO[$clave],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoRecuperadoBd,':codigo' => $codigo,':tipoIngreso' => $estadoCalificacion));

      }

      
      /*=====  End of Componentes 1  ======*/
      

      /*=====================================
      =            componentes 2            =
      =====================================*/
      
              
      $consultaRespaldo_componente=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes,que,como,paraQue,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora FROM proyecto_componente_usuario_2 WHERE idCredencial='$idCredencial' AND codigo='$codigo';");


      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_componente_usuario_2 SET estado='I' WHERE codigo='$codigo';");

      foreach ($consultaRespaldo_componente as $valor) {


        $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_componente_usuario_2",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso','estado'],array(':idComponentes' => $valor["idComponentes"],':que' => $valor["que"],':como' => $valor["como"],':paraQue' =>$valor["paraQue"],':fecha' => $valor["fecha"],':hora' => $valor["hora"],':idCredencial' => $valor["idCredencial"],':idProyecto' => $valor["idProyecto"],':codigo' => $valor["codigo"],':tipoIngreso' => $valor["tipoIngreso"],':estado' => 'A'));

        $idComponentesProyectoBd=$valor["idComponentesProyecto"];

      }


      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_componente_usuario_2 WHERE codigo='$codigo';");

      foreach ($arrayQue as $clave => $valor) {
          
          if(!empty($arraycomoRespaldo[$clave]) && !is_null($arraycomoRespaldo[$clave])){

            $this->constructor->inserta__general__incentivo("proyecto_componente_usuario_2",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso'],array(':idComponentes' => $arrayIdComponentes[$clave],':que' => $arrayqueRespaldo[$clave],':como' => $arraycomoRespaldo[$clave],':paraQue' => $arrayparaRespaldo[$clave],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoRecuperadoBd,':codigo' => $codigo,':tipoIngreso' => $estadoCalificacion));

          }

      }
      
      /*=====  End of componentes 2  ======*/

        
      /*==========================================
      =            Resultados y metas            =
      ==========================================*/

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_resultados_metas SET estado='I' WHERE codigo='$codigo';");

      $selectResultadosMetas = $this->constructor->select__general__incentivo("SELECT objetivoEspecifico,nombreIndicador,descripcion,metodoCalculo,metaFinal,periodicidad,medioVerificacion,codigo,fecha,hora,idComponentes,tipoIngreso FROM proyecto_resultados_metas WHERE codigo='$codigoFinal';");


      foreach ($selectResultadosMetas as $valor) {


          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_resultados_metas", ['objetivoEspecifico','nombreIndicador','descripcion','metodoCalculo','metaFinal','periodicidad','medioVerificacion','codigo','fecha','hora','idComponentes','tipoIngreso','estado'], array(
              ':objetivoEspecifico' => $valor["objetivoEspecifico"],
              ':nombreIndicador' => $valor["nombreIndicador"],
              ':descripcion' => $valor["descripcion"],
              ':metodoCalculo' => $valor["metodoCalculo"],
              ':metaFinal' => $valor["metaFinal"],
              ':periodicidad' => $valor["periodicidad"],
              ':medioVerificacion' => $valor["medioVerificacion"],
              ':codigo' => $valor["codigo"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':idComponentes' => $valor["idComponentes"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' => 'A'
          ));

      }
      
      
      $this->constructor->actualiza__general__incentivo("DELETE FROM  proyecto_resultados_metas WHERE codigo='$codigo';");

      $objetivosEspecificos = $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',que,como,paraQue) AS objetivoEspecifico,idComponentes FROM proyecto_componente_usuario WHERE codigo='$codigo';");


      foreach ($objetivosEspecificos as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_resultados_metas", ['objetivoEspecifico','fecha','hora','idComponentes','codigo'], array(
              ':objetivoEspecifico' => $valor["objetivoEspecifico"],
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':idComponentes' => $idComponentes,
              ':codigo' => $codigo,
          ));

      }

      if(count($arrayqueRespaldo)>0){

        $objetivosEspecificos__respaldos = $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',que,como,paraQue) AS objetivoEspecifico,idComponentes FROM proyecto_componente_usuario_2 WHERE codigo='$codigo';");
        foreach ($objetivosEspecificos__respaldos as $valor) {

            $this->constructor->inserta__general__incentivo("proyecto_resultados_metas", ['objetivoEspecifico','codigo','fecha','hora','idComponentes'], array(
                ':objetivoEspecifico' => $valor["objetivoEspecifico"],
                ':codigo' => $codigo,
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':idComponentes' => $idComponentes,
            ));

        }

      }
      
      /*=====  End of Resultados y metas  ======*/

      /*==================================================
      =            Obtener fecha plurianuales            =
      ==================================================*/

      $diferenciaAnios=intval($diferenciaAnios) + 1;
      
      $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio,YEAR(fechaInicio) AS anioInicio, YEAR(fechaFin) AS anioFin FROM proyecto_descripcion WHERE codigo='$codigoFinal' AND idCredencial='$idCredencial';");

      foreach ($consultaRespaldo__descripcion__dos as $valor) {
          $fechaInicioBd__dos=$valor["fechaInicio"];
          $anioInicioBd=$valor["anioInicio"];
          $anioFinBd=$valor["anioFin"];
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
      
        
      /*====================================================
      =            Generar componentes respaldo            =
      ====================================================*/

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET estado='I' WHERE codigo='$codigoFinal';");
      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto_footer SET estado='I' WHERE codigo='$codigoFinal';");
      
       $informacionRespaldoComponentes = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,tipoIngreso FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");


       foreach ($informacionRespaldoComponentes as $valor) {

          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'detalle', 'justificacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado'], array(
              ':idComponentes' => $valor["idComponentes"],
              ':idNivel1' => $valor["idNivel1"],
              ':detalle' => $valor["detalle"],
              ':justificacion' => $valor["justificacion"],
              ':enero' => $valor["enero"],
              ':febrero' => $valor["febrero"],
              ':marzo' => $valor["marzo"],
              ':abril' => $valor["abril"],
              ':mayo' => $valor["mayo"],
              ':junio' => $valor["junio"],
              ':julio' => $valor["julio"],
              ':agosto' => $valor["agosto"],
              ':septiembre' => $valor["septiembre"],
              ':octubre' => $valor["octubre"],
              ':noviembre' => $valor["noviembre"],
              ':diciembre' => $valor["diciembre"],
              ':total' => $valor["total"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':activo' => $valor["activo"],
              ':anio' => $valor["anio"],
              ':codigo' => $valor["codigo"],
              ':idCredencial' => $valor["idCredencial"],
              ':nivel' => $valor["nivel"],
              ':sector' => $valor["sector"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' => 'A'

          ));

       }

        $informacionRespaldoComponentesFooter = $this->constructor->select__general__incentivo("SELECT codigo,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,anio,sector,tipoIngreso FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


       foreach ($informacionRespaldoComponentesFooter as $valor) {
          
          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['codigo','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector','tipoIngreso','estado'], array(
              ':codigo' => $valor["codigo"],
              ':enero' => $valor["enero"],
              ':febrero' => $valor["febrero"],
              ':marzo' => $valor["marzo"],
              ':abril' => $valor["abril"],
              ':mayo' => $valor["mayo"],
              ':junio' => $valor["junio"],
              ':julio' => $valor["julio"],
              ':agosto' => $valor["agosto"],
              ':septiembre' => $valor["septiembre"],
              ':octubre' => $valor["octubre"],
              ':noviembre' => $valor["noviembre"],
              ':diciembre' => $valor["diciembre"],
              ':total' => $valor["total"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':anio' => $valor["anio"],
              ':sector' => $valor["sector"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' => 'A'
          ));

       }
      
      /*=====  End of Generar componentes respaldo  ======*/

      /*===========================================
      =            Generar componentes            =
      ===========================================*/
      
      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");
      
      /*=====  End of Generar componentes  ======*/
      
      /*===========================================
      =            Generar componentes            =
      ===========================================*/
      
      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");

      for($i=0; $i<$diferenciaAnios;$i++){

        $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

        foreach ($componetesV as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
            ':idComponentes' => $valor["idComponentes"],
            ':idNivel1' => $valorNivel__1["idNivel1"],
            ':fecha' => $this->fecha,
            ':hora' => $this->hora,
            ':anio' => $arrayAnios[$i],
            ':codigo' => $codigoFinal,
            ':idCredencial' => $idCredencial,
            ':nivel' => 0,
            ':sector' => 'componente',
            ':tipoIngreso' => $estadoCalificacion,
          ));

          $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion);


        }


        if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

          $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
            ':idComponentes' => 6,
            ':idNivel1' =>  $valorNivel__1["idNivel1"],
            ':fecha' => $this->fecha,
            ':hora' => $this->hora,
            ':anio' => $arrayAnios[$i],
            ':codigo' => $codigoFinal,
            ':idCredencial' => $idCredencial,
            ':nivel' => 0,
            ':sector' => 'componente',
            ':tipoIngreso' => $estadoCalificacion,
          ));

          $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion);

        }

      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


      /*=====  End of Generar componentes  ======*/

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


                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'priorizado',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estado);


              }


              if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => 6,
                      ':idNivel1' =>  $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'priorizado',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estado);

              }


          }

          for($i=0; $i<count($arrayAnios);$i++){

              $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

              foreach ($componetesV as $valor) {


                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'femenino',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estado);


              }


              if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => 6,
                      ':idNivel1' =>  $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'femenino',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estado);

              }


          }


       }     


      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE (sector='priorizado' OR sector='femenino') AND codigo='$codigoFinal';");    
       
      /*=====  End of Ingresar componentes priorizados  ======*/


        

      return 1;

    }

    public function insertaDescripcionFecha__bandejas($post) {

      $fechaInicio=$post["fechaInicio"];
      $fechaFin=$post["fechaFin"];
      $mensajePluri=$post["mensajePluri"];
      $idTramite=$post["idTramite"];
      $idCredencial=$post["idCredencial"];
      $codigoBd=$post["codigo"];
      $codigo=$post["codigo"];
      $codigoFinal=$post["codigo"];
      $diferenciaAnios=intval($post["diferenciaAnios"]);
      $arrayIdComponentes=json_decode($post["arrayIdComponentes"], true);


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_descripcion SET fechaInicio='$fechaInicio', fechaFin='$fechaFin', tipo='$mensajePluri', diferenciaAnios='".$diferenciaAnios."' WHERE codigo='$codigoBd';");

      /*==================================================
      =            Obtener fecha plurianuales            =
      ==================================================*/

      $diferenciaAnios=intval($diferenciaAnios) + 1;
      
      $consultaRespaldo__descripcion__dos=$this->constructor->select__general__incentivo("SELECT fechaInicio,YEAR(fechaInicio) AS anioInicio, YEAR(fechaFin) AS anioFin FROM proyecto_descripcion WHERE codigo='$codigoFinal' AND idCredencial='$idCredencial';");

      foreach ($consultaRespaldo__descripcion__dos as $valor) {
          $fechaInicioBd__dos=$valor["fechaInicio"];
          $anioInicioBd=$valor["anioInicio"];
          $anioFinBd=$valor["anioFin"];
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
      
        
      /*====================================================
      =            Generar componentes respaldo            =
      ====================================================*/

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET estado='I' WHERE codigo='$codigoFinal';");
      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto_footer SET estado='I' WHERE codigo='$codigoFinal';");
      
       $informacionRespaldoComponentes = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,tipoIngreso FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");


       foreach ($informacionRespaldoComponentes as $valor) {

          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'detalle', 'justificacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado'], array(
              ':idComponentes' => $valor["idComponentes"],
              ':idNivel1' => $valor["idNivel1"],
              ':detalle' => $valor["detalle"],
              ':justificacion' => $valor["justificacion"],
              ':enero' => $valor["enero"],
              ':febrero' => $valor["febrero"],
              ':marzo' => $valor["marzo"],
              ':abril' => $valor["abril"],
              ':mayo' => $valor["mayo"],
              ':junio' => $valor["junio"],
              ':julio' => $valor["julio"],
              ':agosto' => $valor["agosto"],
              ':septiembre' => $valor["septiembre"],
              ':octubre' => $valor["octubre"],
              ':noviembre' => $valor["noviembre"],
              ':diciembre' => $valor["diciembre"],
              ':total' => $valor["total"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':activo' => $valor["activo"],
              ':anio' => $valor["anio"],
              ':codigo' => $valor["codigo"],
              ':idCredencial' => $valor["idCredencial"],
              ':nivel' => $valor["nivel"],
              ':sector' => $valor["sector"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' => 'A'

          ));

       }

        $informacionRespaldoComponentesFooter = $this->constructor->select__general__incentivo("SELECT codigo,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,anio,sector,tipoIngreso FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


       foreach ($informacionRespaldoComponentesFooter as $valor) {
          
          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['codigo','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector','tipoIngreso','estado'], array(
              ':codigo' => $valor["codigo"],
              ':enero' => $valor["enero"],
              ':febrero' => $valor["febrero"],
              ':marzo' => $valor["marzo"],
              ':abril' => $valor["abril"],
              ':mayo' => $valor["mayo"],
              ':junio' => $valor["junio"],
              ':julio' => $valor["julio"],
              ':agosto' => $valor["agosto"],
              ':septiembre' => $valor["septiembre"],
              ':octubre' => $valor["octubre"],
              ':noviembre' => $valor["noviembre"],
              ':diciembre' => $valor["diciembre"],
              ':total' => $valor["total"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':anio' => $valor["anio"],
              ':sector' => $valor["sector"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' => 'A'
          ));

       }
      
      /*=====  End of Generar componentes respaldo  ======*/

      /*===========================================
      =            Generar componentes            =
      ===========================================*/
      
      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");
      
      /*=====  End of Generar componentes  ======*/
      
      /*===========================================
      =            Generar componentes            =
      ===========================================*/
      
      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");

      for($i=0; $i<$diferenciaAnios;$i++){

        $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

        foreach ($componetesV as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
            ':idComponentes' => $valor["idComponentes"],
            ':idNivel1' => $valorNivel__1["idNivel1"],
            ':fecha' => $this->fecha,
            ':hora' => $this->hora,
            ':anio' => $arrayAnios[$i],
            ':codigo' => $codigoFinal,
            ':idCredencial' => $idCredencial,
            ':nivel' => 0,
            ':sector' => 'componente',
            ':tipoIngreso' => $estadoCalificacion,
          ));

          $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion);


        }


        if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

          $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
            ':idComponentes' => 6,
            ':idNivel1' =>  $valorNivel__1["idNivel1"],
            ':fecha' => $this->fecha,
            ':hora' => $this->hora,
            ':anio' => $arrayAnios[$i],
            ':codigo' => $codigoFinal,
            ':idCredencial' => $idCredencial,
            ':nivel' => 0,
            ':sector' => 'componente',
            ':tipoIngreso' => $estadoCalificacion,
          ));

          $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion);

        }

      }

      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


      /*=====  End of Generar componentes  ======*/

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


                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'priorizado',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estado);


              }


              if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => 6,
                      ':idNivel1' =>  $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'priorizado',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'priorizado',$estado);

              }


          }

          for($i=0; $i<count($arrayAnios);$i++){

              $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

              foreach ($componetesV as $valor) {


                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => $valor["idComponentes"],
                      ':idNivel1' => $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'femenino',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estado);


              }


              if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

                  $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                      ':idComponentes' => 6,
                      ':idNivel1' =>  $valorNivel__1["idNivel1"],
                      ':fecha' => $this->fecha,
                      ':hora' => $this->hora,
                      ':anio' => $arrayAnios[$i],
                      ':codigo' => $codigoFinal,
                      ':idCredencial' => $idCredencial,
                      ':nivel' => 0,
                      ':sector' => 'femenino',
                      ':tipoIngreso' => $estado,
                  ));


                  $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'femenino',$estado);

              }


          }


       }     


      $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE (sector='priorizado' OR sector='femenino') AND codigo='$codigoFinal';");    
       
      /*=====  End of Ingresar componentes priorizados  ======*/

      return 1;

    }

    public function insertarDescripcionProyecto__modificacion__observacion($post) {

      $idCredencial=$post["idCredencial"];
      $nombreProyecto=$post["nombreProyecto"];
      $fechaInicio=$post["fechaInicio"];
      $fechaFin=$post["fechaFin"];
      $mensajePluri=$post["mensajePluri"];
      $objetivoGeneral=$post["objetivoGeneral"];
      $diferenciaAnios=$post["diferenciaAnios"];
      $estadoCalificacion=$post["estadoCalificacion"];
      $justificacionProyecto=$post["justificacionProyecto"];

      $codigo=$post["codigo"];
      $chequed=$post["chequed"];

      $arrayIdComponentes=json_decode($post["arrayIdComponentes"], true);
      $arrayQue=json_decode($post["arrayQue"], true);
      $arraycomoO=json_decode($post["arraycomoO"], true);
      $arrayparaO=json_decode($post["arrayparaO"], true);
      $arrayqueRespaldo=json_decode($post["arrayqueRespaldo"], true);
      $arraycomoRespaldo=json_decode($post["arraycomoRespaldo"], true);
      $arrayparaRespaldo=json_decode($post["arrayparaRespaldo"], true);
      $selectedIdsArray=json_decode($post["selectedIdsArray"], true);

      $proyecto__consultar=$this->constructor->select__general__incentivo("SELECT idProyecto FROM proyecto WHERE codigo='$codigo';");
      foreach ($proyecto__consultar as $value) {
        $idProyectoRecuperadoBd=$value["idProyecto"];
      }

      $consultaRespaldo__descripcion=$this->constructor->select__general__incentivo("SELECT idDescripcion,nombre,fechaInicio,fechaFin,tipo,diferenciaAnios,objetivoGeneral,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora,justificacionProyecto FROM proyecto_descripcion WHERE codigo='$codigo';");

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

      $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_descripcion",['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral','idCredencial','idProyecto','fecha','hora','codigo','tipoIngreso','estado','justificacionProyecto'],array(':nombre' => $nombreBd,':fechaInicio' => $fechaInicioBd,':fechaFin' => $fechaFinBd,':tipo' => $tipoBd,':diferenciaAnios' => $diferenciaAniosBd,':objetivoGeneral' => $objetivoGeneralBd,':idCredencial' => $idCredencialBd,':idProyecto' => $idProyectoBd,':fecha' => $fechaBdI,':hora' => $horaBdI,':codigo' => $codigoBd,':tipoIngreso' => $tipoIngresoBd,':estado' => 'A',':justificacionProyecto' => $justificacionProyectoBd));

      $this->proyectoExistenteEliminarBase("codigo","proyecto_descripcion",$codigo);

      $this->constructor->inserta__general__incentivo("proyecto_descripcion",['nombre','fechaInicio','fechaFin','tipo','diferenciaAnios','objetivoGeneral','idCredencial','idProyecto','fecha','hora','codigo','tipoIngreso','justificacionProyecto'],array(':nombre' => $nombreProyecto,':fechaInicio' => $fechaInicio,':fechaFin' => $fechaFin,':tipo' => $mensajePluri,':diferenciaAnios' => $diferenciaAnios,':objetivoGeneral' => $objetivoGeneral,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoRecuperadoBd,':fecha' => $this->fecha,':hora' => $this->hora,':codigo' => $codigo,':tipoIngreso' => $estadoCalificacion,':justificacionProyecto' => $justificacionProyecto));


        
      /*==========================================
      =            Resultados y metas            =
      ==========================================*/

      $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_resultados_metas SET estado='I' WHERE codigo='$codigo';");

      $selectResultadosMetas = $this->constructor->select__general__incentivo("SELECT objetivoEspecifico,nombreIndicador,descripcion,metodoCalculo,metaFinal,periodicidad,medioVerificacion,codigo,fecha,hora,idComponentes,tipoIngreso FROM proyecto_resultados_metas WHERE codigo='$codigoFinal';");


      foreach ($selectResultadosMetas as $valor) {


          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_resultados_metas", ['objetivoEspecifico','nombreIndicador','descripcion','metodoCalculo','metaFinal','periodicidad','medioVerificacion','codigo','fecha','hora','idComponentes','tipoIngreso','estado'], array(
              ':objetivoEspecifico' => $valor["objetivoEspecifico"],
              ':nombreIndicador' => $valor["nombreIndicador"],
              ':descripcion' => $valor["descripcion"],
              ':metodoCalculo' => $valor["metodoCalculo"],
              ':metaFinal' => $valor["metaFinal"],
              ':periodicidad' => $valor["periodicidad"],
              ':medioVerificacion' => $valor["medioVerificacion"],
              ':codigo' => $valor["codigo"],
              ':fecha' => $valor["fecha"],
              ':hora' => $valor["hora"],
              ':idComponentes' => $valor["idComponentes"],
              ':tipoIngreso' => $valor["tipoIngreso"],
              ':estado' => 'A'
          ));

      }
      
      
      $this->constructor->actualiza__general__incentivo("DELETE FROM  proyecto_resultados_metas WHERE codigo='$codigo';");

      $objetivosEspecificos = $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',que,como,paraQue) AS objetivoEspecifico,idComponentes FROM proyecto_componente_usuario WHERE codigo='$codigo';");


      foreach ($objetivosEspecificos as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_resultados_metas", ['objetivoEspecifico','fecha','hora','idComponentes','codigo'], array(
              ':objetivoEspecifico' => $valor["objetivoEspecifico"],
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':idComponentes' => $idComponentes,
              ':codigo' => $codigo,
          ));

      }
      
      $objetivosEspecificos__respaldos = $this->constructor->select__general__incentivo("SELECT CONCAT_WS(' ',que,como,paraQue) AS objetivoEspecifico,idComponentes FROM proyecto_componente_usuario_2 WHERE codigo='$codigo';");


      foreach ($objetivosEspecificos__respaldos as $valor) {

          $this->constructor->inserta__general__incentivo("proyecto_resultados_metas", ['objetivoEspecifico','codigo','fecha','hora','idComponentes'], array(
              ':objetivoEspecifico' => $valor["objetivoEspecifico"],
              ':codigo' => $codigo,
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':idComponentes' => $idComponentes,
          ));

      }
      
      
      /*=====  End of Resultados y metas  ======*/
        


      if ($chequed==="true") {

        /*=====================================
        =            Componentes 1            =
        =====================================*/
        
        
        $consultaRespaldo_componente=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes,que,como,paraQue,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora FROM proyecto_componente_usuario WHERE idCredencial='$idCredencial' AND codigo='$codigo';");


        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_componente_usuario SET estado='I' WHERE codigo='$codigo';");

        foreach ($consultaRespaldo_componente as $valor) {


          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_componente_usuario",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso','estado'],array(':idComponentes' => $valor["idComponentes"],':que' => $valor["que"],':como' => $valor["como"],':paraQue' =>$valor["paraQue"],':fecha' => $valor["fecha"],':hora' => $valor["hora"],':idCredencial' => $valor["idCredencial"],':idProyecto' => $valor["idProyecto"],':codigo' => $valor["codigo"],':tipoIngreso' => $valor["tipoIngreso"],':estado' => 'A'));

          $idComponentesProyectoBd=$valor["idComponentesProyecto"];

          $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_componente_usuario WHERE idComponentesProyecto='$idComponentesProyectoBd';");

        }


        foreach ($arrayQue as $clave => $valor) {
            
            $this->constructor->inserta__general__incentivo("proyecto_componente_usuario",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso'],array(':idComponentes' => $arrayIdComponentes[$clave],':que' => $arrayQue[$clave],':como' => $arraycomoO[$clave],':paraQue' => $arrayparaO[$clave],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoRecuperadoBd,':codigo' => $codigo,':tipoIngreso' => $estadoCalificacion));

        }

        
        /*=====  End of Componentes 1  ======*/
        

        /*=====================================
        =            componentes 2            =
        =====================================*/
        
                
        $consultaRespaldo_componente=$this->constructor->select__general__incentivo("SELECT idComponentesProyecto,idComponentes,que,como,paraQue,idCredencial,idProyecto,codigo,tipoIngreso,fecha,hora FROM proyecto_componente_usuario_2 WHERE idCredencial='$idCredencial' AND codigo='$codigo';");


        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_componente_usuario_2 SET estado='I' WHERE codigo='$codigo';");

        foreach ($consultaRespaldo_componente as $valor) {


          $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_componente_usuario_2",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso','estado'],array(':idComponentes' => $valor["idComponentes"],':que' => $valor["que"],':como' => $valor["como"],':paraQue' =>$valor["paraQue"],':fecha' => $valor["fecha"],':hora' => $valor["hora"],':idCredencial' => $valor["idCredencial"],':idProyecto' => $valor["idProyecto"],':codigo' => $valor["codigo"],':tipoIngreso' => $valor["tipoIngreso"],':estado' => 'A'));

          $idComponentesProyectoBd=$valor["idComponentesProyecto"];

          $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_componente_usuario_2 WHERE idComponentesProyecto='$idComponentesProyectoBd';");

        }


        foreach ($arrayQue as $clave => $valor) {
            
            $this->constructor->inserta__general__incentivo("proyecto_componente_usuario_2",['idComponentes','que','como','paraQue','fecha','hora','idCredencial','idProyecto','codigo','tipoIngreso'],array(':idComponentes' => $arrayIdComponentes[$clave],':que' => $arrayqueRespaldo[$clave],':como' => $arraycomoRespaldo[$clave],':paraQue' => $arrayparaRespaldo[$clave],':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idCredencial,':idProyecto' => $idProyectoRecuperadoBd,':codigo' => $codigo,':tipoIngreso' => $estadoCalificacion));

        }
        
        /*=====  End of componentes 2  ======*/

        /*==================================================
        =            Obtener fecha plurianuales            =
        ==================================================*/
        
        $codigoFinal=$codigo;

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


        
        /*=====  End of Obtener fecha plurianuales  ======*/
        
        /*====================================================
        =            Generar componentes respaldo            =
        ====================================================*/

        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto SET estado='I' WHERE codigo='$codigoFinal';");
        $this->constructor->actualiza__general__incentivo("UPDATE incentivorespaldo.proyecto_presupuesto_footer SET estado='I' WHERE codigo='$codigoFinal';");
        
         $informacionRespaldoComponentes = $this->constructor->select__general__incentivo("SELECT idComponentes,idNivel1,detalle,justificacion,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,activo,anio,codigo,idCredencial,nivel,sector,tipoIngreso FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");


         foreach ($informacionRespaldoComponentes as $valor) {

            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['idComponentes', 'idNivel1', 'detalle', 'justificacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','activo','anio','codigo','idCredencial','nivel','sector','tipoIngreso','estado'], array(
                ':idComponentes' => $valor["idComponentes"],
                ':idNivel1' => $valor["idNivel1"],
                ':detalle' => $valor["detalle"],
                ':justificacion' => $valor["justificacion"],
                ':enero' => $valor["enero"],
                ':febrero' => $valor["febrero"],
                ':marzo' => $valor["marzo"],
                ':abril' => $valor["abril"],
                ':mayo' => $valor["mayo"],
                ':junio' => $valor["junio"],
                ':julio' => $valor["julio"],
                ':agosto' => $valor["agosto"],
                ':septiembre' => $valor["septiembre"],
                ':octubre' => $valor["octubre"],
                ':noviembre' => $valor["noviembre"],
                ':diciembre' => $valor["diciembre"],
                ':total' => $valor["total"],
                ':fecha' => $valor["fecha"],
                ':hora' => $valor["hora"],
                ':activo' => $valor["activo"],
                ':anio' => $valor["anio"],
                ':codigo' => $valor["codigo"],
                ':idCredencial' => $valor["idCredencial"],
                ':nivel' => $valor["nivel"],
                ':sector' => $valor["sector"],
                ':tipoIngreso' => $valor["tipoIngreso"],
                ':estado' => 'A'
  
            ));

         }

          $informacionRespaldoComponentesFooter = $this->constructor->select__general__incentivo("SELECT codigo,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total,fecha,hora,anio,sector,tipoIngreso FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


         foreach ($informacionRespaldoComponentesFooter as $valor) {
            
            $this->constructor->inserta__general__incentivo("incentivorespaldo.proyecto_presupuesto", ['codigo','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total','fecha','hora','anio','sector','tipoIngreso','estado'], array(
                ':codigo' => $valor["codigo"],
                ':enero' => $valor["enero"],
                ':febrero' => $valor["febrero"],
                ':marzo' => $valor["marzo"],
                ':abril' => $valor["abril"],
                ':mayo' => $valor["mayo"],
                ':junio' => $valor["junio"],
                ':julio' => $valor["julio"],
                ':agosto' => $valor["agosto"],
                ':septiembre' => $valor["septiembre"],
                ':octubre' => $valor["octubre"],
                ':noviembre' => $valor["noviembre"],
                ':diciembre' => $valor["diciembre"],
                ':total' => $valor["total"],
                ':fecha' => $valor["fecha"],
                ':hora' => $valor["hora"],
                ':anio' => $valor["anio"],
                ':sector' => $valor["sector"],
                ':tipoIngreso' => $valor["tipoIngreso"],
                ':estado' => 'A'
            ));

         }
        
        /*=====  End of Generar componentes respaldo  ======*/
        


        /*===========================================
        =            Generar componentes            =
        ===========================================*/
        
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");
        
        /*=====  End of Generar componentes  ======*/
        

        /*===========================================
        =            Generar componentes            =
        ===========================================*/
        
        $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto WHERE codigo='$codigoFinal';");

        for($i=0; $i<count($arrayAnios);$i++){

          $componetesV = $this->constructor->select__general__incentivo("SELECT a.idComponentesProyecto, a.idComponentes, b.nombre FROM proyecto_componente_usuario AS a INNER JOIN componentes AS b ON a.idComponentes = b.idComponentes WHERE a.codigo = '$codigo' AND b.estado='A';");

          foreach ($componetesV as $valor) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
              ':idComponentes' => $valor["idComponentes"],
              ':idNivel1' => $valorNivel__1["idNivel1"],
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':anio' => $arrayAnios[$i],
              ':codigo' => $codigoFinal,
              ':idCredencial' => $idCredencial,
              ':nivel' => 0,
              ':sector' => 'componente',
              ':tipoIngreso' => $estadoCalificacion,
            ));

            $this->insertarNivel__1($valor["idComponentes"], 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion);


          }


          if (in_array(1, $arrayIdComponentes) || in_array(2, $arrayIdComponentes) || in_array(3, $arrayIdComponentes)) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
              ':idComponentes' => 6,
              ':idNivel1' =>  $valorNivel__1["idNivel1"],
              ':fecha' => $this->fecha,
              ':hora' => $this->hora,
              ':anio' => $arrayAnios[$i],
              ':codigo' => $codigoFinal,
              ':idCredencial' => $idCredencial,
              ':nivel' => 0,
              ':sector' => 'componente',
              ':tipoIngreso' => $estadoCalificacion,
            ));

            $this->insertarNivel__1(6, 1,$arrayAnios[$i],$codigoFinal,$idCredencial,'componente',$estadoCalificacion);

          }

        }

         $this->constructor->actualiza__general__incentivo("DELETE FROM proyecto_presupuesto_footer WHERE codigo='$codigoFinal';");


        /*=====  End of Generar componentes  ======*/


      }

      return 1;


    } 


    public function insertarNivel__1($idComponentes, $nivel,$anio,$codigoFinal,$idCredencial,$sector='componente',$estadoCalificacion) {

        $nivel__1 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND estado='A';");

        foreach ($nivel__1 as $valorNivel__1) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__1["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 1,
                ':sector' => $sector,
                ':tipoIngreso' => $estadoCalificacion
            ));

            $this->insertarNivel__2($idComponentes, 2,$valorNivel__1["idNivel1"],$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion);
        }

      return 1;

    }

    public function insertarNivel__2($idComponentes, $nivel,$idNivel1,$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion) {

        $nivel__2 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND idNivelRelacion='$idNivel1' AND estado='A';");

        foreach ($nivel__2 as $valorNivel__2) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__2["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 2,
                ':sector' => $sector,
                ':tipoIngreso' => $estadoCalificacion
            ));

            $this->insertarNivel__3($idComponentes, 3,$valorNivel__2["idNivel1"],$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion);


        }

        return 1;
    }

    public function insertarNivel__3($idComponentes, $nivel,$idNivel1,$anio,$codigoFinal,$idCredencial,$sector,$estadoCalificacion) {

        $nivel__2 = $this->constructor->select__general__incentivo("SELECT idNivel1 FROM componentes_nivel WHERE idComponentes = '$idComponentes' AND nivel = '$nivel' AND idNivelRelacion='$idNivel1' AND estado='A';");

        foreach ($nivel__2 as $valorNivel__2) {

            $this->constructor->inserta__general__incentivo("proyecto_presupuesto", ['idComponentes', 'idNivel1', 'fecha', 'hora','anio','codigo','idCredencial','nivel','sector','tipoIngreso'], array(
                ':idComponentes' => $idComponentes,
                ':idNivel1' => $valorNivel__2["idNivel1"],
                ':fecha' => $this->fecha,
                ':hora' => $this->hora,
                ':anio' => $anio,
                ':codigo' => $codigoFinal,
                ':idCredencial' => $idCredencial,
                ':nivel' => 3,
                ':sector' => $sector,
                ':tipoIngreso' => $estadoCalificacion
            ));

        }

        return 1;
    }


    public function componentes__principal($idCredencial) {

       return $this->constructor->select__general__incentivo("SELECT idComponentes,que,como,paraQue FROM proyecto_componente_usuario WHERE codigo='$idCredencial';");

    } 

    public function obtenerNombrePersona($post) {

      $array=array();

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      $credencialObtener=$this->constructor->select__general__incentivo("SELECT a.idUsuarioNuevo FROM proyecto_enviado_antecedente AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id WHERE b.codigo='$codigoProyecto' AND b.codigoUsuario='$codigoUsuario' ORDER BY a.id DESC LIMIT 1;");

      foreach ($credencialObtener as $valor) {
        $idusuarioNuevoBd=$valor["idUsuarioNuevo"];
      }

      $informacionCompleta=$this->constructor->select__general__talento("SELECT c.nombre AS rol,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(d.descripcionFisicamenteEstructura, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS direccion,a.email FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario INNER JOIN th_roles AS c ON c.id_rol=b.id_rol INNER JOIN th_fisicamenteestructura AS d ON d.id_FisicamenteEstructura=a.fisicamenteEstructura WHERE a.id_usuario='$idusuarioNuevoBd';");

      foreach ($informacionCompleta as $valor) {
        array_push($array,$valor["rol"]);
        array_push($array,$valor["nombreCompleto"]);
        array_push($array,$valor["direccion"]);
        array_push($array,$valor["email"]);
      }

      return $array;

    }


    public function proyectosEnviadosCiudadano($idCredencial) {

      return $this->constructor->select__general__incentivo("SELECT a.id,a.codigoUsuario,a.codigo, IF(a.estadoCalificacion IS NULL,'ENVIADO',IF(a.estadoCalificacion='Devuelto' OR a.estadoCalificacion='Comite','RECOMENDADO',UPPER(a.estadoCalificacion))) AS estadoCalificacion,(SELECT a1.tipo FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS estado, IF(a.estadoCalificacion='observado' OR a.estadoCalificacion='modificacion','EstadoProyectosO','EstadoProyectosP') AS componente,(SELECT a1.texto FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id ORDER BY id DESC LIMIT 1) AS observacionBaja,(SELECT a1.nombre FROM proyecto_descripcion AS a1 WHERE a1.codigo=a.codigoUsuario  LIMIT 1) AS nombreProyecto,CONCAT_WS('','EstadoProyectosModificacion') AS componenteModificacion,IFNULL((SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=a.codigoUsuario LIMIT 1),'N') AS modificacion,IFNULL((SELECT a1.estado FROM proyecto_modificacion_solicitud AS a1 WHERE (a1.estado='PENDIENTE' || a1.estado='ENVIADO' || a1.estado='APROBADO') AND  a1.codigo=a.codigoUsuario  LIMIT 1),'N') AS modificacionCer,CONCAT_WS('','EstadoProyectosCertificacion') AS componenteCertificacion,IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='TB','Cerrado-No ejecutado',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='T','Cerrado',IF(a.estadoSeguimiento IS NOT NULL,'P','N/A'))) AS estadoSeguimiento, IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='P','PENDIENTE ENVIAR INFORME',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='E','ENVIADO',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='O','OBSERVADO',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='A','APROBADO',IF(a.estadoSeguimiento IS NOT NULL AND a.estadoSeguimiento='C','CERRADO',' '))))) AS estadoProyectoSeguimiento,CONCAT_WS('','EstadoProyectosSeguimiento') AS componenteSeguimiento, IFNULL(a.observacionAnalista,' ') AS observacionAnalistaNegacion FROM proyecto_enviado AS a WHERE a.idCredencial='$idCredencial' AND a.codigoUsuario IS NOT NULL AND a.codigo<>'041-01-PIT-2025-15-1' AND a.codigo<>'041-01-PIT-2025-17-1' AND a.codigo<>'041-01-PIT-2025-21-1' GROUP BY a.codigo;");

    }

    public function componentes__escogidos($codigo) {

      $array=array();

      $consulta=$this->constructor->select__general__incentivo("SELECT idComponentes FROM proyecto_componente_usuario WHERE codigo='$codigo';");
      foreach ($consulta as $valor) {
        array_push($array, $valor["idComponentes"]);
      }

      $buscador = implode(',', $array);

       return $this->constructor->select__general__incentivo("SELECT idComponentes,nombre,descripcion FROM componentes WHERE estado='A' AND idComponentes IN ($buscador) AND idComponentes!='6';");


    } 

    public function sectorProyecto__escogido($codigo) {

       return $this->constructor->select__general__incentivo("SELECT idSector, nombre, definicion,  GROUP_CONCAT(CONCAT(row_num, '- ', alineacion_nombre) ORDER BY row_num SEPARATOR '; ') AS alineacion FROM (SELECT a.idSector, a.nombre, a.definicion, b.nombre AS alineacion_nombre, @row_num := IF(@prev_sector = a.idSector, @row_num + 1, 1) AS row_num, @prev_sector := a.idSector FROM sector AS a INNER JOIN alineacion_estrategica AS b ON a.idSector = b.idSector INNER JOIN proyecto_sector AS f ON f.idSector=a.idSector CROSS JOIN (SELECT @row_num := 0, @prev_sector := '') AS vars WHERE a.estado = 'A' AND f.codigo='$codigo' ORDER BY a.idSector, b.nombre) AS numbered_alineaciones  GROUP BY idSector, nombre, definicion;");

    } 


    public function obtenerPresupuestoNiveles__2($codigo,$anio,$tiposComponentes) {

       return $this->constructor->select__general__incentivo("SELECT a.id,IF(b.color IS NULL,'#0c4a6e',b.color) AS color,IF(b.color IS NULL,'white','black') AS colorTexto,IF(a.idNivel1 IS NULL,(SELECT a1.numeral FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.numeral) AS rotulo,IF(a.idNivel1 IS NULL,(SELECT a1.nombre FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.nombre) AS nombre, a.detalle, a.justificacion,a.enero,a.febrero,a.marzo,a.abril,a.mayo,a.junio,a.julio,a.agosto,a.septiembre,a.octubre,a.noviembre,a.diciembre,a.total,a.anio,a.codigo,a.idCredencial,IF(b.nivel IS NULL, 0,b.nivel) AS nivel, IF(a.idNivel1 IS NULL,(SELECT a1.idComponentes FROM componentes AS a1 WHERE a1.idComponentes=a.idComponentes),b.idComponentes) AS idComponentes, IF(b.rubros IS NULL,0,b.rubros) AS rubros,IF(b.idNivelRelacion IS NULL,0,b.idNivelRelacion) AS idNivelRelacion,IF(b.idNivel1 IS NULL,0,b.idNivel1) AS idNivel1 FROM proyecto_presupuesto AS a LEFT JOIN componentes_nivel AS b ON a.idNivel1=b.idNivel1 WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' ORDER BY a.id;");

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos__2($codigo,$anio,$tiposComponentes) {

       return $this->constructor->select__general__incentivo("SELECT b.idComponentes FROM proyecto_presupuesto AS a INNER JOIN componentes_nivel AS b ON a.idComponentes=b.idComponentes WHERE a.codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes' GROUP BY b.idComponentes ORDER BY a.id;");

    } 

    public function obtenerPresupuestoNiveles__componentesUnicos__footer__2($codigo,$anio,$tiposComponentes) {

       return $this->constructor->select__general__incentivo("SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total FROM proyecto_presupuesto_footer WHERE codigo='$codigo' AND anio='$anio' AND sector='$tiposComponentes';");

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
        return "EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='5') AND c.idSector=a1.idSector) || EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='4') AND c.idSector=a1.idSector) || EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='1') AND c.idSector=a1.idSector) || EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='2' OR c.idSector='3') AND c.idSector=a1.idSector)";
      }

    }

    public function orden__obtenido($idRol,$fisicamenteEstructura) {

      $ordenBase=$this->constructor->select__general("SELECT b.orden FROM nivel_rol_fisicamente AS a INNER JOIN nivel_rol AS b ON a.idNivel=b.idNivel WHERE a.idFisicamente='$fisicamenteEstructura' AND a.idRol='$idRol';");

      foreach ($ordenBase as $valor) {
        $ordenBd=$valor["orden"];
      }

      return $ordenBd;

    }

    public function bandeja__recibidos($idCredencial,$idRol,$fisicamenteEstructura) {

      $ordenBd=$this->orden__obtenido($idRol,$fisicamenteEstructura);

      $funcionarioEnviado=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($funcionarioEnviado as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }


      $consultaOrden=$this->orden__recibidos($ordenBd,$idRol,$idUsuarioBd,$fisicamenteEstructura);
      $consultaSectores=$this->sectores__recibidos($fisicamenteEstructura);


      if(intval($idRol)===4 && intval($fisicamenteEstructura)===1 ){

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, (SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha,IF(a.estadoCalificacion='observado','OBSERVADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','RECIBIDO DIRECCIÓN TÉCNICA'))))) AS estado,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE  ((a.estadoCalificacion IS NULL OR a.estadoCalificacion='ENVIADO INFRA' OR a.estadoCalificacion='observado' OR a.estadoCalificacion='Recomendado' OR a.estadoCalificacion='rectificado') AND $consultaSectores) AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigoUsuario;");

      }else if (intval($fisicamenteEstructura)===15) {

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector, (SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha,IF(a.estadoCalificacion='observado','OBSERVADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','RECIBIDO DIRECCIÓN TÉCNICA'))))) AS estado,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario AND (e.idComponentes='5' OR e.idComponentes='7') WHERE ($consultaOrden AND $consultaSectores OR (a.idFisicamente='$fisicamenteEstructura' AND a.idUsuario='$idUsuarioBd' AND a.estadoCalificacion!='Comite')) AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) GROUP BY a.codigoUsuario;");


      }else if(intval($idRol)===7){

        return $this->constructor->select__general__incentivo("SELECT a.estadoCalificacion,a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA'))) AS estado, (SELECT IF(a1.tipo='Observado calificacion',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE ((a.estadoCalificacion IS NULL OR a.estadoCalificacion='ENVIADO INFRA' OR a.estadoCalificacion='observado' OR a.estadoCalificacion='Recomendado' OR a.estadoCalificacion='rectificado') AND $consultaSectores) AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id) AND a.codigo<>'041-01-PIT-2025-15-1' GROUP BY a.codigoUsuario;");

      }else if(intval($idRol)===2 && intval($fisicamenteEstructura)===43){

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion='observado','OBSERVADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','RECIBIDO DIRECCIÓN TÉCNICA'))))) AS estado, (SELECT IF(a1.tipo='Observado en etapa de calificación calificacion' OR a.estadoCalificacion='observado',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE (EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='5' OR c.idSector='4') AND c.idSector=a1.idSector) OR EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='2' OR c.idSector='3' OR c.idSector='1') AND c.idSector=a1.idSector) OR EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE ((a.estadoCalificacion='devuelto' AND a.idFisicamente='$fisicamenteEstructura' AND a.idUsuario IS NULL) OR  (c.idSector='5') AND c.idSector=a1.idSector) OR EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='4') AND c.idSector=a1.idSector) OR EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='1') AND c.idSector=a1.idSector) OR EXISTS (SELECT a1.idSector FROM sector AS a1 WHERE (c.idSector='2' OR c.idSector='3') AND c.idSector=a1.idSector) OR (a.idUsuario='$idUsuarioBd'))) AND a.estadoCalificacion IS NULL AND a.idUsuario IS NULL AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id)  GROUP BY a.codigoUsuario;");

      }else{

        return $this->constructor->select__general__incentivo("SELECT a.codigoUsuario,a.codigo,UPPER(b.nombre) AS nombreProyecto,IF(c.idSector IS NULL,'INFRAESTRUCTURA',GROUP_CONCAT(DISTINCT(SELECT UPPER(a1.nombre) FROM sector AS a1 WHERE a1.idSector=c.idSector) SEPARATOR ' ')) AS sector,(SELECT FORMAT(SUM(a1.total), 2) FROM proyecto_presupuesto_footer AS a1 WHERE a1.codigo=a.codigoUsuario GROUP BY a1.codigo) AS monto,a.fecha, IF(a.estadoCalificacion='observado','OBSERVADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF(a.estadoCalificacion='rectificado','RECTIFICADO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NULL,'RECIBIDO',IF((e.idComponentes='5' OR e.idComponentes='7') AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','RECIBIDO DIRECCIÓN TÉCNICA'))))) AS estado, (SELECT IF(a1.tipo='Observado en etapa de calificación calificacion' OR a.estadoCalificacion='observado',CONCAT('', ABS(DATEDIFF(DATE_ADD(a1.fecha, INTERVAL 10 DAY), CURDATE())), ' días'),' ') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS tiempoObservacion,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT a2.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a2 WHERE a2.id_FisicamenteEstructura=a1.idFisicamenteNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS fisicamenteNuevo,(SELECT IF(a1.tipo='Enviado a infraestructura','COORDINACION DE SERVICIOS AL SISTEMA DEPORTIVO',(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a2 WHERE a2.id_usuario=a1.idUsuarioNuevo)) FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=a.id ORDER BY a1.id DESC LIMIT 1) AS usuarioNuevo  FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario  INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE (a.estadoCalificacion='devuelto' AND a.idFisicamente='$fisicamenteEstructura' AND a.idUsuario IS NULL) OR ($consultaOrden AND $consultaSectores OR (a.idUsuario='$idUsuarioBd')) AND NOT EXISTS (SELECT a1.id FROM proyecto_enviado_baja AS a1 WHERE a1.idEnviado=a.id)  GROUP BY a.codigoUsuario;");

      }


    } 



    public function informacion__analistas($idCredencial) {

      $funcionario=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($funcionario as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $this->constructor->select__general__talento("SELECT b.id_rol,a.fisicamenteEstructura,a.id_usuario FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.id_usuario='$idUsuarioBd';");


    } 


    public function informacion__cargo($idCredencial) {

      $personCargo=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($personCargo as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }


      $obtenerRol=$this->constructor->select__general__talento("SELECT b.id_rol FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.id_usuario='$idUsuarioBd';");
      foreach ($obtenerRol as $valor) {
        $id_rolBd=$valor["id_rol"];
      }

      if(intval($id_rolBd)===4){

        return $this->constructor->select__general__talento("SELECT a.id_usuario,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.PersonaACargo='$idUsuarioBd' AND a.estadoUsuario='A' AND b.id_rol='2' AND a.fisicamenteEstructura='15' ORDER BY a.apellido;");

      }else if (intval($id_rolBd)===7) {
        
        return $this->constructor->select__general__talento("SELECT a.id_usuario,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.PersonaACargo='$idUsuarioBd' AND a.estadoUsuario='A' AND b.id_rol='2' ORDER BY a.apellido;");

      }else{

        return $this->constructor->select__general__talento("SELECT a.id_usuario,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto FROM th_usuario AS a WHERE a.PersonaACargo='$idUsuarioBd' AND a.estadoUsuario='A' ORDER BY a.apellido;");


      }

      
    } 

    public function personas__redirigir($fisicamenteEstructura,$idRol) {

      $array=array();

      if (intval($idRol)===7) {

        $obtenerId=$this->constructor->select__general__talento("SELECT id_FisicamenteEstructura FROM th_fisicamenteestructura WHERE descripcionFisicamenteEstructura LIKE '%SUBSECRETA%' AND id_FisicamenteEstructura!='$fisicamenteEstructura' AND id_FisicamenteEstructura!='25'; ");

      }else if(intval($idRol)===2 || intval($idRol)===3){

        $obtenerFisicamenteIdDirector=$this->constructor->select__general__talento("SELECT PersonaACargo FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.fisicamenteEstructura='$fisicamenteEstructura' AND a.estadoUsuario='A' AND b.id_rol='2';");

        foreach ($obtenerFisicamenteIdDirector as $valor) {
          $idUsuarioBd=$valor["PersonaACargo"];
        }

        $obtenerId=$this->constructor->select__general__talento("SELECT a.fisicamenteEstructura AS id_FisicamenteEstructura FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE (a.fisicamenteEstructura='12' OR a.fisicamenteEstructura='14' OR a.fisicamenteEstructura='19' OR a.fisicamenteEstructura='13') AND b.id_rol='2' AND a.fisicamenteEstructura<>'$fisicamenteEstructura' AND a.estadoUsuario='A';");

      }

      foreach ($obtenerId as $valor) {
        array_push($array, $valor["id_FisicamenteEstructura"]);
      }

      $idFisicamentes = implode(', ', $array);


      
      return $this->constructor->select__general__talento("SELECT id_FisicamenteEstructura,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(descripcionFisicamenteEstructura, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS descripcionFisicamenteEstructura FROM th_fisicamenteestructura WHERE id_FisicamenteEstructura IN ($idFisicamentes) AND id_FisicamenteEstructura!='$fisicamenteEstructura';");
      
    } 

    public function seleccionarProfesional($idCredencial) {

      $personCargo=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($personCargo as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
      }

      return $idUsuarioBd;

    }

    public function obtener__version__en__certificacion($codigo){

      $consulta=$this->constructor->select__general__incentivo("SELECT IF(version1 IS NULL,'NO','SI') AS version FROM proyecto_certificacion_factura_tramite WHERE codigo='$codigo';");
      foreach ($consulta as $valor) {
        $versionBd=$valor["version"];
      }
      return $versionBd;

    }    
 

    public function usuario__titpo__cargar($codigo,$idCredencial=0) {

      if($this->obtener__version__en__certificacion($codigo)==="NO"){
        $idCredencialConsulta=$this->constructor->select__general__incentivo("SELECT idCredencial FROM proyecto WHERE codigo='$codigo';");
      }else{
        $array = explode("-", $codigo);
        // $consultaOlgura=$this->constructor->select__general__incentivo("SELECT idCredencial FROM configuracion.credencial WHERE usuario='".$array[1]."';");

        $consultaOlgura=$this->constructor->select__general__incentivo("SELECT idCredencial FROM proyecto_enviado WHERE codigoUsuario='$codigo';");
        foreach ($consultaOlgura as $valor) {
          $idCredencialBdOlgura=$valor["idCredencial"];
        }
        $idCredencialConsulta=$this->constructor->select__general("SELECT idCredencial FROM credencial WHERE idCredencial='$idCredencialBdOlgura';");
      }

      

      foreach ($idCredencialConsulta as $valor) {
        $idCredencialBd=$valor["idCredencial"];
      }


      return $this->constructor->select__general("SELECT b.idTipoUsuario FROM credencial_tipo AS a INNER JOIN incentivo.tipo_usuario AS b ON a.idTipoUsuario=b.idTipoUsuario WHERE a.idCredencial='$idCredencialBd';");

    } 


    public function seleccionar__fisicamenteEstructura($idUsuario) {

      $fisicamenteEstructuraC=$this->constructor->select__general__talento("SELECT fisicamenteEstructura FROM th_usuario WHERE id_usuario='$idUsuario';");

      foreach ($fisicamenteEstructuraC as $valor) {
        $fisicamenteEstructuraBd=$valor["fisicamenteEstructura"];
      }

      return $fisicamenteEstructuraBd;

    }


    public function obtener__fisicamenteUsuarios($idUsuario) {

      $idUsuarioConsulta=$this->constructor->select__general__talento("SELECT fisicamenteEstructura FROM th_usuario WHERE id_usuario='$idUsuario';");

      foreach ($idUsuarioConsulta as $valor) {
        $fisicamenteEstructuraBd=$valor["fisicamenteEstructura"];
      }

      return $fisicamenteEstructuraBd;

    }


    public function obtener__usuarioFisicamente($fisicamenteEnviar,$idRol) {

      if (intval($idRol)===2) {
        $idUsuarioConsulta=$this->constructor->select__general__talento("SELECT a.id_usuario FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.fisicamenteEstructura='$fisicamenteEnviar' AND b.id_rol='2' AND a.estadoUsuario='A';");
      }else if(intval($idRol)===7){
        $idUsuarioConsulta=$this->constructor->select__general__talento("SELECT a.id_usuario FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.fisicamenteEstructura='$fisicamenteEnviar' AND b.id_rol='7' AND a.estadoUsuario='A';");
      }

      foreach ($idUsuarioConsulta as $valor) {
        $id_usuarioBd=$valor["id_usuario"];
      }

      return $id_usuarioBd;

    }

    public function funcion__obtener__enviado($codigo){

        $idUsuarioConsulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigo';");      
        foreach ($idUsuarioConsulta as $valor) {
          $idBd=$valor["id"];
        }

        return $idBd;

    }

    public function funcion__reenviar($fisicamenteEnviar,$idRol,$codigo,$idRolActual,$fisicamenteEstructuraActual,$idUsuario,$textoRegresar) {

      $idUsuarioResignar=$this->obtener__usuarioFisicamente($fisicamenteEnviar,$idRolActual);
      $idEnviado=$this->funcion__obtener__enviado($codigo);


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idFisicamente='$fisicamenteEnviar', idUsuario='$idUsuarioResignar', fechaTramite='".$this->fecha."', horaTramite='".$this->hora."', idFisicamenteN='$fisicamenteEstructuraActual' WHERE codigo='$codigo';");

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio'],array(':idFisicamenteActual' => $fisicamenteEstructuraActual,':idUsuarioActual' => $idUsuario,':idFisicamenteNuevo' => $fisicamenteEnviar,':idUsuarioNuevo' => $idUsuarioResignar,':idEnviado' => $idEnviado,':tipo' => "Redirigido",':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $textoRegresar));


    }


    public function funcion__asignar($idUsuarioResignar,$idRol,$codigo,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar) {

      $fisicamenteEnviar=$this->obtener__fisicamenteUsuarios($idUsuarioResignar);
      $idEnviado=$this->funcion__obtener__enviado($codigo);

      if($estadoEnviado==="RECIBIDO DIRECCIÓN TÉCNICA"){
        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idFisicamente='$fisicamenteEnviar', idUsuario='$idUsuarioResignar', fechaTramite='".$this->fecha."', horaTramite='".$this->hora."', idFisicamenteN=NULL,infraRecibido=NULL,estadoCalificacion=NULL,idUsuarioRecomiendaCalificacion=NULL WHERE codigo='$codigo';");
      }else if($estadoEnviado==="PENDIENTE REVISIÓN TÉCNICA"){
         $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idFisicamente='$fisicamenteEnviar', idUsuario='$idUsuarioResignar', fechaTramite='".$this->fecha."', horaTramite='".$this->hora."', idFisicamenteN=NULL,infraRecibido='A' WHERE codigo='$codigo';");
      }else{
         $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idFisicamente='$fisicamenteEnviar', idUsuario='$idUsuarioResignar', fechaTramite='".$this->fecha."', horaTramite='".$this->hora."', idFisicamenteN=NULL WHERE codigo='$codigo';");
      }

      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio'],array(':idFisicamenteActual' => $fisicamenteEstructuraActual,':idUsuarioActual' => $idUsuario,':idFisicamenteNuevo' => $fisicamenteEnviar,':idUsuarioNuevo' => $idUsuarioResignar,':idEnviado' => $idEnviado,':tipo' => "Asignado en etapa de calificación",':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $textoRegresar));

    }


    public function funcion__obtener__informacion__tipo__recibidos($codigo){

        $idUsuarioConsulta=$this->constructor->select__general__incentivo("SELECT IF(a.estadoCalificacion='ENVIADO INFRA','RECIBIDO DIRECCIÓN TÉCNICA',IF(e.idComponentes='5' AND c.idSector IS NULL,'RECIBIDO',IF(e.idComponentes='5' AND c.idSector IS NOT NULL AND a.idUsuario='0' AND a.idUsuarioRecomiendaCalificacion='0','RECIBIDO DIRECCIÓN TÉCNICA','PENDIENTE REVISIÓN TÉCNICA'))) AS estado FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo LEFT JOIN proyecto_sector AS c ON c.codigo=a.codigoUsuario INNER JOIN proyecto_presupuesto AS d ON d.codigo=a.codigoUsuario AND nivel!='0' INNER JOIN proyecto_componente_usuario AS e ON e.codigo=a.codigoUsuario WHERE a.codigo='$codigo'   GROUP BY c.codigo,d.codigo;");      
        foreach ($idUsuarioConsulta as $valor) {
          $estadoBd=$valor["estado"];
        }

        return $estadoBd;

    }  

    public function funcion__regresarTecnica($texto,$idUsuario,$fisicamenteEstructura,$codigo,$idEnviado) {

      $consulta=$this->constructor->select__general__incentivo("SELECT idFisicamenteActual FROM proyecto_enviado_antecedente WHERE idEnviado='$idEnviado' ORDER BY id DESC LIMIT 1;");
      foreach ($consulta as $valor) {
        $idFisicamenteActualBd=$valor["idFisicamenteActual"];
      }


      $consulta2=$this->constructor->select__general__talento("SELECT a.id_usuario,a.fisicamenteEstructura FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE a.estadoUsuario='A' AND b.id_rol='2' AND a.fisicamenteEstructura='$idFisicamenteActualBd';");
      foreach ($consulta2 as $valor) {
        $id_usuarioBd=$valor["id_usuario"];
        $fisicamenteEstructuraBd=$valor["fisicamenteEstructura"];
      }


      $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idFisicamente='$fisicamenteEstructuraBd', idUsuarioRecomiendaCalificacion='$id_usuarioBd', fechaTramite='".$this->fecha."', horaTramite='".$this->hora."', idFisicamenteN=NULL WHERE id='$idEnviado';");



      return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora','observacionEnvio'],array(':idFisicamenteActual' => $fisicamenteEstructura,':idUsuarioActual' => $idUsuario,':idFisicamenteNuevo' => $fisicamenteEstructuraBd,':idUsuarioNuevo' => $id_usuarioBd,':idEnviado' => $idEnviado,':tipo' => "Devuelto",':fecha' => $this->fecha,':hora' => $this->hora,':observacionEnvio' => $texto));


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

      if(!empty($codigo)){

        if ($tipoUsuario==="redirigir") {
          
          return $this->funcion__reenviar($personaReasignar,$idRolActual,$codigo,$idRolActual,$fisicamenteEstructuraActual,$idUsuario,$textoRegresar);

        }else if ($tipoUsuario==="reasignar"){

          return $this->funcion__asignar($personaReasignar,$idRolActual,$codigo,$fisicamenteEstructuraActual,$idUsuario,$estadoEnviado,$textoRegresar);

        }else if($tipoUsuario==="regresarTecnica"){

          return $this->funcion__regresarTecnica($textoRegresar,$idUsuario,$informacionUsuario[5],$codigo,$idEnviado);

        }

      }

    } 

    public function obtener__nombreTabla__observacion($tipo) {

      switch ($tipo) {

        case 'descripcion':
          return 'proyecto_observacion_descripcion';
        break;
        
        case 'requisitos':
          return 'proyecto_observacion_requisitos';
        break;

        case 'sectorContribuye':
          return 'proyecto_observacion_sector';
        break;

        case 'beneficiarios':
          return 'proyecto_observacion_beneficiarios';
        break;

        case 'componentesValorizados':
          return 'proyecto_observacion_componentes';
        break;

        case 'cronogramaActividades':
          return 'proyecto_observacion_cronogramaactividades';
        break;

        case 'resultadoMetasEsperadas':
          return 'proyecto_observacion_resultadometas';
        break;

        case 'seguimientoEvaluacion':
          return 'proyecto_observacion_seguimiento';
        break;

      }

    } 


    public function seleccionarFuncionario__id($idCredencial) {

      $personCargo=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idCredencial='$idCredencial';");

      foreach ($personCargo as $valor) {
        $idFuncionarioBd=$valor["idFuncionario"];
      }

      return $idFuncionarioBd;

    }


    public function observacion__ministerio($post) {

      $idCredencial=$post["idCredencial"];
      $tipo=$post["tipo"];
      $calificacion=$post["calificacion"];
      $textoObservacion=$post["textoObservacion"];
      $codigo=$post["codigo"];
      $codigoProyecto=$post["codigoProyecto"];

      if(!empty($codigo)){

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


    } 

    public function observacion__descripcion__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_descripcion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    }     

    public function observacion__descripcion($post) {

      $idCredencial=$post["idCredencial"];

      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
          return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_descripcion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_descripcion WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra IS NULL;");
      }

    } 

    public function observacion__requisitos($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_requisitos WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A'  AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_requisitos WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    } 

    public function observacion__requisitos__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_requisitos WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 


    public function observacion__sector($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_sector WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_sector WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    } 


    public function observacion__sector__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_sector WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 


    public function observacion__beneficiarios($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_beneficiarios WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_beneficiarios WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    } 


    public function observacion__beneficiarios__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_beneficiarios WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 

    public function observacion__componentes($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_componentes WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_componentes WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    } 

    public function observacion__componentes__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_componentes WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 


    public function observacion__cronogramaActividades($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_cronogramaactividades WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_cronogramaactividades WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }

    } 

    public function observacion__cronogramaActividades__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_cronogramaactividades WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 


    public function observacion__resultadoMetas($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_resultadometas WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_resultadometas WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }


    } 

    public function observacion__resultadoMetas__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_resultadometas WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 
    
    public function observacion__seguimiento($post) {

      $idCredencial=$post["idCredencial"];
      $idUsuario=$this->obtener__id__usuario($idCredencial);
      $informacionUsuario=$this->obtener__usuario($idUsuario);

      if (intval($informacionUsuario[5])===15 || intval($informacionUsuario[5])===1) {
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_seguimiento WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A' AND infra='A';");
      }else{
        return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_seguimiento WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");
      }


    } 

    public function observacion__seguimiento__usuario($post) {

      return $this->constructor->select__general__incentivo("SELECT calificacion,textoObservacion, IF(calificacion='noValidar','No validado','Validado') AS calificacionTexto  FROM proyecto_observacion_seguimiento WHERE codigo='".$post["codigoProyecto"]."' AND codigoUsuario='".$post["codigoUsuario"]."' AND estado='A';");

    } 

    public function enviar__observacion__general($post) {

      $idUsuario=$post["idUsuario"];
      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];

      if(!empty($codigoUsuario)){

        $informacionAntecedentes=$this->constructor->select__general__incentivo("SELECT a.idFisicamenteActual,a.idUsuarioActual,a.idFisicamenteNuevo,a.idUsuarioNuevo,a.idEnviado,b.idCredencial FROM proyecto_enviado_antecedente AS a INNER JOIN proyecto_enviado AS b ON a.idEnviado=b.id WHERE b.codigo='$codigoProyecto' AND b.codigoUsuario='$codigoUsuario' ORDER BY a.id DESC LIMIT 1;");

        foreach ($informacionAntecedentes as $valor) {

          $idFisicamenteActualBd=$valor["idFisicamenteNuevo"];
          $idUsuarioActualBd=$valor["idUsuarioNuevo"];
          $idFisicamenteNuevoBd=$valor["idFisicamenteNuevo"];
          $idUsuarioNuevoBd=$valor["idUsuarioNuevo"];
          $idEnviadoBd=$valor["idEnviado"];
          $idCredencial=$valor["idCredencial"];

        }

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET estadoCalificacion='observado' WHERE codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario';");

        $this->administradorController->enviarCorreo__general($idCredencial,$codigoProyecto);

        return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente",['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo','fecha','hora'],array(':idFisicamenteActual' => $idFisicamenteActualBd,':idUsuarioActual' => $idUsuarioActualBd,':idFisicamenteNuevo' => $idFisicamenteNuevoBd,':idUsuarioNuevo' => $idUsuarioNuevoBd,':idEnviado' => $idEnviadoBd,':tipo' => "Observado en etapa de calificación calificacion",':fecha' => $this->fecha,':hora' => $this->hora));


      }

    } 

    public function estado__calificacion($post) {

      $codigoUsuario=$post["codigoUsuario"];
      $codigoProyecto=$post["codigoProyecto"];


      return $this->constructor->select__general__incentivo("SELECT IF(estadoCalificacion IS NULL OR estadoCalificacion='rectificado' OR estadoCalificacion='Devuelto','rectificar','noRectificar') AS estadoCalificacion FROM proyecto_enviado WHERE codigo='$codigoProyecto' AND codigoUsuario='$codigoUsuario';");

    } 

    public function estado__calificacion__general($post) {

      $codigoUsuario=$post["codigoUsuario"];

      return $this->constructor->select__general__incentivo("SELECT estadoCalificacion FROM proyecto_enviado WHERE codigoUsuario='$codigoUsuario';");

    } 

    public function estado__calificacion__general__idProyectoEnviado($idEnviado) {

      return $this->constructor->select__general__incentivo("SELECT estadoCalificacion,codigoUsuario,codigo FROM proyecto_enviado WHERE id='$idEnviado';");

    } 


}
