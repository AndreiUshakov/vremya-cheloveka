<?php
$type = $_GET['type'] ?? 'projects';
$dir = $type === 'projects' ? PROJECTS_DIR : NEWS_DIR;
$file = $_GET['file'] ?? null;
$isEdit = !empty($file);
$title = $isEdit ? 'Редактировать' : 'Создать';
$title .= $type === 'projects' ? ' проект' : ' новость';

$data = [
    'title' => '',
    'slug' => '',
    'shortDescription' => '',
    'excerpt' => '',
    'category' => 'other',
    'status' => 'active',
    'featured' => false,
    'targetAmount' => '',
    'collectedAmount' => 0,
    'beneficiariesCount' => 0,
    'regions' => [],
    'imageUrl' => '',
    'projectSlug' => '',
    'content' => '',
    'publishedAt' => date('Y-m-d'),
    'milestones' => []
];

// Если редактируем существующий файл
if ($isEdit && file_exists($dir . '/' . $file)) {
    $content = file_get_contents($dir . '/' . $file);
    
    // Извлекаем frontmatter и контент
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
        $frontmatter = $matches[1];
        $data['content'] = trim($matches[2]);
        
        // Парсим YAML
        $lines = explode("\n", $frontmatter);
        $currentKey = null;
        $arrayKey = null;
        
        foreach ($lines as $line) {
            $line = rtrim($line);
            
            // Массив регионов
            if (preg_match('/^regions:\s*$/', $line)) {
                $arrayKey = 'regions';
                $data['regions'] = [];
                continue;
            }
            
            // Элемент массива регионов
            if ($arrayKey === 'regions' && preg_match('/^\s+-\s+"?([^"]+)"?$/', $line, $m)) {
                $data['regions'][] = trim($m[1], '"');
                continue;
            }
            
            // Обычное поле
            if (preg_match('/^(\w+):\s*(.*)$/', $line, $m)) {
                $arrayKey = null;
                $key = $m[1];
                $value = trim($m[2], '"\'');
                
                if (isset($data[$key]) && !is_array($data[$key])) {
                    $data[$key] = $value;
                }
            }
        }
    }
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    foreach ($_POST as $key => $value) {
        if (isset($data[$key])) {
            $data[$key] = $value;
        }
    }
    
    // Регионы
    if (isset($_POST['regions']) && is_array($_POST['regions'])) {
        $data['regions'] = $_POST['regions'];
    }
    
    // Используем slug из формы или создаём из заголовка
    $slug = !empty($data['slug']) ? $data['slug'] : createSlug($data['title']);
    $filename = $slug . '.md';
    
    // Формируем frontmatter
    $frontmatter = "---\n";
    
    if ($type === 'projects') {
        $frontmatter .= "title: \"" . addslashes($data['title']) . "\"\n";
        $frontmatter .= "slug: \"" . $slug . "\"\n";
        $frontmatter .= "shortDescription: \"" . addslashes($data['shortDescription']) . "\"\n";
        $frontmatter .= "category: \"" . $data['category'] . "\"\n";
        $frontmatter .= "status: \"" . $data['status'] . "\"\n";
        $frontmatter .= "featured: " . (isset($_POST['featured']) ? 'true' : 'false') . "\n";
        
        if (!empty($data['targetAmount'])) {
            $frontmatter .= "targetAmount: " . intval($data['targetAmount']) . "\n";
        }
        
        $frontmatter .= "collectedAmount: " . intval($data['collectedAmount']) . "\n";
        $frontmatter .= "beneficiariesCount: " . intval($data['beneficiariesCount']) . "\n";
        
        if (!empty($data['regions'])) {
            $frontmatter .= "regions:\n";
            foreach ($data['regions'] as $region) {
                $frontmatter .= "  - \"" . addslashes($region) . "\"\n";
            }
        }
        
        $frontmatter .= "imageUrl: \"" . addslashes($data['imageUrl']) . "\"\n";
    } else {
        $frontmatter .= "title: \"" . addslashes($data['title']) . "\"\n";
        $frontmatter .= "excerpt: \"" . addslashes($data['excerpt']) . "\"\n";
        
        if (!empty($data['projectSlug'])) {
            $frontmatter .= "projectSlug: \"" . addslashes($data['projectSlug']) . "\"\n";
        }
        
        if (!empty($data['imageUrl'])) {
            $frontmatter .= "imageUrl: \"" . addslashes($data['imageUrl']) . "\"\n";
        }
    }
    
    $frontmatter .= "publishedAt: " . $data['publishedAt'] . "\n";
    $frontmatter .= "---\n\n";
    
    // Полное содержимое файла
    $fileContent = $frontmatter . $data['content'];
    
    // Сохраняем файл
    $filepath = $dir . '/' . $filename;
    
    if (file_put_contents($filepath, $fileContent)) {
        header('Location: ?action=list&type=' . $type . '&success=1');
        exit;
    } else {
        $error = 'Ошибка сохранения файла';
    }
}
?>

<div class="edit-page">
    <div class="edit-header">
        <h2><?= e($title) ?></h2>
        <a href="?action=list&type=<?= e($type) ?>" class="btn btn-secondary">← Назад к списку</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="edit-form">
        <div class="form-group">
            <label for="title">Название *</label>
            <input type="text" id="title" name="title" value="<?= e($data['title']) ?>" required>
        </div>
        
        <?php if ($type === 'projects'): ?>
        <div class="form-group">
            <label for="slug">URL (slug) *</label>
            <input type="text" id="slug" name="slug" value="<?= e($data['slug']) ?>" required pattern="[a-z0-9\-]+" title="Только строчные латинские буквы, цифры и дефисы">
            <small>Используется в URL страницы. Например: trezvaya-rossiya</small>
        </div>
        <?php endif; ?>

        <?php if ($type === 'projects'): ?>
            <div class="form-group">
                <label for="shortDescription">Краткое описание *</label>
                <textarea id="shortDescription" name="shortDescription" rows="3" required><?= e($data['shortDescription']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Категория *</label>
                    <select id="category" name="category" required>
                        <?php foreach (PROJECT_CATEGORIES as $key => $label): ?>
                            <option value="<?= e($key) ?>" <?= $data['category'] === $key ? 'selected' : '' ?>>
                                <?= e($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Статус *</label>
                    <select id="status" name="status" required>
                        <?php foreach (PROJECT_STATUSES as $key => $label): ?>
                            <option value="<?= e($key) ?>" <?= $data['status'] === $key ? 'selected' : '' ?>>
                                <?= e($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="targetAmount">Целевая сумма (₽)</label>
                    <input type="number" id="targetAmount" name="targetAmount" value="<?= e($data['targetAmount']) ?>">
                </div>

                <div class="form-group">
                    <label for="collectedAmount">Собрано (₽)</label>
                    <input type="number" id="collectedAmount" name="collectedAmount" value="<?= e($data['collectedAmount']) ?>">
                </div>

                <div class="form-group">
                    <label for="beneficiariesCount">Количество благополучателей</label>
                    <input type="number" id="beneficiariesCount" name="beneficiariesCount" value="<?= e($data['beneficiariesCount']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Регионы</label>
                <div class="checkbox-group">
                    <?php foreach (REGIONS as $region): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="regions[]" value="<?= e($region) ?>" 
                                <?= in_array($region, $data['regions']) ? 'checked' : '' ?>>
                            <?= e($region) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="imageUrl">URL изображения *</label>
                <input type="url" id="imageUrl" name="imageUrl" value="<?= e($data['imageUrl']) ?>" required>
                <small>Например: https://images.unsplash.com/photo-xxx</small>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="featured" value="1" <?= !empty($data['featured']) && $data['featured'] !== 'false' ? 'checked' : '' ?>>
                    Показывать на главной странице (избранный проект)
                </label>
            </div>

        <?php else: ?>
            <div class="form-group">
                <label for="excerpt">Краткое описание *</label>
                <textarea id="excerpt" name="excerpt" rows="3" required><?= e($data['excerpt']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="projectSlug">Slug проекта (опционально)</label>
                <input type="text" id="projectSlug" name="projectSlug" value="<?= e($data['projectSlug']) ?>">
                <small>Например: trezvaya-rossiya</small>
            </div>

            <div class="form-group">
                <label for="imageUrl">URL изображения (опционально)</label>
                <input type="url" id="imageUrl" name="imageUrl" value="<?= e($data['imageUrl']) ?>">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="publishedAt">Дата публикации *</label>
            <input type="date" id="publishedAt" name="publishedAt" value="<?= e($data['publishedAt']) ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Содержание (Markdown) *</label>
            <textarea id="content" name="content" rows="15" required><?= e($data['content']) ?></textarea>
            <small>Поддерживается Markdown разметка</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Сохранить</button>
            <a href="?action=list&type=<?= e($type) ?>" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>