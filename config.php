<?php
/**
 * Конфигурация PHP-сайта "Время Человека"
 * Это основной конфигурационный файл, используемый на хостинге
 */

// Базовые настройки
define('SITE_NAME', 'Время Человека');
define('SITE_URL', 'https://времячеловека.рф');
/* define('SITE_URL', 'https://vremyacheloveka.ru'); */
define('SITE_DESCRIPTION', 'Благотворительный фонд');

// Корневая директория сайта
define('ROOT_DIR', __DIR__);

// Пути к директориям контента (теперь в /content)
define('CONTENT_DIR', ROOT_DIR . '/content');
define('PROJECTS_DIR', CONTENT_DIR . '/projects');
define('NEWS_DIR', CONTENT_DIR . '/news');
define('REPORTS_DIR', CONTENT_DIR . '/reports');
define('PARTNERS_DIR', CONTENT_DIR . '/partners');

// Пути для includes
define('INCLUDES_DIR', ROOT_DIR . '/includes');

// Настройки отображения
define('PROJECTS_PER_PAGE', 9);
define('NEWS_PER_PAGE', 10);
define('FEATURED_PROJECTS_COUNT', 3);
define('RECENT_NEWS_COUNT', 3);

// Категории проектов
define('PROJECT_CATEGORIES', [
    'children' => 'Дети',
    'education' => 'Образование',
    'health' => 'Здоровье',
    'social' => 'Социальные',
    'other' => 'Другое'
]);

// Статусы проектов
define('PROJECT_STATUSES', [
    'active' => 'Активный',
    'completed' => 'Завершён',
    'archived' => 'Архивный'
]);

// Типы отчётов
define('REPORT_TYPES', [
    'financial' => 'Финансовый отчёт',
    'project' => 'Отчёт по проекту',
    'annual' => 'Годовой отчёт'
]);

// Регионы России
define('REGIONS', [
    'Москва',
    'Санкт-Петербург',
    'Екатеринбург',
    'Новосибирск',
    'Казань',
    'Нижний Новгород',
    'Челябинск',
    'Самара',
    'Омск',
    'Ростов-на-Дону',
    'Уфа',
    'Красноярск',
    'Воронеж',
    'Пермь',
    'Волгоград'
]);

// Контактная информация
define('CONTACT_EMAIL', 'info@vremyacheloveka.ru');
define('CONTACT_PHONE', '+7 (914) 916-95-59');
define('CONTACT_ADDRESS', 'Иркутск, ул. Горького, д. 27');

// Социальные сети
define('SOCIAL_VK', 'https://vk.com/vremyacheloveka');
define('SOCIAL_TELEGRAM', 'https://t.me/vremyacheloveka');
define('SOCIAL_OK', 'https://ok.ru/vremyacheloveka');

// Настройки безопасности
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Установка кодировки
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Установка временной зоны
date_default_timezone_set('Europe/Moscow');

/**
 * Функция для безопасного вывода HTML
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Функция для форматирования даты
 */
function formatDate($date, $format = 'd.m.Y') {
    if (empty($date)) return '';
    
    if (is_numeric($date)) {
        $timestamp = $date;
    } else {
        $timestamp = strtotime($date);
    }
    
    return date($format, $timestamp);
}

/**
 * Функция для форматирования суммы
 */
function formatAmount($amount) {
    if (is_array($amount)) return '0';
    if ($amount === null) return '0';
    if ($amount == 0) return '0';
    return number_format($amount, 0, ',', ' ');
}

/**
 * Функция для получения процента сбора средств
 */
function getCollectionPercentage($collected, $target) {
    if ($target <= 0) return 0;
    $percentage = ($collected / $target) * 100;
    return min(100, round($percentage));
}

/**
 * Получение текущего URL
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Генерация URL для страниц
 */
function url($path = '') {
    $baseUrl = rtrim(SITE_URL, '/');
    $path = ltrim($path, '/');
    return $baseUrl . '/' . $path;
}

/**
 * Проверка активной страницы для навигации
 */
function isActive($page) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return strpos($currentPath, $page) !== false;
}

/**
 * Получение мета-тегов для страницы
 */
function getMetaTags($title = null, $description = null, $image = null) {
    $siteTitle = $title ? $title . ' — ' . SITE_NAME : SITE_NAME;
    $siteDescription = $description ?? SITE_DESCRIPTION;
    $siteImage = $image ?? url('images/og-image.jpg');
    
    return [
        'title' => $siteTitle,
        'description' => $siteDescription,
        'image' => $siteImage,
        'url' => currentUrl()
    ];
}

/**
 * Функция для преобразования текста в slug (транслитерация)
 */
function createSlug($text) {
    $transliteration = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
        'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
    ];
    
    $text = strtr($text, $transliteration);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    
    return $text;
}

// Подключаем классы
require_once INCLUDES_DIR . '/MarkdownParser.php';