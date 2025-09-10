<?php


namespace App\Routes\RoutesAdmin\Base;

use App\Presentation\Controllers\ControllersAdmin;
use App\Seguridad\Seguridad;
use HTMLPurifier;
use HTMLPurifier_Config;


class BaseAdministrator {


    private $repositorio;

    public function __construct() {
        $this->constructor = new ControllersAdmin();
        $this->constructor__seguridad = new Seguridad();
    }

    public function cambiar__estados__del__menu() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->cambiar__estados__del__menu($_POST);
       echo json_encode($jason);

    }    

    public function observable__menus__dinamicos() {

       extract($_POST);

       $jason['informacion']=$this->constructor->observable__menus__dinamicos($_POST);
       echo json_encode($jason);

    }    

    public function guardar__edicionInformacionUsuario() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->guardar__edicionInformacionUsuario($_POST);
       echo json_encode($jason);

    }    

    public function guardarTramitesGeneral__perfiles() {

       extract($_POST);

       $jason['mensaje']=$this->constructor->guardarTramitesGeneral__perfiles($_POST);
       echo json_encode($jason);

    }    

    public function obtener__transacion() {

       extract($_POST);

       $jason['informacion']=$this->constructor->obtener__transacion($idUsuario);
       $jason['informacion__radios']=$this->constructor->obtener__transacion__radios($idUsuario);
       echo json_encode($jason);

    }    

    public function obtener__roles() {

       extract($_POST);

       $jason['informacion']=$this->constructor->obtener__roles($idUsuario);
       echo json_encode($jason);

    }    

    public function obtener__perfil() {

       extract($_POST);

       $jason['informacion']=$this->constructor->obtener__perfil($idUsuario);
       echo json_encode($jason);

    }    


    public function obtener__activacion__menu__proponentes__historicos() {

       extract($_POST);

       $jason['historico']=$this->constructor->obtener__activacion__menu__proponentes__historicos($idCredencial);
       echo json_encode($jason);

    }    


    public function obtener__menuPrincipal() {

       extract($_POST);

       $jason['menusPrincipales']=$this->constructor->obtener__menuPrincipal($id,$idCredencial);
       $jason['idMenu']=$this->constructor->obtener__id__menu__principal($idMenu);
       echo json_encode($jason);

    }    

    public function obtener__nombreUsuarioPerfil() {

       extract($_POST);

       $jason['nombreUsuario']=$this->constructor->obtener__nombreUsuarioPerfil($_POST);
       echo json_encode($jason);

    }    

    public function obtenerMenu__nombre() {

       extract($_POST);

       $jason['menu']=$this->constructor->obtenerMenu__nombre($_POST);
       echo json_encode($jason);

    }    


    public function verbos__obtenidos() {

       extract($_POST);

       $jason['verbosListas']=$this->constructor->verbos__obtenidos();
       echo json_encode($jason);

    }    


    public function observableAxios__buscar__usuario__existente__registro() {

       extract($_POST);

       $jason['recuperar']=$this->constructor->observableAxios__buscar__usuario__existente__registro($_POST);
       echo json_encode($jason);

    }    

    public function observableAxios__buscar__usuario__existente() {

       extract($_POST);

       $jason['recuperar']=$this->constructor->observableAxios__buscar__usuario__existente($usuarioRecuperar);
       echo json_encode($jason);

    }    

    public function informacionUsuario() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['informacionGeneral']=$this->constructor->informacionUsuario($aplicativo,$idUsuario);
       echo json_encode($jason);

    }



    public function obtenerRolesPerfiles() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['rolesRecibidos']=$this->constructor->obtenerRolesPerfiles($aplicativo,$idUsuario);


       
       echo json_encode($jason);

    }


    public function asignacion__perfil__roles() {

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $jason['mensaje']=$this->constructor->asignacion__perfil__roles($aplicativo,$idUsuario,$idPerfilesSeleccionados,$idRolesSeleccionados,$idTransaccionSeleccionados);
       echo json_encode($jason);

    }


    public function perfiles() {

       extract($_POST);

       $jason['perfiles']=$this->constructor->perfiles($aplicativo);
       echo json_encode($jason);

    }


    public function rol__perfiles() {

       extract($_POST);

       $jason['rol__perfil']=$this->constructor->rol__perfiles($aplicativo);
       echo json_encode($jason);

    }


    public function logoInicial() {

       $jason['logoInicial']=$this->constructor->selectLogoInicial();
       echo json_encode($jason);

    }


    public function bannerInicial() {

       $jason['bannerInicial']=$this->constructor->selectBannerRegistro();
       echo json_encode($jason);

    }

    public function auth() {

       extract($_POST);

       $jason['auth']=$this->constructor->usuarioExistente__auth($aplicativo,$usuario,$password);
       echo json_encode($jason);

    }

    public function usuariosExternos() {

       extract($_POST);

       $jason['funcionarios']=$this->constructor->usuariosExternos($buscar);
       echo json_encode($jason);

    }

    public function funcionarios() {

       extract($_POST);

       $jason['funcionarios']=$this->constructor->funcionarios($buscar);
       echo json_encode($jason);

    }


    public function interfazGeneral() {
      
       extract($_POST);
       $jason['interfazGeneral']=$this->constructor->interfazGeneral($aplicativo);
       echo json_encode($jason);

    }

    public function menus__dinamicos() {

       extract($_POST);
       $jason['menu']=$this->constructor->menus__dinamicos($aplicativo,$idUsuario);
       echo json_encode($jason);

    }    

    public function obtenerNombreRuta() {

       extract($_POST);
       $jason['nombreDeRuta']=$this->constructor->obtenerNombreRuta($ruta);
       echo json_encode($jason);

    }    

    public function generar__tocken() {

        require_once "src/Application/Auth/variablesSesion.php";

       extract($_POST);

       $purifier = $this->constructor__seguridad->purificar();
       $this->constructor__seguridad->purifyFormData($_POST, $purifier);


       $variablesSesion=$this->constructor->generar__variables__sesion($idUsuario);
       $token=$this->constructor->generar__token($variablesSesion[0],$variablesSesion[1],$variablesSesion[2]);
       $token__validez=$this->constructor->validar__token($token);

       array_push($variablesSesion, $token);

       getSesiones($variablesSesion);

       array_push($variablesSesion, $token__validez);
   
       $jason['token__validez']=$variablesSesion;
       echo json_encode($jason);


    }

    public function midleware() {

       session_start();

       extract($_POST);

       $_SESSION['idUsuario'] = $idUsuarioGloal;
       $_SESSION['nombreRol'] = $nombreRolGloal;
       $_SESSION['usuario'] = $usuarioGloal;
       $_SESSION['token'] = $tokenGloal;

       $jason['midleware']=$_SESSION['token'];
    
       echo json_encode($jason);

    }    


    public function salir() {

       session_start();

       session_unset();  
       session_destroy();

       $jason['mensaje']=1;
    
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
