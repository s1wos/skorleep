@echo off
setlocal

if "%1"=="up" (
    echo Запуск контейнеров...
    docker-compose up -d
) else if "%1"=="down" (
    echo Остановка контейнеров...
    docker-compose down
) else if "%1"=="build" (
    echo Сборка контейнеров...
    docker-compose up -d --build
) else if "%1"=="init" (
    echo Полная инициализация проекта...
    docker-compose up -d --build

    echo Ждём запуск контейнеров...
    timeout /t 5 >nul

    echo Генерация ключа...
    docker-compose exec app php artisan key:generate

    echo Миграции...
    docker-compose exec app php artisan migrate

    echo Создание ссылки на storage...
    docker-compose exec app php artisan storage:link

    echo Laravel готов!
) else (
    echo Использование: d [up^|down^|build^|init]
)

endlocal
