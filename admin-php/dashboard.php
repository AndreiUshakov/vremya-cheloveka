<?php
// Подсчёт количества проектов и новостей
$projectsCount = count(glob(PROJECTS_DIR . '/*.md'));
$newsCount = count(glob(NEWS_DIR . '/*.md'));
?>

<div class="dashboard">
    <h2>Добро пожаловать в админ-панель</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Проекты</h3>
            <div class="stat-number"><?= $projectsCount ?></div>
            <a href="?action=list&type=projects" class="btn btn-primary">Управление проектами</a>
        </div>
        
        <div class="stat-card">
            <h3>Новости</h3>
            <div class="stat-number"><?= $newsCount ?></div>
            <a href="?action=list&type=news" class="btn btn-primary">Управление новостями</a>
        </div>
    </div>

    <div class="quick-actions">
        <h3>Быстрые действия</h3>
        <div class="action-buttons">
            <a href="?action=create&type=projects" class="btn btn-success">+ Создать проект</a>
            <a href="?action=create&type=news" class="btn btn-success">+ Создать новость</a>
        </div>
    </div>

    <div class="info-block">
        <h3>Инструкция</h3>
        <ul>
            <li>Используйте раздел "Проекты" для создания и редактирования проектов фонда</li>
            <li>Используйте раздел "Новости" для публикации новостей и событий</li>
            <li>Все изменения сохраняются в файлы Markdown и автоматически отображаются на сайте</li>
            <li>Для изображений используйте URL из внешних источников (например, Unsplash)</li>
        </ul>
    </div>
</div>