# Glassmorphism Design - Краткое руководство

## Быстрый старт

Новый glassmorphism дизайн уже активирован на сайте! Все изменения применены автоматически.

## Что было сделано

### ✅ Созданные файлы

1. **`static/glass-theme.css`** - основной файл стилей с glassmorphism компонентами
2. **`static/styles-old.css`** - резервная копия старых стилей
3. **`docs/GLASSMORPHISM_DESIGN.md`** - полная документация по дизайну
4. **`docs/GLASSMORPHISM_QUICKSTART.md`** - это руководство

### ✅ Модифицированные файлы

1. **`includes/layout.php`**
   - Подключен новый файл стилей
   - Добавлена нижняя навигация с glassmorphism эффектом

2. **`index.php`**
   - Все секции обновлены с использованием стеклянных компонентов
   - Добавлены анимации появления
   - Hero секция с видео сохранена без изменений

## Основные компоненты

### 1. Стеклянные карточки

```html
<!-- Базовая стеклянная карточка -->
<div class="glass-card">
  <h3>Заголовок</h3>
  <p>Содержимое карточки</p>
</div>

<!-- Карточка с усиленным эффектом -->
<div class="glass-card-glow">
  <h3>Заголовок</h3>
  <p>Содержимое</p>
</div>

<!-- Карточка программы/проекта -->
<div class="glass-program-card">
  <div class="glass-icon">
    <i class="fas fa-heart"></i>
  </div>
  <h3>Название программы</h3>
  <p>Описание</p>
</div>
```

### 2. Кнопки

```html
<!-- Основная золотая кнопка -->
<a href="#" class="glass-btn glass-btn-primary">
  <i class="fas fa-heart"></i>
  Поддержать
</a>

<!-- Контурная кнопка -->
<a href="#" class="glass-btn glass-btn-outline">
  <i class="fas fa-info"></i>
  Подробнее
</a>

<!-- Большая кнопка -->
<a href="#" class="glass-btn glass-btn-primary glass-btn-large">
  Большая кнопка
</a>
```

### 3. Секции

```html
<section class="glass-section">
  <div class="container">
    <h2 class="glass-section-title">
      <i class="fas fa-star"></i>
      Заголовок секции
    </h2>
    
    <div class="glass-grid">
      <!-- Карточки здесь -->
    </div>
  </div>
</section>
```

### 4. Сетки

```html
<!-- Стандартная сетка (300px колонки) -->
<div class="glass-grid">
  <div class="glass-card">...</div>
  <div class="glass-card">...</div>
  <div class="glass-card">...</div>
</div>

<!-- Сетка для 3 колонок (280px) -->
<div class="glass-grid glass-grid-3">
  <!-- ... -->
</div>

<!-- Сетка для 4 колонок (220px) -->
<div class="glass-grid glass-grid-4">
  <!-- ... -->
</div>
```

### 5. Анимации

```html
<!-- Базовая анимация появления -->
<div class="glass-card glass-animate">
  <!-- ... -->
</div>

<!-- С задержкой -->
<div class="glass-card glass-animate-delay-1">...</div>
<div class="glass-card glass-animate-delay-2">...</div>
<div class="glass-card glass-animate-delay-3">...</div>
```

## Цвета и стилизация

### Текст

```html
<p class="glass-text-primary">Основной текст (белый 90%)</p>
<p class="glass-text-secondary">Вторичный текст (белый 70%)</p>
<p class="glass-text-gold">Золотой текст</p>
```

### Утилиты

```html
<div class="glass-text-center">Центрированный текст</div>
<div class="glass-mb-2">Отступ снизу 2rem</div>
<div class="glass-mt-2">Отступ сверху 2rem</div>
```

## Нижняя навигация

Нижняя навигация добавлена автоматически в `layout.php` и отображается на всех страницах. Активный пункт подсвечивается автоматически на основе текущего URL.

Для изменения пунктов меню отредактируйте файл [`includes/layout.php`](../includes/layout.php) в разделе `<!-- Нижняя стеклянная навигация -->`.

## Адаптивность

Дизайн полностью адаптивен:

- **Десктоп (>1024px)**: полноразмерные элементы
- **Планшеты (768px-1024px)**: уменьшенные отступы
- **Мобильные (<768px)**: одноколоночный layout, вертикальная навигация
- **Малые экраны (<480px)**: компактная навигация

## Браузерная совместимость

### Полная поддержка
- ✅ Chrome 76+
- ✅ Edge 79+
- ✅ Safari 9+ (с префиксом -webkit-)
- ✅ Firefox 103+

### Частичная поддержка
Старые браузеры без поддержки `backdrop-filter` будут показывать более непрозрачный фон.

## Возврат к старому дизайну

Если нужно вернуться к старому дизайну:

1. Откройте [`includes/layout.php`](../includes/layout.php)
2. Закомментируйте строку:
   ```php
   <link rel="stylesheet" href="/static/glass-theme.css">
   ```
3. Удалите или закомментируйте блок `<!-- Нижняя стеклянная навигация -->`

Старые стили сохранены в [`static/styles-old.css`](../static/styles-old.css).

## Настройка цветов

Все цвета определены через CSS переменные в начале файла [`static/glass-theme.css`](../static/glass-theme.css):

```css
:root {
  --glass-dark-bg-1: #0a0e27;
  --glass-gold: #fde9a9;/*   #d4af37; */;
  /* и т.д. */
}
```

Измените значения переменных для быстрой настройки цветовой схемы.

## Производительность

### Оптимизации уже применены:
- ✅ GPU ускорение через transform
- ✅ Фиксированный фон предотвращает перерисовку
- ✅ Эффективное использование backdrop-filter
- ✅ Минимизированные анимации (60fps)

### Рекомендации:
- Не добавляйте backdrop-filter на большое количество элементов одновременно
- Используйте анимации с задержкой для каскадного эффекта
- Для больших изображений используйте оптимизированные форматы (WebP)

## Accessibility

### Уже реализовано:
- ✅ Контрастность текста (WCAG AA 4.5:1)
- ✅ Keyboard navigation
- ✅ Hover states на всех интерактивных элементах

### Для улучшения:
- [ ] Добавить ARIA метки для навигации
- [ ] Проверить screen reader compatibility
- [ ] Добавить skip links

## Дальнейшее развитие

### Готовые к добавлению компоненты:

**Модальные окна:**
```html
<div class="glass-card-glow" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
  <h3>Модальное окно</h3>
  <p>Содержимое</p>
</div>
```

**Dropdown меню:**
```html
<div class="glass-card" style="position: absolute; min-width: 200px;">
  <a href="#" class="glass-nav-item">Пункт 1</a>
  <a href="#" class="glass-nav-item">Пункт 2</a>
</div>
```

## Поддержка

Для подробной документации см. [`docs/GLASSMORPHISM_DESIGN.md`](GLASSMORPHISM_DESIGN.md)

При возникновении проблем:
1. Проверьте консоль браузера на наличие ошибок
2. Убедитесь, что файл [`static/glass-theme.css`](../static/glass-theme.css) загружается
3. Проверьте, поддерживает ли ваш браузер `backdrop-filter`

## Changelog

### v1.0.0 (2026-01-12)
- ✅ Первый релиз glassmorphism дизайна
- ✅ Темный фон с градиентами (синий + золотой)
- ✅ Стеклянные компоненты с backdrop-filter
- ✅ Нижняя навигация
- ✅ Золотые акценты и анимации
- ✅ Полная адаптивность
- ✅ Сохранена Hero секция с видео

---

**Автор:** Kilo Code  
**Дата:** 12 января 2026  
**Версия:** 1.0.0