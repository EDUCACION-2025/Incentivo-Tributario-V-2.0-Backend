<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\ServicesAdmin;
use App\Application\Auth\Auth;


class ControllersAdmin {

    private $fecha;
    private $hora;

    public function __construct() {

        date_default_timezone_set("America/Guayaquil");

        $this->constructor = ServicesAdmin::getInstance();
        $this->constructor__auth =Auth::getInstance();

        $this->fecha=date('Y-m-d');
        $this->hora=date('H:i:s');

    }

    public function cambiar__estados__del__menu($post) {

      $this->constructor->actualiza__general("UPDATE menu SET estado='".$post["estado"]."' WHERE id='".$post["id"]."';");

      return 1;

    }   


    public function observable__menus__dinamicos($idUsuario) {

        return $this->constructor->select__general("SELECT id,nombre,estado FROM menu;");

    }   

    public function guardar__edicionInformacionUsuario($post){

      $consulta__organismo=$this->constructor->select__general("SELECT idCredencial AS idCredencialOrganismo FROM organismo WHERE idCredencial='".$post['idCredencial']."';");
      foreach ($consulta__organismo as $valor__organismo) {
        $idCredencial__organismo=$valor__organismo["idCredencialOrganismo"];
      }

      $consulta__representante__comparar=$this->constructor->select__general("SELECT idCredencial AS idCredencialRepresentante FROM representante WHERE idCredencial='".$post['idCredencial']."';");
      foreach ($consulta__representante__comparar as $valor__representante__comparar) {
        $idCredencialRepresentante=$valor__representante__comparar["idCredencialRepresentante"];
      }


      $consulta__idUsuarioProponente=$this->constructor->select__general("SELECT idCredencial AS idUsuarioProponente FROM usuario WHERE idCredencial='".$post['idCredencial']."';");
      foreach ($consulta__idUsuarioProponente as $valor__idUsuarioProponentes) {
        $idUsuarioProponente=$valor__idUsuarioProponentes["idUsuarioProponente"];
      }

      if(empty($post['tipoUsuario__perteneciente']) || intval($post['tipoUsuario__perteneciente'])===0){
        $tipoPerteneciente__usuario=0;
      }else{
        $tipoPerteneciente__usuario=$post['tipoUsuario__perteneciente'];
      }

      $this->constructor->actualiza__general("UPDATE credencial_tipo SET idTipoUsuario='".$post['tipoUsuario']."',idSubTipo='$tipoPerteneciente__usuario' WHERE idCredencial='".$post['idCredencial']."';");

      if(!empty($idUsuarioProponente)){

        $this->constructor->actualiza__general("UPDATE usuario SET discapacidad='".$post['tieneDiscapacidad']."' WHERE idCredencial='".$post['idCredencial']."';");

      }


      if(!empty($idCredencialRepresentante) && intval($post['necesitaRepresentanteLegal'])===0 && empty($idCredencial__organismo)){


        $consulta__representante=$this->constructor->select__general("SELECT cedula,nombre,sexo,celular1,celular2,correo1,correo2,fecha,hora FROM representante WHERE idCredencial='".$post['idCredencial']."';");

        foreach ($consulta__representante as $valor__representante) {

          $this->constructor->inserta__general("representante_respaldo", ['cedula','nombre','sexo','celular1','celular2','correo1','correo2','idCredencial','fecha','hora'], array(
            ':cedula' =>$valor__representante['cedula'],
            ':nombre' =>$valor__representante['nombre'],
            ':sexo' =>$valor__representante['sexo'],
            ':celular1' =>$valor__representante['celular1'],
            ':celular2' =>$valor__representante['celular2'],
            ':correo1' =>$valor__representante['correo1'],
            ':correo2' =>$valor__representante['correo2'],
            ':idCredencial' =>$post['idCredencial'],
            ':fecha' =>  $this->fecha,
            ':hora' =>  $this->hora,
          ));

        }

        $this->constructor->actualiza__general("DELETE FROM representante WHERE idCredencial='".$post['idCredencial']."';");


      }else if(empty($idCredencialRepresentante) && intval($post['necesitaRepresentanteLegal'])===1){

        $this->constructor->inserta__general("representante", ['cedula','nombre','sexo','celular1','celular2','correo1','correo2','idCredencial','fecha','hora'], array(
          ':cedula' =>$post['cedulaRepresentanteLegal'],
          ':nombre' =>$post['representanteLegal'],
          ':sexo' =>$post['fechaDeNacimientoRepresentanteLegal'],
          ':celular1' =>$post['celular1RepresentanteLegal'],
          ':celular2' =>$post['celular2RepresentanteLegal'],
          ':correo1' =>$post['correo1RepresentanteLegal'],
          ':correo2' =>$post['correo2RepresentanteLegal'],
          ':idCredencial' =>$post['idCredencial'],
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));


      }else if(!empty($idCredencial__organismo) || intval($post['necesitaRepresentanteLegal'])===1){

        $consulta__representante=$this->constructor->select__general("SELECT cedula,nombre,sexo,celular1,celular2,correo1,correo2,fecha,hora FROM representante WHERE idCredencial='".$post['idCredencial']."';");
        foreach ($consulta__representante as $valor__representante) {

          $this->constructor->inserta__general("representante_respaldo", ['cedula','nombre','sexo','celular1','celular2','correo1','correo2','idCredencial','fecha','hora'], array(
            ':cedula' =>$valor__representante['cedula'],
            ':nombre' =>$valor__representante['nombre'],
            ':sexo' =>$valor__representante['sexo'],
            ':celular1' =>$valor__representante['celular1'],
            ':celular2' =>$valor__representante['celular2'],
            ':correo1' =>$valor__representante['correo1'],
            ':correo2' =>$valor__representante['correo2'],
            ':idCredencial' =>$post['idCredencial'],
            ':fecha' =>  $this->fecha,
            ':hora' =>  $this->hora,
          ));

        }

         $this->constructor->actualiza__general("UPDATE representante SET cedula='".$post['cedulaRepresentanteLegal']."', nombre='".$post['representanteLegal']."', celular1='".$post['celular1RepresentanteLegal']."', celular2='".$post['celular2RepresentanteLegal']."', correo1='".$post['correo1RepresentanteLegal']."', correo2='".$post['correo2RepresentanteLegal']."',sexo='".$post['fechaDeNacimientoRepresentanteLegal']."' WHERE idCredencial='".$post['idCredencial']."';");

      }

      $consulta__contacto=$this->constructor->select__general("SELECT celular1,celular2,email1,email2,callePrincipal,calleSecundaria,numeracion FROM contacto WHERE idCredencial='".$post['idCredencial']."';");
      foreach ($consulta__contacto as $valor__contacto) {

        $this->constructor->inserta__general("contacto_respaldo", ['celular1','celular2','email1','email2','callePrincipal','calleSecundaria','numeracion','idCredencial','fecha','hora'], array(
          ':celular1' =>$valor__contacto['celular1'],
          ':celular2' =>$valor__contacto['celular2'],
          ':email1' =>$valor__contacto['email1'],
          ':email2' =>$valor__contacto['email2'],
          ':callePrincipal' =>$valor__contacto['callePrincipal'],
          ':calleSecundaria' =>$valor__contacto['calleSecundaria'],
          ':numeracion' =>$valor__contacto['numeracion'],
          ':idCredencial' =>$post['idCredencial'],
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));

      }

      $this->constructor->actualiza__general("UPDATE contacto SET celular1='".$post['celular1']."', celular2='".$post['celular2']."', email1='".$post['correo1']."', email2='".$post['correo2']."', callePrincipal='".$post['callePrincipal']."', calleSecundaria='".$post['calleSecundaria']."', numeracion='".$post['numeracion']."' WHERE idCredencial='".$post['idCredencial']."';");


      return 1;

    }


    public function obtener__transacion__radios($idUsuario){

      $array=array();

      $idCredencial=$this->obtener__idCredencial__idUsuario($idUsuario);
      $consulta=$this->constructor->select__general("SELECT modulo,permiso FROM credencial_reporteria WHERE idCredencial='$idCredencial';");
      foreach ($consulta as $valor) {
        array_push($array,$valor["modulo"]."__".$valor["permiso"]);
      }

      return $array;

    }

    public function obtener__idCredencial__idUsuario($idUsuario){

        $consulta=$this->constructor->select__general("SELECT idCredencial FROM funcionario WHERE idUsuario='$idUsuario';");
        foreach ($consulta as $valor) {
            $idCredencial=$valor["idCredencial"];
        }

        return $idCredencial;

    }

    public function guardarTramitesGeneral__perfiles($post) {

      $idUsuario=$post["idUsuario"];
      $arrayIdPrincipal = json_decode($post["arrayIdPrincipal"], true);
      $opcionCalificacion=$post["opcionCalificacion"];
      $opcionCertificacion=$post["opcionCertificacion"];
      $opcionSeguimiento=$post["opcionSeguimiento"];
      $opcionSeguimientoV1=$post["opcionSeguimientoV1"];
      $idCredencialCreador=$post["idCredencial"];

      $idCredencialInsertar=$this->obtener__idCredencial__idUsuario($idUsuario);

      $this->constructor->actualiza__general("DELETE FROM credencial_reporteria WHERE idCredencial='$idCredencialInsertar';");

      if($opcionCalificacion!=='null' && !empty($opcionCalificacion)){
        $this->constructor->inserta__general("credencial_reporteria", ['idCredencial','idCreador','modulo','permiso','fecha','hora'], array(
          ':idCredencial' =>$idCredencialInsertar,
          ':idCreador' =>$idCredencialCreador,
          ':modulo' =>'calificacion',
          ':permiso' =>$opcionCalificacion,
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));
      }

      if($opcionCertificacion!=='null' && !empty($opcionCertificacion)){
        $this->constructor->inserta__general("credencial_reporteria", ['idCredencial','idCreador','modulo','permiso','fecha','hora'], array(
          ':idCredencial' =>$idCredencialInsertar,
          ':idCreador' =>$idCredencialCreador,
          ':modulo' =>'certificacion',
          ':permiso' =>$opcionCertificacion,
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));
      }

      if($opcionSeguimiento!=='null' && !empty($opcionSeguimiento)){
        $this->constructor->inserta__general("credencial_reporteria", ['idCredencial','idCreador','modulo','permiso','fecha','hora'], array(
          ':idCredencial' =>$idCredencialInsertar,
          ':idCreador' =>$idCredencialCreador,
          ':modulo' =>'seguimiento',
          ':permiso' =>$opcionSeguimiento,
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));
      }


      if($opcionSeguimientoV1!=='null' && !empty($opcionSeguimientoV1)){
        $this->constructor->inserta__general("credencial_reporteria", ['idCredencial','idCreador','modulo','permiso','fecha','hora'], array(
          ':idCredencial' =>$idCredencialInsertar,
          ':idCreador' =>$idCredencialCreador,
          ':modulo' =>'seguimientoV1',
          ':permiso' =>$opcionSeguimientoV1,
          ':fecha' =>  $this->fecha,
          ':hora' =>  $this->hora,
        ));
      }


       $funcionario=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idUsuario='$idUsuario';");
       foreach ($funcionario as $valor) {
          $idFuncionarioBd=$valor["idFuncionario"];
       }

       if (empty($idFuncionarioBd)) {

          $credencialDatos=$this->constructor->select__general__talento("SELECT usuario,password AS passwordAsignados FROM ezonshar_mdepsaddb.th_usuario WHERE id_usuario='$idUsuario';");
          foreach ($credencialDatos as $valor) {
            $usuarioCBD=$valor["usuario"];
            $passwordCBD=$valor["passwordAsignados"];
          }


          $this->constructor->inserta__general("credencial",['usuario','fecha','hora','estado','estadoValidacion','contrasena'],array(':usuario' => $usuarioCBD,':fecha' => $this->fecha,':hora' => $this->hora,':estado' => 'A',':estadoValidacion' => 2,':contrasena' => $passwordCBD));

          $maximo=$this->constructor->select__general("SELECT MAX(idCredencial) AS maximo FROM credencial;");

          foreach ($maximo as $valor) {
            $idMaximo=$valor["maximo"];
          }


          $this->constructor->inserta__general("funcionario",['idUsuario','fecha','hora','idCredencial'],array(':idUsuario' => $idUsuario,':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idMaximo));

          $funcionario=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idUsuario='$idUsuario';");

          foreach ($funcionario as $valor) {
            $idFuncionarioBd=$valor["idFuncionario"];
          }

       }

      $this->constructor->actualiza__general("DELETE FROM funcionarioperfil WHERE idFuncionario='$idFuncionarioBd';");


      foreach ($arrayIdPrincipal as $valor) {

        $consultaPerfilTransaccion=$this->constructor->select__general("SELECT idPerfil,idRol,idTransaccion,idAplicativo,idInterfaz FROM perfiltransaccion WHERE id='$valor';");
        foreach ($consultaPerfilTransaccion as $valor__2) {
          $idPerfil=$valor__2["idPerfil"];
          $idRol=$valor__2["idRol"];
          $idTransaccion=$valor__2["idTransaccion"];
          $idAplicativo=$valor__2["idAplicativo"];
          $idInterfaz=$valor__2["idInterfaz"];
        }


        $this->constructor->inserta__general("funcionarioperfil",['idFuncionario','idRol','idPerfil','idAplicativo','fecha','hora','idTransaccion'],array(':idFuncionario' => $idFuncionarioBd,':idRol' => $idRol,':idPerfil' => $idPerfil,':idAplicativo' => 1,':fecha' => $this->fecha,':hora' => $this->hora,':idTransaccion'=>$idTransaccion));

      }

      return 1;

    }   

    public function obtener__transacion($idUsuario) {

        return $this->constructor->select__general("SELECT b.id,(SELECT a1.idPerfil FROM perfil AS a1 WHERE a1.idPerfil=b.idPerfil) AS idPerfil,(SELECT a1.descripcion FROM perfil AS a1 WHERE a1.idPerfil=b.idPerfil) AS nombrePerfil,IFNULL((SELECT IF(a1.id IS NOT NULL,'SI','NO') FROM funcionarioperfil AS a1 INNER JOIN funcionario AS a2 ON a2.idFuncionario=a1.idFuncionario WHERE a1.idPerfil=b.idPerfil AND a2.idUsuario='$idUsuario' GROUP BY a1.idPerfil),'NO') AS evaluadorPerfil,(SELECT a1.idRol FROM rol AS a1 WHERE a1.idRol=b.idRol) AS idRol,(SELECT a1.descripcion FROM rol AS a1 WHERE a1.idRol=b.idRol) AS nombreRol,IFNULL((SELECT IF(a1.id IS NOT NULL,'SI','NO') FROM funcionarioperfil AS a1 INNER JOIN funcionario AS a2 ON a2.idFuncionario=a1.idFuncionario WHERE a1.idRol=b.idRol AND a2.idUsuario='$idUsuario' GROUP BY a1.idPerfil),'NO') AS evaluadorRol,a.idTransaccion,a.descripcion AS nombreTransaccion,IFNULL((SELECT IF(a1.id IS NOT NULL,'SI','NO') FROM funcionarioperfil AS a1 INNER JOIN funcionario AS a2 ON a2.idFuncionario=a1.idFuncionario WHERE a1.idTransaccion=a.idTransaccion AND a2.idUsuario='$idUsuario' GROUP BY a1.idPerfil),'NO') AS evaludadorTransaccion FROM transaccion AS a INNER JOIN perfiltransaccion AS b ON a.idTransaccion=b.idTransaccion WHERE (a.estado='A' OR a.estado='I') AND b.idPerfil<>'4' GROUP BY b.id ORDER BY b.idPerfil,b.idRol,b.idTransaccion ASC;");

    }   

    public function obtener__roles($idUsuario) {

        return $this->constructor->select__general("SELECT a.idRol,a.nombre,a.descripcion,IFNULL((SELECT IF(a1.id IS NOT NULL,'SI','NO') FROM funcionarioperfil AS a1 INNER JOIN funcionario AS a2 ON a2.idFuncionario=a1.idFuncionario WHERE a1.idRol=a.idRol AND a2.idUsuario='$idUsuario' GROUP BY a1.idPerfil),'NO') AS evaluador,b.idPerfil FROM rol AS a INNER JOIN perfiltransaccion AS b ON a.idRol=b.idRol WHERE a.estado='A' GROUP BY b.idRol ORDER BY b.idPerfil ASC;");

    }   

    public function obtener__perfil($idUsuario) {

        return $this->constructor->select__general("SELECT a.idPerfil,a.nombre,a.descripcion,IFNULL((SELECT IF(a1.id IS NOT NULL,'SI','NO') FROM funcionarioperfil AS a1 INNER JOIN funcionario AS a2 ON a2.idFuncionario=a1.idFuncionario WHERE a1.idPerfil=a.idPerfil AND a2.idUsuario='$idUsuario' GROUP BY a1.idPerfil),'NO') AS evaluador FROM perfil AS a INNER JOIN perfiltransaccion AS b ON a.idPerfil=b.idPerfil WHERE a.estado='A' GROUP BY b.idPerfil ORDER BY b.idPerfil ASC;");

    }   

    public function enviarCorreo__general($idCredencial,$codigoFActualizado) {

        $resultadoCredencial=$this->constructor->select__general("SELECT email1 FROM contacto WHERE idCredencial='$idCredencial';");
        foreach ($resultadoCredencial as $valor) {
          $email1Bd=$valor["email1"];
        }

        $resultadoNombreProyecto=$this->constructor->select__general__incentivo("SELECT b.nombre FROM proyecto_enviado AS a INNER JOIN proyecto_descripcion AS b ON a.codigoUsuario=b.codigo WHERE a.codigo='$codigoFActualizado';");
        foreach ($resultadoNombreProyecto as $valorNombre) {
          $nombreProyecto=$valorNombre["nombre"];
        }


      $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>INCENTIVO TRIBUTARIO</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Asunto: Actualización de Estado de proyecto Deportivo.</div><br><div style="font-size:10px;">Le informamos que el proyecto '.$nombreProyecto.' ha cambiado de estado. </div><br><div style="font-size:10px;">Puede consultar la información actualizada ingresando al aplicativo web en el siguiente enlace: <a href="https://incentivo.deporte.gob.ec/#/ingreso" target="_blank">https://incentivo.deporte.gob.ec/#/ingreso</a></div><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Si necesita más información o tiene alguna pregunta, no dude en ponerse en contacto con nuestro equipo de soporte. </span></div><br><div style="font-weight:bold; font-size:10px;">Atentamente, </div><br><br><div style="font-weight:bold; font-size:10px;">Ministerio del Deporte</div><br><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Dirección: </span><span style="font-size:10px;">Av.Gaspar de Villarroel E10-122 y 6 de Diciembre</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Código postal: </span><span style="font-size:10px;">170501 / Quito - Ecuador</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Teléfono: </span><span style="font-size:10px;">+593-2 396-9200 ext. 2384</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Email: incentivotributario@deporte.gob.ec</span></div></body></html>';

       $this->constructor->enviarCorreo([$email1Bd],$bodyMensaje);


        return 1;

    }

    public function obtener__activacion__menu__proponentes__historicos($idCredencial) {

      if(!empty($idCredencial)){

        if (intval($this->obtener__credencial__funcionario($idUsuario))===1) {

          return null;

        }else{

        $consulta=$this->constructor->select__general("SELECT IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)) AS usuario FROM credencial AS a WHERE a.idCredencial='$idCredencial';");

        foreach ($consulta as $valor) {
          $usuario=$valor["usuario"];
        }

        return $this->constructor->select__general__talento("SELECT a.idTramite, a.codigo, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS nombre, IFNULL((SELECT CAST(a1.presupuesto AS DECIMAL(10,2)) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1), 0) + IFNULL((SELECT CAST(a1.presupuesto2 AS DECIMAL(10,2)) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1), 0) + IFNULL((SELECT CAST(a1.presupuesto3 AS DECIMAL(10,2)) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1), 0) + IFNULL((SELECT CAST(a1.presupuestoCuatro AS DECIMAL(10,2)) FROM pro_proyetosreferencias AS a1 WHERE a1.codigoProyecto = a.codigo ORDER BY a1.idProyectoReferencias DESC LIMIT 1), 0) AS monto, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.alcanse, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS alcanse, IFNULL((SELECT STR_TO_DATE(b.inicioPeriodos, '%d/%m/%Y') FROM pro_proyetosreferencias AS b WHERE a.codigo = b.codigoProyecto ORDER BY b.idProyectoReferencias DESC LIMIT 1), '-') AS inicioPeriodosDate, IFNULL((SELECT STR_TO_DATE(b.finPeriodos, '%d/%m/%Y') FROM pro_proyetosreferencias AS b WHERE a.codigo = b.codigoProyecto ORDER BY b.idProyectoReferencias DESC LIMIT 1), '-') AS finPeriodosDate, (SELECT IF(YEAR(STR_TO_DATE(b.inicioPeriodos, '%d/%m/%Y')) <= YEAR(CURDATE()) AND YEAR(STR_TO_DATE(b.finPeriodos, '%d/%m/%Y')) >= YEAR(CURDATE()),'SI', 'NO') FROM pro_proyetosreferencias AS b WHERE a.codigo = b.codigoProyecto ORDER BY b.idProyectoReferencias DESC LIMIT 1) AS fechaDentroDelAnioActual, ROUND(IFNULL((SELECT SUM(a1.subotal) FROM incentivo.proyecto_certificacion_factura_tramite AS a1 WHERE a1.codigo = a.codigo AND a1.estado='A' GROUP BY a1.codigo), 0),2) + FORMAT(CAST(ROUND(IFNULL((SELECT SUM(a1.monto) FROM pro_certificacion AS a1 WHERE a1.codigo = a.codigo GROUP BY a1.codigo), 0), 2) AS DECIMAL(10,2)), 2)  AS montoCertificado, b.proyectoCargadoPdf AS proyecto, b.curriculumDeportivoSegundo AS curriculum, b.certificadoFederacionPdf AS certificadoFederacion, b.certificadoOrganismoSuperiorPdf AS certificadoOrganismoSuperior, b.solicitudFederacionPdf AS solicitudFederacion, b.avalFederacionPdf AS avalFederacion, b.avalFederacionPdf AS avalFederacionPdf, b.solciitudAvalPdf AS solicitudAval, b.avalOrganismoSuperiorPdf AS avalOrganismo FROM pro_proyecto AS a INNER JOIN pro_documentos AS b ON a.codigo = b.codigo WHERE a.codigo LIKE '%$usuario%';");

        }


      }

    }   


    public function obtener__id__menu__principal($idMenu) {

      $nombreDeRuta=$this->constructor->select__general("SELECT idMenu FROM interfaz WHERE name='$idMenu';");
      foreach ($nombreDeRuta as $valor) {
        $idMenu=$valor["idMenu"];
      }

      return $idMenu;

    }   

    public function obtener__nombreUsuarioPerfil($post) {

      $idPerfil=$post["idPerfil"];
      $idCredencial=$post["idCredencial"];

      if (intval($idPerfil)===4) {

        $consulta= $this->constructor->select__general("SELECT nombre FROM usuario WHERE idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
          $nombreBd=$valor["nombre"];
        }

        if (empty($nombreBd)) {
          return $this->constructor->select__general("SELECT razonSocial AS nombre FROM organismo WHERE idCredencial='$idCredencial';");
        }else{
          return $this->constructor->select__general("SELECT nombre FROM usuario WHERE idCredencial='$idCredencial';");
        }

      }else{

        $consulta=$this->constructor->select__general("SELECT idUsuario FROM funcionario WHERE idCredencial='$idCredencial';");
        foreach ($consulta as $valor) {
          $idUsuarioBd=$valor["idUsuario"];
        }

        return $this->constructor->select__general__talento("SELECT CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombre FROM th_usuario AS a WHERE a.id_usuario='$idUsuarioBd';");

      }

    }      

    public function obtenerMenu__nombre($post) {

      $componente=$post["componente"];

       $consulta=$this->constructor->select__general("SELECT text FROM interfaz WHERE component='$componente';");
       foreach ($consulta as $valor) {
          $textBd=$valor["text"];
       }

       return $textBd;

    }         

    public function verbos__obtenidos() {

      return $this->constructor->select__general("SELECT verbo FROM verbo WHERE estado='A';");

    }     

    public function selectLogoInicial() {

        return $this->constructor->select__archivo("SELECT portada FROM configuracion WHERE tipo='portada' AND estado='A';",'documentos/imagenesAplicativo/',"portada");

    }

    public function selectBannerRegistro() {

        return $this->constructor->select__archivo("SELECT portada FROM configuracion WHERE tipo='banner' AND estado='A';",'documentos/imagenesAplicativo/',"portada");

    }

    public function existe__talento($usuario,$password){

      $encriptadoSha1 = sha1($password);

      $idEncontrado=$this->constructor->select__general__talento("SELECT id_usuario FROM th_usuario WHERE usuario='$usuario' AND password='$encriptadoSha1';");
      foreach ($idEncontrado as $valor) {
        $id_usuarioBd=$valor["id_usuario"];
      }

      $nombre=$this->constructor->select__general("SELECT idCredencial FROM funcionario WHERE idUsuario='$id_usuarioBd';");

      foreach ($nombre as $valor) {
        $idBd=$valor["idCredencial"];
      }

      if (!empty($idBd)) {
       return $idBd;
      }else{
        return 0;
      }

    }

    public function usuarioExistente__auth($aplicativo,$usuario,$password) {

      $encriptado = md5($password);


      if(intval($this->existe__talento($usuario,$password))!==0){

        return $this->constructor->select__general("SELECT h.idCredencial,c.nombre AS nombreRol,h.usuario,e.nombre AS nombrePerfil,e.idPerfil,b.idRol,g.path FROM funcionario AS a INNER JOIN funcionarioperfil AS b ON a.idFuncionario=b.idFuncionario INNER JOIN rol AS c ON c.idRol=b.idRol INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN perfiltransaccion AS f ON f.idRol=b.idRol INNER JOIN interfaz AS g ON g.idInterfaz=f.idInterfaz INNER JOIN credencial AS h ON h.idCredencial=a.idCredencial WHERE a.idCredencial='".intval($this->existe__talento($usuario,$password))."' GROUP BY idCredencial ORDER BY g.idInterfaz ASC;");

      }else{

        return $this->constructor->select__general("SELECT a.idCredencial,c.nombre AS nombreRol,a.usuario,e.nombre AS nombrePerfil,e.idPerfil,b.idRol,g.path FROM credencial AS a INNER JOIN usuarioperfil AS b ON a.idCredencial=b.idCredencial INNER JOIN rol AS c ON c.idRol=b.idRol INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN perfiltransaccion AS f ON f.idRol=b.idRol INNER JOIN interfaz AS g ON g.idInterfaz=f.idInterfaz WHERE a.usuario='$usuario' AND a.contrasena='$encriptado' GROUP BY idCredencial ORDER BY g.idInterfaz ASC;");

      }

    }

    public function obtener__credencial__funcionario($idCredencial) {

      $retornador=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idCredencial='$idCredencial';");
      foreach ($retornador as $valor) {
        $idFuncionarioBd=$valor["idFuncionario"];
      }

      if (!empty($idFuncionarioBd)) {
       return 1;
      }else{
        return 0;
      }

    }

    public function generar__variables__sesion($idUsuario) {

      $arrayDatos = array();

      $consultaObtenerFuncionarios=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idCredencial='$idUsuario';");

      foreach ($consultaObtenerFuncionarios as $valor) {
        $idFuncionarioBd=$valor["idFuncionario"];
      }


      if (intval($this->obtener__credencial__funcionario($idUsuario))===1) {
        $resultado=$this->constructor->select__general("SELECT z.idCredencial, f.nombre AS nombreRol, z1.usuario, e.nombre AS nombrePerfil, e.idPerfil,b.idRol,c.path FROM funcionarioperfil AS a INNER JOIN perfiltransaccion AS b ON a.idRol=b.idRol AND a.idPerfil=b.idPerfil AND a.idTransaccion=b.idTransaccion INNER JOIN interfaz AS c ON c.idInterfaz=b.idInterfaz INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN rol AS f ON f.idRol=b.idRol INNER JOIN transaccion AS g ON g.idTransaccion=b.idTransaccion INNER JOIN funcionario AS z ON z.idFuncionario=a.idFuncionario INNER JOIN credencial AS z1 ON z1.idCredencial=z.idCredencial  WHERE a.idFuncionario='$idFuncionarioBd' AND d.nombreAplicativo='incentivo' AND e.estado='A' AND f.estado='A' AND c.estado='A' AND g.estado='A' AND c.visible='A' GROUP BY c.idInterfaz;");
      }else{
        $resultado=$this->constructor->select__general("SELECT a.idCredencial,c.nombre AS nombreRol,a.usuario,e.nombre AS nombrePerfil,e.idPerfil,b.idRol,g.path FROM credencial AS a INNER JOIN usuarioperfil AS b ON a.idCredencial=b.idCredencial INNER JOIN rol AS c ON c.idRol=b.idRol INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN perfiltransaccion AS f ON f.idRol=b.idRol INNER JOIN interfaz AS g ON g.idInterfaz=f.idInterfaz WHERE a.idCredencial='$idUsuario'  GROUP BY idCredencial ORDER BY g.idInterfaz ASC;");
      }

      foreach ($resultado as $valor) {
            $idUsuarioBd=$valor["idCredencial"];
            $nombreRolBd=$valor["nombreRol"];
            $usuarioBd=$valor["usuario"];
            $nombrePerfilBd=$valor["nombrePerfil"];
            $idPerfilBd=$valor["idPerfil"];
            $idRolBd=$valor["idRol"];
            $pathBd=$valor["path"];
       }

       session_start();

       $_SESSION['idUsuario'] = $idUsuarioBd;
       $_SESSION['nombreRol'] = $nombreRolBd;
       $_SESSION['usuario'] = $usuarioBd;
       $_SESSION['nombrePerfil'] = $nombrePerfilBd;
       $_SESSION['idPerfil'] = $idPerfilBd;
       $_SESSION['idRol'] = $idRolBd;

       array_push($arrayDatos, $idUsuarioBd);
       array_push($arrayDatos, $nombreRolBd);
       array_push($arrayDatos, $usuarioBd);
       array_push($arrayDatos, $nombrePerfilBd);
       array_push($arrayDatos, $idPerfilBd);
       array_push($arrayDatos, $idRolBd);
       array_push($arrayDatos, $pathBd);

        return $arrayDatos;

    }


    public function generar__token($idUsuario,$nombreRol,$usuario) {

      $token=$this->constructor__auth->getAuthToken($idUsuario,$usuario);

      session_start();
      $_SESSION['token'] = $token;


        return $token;

    }


    public function validar__token($token) {

      $token=$this->constructor__auth->restriccionToken($token);

        return $token;

    }            

    public function midleware($token) {

      $token=$this->constructor__auth->restriccionToken__2($token);

        return $token;

    }            


    public function interfazGeneral($aplicativo) {

      $aplicativo=$this->constructor->select__general("SELECT a.path,a.`name`,a.component FROM interfaz AS a INNER JOIN perfiltransaccion AS b ON a.idInterfaz=b.idInterfaz WHERE a.estado='A';");

      return $aplicativo;

    }     

    public function obtener__menuPrincipal($id,$idCredencial) {

      if(!empty($idCredencial)){

        $idArray = json_decode($id, true);
        $idString = implode(",", $idArray);

        $consultaObtenerFuncionarios=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idCredencial='$idCredencial';");

        foreach ($consultaObtenerFuncionarios as $valor) {
          $idFuncionarioBd=$valor["idFuncionario"];
        }


        if (!empty($idFuncionarioBd) || intval($idCredencial)===1) {


          return $this->constructor->select__general("SELECT id, nombre FROM menu WHERE id IN ($idString) AND id<>6;");

        }else{


          return $this->constructor->select__general("SELECT id, nombre FROM menu WHERE id IN ($idString) AND id<>6 AND estado='A';");

        }

      }


    }     

    public function menus__dinamicos($aplicativo,$idUsuario) {

      $consultaObtenerFuncionarios=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idCredencial='$idUsuario';");

      foreach ($consultaObtenerFuncionarios as $valor) {
        $idFuncionarioBd=$valor["idFuncionario"];
      }


      if (intval($this->obtener__credencial__funcionario($idUsuario))===1) {

         $aplicativo=$this->constructor->select__general("(SELECT z1.id AS idMenu,c.idInterfaz,c.path,c.`name`,c.component,c.fecha,c.hora,c.estado,c.text,c.icon,c.path,e.estado,c.visible FROM funcionarioperfil AS a INNER JOIN perfiltransaccion AS b ON a.idRol=b.idRol AND a.idPerfil=b.idPerfil AND a.idTransaccion=b.idTransaccion INNER JOIN interfaz AS c ON c.idInterfaz=b.idInterfaz INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN rol AS f ON f.idRol=b.idRol INNER JOIN transaccion AS g ON g.idTransaccion=b.idTransaccion INNER JOIN funcionario AS z ON z.idFuncionario=a.idFuncionario INNER JOIN menu AS z1 ON z1.id=c.idMenu  WHERE a.idFuncionario='$idFuncionarioBd' AND d.nombreAplicativo='INCENTIVO' AND e.estado='A' AND f.estado='A' AND c.estado='A' AND g.estado='A' GROUP BY c.idInterfaz) UNION (SELECT z1.id AS idMenu,c.idInterfaz,c.path,c.`name`,c.component,c.fecha,c.hora,c.estado,c.text,c.icon,c.path,e.estado,c.visible FROM interfaz AS c INNER JOIN perfiltransaccion AS b ON c.idInterfaz=b.idInterfaz INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN rol AS f ON f.idRol=b.idRol INNER JOIN transaccion AS g ON g.idTransaccion=b.idTransaccion  INNER JOIN menu AS z1 ON z1.id=c.idMenu WHERE c.idInterfaz='40' GROUP BY c.idInterfaz);");
      }else{
        $aplicativo=$this->constructor->select__general("SELECT z1.id AS idMenu,c.idInterfaz,c.path,c.`name`,c.component,c.fecha,c.hora,c.estado,c.text,c.icon,c.path,e.estado,c.visible FROM usuarioperfil AS a INNER JOIN perfiltransaccion AS b ON a.idRol=b.idRol INNER JOIN interfaz AS c ON c.idInterfaz=b.idInterfaz INNER JOIN aplicativo AS d ON d.idAplicativo=b.idAplicativo INNER JOIN perfil AS e ON e.idPerfil=b.idPerfil INNER JOIN rol AS f ON f.idRol=b.idRol INNER JOIN transaccion AS g ON g.idTransaccion=b.idTransaccion INNER JOIN menu AS z1 ON z1.id=c.idMenu  WHERE a.idCredencial='$idUsuario' AND d.nombreAplicativo='INCENTIVO' AND e.estado='A' AND f.estado='A' AND c.estado='A' AND g.estado='A' GROUP BY c.idInterfaz;");
      }

      return $aplicativo;

    }     

    public function obtenerNombreRuta($ruta) {

      $nombreDeRuta=$this->constructor->select__general("SELECT a.text FROM interfaz AS a WHERE a.path LIKE '%$ruta%';");

      return $nombreDeRuta;

    }     

    public function usuariosExternos($buscar) {

      $array = array();

      $buscador__organismo = $this->constructor->select__general("SELECT a.idCredencial AS idCredencialOrganismo FROM organismo AS a WHERE a.razonSocial LIKE '%$buscar%' OR a.ruc LIKE '%$buscar%';");
      foreach ($buscador__organismo as $valorOrganismo) {
        array_push($array, $valorOrganismo["idCredencialOrganismo"]);
      }

      $buscador__persona = $this->constructor->select__general("SELECT a.idCredencial AS idCredencialUsuario FROM usuario AS a WHERE a.nombre LIKE '%$buscar%' OR a.cedula LIKE '%$buscar%';");
      foreach ($buscador__persona as $valorUsuario) {
        array_push($array, $valorUsuario["idCredencialUsuario"]);
      }

      $array = array_map(function($item) {
        $item = trim($item);
        if ($item === '' || $item === null) return null;
        return ltrim($item, ',');
      }, $array);

      $array = array_filter($array, function($item) {
        return $item !== null && $item !== '';
      });

      $array = array_values($array);

      $buscadores = implode(',', $array);

      if (empty($buscadores)) {
        return [];
      }

      return $this->constructor->select__general("SELECT a.idCredencial,IFNULL((SELECT a1.ruc FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.cedula FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)) AS credencialProponente,IFNULL((SELECT a1.razonSocial FROM organismo AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1),(SELECT a1.nombre FROM usuario AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1)) AS nombreProponente,(SELECT a2.nombre FROM credencial_tipo AS a1 INNER JOIN incentivo.tipo_usuario AS a2 ON a2.idTipoUsuario=a1.idTipoUsuario WHERE a1.idCredencial=a.idCredencial LIMIT 1) AS tipoUsuario,(SELECT a1.email1 FROM contacto AS a1 WHERE a1.idCredencial=a.idCredencial LIMIT 1) AS correo FROM credencial AS a WHERE a.idCredencial IN ($buscadores) AND a.idCredencial<>1;");

    }  



    public function funcionarios($buscar) {

      $buscador=$this->constructor->select__general__talento("SELECT a.id_usuario,a.cedula,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,a.usuario, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.descripcionFisicamenteEstructura, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')  AS area, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS cargo  FROM ezonshar_mdepsaddb.th_usuario AS a INNER JOIN ezonshar_mdepsaddb.th_puestoinstitucional AS b ON a.puestoInstitucional=b.id_PuestoInstitucional INNER JOIN ezonshar_mdepsaddb.th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura WHERE a.cedula LIKE '%$buscar%' OR a.nombre LIKE '%$buscar%' OR a.apellido LIKE '%$buscar%' OR a.usuario LIKE '%$buscar%' OR c.descripcionFisicamenteEstructura LIKE '%$buscar%' OR b.descripcionPuestoInstitucional LIKE '%$buscar%';");

      return $buscador;

    }  


    public function perfiles($aplicativo) {

      $nombreDeRuta=$this->constructor->select__general("SELECT a.idPerfil,a.nombre,a.descripcion FROM perfil AS a INNER JOIN perfiltransaccion AS b ON a.idPerfil=b.idPerfil INNER JOIN aplicativo AS c ON c.idAplicativo=b.idAplicativo WHERE a.estado='A' AND c.nombreAplicativo='$aplicativo' GROUP BY a.idPerfil;");

      return $nombreDeRuta;

    }              

    public function rol__perfiles($aplicativo) {

      $nombreDeRuta=$this->constructor->select__general("SELECT a.idRol,a.nombre AS nombreRol,a.descripcion,d.idPerfil,d.nombre AS nombrePerfil, d.descripcion AS descripcionPerfil,IF(e.idTransaccion IS NULL, ' ', e.idTransaccion) AS idTransaccion,IF(e.descripcion IS NULL,' ',e.descripcion) AS descripcionTransacion FROM rol AS a INNER JOIN perfiltransaccion AS b ON a.idRol=b.idRol INNER JOIN aplicativo AS c ON c.idAplicativo=b.idAplicativo LEFT JOIN perfil AS d ON d.idPerfil=b.idPerfil LEFT JOIN transaccion AS e ON e.idTransaccion=b.idTransaccion WHERE a.estado='A' AND c.nombreAplicativo='incentivo' AND d.estado='A' AND d.idPerfil!='4' AND b.id<>'22' AND b.id<>'23'  AND e.estado='A' ORDER BY idPerfil,idRol ASC;");

      return $nombreDeRuta;

    }          

    public function asignacion__perfil__roles($aplicativo,$idUsuario,$idPerfilesSeleccionados,$idRolesSeleccionados,$idTransaccionSeleccionados) {

       $idTransaccionSeleccionados__array = json_decode($idTransaccionSeleccionados, true);

       $funcionario=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idUsuario='$idUsuario';");

       foreach ($funcionario as $valor) {
          $idFuncionarioBd=$valor["idFuncionario"];
       }

       if (empty($idFuncionarioBd)) {

          $credencialDatos=$this->constructor->select__general__talento("SELECT usuario,password AS passwordAsignados FROM ezonshar_mdepsaddb.th_usuario WHERE id_usuario='$idUsuario';");
          foreach ($credencialDatos as $valor) {
            $usuarioCBD=$valor["usuario"];
            $passwordCBD=$valor["passwordAsignados"];
          }

          $this->constructor->inserta__general("credencial",['usuario','fecha','hora','estado','estadoValidacion','contrasena'],array(':usuario' => $usuarioCBD,':fecha' => $this->fecha,':hora' => $this->hora,':estado' => 'A',':estadoValidacion' => 2,':contrasena' => $passwordCBD));

          $maximo=$this->constructor->select__general("SELECT MAX(idCredencial) AS maximo FROM credencial;");

          foreach ($maximo as $valor) {
            $idMaximo=$valor["maximo"];
          }


          $this->constructor->inserta__general("funcionario",['idUsuario','fecha','hora','idCredencial'],array(':idUsuario' => $idUsuario,':fecha' => $this->fecha,':hora' => $this->hora,':idCredencial' => $idMaximo));

          $funcionario=$this->constructor->select__general("SELECT idFuncionario FROM funcionario WHERE idUsuario='$idUsuario';");

          foreach ($funcionario as $valor) {
            $idFuncionarioBd=$valor["idFuncionario"];
          }

         
       }

       $this->constructor->actualiza__general("DELETE FROM funcionarioperfil WHERE idFuncionario='$idFuncionarioBd';");

       $aplicativoNombre=$this->constructor->select__general("SELECT idAplicativo FROM aplicativo WHERE nombreAplicativo='$aplicativo';");

       foreach ($aplicativoNombre as $valor) {
          $idAplicativoBd=$valor["idAplicativo"];
       }


       foreach ($idTransaccionSeleccionados__array as $valor) {

        $arrayRealizado=array();
        $arrayRealizado = explode("__", $valor);

        $this->constructor->inserta__general("funcionarioperfil",['idFuncionario','idRol','idPerfil','idAplicativo','fecha','hora','idTransaccion'],array(':idFuncionario' => $idFuncionarioBd,':idRol' => $arrayRealizado[1],':idPerfil' => $arrayRealizado[0],':idAplicativo' => 1,':fecha' => $this->fecha,':hora' => $this->hora,':idTransaccion'=>$arrayRealizado[2]));

       }


      return 1;

    }          

    public function obtenerRolesPerfiles($aplicativo,$idUsuario) {

       $aplicativoNombre=$this->constructor->select__general("SELECT idAplicativo FROM aplicativo WHERE nombreAplicativo='$aplicativo';");

       foreach ($aplicativoNombre as $valor) {
          $idAplicativoBd=$valor["idAplicativo"];
       }

      $funcionarioperfil=$this->constructor->select__general("SELECT a.idRol,a.idPerfil,a.idTransaccion FROM funcionarioperfil AS a INNER JOIN funcionario AS b ON a.idFuncionario=b.idFuncionario WHERE b.idUsuario='$idUsuario' AND a.idAplicativo='1' AND a.idTransaccion IS NOT NULL;");

      return $funcionarioperfil;

    }


    public function informacionUsuario($aplicativo,$idUsuario) {

      $informacionGeneral=$this->constructor->select__general__talento("SELECT a.id_usuario,a.cedula,CONCAT_WS(' ',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.nombre, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó'),REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.apellido, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')) AS nombreCompleto,a.usuario, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.descripcionFisicamenteEstructura, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó')  AS area, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(b.descripcionPuestoInstitucional, 'Ã¡', 'á'),'Ã©','é'),'Ã­','í'),'Ã³','ó'),'Ãº','ú'),'Ã‰','É'),'ÃŒ','Í'),'Ã“','Ó'),'Ãš','Ú'),'Ã±','ñ'),'Ã‘','Ñ'),'&#039;',' ` '),'Ã','Á'),'',' '),'Ã','Á'),'SI','SI'),'â€œ',''),'â€',''),'Á²','ó') AS cargo  FROM ezonshar_mdepsaddb.th_usuario AS a INNER JOIN ezonshar_mdepsaddb.th_puestoinstitucional AS b ON a.puestoInstitucional=b.id_PuestoInstitucional INNER JOIN ezonshar_mdepsaddb.th_fisicamenteestructura AS c ON c.id_FisicamenteEstructura=a.fisicamenteEstructura WHERE a.id_usuario='$idUsuario';");

      return $informacionGeneral;

    }

    public function observableAxios__buscar__usuario__existente($usuarioRecuperar) {

        $resultadoCredencial=$this->constructor->select__general("SELECT b.email1,a.usuario,a.idCredencial FROM credencial AS a INNER JOIN contacto AS b ON a.idCredencial=b.idCredencial WHERE a.usuario='$usuarioRecuperar';");

        foreach ($resultadoCredencial as $valor) {
          $usuarioBd=$valor["usuario"];
          $idCredencialBd=$valor["idCredencial"];
          $email1Bd=$valor["email1"];
        }

        $usuarioInformacion=$this->constructor->select__general("SELECT cedula,nombre FROM usuario WHERE idCredencial='$idCredencialBd';");
        foreach ($usuarioInformacion as $valor) {
          $cedulaUsuarioBd=$valor["cedula"];
          $nombreUsuarioBd=$valor["nombre"];
        }

        $organismoInformacion=$this->constructor->select__general("SELECT ruc,razonSocial FROM organismo WHERE idCredencial='$idCredencialBd';");
        foreach ($organismoInformacion as $valor) {
          $rucOrganismoBd=$valor["ruc"];
          $razonSocialOrganismoBd=$valor["razonSocial"];
        }


        if (!empty($cedulaUsuarioBd)) {
          $nombreCompleto=$nombreUsuarioBd;
        }else{
          $nombreCompleto=$razonSocialOrganismoBd;
        }

        if(!empty($usuarioBd)){

          $codigo=$this->constructor->generarCodigo(5);

          $this->constructor->actualiza__general("UPDATE codigo SET estado='I' WHERE idCredencial='$idCredencialBd';");

          $this->constructor->inserta__general("codigo",['codigo','estado','idCredencial','fecha','hora'],array(':codigo' => $codigo,':estado' => 'A',':idCredencial' => $idCredencialBd,':fecha' => $this->fecha,':hora' => $this->hora));

          $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Estimado/a usuario/a,</div><br><div style="font-size:10px;">'.$nombreCompleto.'</div><br><div style="font-size:10px;">Se ha creado un usuario en la plataforma de incentivo tributario del Ministerio del Deporte con credenciales:</div><br><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Usuario:</span>&nbsp;'.$usuarioBd.'</div><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Código de validación:</span>&nbsp;'.$codigo.'</div><br><div style="font-size:10px;">El código tendrá una duración de 10 minutos, pasado este tiempo deberá generar un nuevo código.</div><br><div style="font-weight:bold; font-size:10px;">Atentamente, </div><br><br><div style="font-weight:bold; font-size:10px;">Ministerio del Deporte</div><br><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Dirección: </span><span style="font-size:10px;">Av.Gaspar de Villarroel E10-122 y 6 de Diciembre</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Código postal: </span><span style="font-size:10px;">170501 / Quito - Ecuador</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Teléfono: </span><span style="font-size:10px;">+593-23969200</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">www.deporte.gob.ec</span></div></body></html>';

          $this->constructor->enviarCorreo([$email1Bd],$bodyMensaje);

        }


        return $resultadoCredencial;

    }

    public function observableAxios__buscar__usuario__existente__registro($post) {


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


        $resultadoCredencial=$this->constructor->select__general("SELECT idCredencial FROM credencial WHERE usuario='$usuario';");
        foreach ($resultadoCredencial as $valor) {
          $idCredencialBd=$valor["idCredencial"];
        }


        if(!empty($idCredencialBd)){

          $codigo=$this->constructor->generarCodigo(5);

          $this->constructor->actualiza__general("UPDATE codigo SET estado='I' WHERE idCredencial='$idCredencialBd';");

          $this->constructor->inserta__general("codigo",['codigo','estado','idCredencial','fecha','hora'],array(':codigo' => $codigo,':estado' => 'A',':idCredencial' => $idCredencialBd,':fecha' => $this->fecha,':hora' => $this->hora));

          $bodyMensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>POA</title><style type="text/css">body {background:#EEE; padding:30px; font-size:16px;}'.'</style>'.'</head>'.'<div style="font-weight:bold; font-size:10px;">Estimado/a usuario/a,</div><br><div style="font-size:10px;">'.$nombreCompleto.'</div><br><div style="font-size:10px;">Se ha creado un usuario en la plataforma de incentivo tributario del Ministerio del Deporte con credenciales:</div><br><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Usuario:</span>&nbsp;'.$usuario.'</div><div style="font-size:10px; display:flex;"><span style="font-weight:bold;">Código de validación:</span>&nbsp;'.$codigo.'</div><br><div style="font-size:10px;">El código tendrá una duración de 5 minutos, pasado este tiempo deberá generar un nuevo código.</div><br><div style="font-weight:bold; font-size:10px;">Atentamente, </div><br><br><div style="font-weight:bold; font-size:10px;">Ministerio del Deporte</div><br><br><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Dirección: </span><span style="font-size:10px;">Av.Gaspar de Villarroel E10-122 y 6 de Diciembre</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Código postal: </span><span style="font-size:10px;">170501 / Quito - Ecuador</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">Teléfono: </span><span style="font-size:10px;">+593-23969200</span></div><div font-size:10px;"><span style="font-weight:bold; font-size:10px;">www.deporte.gob.ec</span></div></body></html>';

          $this->constructor->enviarCorreo([$emailD1],$bodyMensaje);

        }


        return $resultadoCredencial;

    }


}
