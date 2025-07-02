@echo off
setlocal enabledelayedexpansion

REM Optional: project name isolation
set COMPOSE_PROJECT_NAME=mailmm

if "%1"=="init" (
    echo --- Initializing mailmm project ---
    docker compose down --volumes --remove-orphans
    docker compose up -d --build

    docker compose exec app php artisan key:generate
    docker compose exec app php artisan migrate
    docker compose exec app php artisan storage:link

    echo --- Project mailmm initialized and running at http://localhost ---
    exit /b
)

if "%1"=="up" (
    echo --- Starting containers ---
    docker compose up -d
    exit /b
)

if "%1"=="down" (
    echo --- Stopping containers ---
    docker compose down
    exit /b
)
