<?php
namespace App\Routes\Bridge;

use App\Routes\RoutesAdmin\Base\BaseAdministrator;
use App\Routes\RoutesCalificacion\Base\BaseCalificacion;

class Routes {

    private $routes = [];
    private $baseRouters = [];

    public function __construct(array $baseRouters) {
        $this->baseRouters = $baseRouters;
        $this->routes = $this->combineRoutes();
    }

    private function combineRoutes() {
        $combinedRoutes = [];
        foreach ($this->baseRouters as $baseRouter) {
            $combinedRoutes = array_merge($combinedRoutes, $baseRouter->rutas__compuestas());
        }
        return $combinedRoutes;
    }

    public function route($url) {
        if (array_key_exists($url, $this->routes)) {
            $functionName = $this->routes[$url];
            foreach ($this->baseRouters as $baseRouter) {
                if (method_exists($baseRouter, $functionName)) {
                    $baseRouter->$functionName();
                    return;
                }
            }
            echo 0;
        } else {
            echo 0;
        }
    }

    public function run() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->route($url);
    }
}
