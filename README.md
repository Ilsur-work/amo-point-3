# Visitor Counter - Система аналитики посещений

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net)
[![Slim Framework](https://img.shields.io/badge/Slim-4.x-green.svg)](https://www.slimframework.com)
[![SQLite](https://img.shields.io/badge/Database-SQLite-orange.svg)](https://sqlite.org)
[![License](https://img.shields.io/badge/License-MIT-red.svg)](LICENSE)

Система для отслеживания и анализа посещений веб-сайтов. Легковесное решение на базе Slim Framework с панелью статистики, графиками и определением геолокации.

## 📊 Возможности

- ✅ **Автоматический сбор данных** о посетителях (IP, город, устройство, браузер, ОС)
- ✅ **Определение реального IP** через несколько сервисов (с поддержкой прокси и Cloudflare)
- ✅ **Геолокация по IP** - определение города посетителя
- ✅ **Учет уникальных посещений** (один посетитель в час)
- ✅ **Панель статистики** с авторизацией
- ✅ **Интерактивные графики** (Chart.js):
    - Линейный график посещений по часам
    - Круговая диаграмма по городам
- ✅ **REST API** для интеграции с другими сервисами
- ✅ **Простое подключение** - один скрипт на любой сайт
- ✅ **Использование SQLite** - не требует отдельного сервера БД

## 🚀 Демо

- **Панель статистики:** `http://amo-point-3.tw1.su/admin`
- **Логин:** `admin`
- **Пароль:** `admin123`
- 
- **Скрипт для установки на сайт:** `public/tracker.js`

## 📦 Установка

### Требования

- PHP 8.1 или выше
- Composer
- SQLite (встроенный)

### Быстрая установка

```bash
# Клонирование репозитория
git clone https://github.com/Ilsur-work/amo-point-3.git
cd visitor-counter-slim

# Установка зависимостей
composer install --no-dev

# Создание базы данных
mkdir database
touch database/database.sqlite
chmod 775 database
chmod 664 database/database.sqlite

# Запуск встроенного сервера (для разработки)
php -S localhost:8000 -t public