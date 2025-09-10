<?php 
namespace App\Routes\Bridge;

use App\Routes\RoutesAdmin\Base\BaseAdministrator;
use App\Routes\RoutesCalificacion\Base\BaseCalificacion;
use App\Routes\RoutesBandeja\Base\BaseBandeja;
use App\Routes\RoutesModificacion\Base\BaseModificacion;
use App\Routes\RoutesCertificacion\Base\BaseCertificacion;
use App\Routes\RoutesSeguimiento\Base\BaseSeguimiento;
use App\Routes\RoutesReportes\Base\BaseReporte;

class RouteCombiner {

    private $baseAdministrator;
    private $baseCalificacion;
    private $baseBandeja;
    private $baseModificacion;
    private $baseCertificacion;
    private $baseSeguimiento;
    private $baseReporte;

    public function __construct(BaseAdministrator $baseAdministrator, BaseCalificacion $baseCalificacion, BaseBandeja $baseBandeja, BaseModificacion $baseModificacion, BaseCertificacion $baseCertificacion, BaseSeguimiento $baseSeguimiento, BaseReporte $baseReporte) {
        $this->baseAdministrator = $baseAdministrator;
        $this->baseCalificacion = $baseCalificacion;
        $this->baseBandeja = $baseBandeja;
        $this->baseModificacion = $baseModificacion;
        $this->baseCertificacion = $baseCertificacion;
        $this->baseSeguimiento = $baseSeguimiento;
        $this->baseReporte = $baseReporte;
    }

    public function getCombinedRoutes() {
        $routesAdmin = $this->baseAdministrator->rutas__compuestas();
        $routesCalificacion = $this->baseCalificacion->rutas__compuestas();
        $routesBandejas = $this->baseBandeja->rutas__compuestas();
        $RoutesModificacion = $this->baseModificacion->rutas__compuestas();
        $baseCertificacion = $this->baseCertificacion->rutas__compuestas();
        $baseSeguimiento = $this->baseSeguimiento->rutas__compuestas();
        $baseReporte = $this->baseReporte->rutas__compuestas();
        return array_merge($routesAdmin, $routesCalificacion,$routesBandejas,$RoutesModificacion,$baseCertificacion,$baseSeguimiento,$baseReporte);
    }
    
}
