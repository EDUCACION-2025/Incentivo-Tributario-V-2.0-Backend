<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Presentation\Pdf\InformeC;
use App\Presentation\Pdf\Base;
use App\Presentation\Controllers\ControllersBandeja;

class ControllersReporte {

    private $fecha;
    private $hora;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->constructor__basePdf = Base::getInstance();

        $this->constructor = ServicesAdmin::getInstance();
        $this->informePdf = InformeC::getInstance();
        $this->bandeja = new ControllersBandeja();

        $this->baseServidorFtp='/home/incentivoTributario__firma/';

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

    }



    public function obtener__fisicamente__estructura__reportes($idUsuario){

        $consulta=$this->constructor->select__general__talento("SELECT fisicamenteEstructura FROM th_usuario WHERE id_usuario='$idUsuario';");
        foreach ($consulta as $valor) {
            $fisicamenteEstructura=$valor["fisicamenteEstructura"];
        }

        return $fisicamenteEstructura;

    }

    public function obtener__id__usuario__reportes($idCredencial){

        $consulta=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
            $idUsuario=$valor["idUsuario"];
        }

        return $idUsuario;

    }


    public function obtener__id__enviado__reportes($codigo){

        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_enviado WHERE codigo='$codigo';");
        foreach ($consulta as $valor) {
            $id=$valor["id"];
        }

        return $id;

    }



    public function enviar__informacion__asignacion($post){

        $idUsuarioActual=$this->obtener__id__usuario__reportes($post["idUsuario"]);


        $fisicamenteActual=$this->obtener__fisicamente__estructura__reportes($idUsuarioActual);
        $fisicamenteNuevo=$this->obtener__fisicamente__estructura__reportes($post["valorSeleccionado"]);
        $idEnviado=$this->obtener__id__enviado__reportes($post["codigo"]);

        $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','textoDevuelto','tipo', 'fecha', 'hora'], array(
          ':idFisicamenteActual' =>$fisicamenteActual,
          ':idUsuarioActual' => $idUsuarioActual,
          ':idFisicamenteNuevo' => $fisicamenteNuevo,
          ':idUsuarioNuevo' => $post["valorSeleccionado"],
          ':idEnviado' => $idEnviado,
          ':textoDevuelto' => $post["textoSeleccionado"],
          ':tipo' =>'Reasignado desde el administrador',
          ':fecha' => $this->fecha,
          ':hora' => $this->hora,
        ));

        $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idUsuario='".$post["valorSeleccionado"]."',estadoCalificacion=NULL WHERE codigo='".$post["codigo"]."';");

        return 1;


    }


    public function recuperar__areas__tecnicas($areaTecnica1){

        // return $this->constructor->select__general__talento("SELECT id_usuario,CONCAT_WS(' ', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto FROM th_usuario WHERE fisicamenteEstructura='$areaTecnica1';");

         return $this->constructor->select__general__talento("SELECT a.id_usuario,CONCAT_WS(' ', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto FROM th_usuario AS a INNER JOIN th_usuario_roles AS b ON a.id_usuario=b.id_usuario WHERE b.id_rol='3' OR b.id_rol='13' AND a.estadoUsuario='A' GROUP BY a.id_usuario ORDER BY a.apellido;");

    }


    public function recuperar__tramite__seguimiento__v1($idUsuario){

        return $this->constructor->select__general__incentivo("SELECT a.codigo,(SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') FROM ezonshar_mdepsaddb.pro_proyecto AS a1 WHERE a1.codigo=a.codigo) AS nombreProyecto,a.fecha,a.hora,a.declaracionJuramentada,a.fotografias,a.memorias,a.certificaciones,a.otros,a.informeFinalCumplimiento, IFNULL((SELECT CONCAT_WS(' ','SI') FROM configuracion.credencial_reporteria AS a1 WHERE a1.idCredencial='$idUsuario' AND a1.modulo='seguimientoV1'),'NO') AS denominacion,a.estado,IF(a.estado='T','FINALIZADO','PENDIENTE') AS estadoLetras, IFNULL((SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a2.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM configuracion.funcionario AS a1 INNER JOIN ezonshar_mdepsaddb.th_usuario AS a2 ON a2.id_usuario=a1.idUsuario WHERE a1.idCredencial=a.idAnalista),'N/A') AS analista FROM proyecto_seguimiento_documentos_v1 AS a WHERE (a.estado='E' OR a.estado='T') GROUP BY a.codigo;");

    }


    public function obtener__existente__componente($codigoUsuario){

        $consulta=$this->constructor->select__general__incentivo("SELECT id FROM proyecto_sector WHERE codigo='$codigoUsuario';");
        foreach ($consulta as $valor) {
            $id=$valor["id"];
        }

        if (!empty($id)) {
           return "si";
        }else{
            return "no";
        }

    }

    public function obtener__ultimo__antecedente($idEnviado){
        return $this->constructor->select__general__incentivo("SELECT id,idFisicamenteActual,idUsuarioActual,idFisicamenteNuevo,idUsuarioNuevo,idEnviado,textoDevuelto,tipo,fecha,hora,observacionEnvio,idSolicitud,idFactura FROM proyecto_enviado_antecedente WHERE idEnviado='$idEnviado' ORDER BY id DESC;");
    }

    public function recuperar__tramite__calificacion($post){

        $idEnviado=$post["idEnviado"];

        if (!empty($idEnviado)) {

            $codigoUsuario=$post["codigoUsuario"];
            $codigo=$post["codigo"];
            $fisicamenteEstructura=$post["fisicamenteEstructura"];
            $idCredencial=$post["idCredencial"];

            $sector=$this->obtener__existente__componente($codigoUsuario);
            $idUsuario=$this->obtener__idUsuario__idCredencial($idCredencial);

            if(intval($fisicamenteEstructura)===15 && $sector==="si"){
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idUsuario=0, idUsuarioRecomiendaCalificacion=0, estadoCalificacion='ENVIADO INFRA' WHERE id='$idEnviado';");
            }else{
                $this->constructor->actualiza__general__incentivo("UPDATE proyecto_enviado SET idUsuario=NULL, idUsuarioRecomiendaCalificacion=NULL WHERE id='$idEnviado';");
            }

            foreach ($this->obtener__ultimo__antecedente($idEnviado) as $valor) {
                return $this->constructor->inserta__general__incentivo("proyecto_enviado_antecedente", ['idFisicamenteActual','idUsuarioActual','idFisicamenteNuevo','idUsuarioNuevo','idEnviado','tipo', 'fecha', 'hora'], array(
                  ':idFisicamenteActual' =>$valor["idFisicamenteNuevo"],
                  ':idUsuarioActual' => $valor["idUsuarioNuevo"],
                  ':idFisicamenteNuevo' => $fisicamenteEstructura,
                  ':idUsuarioNuevo' => $idUsuario,
                  ':idEnviado' => $idEnviado,
                  ':tipo' =>'Recuperado por superior inmediato en etapa de calificación',
                  ':fecha' => $this->fecha,
                  ':hora' => $this->hora,
                ));
            }

        }

    }

    public function obtener__informe__modificacion__infraestructura($post){

        $array=array();

        $idEnviado=$post["idEnviado"];
        $codigo=$post["codigoUsuario"];
        $codigoProyecto=$post["codigo"];

        $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario, IFNULL(idUsuario2,0) AS idUsuario2,recomendacion,texto,idSolicitud FROM proyecto_enviado_recomendacion_infraestructura_modificacion WHERE idEnviado='$idEnviado' AND tipo='MODIFICACION' ORDER BY id DESC LIMIT 1;");

        foreach ($consulta as $valor) {

          $informacionUsuario=$this->bandeja->obtener__usuario($valor["idUsuario"]);
          $informacionUsuario__analista=$this->bandeja->obtener__usuario($valor["idUsuario"]);
          $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($valor["idUsuario2"]);
          $informacionUsuario__superiorInmediatoFinal=$this->bandeja->obtener__usuario($informacionUsuario__superiorInmediato[3]);


          $casoModificarBd=$this->codigo__modificacion($valor["idSolicitud"]);

          if ($casoModificarBd==="a" || $casoModificarBd==="c") {
        
            $contenido=$this->informePdf->portada__informe__notificacion($codigo,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->portada__informe__antecedente($codigo,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->base__legal__notifiacion($codigo,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->notificacion_legal_notificacion__caso__a__c($codigo,$informacionUsuario__superiorInmediatoFinal[1],$valor["recomendacion"],$valor["texto"]);

            if (intval($valor["idUsuario2"])===0) {
                $informacionUsuario__superiorInmediato=[];
            }

            $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

          }else{

            $contenido=$this->informePdf->portada__informe__modificacion($codigoProyecto,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->datos__generales__modificado($codigo,$valor["idSolicitud"]);
            $contenido.=$this->informePdf->presupuesto__modificacion($codigo,$valor["idSolicitud"]);
            $contenido.=$this->informePdf->analisisTecnico__modificacion($codigo,$informacionUsuario,$valor["recomendacion"],$valor["texto"]);

            if (intval($valor["idUsuario2"])===0) {
                $informacionUsuario__superiorInmediato=[];
            }

            $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

          }

          $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

          array_push($array, $pdfResult);

        }
        
        if(count($array)===0){
            return "no";
        }else{
            return $array;
        }

    }

    public function obtener__informe__modificacion($post){

        $array=array();

        $idEnviado=$post["idEnviado"];
        $codigo=$post["codigoUsuario"];
        $codigoProyecto=$post["codigo"];

        $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario, IFNULL(idUsuario2,0) AS idUsuario2,recomendacion,texto,idSolicitud FROM proyecto_enviado_recomendacion_modificacion WHERE idEnviado='$idEnviado' AND tipo='MODIFICACION' ORDER BY id DESC LIMIT 1;");

        foreach ($consulta as $valor) {

          $informacionUsuario=$this->bandeja->obtener__usuario($valor["idUsuario"]);
          $informacionUsuario__analista=$this->bandeja->obtener__usuario($valor["idUsuario"]);
          $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($valor["idUsuario2"]);
          $informacionUsuario__superiorInmediatoFinal=$this->bandeja->obtener__usuario($informacionUsuario__superiorInmediato[3]);


          $casoModificarBd=$this->codigo__modificacion($valor["idSolicitud"]);

          if ($casoModificarBd==="a" || $casoModificarBd==="c") {
        
            $contenido=$this->informePdf->portada__informe__notificacion($codigo,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->portada__informe__antecedente($codigo,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->base__legal__notifiacion($codigo,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->notificacion_legal_notificacion__caso__a__c($codigo,$informacionUsuario__superiorInmediatoFinal[1],$valor["recomendacion"],$valor["texto"]);

            if (intval($valor["idUsuario2"])===0) {
                $informacionUsuario__superiorInmediato=[];
            }

            $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

          }else{

            $contenido=$this->informePdf->portada__informe__modificacion($codigoProyecto,$informacionUsuario__superiorInmediato[1]);
            $contenido.=$this->informePdf->datos__generales__modificado($codigo,$valor["idSolicitud"]);
            $contenido.=$this->informePdf->presupuesto__modificacion($codigo,$valor["idSolicitud"]);
            $contenido.=$this->informePdf->analisisTecnico__modificacion($codigo,$informacionUsuario,$valor["recomendacion"],$valor["texto"]);

            if (intval($valor["idUsuario2"])===0) {
                $informacionUsuario__superiorInmediato=[];
            }

            $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

          }

          $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

          array_push($array, $pdfResult);

        }
        
        if(count($array)===0){
            return "no";
        }else{
            return $array;
        }

    }

    public function obtener__informe__calificacion__infraestructura($post){

        $idEnviado=$post["idEnviado"];
        $codigo=$post["codigoUsuario"];
        $codigoProyecto=$post["codigo"];

        $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario, IFNULL(idUsuario2,0) AS idUsuario2,recomendacion,texto FROM proyecto_enviado_recomendacion_infraestructura WHERE idEnviado='$idEnviado' AND tipo='CALIFICACION' ORDER BY id DESC LIMIT 1;");

        foreach ($consulta as $valor) {
            $idUsuario=$valor["idUsuario"];
            $idUsuario2=$valor["idUsuario2"];
            $recomendacion=$valor["recomendacion"];
            $texto=$valor["texto"];
        }


        if(empty($idUsuario)){

            return 'no';

        }else{

            $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
            $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($idUsuario2);
            $informacionUsuario__superiorInmediatoFinal=$this->bandeja->obtener__usuario($informacionUsuario__superiorInmediato[3]);

            $contenido=$this->informePdf->portada__informe($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
            $contenido.=$this->informePdf->datos__generales($codigo);
            $contenido.=$this->informePdf->requisitos($codigo);
            $contenido.=$this->informePdf->resumenProyecto($codigo);
            $contenido.=$this->informePdf->presupuesto($codigo);
            $contenido.=$this->informePdf->analisisTecnico($codigo,$informacionUsuario,$recomendacion,$texto);

           if (intval($idUsuario2)===0) {
                $informacionUsuario__superiorInmediato=[];
            }

            $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

            $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

            return $pdfResult;

        }

    }


    public function obtener__informe__calificacion($post){

        $idEnviado=$post["idEnviado"];
        $codigo=$post["codigoUsuario"];
        $codigoProyecto=$post["codigo"];

        $consulta=$this->constructor->select__general__incentivo("SELECT idUsuario, IFNULL(idUsuario2,0) AS idUsuario2,recomendacion,texto FROM proyecto_enviado_recomendacion WHERE idEnviado='$idEnviado' AND tipo='CALIFICACION' ORDER BY id DESC LIMIT 1;");

        foreach ($consulta as $valor) {
            $idUsuario=$valor["idUsuario"];
            $idUsuario2=$valor["idUsuario2"];
            $recomendacion=$valor["recomendacion"];
            $texto=$valor["texto"];
        }


        if(empty($idUsuario)){

            return 'no';

        }else{

            $informacionUsuario=$this->bandeja->obtener__usuario($idUsuario);
            $informacionUsuario__superiorInmediato=$this->bandeja->obtener__usuario($idUsuario2);
            $informacionUsuario__superiorInmediatoFinal=$this->bandeja->obtener__usuario($informacionUsuario__superiorInmediato[3]);

            $contenido=$this->informePdf->portada__informe($codigoProyecto,$informacionUsuario__superiorInmediatoFinal[1]);
            $contenido.=$this->informePdf->datos__generales($codigo);
            $contenido.=$this->informePdf->requisitos($codigo);
            $contenido.=$this->informePdf->resumenProyecto($codigo);
            $contenido.=$this->informePdf->presupuesto($codigo);
            $contenido.=$this->informePdf->analisisTecnico($codigo,$informacionUsuario,$recomendacion,$texto);

           if (intval($idUsuario2)===0) {
                $informacionUsuario__superiorInmediato=[];
            }

            $contenido.=$this->informePdf->pieDeFirma($informacionUsuario,$informacionUsuario__superiorInmediato);

            $pdfResult = $this->constructor__basePdf->generatePdf__sn($contenido, $codigo);

            return $pdfResult;

        }

    }

    public function obtener__transaccionalidad__reporterias__certificacion($idEnviado){

        return $this->constructor->select__general__incentivo("SELECT (SELECT a1.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a1 WHERE a.idFisicamenteActual=a1.id_FisicamenteEstructura) AS direccionOrigen,(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idUsuarioActual) AS usuarioOrigen,(SELECT a1.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a1 WHERE a.idFisicamenteNuevo=a1.id_FisicamenteEstructura) AS direccionDestino,(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idUsuarioNuevo) AS usuarioDestino,a.fecha,a.hora,UPPER(a.tipo) AS tipo, IF(a.tipo LIKE '%comité%' OR tipo LIKE '%PRIORIZACIÓN%','si','no') AS comiteAsignado,IF(a.tipo LIKE '%MODIFICACIÓN CALIFICACION%','si',IF(a.tipo LIKE '%MODIFICACIÓN CULMINADA%','culminada','no')) AS modificacionAsignado FROM proyecto_enviado_antecedente AS a WHERE a.idFactura='$idEnviado';");

    }


    public function obtener__transaccionalidad__reporterias($idEnviado){

        return $this->constructor->select__general__incentivo("SELECT (SELECT a1.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a1 WHERE a.idFisicamenteActual=a1.id_FisicamenteEstructura) AS direccionOrigen,(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idUsuarioActual) AS usuarioOrigen,(SELECT a1.descripcionFisicamenteEstructura FROM ezonshar_mdepsaddb.th_fisicamenteestructura AS a1 WHERE a.idFisicamenteNuevo=a1.id_FisicamenteEstructura) AS direccionDestino,(SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a1.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) FROM ezonshar_mdepsaddb.th_usuario AS a1 WHERE a1.id_usuario=a.idUsuarioNuevo) AS usuarioDestino,a.fecha,a.hora,UPPER(a.tipo) AS tipo, IF(a.tipo LIKE '%comité%' OR tipo LIKE '%PRIORIZACIÓN%','si','no') AS comiteAsignado,IF(a.tipo LIKE '%MODIFICACIÓN CALIFICACION%','si',IF(a.tipo LIKE '%MODIFICACIÓN CULMINADA%','culminada','no')) AS modificacionAsignado FROM proyecto_enviado_antecedente AS a WHERE a.idEnviado='$idEnviado';");

    }


    public function recuperar__reporteria__asignada__calificacion__idCredencial($idCredencial){

        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='calificacion';");

    }

    public function recuperar__reporteria__asignada__certificacion__idCredencial($idCredencial){

        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='certificacion';");

    }

    public function recuperar__reporteria__asignada__seguimiento__idCredencial($idCredencial){

        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='seguimiento';");

    }

    public function recuperar__reporteria__asignada__seguimiento__idCredencial__v1($idCredencial){

        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='seguimientoV1';");

    }


    public function recuperar__reporteria__asignada__calificacion($id){

        $idCredencial=$this->obtener__idCredencial__idUsuario($id);
        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='calificacion';");

    }

    public function recuperar__reporteria__asignada__certificacion($id){

        $idCredencial=$this->obtener__idCredencial__idUsuario($id);
        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='certificacion';");

    }

    public function recuperar__reporteria__asignada__seguimiento($id){

        $idCredencial=$this->obtener__idCredencial__idUsuario($id);
        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='seguimiento';");

    }

    public function recuperar__reporteria__asignada__seguimiento__v1($id){

        $idCredencial=$this->obtener__idCredencial__idUsuario($id);
        return $this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='seguimientoV1';");

    }


    public function obtener__idUsuario__idCredencial($idCredencial){

        $consulta=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
            $idUsuario=$valor["idUsuario"];
        }

        return $idUsuario;

    }

    public function obtener__idCredencial__idUsuario($idUsuario){

        $consulta=$this->constructor->select__general("SELECT idCredencial FROM funcionario WHERE idUsuario='$idUsuario';");
        foreach ($consulta as $valor) {
            $idCredencial=$valor["idCredencial"];
        }

        return $idCredencial;

    }

    public function obtener__existencia__modulo__reporteria($idCredencial,$modulo){

        $consulta=$this->constructor->select__general("SELECT id FROM credencial_reporteria WHERE idCredencial='$idCredencial' AND modulo='$modulo';");
        foreach ($consulta as $valor) {
            $idBd=$valor["id"];
        }

        if (empty($idBd)) {
            return 0;
        }else{
            return 1;
        }

    }

    public function actualizar__reporteria__usuario($post){

        $modulo=$post["modulo"];
        $permiso=$post["permiso"];
        $idCredencial=$post["idCredencial"];
        $idCreador=$post["idCreador"];

        $idCredencialInsertar=$this->obtener__idCredencial__idUsuario($idCredencial);


        if(intval($this->obtener__existencia__modulo__reporteria($idCredencialInsertar,$modulo))===1){

            $consulta=$this->constructor->select__general("SELECT idCredencial,idCreador,modulo,permiso,fecha,hora FROM credencial_reporteria WHERE idCredencial='$idCredencialInsertar' AND modulo='$modulo';");
            foreach ($consulta as $valor) {

                $this->constructor->inserta__general__incentivo("incentivorespaldo.credencial_reporteria", ['idCredencial','idCreador','modulo','permiso','fecha','hora'], array(
                    ':idCredencial' =>$valor["idCredencial"],
                    ':idCreador' =>$valor["idCreador"],
                    ':modulo' =>$valor["modulo"],
                    ':permiso' =>$valor["permiso"],
                    ':fecha' =>  $valor["fecha"],
                    ':hora' =>  $valor["hora"],
                ));

            }

            $this->constructor->actualiza__general("DELETE FROM credencial_reporteria WHERE idCredencial='$idCredencialInsertar' AND modulo='$modulo';");

        }



        return $this->constructor->inserta__general("credencial_reporteria", ['idCredencial','idCreador','modulo','permiso','fecha','hora'], array(
            ':idCredencial' =>$idCredencialInsertar,
            ':idCreador' =>$idCreador,
            ':modulo' =>$modulo,
            ':permiso' =>$permiso,
            ':fecha' =>  $this->fecha,
            ':hora' =>  $this->hora,
        ));

    }    

    public function observable__obtener__documento__modificacion($codigo) {

        $array=array();

        $consulta=$this->constructor->select__general__incentivo("SELECT idSolicitud,codigo FROM proyecto_modificacion_solicitud WHERE codigo='$codigo';");

        foreach ($consulta as $valor) {

            $remote_file = $this->baseServidorFtp.'notificacionModificacion'.'/'.$valor["codigo"]."__".$valor["idSolicitud"]."__notificacionModi.pdf";
            $local_file =$valor["codigo"]."__".$valor["idSolicitud"]."__notificacionModi.pdf";
            $base64=$this->constructor->sftp__servicios($remote_file,$local_file);
            array_push($array, $base64);

        }

       return $array;

    }

    public function obtenerIdSector($fisicamenteEstructura){

        switch ($fisicamenteEstructura) {

            case '24':
                return "(e.idSector='1' OR e.idSector='2' OR e.idSector='3')";
            break;

            case '14':
                return "(e.idSector='1')";
            break;
            
            case '12':
                return "(e.idSector='2' OR e.idSector='3')";
            break;

            case '26':
                return "(e.idSector='4' OR e.idSector='5')";
            break;

            case '13':
                return "(e.idSector='4')";
            break;

            case '19':
                return "(e.idSector='5')";
            break;

        }

    }

    public function reporte__calificacion__subsess($idRol,$fisicamenteEstructura){


        $idSector=$this->obtenerIdSector($fisicamenteEstructura);


        if(intval($fisicamenteEstructura)===15 || intval($fisicamenteEstructura)===1){

            return $this->constructor->select__general__incentivo("select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,if((select `a1`.`idRepresentante` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1) is not null,(select `a1`.`nombre` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1),'N/A') AS `representanteLegal`,(select ucase(`a1`.`nombre`) from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `nombreProyecto`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1),'N/A') AS `sector2`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a1`.`idSector` = 1,'DIRECCION DE DEPORTE FORMATIVO',if(`a1`.`idSector` = 2 or `a1`.`idSector` = 3,'DIRECCION DE DEPORTE DE ALTO RENDIMIENTO',if(`a1`.`idSector` = '4','DIRECCION DE DEPORTE FORMATIVO','DIRECCION DE RECREACION'))) from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1),'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA') AS `areaTecnica1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA','N/A') AS `areaTecnica2`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoVigente`,round((select sum(`a1`.`total`) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null group by `a1`.`codigo` limit 1),2) AS `montoTotalCalificado`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,IFNULL(`d`.`fechaCalifica`,'N/A') AS `fechaCalifica`,month(curdate()) AS `mes`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,d.fecha,IFNULL((SELECT CONCAT_WS(' ','SI') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),'NO') AS modificacion,IFNULL((SELECT CONCAT_WS(' ','MODIFICADO') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),IF(d.estadoCalificacion='CALIFICADO','CALIFICADO',IF(d.estadoCalificacion='baja','BAJA',IF(d.estadoCalificacion='observado','OBSERVADO',IF(d.estadoCalificacion='NEGADO','NEGADO',IF(d.estadoCalificacion='ENVIADO INFRA','ENVIADO A INFRAESTRUCTURA',IF(d.estadoCalificacion='rectificado','RECTIFICADO',IFNULL((SELECT CONCAT_WS(' ','ASIGNADO') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=d.id LIMIT 1),'PENDIENTE ASIGNAR')))))))) AS estado,d.id AS idEnviado,d.codigoUsuario,IF(e.idSector IS NULL,IF(d.idUsuario IS NOT NULL AND d.idUsuarioRecomiendaCalificacion IS NULL,'si','no'),IF(EXISTS(SELECT a1.id FROM proyecto_enviado_recomendacion AS a1 WHERE a1.idEnviado=d.id AND a1.idUsuario2 IS NOT NULL ORDER BY a1.id LIMIT 1) AND d.idUsuario IS NOT NULL AND d.idUsuarioRecomiendaCalificacion IS NULL ,'si','no')) AS calificacionReasignar,IF(e.idSector IS NULL,IF(d.estadoCalificacion='CALIFICADO' AND d.estadoSeguimiento IS NULL AND d.idUsuarioModificacionRecomienda IS NULL AND EXISTS (SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=d.codigoUsuario AND a1.estadoModificacion='ENVIADO' LIMIT 1),'si','no'),IF(EXISTS(SELECT a1.id FROM proyecto_enviado_recomendacion_modificacion AS a1 WHERE a1.idEnviado=d.id AND a1.idUsuario2 IS NOT NULL ORDER BY a1.id LIMIT 1) AND  NOT EXISTS(SELECT a1.id FROM proyecto_enviado_recomendacion_infraestructura_modificacion AS a1 WHERE a1.idEnviado=d.id ORDER BY a1.id LIMIT 1) AND d.estadoCalificacion='CALIFICADO' AND d.estadoSeguimiento IS NULL AND d.idUsuarioModificacionRecomienda IS NULL AND EXISTS (SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=d.codigoUsuario AND a1.estadoModificacion='ENVIADO' LIMIT 1),'si','no')) AS modificacionReasignar from (((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) LEFT JOIN proyecto_sector AS e ON e.codigo=d.codigoUsuario INNER JOIN proyecto_componente_usuario AS f ON f.codigo=d.codigoUsuario WHERE  (f.idComponentes='5' OR f.idComponentes='7') AND d.codigo<>'041-01-PIT-2025-15-1' AND d.codigo<>'041-01-PIT-2025-17-1' AND d.codigo<>'041-01-PIT-2025-21-1' AND d.codigo<>'041-01-PIT-2025-1-1' AND d.codigo<>'041-01-PIT-2025-2-1' group by `d`.`codigo`;");
        }else{


            return $this->constructor->select__general__incentivo("select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,if((select `a1`.`idRepresentante` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1) is not null,(select `a1`.`nombre` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1),'N/A') AS `representanteLegal`,(select ucase(`a1`.`nombre`) from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `nombreProyecto`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1),'N/A') AS `sector2`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a1`.`idSector` = 1,'DIRECCION DE DEPORTE FORMATIVO',if(`a1`.`idSector` = 2 or `a1`.`idSector` = 3,'DIRECCION DE DEPORTE DE ALTO RENDIMIENTO',if(`a1`.`idSector` = '4','DIRECCION DE DEPORTE FORMATIVO','DIRECCION DE RECREACION'))) from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1),'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA') AS `areaTecnica1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA','N/A') AS `areaTecnica2`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoVigente`,round((select sum(`a1`.`total`) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null group by `a1`.`codigo` limit 1),2) AS `montoTotalCalificado`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,IFNULL(`d`.`fechaCalifica`,'N/A') AS `fechaCalifica`,month(curdate()) AS `mes`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,d.fecha,IFNULL((SELECT CONCAT_WS(' ','SI') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),'NO') AS modificacion,IFNULL((SELECT CONCAT_WS(' ','MODIFICADO') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),IF(d.estadoCalificacion='CALIFICADO','CALIFICADO',IF(d.estadoCalificacion='baja','BAJA',IF(d.estadoCalificacion='observado','OBSERVADO',IF(d.estadoCalificacion='NEGADO','NEGADO',IF(d.estadoCalificacion='ENVIADO INFRA','ENVIADO A INFRAESTRUCTURA',IF(d.estadoCalificacion='rectificado','RECTIFICADO',IFNULL((SELECT CONCAT_WS(' ','ASIGNADO') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=d.id LIMIT 1),'PENDIENTE ASIGNAR')))))))) AS estado,d.id AS idEnviado,d.codigoUsuario, IF(d.estadoCalificacion='Devuelto' OR (d.idUsuario IS NOT NULL AND d.idUsuarioRecomiendaCalificacion IS NULL AND d.estadoCalificacion<>'baja'),'si','no') AS calificacionReasignar,IF(d.estadoCalificacion='CALIFICADO'  AND d.estadoSeguimiento IS NULL AND d.idUsuarioModificacionRecomienda IS NULL AND EXISTS (SELECT a1.idSolicitud FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=d.codigoUsuario AND a1.estadoModificacion='ENVIADO' LIMIT 1),'si','no') AS modificacionReasignar from (((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) INNER JOIN proyecto_sector AS e ON e.codigo=d.codigoUsuario INNER JOIN proyecto_componente_usuario AS f ON f.codigo=d.codigoUsuario WHERE $idSector AND d.codigo<>'041-01-PIT-2025-15-1' AND d.codigo<>'041-01-PIT-2025-17-1' AND d.codigo<>'041-01-PIT-2025-21-1' AND d.codigo<>'041-01-PIT-2025-1-1' AND d.codigo<>'041-01-PIT-2025-2-1'  group by `d`.`codigo`;");
        }


    }    

    public function reporte__calificacion__asignar(){

      return $this->constructor->select__general__incentivo("select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,if((select `a1`.`idRepresentante` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1) is not null,(select `a1`.`nombre` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1),'N/A') AS `representanteLegal`,(select ucase(`a1`.`nombre`) from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `nombreProyecto`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1),'N/A') AS `sector2`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a1`.`idSector` = 1,14,if(`a1`.`idSector` = 2 or `a1`.`idSector` = 3,12,if(`a1`.`idSector` = '4',13,19))) from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1),15) AS `areaTecnica1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA','N/A') AS `areaTecnica2`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoVigente`,round((select sum(`a1`.`total`) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null group by `a1`.`codigo` limit 1),2) AS `montoTotalCalificado`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,IFNULL(`d`.`fechaCalifica`,'N/A') AS `fechaCalifica`,month(curdate()) AS `mes`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,d.fecha,IFNULL((SELECT CONCAT_WS(' ','SI') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),'NO') AS modificacion,IFNULL((SELECT CONCAT_WS(' ','MODIFICADO') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),IF(d.estadoCalificacion='CALIFICADO','CALIFICADO',IF(d.estadoCalificacion='baja','BAJA',IF(d.estadoCalificacion='observado','OBSERVADO',IF(d.estadoCalificacion='NEGADO','NEGADO',IF(d.estadoCalificacion='ENVIADO INFRA','ENVIADO A INFRAESTRUCTURA',IF(d.estadoCalificacion='rectificado','RECTIFICADO',IFNULL((SELECT CONCAT_WS(' ','ASIGNADO') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=d.id LIMIT 1),'PENDIENTE ASIGNAR')))))))) AS estado,d.id AS idEnviado,d.codigoUsuario from (((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) WHERE ((d.estadoCalificacion<>'estadoCalificacion') OR d.estadoCalificacion IS NULL) AND  d.codigo<>'041-01-PIT-2025-15-1' AND d.codigo<>'041-01-PIT-2025-17-1' AND d.codigo<>'041-01-PIT-2025-21-1' AND d.codigo<>'041-01-PIT-2025-1-1' AND d.codigo<>'041-01-PIT-2025-2-1'  group by `d`.`codigo`;");

    }    

    public function reporte__calificacion(){

      return $this->constructor->select__general__incentivo("select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,if((select `a1`.`idRepresentante` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1) is not null,(select `a1`.`nombre` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1),'N/A') AS `representanteLegal`,(select ucase(`a1`.`nombre`) from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `nombreProyecto`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1),'N/A') AS `sector2`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a1`.`idSector` = 1,'DIRECCION DE DEPORTE FORMATIVO',if(`a1`.`idSector` = 2 or `a1`.`idSector` = 3,'DIRECCION DE DEPORTE DE ALTO RENDIMIENTO',if(`a1`.`idSector` = '4','DIRECCION DE DEPORTE FORMATIVO','DIRECCION DE RECREACION'))) from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1),'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA') AS `areaTecnica1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA','N/A') AS `areaTecnica2`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoVigente`,round((select sum(`a1`.`total`) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null group by `a1`.`codigo` limit 1),2) AS `montoTotalCalificado`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,IFNULL(`d`.`fechaCalifica`,'N/A') AS `fechaCalifica`,month(curdate()) AS `mes`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,d.fecha,IFNULL((SELECT CONCAT_WS(' ','SI') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),'NO') AS modificacion,IFNULL((SELECT CONCAT_WS(' ','MODIFICADO') FROM proyecto_modificacion_solicitud AS a1 WHERE a1.codigo=c.codigo AND a1.estadoModificacion IS NOT NULL AND a1.estadoModificacion='APROBADO' GROUP BY a1.codigo),IF(d.estadoCalificacion='CALIFICADO','CALIFICADO',IF(d.estadoCalificacion='baja','BAJA',IF(d.estadoCalificacion='observado','OBSERVADO',IF(d.estadoCalificacion='NEGADO','NEGADO',IF(d.estadoCalificacion='ENVIADO INFRA','ENVIADO A INFRAESTRUCTURA',IF(d.estadoCalificacion='rectificado','RECTIFICADO',IFNULL((SELECT CONCAT_WS(' ','ASIGNADO') FROM proyecto_enviado_antecedente AS a1 WHERE a1.idEnviado=d.id LIMIT 1),'PENDIENTE ASIGNAR')))))))) AS estado,d.id AS idEnviado,d.codigoUsuario from (((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) WHERE d.codigo<>'041-01-PIT-2025-15-1' AND d.codigo<>'041-01-PIT-2025-17-1' AND d.codigo<>'041-01-PIT-2025-21-1' AND d.codigo<>'041-01-PIT-2025-1-1' AND d.codigo<>'041-01-PIT-2025-2-1' group by `d`.`codigo`;");

    }    

    public function reporteria__certificacion($cargarData){

        if($cargarData==="UNIT" || $cargarData==="SUBSESS"){

            return $this->constructor->select__general__incentivo("(select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a2`.`idSector` = '1' or `a2`.`idSector` = '2' or `a2`.`idSector` = '3',ucase('Mantener la participacion de los deportistas ecuatorianos, en competencias nacionales e internacionales'),ucase(' Incrementar los beneficiarios de los servicios de actividad fisica que promueve el Ministerio del Deporte  ')) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),'Incrementar la infraestructura deportiva con condiciones optimas a nivel nacional') AS `alineacionEstrategica`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoAnioVigente`,`f`.`ruc` AS `ruc`,`f`.`razonSocial` AS `nombrePatrocinador`,`e`.`numeroFactura` AS `nroComprobante`,`e`.`fechaEmision` AS `fechaEmision`,round(`e`.`subotal`,2) AS `montoCertificado`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,if((select `a2`.`fecha` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`fecha` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `fechaActaComite`,(SELECT a1.nombre FROM proyecto_descripcion AS a1 WHERE a1.codigo=d.codigoUsuario LIMIT 1) AS nombreProyecto,e.fecha,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null  group by `a1`.`codigo` limit 1),0),2) AS `montoCalificado`,e.idIncremental, IF(e.estadoRevision='1' AND e.estadoRecomendacion='1','RECOMENDADO COMITÉ',IF(e.estado='P','PENDIENTE',IF(e.estado='A','CERTIFICADO','NEGADO'))) AS estado ,e.id,(SELECT a1.documento FROM comite_proyectos AS a1 WHERE a1.idComite=g.idComite AND a1.modulo='CERTIFICACION' LIMIT 1) AS documentoNotificacion,d.codigoUsuario,(SELECT a1.documento FROM proyecto_certificacion_plurianual AS a1 WHERE a1.codigo=d.codigoUsuario AND YEAR(a1.fecha)=YEAR(e.fecha) LIMIT 1) AS documentoContinuidad from (((((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) join `incentivo`.`proyecto_certificacion_factura_tramite` `e` on(`e`.`codigo` = `c`.`codigo`)) join `incentivo`.`certificacion_patrocinadores` `f` on(`f`.`id` = `e`.`idPatrocinador`)) LEFT JOIN proyecto_certificacion_factura_comite AS g ON g.idFactura=e.id) UNION (SELECT row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,(SELECT STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y') FROM ezonshar_mdepsaddb.pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=c.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaInicio,(SELECT STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y') FROM ezonshar_mdepsaddb.pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=c.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaFin, IF(c.tipoDeportistas = 'alto' OR c.tipoDeportistas = 'alto2' OR c.tipoDeportistas = 'altoRendimiento' OR c.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(c.tipoDeportistas = 'actividadFisica', UPPER('Educacion Fisica'), IF(c.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(c.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreacion'))))) AS sector,IF(c.tipoDeportistas = 'alto' OR c.tipoDeportistas = 'alto2' OR c.tipoDeportistas = 'altoRendimiento' OR c.tipoDeportistas = 'altoRendimientoDiscapacidad',UPPER('Mantener la participacion de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad fisica que promueve el Ministerio del Deporte  ')) AS alineacionEstrategica, ROUND(CAST(c.monto AS DOUBLE), 2) AS montoCalificadoAnioVigente,`f`.`ruc` AS `ruc`,`f`.`razonSocial` AS `nombrePatrocinador`,`e`.`numeroFactura` AS `nroComprobante`,`e`.`fechaEmision` AS `fechaEmision`,round(`e`.`subotal`,2) AS `montoCertificado`, IF(c.tipoDeportistas = 'alto' OR c.tipoDeportistas = 'alto2' OR c.tipoDeportistas = 'altoRendimiento' OR c.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(c.tipoDeportistas = 'actividadFisica', UPPER('Educacion Fisica'), IF(c.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(c.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreacion'))))) AS tipoProyecto,CONCAT_WS(' ', 'N/A') AS nroActa,CONCAT_WS(' ', 'N/A') AS fechaActaComite,UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto, e.fecha,ROUND(CAST(c.monto AS DOUBLE), 2) AS montoCalificado,e.idIncremental, IF(e.estadoRevision='1' AND e.estadoRecomendacion='1','RECOMENDADO COMITÉ',IF(e.estado='P','PENDIENTE',IF(e.estado='A','CERTIFICADO','NEGADO'))) AS estado,e.id,(SELECT a1.documento FROM comite_proyectos AS a1 WHERE a1.idComite=g.idComite AND a1.modulo='CERTIFICACION' LIMIT 1) AS documentoNotificacion,d.codigo AS codigoUsuario,(SELECT a1.documento FROM proyecto_certificacion_plurianual AS a1 WHERE a1.codigo=d.codigo AND YEAR(a1.fecha)=YEAR(e.fecha) LIMIT 1) AS documentoContinuidad FROM  configuracion.credencial AS a INNER JOIN configuracion.credencial_tipo AS b ON b.idCredencial=a.idCredencial INNER JOIN proyecto_enviado AS d ON d.idCredencial=a.idCredencial INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=d.codigo INNER JOIN certificacion_patrocinadores AS f ON f.id=e.idPatrocinador  LEFT JOIN proyecto_certificacion_factura_comite AS g ON g.idFactura=e.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON e.codigo=c.codigo WHERE d.codigoUsuario IS NULL);");

        }else{

            return $this->constructor->select__general__incentivo("(select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a2`.`idSector` = '1' or `a2`.`idSector` = '2' or `a2`.`idSector` = '3',ucase('Mantener la participacion de los deportistas ecuatorianos, en competencias nacionales e internacionales'),ucase(' Incrementar los beneficiarios de los servicios de actividad fisica que promueve el Ministerio del Deporte  ')) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),'Incrementar la infraestructura deportiva con condiciones optimas a nivel nacional') AS `alineacionEstrategica`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoAnioVigente`,`f`.`ruc` AS `ruc`,`f`.`razonSocial` AS `nombrePatrocinador`,`e`.`numeroFactura` AS `nroComprobante`,`e`.`fechaEmision` AS `fechaEmision`,round(`e`.`subotal`,2) AS `montoCertificado`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,if((select `a2`.`fecha` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`fecha` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `fechaActaComite`,(SELECT a1.nombre FROM proyecto_descripcion AS a1 WHERE a1.codigo=d.codigoUsuario LIMIT 1) AS nombreProyecto,e.fecha,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null  group by `a1`.`codigo` limit 1),0),2) AS `montoCalificado`,e.idIncremental, IF(e.estadoRevision='1' AND e.estadoRecomendacion='1','RECOMENDADO COMITÉ',IF(e.estado='P','PENDIENTE',IF(e.estado='A','CERTIFICADO','NEGADO'))) AS estado ,e.id,(SELECT a1.documento FROM comite_proyectos AS a1 WHERE a1.idComite=g.idComite AND a1.modulo='CERTIFICACION' LIMIT 1) AS documentoNotificacion,d.codigoUsuario,(SELECT a1.documento FROM proyecto_certificacion_plurianual AS a1 WHERE a1.codigo=d.codigoUsuario AND YEAR(a1.fecha)=YEAR(e.fecha) LIMIT 1) AS documentoContinuidad from (((((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) join `incentivo`.`proyecto_certificacion_factura_tramite` `e` on(`e`.`codigo` = `c`.`codigo`)) join `incentivo`.`certificacion_patrocinadores` `f` on(`f`.`id` = `e`.`idPatrocinador`)) LEFT JOIN proyecto_certificacion_factura_comite AS g ON g.idFactura=e.id WHERE e.estado='A') UNION (SELECT row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,(SELECT STR_TO_DATE(a1.inicioPeriodos, '%d/%m/%Y') FROM ezonshar_mdepsaddb.pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=c.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaInicio,(SELECT STR_TO_DATE(a1.finPeriodos, '%d/%m/%Y') FROM ezonshar_mdepsaddb.pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto=c.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1) AS fechaFin, IF(c.tipoDeportistas = 'alto' OR c.tipoDeportistas = 'alto2' OR c.tipoDeportistas = 'altoRendimiento' OR c.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(c.tipoDeportistas = 'actividadFisica', UPPER('Educacion Fisica'), IF(c.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(c.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreacion'))))) AS sector,IF(c.tipoDeportistas = 'alto' OR c.tipoDeportistas = 'alto2' OR c.tipoDeportistas = 'altoRendimiento' OR c.tipoDeportistas = 'altoRendimientoDiscapacidad',UPPER('Mantener la participacion de los deportistas ecuatorianos, en competencias nacionales e internacionales'),UPPER(' Incrementar los beneficiarios de los servicios de actividad fisica que promueve el Ministerio del Deporte  ')) AS alineacionEstrategica, ROUND(CAST(c.monto AS DOUBLE), 2) AS montoCalificadoAnioVigente,`f`.`ruc` AS `ruc`,`f`.`razonSocial` AS `nombrePatrocinador`,`e`.`numeroFactura` AS `nroComprobante`,`e`.`fechaEmision` AS `fechaEmision`,round(`e`.`subotal`,2) AS `montoCertificado`, IF(c.tipoDeportistas = 'alto' OR c.tipoDeportistas = 'alto2' OR c.tipoDeportistas = 'altoRendimiento' OR c.tipoDeportistas = 'altoRendimientoDiscapacidad', UPPER('Deporte de Alto rendimiento'), IF(c.tipoDeportistas = 'actividadFisica', UPPER('Educacion Fisica'), IF(c.tipoDeportistas = 'formativo', UPPER('Deporte Formativo'), IF(c.tipoDeportistas = 'profesional', UPPER('Deporte profesional'), UPPER('Recreacion'))))) AS tipoProyecto,CONCAT_WS(' ', 'N/A') AS nroActa,CONCAT_WS(' ', 'N/A') AS fechaActaComite,UPPER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreProyecto, e.fecha,ROUND(CAST(c.monto AS DOUBLE), 2) AS montoCalificado,e.idIncremental, IF(e.estadoRevision='1' AND e.estadoRecomendacion='1','RECOMENDADO COMITÉ',IF(e.estado='P','PENDIENTE',IF(e.estado='A','CERTIFICADO','NEGADO'))) AS estado ,e.id,(SELECT a1.documento FROM comite_proyectos AS a1 WHERE a1.idComite=g.idComite AND a1.modulo='CERTIFICACION' LIMIT 1) AS documentoNotificacion,d.codigo AS codigoUsuario,(SELECT a1.documento FROM proyecto_certificacion_plurianual AS a1 WHERE a1.codigo=d.codigo AND YEAR(a1.fecha)=YEAR(e.fecha) LIMIT 1) AS documentoContinuidad FROM  configuracion.credencial AS a INNER JOIN configuracion.credencial_tipo AS b ON b.idCredencial=a.idCredencial INNER JOIN proyecto_enviado AS d ON d.idCredencial=a.idCredencial INNER JOIN proyecto_certificacion_factura_tramite AS e ON e.codigo=d.codigo INNER JOIN certificacion_patrocinadores AS f ON f.id=e.idPatrocinador  LEFT JOIN proyecto_certificacion_factura_comite AS g ON g.idFactura=e.id INNER JOIN ezonshar_mdepsaddb.pro_proyecto AS c ON e.codigo=c.codigo WHERE d.codigoUsuario IS NULL AND e.estado='A');");

        }


    }    


    public function reporte__seguimiento(){

      return $this->constructor->select__general__incentivo("select row_number() over ( order by `a`.`idCredencial`) AS `idSecuencial`,`d`.`codigo` AS `codigo`,(select `a1`.`nombre` from `incentivo`.`tipo_usuario` `a1` where `a1`.`idTipoUsuario` = `b`.`idTipoUsuario` limit 1) AS `tipoUsuario`,if((select `a1`.`idOrganismo` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1) is not null,(select `a1`.`razonSocial` from `configuracion`.`organismo` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1),(select `a1`.`nombre` from `configuracion`.`usuario` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` limit 1)) AS `proponente`,if((select `a1`.`idRepresentante` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1) is not null,(select `a1`.`nombre` from `configuracion`.`representante` `a1` where `a1`.`idCredencial` = `a`.`idCredencial` LIMIT 1),'N/A') AS `representanteLegal`,(select ucase(`a1`.`nombre`) from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `nombreProyecto`,(select `a1`.`fechaInicio` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaInicio`,(select `a1`.`fechaFin` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `c`.`codigo`) AS `fechaFin`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select ucase(`a2`.`nombre`) from (`incentivo`.`proyecto_sector` `a1` join `incentivo`.`sector` `a2` on(`a1`.`idSector` = `a2`.`idSector`)) where `a1`.`codigo` = `c`.`codigo` limit 1),(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1)) AS `sector1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1),'N/A') AS `sector2`,if((select `a1`.`id` from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1) is not null,(select if(`a1`.`idSector` = 1,'DIRECCION DE DEPORTE FORMATIVO',if(`a1`.`idSector` = 2 or `a1`.`idSector` = 3,'DIRECCION DE DEPORTE DE ALTO RENDIMIENTO',if(`a1`.`idSector` = '4','DIRECCION DE DEPORTE FORMATIVO','DIRECCION DE RECREACION'))) from `incentivo`.`proyecto_sector` `a1` where `a1`.`codigo` = `c`.`codigo` limit 1),'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA') AS `areaTecnica1`,if((select `a2`.`nombre` from (`incentivo`.`proyecto_componente_usuario` `a1` join `incentivo`.`componentes` `a2` on(`a1`.`idComponentes` = `a2`.`idComponentes`)) where (`a2`.`idComponentes` = 5 or `a2`.`idComponentes` = 7) and `a1`.`codigo` = `c`.`codigo` limit 1) is not null,'DIRECCION DE GESTION DE INFRAESTRUCTURA DEPORTIVA','N/A') AS `areaTecnica2`,round(ifnull((select ifnull(sum(`a1`.`total`),0) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null and `a1`.`anio` = year(curdate()) group by `a1`.`codigo` limit 1),0),2) AS `montoCalificadoVigente`,round((select sum(`a1`.`total`) from `incentivo`.`proyecto_presupuesto` `a1` where `a1`.`codigo` = `c`.`codigo` and `a1`.`idNivel1` is not null group by `a1`.`codigo` limit 1),2) AS `montoTotalCalificado`,if((select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1) is not null,(select `a2`.`numeroActa` from (`incentivo`.`comite_proyectos_calificadores` `a1` join `incentivo`.`comite_acta` `a2` on(`a2`.`idComite` = `a1`.`idComite`)) where `a1`.`idEnviado` = `d`.`id` order by `a1`.`id` limit 1),'N/A') AS `nroActa`,`d`.`fechaCalifica` AS `fechaCalifica`,month(curdate()) AS `mes`,(select `a1`.`tipo` from `incentivo`.`proyecto_descripcion` `a1` where `a1`.`codigo` = `d`.`codigoUsuario`) AS `tipoProyecto`,d.fecha, d.id AS idEnviado,d.codigoUsuario from (((`configuracion`.`credencial` `a` join `configuracion`.`credencial_tipo` `b` on(`b`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto` `c` on(`c`.`idCredencial` = `a`.`idCredencial`)) join `incentivo`.`proyecto_enviado` `d` on(`d`.`codigoUsuario` = `c`.`codigo`)) where `d`.`estadoCalificacion` = 'CALIFICADO' AND d.estadoSeguimiento IS NOT NULL AND d.estadoSeguimiento!='P' group by `d`.`codigo`;");

    }    

    public function codigo__modificacion($idSolicitud) {

        $consulta=$this->constructor->select__general__incentivo("SELECT casoModificar FROM proyecto_modificacion_solicitud WHERE idSolicitud='$idSolicitud';");
        foreach ($consulta as $valor) {
            $casoModificar=$valor["casoModificar"];
        }
        return $casoModificar;

    } 


}
