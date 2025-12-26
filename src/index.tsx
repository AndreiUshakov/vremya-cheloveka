import { Hono } from 'hono'
import { cors } from 'hono/cors'
import { serveStatic } from 'hono/cloudflare-workers'

type Bindings = {
  DB: D1Database;
}

const app = new Hono<{ Bindings: Bindings }>()

// CORS для API
app.use('/api/*', cors())

// Статические файлы
app.use('/static/*', serveStatic({ root: './public' }))

// API: Получить список проектов
app.get('/api/projects', async (c) => {
  try {
    const { category, status } = c.req.query()
    
    let query = 'SELECT * FROM projects WHERE 1=1'
    const params: string[] = []
    
    if (category) {
      query += ' AND category = ?'
      params.push(category)
    }
    
    if (status) {
      query += ' AND status = ?'
      params.push(status)
    } else {
      query += ' AND status = "active"'
    }
    
    query += ' ORDER BY created_at DESC'
    
    const { results } = await c.env.DB.prepare(query).bind(...params).all()
    
    return c.json({
      success: true,
      projects: results
    })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to fetch projects' }, 500)
  }
})

// API: Получить один проект по slug
app.get('/api/projects/:slug', async (c) => {
  try {
    const slug = c.req.param('slug')
    
    const project = await c.env.DB.prepare(
      'SELECT * FROM projects WHERE slug = ?'
    ).bind(slug).first()
    
    if (!project) {
      return c.json({ success: false, error: 'Project not found' }, 404)
    }
    
    // Получить этапы проекта
    const { results: milestones } = await c.env.DB.prepare(
      'SELECT * FROM project_milestones WHERE project_id = ? ORDER BY sort_order'
    ).bind(project.id).all()
    
    return c.json({
      success: true,
      project: {
        ...project,
        milestones
      }
    })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to fetch project' }, 500)
  }
})

// API: Получить новости
app.get('/api/news', async (c) => {
  try {
    const limit = parseInt(c.req.query('limit') || '10')
    
    const { results } = await c.env.DB.prepare(
      'SELECT * FROM news ORDER BY published_at DESC LIMIT ?'
    ).bind(limit).all()
    
    return c.json({
      success: true,
      news: results
    })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to fetch news' }, 500)
  }
})

// API: Получить отчёты
app.get('/api/reports', async (c) => {
  try {
    const { year, type } = c.req.query()
    
    let query = 'SELECT * FROM reports WHERE 1=1'
    const params: (string | number)[] = []
    
    if (year) {
      query += ' AND year = ?'
      params.push(parseInt(year))
    }
    
    if (type) {
      query += ' AND type = ?'
      params.push(type)
    }
    
    query += ' ORDER BY year DESC, created_at DESC'
    
    const { results } = await c.env.DB.prepare(query).bind(...params).all()
    
    return c.json({
      success: true,
      reports: results
    })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to fetch reports' }, 500)
  }
})

// API: Получить партнёров
app.get('/api/partners', async (c) => {
  try {
    const { results } = await c.env.DB.prepare(
      'SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order'
    ).all()
    
    return c.json({
      success: true,
      partners: results
    })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to fetch partners' }, 500)
  }
})

// Главная страница
app.get('/', async (c) => {
  // Получить избранные проекты
  const { results: projects } = await c.env.DB.prepare(
    'SELECT * FROM projects WHERE status = "active" ORDER BY created_at DESC LIMIT 3'
  ).all()
  
  // Получить последние новости
  const { results: news } = await c.env.DB.prepare(
    'SELECT * FROM news ORDER BY published_at DESC LIMIT 4'
  ).all()
  
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Время Человека - Благотворительный фонд</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
    </head>
    <body>
        <!-- Header -->
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <a href="/" class="logo">
                        <i class="fas fa-heart"></i> ВРЕМЯ ЧЕЛОВЕКА
                    </a>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="/">Главная</a></li>
                            <li><a href="/projects">Проекты</a></li>
                            <li><a href="/about">О фонде</a></li>
                            <li><a href="/reports">Отчёты</a></li>
                            <li><a href="/contacts">Контакты</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero-new">
            <!-- Видео фон -->
            <video autoplay muted loop playsinline class="hero-video-bg">
                <source src="/hero-video.mp4" type="video/mp4">
            </video>
            
            <!-- Затемнение -->
            <div class="hero-video-overlay"></div>
            
            <!-- Контент по центру -->
            <div class="container">
                <div class="hero-content-new fade-in-up">
                    <h1>ВРЕМЯ ЧЕЛОВЕКА</h1>
                    <p class="hero-subtitle">— Благотворительный фонд —</p>
                    <div class="cta-buttons">
                        <a href="#donate" class="btn btn-primary btn-large">
                            <i class="fas fa-hand-holding-heart"></i> Поддержать фонд
                        </a>
                        <a href="/projects" class="btn btn-outline btn-large">
                            <i class="fas fa-project-diagram"></i> Наши проекты
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- О фонде (краткая секция) -->
        <section class="section section-overlap">
            <div class="container">
                <div class="floating-card">
                    <h2 class="text-center mb-2">
                        <i class="fas fa-info-circle text-primary"></i>
                        О фонде
                    </h2>
                    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                        <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                            Благотворительный фонд «Время Человека» действует под эгидой АНО «Институт развития общества». 
                            Мы поддерживаем проекты, направленные на укрепление моральных ценностей, 
                            продвижение трезвого образа жизни и ответственного отношения к семье.
                        </p>
                        <p style="font-size: 1.1rem;">
                            <strong>Наши ценности:</strong> Нравственность • Трезвость • Ответственность • Прозрачность • Забота о будущем детей
                        </p>
                        <a href="/about" class="btn btn-outline" style="margin-top: 1.5rem;">
                            Узнать больше
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Избранные проекты -->
        <section class="section rays-bg">
            <div class="container">
                <h2 class="text-center text-burgundy mb-2">
                    <i class="fas fa-folder-open"></i>
                    Наши проекты
                </h2>
                <div class="projects-grid" id="featured-projects">
                    ${projects.map((project: any) => `
                        <div class="project-card">
                            <img src="${project.image_url}" alt="${project.title}" class="project-image" />
                            <div class="project-content">
                                <span class="project-category">${getCategoryName(project.category)}</span>
                                <h3 class="project-title">${project.title}</h3>
                                <p class="project-description">${project.short_description}</p>
                                
                                ${project.target_amount ? `
                                    <div class="project-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Собрано</span>
                                            <span class="stat-value">${formatAmount(project.collected_amount)} ₽</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Цель</span>
                                            <span class="stat-value">${formatAmount(project.target_amount)} ₽</span>
                                        </div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: ${(project.collected_amount / project.target_amount * 100).toFixed(1)}%"></div>
                                    </div>
                                ` : ''}
                                
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="/projects/${project.slug}" class="btn btn-outline" style="flex: 1; padding: 0.75rem;">
                                        Подробнее
                                    </a>
                                    <a href="#donate-${project.id}" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">
                                        Помочь
                                    </a>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="text-center mt-2">
                    <a href="/projects" class="btn btn-secondary">
                        <i class="fas fa-th-large"></i> Все проекты
                    </a>
                </div>
            </div>
        </section>

        <!-- Почему нам можно доверять -->
        <section class="section">
            <div class="container">
                <h2 class="text-center text-burgundy mb-2">
                    <i class="fas fa-shield-alt"></i>
                    Почему нам можно доверять
                </h2>
                <div class="trust-grid">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h4 class="trust-title">Прозрачная отчётность</h4>
                        <p>Публикуем подробные финансовые и проектные отчёты</p>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-users-cog"></i></div>
                        <h4 class="trust-title">Общественный контроль</h4>
                        <p>Работаем под надзором АНО "Институт развития общества"</p>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-certificate"></i></div>
                        <h4 class="trust-title">Экспертная оценка</h4>
                        <p>Все проекты проходят независимую экспертизу</p>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-chart-line"></i></div>
                        <h4 class="trust-title">Измеримый результат</h4>
                        <p>Отслеживаем и публикуем конкретные показатели помощи</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Как работают пожертвования -->
        <section class="section section-overlap">
            <div class="container">
                <div class="floating-card">
                    <h2 class="text-center text-burgundy mb-2">
                        <i class="fas fa-route"></i>
                        Как работают пожертвования
                    </h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-top: 2rem;">
                        <div style="text-align: center;">
                            <div style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;">1</div>
                            <h4>Вы делаете пожертвование</h4>
                            <p>Выбираете проект или общий фонд</p>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;">2</div>
                            <h4>Средства поступают</h4>
                            <p>На целевой счёт проекта</p>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;">3</div>
                            <h4>Реализация проекта</h4>
                            <p>Команда выполняет задачи проекта</p>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;">4</div>
                            <h4>Отчёт о результатах</h4>
                            <p>Вы видите конкретный результат вашей помощи</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Заглушка для формы пожертвований -->
        <section class="section" id="donate">
            <div class="container">
                <h2 class="text-center text-burgundy mb-2">
                    <i class="fas fa-hand-holding-heart"></i>
                    Поддержать фонд
                </h2>
                <div class="donation-widget">
                    <h3>Форма приёма пожертвований</h3>
                    <p>Здесь будет интегрирован виджет Робокассы</p>
                    <div class="widget-placeholder">
                        &lt;!-- Robokassa Widget Integration --&gt;<br>
                        &lt;!-- Виджет будет добавлен при настройке платёжной системы --&gt;<br><br>
                        <div style="text-align: left; color: #666;">
                        Параметры интеграции:<br>
                        • Общий фонд: MerchantLogin=fund_general<br>
                        • Целевые проекты: MerchantLogin=fund_project_{id}<br>
                        • Возможность разовых и регулярных платежей
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Новости -->
        <section class="section rays-bg">
            <div class="container">
                <h2 class="text-center text-burgundy mb-2">
                    <i class="fas fa-newspaper"></i>
                    Новости и обновления
                </h2>
                <div class="news-grid">
                    ${news.map((item: any) => `
                        <div class="news-card">
                            ${item.image_url ? `<img src="${item.image_url}" alt="${item.title}" class="news-image" />` : ''}
                            <div class="news-content">
                                <div class="news-date">
                                    <i class="far fa-calendar"></i>
                                    ${new Date(item.published_at).toLocaleDateString('ru-RU')}
                                </div>
                                <h3 class="news-title">${item.title}</h3>
                                <p class="news-excerpt">${item.excerpt}</p>
                                <a href="/news/${item.slug}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    Читать далее
                                </a>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h4>О нас</h4>
                        <p>
                            Благотворительный фонд «Время Человека» — поддержка моральных и трезвых инициатив в России.
                        </p>
                    </div>
                    <div class="footer-section">
                        <h4>Навигация</h4>
                        <ul class="footer-links">
                            <li><a href="/">Главная</a></li>
                            <li><a href="/projects">Проекты</a></li>
                            <li><a href="/about">О фонде</a></li>
                            <li><a href="/reports">Отчёты</a></li>
                            <li><a href="/contacts">Контакты</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Контакты</h4>
                        <ul class="footer-links">
                            <li><i class="fas fa-envelope"></i> info@vremyacheloveka.ru</li>
                            <li><i class="fas fa-phone"></i> +7 (495) 123-45-67</li>
                            <li><i class="fas fa-map-marker-alt"></i> Москва, Россия</li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Правовая информация</h4>
                        <ul class="footer-links">
                            <li><a href="/privacy">Политика конфиденциальности</a></li>
                            <li><a href="/terms">Пользовательское соглашение</a></li>
                            <li><a href="/legal">Юридические документы</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>© 2024 Благотворительный фонд «Время Человека». Под эгидой АНО «Институт развития общества».</p>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    </body>
    </html>
  `)
})

// Страница списка проектов
app.get('/projects', async (c) => {
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Проекты - Время Человека</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <a href="/" class="logo">
                        <i class="fas fa-heart"></i> ВРЕМЯ ЧЕЛОВЕКА
                    </a>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="/">Главная</a></li>
                            <li><a href="/projects" style="color: var(--golden-brown);">Проекты</a></li>
                            <li><a href="/about">О фонде</a></li>
                            <li><a href="/reports">Отчёты</a></li>
                            <li><a href="/contacts">Контакты</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <section class="section" style="padding-top: 3rem;">
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
                        <button class="btn btn-outline filter-btn" data-category="all">Все категории</button>
                        <button class="btn btn-outline filter-btn" data-category="social">Социальные</button>
                        <button class="btn btn-outline filter-btn" data-category="children">Дети</button>
                        <button class="btn btn-outline filter-btn" data-category="education">Образование</button>
                        <button class="btn btn-outline filter-btn" data-category="health">Здоровье</button>
                    </div>
                </div>

                <!-- Список проектов -->
                <div class="projects-grid" id="projects-list">
                    <div class="text-center" style="grid-column: 1 / -1; padding: 3rem;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: var(--primary-red);"></i>
                        <p style="margin-top: 1rem;">Загрузка проектов...</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer">
            <div class="container">
                <div class="footer-bottom">
                    <p>© 2024 Благотворительный фонд «Время Человека»</p>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
        <script src="/static/projects.js"></script>
    </body>
    </html>
  `)
})

// Вспомогательные функции
function getCategoryName(category: string): string {
  const categories: Record<string, string> = {
    'children': 'Дети',
    'education': 'Образование',
    'health': 'Здоровье',
    'social': 'Социальные',
    'other': 'Другое'
  }
  return categories[category] || category
}

function formatAmount(amount: number): string {
  return new Intl.NumberFormat('ru-RU').format(amount)
}

// Страница детального просмотра проекта
app.get('/projects/:slug', async (c) => {
  try {
    const slug = c.req.param('slug')
    
    const project = await c.env.DB.prepare(
      'SELECT * FROM projects WHERE slug = ?'
    ).bind(slug).first()
    
    if (!project) {
      return c.html('<h1>Проект не найден</h1>', 404)
    }
    
    // Получить этапы проекта
    const { results: milestones } = await c.env.DB.prepare(
      'SELECT * FROM project_milestones WHERE project_id = ? ORDER BY sort_order'
    ).bind(project.id).all()
    
    return c.html(`
      <!DOCTYPE html>
      <html lang="ru">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>${project.title} - Время Человека</title>
          <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
          <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
          <link href="/static/styles.css" rel="stylesheet">
      </head>
      <body>
          <header class="site-header">
              <div class="container">
                  <div class="header-content">
                      <a href="/" class="logo">
                          <i class="fas fa-heart"></i> ВРЕМЯ ЧЕЛОВЕКА
                      </a>
                      <nav class="main-nav">
                          <ul>
                              <li><a href="/">Главная</a></li>
                              <li><a href="/projects">Проекты</a></li>
                              <li><a href="/about">О фонде</a></li>
                              <li><a href="/reports">Отчёты</a></li>
                              <li><a href="/contacts">Контакты</a></li>
                          </ul>
                      </nav>
                  </div>
              </div>
          </header>

          <section class="section" style="padding-top: 3rem;">
              <div class="container">
                  <div style="margin-bottom: 2rem;">
                      <a href="/projects" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                          <i class="fas fa-arrow-left"></i> Назад к проектам
                      </a>
                  </div>

                  <!-- Заголовок проекта -->
                  <div class="floating-card">
                      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                          <img src="${project.image_url}" alt="${project.title}" style="width: 100%; border-radius: 8px;" />
                          <div>
                              <span class="project-category">${getCategoryName(project.category)}</span>
                              <h1 class="text-burgundy" style="margin: 1rem 0;">${project.title}</h1>
                              <p style="font-size: 1.2rem; margin-bottom: 2rem;">${project.short_description}</p>
                              
                              ${project.target_amount ? `
                                  <div class="project-stats" style="margin-bottom: 1.5rem;">
                                      <div class="stat-item">
                                          <span class="stat-label">Собрано</span>
                                          <span class="stat-value">${formatAmount(project.collected_amount)} ₽</span>
                                      </div>
                                      <div class="stat-item">
                                          <span class="stat-label">Цель</span>
                                          <span class="stat-value">${formatAmount(project.target_amount)} ₽</span>
                                      </div>
                                      <div class="stat-item">
                                          <span class="stat-label">Получателей</span>
                                          <span class="stat-value">${project.beneficiaries_count.toLocaleString('ru-RU')}</span>
                                      </div>
                                  </div>
                                  <div class="progress-bar" style="margin-bottom: 1.5rem;">
                                      <div class="progress-fill" style="width: ${(project.collected_amount / project.target_amount * 100).toFixed(1)}%"></div>
                                  </div>
                              ` : ''}
                              
                              <a href="#donate-widget" class="btn btn-primary" style="padding: 1rem 2rem;">
                                  <i class="fas fa-hand-holding-heart"></i> Помочь проекту
                              </a>
                          </div>
                      </div>
                  </div>

                  <!-- Описание проекта -->
                  <div class="floating-card" style="margin-top: 2rem;">
                      <h2 class="text-burgundy mb-2">
                          <i class="fas fa-align-left"></i>
                          О проекте
                      </h2>
                      <p style="font-size: 1.1rem; line-height: 1.8; white-space: pre-line;">${project.full_description}</p>
                  </div>

                  <!-- Этапы реализации -->
                  ${milestones.length > 0 ? `
                      <div class="floating-card" style="margin-top: 2rem;">
                          <h2 class="text-burgundy mb-2">
                              <i class="fas fa-tasks"></i>
                              Этапы реализации
                          </h2>
                          <div style="display: flex; flex-direction: column; gap: 1rem;">
                              ${milestones.map((milestone: any) => `
                                  <div style="display: flex; align-items: start; padding: 1rem; background: ${milestone.is_completed ? '#e8f5e9' : '#fff3e0'}; border-radius: 8px; border-left: 4px solid ${milestone.is_completed ? 'var(--dark-green)' : 'var(--golden-brown)'};">
                                      <div style="font-size: 2rem; margin-right: 1rem;">
                                          ${milestone.is_completed ? '✅' : '⏳'}
                                      </div>
                                      <div style="flex: 1;">
                                          <h4 style="margin-bottom: 0.5rem; color: var(--deep-burgundy);">${milestone.title}</h4>
                                          ${milestone.description ? `<p style="color: #666;">${milestone.description}</p>` : ''}
                                          ${milestone.target_date ? `<p style="font-size: 0.9rem; color: #888; margin-top: 0.5rem;"><i class="far fa-calendar"></i> ${new Date(milestone.target_date).toLocaleDateString('ru-RU')}</p>` : ''}
                                      </div>
                                  </div>
                              `).join('')}
                          </div>
                      </div>
                  ` : ''}

                  <!-- Форма пожертвования -->
                  <div id="donate-widget" style="margin-top: 2rem;">
                      <div class="donation-widget">
                          <h3>Поддержать проект "${project.title}"</h3>
                          <p>Здесь будет интегрирован виджет Робокассы для пожертвований</p>
                          <div class="widget-placeholder">
                              &lt;!-- Robokassa Widget для проекта ${project.slug} --&gt;<br>
                              &lt;!-- MerchantLogin=fund_project_${project.id} --&gt;<br>
                              &lt;!-- Виджет будет добавлен при настройке платёжной системы --&gt;
                          </div>
                      </div>
                  </div>
              </div>
          </section>

          <footer class="site-footer">
              <div class="container">
                  <div class="footer-bottom">
                      <p>© 2024 Благотворительный фонд «Время Человека»</p>
                  </div>
              </div>
          </footer>
      </body>
      </html>
    `)
  } catch (error) {
    return c.html('<h1>Ошибка загрузки проекта</h1>', 500)
  }
})

// Страница "О фонде"
app.get('/about', (c) => {
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>О фонде - Время Человека</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <a href="/" class="logo">
                        <i class="fas fa-heart"></i> ВРЕМЯ ЧЕЛОВЕКА
                    </a>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="/">Главная</a></li>
                            <li><a href="/projects">Проекты</a></li>
                            <li><a href="/about" style="color: var(--golden-brown);">О фонде</a></li>
                            <li><a href="/reports">Отчёты</a></li>
                            <li><a href="/contacts">Контакты</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <section class="section" style="padding-top: 3rem;">
            <div class="container">
                <h1 class="text-center text-burgundy mb-2">
                    <i class="fas fa-info-circle"></i>
                    О благотворительном фонде «Время Человека»
                </h1>

                <div class="floating-card">
                    <h2 class="text-burgundy mb-2">Наша миссия</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Благотворительный фонд «Время Человека» создан для поддержки инициатив, направленных на укрепление моральных ценностей, 
                        продвижение трезвого образа жизни и ответственного отношения к семье и воспитанию детей в России.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Мы верим, что каждый человек заслуживает возможности жить достойно, воспитывать детей в здоровой среде 
                        и получать поддержку в трудные моменты жизни.
                    </p>
                </div>

                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">Под эгидой АНО «Институт развития общества»</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Фонд действует под контролем и при поддержке Автономной некоммерческой организации «Институт развития общества», 
                        что гарантирует прозрачность, профессионализм и ответственность в реализации всех проектов.
                    </p>
                </div>

                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">Наши ценности</h2>
                    <div class="trust-grid">
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-balance-scale"></i></div>
                            <h4 class="trust-title">Нравственность</h4>
                            <p>Поддержка традиционных моральных принципов и семейных ценностей</p>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-glass-water"></i></div>
                            <h4 class="trust-title">Трезвость</h4>
                            <p>Продвижение здорового образа жизни без алкоголя и вредных привычек</p>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-user-shield"></i></div>
                            <h4 class="trust-title">Ответственность</h4>
                            <p>Воспитание ответственного отношения к семье, детям и обществу</p>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-eye"></i></div>
                            <h4 class="trust-title">Прозрачность</h4>
                            <p>Открытая отчётность о деятельности и использовании средств</p>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-child"></i></div>
                            <h4 class="trust-title">Забота о детях</h4>
                            <p>Создание условий для здорового развития подрастающего поколения</p>
                        </div>
                    </div>
                </div>

                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">Принципы работы</h2>
                    <div style="display: grid; gap: 1.5rem;">
                        <div style="border-left: 4px solid var(--primary-red); padding-left: 1.5rem;">
                            <h4 style="color: var(--deep-burgundy); margin-bottom: 0.5rem;">Профессионализм</h4>
                            <p>Все проекты реализуются командой опытных специалистов с привлечением экспертов</p>
                        </div>
                        <div style="border-left: 4px solid var(--dark-green); padding-left: 1.5rem;">
                            <h4 style="color: var(--deep-burgundy); margin-bottom: 0.5rem;">Целевое использование средств</h4>
                            <p>Каждый рубль идёт на заявленные цели с полной отчётностью</p>
                        </div>
                        <div style="border-left: 4px solid var(--golden-brown); padding-left: 1.5rem;">
                            <h4 style="color: var(--deep-burgundy); margin-bottom: 0.5rem;">Измеримость результата</h4>
                            <p>Мы ставим конкретные цели и отслеживаем их достижение</p>
                        </div>
                        <div style="border-left: 4px solid var(--primary-red); padding-left: 1.5rem;">
                            <h4 style="color: var(--deep-burgundy); margin-bottom: 0.5rem;">Общественный контроль</h4>
                            <p>Деятельность фонда открыта для проверки и независимого аудита</p>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 3rem;">
                    <a href="/projects" class="btn btn-primary" style="margin-right: 1rem;">
                        <i class="fas fa-folder-open"></i> Наши проекты
                    </a>
                    <a href="#donate" class="btn btn-secondary">
                        <i class="fas fa-hand-holding-heart"></i> Поддержать фонд
                    </a>
                </div>
            </div>
        </section>

        <footer class="site-footer">
            <div class="container">
                <div class="footer-bottom">
                    <p>© 2024 Благотворительный фонд «Время Человека»</p>
                </div>
            </div>
        </footer>
    </body>
    </html>
  `)
})

// Страница "Отчёты"
app.get('/reports', async (c) => {
  const { results: reports } = await c.env.DB.prepare(
    'SELECT * FROM reports ORDER BY year DESC, created_at DESC'
  ).all()
  
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Отчёты - Время Человека</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <a href="/" class="logo">
                        <i class="fas fa-heart"></i> ВРЕМЯ ЧЕЛОВЕКА
                    </a>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="/">Главная</a></li>
                            <li><a href="/projects">Проекты</a></li>
                            <li><a href="/about">О фонде</a></li>
                            <li><a href="/reports" style="color: var(--golden-brown);">Отчёты</a></li>
                            <li><a href="/contacts">Контакты</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <section class="section" style="padding-top: 3rem;">
            <div class="container">
                <h1 class="text-center text-burgundy mb-2">
                    <i class="fas fa-file-invoice"></i>
                    Отчёты о деятельности
                </h1>
                <p class="text-center" style="max-width: 800px; margin: 0 auto 3rem; font-size: 1.1rem;">
                    Мы открыто публикуем финансовые и проектные отчёты, чтобы каждый жертвователь 
                    мог видеть, как используются средства фонда.
                </p>

                <div class="floating-card">
                    <h2 class="text-burgundy mb-2">Финансовые отчёты</h2>
                    ${reports.filter((r: any) => r.type === 'financial').length > 0 ? 
                      reports.filter((r: any) => r.type === 'financial').map((report: any) => `
                        <div style="padding: 1.5rem; background: white; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid var(--dark-green);">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <h4 style="color: var(--deep-burgundy); margin-bottom: 0.5rem;">${report.title}</h4>
                                    ${report.description ? `<p style="color: #666; margin-bottom: 1rem;">${report.description}</p>` : ''}
                                    <div style="font-size: 0.9rem; color: #888;">
                                        <i class="far fa-calendar"></i> ${report.year} год
                                    </div>
                                </div>
                                <a href="${report.file_url}" class="btn btn-outline" style="padding: 0.75rem 1.5rem;" download>
                                    <i class="fas fa-download"></i> Скачать PDF
                                </a>
                            </div>
                        </div>
                      `).join('') 
                      : '<p style="color: #888;">Финансовые отчёты будут опубликованы в ближайшее время</p>'
                    }
                </div>

                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">Проектные отчёты</h2>
                    ${reports.filter((r: any) => r.type === 'project').length > 0 ? 
                      reports.filter((r: any) => r.type === 'project').map((report: any) => `
                        <div style="padding: 1.5rem; background: white; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid var(--primary-red);">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <h4 style="color: var(--deep-burgundy); margin-bottom: 0.5rem;">${report.title}</h4>
                                    ${report.description ? `<p style="color: #666; margin-bottom: 1rem;">${report.description}</p>` : ''}
                                    <div style="font-size: 0.9rem; color: #888;">
                                        <i class="far fa-calendar"></i> ${report.year} год
                                    </div>
                                </div>
                                <a href="${report.file_url}" class="btn btn-outline" style="padding: 0.75rem 1.5rem;" download>
                                    <i class="fas fa-download"></i> Скачать PDF
                                </a>
                            </div>
                        </div>
                      `).join('') 
                      : '<p style="color: #888;">Проектные отчёты будут опубликованы в ближайшее время</p>'
                    }
                </div>

                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">Методология учёта</h2>
                    <p style="line-height: 1.8;">
                        Все финансовые операции фонда ведутся в соответствии с российским законодательством 
                        и международными стандартами финансовой отчётности. Ежегодно проводится независимый аудит.
                    </p>
                    <div style="margin-top: 1.5rem;">
                        <h4 style="color: var(--deep-burgundy); margin-bottom: 1rem;">Принципы учёта средств:</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                                <i class="fas fa-check text-primary"></i> 
                                <strong>Целевое использование:</strong> 100% средств, собранных на конкретный проект, идут на его реализацию
                            </li>
                            <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                                <i class="fas fa-check text-primary"></i>
                                <strong>Административные расходы:</strong> Не более 15% от общих средств фонда
                            </li>
                            <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                                <i class="fas fa-check text-primary"></i>
                                <strong>Прозрачность:</strong> Публикация отчётов каждые 6 месяцев
                            </li>
                            <li style="padding: 0.5rem 0;">
                                <i class="fas fa-check text-primary"></i>
                                <strong>Аудит:</strong> Ежегодный независимый аудит финансовой отчётности
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer">
            <div class="container">
                <div class="footer-bottom">
                    <p>© 2024 Благотворительный фонд «Время Человека»</p>
                </div>
            </div>
        </footer>
    </body>
    </html>
  `)
})

// Страница "Контакты"
app.get('/contacts', (c) => {
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Контакты - Время Человека</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <a href="/" class="logo">
                        <i class="fas fa-heart"></i> ВРЕМЯ ЧЕЛОВЕКА
                    </a>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="/">Главная</a></li>
                            <li><a href="/projects">Проекты</a></li>
                            <li><a href="/about">О фонде</a></li>
                            <li><a href="/reports">Отчёты</a></li>
                            <li><a href="/contacts" style="color: var(--golden-brown);">Контакты</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <section class="section" style="padding-top: 3rem;">
            <div class="container">
                <h1 class="text-center text-burgundy mb-2">
                    <i class="fas fa-address-book"></i>
                    Контакты
                </h1>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                    <!-- Контактная информация -->
                    <div class="floating-card">
                        <h2 class="text-burgundy mb-2">Свяжитесь с нами</h2>
                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="font-size: 1.5rem; color: var(--primary-red);">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h4 style="margin-bottom: 0.5rem;">Email</h4>
                                    <a href="mailto:info@vremyacheloveka.ru" style="color: var(--deep-burgundy);">info@vremyacheloveka.ru</a>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="font-size: 1.5rem; color: var(--primary-red);">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h4 style="margin-bottom: 0.5rem;">Телефон</h4>
                                    <a href="tel:+74951234567" style="color: var(--deep-burgundy);">+7 (495) 123-45-67</a>
                                    <p style="font-size: 0.9rem; color: #888; margin-top: 0.25rem;">Пн-Пт: 9:00 - 18:00 (МСК)</p>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="font-size: 1.5rem; color: var(--primary-red);">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4 style="margin-bottom: 0.5rem;">Адрес</h4>
                                    <p style="color: var(--text-dark);">
                                        г. Москва, Россия<br>
                                        (точный адрес сообщается по запросу)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
                            <h4 style="margin-bottom: 1rem; color: var(--deep-burgundy);">Социальные сети</h4>
                            <div style="display: flex; gap: 1rem; font-size: 1.5rem;">
                                <a href="#" style="color: var(--primary-red);"><i class="fab fa-vk"></i></a>
                                <a href="#" style="color: var(--primary-red);"><i class="fab fa-telegram"></i></a>
                                <a href="#" style="color: var(--primary-red);"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Форма обратной связи -->
                    <div class="floating-card">
                        <h2 class="text-burgundy mb-2">Напишите нам</h2>
                        <form style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ваше имя</label>
                                <input type="text" placeholder="Введите ваше имя" style="width: 100%; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px; font-size: 1rem;">
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                                <input type="email" placeholder="your@email.com" style="width: 100%; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px; font-size: 1rem;">
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Тема обращения</label>
                                <select style="width: 100%; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px; font-size: 1rem;">
                                    <option>Общий вопрос</option>
                                    <option>Вопрос о проекте</option>
                                    <option>Партнёрство</option>
                                    <option>Волонтёрство</option>
                                    <option>Другое</option>
                                </select>
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Сообщение</label>
                                <textarea rows="5" placeholder="Введите ваше сообщение..." style="width: 100%; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Отправить сообщение
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Юридическая информация -->
                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">Юридическая информация</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                        <div>
                            <h4 style="color: var(--deep-burgundy); margin-bottom: 1rem;">Реквизиты фонда</h4>
                            <table style="width: 100%; font-size: 0.95rem;">
                                <tr><td style="padding: 0.5rem 0; color: #888;">Наименование:</td><td style="padding: 0.5rem 0;">Благотворительный фонд «Время Человека»</td></tr>
                                <tr><td style="padding: 0.5rem 0; color: #888;">ИНН:</td><td style="padding: 0.5rem 0;">1234567890</td></tr>
                                <tr><td style="padding: 0.5rem 0; color: #888;">КПП:</td><td style="padding: 0.5rem 0;">123456789</td></tr>
                                <tr><td style="padding: 0.5rem 0; color: #888;">ОГРН:</td><td style="padding: 0.5rem 0;">1234567890123</td></tr>
                            </table>
                        </div>
                        <div>
                            <h4 style="color: var(--deep-burgundy); margin-bottom: 1rem;">Банковские реквизиты</h4>
                            <table style="width: 100%; font-size: 0.95rem;">
                                <tr><td style="padding: 0.5rem 0; color: #888;">Банк:</td><td style="padding: 0.5rem 0;">ПАО Сбербанк</td></tr>
                                <tr><td style="padding: 0.5rem 0; color: #888;">Р/С:</td><td style="padding: 0.5rem 0;">40703810000000000000</td></tr>
                                <tr><td style="padding: 0.5rem 0; color: #888;">К/С:</td><td style="padding: 0.5rem 0;">30101810000000000000</td></tr>
                                <tr><td style="padding: 0.5rem 0; color: #888;">БИК:</td><td style="padding: 0.5rem 0;">044525225</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer">
            <div class="container">
                <div class="footer-bottom">
                    <p>© 2024 Благотворительный фонд «Время Человека»</p>
                </div>
            </div>
        </footer>
    </body>
    </html>
  `)
})

// Админ-панель - главная страница
app.get('/admin', (c) => {
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Админ-панель - Время Человека</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
        <style>
          .admin-sidebar {
            background: var(--deep-burgundy);
            color: white;
            padding: 2rem;
            min-height: 100vh;
          }
          .admin-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            transition: background 0.3s;
          }
          .admin-menu a:hover {
            background: rgba(255,255,255,0.1);
          }
          .admin-content {
            padding: 2rem;
            flex: 1;
          }
        </style>
    </head>
    <body>
        <div style="display: flex;">
            <div class="admin-sidebar" style="width: 250px;">
                <h2 style="margin-bottom: 2rem; color: var(--golden-brown);">
                    <i class="fas fa-cog"></i> Админ-панель
                </h2>
                <div class="admin-menu">
                    <a href="/admin"><i class="fas fa-home"></i> Главная</a>
                    <a href="/admin/projects"><i class="fas fa-project-diagram"></i> Проекты</a>
                    <a href="/admin/news"><i class="fas fa-newspaper"></i> Новости</a>
                    <a href="/admin/reports"><i class="fas fa-file-invoice"></i> Отчёты</a>
                    <a href="/admin/partners"><i class="fas fa-handshake"></i> Партнёры</a>
                    <hr style="margin: 1.5rem 0; border-color: rgba(255,255,255,0.2);">
                    <a href="/"><i class="fas fa-globe"></i> Перейти на сайт</a>
                </div>
            </div>
            
            <div class="admin-content" style="background: var(--cream-bg);">
                <h1 class="text-burgundy mb-2">
                    <i class="fas fa-chart-line"></i>
                    Панель управления
                </h1>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                    <div class="floating-card" style="background: linear-gradient(135deg, #C3423F 0%, #6B1F26 100%); color: white;">
                        <h3 style="color: white; margin-bottom: 1rem;">
                            <i class="fas fa-project-diagram"></i> Проекты
                        </h3>
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">3</div>
                        <p>Активных проектов</p>
                        <a href="/admin/projects" class="btn btn-outline" style="margin-top: 1rem; border-color: white; color: white;">Управление</a>
                    </div>
                    
                    <div class="floating-card" style="background: linear-gradient(135deg, #1F4D3A 0%, #0d2418 100%); color: white;">
                        <h3 style="color: white; margin-bottom: 1rem;">
                            <i class="fas fa-newspaper"></i> Новости
                        </h3>
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">2</div>
                        <p>Опубликованных новостей</p>
                        <a href="/admin/news" class="btn btn-outline" style="margin-top: 1rem; border-color: white; color: white;">Управление</a>
                    </div>
                    
                    <div class="floating-card" style="background: linear-gradient(135deg, #B58A4A 0%, #8a6835 100%); color: white;">
                        <h3 style="color: white; margin-bottom: 1rem;">
                            <i class="fas fa-ruble-sign"></i> Собрано
                        </h3>
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">2.56М</div>
                        <p>Рублей всего</p>
                        <a href="/admin/reports" class="btn btn-outline" style="margin-top: 1rem; border-color: white; color: white;">Отчёты</a>
                    </div>
                </div>
                
                <div class="floating-card" style="margin-top: 2rem;">
                    <h2 class="text-burgundy mb-2">
                        <i class="fas fa-info-circle"></i>
                        Быстрый старт
                    </h2>
                    <p style="margin-bottom: 1.5rem;">Добро пожаловать в админ-панель фонда «Время Человека». Здесь вы можете управлять контентом сайта.</p>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div style="padding: 1rem; background: #f0f0f0; border-radius: 4px; border-left: 4px solid var(--primary-red);">
                            <h4 style="margin-bottom: 0.5rem;">1. Управление проектами</h4>
                            <p>Создавайте, редактируйте и архивируйте проекты фонда</p>
                        </div>
                        <div style="padding: 1rem; background: #f0f0f0; border-radius: 4px; border-left: 4px solid var(--dark-green);">
                            <h4 style="margin-bottom: 0.5rem;">2. Публикация новостей</h4>
                            <p>Делитесь новостями и успехами фонда с посетителями сайта</p>
                        </div>
                        <div style="padding: 1rem; background: #f0f0f0; border-radius: 4px; border-left: 4px solid var(--golden-brown);">
                            <h4 style="margin-bottom: 0.5rem;">3. Загрузка отчётов</h4>
                            <p>Публикуйте финансовые и проектные отчёты для прозрачности</p>
                        </div>
                    </div>
                </div>
                
                <div class="floating-card" style="margin-top: 2rem; background: #fff3cd; border-left: 4px solid #ffc107;">
                    <h4 style="margin-bottom: 1rem;"><i class="fas fa-exclamation-triangle"></i> Важно</h4>
                    <p><strong>Аутентификация:</strong> В текущей версии админ-панель открыта для демонстрации. Перед production-запуском необходимо добавить систему аутентификации.</p>
                    <p style="margin-top: 0.5rem;"><strong>Рекомендация:</strong> Используйте Cloudflare Access или JWT-токены для защиты админ-панели.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
  `)
})

// Админ: Управление проектами
app.get('/admin/projects', async (c) => {
  const { results: projects } = await c.env.DB.prepare(
    'SELECT * FROM projects ORDER BY created_at DESC'
  ).all()
  
  return c.html(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Управление проектами - Админ-панель</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
        <link href="/static/styles.css" rel="stylesheet">
        <style>
          .admin-sidebar {
            background: var(--deep-burgundy);
            color: white;
            padding: 2rem;
            min-height: 100vh;
          }
          .admin-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            transition: background 0.3s;
          }
          .admin-menu a:hover, .admin-menu a.active {
            background: rgba(255,255,255,0.1);
          }
          .admin-content {
            padding: 2rem;
            flex: 1;
          }
          .admin-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          }
          .admin-table th {
            background: var(--deep-burgundy);
            color: white;
            padding: 1rem;
            text-align: left;
          }
          .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
          }
          .admin-table tr:last-child td {
            border-bottom: none;
          }
          .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
          }
          .status-active { background: #d4edda; color: #155724; }
          .status-completed { background: #d1ecf1; color: #0c5460; }
          .status-archived { background: #f8d7da; color: #721c24; }
        </style>
    </head>
    <body>
        <div style="display: flex;">
            <div class="admin-sidebar" style="width: 250px;">
                <h2 style="margin-bottom: 2rem; color: var(--golden-brown);">
                    <i class="fas fa-cog"></i> Админ-панель
                </h2>
                <div class="admin-menu">
                    <a href="/admin"><i class="fas fa-home"></i> Главная</a>
                    <a href="/admin/projects" class="active"><i class="fas fa-project-diagram"></i> Проекты</a>
                    <a href="/admin/news"><i class="fas fa-newspaper"></i> Новости</a>
                    <a href="/admin/reports"><i class="fas fa-file-invoice"></i> Отчёты</a>
                    <a href="/admin/partners"><i class="fas fa-handshake"></i> Партнёры</a>
                    <hr style="margin: 1.5rem 0; border-color: rgba(255,255,255,0.2);">
                    <a href="/"><i class="fas fa-globe"></i> Перейти на сайт</a>
                </div>
            </div>
            
            <div class="admin-content" style="background: var(--cream-bg);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h1 class="text-burgundy">
                        <i class="fas fa-project-diagram"></i>
                        Управление проектами
                    </h1>
                    <button class="btn btn-primary" onclick="alert('Функция создания проекта будет добавлена')">
                        <i class="fas fa-plus"></i> Добавить проект
                    </button>
                </div>
                
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Статус</th>
                            <th>Собрано / Цель</th>
                            <th>Получателей</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${projects.map((project: any) => `
                            <tr>
                                <td>${project.id}</td>
                                <td><strong>${project.title}</strong></td>
                                <td>${getCategoryName(project.category)}</td>
                                <td><span class="status-badge status-${project.status}">${project.status}</span></td>
                                <td>${formatAmount(project.collected_amount || 0)} / ${formatAmount(project.target_amount || 0)} ₽</td>
                                <td>${(project.beneficiaries_count || 0).toLocaleString('ru-RU')}</td>
                                <td>
                                    <button class="btn btn-outline" style="padding: 0.5rem 0.75rem; font-size: 0.85rem; margin-right: 0.5rem;" onclick="alert('Редактирование проекта ${project.id}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;" onclick="window.open('/projects/${project.slug}', '_blank')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                
                <div class="floating-card" style="margin-top: 2rem; background: #e7f3ff; border-left: 4px solid #2196F3;">
                    <h4 style="margin-bottom: 1rem;"><i class="fas fa-info-circle"></i> Подсказка</h4>
                    <p>Для полноценного управления проектами (создание, редактирование, удаление) требуется реализация API-endpoints с методами POST/PUT/DELETE.</p>
                    <p style="margin-top: 0.5rem;">Текущая версия предоставляет просмотр существующих проектов из базы данных.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
  `)
})

// Админ API: Создание проекта (пример)
app.post('/api/admin/projects', async (c) => {
  try {
    const body = await c.req.json()
    const { title, slug, short_description, full_description, category, target_amount, image_url } = body
    
    const result = await c.env.DB.prepare(`
      INSERT INTO projects (title, slug, short_description, full_description, category, status, target_amount, image_url)
      VALUES (?, ?, ?, ?, ?, 'active', ?, ?)
    `).bind(title, slug, short_description, full_description, category, target_amount, image_url).run()
    
    return c.json({ success: true, id: result.meta.last_row_id })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to create project' }, 500)
  }
})

// Админ API: Обновление проекта
app.put('/api/admin/projects/:id', async (c) => {
  try {
    const id = c.req.param('id')
    const body = await c.req.json()
    const { title, short_description, full_description, category, status, target_amount, collected_amount, beneficiaries_count, image_url } = body
    
    await c.env.DB.prepare(`
      UPDATE projects 
      SET title = ?, short_description = ?, full_description = ?, category = ?, status = ?, 
          target_amount = ?, collected_amount = ?, beneficiaries_count = ?, image_url = ?, updated_at = CURRENT_TIMESTAMP
      WHERE id = ?
    `).bind(title, short_description, full_description, category, status, target_amount, collected_amount, beneficiaries_count, image_url, id).run()
    
    return c.json({ success: true })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to update project' }, 500)
  }
})

export default app
