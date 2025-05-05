<?php

namespace Modules\Router\Modul;

class Builderjson{
    private array $routes = [];
    private string $modulesDir = APP_ROOT . DS. 'modules';

    public function build(){
        $modules = scandir($this->modulesDir);

        foreach ($modules as $module) {
            if ($this->is_invalid_module($module)) {
                continue;
            }

            $installDir = $this->modulesDir .  DS . $module .  DS .'install';
            
            $routeFile = $this->get_route_file_path($module);
            $this->process_route_file($module, $routeFile);
        }
    }

    private function is_invalid_module(string $module){
        return $module === '.' || $module === '..' || !is_dir($this->modulesDir . DS . $module);
    }

    private function get_route_file_path(string $module){
        return $this->modulesDir . DS . $module . DS . 'install' . DS . 'router.json';
    }

    private function process_route_file(string $moduleName, string $routeFile){
        if (!file_exists($routeFile)) {
            return;
        }

        $jsonContent = file_get_contents($routeFile);
        $routes = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $logger = new \Modules\Core\Modul\Logs();       
            $logger->loging('router', "['ошибка'] JSON {$moduleName}/install/router.json - ошибка чтения  ");
            return;
        }

        $this->validate_and_add_routes($routes, $moduleName);
    }

    private function validate_and_add_routes(array $routes, string $moduleName){
        foreach ($routes as $route) {
            if (!$this->is_valid_route($route)) {
                $logger = new \Modules\Core\Modul\Logs();       
                $logger->loging('router', "['ошибка'] JSON {$moduleName}/install/router.json - ошибка формата  ");
                continue;
            }

            $this->routes[] = [
                'path' => $route[0],
                'class' => $route[1],
                'method' => $route[2],
                'module' => $moduleName
            ];
        }
    }

    private function is_valid_route(array $route){
        return count($route) >= 3 &&
               is_string($route[0]) &&
               is_string($route[1]) &&
               is_string($route[2]);
    }

    public function get_routes(){
        return $this->routes;
    }

    public function save_routes_to_file(){
        $outputFile = $this->modulesDir . DS . 'router' . DS . 'install' . DS . 'buildrouterjson.json';

        $installDir = dirname($outputFile);
        if (!is_dir($installDir)) {
            mkdir($installDir, 0755, true);
        }

        $data = [
            'generated_at' => date('Y-m-d H:i:s'),
            'routes_count' => count($this->routes),
            'routes' => $this->routes
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            $logger = new \Modules\Core\Modul\Logs();       
            $logger->loging('router', "['ошибка'] Не удалось сгенерировать JSON для buildrouterjson.json");
            return false;
        }

        $result = file_put_contents($outputFile, $json);

        if ($result === false) {
            $logger = new \Modules\Core\Modul\Logs();       
            $logger->loging('router', "['ошибка'] Не удалось записать buildrouterjson.json");
            return false;
        }

        return true;
    }
}