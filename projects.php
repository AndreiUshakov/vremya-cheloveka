<?php
/**
 * Страница списка всех проектов
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Получаем параметры фильтрации
$category = $_GET['category'] ?? null;
$status = $_GET['status'] ?? null;

// Получаем все проекты
$projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);

// Применяем фильтры
if ($category) {
    $projects = MarkdownParser::filter($projects, ['category' => $category]);
}
if ($status) {
    $projects = MarkdownParser::filter($projects, ['status' => $status]);
}

// Сортируем
$projects = MarkdownParser::sort($projects, 'publishedAt', 'desc');

// Начинаем буферизацию контента
startContent();
?>

<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <h1 class="glass-section-title glass-text-center">            
            Проекты фонда
        </h1>
       
        <!-- Фильтры -->
        <div class="glass-filters-card">
            <h3 class="glass-filters-title">
                <i class="fas fa-filter"></i> Фильтры
            </h3>
            <div class="glass-filters-grid">
                <a href="/projects" class="glass-filter-btn <?= !$category ? 'active' : '' ?>">
                    <i class="fas fa-th"></i>
                    <span>Все категории</span>
                </a>
                <?php foreach (PROJECT_CATEGORIES as $key => $name): ?>
                <a href="/projects?category=<?= e($key) ?>" class="glass-filter-btn <?= $category === $key ? 'active' : '' ?>">
                    <i class="fas fa-tag"></i>
                    <span><?= e($name) ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Список проектов -->
        <?php if (empty($projects)): ?>
            <div class="glass-card glass-text-center">
                <p style="font-size: 1.2rem; margin-bottom: 1.5rem; color: var(--glass-text-secondary);">Проектов по выбранным критериям не найдено.</p>
                <a href="/projects" class="glass-btn glass-btn-primary">Сбросить фильтры</a>
            </div>
        <?php else: ?>
            <div class="glass-projects-grid" id="projects-list">
                <?php foreach ($projects as $project): ?>
                <?php
                    // Безопасное вычисление прогресса с проверкой всех значений
                    $collectedAmount = isset($project['collectedAmount']) && (!empty($project['collectedAmount'])) ? (float)$project['collectedAmount'] : 0;
                    $targetAmount = isset($project['targetAmount']) ? (float)$project['targetAmount'] : 0;
                    $progress = ($targetAmount > 0)
                        ? number_format(($collectedAmount / $targetAmount) * 100, 1)
                        : 0;
                ?>
                <div class="glass-project-card" data-category="<?= e($project['category'] ?? '') ?>">
                    <?php if (!empty($project['imageUrl'])): ?>
                    <div class="glass-project-image-wrapper">
                        <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" class="glass-project-image" />
                    </div>
                    <?php endif; ?>
                    <div class="glass-project-content">
                        <span class="glass-project-category">
                            <i class="fas fa-tag"></i>
                            <?= e(PROJECT_CATEGORIES[$project['category']] ?? $project['category']) ?>
                        </span>
                        <h3 class="glass-project-title"><?= e($project['title']) ?></h3>
                        <p class="glass-project-description"><?= e($project['shortDescription'] ?? '') ?></p>
                        
                        <?php if (isset($project['targetAmount']) && $project['targetAmount'] > 0): ?>
                        <div class="glass-project-stats">
                            <div class="glass-stat-item">
                                <span class="glass-stat-label">Собрано</span>
                                <span class="glass-stat-value"><?= formatAmount($collectedAmount) ?> ₽</span>
                            </div>
                            <div class="glass-stat-item">
                                <span class="glass-stat-label">Цель</span>
                                <span class="glass-stat-value"><?= formatAmount($project['targetAmount']) ?> ₽</span>
                            </div>
                        </div>
                        <div class="glass-progress-bar">
                            <div class="glass-progress-fill" style="width: <?= $progress ?>%">
                                <span class="glass-progress-text"><?= $progress ?>%</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="glass-project-actions">
                            <a href="/projects/<?= e($project['slug']) ?>" class="glass-btn glass-btn-outline">
                                <i class="fas fa-info-circle"></i>
                                Подробнее
                            </a>
                            <a href="/projects/<?= e($project['slug']) ?>#donate" class="glass-btn glass-btn-primary">
                                <i class="fas fa-hand-holding-heart"></i>
                                Помочь
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => 'Наши проекты',
    'description' => 'Проекты благотворительного фонда "Время Человека". Помогаем детям, семьям, боремся с алкоголизмом и реализуем социальные программы по всей России.'
]);
?>