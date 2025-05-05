<?php

namespace Modules\Router\Modul;

class Buildersql{
    private array $routes = [];
    private string $modulesDir = APP_ROOT . DS. 'modules';


    public function build(){
        $this->routes = $this->load_sql_routes();
    }

    private function load_sql_routes(){
        $sqlRoutes = [];
        
        try {
            $pdo = \Modules\Core\Modul\Sql::connect();
            $tableName = \Modules\Core\Modul\Env::get("DB_PREFIX") . 'router';
            $stmt = $pdo->query("SELECT url, class, funct FROM `{$tableName}`");
            
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $sqlRoutes[] = [
                    'path' => $row['url'],
                    'class' => $row['class'],
                    'method' => $row['funct'],
                    'source' => 'SQL_Database'
                ];
            }
        } catch (\PDOException $e) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "SQL ошибка сборки" . $e->getMessage());
        }
        
        return $sqlRoutes;
    }

    public function save_routes_to_file(){
        $outputFile = $this->modulesDir . DS . 'router' . DS . 'install' . DS . 'buildroutersql.json';
        
        // Создаем папку install если её нет
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
            $logger->loging('router', "[Ошибка] сбой генерации buildroutersql.json");
            return false;
        }

        $result = file_put_contents($outputFile, $json);
        
        if ($result === false) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "[Ошибка] сбой записи buildroutersql.json");
            return false;
        }

        return true;
    }

    public function get_routes(): array
    {
        return $this->routes;
    }
    
}