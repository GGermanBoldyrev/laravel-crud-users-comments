# 🚀 Laravel CRUD Users & Comments API - План развертывания

Этот документ содержит пошаговые инструкции по развертыванию API для работы с пользователями, постами и комментариями.

## 📋 Требования

- **Docker** и **Docker Compose**
- **Git**
- **Make** (опционально, для удобства)

## 🔄 Быстрое развертывание


### Пошаговая установка

```bash
# 1. Клонируем репозиторий
git clone <repository-url>
cd laravel-crud-users-comments

# 2. Устанавливаем зависимости
composer install

# 3. Копируем файл окружения
cp env.example .env

# 4. Настраиваем .env файл
# Базовые настройки уже корректно настроены в env.example
# Для базы
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

MYSQL_EXTRA_OPTIONS=

# 5. Запускаем контейнеры
make up
# или ./vendor/bin/sail up -d

# 6. Генерируем ключ приложения
./vendor/bin/sail artisan key:generate

# 7. Запускаем миграции и сиды
make migrate-seed
# или ./vendor/bin/sail artisan migrate --seed

# 8. Генерируем Swagger документацию
./vendor/bin/sail artisan l5-swagger:generate
```



## 🗂️ Структура проекта

```
laravel-crud-users-comments/
├── app/
│   ├── Http/Controllers/Api/    # API контроллеры
│   ├── Http/Requests/          # Валидация запросов
│   ├── Models/                 # Eloquent модели
│   ├── Services/               # Бизнес-логика
│   └── Swagger/                # Swagger конфигурация
├── database/
│   ├── migrations/             # Миграции БД
│   ├── seeders/               # Заполнение тестовыми данными
│   └── factories/             # Фабрики для тестов
├── routes/
│   └── api.php                # API маршруты
├── docker-compose.yml         # Конфигурация Docker
├── Makefile                   # Команды для управления
└── env.example               # Пример конфигурации
```

## 🎯 Доступные эндпоинты

После развертывания будут доступны:

- **API Base URL**: `http://localhost/api`
- **Swagger UI**: `http://localhost/api/documentation`

### Основные API маршруты:

#### Аутентификация
- `POST /api/auth/register` - Регистрация
- `POST /api/auth/login` - Вход
- `GET /api/auth/me` - Профиль (требует авторизации)
- `POST /api/auth/logout` - Выход (требует авторизации)

#### Пользователи
- `GET /api/users` - Список пользователей
- `GET /api/users/{id}` - Пользователь по ID
- `POST /api/users` - Создать пользователя (требует авторизации)
- `PUT /api/users/{id}` - Обновить пользователя (требует авторизации)
- `DELETE /api/users/{id}` - Удалить пользователя (требует авторизации)

#### Посты
- `GET /api/posts` - Список постов
- `GET /api/posts/{id}` - Пост по ID
- `GET /api/posts/mine` - Мои посты (требует авторизации)
- `GET /api/posts/{userId}/active` - Активные посты пользователя
- `POST /api/posts` - Создать пост (требует авторизации)
- `PUT /api/posts/{id}` - Обновить пост (требует авторизации)
- `DELETE /api/posts/{id}` - Удалить пост (требует авторизации)

#### Комментарии
- `GET /api/comments` - Список комментариев
- `GET /api/comments/{id}` - Комментарий по ID
- `GET /api/comments/mine` - Мои комментарии (требует авторизации)
- `GET /api/posts/{postId}/comments` - Комментарии к посту
- `GET /api/comments/{commentId}/replies` - Ответы на комментарий
- `GET /api/comments/{userId}/to-active-posts` - Комментарии пользователя к активным постам
- `POST /api/comments` - Создать комментарий (требует авторизации)
- `PUT /api/comments/{id}` - Обновить комментарий (требует авторизации)
- `DELETE /api/comments/{id}` - Удалить комментарий (требует авторизации)



## 🔐 Аутентификация

API использует **Laravel Sanctum** для аутентификации. 

### Процесс работы:
1. Регистрация: `POST /api/auth/register`
2. Получение токена: `POST /api/auth/login`
3. Использование токена в заголовке: `Authorization: Bearer {token}`

## 📊 Тестовые данные

После выполнения `make seed` будут созданы:
- 10 тестовых пользователей
- 20 тестовых постов
- 50 тестовых комментариев
