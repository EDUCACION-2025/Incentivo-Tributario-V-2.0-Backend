<?php


namespace App\Routes\RoutesCertificacion\Base;

use App\Presentation\Controllers\ControllersCertificacion;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;


class BaseCertificacion {


    private $repositorio;

    public function __construct() {
        $this->constructor = new ControllersCertificacion();
        $this->constructor__seguridad = new Seguridad();
    }

   public function obtener__certificaciones__mostrar__pendientes() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionGeneral']=$this->constructor->obtener__certificaciones__mostrar__pendientes($_POST);

       echo json_encode($jason);

   }

   public function obtener__certificaciones__mostrar() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionGeneral']=$this->constructor->obtener__certificaciones__mostrar($_POST);

       echo json_encode($jason);

   }

   public function xml__generar() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionXml']=$this->constructor->xml__generar($_POST);

       echo json_encode($jason);

    }


    public function obtener__informacion__certificacion__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionCertificacion']=$this->constructor->obtener__informacion__certificacion__v1($_POST);

       echo json_encode($jason);

    }



    public function analistaAtiendeCertificado() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacionAnalista']=$this->constructor->analistaAtiendeCertificado($codigoUsuario);

       echo json_encode($jason);

    }

    public function obtener__seguimiento__margen() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['estadoSeguimientoBd']=$this->constructor->obtener__seguimiento__margen($codigoUsuario);

       echo json_encode($jason);

    }

    public function enviar__terminar__certificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->enviar__terminar__certificacion($_POST);

       echo json_encode($jason);

    }


    public function obtener__dias__proyectos() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['dias']=$this->constructor->obtener__dias__proyectos();

       echo json_encode($jason);

    }

    public function obtener__proyectos__recomendados__analistas__certificacion__dos() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['proyectosComiteRecomandos__cer']=$this->constructor->obtener__proyectos__recomendados__analistas__final__certificacion($idComite,$estado);

       echo json_encode($jason);

    }


    public function actualizar__solicitud__continuidad__proyecto() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->actualizar__solicitud__continuidad__proyecto($idEnviar);
       echo json_encode($jason);

    }


    public function informacionSolicitud__general__calificar__analista__solicitud__de__continuidad() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionSolicitud']=$this->constructor->informacionSolicitud__general__calificar__analista__solicitud__de__continuidad($_POST);
       echo json_encode($jason);

    }


    public function actualizar__solicitud__continuidad() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->actualizar__solicitud__continuidad($_POST);


       echo json_encode($jason);

    }

    public function obtenerExistente__solicitud__continuidad() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['docuRuta']=$this->constructor->obtenerExistente__solicitud__continuidad($_POST);


       echo json_encode($jason);

    }

    public function generar__informe__certificacion__solicitud() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe__certificacion__solicitud($_POST);


       echo json_encode($jason);

    }



    public function buscar__plurianuales__anio__existente() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['existente']=$this->constructor->buscar__plurianuales__anio__existente($_POST);

       echo json_encode($jason);

    }



    public function obtener__proyectos__documentacion__facturas__notas__de__venta() {


       extract($_POST);
       
       $jason['documentoComprobante']=$this->constructor->obtener__proyectos__documentacion__facturas__notas__de__venta__factura__comprobantes($idFactura,$id);
       $jason['documentoXml']=$this->constructor->obtener__proyectos__documentacion__facturas__notas__de__venta__xml($idFactura,$id);
       $jason['documentoFactura']=$this->constructor->obtener__proyectos__documentacion__facturas__notas__de__venta__factura($idFactura,$id);

       $jason['documentoNotaVenta']=$this->constructor->obtener__proyectos__documentacion__facturas__notas__de__venta__notaDeVenta($idFactura,$id);

       echo json_encode($jason);

    }



    public function obtener__proyectos__recomendados__analistas__final__certificacion() {


       extract($_POST);
       $jason['proyectosComiteRecomandos__certificacion']=$this->constructor->obtener__proyectos__recomendados__analistas__final__certificacion($idComite,$estado);

       echo json_encode($jason);

    }


    public function despriozar__proyectos__certificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->despriozar__proyectos__certificacion($valor,$proyectoId,$idComite);

       echo json_encode($jason);

    }



    public function obtener__proyectos__recomendados__certificacion() {


       extract($_POST);
       $jason['proyectosComiteRecomandos']=$this->constructor->obtener__proyectos__recomendados__certificacion($idComite);

       echo json_encode($jason);

    }

    public function priorizar__proyectos__certificacion() {


       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       

       $jason['mensaje']=$this->constructor->priorizar__proyectos__certificacion($valor,$proyectoId,$idComite);

       echo json_encode($jason);

    }

    public function obtenerProyectos__comite__certificacion() {


       extract($_POST);
       $jason['proyectosComite']=$this->constructor->obtenerProyectos__comite__certificacion($idComite);

       echo json_encode($jason);

    }


    public function observable__axios__informacion__facturas() {

       extract($_POST);

       $jason['informacion']=$this->constructor->observable__axios__informacion__facturas($idFactura);
       echo json_encode($jason);

    } 

    public function bandeja__recomendados__comite__certificacion() {

       extract($_POST);

       $jason['bandejaRecibidos']=$this->constructor->bandeja__recomendados__comite__certificacion($_POST);
       echo json_encode($jason);

    } 

    public function enviar__proyecto__comite__certificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->enviar__proyecto__comite__certificacion($_POST);


       echo json_encode($jason);

    }

    public function regresar__analista__recomendacion__certificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->regresar__analista__recomendacion__certificacion($_POST);


       echo json_encode($jason);

    }


    public function obtenerInformacion__factura__general() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['informacion']=$this->constructor->obtener__informacion__certifiacion__id__usuario__recomienda($idFactura);


       echo json_encode($jason);

    }


    public function generar__informe__recomendado__certificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe__recomendado__certificacion($_POST);


       echo json_encode($jason);

    }

    public function observable__axios__recibidos_certificacion__recomendados() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->observable__axios__recibidos_certificacion__recomendados($_POST);
       echo json_encode($jason);

    }

    public function verificar__certificacion__recomendacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['informacion']=$this->constructor->verificar__certificacion__recomendacion($idFactura);
       echo json_encode($jason);

    } 

    public function insertaReenvio__certificacion__recomendacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->insertaReenvio__certificacion__recomendacion($_POST);
       echo json_encode($jason);

    } 


    public function generar__informe__certificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['token__archivo']=$this->constructor->generar__informe($_POST);


       echo json_encode($jason);

    }


    public function insertar__informe__certificacion() {


       extract($_POST);


       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);
       
       $jason['mensaje']=$this->constructor->insertar__informe($_POST);


       echo json_encode($jason);

    }


    public function observable__axios__recibidos_certificacion_analista() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->observable__axios__recibidos_certificacion_analista($_POST);
       echo json_encode($jason);

    }
    

    public function insertaReenvio__certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);

       $jason['mensaje']=$this->constructor->insertaReenvio($_POST);
       echo json_encode($jason);

    } 

    public function observable__axios__recibidos_certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->observable__axios__recibidos_certificacion($_POST);
       echo json_encode($jason);

    }
    

    public function informacionSolicitudCertificacion__general() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->informacionSolicitudCertificacion__general($_POST);
       echo json_encode($jason);

    }

    public function obtener__informacion__montos__certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->obtener__informacion__montos__certificacion($_POST);
       echo json_encode($jason);

    }

    public function insertar__datos__certificacion__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertar__datos__certificacion__v1($_POST);
       echo json_encode($jason);

    }

    public function insertar__datos__certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->insertar__datos__certificacion($_POST);
       echo json_encode($jason);

    }

    public function informacionIva() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionIva']=$this->constructor->informacionIva($_POST);
       echo json_encode($jason);

    }

    public function consultar__monto__certificacion__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['montosCertificacion']=$this->constructor->consultar__monto__certificacion__v1($_POST);
       echo json_encode($jason);

    }

    public function consultar__monto__certificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['montosCertificacion']=$this->constructor->consultar__monto__certificacion($_POST);
       echo json_encode($jason);

    }

    public function obtener__cuantos__tramies() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCuantos']=$this->constructor->obtener__cuantos__tramies($_POST);
       echo json_encode($jason);

    }

    public function informacionCertificacion__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->informacionCertificacion__v1($_POST);
       echo json_encode($jason);

    }

    public function informacionCertificacion() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCertificacion']=$this->constructor->informacionCertificacion($_POST);
       echo json_encode($jason);

    }

    public function buscar__proyecto__existente__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCompleta']=$this->constructor->buscar__proyecto__existente__v1($_POST);
       echo json_encode($jason);

    }

    public function buscar__proyecto__existente() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionCompleta']=$this->constructor->buscar__proyecto__existente($_POST);
       echo json_encode($jason);

    }

    public function proyectos__aprobados__codigo__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacion__codigo']=$this->constructor->proyectos__aprobados__codigo__v1($_POST);
       echo json_encode($jason);

    }


    public function proyectos__aprobados__codigo() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacion__codigo']=$this->constructor->proyectos__aprobados__codigo($_POST);
       echo json_encode($jason);

    }

    public function proyectos__aprobados__nombres__v1() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacion__nombre']=$this->constructor->proyectos__aprobados__nombres__v1($_POST);
       echo json_encode($jason);

    }

    public function proyectos__aprobados__nombres() {

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
