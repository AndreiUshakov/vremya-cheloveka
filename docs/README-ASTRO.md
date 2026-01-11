# Время Человека - Статическая версия на Astro

> Миграция проекта с Cloudflare Workers/D1 на статический сайт с Astro + Decap CMS

## 🎯 Что изменилось

### До миграции (Cloudflare Stack)
- **Runtime**: Cloudflare Workers
- **Framework**: Hono (TypeScript)
- **Database**: Cloudflare D1 (SQLite)
- **Деплой**: `wrangler pages deploy`
- **Админка**: Кастомная на Hono

### После миграции (Static Stack)
- **Generator**: Astro (Static Site)
- **Content**: Markdown + JSON файлы
- **CMS**: Decap CMS (визуальный редактор)
- **Деплой**: Netlify / Vercel / GitHub Pages
- **Админка**: Decap CMS на `/admin`

## 📦 Структура проекта

```
vremya-cheloveka/
├── src/
│   ├── content/              # Контент сайта
│   │   ├── config.ts        # Конфигурация коллекций
│   │   ├── projects/        # Проекты (.md)
│   │   ├── news/            # Новости (.md)
│   │   ├── reports/         # Отчёты (.md)
│   │   └── partners/        # Партнёры (.json)
│   ├── layouts/
│   │   └── BaseLayout.astro # Базовый layout
│   ├── pages/               # Страницы сайта
│   │   ├── index.astro      # Главная
│   │   ├── projects/        # Проекты
│   │   └── [другие].astro
│   └── styles/
│       └── styles.css       # Стили (sovietwave)
├── public/
│   ├── admin/               # Decap CMS
│   │   ├── config.yml       # Конфигурация CMS
│   │   └── index.html       # Вход в админку
│   ├── static/              # Статические файлы
│   │   └── hero-video.mp4
│   └── images/              # Изображения
├── astro.config.mjs         # Конфигурация Astro
├── package-astro.json       # Зависимости для Astro
├── netlify.toml             # Конфигурация Netlify
└── README-ASTRO.md          # Эта документация
```

## 🚀 Быстрый старт

### 1. Установка зависимостей

```bash
# Переименовываем package.json
mv package.json package-old.json
mv package-astro.json package.json

# Аналогично с tsconfig
mv tsconfig.json tsconfig-old.json
mv tsconfig-astro.json tsconfig.json

# Устанавливаем зависимости
npm install
```

### 2. Локальная разработка

```bash
# Запуск dev-сервера
npm run dev

# Откройте http://localhost:4321
```

### 3. Сборка проекта

```bash
# Проверка и сборка
npm run build

# Предпросмотр собранного сайта
npm run preview
```

## 📝 Управление контентом

### Вариант 1: Через Decap CMS (Визуальная админка)

1. **Локально**:
   - Запустите `npm run dev`
   - Откройте `http://localhost:4321/admin`
   - Используйте тестовый режим (без аутентификации)

2. **На production**:
   - Перейдите на `https://ваш-сайт.netlify.app/admin`
   - Войдите через Netlify Identity
   - Редактируйте контент в визуальном редакторе

### Вариант 2: Через Git (Markdown/JSON файлы)

```bash
# Создать новый проект
cat > src/content/projects/new-project.md << 'EOF'
---
title: "Название проекта"
slug: "nazvanie-proekta"
shortDescription: "Краткое описание"
category: "social"
status: "active"
targetAmount: 1000000
collectedAmount: 0
beneficiariesCount: 0
regions: ["Москва"]
imageUrl: "/images/project.jpg"
publishedAt: 2024-12-01
---

## Полное описание проекта

Здесь размещается детальное описание...
EOF

# Закоммитить
git add src/content/projects/new-project.md
git commit -m "Добавлен новый проект"
git push

# Автоматически пересобирается и деплоится
```

## 🌐 Деплой

### На Netlify (Рекомендуется)

1. **Через Git интеграцию**:
   ```bash
   # Push в GitHub
   git add .
   git commit -m "Миграция на Astro"
   git push origin main
   ```

2. **В Netlify Dashboard**:
   - New site from Git → выбрать репозиторий
   - Build command: `npm run build`
   - Publish directory: `dist`
   - Deploy!

3. **Настройка Netlify Identity** (для CMS):
   - Site settings → Identity → Enable Identity
   - Registration → Invite only
   - External providers → GitHub (опционально)
   - Services → Git Gateway → Enable

4. **Пригласить админа**:
   - Identity → Invite users → ваш email
   - Получите письмо → подтвердите
   - Теперь можно войти в `/admin`

### На Vercel

```bash
# Установка Vercel CLI
npm i -g vercel

# Деплой
vercel

# Production деплой
vercel --prod
```

### На GitHub Pages

```yaml
# .github/workflows/deploy.yml
name: Deploy to GitHub Pages

on:
  push:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
        with:
          node-version: 18
      - run: npm install
      - run: npm run build
      - uses: peaceiris/actions-gh-pages@v3
        with:
          github_token: ${{ secrets.GITHUB_TOKEN }}
          publish_dir: ./dist
```

## 📊 Преимущества миграции

### Скорость
- ⚡ **До**: ~500ms (серверный рендеринг)
- ⚡ **После**: ~50ms (статика из CDN)
- 📦 **Размер**: Меньше на 80% (нет runtime)

### Стоимость
- 💰 **До**: $5-10/мес (Cloudflare Workers + D1)
- 💰 **После**: $0 (Netlify/Vercel free tier)

### Простота
- ✅ Markdown вместо SQL
- ✅ Git вместо API
- ✅ Визуальный CMS из коробки
- ✅ Деплой за 2 минуты

### Надёжность
- 🛡️ Статика не падает
- 🌍 CDN по всему миру
- 🔒 Нет уязвимостей в базе данных

## 🎨 Стили и дизайн

Все стили sovietwave сохранены:
- ✅ Видео Hero-секция
- ✅ Светлая цветовая палитра
- ✅ Карточки проектов
- ✅ Прогресс-бары
- ✅ Адаптивный дизайн

Файл стилей: `src/styles/styles.css`

## 🔧 Настройка Decap CMS

### Локальная разработка
```bash
# В package.json добавить прокси
npm install -D decap-server

# В другом терминале
npx decap-server
```

### Кастомизация полей
Редактируйте `public/admin/config.yml` для изменения структуры контента.

### Загрузка изображений
- Изображения сохраняются в `public/images/uploads/`
- Автоматически коммитятся в Git
- Доступны по пути `/images/uploads/filename.jpg`

## 📚 Добавление новых страниц

### Создать статическую страницу

```astro
---
// src/pages/about.astro
import BaseLayout from '../layouts/BaseLayout.astro';
---

<BaseLayout title="О фонде">
    <section class="section">
        <div class="container">
            <h1>О фонде</h1>
            <p>Контент страницы...</p>
        </div>
    </section>
</BaseLayout>
```

### Создать динамические страницы

```astro
---
// src/pages/news/[slug].astro
import { getCollection } from 'astro:content';

export async function getStaticPaths() {
  const news = await getCollection('news');
  return news.map(item => ({
    params: { slug: item.data.slug },
    props: { item }
  }));
}
---
```

## 🔄 Миграция данных

Данные уже мигрированы из `seed.sql` в markdown:
- ✅ 3 проекта
- ✅ 2 новости
- ✅ 2 отчёта
- ✅ 2 партнёра

Для добавления данных:
1. Используйте `/admin` на production
2. Или создайте `.md` файлы вручную в `src/content/`

## 🎯 Roadmap

- [x] Миграция на Astro
- [x] Настройка Decap CMS
- [x] Конфигурация деплоя
- [ ] Добавить страницы: About, Reports, Contacts
- [ ] Интеграция Робокассы (виджет)
- [ ] SEO оптимизация (meta tags, sitemap)
- [ ] Добавить RSS feed
- [ ] PWA манифест

## 📞 Поддержка

При возникновении вопросов:
1. Проверьте документацию Astro: https://docs.astro.build
2. Документация Decap CMS: https://decapcms.org/docs
3. Netlify Docs: https://docs.netlify.com

## 📄 Лицензия

© 2024 Благотворительный фонд «Время Человека»  
АНО «Институт развития общества»