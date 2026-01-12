<?php
// Настройки сессии (должны быть установлены ДО session_start())
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Установите 1 если используете HTTPS

session_start();
require_once 'config.php';

// Простая проверка авторизации (пароль будет задан через .htaccess)
// Этот файл просто отображает панель управления

$action = $_GET['action'] ?? 'dashboard';
$type = $_GET['type'] ?? 'projects';

// API для получения списка slug (для проверки уникальности)
if ($action === 'api' && isset($_GET['method']) && $_GET['method'] === 'list_slugs') {
    header('Content-Type: application/json');
    
    $slugs = [];
    $dir = PROJECTS_DIR;
    
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                $slug = pathinfo($file, PATHINFO_FILENAME);
                $slugs[] = $slug;
            }
        }
    }
    
    echo json_encode(['success' => true, 'slugs' => $slugs]);
    exit;
}

// Обработка POST-запросов (сохранение, удаление) должна быть ДО вывода HTML
// чтобы редиректы работали корректно
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка удаления
    if ($action === 'delete' && isset($_POST['confirm'])) {
        $dir = $type === 'projects' ? PROJECTS_DIR : NEWS_DIR;
        $file = $_GET['file'] ?? null;
        
        if (!empty($file)) {
            $filepath = $dir . '/' . $file;
            
            if (file_exists($filepath)) {
                // Извлекаем imageUrl из файла перед удалением
                $content = file_get_contents($filepath);
                $imageUrl = '';
                
                if (preg_match('/imageUrl:\s*"([^"]+)"/', $content, $matches)) {
                    $imageUrl = $matches[1];
                }
                
                // Удаляем файл проекта
                if (unlink($filepath)) {
                    // Удаляем связанное изображение, если оно в /static/img/
                    if (!empty($imageUrl) && strpos($imageUrl, '/static/img/') === 0) {
                        $imagePath = ROOT_DIR . $imageUrl;
                        if (file_exists($imagePath) && basename($imageUrl) !== 'nophoto.svg') {
                            @unlink($imagePath);
                        }
                    }
                    
                    header('Location: ?action=list&type=' . $type . '&deleted=1');
                    exit;
                } else {
                    $_SESSION['error'] = 'Ошибка удаления файла';
                }
            }
        }
    }
    
    // Обработка формы создания/редактирования
    if (($action === 'edit' || $action === 'create') && isset($_POST['title'])) {
        $dir = $type === 'projects' ? PROJECTS_DIR : NEWS_DIR;
        
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = $value;
        }
        
        // Регионы
        if (isset($_POST['regions']) && is_array($_POST['regions'])) {
            $data['regions'] = $_POST['regions'];
        } else {
            $data['regions'] = [];
        }
        
        // Используем slug из формы или создаём из заголовка
        $slug = !empty($data['slug']) ? $data['slug'] : createSlug($data['title']);
        $filename = $slug . '.md';
        
        // Обработка загрузки изображения
        $imageUrl = $data['imageUrl'] ?? '';
        
        // Если запрошено удаление изображения
        if (!empty($data['deleteImage']) && $data['deleteImage'] === '1' && !empty($imageUrl)) {
            // Удаляем файл, если он находится в /static/img/
            if (strpos($imageUrl, '/static/img/') === 0) {
                $imagePath = ROOT_DIR . $imageUrl;
                if (file_exists($imagePath) && basename($imageUrl) !== 'nophoto.svg') {
                    @unlink($imagePath);
                }
            }
            $imageUrl = '';
        }
        
        // Если загружен новый файл
        if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
            $uploadFile = $_FILES['imageFile'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5 МБ
            
            // Проверка типа файла
            if (!in_array($uploadFile['type'], $allowedTypes)) {
                $_SESSION['error'] = 'Недопустимый формат файла. Разрешены только JPG, PNG, GIF, WebP';
                header('Location: ?action=edit&type=' . $type . '&file=' . $filename);
                exit;
            }
            
            // Проверка размера
            if ($uploadFile['size'] > $maxSize) {
                $_SESSION['error'] = 'Размер файла превышает 5 МБ';
                header('Location: ?action=edit&type=' . $type . '&file=' . $filename);
                exit;
            }
            
            // Удаляем старое изображение если оно было
            if (!empty($imageUrl) && strpos($imageUrl, '/static/img/') === 0) {
                $oldImagePath = ROOT_DIR . $imageUrl;
                if (file_exists($oldImagePath) && basename($imageUrl) !== 'nophoto.svg') {
                    @unlink($oldImagePath);
                }
            }
            
            // Генерируем уникальное имя файла
            $extension = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
            $newFilename = $slug . '-' . time() . '.' . $extension;
            $uploadPath = ROOT_DIR . '/static/img/' . $newFilename;
            
            // Перемещаем загруженный файл
            if (move_uploaded_file($uploadFile['tmp_name'], $uploadPath)) {
                $imageUrl = '/static/img/' . $newFilename;
            } else {
                $_SESSION['error'] = 'Ошибка загрузки файла';
                header('Location: ?action=edit&type=' . $type . '&file=' . $filename);
                exit;
            }
        }
        
        // Устанавливаем заглушку если изображение не указано
        if (empty($imageUrl)) {
            $imageUrl = '/static/img/nophoto.svg';
        }
        
        $data['imageUrl'] = $imageUrl;
        
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
            
            if (!empty($data['imageUrl']) && $data['imageUrl'] !== '/static/img/nophoto.svg') {
                $frontmatter .= "imageUrl: \"" . addslashes($data['imageUrl']) . "\"\n";
            }
        } else {
            $frontmatter .= "title: \"" . addslashes($data['title']) . "\"\n";
            $frontmatter .= "excerpt: \"" . addslashes($data['excerpt']) . "\"\n";
            
            if (!empty($data['projectSlug'])) {
                $frontmatter .= "projectSlug: \"" . addslashes($data['projectSlug']) . "\"\n";
            }
            
            if (!empty($data['imageUrl'])) {
                $frontmatter .= "imageUrl: \"" . addslashes($data['imageUrl']) . "\"\n";
            }
            
            $frontmatter .= "featured: " . (isset($_POST['featured']) ? 'true' : 'false') . "\n";
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
            $_SESSION['error'] = 'Ошибка сохранения файла';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Время Человека</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Админ-панель "Время Человека"</h1>
            <nav class="admin-nav">
                <a href="?action=dashboard" class="<?= $action === 'dashboard' ? 'active' : '' ?>">Главная</a>
                <a href="?action=list&type=projects" class="<?= $action === 'list' && $type === 'projects' ? 'active' : '' ?>">Проекты</a>
                <a href="?action=list&type=news" class="<?= $action === 'list' && $type === 'news' ? 'active' : '' ?>">Новости</a>
                <a href="/" target="_blank">На сайт</a>
            </nav>
        </header>

        <main class="admin-main">
            <?php
            switch ($action) {
                case 'dashboard':
                    include 'dashboard.php';
                    break;
                case 'list':
                    include 'list.php';
                    break;
                case 'edit':
                    include 'edit.php';
                    break;
                case 'create':
                    include 'edit.php';
                    break;
                case 'delete':
                    include 'delete.php';
                    break;
                default:
                    include 'dashboard.php';
            }
            ?>
        </main>

        <footer class="admin-footer">
            <p>&copy; <?= date('Y') ?> Фонд "Время Человека"</p>
        </footer>
    </div>
    
    <?php if ($action === 'edit' || $action === 'create'): ?>
    <script src="slug-generator.js"></script>
    <script src="image-upload.js"></script>
    <?php endif; ?>
</body>
</html>