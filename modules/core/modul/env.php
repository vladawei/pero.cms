<?php
namespace Modules\Core\Modul;

class Env 
{
    private static array $vars = [];
    private static bool $initialized = false;

    public static function load(): void
    {
        if (self::$initialized) {
            return;
        }

        $envPath = APP_ROOT . DS . '.env';
        
        if (!file_exists($envPath)) {
            self::safeLog('Китическая ошибка: .env файл не найден ' . $envPath);
            throw new \RuntimeException('Configuration file .env not found');
        }

        $vars = parse_ini_file($envPath);
        if ($vars === false) {
            self::safeLog('Китическая ошибка: нет парсинга данных .env');
            throw new \RuntimeException('Invalid .env file format');
        }

        self::$vars = $vars;
        self::$initialized = true;
    }

    public static function get(string $key, mixed $default = null)
    {
        if (!self::$initialized) {
            self::load();
        }

        return self::$vars[$key] ?? $default;
    }

    private static function safeLog(string $message): void
    {
        try {
            $logger = new Logs();
            $logger->loging('core', $message);
        } catch (\Exception $e) {
            error_log('Env Logger Fallback: ' . $message);
        }
    }
}