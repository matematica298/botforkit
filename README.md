# Система kitPanel

## Установка

#### Установка Traefik

#### Установка проекта
Клонируем себе репозиторий:
```
git clone https://github.com/skmainzmn/kitdev_orchid.git kitdev.local
```

Копируем файл переменных окружения:
```
cp .env.example .env
```

Добавляем локальный домен в `/etc/hosts`:
```
127.0.0.1   kitdev.local
```

Устанавливаем пакеты композера:
```
docker-compose up composer
```

Далее поднимаем все контейнеры докера:
```
docker-compose up --build -d
```
