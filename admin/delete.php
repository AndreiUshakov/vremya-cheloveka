<?php
$type = $_GET['type'] ?? 'projects';
$dir = $type === 'projects' ? PROJECTS_DIR : NEWS_DIR;
$file = $_GET['file'] ?? null;

if (empty($file)) {
    header('Location: ?action=list&type=' . $type);
    exit;
}

$filepath = $dir . '/' . $file;

if (!file_exists($filepath)) {
    header('Location: ?action=list&type=' . $type . '&error=not_found');
    exit;
}

// Подтверждение удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    if (unlink($filepath)) {
        header('Location: ?action=list&type=' . $type . '&deleted=1');
        exit;
    } else {
        $error = 'Ошибка удаления файла';
    }
}

// Получаем название для отображения
$content = file_get_contents($filepath);
$itemTitle = 'Неизвестно';

if (preg_match('/^---\s*\n(.*?)\n---/s', $content, $matches)) {
    if (preg_match('/title:\s*"?([^"\n]+)"?/i', $matches[1], $titleMatch)) {
        $itemTitle = trim($titleMatch[1], '"');
    }
}
?>

<div class="delete-page">
    <div class="delete-header">
        <h2>Удаление</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="delete-confirm">
        <div class="alert alert-warning">
            <strong>Внимание!</strong> Вы собираетесь удалить:
        </div>
        
        <div class="delete-info">
            <p><strong>Тип:</strong> <?= $type === 'projects' ? 'Проект' : 'Новость' ?></p>
            <p><strong>Название:</strong> <?= e($itemTitle) ?></p>
            <p><strong>Файл:</strong> <?= e($file) ?></p>
        </div>

        <p><strong>Это действие необратимо!</strong></p>

        <form method="POST" class="delete-form">
            <input type="hidden" name="confirm" value="1">
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Да, удалить</button>
                <a href="?action=list&type=<?= e($type) ?>" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
</div>