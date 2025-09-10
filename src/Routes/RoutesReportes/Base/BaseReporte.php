<?php


namespace App\Routes\RoutesReportes\Base;

use App\Presentation\Controllers\ControllersReporte;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;


class BaseReporte {

  private $repositorio;

  public function __construct() {
      $this->constructor = new ControllersReporte();
      $this->constructor__seguridad = new Seguridad();
  }

  public function enviar__informacion__asignacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->enviar__informacion__asignacion($_POST);

     echo json_encode($jason);

  }  



  public function recuperar__areas__tecnicas() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->recuperar__areas__tecnicas($areaTecnica1);

     echo json_encode($jason);

  }  


  public function recuperar__tramite__seguimiento__v1() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->recuperar__tramite__seguimiento__v1($idUsuario);

     echo json_encode($jason);

  }  

  public function obtener__transaccionalidad__reporterias__certificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->obtener__transaccionalidad__reporterias__certificacion($idEnviado);

     echo json_encode($jason);

  }  

  public function recuperar__tramite__calificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->recuperar__tramite__calificacion($_POST);

     echo json_encode($jason);

  }  


  public function obtener__informe__modificacion__infraestructura() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['pdf']=$this->constructor->obtener__informe__modificacion__infraestructura($_POST);

     echo json_encode($jason);

  }  

  public function obtener__informe__modificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['pdf']=$this->constructor->obtener__informe__modificacion($_POST);

     echo json_encode($jason);

  }  

  public function obtener__informe__calificacion__infraestructura() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['pdf']=$this->constructor->obtener__informe__calificacion__infraestructura($_POST);

     echo json_encode($jason);

  }  

  public function obtener__informe__calificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['pdf']=$this->constructor->obtener__informe__calificacion($_POST);

     echo json_encode($jason);

  }  

  public function reporte__calificacion__subsess() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->reporte__calificacion__subsess($idRol,$fisicamenteEstructura);

     echo json_encode($jason);

  }  

  public function obtener__transaccionalidad__reporterias() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->obtener__transaccionalidad__reporterias($idEnviado);

     echo json_encode($jason);

  }  

  public function observable__obtener__documento__modificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['docuRuta']=$this->constructor->observable__obtener__documento__modificacion($codigo);

     echo json_encode($jason);

  }  

  public function recuperar__reporteria__asignada__idCredencial() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['calificacion']=$this->constructor->recuperar__reporteria__asignada__calificacion__idCredencial($id);
     $jason['certificacion']=$this->constructor->recuperar__reporteria__asignada__certificacion__idCredencial($id);
     $jason['seguimiento']=$this->constructor->recuperar__reporteria__asignada__seguimiento__idCredencial($id);
     $jason['seguimientoV1']=$this->constructor->recuperar__reporteria__asignada__seguimiento__idCredencial__v1($id);

     echo json_encode($jason);

  }  

  public function recuperar__reporteria__asignada() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['calificacion']=$this->constructor->recuperar__reporteria__asignada__calificacion($id);
     $jason['certificacion']=$this->constructor->recuperar__reporteria__asignada__certificacion($id);
     $jason['seguimiento']=$this->constructor->recuperar__reporteria__asignada__seguimiento($id);
     $jason['seguimientoV1']=$this->constructor->recuperar__reporteria__asignada__seguimiento__v1($id);

     echo json_encode($jason);

  }  


  public function actualizar__reporteria__usuario() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->actualizar__reporteria__usuario($_POST);

     echo json_encode($jason);

  }  

  public function reporte__calificacion__asignar() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->reporte__calificacion__asignar();

     echo json_encode($jason);

  }  

  public function reporte__calificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->reporte__calificacion();

     echo json_encode($jason);

  }  

  public function reporteria__certificacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->reporteria__certificacion($cargarData);

     echo json_encode($jason);

  }  

  public function reporte__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->reporte__seguimiento();

     echo json_encode($jason);

  }  


  public function rutas__compuestas() {

    $arrayAsociativo = array();

    $nombresFunciones = get_class_methods($this);

    foreach ($nombresFunciones as $valor) {

    	if ($valor!="rutas__compuestas") {
    		$arrayAsociativo['/incentivo/'.$valor] = $valor;
         // $arrayAsociativo['/'.$valor] = $valor;
    	}

    }

    return $arrayAsociativo;

  }

}
