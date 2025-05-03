#!/bin/bash
# Flexible permissions for development
PROJECT_PATH="/home/redlava/webserver/pero.cms"
WEB_USER="www-data"
WRITABLE_DIRS=("logs" "cache" "upload" "tmp")

echo "Устанавливаю development-права для $PROJECT_PATH"

# Установка владельца (рекурсивно)
sudo chown -R redlava:$WEB_USER $PROJECT_PATH

# Базовые права
find $PROJECT_PATH -type d -exec chmod 775 {} \;
find $PROJECT_PATH -type f -exec chmod 664 {} \;

# Writable-директории
for dir in "${WRITABLE_DIRS[@]}"; do
    if [ -d "$PROJECT_PATH/$dir" ]; then
        chmod 777 "$PROJECT_PATH/$dir"
        echo "  ✔ Права 777 для $dir"
    else
        mkdir -p "$PROJECT_PATH/$dir"
        chmod 777 "$PROJECT_PATH/$dir"
        echo "  ✔ Создал $dir с правами 777"
    fi
done

# Специальные файлы
[ -f "$PROJECT_PATH/.env" ] && chmod 660 "$PROJECT_PATH/.env"
[ -f "$PROJECT_PATH/index.php" ] && chmod 755 "$PROJECT_PATH/index.php"

echo "Готово! Права настроены в developer-режиме"