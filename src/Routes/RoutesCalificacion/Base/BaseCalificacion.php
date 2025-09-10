<?php


namespace App\Routes\RoutesCalificacion\Base;

use App\Presentation\Controllers\ControllersCalificacion;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;


class BaseCalificacion {


    private $repositorio;

    public function __construct() {
        $this->constructor = new ControllersCalificacion();
        $this->constructor__seguridad = new Seguridad();
    }

    public function existente__preliminar__envio() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacion']=$this->constructor->existente__preliminar__envio($_POST);
       echo json_encode($jason);

    }

    public function eliminarAnexo__infra__adicional() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->eliminarAnexo__infra__adicional($_POST);
       echo json_encode($jason);

    }

   public function documentosAnexosAdicionales__unitario() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacion__anexos']=$this->constructor->documentosAnexosAdicionales__unitario($_POST);
       echo json_encode($jason);

    }


    public function documentosAnexosAdicionales() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacion__anexos']=$this->constructor->documentosAnexosAdicionales($_POST);
       $jason['informacion__anexosNombres']=$this->constructor->documentosAnexosAdicionales__nombres($_POST);
       $jason['informacion__id']=$this->constructor->documentosAnexosAdicionales__id($_POST);
       echo json_encode($jason);

    }


    public function eliminar__usuario__registro() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->eliminar__usuario__registro($usuario);
       echo json_encode($jason);

    }


    public function obtener__id__enviado__proyecto() {

       extract($_POST);

       $jason['idEnviado']=$this->constructor->obtener__id__enviado__proyecto($_POST);
       echo json_encode($jason);

    }    


    public function enviarCodigoFinal() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->enviarCodigoFinal($codigo,$codigoEnviado,$idCredencial);
       echo json_encode($jason);

    }    

    public function observable__obtener__documentos__versiones__observaciones__proyecto__array() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['docuRuta']=$this->constructor->observable__obtener__documentos__versiones__observaciones__proyecto__array($codigo);
       echo json_encode($jason);

    }    

    public function obtenerExistente__r__obtener__documentos() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['docuRuta']=$this->constructor->obtenerExistente__r__obtener__documentos($docuRuta,$carpeta);
       echo json_encode($jason);

    }    

   public function obtenerExistente__r__obtener__documentos__2() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['docuRuta']=$this->constructor->obtenerExistente__r__obtener__documentos__2($docuRuta,$carpeta);
       echo json_encode($jason);

    }    

    public function obtenerExistente__r() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['docuRuta']=$this->constructor->obtenerExistente__r($docuRuta,$carpeta);
       echo json_encode($jason);

    }    

    public function obtenerExistente() {

       extract($_POST);

       $jason['docuRuta']=$this->constructor->obtenerExistente($docuRuta);
       echo json_encode($jason);

    }    


    public function observableCargaCredencial__general() {

       extract($_POST);

       $jason['credencialGeneral']=$this->constructor->observableCargaCredencial__general($codigo);
       echo json_encode($jason);

    }    

    public function generarProyecto() {

       extract($_POST);

       $jason['base64']=$this->constructor->generarProyecto($codigo,$idCredencial,$tipo,$version);
       echo json_encode($jason);

    }    


    public function guarda__documentos__habilitantes() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->guarda__documentos__habilitantes($codigo,$tipoUsuario,$componente);
       echo json_encode($jason);

    }    
 

    public function componente__infra__seleccionado() {

       extract($_POST);

       $jason['infra']=$this->constructor->obtenerArchivosComponentes__infras__5($codigo);

       echo json_encode($jason);

    }    
 
    public function componente__infra__seleccionado__compra__venta__bienes() {

       extract($_POST);

       $jason['infraCompraVenta']=$this->constructor->componente__infra__seleccionado__compra__venta__bienes($codigo);

       echo json_encode($jason);

    }    


    public function buscarDocumentos__requisitos() {

       extract($_POST);

       $jason['proyecto']=$this->constructor->seleccionaGlobales__proyecto($codigo);
       $jason['curriculumDeportivo']=$this->constructor->seleccionaGlobales__curriculoDeportivo($codigo);
       $jason['certificadoTrayectoria']=$this->constructor->seleccionaGlobales__certificadoTrayectoria($codigo);
       $jason['documentoLegalProponente']=$this->constructor->seleccionaGlobales__documentoLegalProponente($codigo);
       $jason['curriculumExperiencia']=$this->constructor->seleccionaGlobales__curriculoExperiencia($codigo);
       $jason['rucDocumento']=$this->constructor->seleccionaGlobales__ruc($codigo);
       $jason['tituloPropiedad']=$this->constructor->seleccionaGlobales__tituloPropiedad($codigo);
       $jason['memoriaTecnica']=$this->constructor->seleccionaGlobales__memoriaTecnica($codigo);
       $jason['planosArquitectonicos']=$this->constructor->seleccionaGlobales__planosArquitectonicos($codigo);
       $jason['presupuestoDetalle']=$this->constructor->seleccionaGlobales__presupuestoDetalle($codigo);
       $jason['respaldoDigitales']=$this->constructor->seleccionaGlobales__respaldoDigitales($codigo);
       $jason['proyectoCargado']=$this->constructor->seleccionaGlobales__proyectoCargado($codigo);
       $jason['analisisPreciosUnitarios']=$this->constructor->seleccionaGlobales__analisisPreciosDigitales($codigo);
       $jason['especificacionesTecnicas']=$this->constructor->seleccionaGlobales__especificacionesTecnicas($codigo);

       $jason['propiedadInmueble__CompraVenta']=$this->constructor->propiedadInmueble__CompraVenta($codigo);
       $jason['compromiso__CompraVenta']=$this->constructor->compromiso__CompraVenta($codigo);
       $jason['avaluo__CompraVenta']=$this->constructor->avaluo__CompraVenta($codigo);
       $jason['informeEstadoActual__CompraVenta']=$this->constructor->informeEstadoActual__CompraVenta($codigo);
       $jason['permisos__CompraVenta']=$this->constructor->permisos__CompraVenta($codigo);

       echo json_encode($jason);

    }    


    public function actualizarArchivosGenerales() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->actualizarArchivosGenerales($_POST);
       $jason['general']=$this->constructor->select__general__documentos__incentivo($_POST);

       echo json_encode($jason);

    }    

    public function validarSeguimiento() {

       extract($_POST);

       $jason['actividadSeguimiento']=$this->constructor->validarActividadSeguimientoSeguimiento($codigo);
       $jason['medioVerificacionSeguimiento']=$this->constructor->validarMedioVerificacionSeguimiento($codigo);
       $jason['observacionSeguimiento']=$this->constructor->validarObservacionSeguimiento($codigo);

       echo json_encode($jason);

    }    


    public function validarPronosticosResultados() {

       extract($_POST);

       $jason['indicador']=$this->constructor->validarIndicador($codigo);
       $jason['descripcion']=$this->constructor->validarDescripcion($codigo);
       $jason['metodoCalculo']=$this->constructor->validarMetodoCalculo($codigo);
       $jason['metaFinal']=$this->constructor->validarMetaFinal($codigo);
       $jason['periodicidad']=$this->constructor->validarPeriodicidad($codigo);
       $jason['verificacion']=$this->constructor->validarMedioVerificacion($codigo);

       $jason['global']=$this->constructor->validarGlobalIndicador($codigo);
       $jason['deportistaOrganismo']=$this->constructor->validarDeportistaOrganismo($codigo);
       $jason['disciplina']=$this->constructor->validarDisciplina($codigo);
       $jason['categoriaEdad']=$this->constructor->validarCategoriaDeEdad($codigo);
       $jason['participacion']=$this->constructor->validarEventoParticipacion($codigo);
       $jason['pronosticoUbicacion']=$this->constructor->validarPronosticoUbicacion($codigo);

       echo json_encode($jason);

    }    


    public function guardarSeguimiento() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarSeguimiento($codigo);
       echo json_encode($jason);

    }



    public function actualizarSeguimientoEvaluacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarSeguimientoEvaluacion($_POST);
       echo json_encode($jason);

    }



    public function seguimientoEvaluacion() {


       extract($_POST);

       
       $jason['seguimientoObjeto']=$this->constructor->seguimientoEvaluacion($codigo);
       echo json_encode($jason);

    }



    public function guardarPronosticos() {


       extract($_POST);

       
       $jason['mensaje']=$this->constructor->guardarPronosticos($codigo);
       echo json_encode($jason);

    }


    public function actualizarPronosticos() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->actualizarPronosticos($_POST);
       echo json_encode($jason);

    }

    public function eliminarPronosticos() {


       extract($_POST);

       $jason['pronosticos']=$this->constructor->eliminarPronosticos($codigo,$id);
       echo json_encode($jason);

    }


    public function agregarPronosticos() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['pronosticos']=$this->constructor->agregarPronosticos($_POST);
       echo json_encode($jason);

    }


    public function obtenerPronosticos() {


       extract($_POST);


       $jason['pronosticos']=$this->constructor->obtenerPronosticos($codigo);
       echo json_encode($jason);

    }



    public function actualizarResultadosEsperados() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarResultadosEsperados($_POST);
       echo json_encode($jason);

    }


    public function resultadosEsperados__obtener() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['resultadoObjeto']=$this->constructor->resultadosEsperados__obtener($codigo);
       echo json_encode($jason);

    }

    public function validarCronogramaDeActividades() {

       extract($_POST);


       $jason['actividades']=$this->constructor->validarActividades($codigo);
       $jason['componentes']=$this->constructor->validarComponentes($codigo);
       $jason['provincia']=$this->constructor->validarProvincia($codigo);
       $jason['canton']=$this->constructor->validarCanton($codigo);
       $jason['parroquia']=$this->constructor->validarParroquia($codigo);
       $jason['pais']=$this->constructor->validarPais($codigo);
       $jason['ciudad']=$this->constructor->validarCiudad($codigo);

       echo json_encode($jason);

    }     


    public function guardarCronogramaDeActividades() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramaDeActividades($codigo);
       echo json_encode($jason);

    }     

    public function eliminarCronogramas__agregadosAdicionales() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->eliminarCronogramas__agregadosAdicionales($_POST);
       echo json_encode($jason);

    }         


    public function guardarCronogramas__agregadosAdicionales() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramas__agregadosAdicionales($_POST);
       echo json_encode($jason);

    }         

    public function guardarCronogramas__adicionales() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->guardarCronogramas__adicionales($_POST);
       echo json_encode($jason);

    }     


     public function actualizarTipo__cronogramaDeActividades() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarTipo__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }     


     public function actualizarRubrosComponentesMeses__cronogramaDeActividades() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentesMeses__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }     

     public function actualizarRubrosComponentesTextos__cronogramaDeActividades() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentesTextos__cronogramaDeActividades($_POST);
       echo json_encode($jason);

    }     

     public function cronogramaDeActividades() {


       extract($_POST);
       $jason['cronogramaDeActividades']=$this->constructor->cronogramaDeActividades($codigo,$anio,$tiposComponentes);
       echo json_encode($jason);

    }   


     public function porcentaje() {


       extract($_POST);

       $jason['porcentaje']=$this->constructor->porcentaje($codigo);

       echo json_encode($jason);

    }   


     public function buscarProfecionales() {


       extract($_POST);

       $jason['resultado']=$this->constructor->buscarProfecionales($codigo);

       echo json_encode($jason);

    }   



     public function guardarEstadoComponentes() {


       extract($_POST);

       $jason['mensaje']=$this->constructor->guardarEstadoComponentes($codigo,$idCredencial);

       echo json_encode($jason);

    }   


     public function guardarComponentesGlobal_validacion() {


       extract($_POST);

       $jason['infraObligatorios']=$this->constructor->obtener__infraestructuraObligatorios($codigo);
       $jason['montosAnuales']=$this->constructor->obtenerSumasGlobalesAnuales($codigo);
       $jason['documentoNecesarios']=$this->constructor->obtenerArchivosComponentes__infras($codigo,$anio);
       $jason['componente5']=$this->constructor->obtenerArchivosComponentes__infras__5($codigo);
       $jason['priorizados']=$this->constructor->obtenerSumasGlobalesAnuales__priorizados__comparacion($codigo);
       $jason['femeninos']=$this->constructor->obtenerSumasGlobalesAnuales__femeninos__comparacion($codigo);
       $jason['descripcionNull']=$this->constructor->componentes__descripcion__null($codigo);
       $jason['obligatoriosComponentes']=$this->constructor->componentes__obligatorios__ingresar($codigo);

       echo json_encode($jason);

    }     
    

     public function obtenerSumasGlobalesAnuales() {


       extract($_POST);


       $jason['montosAnuales']=$this->constructor->obtenerSumasGlobalesAnuales($codigo);
       $jason['montosAnuales__priorizados']=$this->constructor->obtenerSumasGlobalesAnuales__priorizados($codigo);
       $jason['montosAnuales__femeninos']=$this->constructor->obtenerSumasGlobalesAnuales__femeninos($codigo);

       echo json_encode($jason);

    }     
    


     public function seleccionaArchivosInfras() {


       extract($_POST);



       $jason['presupuestoRubro']=$this->constructor->seleccionaArchivosInfras__planos($codigo,$anioObtenido);
       $jason['cronogramaValoradoPdf']=$this->constructor->seleccionaArchivosInfras__docLegal($codigo,$anioObtenido);
       $jason['cronogramaValoradoExcel']=$this->constructor->seleccionaArchivosInfras__otrosPendientes($codigo,$anioObtenido);

       $jason['anexoCronogramas']=$this->constructor->seleccionaArchivosInfras__cronogramaAnexo();
       $jason['anexoPresupuesto']=$this->constructor->seleccionaArchivosInfras__presupuestoAnexo();


       echo json_encode($jason);

    }     
    
   public function archivos__infraestructura__anexos__adicionales() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->archivos__infraestructura__anexos__adicionales($_POST);

       echo json_encode($jason);

    }     

     public function actualizarArchivosInfra() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->actualizarArchivosInfra($_POST);
       $jason['respaldosDigitales']=$this->constructor->seleccionaArchivosInfras__otrosRespaldosDigitales__general($_POST);

       echo json_encode($jason);

    }     


     public function actualizarRubrosComponentesTextos() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentesTextos($_POST);
       echo json_encode($jason);

    }     



     public function actualizarRubrosComponentes() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizarRubrosComponentes($_POST);
       echo json_encode($jason);

    }     


     public function obtenerPresupuestoNiveles() {

       extract($_POST);

       $jason['presupuestoCOmponentes']=$this->constructor->obtenerPresupuestoNiveles($codigo,$anio,$tiposComponentes);
       $jason['presupuestoCOmponentes__codigo']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos($codigo,$anio,$tiposComponentes);
       $jason['presupuestoCOmponentes__codigo__footer']=$this->constructor->obtenerPresupuestoNiveles__componentesUnicos__footer($codigo,$anio,$tiposComponentes);

       echo json_encode($jason);


    }      


     public function obtenerPrimerNivel() {

       extract($_POST);

       $jason['primerNivel']=$this->constructor->obtenerPrimerNivel($idComponente);

       echo json_encode($jason);


    }      



     public function obtenerComponentesPresupuestos() {

       extract($_POST);

       $jason['componentes']=$this->constructor->componentesPresupuesto($codigo);

       echo json_encode($jason);


    }      


     public function eliminarBeneficiariosSelector() {

       extract($_POST);

       $jason['eliminarBeneficiariosSelector']=$this->constructor->eliminarBeneficiariosSelector($id,$codigo);
       echo json_encode($jason);


    }      
    

     public function beneficiariosSelector() {

       extract($_POST);

       $jason['beneficiariosSelector']=$this->constructor->beneficiariosSelector($codigo);
       echo json_encode($jason);


    }      
    

    public function insertaBeneficiario() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertaBeneficiario($_POST);
       echo json_encode($jason);


    }      



    public function sectorJustificacion() {


       extract($_POST);

       $jason['sectorObtener']=$this->constructor->sectorObtener($codigo);
       $jason['justificacionObtener']=$this->constructor->justificacionObtener($codigo);

       echo json_encode($jason);


    }     




    public function insertaSector() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertaSector($_POST);
       echo json_encode($jason);


    }      


    /*===========================================
    =            Selecciona opciones            =
    ===========================================*/

    
    public function rangoEdad() {


       extract($_POST);

       $jason['rangoEdad']=$this->constructor->rangoEdad();
       echo json_encode($jason);


    }      

    
    public function beneficiarios() {


       extract($_POST);

       $jason['beneficiarios']=$this->constructor->beneficiarios();
       echo json_encode($jason);


    }      
    
    public function autoidentificacion() {


       extract($_POST);

       $jason['autoidentificacion']=$this->constructor->autoidentificacion();
       echo json_encode($jason);


    }      
    

    public function discapacidad() {


       extract($_POST);

       $jason['discapacidad']=$this->constructor->discapacidad();
       echo json_encode($jason);


    }      
    
    
    
    /*=====  End of Selecciona opciones  ======*/
    

    public function sectorProyecto() {


       extract($_POST);

       $jason['sectorProyecto']=$this->constructor->sectorProyecto();
       echo json_encode($jason);


    }      


    public function componentes__respaldos() {


       extract($_POST);

       $jason['componentes__respaldos']=$this->constructor->componentes__respaldos($idCodigo);
       echo json_encode($jason);


    }      

    public function componentes__principal() {


       extract($_POST);

       $jason['componentes']=$this->constructor->componentes__principal($idCodigo);
       echo json_encode($jason);


    }       


    public function descripcionProyecto() {


       extract($_POST);

       $jason['informacionDescripcion']=$this->constructor->descripcionProyecto($idCodigo,$url);
       echo json_encode($jason);


    }       

    public function codigoProyecto() {


       extract($_POST);

       $jason['codigoC']=$this->constructor->codigoProyecto($idCredencial);
       echo json_encode($jason);


    }       


    public function insertarDescripcionProyecto() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertarDescripcionProyecto($_POST);
       echo json_encode($jason);


    }       

    public function obtener__informacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionUsuario']=$this->constructor->obtener__informacion__usuario($idCredencial);
       $jason['informacionOrganismo']=$this->constructor->obtener__informacion__organismo($idCredencial);
       $jason['informacionContacto']=$this->constructor->obtener__informacion__contacto($idCredencial);
       $jason['informacionRepresentante']=$this->constructor->obtener__informacion__representante($idCredencial);
       $jason['informacionTipoUsuario']=$this->constructor->obtener__informacion__informacionTipo($idCredencial);
       $jason['informacionSubtipoUsuario']=$this->constructor->obtener__informacion__informacionSubTipo($idCredencial);

       echo json_encode($jason);

    }    

    public function componentes() {


       $jason['componentes']=$this->constructor->componentes();
       echo json_encode($jason);

    }    


    public function codigo__registro__final__usuario() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['codigo__registro']=$this->constructor->codigo__registro__final__usuario($codigo,$password,$_POST);
       echo json_encode($jason);

    }    

    public function codigo__registro() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['codigo__registro']=$this->constructor->codigo__registro($codigo,$password);
       echo json_encode($jason);

    }    


    public function codigo() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['codigo']=$this->constructor->codigo($codigo);
       echo json_encode($jason);

    }    


    public function insertarUsuario() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertarUsuario($_POST);
       echo json_encode($jason);

    }    



    public function organismos() {

       $jason['organismos']=$this->constructor->organismos();
       echo json_encode($jason);

    }

    public function paises() {

       $jason['paises']=$this->constructor->paises();
       echo json_encode($jason);

    }

    public function provincia() {

       $jason['provincia']=$this->constructor->provincia();
       echo json_encode($jason);

    }


    public function canton() {

       extract($_POST);

       $jason['canton']=$this->constructor->canton($idProvincia);
       echo json_encode($jason);

    }

      public function canton__sin() {

       extract($_POST);

       $jason['canton']=$this->constructor->canton__sin();
       echo json_encode($jason);

    }

    public function parroquia() {

       extract($_POST);

       $jason['parroquia']=$this->constructor->parroquia($idCanton);
       echo json_encode($jason);

    }

    public function parroquia__sin() {

       extract($_POST);

       $jason['parroquia']=$this->constructor->parroquia__sin();
       echo json_encode($jason);

    }

    public function genero() {

       $jason['genero']=$this->constructor->genero();
       echo json_encode($jason);

    }

    public function orientacion__sexual() {

       $jason['orientacion__sexual']=$this->constructor->orientacion__sexual();
       echo json_encode($jason);

    }

    public function dinardap__ruc__certificacion__emisor() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['dinardap__ruc']=$this->constructor->dinardap__ruc__certificacion__emisor($ruc,$idCredencial);
       echo json_encode($jason);

    }   


    public function dinardap__ruc__certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['dinardap__ruc']=$this->constructor->dinardap__ruc__certificacion($ruc,$idCredencial);
       echo json_encode($jason);

    }    

    public function dinardap__ruc() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['dinardap__ruc']=$this->constructor->dinardap__ruc($ruc);
       echo json_encode($jason);

    }    

    public function dinardap() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['dinardap']=$this->constructor->dinardap($cedula);
       echo json_encode($jason);

    }

    public function tipoUsuario() {

       $jason['tipoUsuario']=$this->constructor->tipoUsuario();
       echo json_encode($jason);

    }

    public function tipoUsuarioFederado() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['tipoUsuarioFederado']=$this->constructor->tipoUsuarioFederado($idTipo);
       echo json_encode($jason);

    }


    public function tipoUsuarioFederado__global() {

       $jason['tipoUsuarioFederado']=$this->constructor->tipoUsuarioFederado__global();
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
