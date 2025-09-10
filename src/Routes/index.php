<?php

require 'vendor/autoload.php';

use App\Routes\RoutesAdmin\Base\BaseAdministrator;
use App\Routes\RoutesCalificacion\Base\BaseCalificacion;
use App\Routes\RoutesBandeja\Base\BaseBandeja;
use App\Routes\RoutesModificacion\Base\BaseModificacion;
use App\Routes\RoutesCertificacion\Base\BaseCertificacion;
use App\Routes\RoutesSeguimiento\Base\BaseSeguimiento;
use App\Routes\RoutesReportes\Base\BaseReporte;

use App\Routes\Bridge\RouteCombiner;
use App\Routes\Bridge\Routes;

$baseAdmin = new BaseAdministrator();
$baseCalificacion = new BaseCalificacion();
$baseBandeja = new BaseBandeja();
$baseModificacion = new BaseModificacion();
$baseCertificacion = new BaseCertificacion();
$baseSeguimiento = new BaseSeguimiento();
$baseReporte = new BaseReporte();

$routeCombiner = new RouteCombiner($baseAdmin, $baseCalificacion,$baseBandeja,$baseModificacion,$baseCertificacion,$baseSeguimiento,$baseReporte);

$combinedRoutes = $routeCombiner->getCombinedRoutes();

$routeAdmin = new Routes([$baseAdmin, $baseCalificacion,$baseBandeja,$baseModificacion,$baseCertificacion,$baseSeguimiento,$baseReporte]);

$routeAdmin->run();
