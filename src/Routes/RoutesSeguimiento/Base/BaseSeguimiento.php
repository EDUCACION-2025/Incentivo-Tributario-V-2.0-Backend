<?php


namespace App\Routes\RoutesSeguimiento\Base;

use App\Presentation\Controllers\ControllersSeguimiento;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;


class BaseSeguimiento {

  private $repositorio;

  public function __construct() {
      $this->constructor = new ControllersSeguimiento();
      $this->constructor__seguridad = new Seguridad();
  }

   public function enviarInformacionSeguimientoFinal() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->enviarInformacionSeguimientoFinal($_POST);
     echo json_encode($jason);

  } 

   public function buscar__proyecto__existente__v1__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion']=$this->constructor->buscar__proyecto__existente__v1__seguimiento($_POST);
     echo json_encode($jason);

  } 

   public function obtener__proyectos__aprobados__codigo__v1__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion__codigo']=$this->constructor->obtener__proyectos__aprobados__codigo__v1__seguimiento($_POST);
     echo json_encode($jason);

  } 

  public function proyectos__aprobados__nombres__v1__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacion__codigo']=$this->constructor->proyectos__aprobados__nombres__v1__seguimiento($_POST);
     echo json_encode($jason);

  } 

  public function insertaReenvio__seguimiento__regresar() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['existe']=$this->constructor->insertaReenvio__seguimiento__regresar($_POST);
     echo json_encode($jason);

  } 


  public function informacion__general__proyecto__seguimiento__recomendacion() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['existe']=$this->constructor->informacion__general__proyecto__seguimiento__recomendacion($codigoUsuario,$tipo);
     echo json_encode($jason);

  } 

  public function informe__tecnico__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['token__archivo']=$this->constructor->informe__tecnico__seguimiento($_POST);
     echo json_encode($jason);

  } 

  public function activar__informe__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['activacionInforme']=$this->constructor->activar__informe__seguimiento($codigoUsuario);
     echo json_encode($jason);

  } 

  public function bandeja__recomendados__comite__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['bandejaRecibidos']=$this->constructor->bandeja__recomendados__comite($_POST);
     echo json_encode($jason);

  } 

  public function existe__componentes__infra() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['existe']=$this->constructor->existe__componentes__infra($codigo);
     echo json_encode($jason);

  } 

  public function insertaReenvio__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->insertaReenvio($_POST);
     echo json_encode($jason);

  } 

   public function bandeja__recibidos__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['bandejaRecibidos']=$this->constructor->bandeja__recibidos__seguimiento($idCredencial,$idRol,$fisicamenteEstructura);


     echo json_encode($jason);

  }    


  public function recuperar__informacion__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacionResumen']=$this->constructor->recuperar__resumen($codigo);
     $jason['informacionObjetivoGeneral']=$this->constructor->recuperar__objetivo__general($codigo);

     echo json_encode($jason);

  }  

  public function guardar__general__textos() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardar__general__textos($_POST);

     echo json_encode($jason);

  }  


  public function guardar__seguimiento__matriz() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardar__seguimiento__matriz($_POST);

     echo json_encode($jason);

  }  


  public function guardar__presupuesto() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardar__presupuesto($_POST);

     echo json_encode($jason);

  }  

  public function guardar__beneficiarios() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardar__beneficiarios($_POST);

     echo json_encode($jason);

  }  

  public function guardar__objetivos__generales() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardar__objetivos__generales($_POST);

     echo json_encode($jason);

  }  

  public function guardar__resumen() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardar__resumen($_POST);

     echo json_encode($jason);

  }  

  public function obtener__informacion__seguimiento__estados() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacionSeguimientoEstados']=$this->constructor->obtener__informacion__seguimiento__estados($codigo);

     echo json_encode($jason);

  }  

  public function seguimiento__obtener__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacionSeguimiento']=$this->constructor->seguimiento__obtener__seguimiento($codigo);

     echo json_encode($jason);

  }    

  public function componentes__montos__asignados() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['componentesAsignados']=$this->constructor->componentes__montos__asignados($codigo);
     $jason['femeninosAsignados']=$this->constructor->componentes__montos__asignados__femenino($codigo);
     $jason['priorizadosAsignados']=$this->constructor->componentes__montos__asignados__priorizado($codigo);

     echo json_encode($jason);

  }    

  public function beneficiarios__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['beneficiarios']=$this->constructor->beneficiarios__seguimiento($codigo);


     echo json_encode($jason);

  }    

  public function objetivos__especificos() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['objetivoEspecificos']=$this->constructor->objetivos__especificos($codigo);


     echo json_encode($jason);

  }    

   public function informacion__general__proyecto__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informacionGeneral']=$this->constructor->informacion__general__proyecto__seguimiento($codigo);


     echo json_encode($jason);

  }    


   public function estado__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['estadoSeguimiento']=$this->constructor->estado__seguimiento($codigo);


     echo json_encode($jason);

  }    


  public function actualizar__informe__enviar() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->actualizar__informe__enviar($_POST);


     echo json_encode($jason);

  }    

  public function generar__informe__de__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['base64']=$this->constructor->generar__informe__de__seguimiento($codigo);


     echo json_encode($jason);

  }    

  public function activacion__documentos() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['activacion']=$this->constructor->activacion__documentos($codigo);


     echo json_encode($jason);

  }    

   public function guardarDocumentosGenerales__seguimiento__v1() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardarDocumentosGenerales__seguimiento__v1($codigo);


     echo json_encode($jason);

  }    

  public function guardarDocumentosGenerales__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->guardarDocumentosGenerales__seguimiento($codigo);


     echo json_encode($jason);

  }    


  public function buscarDocumentos__requisitos__seguimiento__v1() {

     extract($_POST);


     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['informe']=$this->constructor->seleccionaGlobales_informe__v1($codigo);
     $jason['declaracionJuramentada']=$this->constructor->seleccionaGlobales__declaracionJuramentada__v1($codigo);
     $jason['fotografias']=$this->constructor->seleccionaGlobales__fotografias__v1($codigo);
     $jason['memorias']=$this->constructor->seleccionaGlobales__memorias__v1($codigo);
     $jason['certificaciones']=$this->constructor->seleccionaGlobales__certificaciones__v1($codigo);
     $jason['otros']=$this->constructor->seleccionaGlobales_otros__v1($codigo);


     echo json_encode($jason);


  }    

  public function buscarDocumentos__requisitos__seguimiento() {

     extract($_POST);


     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['declaracionJuramentada']=$this->constructor->seleccionaGlobales__declaracionJuramentada($codigo);
     $jason['fotografias']=$this->constructor->seleccionaGlobales__fotografias($codigo);
     $jason['memorias']=$this->constructor->seleccionaGlobales__memorias($codigo);
     $jason['certificaciones']=$this->constructor->seleccionaGlobales__certificaciones($codigo);
     $jason['otros']=$this->constructor->seleccionaGlobales_otros($codigo);

     echo json_encode($jason);

  }    

  public function observable__enviarDocumentacion__v1() {

     extract($_POST);


     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);


     $jason['mensaje']=$this->constructor->actualizarArchivosGenerales__v1($_POST);

     echo json_encode($jason);

  }    


  public function actualizarArchivosGenerales__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);

     $jason['mensaje']=$this->constructor->actualizarArchivosGenerales($_POST);
     $jason['general']=$this->constructor->select__general__documentos__incentivo($_POST);

     echo json_encode($jason);

  }    

  public function buscar__proyecto__existente__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);


     $jason['informacionCompleta']=$this->constructor->buscar__proyecto__existente($_POST);
     echo json_encode($jason);

  }


  public function proyectos__aprobados__codigo__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);


     $jason['informacion__codigo']=$this->constructor->proyectos__aprobados__codigo($_POST);
     echo json_encode($jason);

  }

  public function proyectos__aprobados__nombres__seguimiento() {

     extract($_POST);

     $purifier = $this->constructor__seguridad->purificar();
     $this->constructor__seguridad->purifyFormData($_POST, $purifier);


     $jason['informacion__nombre']=$this->constructor->proyectos__aprobados__nombres($_POST);
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
