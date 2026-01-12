<?php
/**
 * Главная страница сайта
 */

// Включаем отображение ошибок для диагностики
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Функция для логирования ошибок
function logError($message, $context = []) {
    $logFile = __DIR__ . '/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
    $logMessage = "[{$timestamp}] {$message}{$contextStr}\n";
    error_log($logMessage, 3, $logFile);
}

// Функция для отображения страницы ошибки
function showErrorPage($message, $details = '') {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибка - Время Человека</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .error-container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                padding: 40px;
                max-width: 600px;
                text-align: center;
            }
            .error-icon {
                font-size: 64px;
                color: #e74c3c;
                margin-bottom: 20px;
            }
            h1 {
                color: #2c3e50;
                margin: 0 0 15px 0;
                font-size: 28px;
            }
            p {
                color: #7f8c8d;
                margin: 0 0 25px 0;
                line-height: 1.6;
            }
            .details {
                background: #f8f9fa;
                border-left: 4px solid #e74c3c;
                padding: 15px;
                margin: 20px 0;
                text-align: left;
                font-family: monospace;
                font-size: 13px;
                overflow-x: auto;
                color: #2c3e50;
            }
            .btn {
                display: inline-block;
                background: #667eea;
                color: white;
                padding: 12px 30px;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s;
            }
            .btn:hover {
                background: #764ba2;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h1>Произошла ошибка</h1>
            <p><?= htmlspecialchars($message) ?></p>
            <?php if ($details): ?>
            <div class="details">
                <strong>Подробности:</strong><br>
                <?= nl2br(htmlspecialchars($details)) ?>
            </div>
            <?php endif; ?>
            <a href="/" class="btn">Вернуться на главную</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

try {
    require_once __DIR__ . '/config.php';
    require_once INCLUDES_DIR . '/layout.php';

    // Проверка существования необходимых директорий
    if (!is_dir(PROJECTS_DIR)) {
        throw new Exception('Директория проектов не найдена: ' . PROJECTS_DIR);
    }
    if (!is_dir(NEWS_DIR)) {
        throw new Exception('Директория новостей не найдена: ' . NEWS_DIR);
    }

    // Получаем данные с обработкой ошибок
    $projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
    if ($projects === null) {
        throw new Exception('Не удалось загрузить проекты');
    }
    
    $projects = MarkdownParser::sort($projects, 'publishedAt', 'desc');
    $featuredProjects = MarkdownParser::getFeatured($projects, FEATURED_PROJECTS_COUNT);

    $news = MarkdownParser::getAllFromDirectory(NEWS_DIR);
    if ($news === null) {
        throw new Exception('Не удалось загрузить новости');
    }
    
    $news = MarkdownParser::sort($news, 'publishedAt', 'desc');
    $featuredNews = MarkdownParser::getFeatured($news, RECENT_NEWS_COUNT);

    // Статистика
    $totalBeneficiaries = 0;
    $totalCollected = 0;
    $activeProjectsCount = 0;

    foreach ($projects as $project) {
        if (isset($project['beneficiariesCount'])) {
            $totalBeneficiaries += (int)$project['beneficiariesCount'];
        }
        if (isset($project['collectedAmount'])) {
            $totalCollected += (int)$project['collectedAmount'];
        }
        if (isset($project['status']) && $project['status'] === 'active') {
            $activeProjectsCount++;
        }
    }

    // Начинаем буферизацию контента
    startContent();
?>

<!-- Секция Hero -->
<section class="hero-new">
    <!-- Видео фон -->
    <video autoplay muted loop playsinline class="hero-video-bg">
        <source src="/static/hero-video.mp4" type="video/mp4">
    </video>
    
    <!-- Затемнение -->
    <div class="hero-video-overlay"></div>
    
    <!-- Контент по центру -->
    <div class="container">
        <div class="hero-content-new fade-in-up">
            <h1>ВРЕМЯ ЧЕЛОВЕКА</h1>
            <p class="hero-subtitle">— Благотворительный фонд —</p>
            <div class="cta-buttons">
                <a href="#donate" class="btn btn-primary btn-large">
                    <i class="fas fa-hand-holding-heart"></i> Поддержать фонд
                </a>
                <a href="/projects" class="btn btn-outline btn-large">
                    <i class="fas fa-project-diagram"></i> Наши проекты
                </a>
            </div>
        </div>
    </div>
</section>

<!-- О фонде (краткая секция) -->
<section class="section">
    <div class="container">
        <div class="floating-card">
            <h2 class="text-center mb-2">
                <i class="fas fa-info-circle text-primary"></i>
                О фонде
            </h2>
            <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                    Благотворительный фонд «Время Человека» действует под эгидой АНО «Институт развития общества».
                    Мы поддерживаем проекты, направленные на укрепление моральных ценностей,
                    продвижение трезвого образа жизни и ответственного отношения к семье.
                </p>
                <p style="font-size: 1.1rem;">
                    <strong>Наши ценности:</strong> Нравственность • Трезвость • Ответственность • Прозрачность • Забота о будущем детей
                </p>
                <a href="/about" class="btn btn-outline" style="margin-top: 1.5rem;">
                    Узнать больше
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Избранные проекты -->
<?php if (!empty($featuredProjects)): ?>
<section class="section rays-bg">
    <div class="container">
        <h2 class="text-center text-burgundy mb-2">
            <i class="fas fa-folder-open"></i>
            Наши проекты
        </h2>
        <div class="projects-grid">
            <?php foreach ($featuredProjects as $project): ?>
            <?php
                $progress = isset($project['targetAmount']) && $project['targetAmount'] > 0
                    ? number_format(($project['collectedAmount'] ?? 0) / $project['targetAmount'] * 100, 1)
                    : 0;
            ?>
            <div class="project-card">
                <?php if (!empty($project['imageUrl'])): ?>
                <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" class="project-image" />
                <?php endif; ?>
                <div class="project-content">
                    <span class="project-category"><?= e(PROJECT_CATEGORIES[$project['category']] ?? PROJECT_CATEGORIES['other'] ?? 'Другое') ?></span>
                    <h3 class="project-title"><?= e($project['title']) ?></h3>
                    <p class="project-description"><?= e($project['shortDescription'] ?? '') ?></p>
                    
                    <?php if (isset($project['targetAmount']) && $project['targetAmount'] > 0): ?>
                    <div class="project-stats">
                        <div class="stat-item">
                            <span class="stat-label">Собрано</span>
                            <span class="stat-value"><?= formatAmount($project['collectedAmount'] ?? 0) ?> ₽</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Цель</span>
                            <span class="stat-value"><?= formatAmount($project['targetAmount']) ?> ₽</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                    </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="/projects/<?= e($project['slug']) ?>" class="btn btn-outline" style="flex: 1; padding: 0.75rem;">
                            Подробнее
                        </a>
                        <a href="/projects/<?= e($project['slug']) ?>#donate" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">
                            Помочь
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-2">
            <a href="/projects" class="btn btn-secondary">
                <i class="fas fa-th-large"></i> Все проекты
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Почему нам можно доверять -->
<section class="section">
    <div class="container">
        <h2 class="text-center text-burgundy mb-2">
            <i class="fas fa-shield-alt"></i>
            Почему нам можно доверять
        </h2>
        <div class="trust-grid">
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <h4 class="trust-title">Прозрачная отчётность</h4>
                <p>Публикуем подробные финансовые и проектные отчёты</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-users-cog"></i></div>
                <h4 class="trust-title">Общественный контроль</h4>
                <p>Работаем под надзором АНО "Институт развития общества"</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-certificate"></i></div>
                <h4 class="trust-title">Экспертная оценка</h4>
                <p>Все проекты проходят независимую экспертизу</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fas fa-chart-line"></i></div>
                <h4 class="trust-title">Измеримый результат</h4>
                <p>Отслеживаем и публикуем конкретные показатели помощи</p>
            </div>
        </div>
    </div>
</section>

<!-- Новости -->
<?php if (!empty($featuredNews)): ?>
<section class="section rays-bg">
    <div class="container">
        <h2 class="text-center text-burgundy mb-2">
            <i class="fas fa-newspaper"></i>
            Новости и обновления
        </h2>
        <div class="news-grid">
            <?php foreach ($featuredNews as $item): ?>
            <div class="news-card">
                <?php if (!empty($item['imageUrl'])): ?>
                <img src="<?= e($item['imageUrl']) ?>" alt="<?= e($item['title']) ?>" class="news-image" />
                <?php endif; ?>
                <div class="news-content">
                    <div class="news-date">
                        <i class="far fa-calendar"></i>
                        <?= formatDate($item['publishedAt']) ?>
                    </div>
                    <h3 class="news-title"><?= e($item['title']) ?></h3>
                    <p class="news-excerpt"><?= e($item['excerpt'] ?? '') ?></p>
                    <a href="/news/<?= e($item['slug']) ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                        Читать далее
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Заглушка для формы пожертвований -->
<section class="section" id="donate">
    <div class="container">
        <h2 class="text-center text-burgundy mb-2">
            <i class="fas fa-hand-holding-heart"></i>
            Поддержать фонд
        </h2>
        <div class="donation-widget">
            <h3>Форма приёма пожертвований</h3>
            <p>Здесь будет интегрирован виджет Робокассы</p>
            <div class="widget-placeholder">
                &lt;!-- Robokassa Widget Integration --&gt;<br>
                &lt;!-- Виджет будет добавлен при настройке платёжной системы --&gt;<br><br>
                <div style="text-align: left; color: #666;">
                Параметры интеграции:<br>
                • Общий фонд: MerchantLogin=fund_general<br>
                • Целевые проекты: MerchantLogin=fund_project_{id}<br>
                • Возможность разовых и регулярных платежей
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    // Завершаем буферизацию и рендерим страницу
    endContent([
        'title' => 'Главная',
        'description' => 'Благотворительный фонд "Время Человека" — помощь людям, нуждающимся в поддержке. Узнайте о наших проектах и присоединяйтесь к доброму делу.'
    ]);
    
} catch (Exception $e) {
    // Если ошибка произошла после начала буферизации, очищаем буфер
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Логируем ошибку
    logError('Ошибка на главной странице: ' . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    // Показываем страницу ошибки с подробностями
    showErrorPage(
        'К сожалению, не удалось загрузить главную страницу. Мы уже работаем над решением проблемы.',
        "Ошибка: " . $e->getMessage() . "\n" .
        "Файл: " . $e->getFile() . "\n" .
        "Строка: " . $e->getLine()
    );
}
?>