<?php


namespace App\Routes\RoutesModificacion\Base;

use App\Presentation\Controllers\ControllersModificacion;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;
use App\Domain\Repositories\Repositories;

class BaseModificacion {


    private $repositorio;

    public function __construct() {
        $this->constructor = new ControllersModificacion();
        $this->constructor__seguridad = new Seguridad();
        $this->repositorio = new Repositories();
    }

    public function proyectosEnviadosCiudadano__modificacion() {

       extract($_POST);

       $jason['data']=$this->constructor->proyectosEnviadosCiudadano($idCredencial);
       echo json_encode($jason);

    }    

    public function informacionSolicitud__cuantos__versiones() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['cuantos']=$this->constructor->cuantos__tramitesTieneModificaciones($codigo);
       echo json_encode($jason);

    }

    public function informacionSolicitud__cuantos() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionSolicitud__cuantos']=$this->constructor->informacionSolicitud__pendiente__cuantos($codigo);
       echo json_encode($jason);

    }

    public function obtener__proyectos__recomendados__analistas__modificacion__dos() {


       extract($_POST);
       $jason['proyectosComiteRecomandos__mod']=$this->constructor->obtener__proyectos__recomendados__analistas($idComite,$estado);

       echo json_encode($jason);

    }


    public function obtener__proyectos__recomendados__analistas__modificacion() {


       extract($_POST);
       $jason['proyectosComiteRecomandos__modificacion']=$this->constructor->obtener__proyectos__recomendados__analistas($idComite,$estado);

       echo json_encode($jason);

    }


    public function despriozar__proyectos__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->despriozar__proyectos($valor,$proyectoId,$idComite);

       echo json_encode($jason);

    }


    public function obtener__proyectos__recomendados__modificacion() {


       extract($_POST);
       $jason['proyectosComiteRecomandos']=$this->constructor->obtener__proyectos__recomendados($idComite);

       echo json_encode($jason);

    }

    public function priorizar__proyectos__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->priorizar__proyectos($valor,$proyectoId,$idComite);

       echo json_encode($jason);

    }

    public function obtenerProyectos__comite__modificacion() {


       extract($_POST);
       $jason['proyectosComite']=$this->constructor->obtenerProyectos__comite($idComite);

       echo json_encode($jason);

    }

    public function bandeja__recomendados__comite__modificacion() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recomendados__comite($_POST);
       echo json_encode($jason);

    } 

    public function notificacion__generar() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->notificacion__generar($_POST);


       echo json_encode($jason);

    }    

    public function generar__informe__comite__modificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe__comite($_POST);


       echo json_encode($jason);

    }


    public function sector__componentes__calificados__infra__modificacion() {


       extract($_POST);


       $jason['existenciaSector']=$this->constructor->sector__calificado__comite($codigo);
       $jason['existenciaInfra']=$this->constructor->componente__calificado__comite($codigo);

       echo json_encode($jason);

    }


    public function bandeja__recomendados__informacion__modificacion__recomendacion__analistas() {

       extract($_POST);

       $jason['informacionRecomendacion']=$this->constructor->bandeja__recomendados__informacion__modificacion__recomendacion__analistas($_POST);
       echo json_encode($jason);

    } 


    public function bandeja__recomendados__informacion__modificacion() {

       extract($_POST);

       foreach ($this->constructor->bandeja__recomendados__informacion($_POST) as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
       }

       $jason['informacionRecomendacion']=$this->constructor->bandeja__recomendados__informacion($_POST);
       $jason['informacionUsuario']=$this->constructor->obtener__usuario($idUsuarioBd);
       echo json_encode($jason);

    } 

    public function bandeja__recomendados__informacion__infraestructura__modificacion() {

       extract($_POST);

       foreach ($this->constructor->bandeja__recomendados__informacion__infraestructura($_POST) as $valor) {
        $idUsuarioBd=$valor["idUsuario"];
       }

       $jason['informacionRecomendacion__infra']=$this->constructor->bandeja__recomendados__informacion__infraestructura($_POST);
       $jason['informacionUsuario__analista']=$this->constructor->obtener__usuario($idUsuarioBd);
       echo json_encode($jason);

    } 
    
    public function enviar__proyecto__comite__calificacion__a__b__c__negacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->enviar__proyecto__comite__calificacion__a__b__c__negacion($_POST);


       echo json_encode($jason);

    }        

    public function enviar__proyecto__comite__calificacion__modificacion__a__c() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->enviar__proyecto__comite__calificacion__a__c($_POST);


       echo json_encode($jason);

    }    

    public function enviar__proyecto__comite__calificacion__modificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->enviar__proyecto__comite__calificacion($_POST);


       echo json_encode($jason);

    }

    public function presupuesto__real__proyecto() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['informacion']=$this->constructor->presupuesto__real__proyecto($codigo);


       echo json_encode($jason);

    }

    public function regresar__analista__recomendacion__modificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->regresar__analista__recomendacion($_POST);


       echo json_encode($jason);

    }

    public function bandeja__recomendados__modificacion() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recomendados($_POST);
       echo json_encode($jason);

    } 



    public function recomendar__analista__modificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->recomendar__analista($_POST);


       echo json_encode($jason);

    }

    public function generar__informe__modificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe($_POST);


       echo json_encode($jason);

    }

    public function observacion__ministerio__modificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->observacion__ministerio($_POST);
       echo json_encode($jason);

    } 

    public function obtener__observaciones__generales__modificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['obserbacionDescripcion']=$this->constructor->observacion__descripcion($_POST);
       $jason['obserbacionComponentes']=$this->constructor->observacion__componentes($_POST);
       $jason['obserbacionCronogramaActividades']=$this->constructor->observacion__cronogramaActividades($_POST);

       echo json_encode($jason);

    } 


    public function insertaReenvio__modificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->insertaReenvio($_POST);
       echo json_encode($jason);

    } 


    public function obtenerPresupuestoNiveles__visualizar__modificacion() {

       extract($_POST);

       $jason['presupuestoCOmponentes']=$this->constructor->obtenerPresupuestoNiveles__2($codigo,$anio,$tiposComponentes,$idTramite);
       $jason['presupuestoCOmponentes__codigo']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos__2($codigo,$anio,$tiposComponentes,$idTramite);
       $jason['presupuestoCOmponentes__codigo__footer']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos__footer__2($codigo,$anio,$tiposComponentes,$idTramite);

       echo json_encode($jason);


    }      

    public function bandeja__recibidos__modificacion() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recibidos__modificacion($idCredencial,$idRol,$fisicamenteEstructura);
       echo json_encode($jason);

    } 

    public function informacionGeneralModificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['informacion']=$this->constructor->informacionGeneralModificacion($_POST);
       echo json_encode($jason);

    }


    public function actualizar__proyecto__enviar__observar__modificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->actualizar__proyecto__enviar__observar__modificacion($_POST);
       echo json_encode($jason);

    }


    public function justificaciones__globales() {

       extract($_POST);

       $jason['justificacionDescripcion']=$this->constructor->justificaciones__descripcion($_POST);
       $jason['justificacionPresupuesto']=$this->constructor->justificaciones__presupuesto($_POST);
       $jason['justificacionActividades']=$this->constructor->justificaciones__actividades($_POST);

       echo json_encode($jason);

    }    

    public function justificacion__cronogramaDeActividades() {

       extract($_POST);

       $jason['justificacion']=$this->constructor->justificacion__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }    

    public function guardarCronogramaDeActividades__respaldo__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramaDeActividades__respaldo__modificacion($_POST);
       echo json_encode($jason);

    }   

    public function validarCronogramaDeActividades__modificacion() {

       extract($_POST);


       $jason['actividades']=$this->constructor->validarActividades($codigo,$idTramite);
       $jason['componentes']=$this->constructor->validarComponentes($codigo,$idTramite);
       $jason['provincia']=$this->constructor->validarProvincia($codigo,$idTramite);
       $jason['canton']=$this->constructor->validarCanton($codigo,$idTramite);
       $jason['parroquia']=$this->constructor->validarParroquia($codigo,$idTramite);
       $jason['pais']=$this->constructor->validarPais($codigo,$idTramite);
       $jason['ciudad']=$this->constructor->validarCiudad($codigo,$idTramite);

       echo json_encode($jason);

    }     

    public function guardarCronogramas__agregadosAdicionales__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramas__agregadosAdicionales($_POST);
       echo json_encode($jason);

    }        

    public function eliminarCronogramas__agregadosAdicionales__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->eliminarCronogramas__agregadosAdicionales($_POST);
       echo json_encode($jason);

    }         


    public function guardarCronogramas__adicionales__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramas__adicionales($_POST);
       echo json_encode($jason);

    }     


     public function actualizarTipo__cronogramaDeActividades__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarTipo__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }     

     public function actualizarRubrosComponentesMeses__cronogramaDeActividades__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentesMeses__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }     

     public function actualizarRubrosComponentesTextos__cronogramaDeActividades__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentesTextos__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }     

    public function cronogramaDeActividades__modificacion() {


       extract($_POST);
       $jason['cronogramaDeActividades']=$this->constructor->cronogramaDeActividades($codigo,$anio,$tiposComponentes,$idTramite);
       echo json_encode($jason);

    }   

    public function insertarDescripcionProyecto__modificacion__observacion__modificacion() {

       extract($_POST);

      $jason['mensaje']=$this->constructor->insertarDescripcionProyecto__modificacion__observacion__modificacion($_POST);

       echo json_encode($jason);

    } 


     public function guardarComponentesGlobal_validacion_modificacion() {


       extract($_POST);

       $jason['infraObligatorios']=$this->constructor->obtener__infraestructuraObligatorios($codigo,$idTramite);
       $jason['montosAnuales']=$this->constructor->obtenerSumasGlobalesAnuales($codigo,$idTramite);
       $jason['documentoNecesarios']=$this->constructor->obtenerArchivosComponentes__infras($codigo,$anio,$idTramite);
       $jason['componente5']=$this->constructor->obtenerArchivosComponentes__infras__5($codigo,$idTramite);
       $jason['priorizados']=$this->constructor->obtenerSumasGlobalesAnuales__priorizados__comparacion($codigo,$idTramite);
       $jason['femeninos']=$this->constructor->obtenerSumasGlobalesAnuales__femeninos__comparacion($codigo,$idTramite);
       $jason['descripcionNull']=$this->constructor->componentes__descripcion__null($codigo,$idTramite);
       $jason['obligatoriosComponentes']=$this->constructor->componentes__obligatorios__ingresar($codigo,$idTramite);

       echo json_encode($jason);

    }     
    
    public function justificacion__componentes() {

       extract($_POST);

       $jason['justificacion']=$this->constructor->justificacion__componentes($_POST);
       echo json_encode($jason);

    }    

     public function guardarEstadoComponentes__respaldo__modificacion() {


       extract($_POST);

       $jason['mensaje']=$this->constructor->guardarEstadoComponentes__respaldo($codigo,$idCredencial,$estadoCalificacion,$justificacionModificacion,$idTramite);

       echo json_encode($jason);

     }   

     public function obtenerSumasGlobalesAnuales__modificacion() {


       extract($_POST);

       $jason['montosAnuales']=$this->constructor->obtenerSumasGlobalesAnuales($codigo,$idTramite);
       $jason['montosAnuales__priorizados']=$this->constructor->obtenerSumasGlobalesAnuales__priorizados($codigo,$idTramite);
       $jason['montosAnuales__femeninos']=$this->constructor->obtenerSumasGlobalesAnuales__femeninos($codigo,$idTramite);

       echo json_encode($jason);

    }     
    

    public function actualizarRubrosComponentesTextos__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentesTextos__modificacion($_POST);
       echo json_encode($jason);

    }

     public function obtenerPresupuestoNiveles__modificacion() {

       extract($_POST);

       $jason['presupuestoCOmponentes']=$this->constructor->obtenerPresupuestoNiveles($codigo,$anio,$tiposComponentes,$idTramite);
       $jason['presupuestoCOmponentes__codigo']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos($codigo,$anio,$tiposComponentes,$idTramite);
       $jason['presupuestoCOmponentes__codigo__footer']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos__footer($codigo,$anio,$tiposComponentes,$idTramite);

       echo json_encode($jason);


    }      


    public function actualizarRubrosComponentes__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentes__modificacion($_POST);
       echo json_encode($jason);

    }


    public function calificar__solicitud__de__modificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->calificar__solicitud__de__modificacion($_POST);
       echo json_encode($jason);

    }


    public function informacionSolicitud__general__calificar__analista() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionSolicitud']=$this->constructor->informacionSolicitud__general__calificar__analista($_POST);
       echo json_encode($jason);

    }

    public function informacionSolicitud__generalEsperada() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionSolicitudEsperada']=$this->constructor->informacionSolicitud__generalEsperada($codigo);
       echo json_encode($jason);

    }


    public function informacionSolicitud__general() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionSolicitud']=$this->constructor->informacionSolicitud__general($codigo);
       echo json_encode($jason);

    }

    public function informacionSolicitud() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionSolicitud']=$this->constructor->informacionSolicitud__pendiente($codigo);
       echo json_encode($jason);

    }

    public function registroSolicitudDeModificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->registroSolicitudDeModificacion($_POST);
       echo json_encode($jason);

    }

     public function codigo__modificacion() {


       extract($_POST);

       $jason['idTramite']=$this->constructor->codigo__modificacion($codigo);

       echo json_encode($jason);

     }   

     public function obtenerInformacionGeneral__proyecto() {


       extract($_POST);

       $jason['informacionGeneral']=$this->constructor->obtenerInformacionGeneral__proyecto($codigo);

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
