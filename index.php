<?php
/**
 * Главная страница сайта
 */

// Включаем отображение ошибок для диагностики
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Функция для логирования ошибок
function logError($message, $context = []) {
    $logFile = __DIR__ . '/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
    $logMessage = "[{$timestamp}] {$message}{$contextStr}\n";
    error_log($logMessage, 3, $logFile);
}

// Функция для отображения страницы ошибки
function showErrorPage($message, $details = '') {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибка - Время Человека</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .error-container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                padding: 40px;
                max-width: 600px;
                text-align: center;
            }
            .error-icon {
                font-size: 64px;
                color: #e74c3c;
                margin-bottom: 20px;
            }
            h1 {
                color: #2c3e50;
                margin: 0 0 15px 0;
                font-size: 28px;
            }
            p {
                color: #7f8c8d;
                margin: 0 0 25px 0;
                line-height: 1.6;
            }
            .details {
                background: #f8f9fa;
                border-left: 4px solid #e74c3c;
                padding: 15px;
                margin: 20px 0;
                text-align: left;
                font-family: monospace;
                font-size: 13px;
                overflow-x: auto;
                color: #2c3e50;
            }
            .btn {
                display: inline-block;
                background: #667eea;
                color: white;
                padding: 12px 30px;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s;
            }
            .btn:hover {
                background: #764ba2;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h1>Произошла ошибка</h1>
            <p><?= htmlspecialchars($message) ?></p>
            <?php if ($details): ?>
            <div class="details">
                <strong>Подробности:</strong><br>
                <?= nl2br(htmlspecialchars($details)) ?>
            </div>
            <?php endif; ?>
            <a href="/" class="btn">Вернуться на главную</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

try {
    require_once __DIR__ . '/config.php';
    require_once INCLUDES_DIR . '/layout.php';

    // Проверка существования необходимых директорий
    if (!is_dir(PROJECTS_DIR)) {
        throw new Exception('Директория проектов не найдена: ' . PROJECTS_DIR);
    }
    if (!is_dir(NEWS_DIR)) {
        throw new Exception('Директория новостей не найдена: ' . NEWS_DIR);
    }

    // Получаем данные с обработкой ошибок
    $projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
    if ($projects === null) {
        throw new Exception('Не удалось загрузить проекты');
    }
    
    $projects = MarkdownParser::sort($projects, 'publishedAt', 'desc');
    $featuredProjects = MarkdownParser::getFeatured($projects, FEATURED_PROJECTS_COUNT);

    $news = MarkdownParser::getAllFromDirectory(NEWS_DIR);
    if ($news === null) {
        throw new Exception('Не удалось загрузить новости');
    }
    
    $news = MarkdownParser::sort($news, 'publishedAt', 'desc');
    $featuredNews = MarkdownParser::getFeatured($news, RECENT_NEWS_COUNT);

    // Статистика
    $totalBeneficiaries = 0;
    $totalCollected = 0;
    $activeProjectsCount = 0;

    foreach ($projects as $project) {
        if (isset($project['beneficiariesCount'])) {
            $totalBeneficiaries += (int)$project['beneficiariesCount'];
        }
        if (isset($project['collectedAmount'])) {
            $totalCollected += (int)$project['collectedAmount'];
        }
        if (isset($project['status']) && $project['status'] === 'active') {
            $activeProjectsCount++;
        }
    }

    // Начинаем буферизацию контента
    startContent();
?>

<!-- Секция Hero -->
<section class="hero-new">
    <!-- Видео фон -->
    <video autoplay muted loop playsinline class="hero-video-bg">
        <source src="/static/hero-video.mp4" type="video/mp4">
    </video>
    
    <!-- Затемнение -->
    <div class="hero-video-overlay"></div>
    
    <!-- Контент по центру -->
    <div class="container">
        <div class="hero-content-new fade-in-up">
            <!-- Логотип фонда -->
            <div style="display: flex; justify-content: center;">
                <img
                    src="/static/img/logoWhiteTranPic.png"
                    alt="Логотип фонда Время Человека"
                    style="max-width: 200px; width: 100%; height: auto; filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.5));"
                />
            </div>
            <h1>ВРЕМЯ ЧЕЛОВЕКА</h1>
            <p class="hero-subtitle">— Благотворительный фонд —</p>
            
        </div>
    </div>
</section>

<!-- О фонде (краткая секция) -->
<section class="glass-section">
    <div class="container">
        <div class="glass-mission-card glass-animate">
            
            <div style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;">
                <!-- Логотип слева -->
                <div style="flex: 0 0 auto;">
                    <img src="/static/img/brand.png" alt="Логотип фонда Время Человека" style="max-width: 250px; width: 100%; height: auto; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);" />
                </div>
                
                <!-- Текст и кнопка справа -->
                <div style="flex: 1 1 400px;">
                    <h2 class="glass-mb-2">
                        О фонде
                    </h2>
                    <p style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--glass-text-secondary); line-height: 1.8;">
                        Благотворительный фонд «Время Человека» — некоммерческая организация, созданная для поддержки инициатив в области народосбережения и просвещения. Мы объединяем благотворителей, волонтеров и специалистов для реализации социально значимых проектов в сфере образования, культуры, здоровья и семейных ценностей.
                    </p>
                    <a href="/about" class="glass-btn glass-btn-primary glass-btn-large" style="margin-top: 1.5rem;">
                        <i class="fas fa-arrow-right"></i>
                        Узнать больше
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Наши ценности -->
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            Наши ценности
        </h2>
        
        <div class="glass-values-grid">
            <!-- Ценность 1: Образование -->
            <div class="glass-value-card glass-animate-delay-1">
                <div class="glass-value-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="glass-value-content">
                    <h4>Образование и культура</h4>
                    <p>Каждый человек заслуживает доступа к качественному образованию и культурному развитию</p>
                </div>
            </div>
            
            <!-- Ценность 2: Семья -->
            <div class="glass-value-card glass-animate-delay-2">
                <div class="glass-value-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="glass-value-content">
                    <h4>Семейные ценности</h4>
                    <p>Семья — основа общества, требующая поддержки и защиты</p>
                </div>
            </div>
            
            <!-- Ценность 3: Здоровье -->
            <div class="glass-value-card glass-animate-delay-1">
                <div class="glass-value-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="glass-value-content">
                    <h4>Здоровый образ жизни</h4>
                    <p>Здоровый образ жизни и просвещение — путь к благополучию нации</p>
                </div>
            </div>
            
            <!-- Ценность 4: Волонтерство -->
            <div class="glass-value-card glass-animate-delay-2">
                <div class="glass-value-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div class="glass-value-content">
                    <h4>Добровольчество</h4>
                    <p>Волонтерство и добровольчество — движущая сила позитивных перемен</p>
                </div>
            </div>
        </div>
        
        <!-- Призыв к действию для инвесторов -->
        <div class="glass-card-glow glass-animate" style="max-width: 900px; margin: 3rem auto 0; text-align: center;">
            <div class="glass-icon" style="margin: 0 auto 1.5rem;">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <h3 style="color: var(--glass-text-primary); margin-bottom: 1rem; font-size: 1.8rem; font-weight: 600;">Станьте меценатом фонда</h3>
            <p style="color: var(--glass-text-secondary); margin-bottom: 2rem; font-size: 1.15rem; line-height: 1.8; max-width: 700px; margin-left: auto; margin-right: auto;">
                Если Вы разделяете наши ценности, поддержите проекты фонда или сделайте перевод на уставную деятельность
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="/projects" class="glass-btn glass-btn-primary glass-btn-large">
                    <i class="fas fa-folder-open"></i>
                    Поддержать проект
                </a>
                <a href="#donate" class="glass-btn glass-btn-outline glass-btn-large">
                    <i class="fas fa-donate"></i>
                    Поддержать фонд
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Направления деятельности -->
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">            
            Направления деятельности
        </h2>
        
        <div class="glass-grid glass-grid-activities">
            <!-- 1. Образование и развитие -->
            <div class="glass-activity-card glass-animate-delay-1">
                <div class="activity-header">
                    <div class="activity-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Образование и развитие</h3>
                </div>
                <ul class="activity-list">
                    <li>Разработка образовательных и развивающих программ для детей и молодежи</li>
                    <li>Техническое оснащение образовательных организаций для повышения эффективности преподавания</li>
                    <li>Создание высококачественных методических материалов для развивающих занятий</li>
                </ul>
            </div>

            <!-- 2. Наука и исследования -->
            <div class="glass-activity-card glass-animate-delay-2">
                <div class="activity-header">
                    <div class="activity-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h3>Наука и исследования</h3>
                </div>
                <ul class="activity-list">
                    <li>Проведение научно-исследовательских работ по влиянию окружающей среды и информационного пространства на развитие детей</li>
                    <li>Популяризация научных исследований в области детского развития</li>
                    <li>Содействие развитию научного потенциала региона</li>
                </ul>
            </div>

            <!-- 3. Культура и искусство -->
            <div class="glass-activity-card glass-animate-delay-3">
                <div class="activity-header">
                    <div class="activity-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Культура и искусство</h3>
                </div>
                <ul class="activity-list">
                    <li>Поддержка культурных и художественных инициатив</li>
                    <li>Производство высококачественной информационной, видео и аудиопродукции</li>
                    <li>Издательская деятельность в соответствии с целями фонда</li>
                    <li>Организация культурных и массовых мероприятий</li>
                </ul>
            </div>

            <!-- 4. Семья и здоровье -->
            <div class="glass-activity-card glass-animate-delay-1">
                <div class="activity-header">
                    <div class="activity-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Семья и здоровье</h3>
                </div>
                <ul class="activity-list">
                    <li>Укрепление и защита семьи, популяризация традиционных семейных ценностей</li>
                    <li>Поддержка многодетности и материнства</li>
                    <li>Пропаганда здорового образа жизни</li>
                    <li>Улучшение морально-психологического состояния граждан</li>
                </ul>
            </div>

            <!-- 5. Волонтерство и гражданское общество -->
            <div class="glass-activity-card glass-animate-delay-2">
                <div class="activity-header">
                    <div class="activity-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Волонтерство и гражданское общество</h3>
                </div>
                <ul class="activity-list">
                    <li>Содействие добровольческой деятельности</li>
                    <li>Поддержка молодежных инициатив и детских организаций</li>
                    <li>Патриотическое и духовно-нравственное воспитание</li>
                    <li>Правовое просвещение населения</li>
                </ul>
            </div>

            <!-- 6. Призыв к действию -->
            <div class="glass-activity-card glass-activity-card-cta glass-animate-delay-3">
                <div class="activity-header">
                    <div class="activity-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Есть идея проекта?</h3>
                </div>
                <div class="cta-content">
                    <p>Заинтересованы в сотрудничестве или хотите предложить свой проект?</p>
                    <a href="/contacts" class="glass-btn glass-btn-primary glass-btn-large">
                        <i class="fas fa-arrow-right"></i>
                        Связаться с нами
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Избранные проекты -->
<?php if (!empty($featuredProjects)): ?>
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">            
            Наши проекты
        </h2>
        <div class="glass-grid">
            <?php foreach ($featuredProjects as $project): ?>
            <?php
                // Безопасное вычисление прогресса с проверкой всех значений
                $collectedAmount = isset($project['collectedAmount']) ? (float)$project['collectedAmount'] : 0;
                $targetAmount = isset($project['targetAmount']) ? (float)$project['targetAmount'] : 0;
                $progress = ($targetAmount > 0)
                    ? number_format(($collectedAmount / $targetAmount) * 100, 1)
                    : 0;
            ?>
            <div class="glass-program-card glass-animate-delay-<?= ($loop_index = array_search($project, $featuredProjects) + 1) > 3 ? 3 : $loop_index ?>">
                <?php if (!empty($project['imageUrl'])): ?>
                <img src="<?= e($project['imageUrl']) ?>" alt="<?= e($project['title']) ?>" class="project-image" style="border-radius: 12px; margin-bottom: 1rem;" />
                <?php endif; ?>
                <div>
                    <!-- <div class="glass-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div> -->
                    <span class="glass-project-category">
                            <i class="fas fa-tag"></i>
                            <?= e(PROJECT_CATEGORIES[$project['category']] ?? $project['category']) ?>
                        </span>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.4rem; margin-bottom: 0.75rem; margin-top: 0.5rem;"><?= e($project['title']) ?></h3>
                    <p style="color: var(--glass-text-secondary); margin-bottom: 1rem; font-size: 0.95rem; line-height: 1.6;"><?= e($project['shortDescription'] ?? '') ?></p>
                    
                    <?php if (isset($project['targetAmount']) && $project['targetAmount'] > 0): ?>
                    <div class="project-stats">
                        <div class="stat-item">
                            <span class="stat-label">Собрано</span>
                            <span class="stat-value"><?= formatAmount($collectedAmount) ?> ₽</span>
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
                    
                    <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                        <a href="/projects/<?= e($project['slug']) ?>" class="glass-btn glass-btn-outline" style="flex: 1;">
                            <i class="fas fa-info-circle"></i>
                            Подробнее
                        </a>
                        <a href="/projects/<?= e($project['slug']) ?>#donate" class="glass-btn glass-btn-primary" style="flex: 1;">
                            <i class="fas fa-donate"></i>
                            Помочь
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="glass-text-center glass-mt-2">
            <a href="/projects" class="glass-btn glass-btn-primary glass-btn-large">
                
                Все проекты
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Почему нам можно доверять -->
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
           
            Почему нам можно доверять
        </h2>
        <div class="glass-grid glass-grid-4">
            <div class="glass-card glass-animate-delay-1">
                <div class="glass-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Прозрачная отчётность</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Публикуем подробные финансовые и проектные отчёты</p>
            </div>
            <div class="glass-card glass-animate-delay-2">
                <div class="glass-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Общественный контроль</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Работаем под надзором АНО "Институт Развития Общества"</p>
            </div>
            <div class="glass-card glass-animate-delay-3">
                <div class="glass-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Экспертная оценка</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Все проекты проходят независимую экспертизу</p>
            </div>
            <div class="glass-card glass-animate-delay-3">
                <div class="glass-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 style="color: var(--glass-text-primary); font-weight: 600; margin-bottom: 0.5rem;">Измеримый результат</h4>
                <p style="color: var(--glass-text-secondary); line-height: 1.6;">Отслеживаем и публикуем конкретные показатели помощи</p>
            </div>
        </div>
    </div>
</section>

<!-- Новости -->
<?php if (!empty($featuredNews)): ?>
<section class="glass-section">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            
            Новости и обновления
        </h2>
        <div class="glass-grid glass-grid-3">
            <?php foreach ($featuredNews as $item): ?>
            <div class="glass-news-card glass-animate-delay-<?= ($news_index = array_search($item, $featuredNews) + 1) > 3 ? 3 : $news_index ?>">
                <?php if (!empty($item['imageUrl'])): ?>
                <img src="<?= e($item['imageUrl']) ?>" alt="<?= e($item['title']) ?>" style="width: 100%; height: 180px; object-fit: cover;" />
                <?php endif; ?>
                <div class="news-content">
                    <div class="news-date">
                        <i class="far fa-calendar"></i>
                        <?= formatDate($item['publishedAt']) ?>
                    </div>
                    <h3 class="news-title"><?= e($item['title']) ?></h3>
                    <p class="news-excerpt"><?= e($item['excerpt'] ?? '') ?></p>
                    <a href="/news/<?= e($item['slug']) ?>" class="glass-btn glass-btn-outline" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
                        <i class="fas fa-arrow-right"></i>
                        Читать далее
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="glass-text-center glass-mt-2">
            <a href="/news" class="glass-btn glass-btn-primary glass-btn-large">
                <i class="fas fa-newspaper"></i>
                Все новости
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Заглушка для формы пожертвований -->
<section class="glass-section" id="donate">
    <div class="container">
        <h2 class="glass-section-title glass-animate">
            
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
    </div>
</section>

<?php
    // Завершаем буферизацию и рендерим страницу
    endContent([
        'title' => 'Главная',
        'description' => 'Благотворительный фонд "Время Человека" — помощь людям, нуждающимся в поддержке. Узнайте о наших проектах и присоединяйтесь к доброму делу.'
    ]);
    
} catch (Exception $e) {
    // Если ошибка произошла после начала буферизации, очищаем буфер
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Логируем ошибку
    logError('Ошибка на главной странице: ' . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    // Показываем страницу ошибки с подробностями
    showErrorPage(
        'К сожалению, не удалось загрузить главную страницу. Мы уже работаем над решением проблемы.',
        "Ошибка: " . $e->getMessage() . "\n" .
        "Файл: " . $e->getFile() . "\n" .
        "Строка: " . $e->getLine()
    );
}
?>