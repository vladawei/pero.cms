<?php

namespace Modules\Core\Modul;

class Logs{
    // Константы для дефолтных значений
    const DEFAULT_LOG_FILE = 'logs.txt';
    const LOGS_DIR = 'logs';

    /**
     * Запись лога в файл
     * 
     * @param string $modName Название модуля (поддиректория)
     * @param string $text Текст сообщения
     * @param string $logFile Имя файла лога (по умолчанию 'logs.txt')
     * @return bool Возвращает true при успешной записи
     * @throws \RuntimeException При ошибках файловой системы
     * 
     */

    /*
    пример использования
     $logger = new \Modules\Core\Modul\Logs();
     $logger->loging('core', 'ядро загружено');
     */
    public function loging(string $modName, string $text, string $logFile = self::DEFAULT_LOG_FILE): bool {
        if (empty($modName) || empty($text)) {
            throw new \InvalidArgumentException('Имя модуля и текст сообщения не может быть пустым ');
        }

        $filePath = $this->getLogFilePath($modName, $logFile);
        
        try {
            $this->ensureLogDirectoryExists($modName);
            $this->createLogFile($filePath);
            $this->writeLogEntry($filePath, $text);
            return true;
        } catch (\Exception $e) {
            error_log('Ошибка логера: ' . $e->getMessage());
            throw new \RuntimeException('Ошибка записи лога: ' . $e->getMessage());
        }
    }

    protected function createLogFile(string $filePath): void {
        if (!file_exists($filePath)) {
            if (!touch($filePath)) {
                throw new \RuntimeException("Ошибка создания файла логов: {$filePath}");
            }
            chmod($filePath, 0666);
        }
        
        if (!is_writable($filePath)) {
            throw new \RuntimeException("Файл логов не доступен для записи: {$filePath}");
        }
    }

    protected function writeLogEntry(string $filePath, string $text): void {
        $date = date('d.m.Y H:i:s');
        $entry = "[{$date}] : {$text}\n";
        
        if (file_put_contents($filePath, $entry, FILE_APPEND | LOCK_EX) === false) {
            throw new \RuntimeException("Ошибка записи в лог файл: {$filePath}");
        }
    }

    protected function ensureLogDirectoryExists(string $modName): void {
        $dirPath = $this->getLogDirPath($modName);
        
        if (!file_exists($dirPath)) {
            if (!mkdir($dirPath, 0775, true) && !is_dir($dirPath)) {
                throw new \RuntimeException("Ошибка создания директории: {$dirPath}");
            }
        }
    }

    protected function getLogDirPath(string $modName): string {
        return APP_ROOT . DS . self::LOGS_DIR . DS . $modName;
    }

    protected function getLogFilePath(string $modName, string $logFile): string {
        return $this->getLogDirPath($modName) . DS . $logFile;
    }

}