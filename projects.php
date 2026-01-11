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

<section class="section" style="padding-top: 6rem;">
    <div class="container">
        <h1 class="text-center text-burgundy mb-2">
            <i class="fas fa-project-diagram"></i>
            Наши проекты
        </h1>
        <p class="text-center" style="max-width: 800px; margin: 0 auto 3rem; font-size: 1.1rem;">
            Фонд «Время Человека» поддерживает инициативы, направленные на укрепление
            нравственных ценностей, продвижение трезвого образа жизни и заботу о детях.
        </p>

        <!-- Фильтры -->
        <div class="floating-card" style="margin-bottom: 3rem;">
            <h3 style="margin-bottom: 1rem;">
                <i class="fas fa-filter"></i> Фильтры
            </h3>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="/projects" class="btn btn-outline filter-btn <?= !$category ? 'active' : '' ?>">Все категории</a>
                <?php foreach (PROJECT_CATEGORIES as $key => $name): ?>
                <a href="/projects?category=<?= e($key) ?>" class="btn btn-outline filter-btn <?= $category === $key ? 'active' : '' ?>">
                    <?= e($name) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Список проектов -->
        <?php if (empty($projects)): ?>
            <div class="floating-card text-center">
                <p style="font-size: 1.2rem; margin-bottom: 1.5rem;">Проектов по выбранным критериям не найдено.</p>
                <a href="/projects" class="btn btn-primary">Сбросить фильтры</a>
            </div>
        <?php else: ?>
            <div class="projects-grid" id="projects-list">
                <?php foreach ($projects as $project): ?>
                <?php
                    $progress = isset($project['targetAmount']) && $project['targetAmount'] > 0
                        ? number_format(($project['collectedAmount'] ?? 0) / $project['targetAmount'] * 100, 1)
                        : 0;
                ?>
                <div class="project-card" data-category="<?= e($project['category'] ?? '') ?>">
                    <?php if (!empty($project['imageUrl'])): ?>
                    <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" class="project-image" />
                    <?php endif; ?>
                    <div class="project-content">
                        <span class="project-category"><?= e(PROJECT_CATEGORIES[$project['category']] ?? $project['category']) ?></span>
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
        <?php endif; ?>
    </div>
</section>

<style>
    .filter-btn.active {
        background: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
    }
</style>

<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => 'Наши проекты',
    'description' => 'Проекты благотворительного фонда "Время Человека". Помогаем детям, семьям, боремся с алкоголизмом и реализуем социальные программы по всей России.'
]);
?>