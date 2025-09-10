<?php


namespace App\Routes\RoutesBandeja\Base;

use App\Presentation\Controllers\ControllersBandeja;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;


class BaseBandeja {


    private $repositorio;

    public function __construct() {
        $this->constructor = new ControllersBandeja();
        $this->constructor__seguridad = new Seguridad();
    }

    public function obtener__negacion__del__analista() {
      
       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacion']=$this->constructor->obtener__negacion__del__analista($_POST);
       echo json_encode($jason);

    } 

    public function actualizarNegacionAnalistaProyectoCalificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->actualizarNegacionAnalistaProyectoCalificacion($_POST);
       echo json_encode($jason);

    } 

    public function insertaInformacionGeneral__bandejas() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->insertaInformacionGeneral__bandejas($_POST);
       echo json_encode($jason);

    } 

    public function insertaObjetivosEspecificos__bandejas() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->insertaObjetivosEspecificos__bandejas($_POST);
       echo json_encode($jason);

    } 

    public function insertaDescripcionFecha__bandejas() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->insertaDescripcionFecha__bandejas($_POST);
       echo json_encode($jason);

    } 

    public function observable__obtener__documentos__versiones__observaciones() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacion']=$this->constructor->observable__obtener__documentos__versiones__observaciones($codigo);

       echo json_encode($jason);

    }


    public function consultar__auxiliar__comites() {


       extract($_POST);
       $jason['informacion']=$this->constructor->consultar__auxiliar__comites();

       echo json_encode($jason);

    }

    public function actualizar__nombres__notificaciones() {


       extract($_POST);
       $jason['mensaje']=$this->constructor->actualizar__nombres__notificaciones($_POST);

       echo json_encode($jason);

    }


    public function informacion__observacion__infra() {

       extract($_POST);

       $jason['observacionesInfra']=$this->constructor->informacion__observacion__infra($_POST);
       echo json_encode($jason);

    }    


    public function enviar__acta__final() {

       extract($_POST);

       $jason['base64']=$this->constructor->enviar__acta__final($_POST);
       echo json_encode($jason);

    }    


    public function obtener__documento__acta() {

       extract($_POST);

       $jason['base64']=$this->constructor->obtener__documento__acta($_POST);
       echo json_encode($jason);

    }    

    public function enviar__acta() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->enviar__acta($_POST);
       echo json_encode($jason);

    }    

    public function informacion__contenido__acta() {

       extract($_POST);

       $jason['informacionContenidoActa']=$this->constructor->informacion__contenido__acta($_POST);
       echo json_encode($jason);

    }

    public function informacion__secretaria__comite() {

       extract($_POST);

       $jason['secretariaComite']=$this->constructor->informacion__secretaria__comite($_POST);
       echo json_encode($jason);

    }

    public function informacion__ministro() {

       extract($_POST);

       $jason['ministro']=$this->constructor->informacion__ministro($_POST);
       echo json_encode($jason);

    }

    public function comite__informacion__acta__pdf() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['pdfResult']=$this->constructor->comite__informacion__acta__pdf($_POST);
       $jason['wordResult']=$this->constructor->comite__informacion__acta__word($_POST);
       echo json_encode($jason);

    }
    
    public function informacion__presidente__del__comite() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionPresidenteDelComite']=$this->constructor->informacion__presidente__del__comite($_POST);
       echo json_encode($jason);

    }


    public function comite__informacion__proyectos__selectivo() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionComite']=$this->constructor->comite__informacion__proyectos__selectivo($_POST);
       echo json_encode($jason);

    }


    public function actualizar__proyecto__con__notificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->actualizar__proyecto__con__notificacion($_POST);
       echo json_encode($jason);

    }


    public function obtenerExistente__notificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['docuRuta']=$this->constructor->obtenerExistente__notificacion($_POST);
       echo json_encode($jason);

    }

    public function obtener__informacion__funcionario() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionFuncionario']=$this->constructor->obtener__informacion__funcionario($_POST);
       echo json_encode($jason);

    }

    public function notificacion__comite__favorable__conjunto__pdf__contenido() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['base64']=$this->constructor->notificacion__comite__favorable__conjunto__pdf__contenido($_POST);
       echo json_encode($jason);

    }

    public function notificacion__comite__favorable__conjunto() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['contenidoArray']=$this->constructor->notificacion__comite__favorable__conjunto($_POST);
       echo json_encode($jason);

    }

    public function generar__notificacion__comite__favorable__certificados__realizados() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['pdfResult']=$this->constructor->generar__notificacion__comite__favorable__certificados__realizados($_POST);
       echo json_encode($jason);

    }


    public function notificacion__comite__favorable__certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['pdfResult']=$this->constructor->notificacion__comite__favorable__certificacion($_POST);
       echo json_encode($jason);

    }

    public function notificacion__comite__favorable() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['pdfResult']=$this->constructor->notificacion__comite__favorable($_POST);
       echo json_encode($jason);

    }
    
    public function informacionAnalista__calificacion__general() {

       extract($_POST);

       $jason['informacionCalificacionGeneral']=$this->constructor->informacionAnalista__calificacion__general($_POST);
       echo json_encode($jason);

    }

    public function recibir__estado__calificacion__ciudadanos() {

       extract($_POST);

       $jason['calificacionRealizado']=$this->constructor->recibir__estado__calificacion__ciudadanos($_POST);
       echo json_encode($jason);

    }

    public function inserta__ratificacion__ingreso() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->inserta__ratificacion__ingreso($_POST);
       echo json_encode($jason);

    }

    public function cuantos__proyectos__asociados() {

       extract($_POST);

       $jason['cuantosProyectos__asociados']=$this->constructor->cuantos__proyectos__asociados($_POST);
       echo json_encode($jason);

    }

    public function cuantos__proyectos() {

       extract($_POST);

       $jason['cuantosProyectos']=$this->constructor->cuantos__proyectos($_POST);
       echo json_encode($jason);

    }

    public function comite__informacion__convocatoria__pdf() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['pdfResult']=$this->constructor->comite__informacion__convocatoria__pdf($_POST);
       echo json_encode($jason);

    }
    

    public function iniciarSesion__comite() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->iniciarSesion__comite($_POST);
       echo json_encode($jason);

    }
    

    public function participar__comite() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->participar__comite($_POST);
       echo json_encode($jason);

    }

    public function comite__informacion__proyectos__miembros() {


       extract($_POST);

       $jason['informacionComites']=$this->constructor->comite__informacion__proyectos__miembros($_POST);
       echo json_encode($jason);

    }


    public function comite__informacion__proyectos() {


       extract($_POST);

       $jason['informacionComites']=$this->constructor->comite__informacion__proyectos($_POST);
       echo json_encode($jason);

    }

    public function consultar__observacion__proyecto__comite__u() {


       extract($_POST);

       $jason['informacionConstulaProyectoAnalista']=$this->constructor->consultar__observacion__proyecto__comite__u($_POST);
       echo json_encode($jason);

    }


    public function insertar__observacion__proyecto__comite__u() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->insertar__observacion__proyecto__comite__u($_POST);
       echo json_encode($jason);

    }

    public function sector__componentes__calificados__infra() {


       extract($_POST);


       $jason['existenciaSector']=$this->constructor->sector__calificado__comite($codigo);
       $jason['existenciaInfra']=$this->constructor->componente__calificado__comite($codigo);

       echo json_encode($jason);

    }

    public function proyectos__revisar__comite__revisar() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['proyectosRevisar']=$this->constructor->proyectos__revisar__comite__revisar($idComite,$idCredencial);

       echo json_encode($jason);

    }

    public function proyectos__revisar__comite() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['proyectosRevisar']=$this->constructor->proyectos__revisar__comite($idComite);

       echo json_encode($jason);

    }

    public function personas__convocadas() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->personas__convocadas($valor,$idTabla,$idComite);

       echo json_encode($jason);

    }

    public function iniciar__sesion() {


       extract($_POST);

       $jason['mensaje']=$this->constructor->iniciar__sesion($idComite);

       echo json_encode($jason);

    }


    public function despriozar__proyectos() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->despriozar__proyectos($valor,$proyectoId,$idComite);

       echo json_encode($jason);

    }

    public function priorizar__proyectos() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->priorizar__proyectos($valor,$proyectoId,$idComite);

       echo json_encode($jason);

    }

    public function obtener__proyectos__recomendados__analistas() {


       extract($_POST);
       $jason['proyectosComiteRecomandos']=$this->constructor->obtener__proyectos__recomendados__analistas($idComite,$estado);

       echo json_encode($jason);

    }


    public function obtener__proyectos__recomendados() {


       extract($_POST);
       $jason['proyectosComiteRecomandos']=$this->constructor->obtener__proyectos__recomendados($idComite);

       echo json_encode($jason);

    }

    public function obtenerProyectos__comite() {


       extract($_POST);
       $jason['proyectosComite']=$this->constructor->obtenerProyectos__comite($idComite);

       echo json_encode($jason);

    }

    public function estadoComite() {


       extract($_POST);
       $jason['estadoComite']=$this->constructor->estadoComite($idComite);

       echo json_encode($jason);

    }

    public function id__comite__obtenido() {


       extract($_POST);
       $jason['idComite']=$this->constructor->id__comite__obtenido($_POST);

       echo json_encode($jason);

    }

    public function enviar__convocatoria() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->enviar__convocatoria($_POST);


       echo json_encode($jason);

    }

    public function comite__informacion__convocatoria() {


       extract($_POST);
       $jason['informacionConvocatoria']=$this->constructor->comite__informacion__convocatoria($_POST);

       echo json_encode($jason);

    }

    public function generar__convocatoria() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['texto']=$this->constructor->generar__convocatoria__textual($_POST);
       $jason['pdfResult']=$this->constructor->generar__convocatoria($_POST);

       echo json_encode($jason);

    }

    public function eliminar__personal__comite__participantes() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->eliminar__personal__comite__participantes($_POST);


       echo json_encode($jason);

    }

    public function eliminar__personal__comite() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->eliminar__personal__comite($_POST);


       echo json_encode($jason);

    }

    public function personal__comite__editado__participantes() {


       extract($_POST);
       $jason['personalComite__participante']=$this->constructor->personal__comite__editado__participantes($_POST);

       echo json_encode($jason);

    }

    public function personal__comite__editado() {


       extract($_POST);
       $jason['personalComite']=$this->constructor->personal__comite__editado($_POST);

       echo json_encode($jason);

    }

    public function agregarPersonal__comite__sesion__participantes() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->agregarPersonal__comite__sesion__participantes($_POST);


       echo json_encode($jason);

    }


    public function agregarPersonal__comite__sesion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->agregarPersonal__comite__sesion($_POST);


       echo json_encode($jason);

    }

    public function funcionarios__maxima__comite() {

       extract($_POST);
       $jason['funcionariosMinisterio']=$this->constructor->funcionarios__ministerio($_POST);

       echo json_encode($jason);

    }


    public function funcionarios__ministerio() {

       extract($_POST);
       $jason['funcionariosMinisterio']=$this->constructor->funcionarios__ministerio($_POST);

       echo json_encode($jason);

    }


    public function delegadoMaximaAutoridad() {

       extract($_POST);
       $jason['delegadoMaximaAutoridad']=$this->constructor->delegadoMaximaAutoridad($_POST);

       echo json_encode($jason);

    }

    public function personalComite() {

       extract($_POST);
       $jason['personalComite']=$this->constructor->personalComite($_POST);

       echo json_encode($jason);

    }

    public function obtener__informacion__analistas() {

       extract($_POST);

       $jason['informacionAnalistaIngresado']=$this->constructor->obtener__informacion__analistas($_POST);
       echo json_encode($jason);

    } 



    public function bandeja__recomendados__comite() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recomendados__comite($_POST);
       echo json_encode($jason);

    } 


    public function enviar__proyecto__comite__calificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->enviar__proyecto__comite__calificacion($_POST);


       echo json_encode($jason);

    }

    public function informacion__texto__devuelto() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['informacionDevuelta']=$this->constructor->informacion__texto__devuelto($_POST);


       echo json_encode($jason);

    }


    public function regresar__analista__recomendacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->regresar__analista__recomendacion($_POST);


       echo json_encode($jason);

    }

    public function bandeja__recomendados__informacion__infraestructura() {

       extract($_POST);

       foreach ($this->constructor->bandeja__recomendados__informacion__infraestructura($_POST) as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
       }

       $jason['informacionRecomendacion__infra']=$this->constructor->bandeja__recomendados__informacion__infraestructura($_POST);
       $jason['informacionUsuario__analista']=$this->constructor->obtener__usuario($idUsuarioBd);
       echo json_encode($jason);

    } 

    public function bandeja__recomendados__informacion() {

       extract($_POST);

       foreach ($this->constructor->bandeja__recomendados__informacion($_POST) as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
       }

       $jason['informacionRecomendacion']=$this->constructor->bandeja__recomendados__informacion($_POST);
       $jason['informacionUsuario']=$this->constructor->obtener__usuario($idUsuarioBd);
       echo json_encode($jason);

    } 

    public function bandeja__recomendados() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recomendados($_POST);
       echo json_encode($jason);

    } 


    public function recomendar__analista() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->recomendar__analista($_POST);


       echo json_encode($jason);

    }

    public function insertar__informe() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->insertar__informe($_POST);


       echo json_encode($jason);

    }


    public function generar__informe__comite() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe__comite($_POST);


       echo json_encode($jason);

    }


    public function generar__informe() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe($_POST);


       echo json_encode($jason);

    }


    public function baja__proyecto() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['bajaProyecto']=$this->constructor->baja__proyecto($_POST);


       echo json_encode($jason);

    }

    public function obtener__informacion__observaciones() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['observacionDescripcion']=$this->constructor->obtener__observacion__descripcion($_POST);
       $jason['observacionRequisitos']=$this->constructor->obtener__observacion__requisitos($_POST);
       $jason['observacionSector']=$this->constructor->obtener__observacion__sector($_POST);
       $jason['observacionBeneficiarios']=$this->constructor->obtener__observacion__beneficiarios($_POST);
       $jason['observacionPresupuesto']=$this->constructor->obtener__observacion__presupuesto($_POST);
       $jason['observacionCronograma']=$this->constructor->obtener__observacion__cronograma($_POST);
       $jason['observacionResultado']=$this->constructor->obtener__observacion__resultado($_POST);
       $jason['observacionSeguimiento']=$this->constructor->obtener__observacion__seguimiento($_POST);

       echo json_encode($jason);

    }


    public function actualizar__proyecto__enviar__observar() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->actualizar__proyecto__enviar__observar($_POST);
       echo json_encode($jason);

    }

    public function eliminar__archivo__proyecto() {


       extract($_POST);

       
       $jason['mensaje']=$this->constructor->eliminar__archivo__proyecto($codigo);
       echo json_encode($jason);

    }


    public function validarSeguimiento__respaldo() {


       extract($_POST);

       
       $jason['mensaje']=$this->constructor->validarSeguimiento__respaldo($codigo);
       echo json_encode($jason);

    }


    public function guardarPronosticos__respaldo() {


       extract($_POST);

       
       $jason['mensaje']=$this->constructor->guardarPronosticos__respaldo($codigo,$estadoCalificacion);
       echo json_encode($jason);

    }


    public function guardarCronogramaDeActividades__respaldo() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramaDeActividades__respaldo($codigo,$estadoCalificacion);
       echo json_encode($jason);

    }     


     public function guardarEstadoComponentes__respaldo() {


       extract($_POST);

       $jason['mensaje']=$this->constructor->guardarEstadoComponentes__respaldo($codigo,$idCredencial,$estadoCalificacion);

       echo json_encode($jason);

    }   

    public function insertaBeneficiario__respaldo() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertaBeneficiario__respaldo($_POST);
       echo json_encode($jason);


    }      


    public function insertaSector__respaldos() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertaSector__respaldos($_POST);
       echo json_encode($jason);


    }      


    public function guarda__documentos__habilitantes__respaldos() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->guarda__documentos__habilitantes__respaldos($codigo,$estado);
       echo json_encode($jason);

    }    

    public function insertarDescripcionProyecto__modificacion__observacion() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->insertarDescripcionProyecto__modificacion__observacion($_POST);
       echo json_encode($jason);

    } 

    public function estado__calificacion__general() {

       extract($_POST);

       $jason['estadoCalificacion']=$this->constructor->estado__calificacion__general($_POST);
       echo json_encode($jason);

    } 


    public function calificador__obtener() {

       extract($_POST);

       $jason['informacionCompleta']=$this->constructor->obtenerNombrePersona($_POST);
       echo json_encode($jason);

    } 

    public function estado__calificacion() {

       extract($_POST);

       $jason['estadoCalificacion']=$this->constructor->estado__calificacion($_POST);
       echo json_encode($jason);

    } 

    public function enviar__observacion__general() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->enviar__observacion__general($_POST);
       echo json_encode($jason);

    } 

    public function obtener__observaciones__generales__usuarios() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['obserbacionDescripcion']=$this->constructor->observacion__descripcion__usuario($_POST);
       $jason['obserbacioRequisitos']=$this->constructor->observacion__requisitos__usuario($_POST);
       $jason['obserbacionSector']=$this->constructor->observacion__sector__usuario($_POST);
       $jason['obserbacionBeneficiarios']=$this->constructor->observacion__beneficiarios__usuario($_POST);
       $jason['obserbacionComponentes']=$this->constructor->observacion__componentes__usuario($_POST);
       $jason['obserbacionCronogramaActividades']=$this->constructor->observacion__cronogramaActividades__usuario($_POST);
       $jason['obserbacionResultadoMetas']=$this->constructor->observacion__resultadoMetas__usuario($_POST);
       $jason['obserbacionSeguimiento']=$this->constructor->observacion__seguimiento__usuario($_POST);

       echo json_encode($jason);

    } 



    public function obtener__observaciones__generales() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['obserbacionDescripcion']=$this->constructor->observacion__descripcion($_POST);
       $jason['obserbacioRequisitos']=$this->constructor->observacion__requisitos($_POST);
       $jason['obserbacionSector']=$this->constructor->observacion__sector($_POST);
       $jason['obserbacionBeneficiarios']=$this->constructor->observacion__beneficiarios($_POST);
       $jason['obserbacionComponentes']=$this->constructor->observacion__componentes($_POST);
       $jason['obserbacionCronogramaActividades']=$this->constructor->observacion__cronogramaActividades($_POST);
       $jason['obserbacionResultadoMetas']=$this->constructor->observacion__resultadoMetas($_POST);
       $jason['obserbacionSeguimiento']=$this->constructor->observacion__seguimiento($_POST);

       echo json_encode($jason);

    } 


    public function observacion__ministerio() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->observacion__ministerio($_POST);
       echo json_encode($jason);

    } 


    public function orden__obtenido() {

       extract($_POST);

       $jason['orden']=$this->constructor->orden__obtenido($idRol,$fisicamenteEstructura);
       echo json_encode($jason);

    } 

    public function insertaReenvio() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->insertaReenvio($_POST);
       echo json_encode($jason);

    } 

    public function usuario__titpo__cargar() {

       extract($_POST);

       $jason['usuarioTipo']=$this->constructor->usuario__titpo__cargar($codigo,$idCredencial);
       echo json_encode($jason);

    } 

    public function personas__redirigir() {

       extract($_POST);

       $jason['personasRedirigir']=$this->constructor->personas__redirigir($fisicamenteEstructura,$idRol);
       echo json_encode($jason);

    } 

    public function informacion__cargo() {

       extract($_POST);

       $jason['informacionCargo']=$this->constructor->informacion__cargo($idCredencial);
       echo json_encode($jason);

    } 


    public function informacion__analistas() {

       extract($_POST);

       $jason['informacionAnalistas']=$this->constructor->informacion__analistas($idCredencial);
       echo json_encode($jason);

    } 

    public function bandeja__recibidos() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recibidos($idCredencial,$idRol,$fisicamenteEstructura);
       echo json_encode($jason);

    } 

    public function obtenerPresupuestoNiveles__visualizar() {

       extract($_POST);

       $jason['presupuestoCOmponentes']=$this->constructor->obtenerPresupuestoNiveles__2($codigo,$anio,$tiposComponentes);
       $jason['presupuestoCOmponentes__codigo']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos__2($codigo,$anio,$tiposComponentes);
       $jason['presupuestoCOmponentes__codigo__footer']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos__footer__2($codigo,$anio,$tiposComponentes);

       echo json_encode($jason);


    }      


    public function sectorProyecto__escogido() {


       extract($_POST);

       $jason['sectorProyecto']=$this->constructor->sectorProyecto__escogido($codigo);
       echo json_encode($jason);


    }      

    public function componentes__escogidos() {

      extract($_POST);

       $jason['componentesVisualizar']=$this->constructor->componentes__escogidos($codigo);
       echo json_encode($jason);

    }    
 

    public function proyectosEnviadosCiudadano() {

       extract($_POST);

       $jason['data']=$this->constructor->proyectosEnviadosCiudadano($idCredencial);
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
