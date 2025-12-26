# 🎥 Исправление проблемы с видео (404 Error)

## Проблема
Видео по адресу `/hero-video.mp4` возвращало **404 Not Found**.

## Причина
Cloudflare Pages/Wrangler не отдаёт статические файлы из корня `public/`, только из директории, настроенной через `serveStatic`.

## Решение

### 1. Переместили видео в `/static/` директорию:
```bash
mv public/hero-video.mp4 public/static/hero-video.mp4
```

### 2. Обновили путь в HTML (src/index.tsx):
```tsx
// Было:
<source src="/hero-video.mp4" type="video/mp4">

// Стало:
<source src="/static/hero-video.mp4" type="video/mp4">
```

### 3. Пересобрали проект:
```bash
npm run build
pm2 restart human-time
```

## ✅ Результат
- Видео доступно по адресу: `/static/hero-video.mp4`
- HTTP статус: **200 OK**
- Content-Type: `video/mp4`
- Размер: 650KB

## 🌐 Проверка
```bash
# Локально:
curl -I http://localhost:3000/static/hero-video.mp4

# На сайте:
curl -I https://3000-imrdnvzfquwbfbu9a2774-5634da27.sandbox.novita.ai/static/hero-video.mp4
```

Оба запроса должны вернуть **HTTP 200 OK**.

---
**Дата исправления**: 26.12.2025  
**Статус**: ✅ Решено
