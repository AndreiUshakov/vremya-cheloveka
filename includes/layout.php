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
    <link rel="stylesheet" href="/static/styles.css?v=<?= time()  ?>">
    <link rel="stylesheet" href="/static/glass-theme.css?v=<?= time()  ?>">
    
    <!-- Дополнительные стили для конкретных страниц -->
    <?php if (isset($meta['additional_css'])): ?>
        <?php foreach ($meta['additional_css'] as $css): ?>
            <link rel="stylesheet" href="<?= e($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Основной контент -->
    <?= $content ?>
    
    <!-- Подвал сайта -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>О нас</h4>
                    <p>
                        Благотворительный фонд «Время Человека» — поддержка инициатив в области народосбережения и просвещения.
                    </p>
                </div>
                <div class="footer-section">
                    <h4>Навигация</h4>
                    <ul class="footer-links">
                        <li><a href="/">Главная</a></li>
                        <li><a href="/projects">Проекты</a></li>
                        <li><a href="/news">Новости</a></li>
                        <li><a href="/about">О фонде</a></li>
                        <li><a href="/documents">Документы</a></li>
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
                <p>&copy; <?= date('Y') ?> Благотворительный фонд «Время Человека». Под эгидой АНО «Институт Развития Общества».</p>
            </div>
        </div>
    </footer>
    
    <!-- Стеклянная навигация -->
    <nav class="glass-bottom-nav <?= ($_SERVER['REQUEST_URI'] === '/' || strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) ? 'nav-bottom' : 'nav-top' ?>">
        <a href="/" class="glass-nav-item <?= ($_SERVER['REQUEST_URI'] === '/' || strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) ? 'active' : '' ?>">
            <img src="/static/img/logoWhiteTranPic.png" alt="<?= e(SITE_NAME) ?>" style="height: 40px;">
            <span>Главная</span>
        </a>
        <a href="/about" class="glass-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/about') !== false ? 'active' : '' ?>">
            <i class="fas fa-circle-info"></i>
            <span>О фонде</span>
        </a>
        <a href="/projects" class="glass-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/projects') !== false ? 'active' : '' ?>">
            <i class="fas fa-folder-open"></i>
            <span>Проекты</span>
        </a>
        <a href="/news" class="glass-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/news') !== false ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i>
            <span>Новости</span>
        </a>
        <a href="/documents" class="glass-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/documents') !== false ? 'active' : '' ?>">
            <i class="fas fa-file-invoice"></i>
            <span>Документы</span>
        </a>
        <a href="/contacts" class="glass-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/contacts') !== false ? 'active' : '' ?>">
            <i class="fas fa-phone"></i>
            <span>Контакты</span>
        </a>
        <a href="/#donate" class="glass-nav-item glass-nav-cta">
            <i class="fas fa-hand-holding-heart"></i>
            <span>Сделать взнос</span>
        </a>
    </nav>
    
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