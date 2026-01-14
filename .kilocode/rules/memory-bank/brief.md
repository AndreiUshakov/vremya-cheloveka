# PHP-сайт для "Время Человека"

Динамический PHP-сайт, который читает контент из Markdown и JSON файлов. Позволяет модераторам редактировать контент через админ-панель без необходимости пересборки сайта.

## Архитектура

### Как это работает

1. **Модераторы** редактируют контент через PHP-админку (`/admin/`)
2. **Изменения** сохраняются в `.md` и `.json` файлы в `content/`
3. **PHP-сайт** читает эти файлы напрямую и отображает контент
4. **Никакой пересборки** не требуется — изменения видны сразу

### Преимущества

✅ Модераторы могут редактировать контент без доступа к Git  
✅ Изменения отображаются мгновенно  
✅ Красивые ЧПУ: `/projects/trezvaya-rossiya` вместо `/project.php?id=1`  
✅ Полностью динамический — легко добавлять новые проекты и новости  
✅ Безопасность через `.htaccess` и валидацию данных  

## Структура файлов

```
vremya-cheloveka/
├── .htaccess              # ЧПУ и настройки безопасности
├── config.php             # Общая конфигурация
├── index.php              # Главная страница
├── projects.php           # Список проектов
├── project.php            # Отдельный проект
├── admin/                 # Админ-панель
├── content/               # Контент в Markdown
│   ├── projects/
│   ├── news/
│   ├── reports/
│   └── partners/
├── includes/
│   ├── MarkdownParser.php # Парсер .md файлов
│   └── layout.php         # Базовый layout
└── static/                # CSS, JS, изображения
```

## Установка

### 1. Загрузка на сервер

Загрузите весь проект на хостинг:

```
/var/www/u0557545/data/www/vremyacheloveka.ru/
```

### 2. Структура на сервере

Убедитесь, что структура выглядит так:

```
/var/www/u0557545/data/www/vremyacheloveka.ru/
├── admin/               # PHP админка
├── content/             # Контент в Markdown
│   ├── projects/
│   ├── news/
│   ├── reports/
│   └── partners/
├── includes/            # PHP классы
├── static/              # Статические файлы
├── .htaccess            # ЧПУ и безопасность
├── config.php           # Конфигурация
├── index.php            # Главная
├── projects.php         # Проекты
└── project.php          # Один проект
```

### 3. Настройка путей

Откройте `config.php` и проверьте пути:

```php
define('CONTENT_DIR', ROOT_DIR . '/content');
```

Пути настроены относительно корня проекта.

### 4. Права доступа

```bash
# Права на чтение для PHP
chmod 755 /var/www/u0557545/data/www/vremyacheloveka.ru/content/
chmod 644 /var/www/u0557545/data/www/vremyacheloveka.ru/content/projects/*.md
chmod 644 /var/www/u0557545/data/www/vremyacheloveka.ru/content/news/*.md
```

## ЧПУ (Человеко-Понятные URL)

### Как работает

`.htaccess` преобразует красивые URL в параметры:

| Красивый URL | Реальный файл |
|--------------|---------------|
| `/` | `index.php` |
| `/projects` | `projects.php` |
| `/projects/trezvaya-rossiya` | `project.php?slug=trezvaya-rossiya` |
| `/news/itogi-2024-goda` | `news-single.php?slug=itogi-2024-goda` |

### Добавление новых страниц

Чтобы добавить новую страницу с ЧПУ:

1. Создайте PHP-файл (например, `about.php`)
2. Добавьте правило в `.htaccess`:

```apache
RewriteRule ^about/?$ about.php [L]
```

## Работа с контентом

### Структура Markdown файла

Пример `content/projects/trezvaya-rossiya.md`:

```markdown
---
title: "Трезвая Россия"
slug: "trezvaya-rossiya"
shortDescription: "Программа поддержки трезвого образа жизни"
category: "social"
status: "active"
featured: true
targetAmount: 5000000
collectedAmount: 1250000
beneficiariesCount: 15000
regions:
  - "Москва"
  - "Санкт-Петербург"
imageUrl: "https://images.unsplash.com/..."
publishedAt: 2024-01-15
---

## О проекте

Текст проекта в Markdown...
```

### Обязательные поля

- `title` — название
- `slug` — используется в URL (должен совпадать с именем файла)
- `shortDescription` — краткое описание
- `category` — категория проекта
- `status` — статус (active/completed/archived)
- `publishedAt` — дата публикации

### Опциональные поля

- `featured: true` — показать на главной странице
- `targetAmount` — целевая сумма сбора
- `collectedAmount` — собранная сумма
- `beneficiariesCount` — количество благополучателей
- `regions` — список регионов
- `imageUrl` — изображение проекта

## Получение данных в PHP

### Все проекты

```php
$projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
```

### Один проект по slug

```php
$project = MarkdownParser::getBySlug(PROJECTS_DIR, 'trezvaya-rossiya');
```

### Сортировка

```php
$projects = MarkdownParser::sort($projects, 'publishedAt', 'desc');
```

### Фильтрация

```php
$activeProjects = MarkdownParser::filter($projects, ['status' => 'active']);
```

### Избранные проекты

```php
$featured = MarkdownParser::getFeatured($projects, 3); // Топ-3
```

## Создание новых страниц

### Пример: страница новостей

Создайте `news.php`:

```php
<?php
require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

$news = MarkdownParser::getAllFromDirectory(NEWS_DIR);
$news = MarkdownParser::sort($news, 'publishedAt', 'desc');

startContent();
?>

<section class="news-list">
    <div class="container">
        <h1>Новости</h1>
        
        <?php foreach ($news as $item): ?>
        <article>
            <h2>
                <a href="/news/<?= e($item['slug']) ?>">
                    <?= e($item['title']) ?>
                </a>
            </h2>
            <time><?= formatDate($item['publishedAt']) ?></time>
            <p><?= e($item['excerpt']) ?></p>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<?php
endContent(['title' => 'Новости']);
?>
```

## Функции-помощники

### Безопасный вывод HTML

```php
<?= e($project['title']) ?>
```

### Форматирование даты

```php
<?= formatDate($project['publishedAt']) ?>        // 15.01.2024
<?= formatDate($project['publishedAt'], 'Y') ?>   // 2024
```

### Форматирование суммы

```php
<?= formatAmount(1250000) ?>  // 1 250 000 ₽
```

### Процент сбора средств

```php
<?= getCollectionPercentage($collected, $target) ?>  // 25
```

### Генерация URL

```php
<?= url('/projects') ?>  // https://vremyacheloveka.ru/projects
```

### Проверка активной страницы

```php
<a href="/projects" class="<?= isActive('/projects') ? 'active' : '' ?>">
    Проекты
</a>
```

## Рабочий процесс

### 1. Редактирование через админку

1. Откройте `https://vremyacheloveka.ru/admin/`
2. Войдите с логином/паролем
3. Создайте или отредактируйте проект/новость
4. Изменения сразу видны на сайте

### 2. Прямое редактирование файлов

Если нужно отредактировать файл напрямую:

1. Подключитесь к серверу по SFTP
2. Откройте `content/projects/trezvaya-rossiya.md`
3. Внесите изменения
4. Сохраните — изменения сразу на сайте

## Безопасность

### Защита служебных файлов

`.htaccess` блокирует доступ к:
- `/includes/` — PHP классы
- `/content/` — исходный контент
- `.htaccess`, `.env` и другие служебные файлы

### Защита от XSS

Всегда используйте функцию `e()` для вывода:

```php
<?= e($userInput) ?>  // ✅ Безопасно
<?= $userInput ?>     // ❌ Уязвимость XSS
```

### HTTP заголовки безопасности

Настроены в `.htaccess`:
- X-XSS-Protection
- X-Frame-Options
- X-Content-Type-Options

## Производительность

### Кеширование

Markdown файлы читаются при каждом запросе. Для высоконагруженных сайтов рекомендуется добавить кеширование:

```php
// Пример с APCu
if (apcu_exists('projects')) {
    $projects = apcu_fetch('projects');
} else {
    $projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
    apcu_store('projects', $projects, 3600); // 1 час
}
```

### Оптимизация изображений

Используйте:
- WebP формат для изображений
- Lazy loading: `<img loading="lazy">`
- CDN для статических файлов

## Troubleshooting

### Ошибка 404 на всех страницах

Проверьте, что mod_rewrite включен:
```bash
a2enmod rewrite
service apache2 restart
```

### Не работают ЧПУ

Убедитесь, что в конфигурации Apache разрешён `.htaccess`:
```apache
<Directory /var/www/u0557545/data/www/vremyacheloveka.ru>
    AllowOverride All
</Directory>
```

### Проекты не отображаются

1. Проверьте права на чтение: `chmod 644 content/projects/*.md`
2. Проверьте путь в `config.php`
3. Убедитесь, что frontmatter корректен (между `---`)

## Дополнительные возможности

### Поиск

Добавьте поиск по проектам:

```php
$query = $_GET['q'] ?? '';
$results = array_filter($projects, function($p) use ($query) {
    return stripos($p['title'], $query) !== false ||
           stripos($p['shortDescription'], $query) !== false;
});
```

### Пагинация

```php
$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$paginatedProjects = array_slice($projects, $offset, $perPage);
```

### RSS лента

Создайте `rss.php` для генерации RSS:

```php
header('Content-Type: application/rss+xml; charset=utf-8');
$news = MarkdownParser::getAllFromDirectory(NEWS_DIR);
// Генерируйте XML...
```

## Поддержка

По вопросам настройки и доработки обращайтесь к разработчику.

---

**Последнее обновление:** 2026-01-11