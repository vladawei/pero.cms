<?php

namespace Modules\Core\Modul;

class Sql extends \PDO
{
    private static $instance = null;

    /**
     * Статический метод для получения подключения
     */
    public static function connect(): \PDO{
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $logger = new Logs();
        try {
            $host = Env::get('DB_HOST', 'localhost');
            $port = Env::get('DB_PORT', '3306');
            $dbname = Env::get('DB_DATABASE');
            $user = Env::get('DB_USERNAME');
            $pass = Env::get('DB_PASSWORD');
            $charset = Env::get('DB_CHARSET', 'utf8mb4');

            if (empty($dbname) || empty($user)) {
                $logger->loging('sql', "Неполная комлектация конфигураций");
                throw new \RuntimeException('Database configuration is incomplete');
            }

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

            parent::__construct($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_PERSISTENT => false
            ]);

        } catch (\PDOException $e) {
            $logger->loging('sql', "Ошибка подключения к бд" . $e->getMessage());
            error_log('Database connection failed: ' . $e->getMessage());
            throw new \RuntimeException('Database connection error');
        }
    }

    public function prepare($statement, $options = null){
        if(Env::get('APP_DEBUG') == "true"){
            $str = "запрос ". $statement;
            $logger = new Logs();
            $logger->loging('sql', $str);
            error_log("SQL: $statement");
        }
        return parent::prepare($statement, $options ?? []);
    }
    
    private function __clone() {}
    public function __wakeup() {}
}