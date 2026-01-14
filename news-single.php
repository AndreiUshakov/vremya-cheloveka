<?php
/**
 * Страница отдельной новости
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

// Получаем данные новости
$newsItem = MarkdownParser::getBySlug(NEWS_DIR, $slug);

if (!$newsItem) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Получаем связанный проект, если указан
$relatedProject = null;
if (!empty($newsItem['projectSlug'])) {
    $relatedProject = MarkdownParser::getBySlug(PROJECTS_DIR, $newsItem['projectSlug']);
}

// Получаем другие новости (последние 3, исключая текущую)
$allNews = MarkdownParser::getAllFromDirectory(NEWS_DIR);
$otherNews = array_filter($allNews, function($item) use ($slug) {
    return $item['slug'] !== $slug;
});
$otherNews = MarkdownParser::sort($otherNews, 'publishedAt', 'desc');
$otherNews = array_slice($otherNews, 0, 3);

// Начинаем буферизацию контента
startContent();
?>

<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <!-- Заголовок новости -->
        <div class="glass-card glass-animate">
            <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <span class="glass-text-muted" style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="far fa-calendar"></i>
                    <?= formatDate($newsItem['publishedAt'] ?? null) ?>
                </span>
                
                <?php if ($newsItem['featured'] ?? false): ?>
                <span class="glass-project-category" >
                    <i class="fas fa-star"></i>
                    Важное
                </span>
                <?php endif; ?>
            </div>
            
            <h1 class="glass-text-primary" style="margin-bottom: 1.5rem; font-size: 2.5rem; font-weight: 700; line-height: 1.2;">
                <?= e($newsItem['title']) ?>
            </h1>
            
            
            
            <?php if (!empty($newsItem['imageUrl'])): ?>
            <div style="margin-bottom: 2rem; ">
                <img src="<?= e($newsItem['imageUrl']) ?>" alt="<?= e($newsItem['title']) ?>" style="width: 100%; height: auto; display: block; max-height: 300px;object-fit: contain;">
            </div>
            <?php endif; ?>
            
            <!-- Полное содержание новости -->
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="glass-text-secondary" style="line-height: 1.8; font-size: 1.1rem;">
                    <?= $newsItem['content'] ?>
                </div>
            </div>
            
            <!-- Связанный проект -->
            <?php if ($relatedProject): ?>
            <div style="margin-top: 2.5rem; padding-top: 2.5rem; border-top: 1px solid rgba(212, 175, 55, 0.3);">
                <h3 class="glass-text-primary" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-link glass-text-gold"></i>
                    Связанный проект
                </h3>
                
                <div class="glass-card" style="background: rgba(10, 14, 39, 0.4); border: 1px solid rgba(212, 175, 55, 0.3);">
                    <div style="display: flex; gap: 1.5rem; align-items: start; flex-wrap: wrap;">
                        <?php if (!empty($relatedProject['imageUrl'])): ?>
                        <div style="flex: 0 0 200px; border-radius: 12px; overflow: hidden;">
                            <img src="<?= e($relatedProject['imageUrl']) ?>" alt="<?= e($relatedProject['title']) ?>" style="width: 100%; height: auto; display: block;">
                        </div>
                        <?php endif; ?>
                        
                        <div style="flex: 1; min-width: 250px;">
                            <div style="margin-bottom: 0.75rem;">
                                <span class="glass-project-category">
                                    <i class="fas fa-folder"></i>
                                    <?= e(PROJECT_CATEGORIES[$relatedProject['category']] ?? $relatedProject['category']) ?>
                                </span>
                            </div>
                            
                            <h4 class="glass-text-primary" style="margin-bottom: 0.75rem; font-size: 1.3rem; font-weight: 600;">
                                <?= e($relatedProject['title']) ?>
                            </h4>
                            
                            <?php if (!empty($relatedProject['shortDescription'])): ?>
                            <p class="glass-text-secondary" style="margin-bottom: 1rem; line-height: 1.6;">
                                <?= e($relatedProject['shortDescription']) ?>
                            </p>
                            <?php endif; ?>
                            
                            <a href="/projects/<?= e($relatedProject['slug']) ?>" class="glass-btn glass-btn-outline">
                                <i class="fas fa-arrow-right"></i>
                                Подробнее о проекте
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Другие новости -->
        <?php if (!empty($otherNews)): ?>
        <div class="glass-section">
            <h2 class="glass-section-title glass-text-center">
                <i class="fas fa-newspaper"></i>
                Другие новости
            </h2>
            <div class="glass-grid glass-grid-3">
                <?php foreach ($otherNews as $item): ?>
                <div class="glass-news-card glass-animate">
                    <?php if (!empty($item['imageUrl'])): ?>
                    <div class="glass-project-image-wrapper" style="height: 200px;">
                        <img src="<?= e($item['imageUrl']) ?>" alt="<?= e($item['title']) ?>" class="glass-project-image" />
                    </div>
                    <?php endif; ?>
                    <div class="news-content">
                        <div class="news-date glass-text-muted" style="margin-bottom: 0.75rem;">
                            <i class="far fa-calendar"></i>
                            <?= formatDate($item['publishedAt']) ?>
                        </div>
                        <h3 class="news-title glass-text-primary" style="margin-bottom: 0.75rem; font-size: 1.2rem; font-weight: 600;">
                            <?= e($item['title']) ?>
                        </h3>
                        <p class="news-excerpt glass-text-secondary" style="margin-bottom: 1rem; line-height: 1.6;">
                            <?= e($item['excerpt'] ?? '') ?>
                        </p>
                        <a href="/news/<?= e($item['slug']) ?>" class="glass-btn glass-btn-outline" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
                            <i class="fas fa-arrow-right"></i>
                            Читать далее
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Возврат к списку новостей -->
        <div style="text-align: center; margin-top: 3rem;">
            <a href="/news" class="glass-btn glass-btn-outline glass-btn-large">
                <i class="fas fa-th-list"></i> Все новости
            </a>
        </div>
    </div>
</section>

<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => $newsItem['title'],
    'description' => $newsItem['excerpt'] ?? substr(strip_tags($newsItem['content']), 0, 200),
    'image' => $newsItem['imageUrl'] ?? null
]);
?>