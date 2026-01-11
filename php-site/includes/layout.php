<?php
/**
 * Базовый layout для всех страниц
 */

function renderLayout($content, $meta = []) {
    $metaTags = getMetaTags(
        $meta['title'] ?? null,
        $meta['description'] ?? null,
        $meta['image'] ?? null
    );
    ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($metaTags['title']) ?></title>
    
    <!-- Meta теги -->
    <meta name="description" content="<?= e($metaTags['description']) ?>">
    <meta name="keywords" content="благотворительность, помощь, фонд, дети, социальные проекты">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($metaTags['title']) ?>">
    <meta property="og:description" content="<?= e($metaTags['description']) ?>">
    <meta property="og:image" content="<?= e($metaTags['image']) ?>">
    <meta property="og:url" content="<?= e($metaTags['url']) ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTags['title']) ?>">
    <meta name="twitter:description" content="<?= e($metaTags['description']) ?>">
    <meta name="twitter:image" content="<?= e($metaTags['image']) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Стили -->
    <link rel="stylesheet" href="/static/styles.css">
    
    <!-- Дополнительные стили для конкретных страниц -->
    <?php if (isset($meta['additional_css'])): ?>
        <?php foreach ($meta['additional_css'] as $css): ?>
            <link rel="stylesheet" href="<?= e($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Шапка сайта -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <a href="/" class="logo">
                    <i class="fas fa-heart"></i> <?= e(SITE_NAME) ?>
                </a>
                
                <nav class="main-nav">
                    <ul>
                        <li><a href="/">Главная</a></li>
                        <li><a href="/projects">Проекты</a></li>
                        <li><a href="/about">О фонде</a></li>
                        <li><a href="/reports">Отчёты</a></li>
                        <li><a href="/contacts">Контакты</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Основной контент -->
    <?= $content ?>
    
    <!-- Подвал сайта -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>О нас</h4>
                    <p>
                        Благотворительный фонд «Время Человека» — поддержка моральных и трезвых инициатив в России.
                    </p>
                </div>
                <div class="footer-section">
                    <h4>Навигация</h4>
                    <ul class="footer-links">
                        <li><a href="/">Главная</a></li>
                        <li><a href="/projects">Проекты</a></li>
                        <li><a href="/about">О фонде</a></li>
                        <li><a href="/reports">Отчёты</a></li>
                        <li><a href="/contacts">Контакты</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Контакты</h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope"></i> <?= e(CONTACT_EMAIL) ?></li>
                        <li><i class="fas fa-phone"></i> <?= e(CONTACT_PHONE) ?></li>
                        <li><i class="fas fa-map-marker-alt"></i> <?= e(CONTACT_ADDRESS) ?></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Правовая информация</h4>
                    <ul class="footer-links">
                        <li><a href="/privacy">Политика конфиденциальности</a></li>
                        <li><a href="/terms">Пользовательское соглашение</a></li>
                        <li><a href="/legal">Юридические документы</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Благотворительный фонд «Время Человека». Под эгидой АНО «Институт развития общества».</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.site-header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
    
    <!-- Дополнительные скрипты для конкретных страниц -->
    <?php if (isset($meta['additional_js'])): ?>
        <?php foreach ($meta['additional_js'] as $js): ?>
            <script src="<?= e($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
<?php
}

/**
 * Функция для начала буферизации контента
 */
function startContent() {
    ob_start();
}

/**
 * Функция для завершения буферизации и рендеринга layout
 */
function endContent($meta = []) {
    $content = ob_get_clean();
    renderLayout($content, $meta);
}