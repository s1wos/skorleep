COMPOSE_FILES=$(find "$(pwd)" -maxdepth 1 -type f -name "docker-compose*.yml" | xargs)
if [ -z "$COMPOSE_FILES" ]; then
  echo "⚠️  Не найдены файлы docker-compose*.yml в текущей директории."
  exit 1
fi

# Выполнить docker-compose с нашими файлами
function dc() {
  docker compose -f $COMPOSE_FILES "$@"
}

function help_msg() {
  cat << EOF
Использование: ./d.sh <команда> [args...]
Команды:
  up             – запускает контейнеры в фоновом режиме
  down           – останавливает контейнеры
  build          – пересобирает и запускает контейнеры
  restart        – останавливает и запускает заново
  init           – для Laravel: build → key:generate → migrate → storage:link
  exec <service> <cmd> [args...] – выполняет команду внутри контейнера
  logs [service] – просмотреть логи (все или указанного сервиса)
  ps             – статус контейнеров
  help           – показать это сообщение
EOF
}

case "$1" in
  up)
    dc up -d ;;
  down)
    dc down ;;
  build)
    dc up -d --build ;;
  restart)
    dc down && dc up -d ;;
  init)
    dc up -d --build
    echo "⏳ Ждем старта сервисов..."
    sleep 5
    dc exec app php artisan key:generate
    dc exec app php artisan migrate
    dc exec app php artisan storage:link
    echo "✅ Проект инициализирован"
    ;;
  exec)
    shift
    if [ -z "$1" ]; then
      echo "Ошибка: не указан сервис"
      exit 1
    fi
    SERVICE="$1"; shift
    dc exec "$SERVICE" "$@" ;;
  logs)
    if [ -n "$2" ]; then
      dc logs "$2"
    else
      dc logs --tail=50 -f
    fi ;;
  ps)
    dc ps ;;
  help|*)
    help_msg ;;
esac
