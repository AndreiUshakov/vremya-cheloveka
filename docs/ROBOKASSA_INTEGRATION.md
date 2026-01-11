# Интеграция Робокассы

## Текущее состояние

В проекте подготовлены **заглушки** для виджетов Робокассы на следующих страницах:
- Главная страница (`/`) - секция "Поддержать фонд"
- Страница детального просмотра проекта (`/projects/:slug`) - внизу страницы
- Все остальные формы пожертвований

## Расположение заглушек

Все заглушки имеют класс `.donation-widget` и содержат `div.widget-placeholder`.

### Главная страница
```html
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
                <!-- Robokassa Widget Integration -->
                <!-- Виджет будет добавлен при настройке платёжной системы -->
            </div>
        </div>
    </div>
</section>
```

### Страница проекта
```html
<div id="donate-widget" style="margin-top: 2rem;">
    <div class="donation-widget">
        <h3>Поддержать проект "${project.title}"</h3>
        <p>Здесь будет интегрирован виджет Робокассы для пожертвований</p>
        <div class="widget-placeholder">
            <!-- Robokassa Widget для проекта ${project.slug} -->
            <!-- MerchantLogin=fund_project_${project.id} -->
        </div>
    </div>
</div>
```

## Шаги интеграции

### 1. Получение данных от Робокассы

Необходимо получить у Робокассы:
- **MerchantLogin** - логин магазина
- **Password #1** - пароль для формирования подписи
- **Password #2** - пароль для приёма результатов оплаты
- **Тестовый режим** - для разработки

### 2. Настройка виджета

#### Вариант A: Встроенный виджет (рекомендуется)

Замените содержимое `.widget-placeholder` на скрипт Робокассы:

```html
<div class="widget-placeholder">
    <script src="https://auth.robokassa.ru/Merchant/bundle/robokassa_iframe.js"></script>
    <iframe 
        src="https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=ВАШ_ЛОГИН&OutSum=1000&InvId=0&Description=Пожертвование&Culture=ru"
        width="100%" 
        height="600" 
        frameborder="0"
        style="border-radius: 8px;">
    </iframe>
</div>
```

#### Вариант B: Кнопка с формой

```html
<div class="widget-placeholder">
    <form action="https://auth.robokassa.ru/Merchant/Index.aspx" method="POST">
        <input type="hidden" name="MerchantLogin" value="ВАШ_ЛОГИН">
        <input type="hidden" name="Culture" value="ru">
        
        <div style="margin-bottom: 1rem;">
            <label>Сумма пожертвования:</label>
            <select name="OutSum" style="width: 100%; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px;">
                <option value="500">500 ₽</option>
                <option value="1000">1 000 ₽</option>
                <option value="3000">3 000 ₽</option>
                <option value="5000">5 000 ₽</option>
                <option value="10000">10 000 ₽</option>
            </select>
        </div>
        
        <input type="hidden" name="Description" value="Пожертвование в фонд">
        <input type="hidden" name="SignatureValue" value="СГЕНЕРИРОВАННАЯ_ПОДПИСЬ">
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-credit-card"></i> Пожертвовать
        </button>
    </form>
</div>
```

### 3. Генерация подписи (SignatureValue)

Подпись генерируется по формуле:
```
MD5(MerchantLogin:OutSum:InvId:Password#1)
```

Для этого нужно создать серверный endpoint:

#### Добавить в `src/index.tsx`:

```typescript
// Генерация подписи для Робокассы
app.post('/api/robokassa/signature', async (c) => {
  try {
    const { merchantLogin, outSum, invId, description } = await c.req.json()
    const password1 = 'ВАШ_PASSWORD_1' // Хранить в переменных окружения!
    
    // Формула подписи
    const signatureString = `${merchantLogin}:${outSum}:${invId}:${password1}`
    
    // MD5 хэш (нужно добавить библиотеку crypto-js или использовать Web Crypto API)
    const signature = await crypto.subtle.digest(
      'MD5',
      new TextEncoder().encode(signatureString)
    )
    
    return c.json({
      success: true,
      signature: Array.from(new Uint8Array(signature))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('')
    })
  } catch (error) {
    return c.json({ success: false, error: 'Failed to generate signature' }, 500)
  }
})
```

### 4. Настройка Result URL (обратная связь)

Робокасса отправляет уведомления о платежах на Result URL. Нужно создать endpoint:

```typescript
// Обработка уведомлений от Робокассы
app.post('/api/robokassa/result', async (c) => {
  try {
    const body = await c.req.json()
    const { OutSum, InvId, SignatureValue, custom_project_id } = body
    const password2 = 'ВАШ_PASSWORD_2' // Хранить в переменных окружения!
    
    // Проверка подписи
    const expectedSignature = md5(`${OutSum}:${InvId}:${password2}`)
    
    if (SignatureValue.toLowerCase() !== expectedSignature.toLowerCase()) {
      return c.text('Invalid signature', 400)
    }
    
    // Обновить сумму в БД
    if (custom_project_id) {
      await c.env.DB.prepare(`
        UPDATE projects 
        SET collected_amount = collected_amount + ?
        WHERE id = ?
      `).bind(parseFloat(OutSum), custom_project_id).run()
    }
    
    return c.text(`OK${InvId}`)
  } catch (error) {
    return c.text('Error', 500)
  }
})
```

### 5. Конфигурация для разных проектов

#### Общий фонд
```javascript
MerchantLogin = "fund_general"
Description = "Пожертвование в общий фонд"
custom_field = ""
```

#### Конкретный проект
```javascript
MerchantLogin = "fund_project_{id}"
Description = "Пожертвование на проект: {название}"
custom_project_id = "{id проекта}"
```

### 6. Переменные окружения

Создайте файл `.dev.vars` для локальной разработки:
```
ROBOKASSA_MERCHANT_LOGIN=ваш_логин
ROBOKASSA_PASSWORD_1=ваш_пароль_1
ROBOKASSA_PASSWORD_2=ваш_пароль_2
ROBOKASSA_TEST_MODE=true
```

Для production используйте:
```bash
npx wrangler secret put ROBOKASSA_MERCHANT_LOGIN
npx wrangler secret put ROBOKASSA_PASSWORD_1
npx wrangler secret put ROBOKASSA_PASSWORD_2
```

## Тестирование

Робокасса предоставляет тестовый режим:
- Используйте логин и пароли из тестового аккаунта
- URL для тестов: `https://auth.robokassa.ru/Merchant/Index.aspx?IsTest=1`

## Безопасность

⚠️ **Важно**:
- Никогда не храните пароли в коде
- Используйте переменные окружения (Cloudflare Secrets)
- Всегда проверяйте подпись в Result URL
- Логируйте все транзакции для аудита

## Документация Робокассы

- Официальная документация: https://docs.robokassa.ru/
- Техническая интеграция: https://docs.robokassa.ru/merchant-interface/
- Генерация подписи: https://docs.robokassa.ru/receipt/#_3

## Контакты для поддержки

Если возникнут вопросы по интеграции:
- Email: merchant@robokassa.ru
- Телефон: +7 (800) 500-44-55
- Telegram: @robokassa_support
