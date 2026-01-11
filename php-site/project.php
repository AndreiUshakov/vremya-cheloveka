<?php
/**
 * Страница отдельного проекта
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Получаем slug из параметра
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Получаем данные проекта
$project = MarkdownParser::getBySlug(PROJECTS_DIR, $slug);

if (!$project) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Получаем связанные новости
$news = MarkdownParser::getAllFromDirectory(NEWS_DIR);
$relatedNews = array_filter($news, function($item) use ($slug) {
    return isset($item['projectSlug']) && $item['projectSlug'] === $slug;
});
$relatedNews = MarkdownParser::sort($relatedNews, 'publishedAt', 'desc');
$relatedNews = array_slice($relatedNews, 0, 3);

// Начинаем буферизацию контента
startContent();
?>

<section class="section" style="padding-top: 6rem;">
    <div class="container">
        <!-- Заголовок проекта -->
        <div class="floating-card">
            <div style="margin-bottom: 1.5rem;">
                <span class="project-category"><?= e(PROJECT_CATEGORIES[$project['category']] ?? $project['category']) ?></span>
            </div>
            
            <h1 class="text-burgundy" style="margin-bottom: 1rem;"><?= e($project['title']) ?></h1>
            
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">
                <?= e($project['shortDescription'] ?? '') ?>
            </p>
            
            <?php if (!empty($project['imageUrl'])): ?>
            <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" style="width: 100%; border-radius: 8px; margin-bottom: 2rem;">
            <?php endif; ?>
            
            <!-- Прогресс сбора средств -->
            <?php if (isset($project['targetAmount']) && $project['targetAmount'] > 0): ?>
            <?php
                $progress = number_format(($project['collectedAmount'] ?? 0) / $project['targetAmount'] * 100, 1);
            ?>
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--golden-brown);">
                <h3 style="margin-bottom: 1rem;"><i class="fas fa-hand-holding-usd"></i> Сбор средств</h3>
                
                <div class="project-stats" style="margin-bottom: 1rem;">
                    <div class="stat-item">
                        <span class="stat-label">Собрано</span>
                        <span class="stat-value"><?= formatAmount($project['collectedAmount'] ?? 0) ?> ₽</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Цель</span>
                        <span class="stat-value"><?= formatAmount($project['targetAmount']) ?> ₽</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Прогресс</span>
                        <span class="stat-value"><?= $progress ?>%</span>
                    </div>
                </div>
                
                <div class="progress-bar" style="height: 12px; margin-bottom: 1.5rem;">
                    <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                </div>
                
                <a href="/donate?project=<?= e($project['slug']) ?>" class="btn btn-primary btn-large" style="width: 100%;">
                    <i class="fas fa-heart"></i> Помочь проекту
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Основной контент -->
        <div class="section" style="padding: 2rem 0;">
            <div style="max-width: 900px; margin: 0 auto;">
                <?= $project['content'] ?>
            </div>
        </div>

<!-- Этапы реализации -->
<?php if (!empty($project['milestones'])): ?>
<section class="project-milestones">
    <div class="container">
        <h2 class="section-title">Этапы реализации</h2>
        
        <div class="milestones-list">
            <?php foreach ($project['milestones'] as $index => $milestone): ?>
            <div class="milestone <?= isset($milestone['isCompleted']) && $milestone['isCompleted'] ? 'milestone--completed' : '' ?>">
                <div class="milestone__marker">
                    <?php if (isset($milestone['isCompleted']) && $milestone['isCompleted']): ?>
                    <svg class="icon icon--check" width="24" height="24">
                        <use href="/images/icons.svg#check"></use>
                    </svg>
                    <?php else: ?>
                    <span class="milestone__number"><?= $index + 1 ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="milestone__content">
                    <h3 class="milestone__title"><?= e($milestone['title'] ?? '') ?></h3>
                    
                    <?php if (!empty($milestone['description'])): ?>
                    <p class="milestone__description"><?= e($milestone['description']) ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($milestone['targetDate'])): ?>
                    <time class="milestone__date">
                        Планируется: <?= formatDate($milestone['targetDate']) ?>
                    </time>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

        <!-- Связанные новости -->
        <?php if (!empty($relatedNews)): ?>
        <h2 class="text-center text-burgundy mb-2">
            <i class="fas fa-newspaper"></i>
            Новости проекта
        </h2>
        <div class="news-grid">
            <?php foreach ($relatedNews as $item): ?>
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
        <?php endif; ?>
    </div>
</section>

<!-- Призыв к действию -->
<section class="section" id="donate">
    <div class="container">
        <div class="floating-card text-center">
            <h2 class="text-burgundy mb-2">
                <i class="fas fa-hand-holding-heart"></i>
                Помогите проекту
            </h2>
            <p style="font-size: 1.1rem; margin-bottom: 2rem;">
                Ваше пожертвование поможет нам продолжить работу и помочь ещё большему количеству людей.
            </p>
            <div class="cta-buttons">
                <a href="/donate?project=<?= e($project['slug']) ?>" class="btn btn-primary btn-large">
                    <i class="fas fa-heart"></i> Сделать пожертвование
                </a>
                <a href="/projects" class="btn btn-outline btn-large">
                    <i class="fas fa-th-large"></i> Другие проекты
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => $project['title'],
    'description' => $project['shortDescription'] ?? $project['excerpt'],
    'image' => $project['imageUrl'] ?? null
]);
?>