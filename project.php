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

<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <!-- Заголовок проекта -->
        <div class="glass-card glass-animate">
            <div style="margin-bottom: 1.5rem;">
                <span class="glass-project-category">
                    <i class="fas fa-folder"></i>
                    <?= e(PROJECT_CATEGORIES[$project['category']] ?? $project['category']) ?>
                </span>
            </div>
            
            <h1 class="glass-text-primary" style="margin-bottom: 1rem; font-size: 2.5rem; font-weight: 700;">
                <?= e($project['title']) ?>
            </h1>
            
            <p class="glass-text-secondary" style="font-size: 1.2rem; margin-bottom: 2rem; line-height: 1.6;">
                <?= e($project['shortDescription'] ?? '') ?>
            </p>
            
            <?php if (!empty($project['imageUrl'])): ?>
            <div style="margin-bottom: 2rem; border-radius: 20px; overflow: hidden;">
                <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" style="width: 100%; height: auto; display: block;">
            </div>
            <?php endif; ?>
            
            <!-- Прогресс сбора средств -->
            <?php if (isset($project['targetAmount']) && $project['targetAmount'] > 0): ?>
            <?php
                // Безопасное вычисление прогресса с проверкой всех значений
                $collectedAmount = isset($project['collectedAmount']) ? (float)$project['collectedAmount'] : 0;
                $targetAmount = (float)$project['targetAmount'];
                $progress = ($targetAmount > 0)
                    ? number_format(($collectedAmount / $targetAmount) * 100, 1)
                    : 0;
            ?>
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(212, 175, 55, 0.3);">
                <h3 class="glass-text-primary" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-hand-holding-usd glass-text-gold"></i>
                    Сбор средств
                </h3>
                
                <div class="glass-project-stats" style="margin-bottom: 1.5rem;">
                    <div class="glass-stat-item">
                        <span class="glass-stat-label">Собрано</span>
                        <span class="glass-stat-value glass-text-gold"><?= formatAmount($collectedAmount) ?> ₽</span>
                    </div>
                    <div class="glass-stat-item">
                        <span class="glass-stat-label">Цель</span>
                        <span class="glass-stat-value"><?= formatAmount($project['targetAmount']) ?> ₽</span>
                    </div>
                    <div class="glass-stat-item">
                        <span class="glass-stat-label">Прогресс</span>
                        <span class="glass-stat-value"><?= $progress ?>%</span>
                    </div>
                </div>
                
                <div class="glass-progress-bar" style="margin-bottom: 1.5rem;">
                    <div class="glass-progress-fill" style="width: <?= $progress ?>%">
                        <span class="glass-progress-text"><?= $progress ?>%</span>
                    </div>
                </div>
                
                <a href="#donate" class="glass-btn glass-btn-primary glass-btn-large" style="width: 100%; justify-content: center;">
                    <i class="fas fa-heart"></i> Помочь проекту
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Полное описание проекта -->
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="glass-text-secondary" style="line-height: 1.8;">
                    <?= $project['content'] ?>
                </div>
            </div>
        </div>

        <!-- Этапы реализации -->
        <?php if (!empty($project['milestones'])): ?>
        <div class="glass-section">
            <h2 class="glass-section-title">
                <i class="fas fa-tasks"></i>
                Этапы реализации
            </h2>
            
            <div class="glass-grid glass-grid-3">
                <?php foreach ($project['milestones'] as $index => $milestone): ?>
                <div class="glass-card glass-animate-delay-<?= min($index + 1, 3) ?> <?= isset($milestone['isCompleted']) && $milestone['isCompleted'] ? 'milestone-completed' : '' ?>" style="position: relative;">
                    <?php if (isset($milestone['isCompleted']) && $milestone['isCompleted']): ?>
                    <div style="position: absolute; top: 1rem; right: 1rem; width: 40px; height: 40px; background: linear-gradient(135deg, var(--glass-gold) 0%, var(--glass-amber) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #0a0e27;">
                        <i class="fas fa-check" style="font-size: 1.2rem;"></i>
                    </div>
                    <?php else: ?>
                    <div style="position: absolute; top: 1rem; right: 1rem; width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--glass-text-secondary); font-weight: 700;">
                        <?= $index + 1 ?>
                    </div>
                    <?php endif; ?>
                    
                    <h3 class="glass-text-primary" style="margin-bottom: 1rem; font-size: 1.3rem; padding-right: 3rem;">
                        <?= e($milestone['title'] ?? '') ?>
                    </h3>
                    
                    <?php if (!empty($milestone['description'])): ?>
                    <p class="glass-text-secondary" style="line-height: 1.6; margin-bottom: 1rem;">
                        <?= e($milestone['description']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($milestone['targetDate'])): ?>
                    <time class="glass-text-muted" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="far fa-calendar"></i>
                        Планируется: <?= formatDate($milestone['targetDate']) ?>
                    </time>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Связанные новости -->
        <?php if (!empty($relatedNews)): ?>
        <div class="glass-section">
            <h2 class="glass-section-title glass-text-center">
                <i class="fas fa-newspaper"></i>
                Новости проекта
            </h2>
            <div class="glass-grid glass-grid-1">
                <?php foreach ($relatedNews as $item): ?>
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
    </div>
</section>

   <section class="glass-section" id="donate">
    <div class="container">


    <div class="glass-mission-card glass-animate">
            
            <div style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;">
                <!-- Логотип слева -->
                <div style="flex: 0 0 auto;">
                    <img src="/static/img/qr.jpg" alt="QR code фонда Время Человека" style="max-width: 350px; width: 100%; height: auto; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);" />
                </div>
                
                <!-- Текст и кнопка справа -->
                <div style="flex: 1 1 400px;">
                    <h2 class="glass-mb-2">
                        Поддержать фонд
                    </h2>
                    <p style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--glass-text-secondary); line-height: 1.8;">
                        Воспользуйтесь специальным qr-кодом для превода в приложении любого банка, например Сбербанк Онлайн. В разделе "Платежи" нажмите кнопку "Сканировать QR или телефон" и наведите камеру на изображение слева. Этот способ ускоряет заполнение реквизитов фонда. Вы можете также сделать перевод по реквизитам, указанным на странице "Контакты"
                    </p>
                    <a href="/contacts" class="glass-btn glass-btn-primary glass-btn-large" style="margin-top: 1.5rem;">
                        <i class="fas fa-arrow-right"></i>
                        Посмотреть реквизиты
                    </a>
                </div>
            </div>
        </div>

     <!--    <h2 class="glass-section-title glass-animate">
            
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
         -->
       
        
                    
            
       

    
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