<?php
$type = $_GET['type'] ?? 'projects';
$dir = $type === 'projects' ? PROJECTS_DIR : NEWS_DIR;
$title = $type === 'projects' ? 'Проекты' : 'Новости';

// Получаем список файлов
$files = glob($dir . '/*.md');
$items = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $filename = basename($file);
    
    // Извлекаем frontmatter
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
        $frontmatter = $matches[1];
        $item = ['filename' => $filename];
        
        // Парсим YAML вручную (простой парсер)
        $lines = explode("\n", $frontmatter);
        foreach ($lines as $line) {
            if (preg_match('/^(\w+):\s*(.*)$/', $line, $m)) {
                $key = $m[1];
                $value = trim($m[2], '"\'');
                $item[$key] = $value;
            }
        }
        
        $items[] = $item;
    }
}

// Сортируем по дате публикации (новые первые)
usort($items, function($a, $b) {
    $dateA = strtotime($a['publishedAt'] ?? '2000-01-01');
    $dateB = strtotime($b['publishedAt'] ?? '2000-01-01');
    return $dateB - $dateA;
});
?>

<div class="list-page">
    <div class="list-header">
        <h2><?= e($title) ?></h2>
        <a href="?action=create&type=<?= e($type) ?>" class="btn btn-success">+ Создать</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= $type === 'projects' ? 'Проект' : 'Новость' ?> успешно сохранен<?= $type === 'projects' ? '' : 'а' ?>!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">
            <?= $type === 'projects' ? 'Проект' : 'Новость' ?> успешно удален<?= $type === 'projects' ? '' : 'а' ?>!
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <p>Пока нет <?= $type === 'projects' ? 'проектов' : 'новостей' ?></p>
            <a href="?action=create&type=<?= e($type) ?>" class="btn btn-success">Создать первый</a>
        </div>
    <?php else: ?>
        <div class="items-table">
            <table>
                <thead>
                    <tr>
                        <th>Название</th>
                        <?php if ($type === 'projects'): ?>
                            <th>Категория</th>
                            <th>Статус</th>
                        <?php else: ?>
                            <th>Проект</th>
                        <?php endif; ?>
                        <th>На главной</th>
                        <th>Дата публикации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><strong><?= e($item['title'] ?? 'Без названия') ?></strong></td>
                            <?php if ($type === 'projects'): ?>
                                <td><?= e(PROJECT_CATEGORIES[$item['category'] ?? 'other'] ?? 'Другое') ?></td>
                                <td>
                                    <span class="status status-<?= e($item['status'] ?? 'active') ?>">
                                        <?= e(PROJECT_STATUSES[$item['status'] ?? 'active'] ?? 'Активный') ?>
                                    </span>
                                </td>
                            <?php else: ?>
                                <td><?= e($item['projectSlug'] ?? '—') ?></td>
                            <?php endif; ?>
                            <td style="text-align: center;">
                                <?php if (($item['featured'] ?? 'false') === 'true'): ?>
                                    <span style="color: #28a745; font-size: 18px;">✓</span>
                                <?php else: ?>
                                    <span style="color: #ccc;">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($item['publishedAt'] ?? '—') ?></td>
                            <td class="actions">
                                <a href="?action=edit&type=<?= e($type) ?>&file=<?= urlencode($item['filename']) ?>" class="btn btn-small btn-primary">Редактировать</a>
                                <a href="?action=delete&type=<?= e($type) ?>&file=<?= urlencode($item['filename']) ?>" class="btn btn-small btn-danger" onclick="return confirm('Вы уверены?')">Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>