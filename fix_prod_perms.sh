#!/bin/bash
# Strict permissions for production
PROJECT_PATH="/home/redlava/webserver/pero.cms"
WEB_USER="www-data"
LOG_DIRS=("logs" "cache" "upload")

echo "Устанавливаю production-права для $PROJECT_PATH"

# Установка владельца
sudo chown -R redlava:$WEB_USER $PROJECT_PATH

# Базовые права
find $PROJECT_PATH -type d -exec chmod 750 {} \;
find $PROJECT_PATH -type f -exec chmod 640 {} \;

# Writable-директории
for dir in "${LOG_DIRS[@]}"; do
    if [ -d "$PROJECT_PATH/$dir" ]; then
        chmod 770 "$PROJECT_PATH/$dir"
        echo "  ✔ Права 770 для $dir"
    fi
done

# Критичные файлы
[ -f "$PROJECT_PATH/.env" ] && chmod 600 "$PROJECT_PATH/.env"
[ -f "$PROJECT_PATH/index.php" ] && chmod 750 "$PROJECT_PATH/index.php"

# Запрет исполняемых прав для файлов (кроме явно разрешенных)
find $PROJECT_PATH -type f -name "*.php" -not -path "$PROJECT_PATH/index.php" -exec chmod 640 {} \;

echo "Готово! Права настроены в строгом режиме"