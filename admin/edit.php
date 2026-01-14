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

// Обработка POST теперь в index.php, здесь только проверка на ошибки из сессии
$error = $_SESSION['error'] ?? null;
if ($error) {
    unset($_SESSION['error']);
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

    <form method="POST" enctype="multipart/form-data" class="edit-form">
        <div class="form-group">
            <label for="title">Название *</label>
            <input type="text" id="title" name="title" value="<?= e($data['title']) ?>" required>
        </div>
        
        <?php if ($type === 'projects'): ?>
        <div class="form-group">
            <label for="slug">URL (slug) *</label>
            <input type="text" id="slug" name="slug" value="<?= e($data['slug']) ?>"
                   data-current-file="<?= $isEdit ? e($file) : '' ?>"
                   required pattern="[a-z0-9\-]+"
                   title="Только строчные латинские буквы, цифры и дефисы"
                   autocomplete="off">
            <small>Генерируется автоматически из названия. Можно изменить вручную.</small>
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
                <label>Изображение проекта</label>
                <div class="image-upload-container">
                    <div class="image-preview">
                        <?php
                        $currentImage = !empty($data['imageUrl']) ? $data['imageUrl'] : '/static/img/nophoto.svg';
                        ?>
                        <img id="image-preview" src="<?= e($currentImage) ?>" alt="Превью изображения">
                    </div>
                    <div class="image-controls">
                        <input type="hidden" id="imageUrl" name="imageUrl" value="<?= e($data['imageUrl']) ?>">
                        <input type="hidden" id="deleteImage" name="deleteImage" value="0">
                        
                        <label for="imageFile" class="btn btn-primary btn-small">
                            <i class="fas fa-upload"></i> Загрузить изображение
                        </label>
                        <input type="file" id="imageFile" name="imageFile" accept="image/*" style="display: none;">
                        
                        <?php if (!empty($data['imageUrl'])): ?>
                        <button type="button" id="deleteImageBtn" class="btn btn-danger btn-small">
                            <i class="fas fa-trash"></i> Удалить изображение
                        </button>
                        <?php endif; ?>
                        
                        <small class="image-info">
                            Поддерживаемые форматы: JPG, PNG, GIF, WebP. Максимальный размер: 5 МБ
                        </small>
                    </div>
                </div>
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
                <label for="projectSlug">Связанный проект (опционально)</label>
                <select id="projectSlug" name="projectSlug">
                    <option value="">-- Не выбран --</option>
                    <?php
                    // Получаем список всех проектов
                    $projectFiles = glob(PROJECTS_DIR . '/*.md');
                    $projects = [];
                    
                    foreach ($projectFiles as $projectFile) {
                        $content = file_get_contents($projectFile);
                        if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
                            $frontmatter = $matches[1];
                            $projectData = ['title' => '', 'slug' => ''];
                            
                            // Парсим title и slug
                            if (preg_match('/^title:\s*"?([^"\n]+)"?$/m', $frontmatter, $m)) {
                                $projectData['title'] = trim($m[1], '"');
                            }
                            if (preg_match('/^slug:\s*"?([^"\n]+)"?$/m', $frontmatter, $m)) {
                                $projectData['slug'] = trim($m[1], '"');
                            }
                            
                            if (!empty($projectData['slug']) && !empty($projectData['title'])) {
                                $projects[] = $projectData;
                            }
                        }
                    }
                    
                    // Сортируем проекты по названию
                    usort($projects, function($a, $b) {
                        return strcmp($a['title'], $b['title']);
                    });
                    
                    // Выводим опции
                    foreach ($projects as $project): ?>
                        <option value="<?= e($project['slug']) ?>" <?= $data['projectSlug'] === $project['slug'] ? 'selected' : '' ?>>
                            <?= e($project['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>Выберите проект, к которому относится эта новость</small>
            </div>

            <div class="form-group">
                <label>Изображение новости</label>
                <div class="image-upload-container">
                    <div class="image-preview">
                        <?php
                        $currentImage = !empty($data['imageUrl']) ? $data['imageUrl'] : '/static/img/nophoto.svg';
                        ?>
                        <img id="image-preview" src="<?= e($currentImage) ?>" alt="Превью изображения">
                    </div>
                    <div class="image-controls">
                        <input type="hidden" id="imageUrl" name="imageUrl" value="<?= e($data['imageUrl']) ?>">
                        <input type="hidden" id="deleteImage" name="deleteImage" value="0">
                        
                        <label for="imageFile" class="btn btn-primary btn-small">
                            <i class="fas fa-upload"></i> Загрузить изображение
                        </label>
                        <input type="file" id="imageFile" name="imageFile" accept="image/*" style="display: none;">
                        
                        <?php if (!empty($data['imageUrl'])): ?>
                        <button type="button" id="deleteImageBtn" class="btn btn-danger btn-small">
                            <i class="fas fa-trash"></i> Удалить изображение
                        </button>
                        <?php endif; ?>
                        
                        <small class="image-info">
                            Поддерживаемые форматы: JPG, PNG, GIF, WebP. Максимальный размер: 5 МБ
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="featured" value="1" <?= !empty($data['featured']) && $data['featured'] !== 'false' ? 'checked' : '' ?>>
                    Показывать на главной странице (избранная новость)
                </label>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="publishedAt">Дата публикации *</label>
            <input type="date" id="publishedAt" name="publishedAt" value="<?= e($data['publishedAt']) ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Содержание *</label>
            <textarea id="content" name="content" rows="15" required><?= e($data['content']) ?></textarea>
            <small>Используйте панель инструментов для форматирования текста. Поддерживается Markdown разметка.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Сохранить</button>
            <a href="?action=list&type=<?= e($type) ?>" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>