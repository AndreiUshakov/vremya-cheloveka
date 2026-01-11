<?php
/**
 * Главная страница сайта
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Получаем данные
$projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
$projects = MarkdownParser::sort($projects, 'publishedAt', 'desc');
$featuredProjects = MarkdownParser::getFeatured($projects, FEATURED_PROJECTS_COUNT);

$news = MarkdownParser::getAllFromDirectory(NEWS_DIR);
$news = MarkdownParser::sort($news, 'publishedAt', 'desc');
$recentNews = array_slice($news, 0, RECENT_NEWS_COUNT);

// Статистика
$totalBeneficiaries = 0;
$totalCollected = 0;
$activeProjectsCount = 0;

foreach ($projects as $project) {
    if (isset($project['beneficiariesCount'])) {
        $totalBeneficiaries += $project['beneficiariesCount'];
    }
    if (isset($project['collectedAmount'])) {
        $totalCollected += $project['collectedAmount'];
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
                    <span class="project-category"><?= e($project['category'] ?? 'Другое') ?></span>
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
<?php if (!empty($recentNews)): ?>
<section class="section rays-bg">
    <div class="container">
        <h2 class="text-center text-burgundy mb-2">
            <i class="fas fa-newspaper"></i>
            Новости и обновления
        </h2>
        <div class="news-grid">
            <?php foreach ($recentNews as $item): ?>
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
?>