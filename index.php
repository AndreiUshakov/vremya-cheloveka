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
        </div>
    </div>
</section>

<!-- О фонде (краткая секция) -->
<section class="glass-section">
    <div class="container">
        <div class="glass-mission-card glass-animate">
            <h2 class="glass-text-center glass-mb-2">
                <i class="fas fa-info-circle glass-text-gold"></i>
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
                <a href="/about" class="glass-btn glass-btn-outline glass-btn-large" style="margin-top: 1.5rem;">
                    <i class="fas fa-arrow-right"></i>
                    Узнать больше
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Избранные проекты -->
<?php if (!empty($featuredProjects)): ?>
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            <i class="fas fa-folder-open"></i>
            Наши проекты
        </h2>
        <div class="glass-grid">
            <?php foreach ($featuredProjects as $project): ?>
            <?php
                $progress = isset($project['targetAmount']) && $project['targetAmount'] > 0
                    ? number_format(($project['collectedAmount'] ?? 0) / $project['targetAmount'] * 100, 1)
                    : 0;
            ?>
            <div class="glass-program-card glass-animate-delay-<?= ($loop_index = array_search($project, $featuredProjects) + 1) > 3 ? 3 : $loop_index ?>">
                <?php if (!empty($project['imageUrl'])): ?>
                <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" class="project-image" style="border-radius: 12px; margin-bottom: 1rem;" />
                <?php endif; ?>
                <div>
                    <div class="glass-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.4rem; margin-bottom: 0.75rem;"><?= e($project['title']) ?></h3>
                    <p style="color: var(--glass-text-secondary); margin-bottom: 1rem; font-size: 0.95rem; line-height: 1.6;"><?= e($project['shortDescription'] ?? '') ?></p>
                    
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
                    
                    <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                        <a href="/projects/<?= e($project['slug']) ?>" class="glass-btn glass-btn-outline" style="flex: 1;">
                            <i class="fas fa-info-circle"></i>
                            Подробнее
                        </a>
                        <a href="/projects/<?= e($project['slug']) ?>#donate" class="glass-btn glass-btn-primary" style="flex: 1;">
                            <i class="fas fa-donate"></i>
                            Помочь
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="glass-text-center glass-mt-2">
            <a href="/projects" class="glass-btn glass-btn-primary glass-btn-large">
                <i class="fas fa-th-large"></i>
                Все проекты
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Почему нам можно доверять -->
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            <i class="fas fa-shield-alt"></i>
            Почему нам можно доверять
        </h2>
        <div class="glass-grid glass-grid-4">
            <div class="glass-card glass-animate-delay-1">
                <div class="glass-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Прозрачная отчётность</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Публикуем подробные финансовые и проектные отчёты</p>
            </div>
            <div class="glass-card glass-animate-delay-2">
                <div class="glass-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Общественный контроль</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Работаем под надзором АНО "Институт развития общества"</p>
            </div>
            <div class="glass-card glass-animate-delay-3">
                <div class="glass-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Экспертная оценка</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Все проекты проходят независимую экспертизу</p>
            </div>
            <div class="glass-card glass-animate-delay-3">
                <div class="glass-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Измеримый результат</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Отслеживаем и публикуем конкретные показатели помощи</p>
            </div>
        </div>
    </div>
</section>

<!-- Новости -->
<?php if (!empty($featuredNews)): ?>
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            <i class="fas fa-newspaper"></i>
            Новости и обновления
        </h2>
        <div class="glass-grid glass-grid-3">
            <?php foreach ($featuredNews as $item): ?>
            <div class="glass-news-card glass-animate-delay-<?= ($news_index = array_search($item, $featuredNews) + 1) > 3 ? 3 : $news_index ?>">
                <?php if (!empty($item['imageUrl'])): ?>
                <img src="<?= e($item['imageUrl']) ?>" alt="<?= e($item['title']) ?>" style="width: 100%; height: 180px; object-fit: cover;" />
                <?php endif; ?>
                <div class="news-content">
                    <div class="news-date">
                        <i class="far fa-calendar"></i>
                        <?= formatDate($item['publishedAt']) ?>
                    </div>
                    <h3 class="news-title"><?= e($item['title']) ?></h3>
                    <p class="news-excerpt"><?= e($item['excerpt'] ?? '') ?></p>
                    <a href="/news/<?= e($item['slug']) ?>" class="glass-btn glass-btn-outline" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
                        <i class="fas fa-arrow-right"></i>
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
<section class="glass-section" id="donate">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            <i class="fas fa-hand-holding-heart"></i>
            Поддержать фонд
        </h2>
        <div class="glass-card-glow glass-animate" style="max-width: 800px; margin: 0 auto; text-align: center;">
            <div class="glass-icon" style="margin: 0 auto 1.5rem;">
                <i class="fas fa-heart"></i>
            </div>
            <h3 style="color: var(--glass-text-primary); margin-bottom: 1rem; font-size: 1.5rem;">Форма приёма пожертвований</h3>
            <p style="color: var(--glass-text-secondary); margin-bottom: 1.5rem; font-size: 1.1rem;">
                Здесь будет интегрирован виджет Робокассы
            </p>
            <div style="background: rgba(10, 14, 39, 0.5); padding: 2rem; border-radius: 16px; border: 1px dashed rgba(212, 175, 55, 0.3);">
                <div style="font-family: monospace; color: var(--glass-text-muted); font-size: 0.9rem;">
                    &lt;!-- Robokassa Widget Integration --&gt;<br>
                    &lt;!-- Виджет будет добавлен при настройке платёжной системы --&gt;
                </div>
                <div style="text-align: left; color: var(--glass-text-secondary); margin-top: 1.5rem; line-height: 1.8;">
                    <strong style="color: var(--glass-gold);">Параметры интеграции:</strong><br>
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