<?php

namespace Modules\Router\Modul;

class Builder{
    private string $modulesDir = APP_ROOT . DS. 'modules';
    private array $mergedRoutes = [];

    public function start(){
        $this->build();
        if ($this->save_combined_routes()) {
            return true;
        }
        return false;
    }
    public function build(){
        $builderJson = new \Modules\Router\Modul\Builderjson();
        $builderJson->build();
        $builderJson->save_routes_to_file();
        $jsonRoutes = $builderJson->get_routes();

        $builderSql = new \Modules\Router\Modul\Buildersql();
        $builderSql->build();
        $builderSql->save_routes_to_file();
        $sqlRoutes = $builderSql->get_routes();

        $this->mergedRoutes = $this->merge_routes($jsonRoutes, $sqlRoutes);
    }

    private function merge_routes(array $jsonRoutes, array $sqlRoutes){
        $merged = [];
        $existingPaths = [];

        foreach ($jsonRoutes as $route) {
            $path = $route['path'];
            if (!isset($existingPaths[$path])) {
                $merged[] = $route;
                $existingPaths[$path] = true;
            }
        }

        foreach ($sqlRoutes as $route) {
            $path = $route['path'];
            if (!isset($existingPaths[$path])) {
                $route['source'] = 'SQL (fallback)';
                $merged[] = $route;
                $existingPaths[$path] = true;
            }
        }

        return $merged;
    }
    public function save_combined_routes(){
        $outputFile = $this->modulesDir . DS . 'router' . DS . 'install' . DS . 'buildrouter.json';
        
        $data = [
            'generated_at' => date('Y-m-d H:i:s'),
            'sources' => [
                'json_routes' => $this->count_routes_by_source($this->mergedRoutes, 'JSON'),
                'sql_routes' => $this->count_routes_by_source($this->mergedRoutes, 'SQL'),
            ],
            'routes_count' => count($this->mergedRoutes),
            'routes' => $this->mergedRoutes
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        if ($json === false) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "[Ошибка] генерация итогового JSON");
            return false;
        }

        $result = file_put_contents($outputFile, $json);
        
        if ($result === false) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "[Ошибка] записи buildrouter.json");
            return false;
        }

        return true;
    }

    private function count_routes_by_source(array $routes, string $source){
        return count(array_filter($routes, fn($route) => ($route['source'] ?? '') === $source));
    }
    public function get_combined_routes(){
        return $this->mergedRoutes;
    }
}