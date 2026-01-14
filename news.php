<?php
/**
 * Страница всех новостей
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Получаем все новости
$allNews = MarkdownParser::getAllFromDirectory(NEWS_DIR);

if ($allNews === null) {
    $allNews = [];
}

// Сортируем по дате публикации (свежие вверху)
$allNews = MarkdownParser::sort($allNews, 'publishedAt', 'desc');

// Начинаем буферизацию контента
startContent();
?>

<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <!-- Заголовок страницы -->
        <div class="glass-animate" style=" margin-bottom: 4rem;">
            <h1 class="glass-section-title" style=" margin-bottom: 1rem;">
               
                Новости фонда
            </h1>
           
        </div>

        <!-- Сетка новостей -->
        <?php if (!empty($allNews)): ?>
        <div class="glass-grid glass-grid-3">
            <?php foreach ($allNews as $index => $newsItem): ?>
            <div class="glass-news-card glass-animate-delay-<?= ($index % 3) + 1 ?>">
                <?php if (!empty($newsItem['imageUrl'])): ?>
                <div class="glass-project-image-wrapper" style="height: 200px;">
                    <img src="<?= e($newsItem['imageUrl']) ?>" alt="<?= e($newsItem['title']) ?>" class="glass-project-image" />
                </div>
                <?php endif; ?>
                
                <div class="news-content">
                    <div class="news-date glass-text-muted" style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="far fa-calendar"></i>
                        <?= formatDate($newsItem['publishedAt']) ?>
                    </div>
                    
                    <?php if ($newsItem['featured'] ?? false): ?>
                    <div style="margin-bottom: 0.75rem;">
                        <span class="glass-project-category" >
                            <i class="fas fa-star"></i>
                            Важное
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <h3 class="news-title glass-text-primary" style="margin-bottom: 0.75rem; font-size: 1.3rem; font-weight: 600; line-height: 1.3;">
                        <?= e($newsItem['title']) ?>
                    </h3>
                    
                    <?php if (!empty($newsItem['excerpt'])): ?>
                    <p class="news-excerpt glass-text-secondary" style="margin-bottom: 1.25rem; line-height: 1.6;">
                        <?= e($newsItem['excerpt']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <a href="/news/<?= e($newsItem['slug']) ?>" class="glass-btn glass-btn-outline" style="padding: 0.75rem 1.25rem; font-size: 0.95rem;">
                        <i class="fas fa-arrow-right"></i>
                        Читать полностью
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Пустое состояние -->
        <div class="glass-card glass-animate" style="text-align: center; padding: 4rem 2rem;">
            <div class="glass-icon" style="margin: 0 auto 1.5rem; opacity: 0.5;">
                <i class="fas fa-newspaper"></i>
            </div>
            <h3 class="glass-text-primary" style="margin-bottom: 1rem; font-size: 1.5rem;">
                Новостей пока нет
            </h3>
            <p class="glass-text-secondary" style="font-size: 1.1rem; max-width: 500px; margin: 0 auto;">
                Следите за обновлениями — скоро здесь появятся новости о деятельности фонда
            </p>
        </div>
        <?php endif; ?>

        <!-- Кнопка возврата на главную -->
        <div style="text-align: center; margin-top: 4rem;">
            <a href="/" class="glass-btn glass-btn-outline glass-btn-large">
                <i class="fas fa-home"></i>
                На главную
            </a>
        </div>
    </div>
</section>

<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => 'Новости фонда',
    'description' => 'Актуальные новости и обновления о деятельности благотворительного фонда «Время Человека». Узнайте о наших проектах, мероприятиях и достижениях.'
]);
?>