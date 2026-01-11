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

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Время Человека</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Админ-панель "Время Человека"</h1>
            <nav class="admin-nav">
                <a href="?action=dashboard" class="<?= $action === 'dashboard' ? 'active' : '' ?>">Главная</a>
                <a href="?action=list&type=projects" class="<?= $action === 'list' && $type === 'projects' ? 'active' : '' ?>">Проекты</a>
                <a href="?action=list&type=news" class="<?= $action === 'list' && $type === 'news' ? 'active' : '' ?>">Новости</a>
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
</body>
</html>