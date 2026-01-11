# Инструкция по развертыванию админ-панели на хостинге

## Проблема

На хостинге находится только статическая версия сайта (скомпилированные HTML-файлы из папки `dist/`), но PHP-админка требует доступ к исходным файлам в директориях `src/content/projects` и `src/content/news`.

## Решение

Необходимо загрузить на хостинг структуру директорий с контентом, даже если сам сайт статический.

---

## Вариант 1: Ручная загрузка через FTP/SFTP (Простой)

### Шаг 1: Создание структуры директорий на сервере

Подключитесь к серверу по SSH или используйте файловый менеджер хостинга и создайте следующие директории:

```bash
mkdir -p /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/projects
mkdir -p /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/news
mkdir -p /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/reports
mkdir -p /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/partners
```

### Шаг 2: Установка прав доступа

```bash
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/src
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/src/content
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/projects
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/news
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/reports
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/src/content/partners
```

### Шаг 3: Загрузка существующих файлов

Используя FTP/SFTP клиент (например, FileZilla), загрузите все файлы из локальных директорий:

- `src/content/projects/*.md` → `/var/www/u0557545/data/www/vremyacheloveka.ru/src/content/projects/`
- `src/content/news/*.md` → `/var/www/u0557545/data/www/vremyacheloveka.ru/src/content/news/`
- `src/content/reports/*.md` → `/var/www/u0557545/data/www/vremyacheloveka.ru/src/content/reports/`
- `src/content/partners/*.json` → `/var/www/u0557545/data/www/vremyacheloveka.ru/src/content/partners/`

### Шаг 4: Проверка

После загрузки структура должна выглядеть так:

```
/var/www/u0557545/data/www/vremyacheloveka.ru/
├── admin-php/              # PHP админка
│   ├── config.php
│   ├── index.php
│   └── ...
├── src/
│   └── content/            # Файлы контента
│       ├── projects/
│       │   ├── trezvaya-rossiya.md
│       │   ├── budushchee-nashikh-detey.md
│       │   └── otvetstvennoe-ottsovstvo.md
│       ├── news/
│       │   ├── itogi-2024-goda.md
│       │   └── otkrytie-tsentra-podderzhki-v-ekaterinburge.md
│       ├── reports/
│       │   ├── financial-2024.md
│       │   └── trezvaya-rossiya-2024.md
│       └── partners/
│           ├── iro.json
│           └── minzdrav.json
└── public/                 # Статический сайт (уже есть)
    └── ...
```

---

## Вариант 2: Автоматическая синхронизация (Продвинутый)

Используйте скрипт для автоматической загрузки после локальных изменений.

### Создание скрипта синхронизации

Создайте файл `sync-content.sh` в корне проекта:

```bash
#!/bin/bash

# Конфигурация
REMOTE_USER="u0557545"
REMOTE_HOST="vremyacheloveka.ru"
REMOTE_PATH="/var/www/u0557545/data/www/vremyacheloveka.ru/src/content/"
LOCAL_PATH="./src/content/"

# Синхронизация
echo "Синхронизация контента с сервером..."
rsync -avz --delete \
  --include='*/' \
  --include='*.md' \
  --include='*.json' \
  --exclude='*' \
  "$LOCAL_PATH" \
  "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH"

echo "✅ Синхронизация завершена!"
```

### Использование:

```bash
chmod +x sync-content.sh
./sync-content.sh
```

---

## Вариант 3: GitHub Actions + Webhook (Автоматический)

Если проект в GitHub, можно настроить автоматическую синхронизацию при каждом коммите.

### Шаг 1: Создайте `.github/workflows/sync-content.yml`

```yaml
name: Sync Content to Server

on:
  push:
    branches: [ main ]
    paths:
      - 'src/content/**'

jobs:
  sync:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup SSH
        uses: webfactory/ssh-agent@v0.7.0
        with:
          ssh-private-key: ${{ secrets.SSH_PRIVATE_KEY }}
      
      - name: Sync content
        run: |
          rsync -avz --delete \
            src/content/ \
            ${{ secrets.REMOTE_USER }}@${{ secrets.REMOTE_HOST }}:${{ secrets.REMOTE_PATH }}
```

### Шаг 2: Добавьте секреты в GitHub

В настройках репозитория добавьте:
- `SSH_PRIVATE_KEY` - приватный ключ SSH
- `REMOTE_USER` - логин на сервере (u0557545)
- `REMOTE_HOST` - хост сервера
- `REMOTE_PATH` - путь к директории на сервере

---

## Важные замечания

### 1. Рабочий процесс после настройки

После развертывания `src/content/` на хостинг:

1. **Редактирование через админку** → изменения сохраняются в `src/content/` на сервере
2. **Пересборка сайта** → запускаете локально или на CI/CD
3. **Деплой статики** → обновляете только `dist/` или `public/`

### 2. Синхронизация изменений

Если вы редактируете контент через админку на сервере, периодически скачивайте изменения обратно:

```bash
# Скачать изменения с сервера на локальную машину
rsync -avz u0557545@vremyacheloveka.ru:/var/www/u0557545/data/www/vremyacheloveka.ru/src/content/ ./src/content/
```

### 3. Альтернатива: Git на сервере

Можно клонировать весь репозиторий на сервер и использовать git pull для обновлений:

```bash
cd /var/www/u0557545/data/www/vremyacheloveka.ru
git clone https://github.com/your-username/vremya-cheloveka.git repo
ln -s repo/src/content ./src/content
```

Затем обновлять:
```bash
cd /var/www/u0557545/data/www/vremyacheloveka.ru/repo
git pull origin main
```

---

## Проверка работоспособности

После развертывания:

1. Откройте админку: `https://vremyacheloveka.ru/admin-php/`
2. Войдите с логином/паролем
3. Проверьте, что отображаются существующие проекты и новости
4. Попробуйте создать тестовую новость
5. Проверьте, что файл появился в `src/content/news/`

---

## Поддержка

Если возникли проблемы:

1. Проверьте права доступа к директориям
2. Убедитесь, что пути в `admin-php/config.php` указаны правильно
3. Проверьте логи Apache: `/var/log/apache2/error.log`
4. Проверьте логи PHP: `/var/log/php-fpm/error.log`