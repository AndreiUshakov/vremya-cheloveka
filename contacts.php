<?php
/**
 * Страница "Контакты"
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Начинаем буферизацию контента
startContent();
?>

<!-- Основная секция "Контакты" -->
<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <h1 class="glass-section-title glass-animate">
            Контакты
        </h1>
        
        <!-- Контактная информация -->
        <div class="glass-card-glow glass-animate" style="max-width: 1200px; margin: 0 auto 3rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <!-- Email -->
                <div style="text-align: center; padding: 1.5rem;">
                    <div class="glass-icon" style="margin: 0 auto 1rem; background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05)); width: 80px; height: 80px;">
                        <i class="fas fa-envelope" style="font-size: 2rem;color: var(--glass-text-primary);"></i>
                    </div>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: 600;">Email</h3>
                    <a href="mailto:<?= e(CONTACT_EMAIL) ?>" style="color: var(--glass-gold); text-decoration: none; font-size: 1.1rem; transition: opacity 0.3s;">
                        <?= e(CONTACT_EMAIL) ?>
                    </a>
                </div>
                
                <!-- Телефон -->
                <div style="text-align: center; padding: 1.5rem;">
                    <div class="glass-icon" style="margin: 0 auto 1rem; background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05)); width: 80px; height: 80px;">
                        <i class="fas fa-phone" style="font-size: 2rem; color: var(--glass-text-primary);"></i>
                    </div>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: 600;">Телефон</h3>
                    <a href="tel:+79149169559" style="color: var(--glass-gold); text-decoration: none; font-size: 1.1rem; transition: opacity 0.3s;">
                        <?= e(CONTACT_PHONE) ?>
                    </a>
                </div>
                
                <!-- Адрес -->
                <div style="text-align: center; padding: 1.5rem;">
                    <div class="glass-icon" style="margin: 0 auto 1rem; background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05)); width: 80px; height: 80px;">
                        <i class="fas fa-map-marker-alt" style="font-size: 2rem; color: var(--glass-text-primary);"></i>
                    </div>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: 600;">Адрес</h3>
                    <p style="color: var(--glass-text-secondary); font-size: 1.1rem; margin: 0;">
                        <?= e(CONTACT_ADDRESS) ?>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Карта Яндекс.Карты -->
        <!-- <div class="glass-card-glow glass-animate" style="max-width: 1200px; margin: 0 auto 3rem;">
            <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                Как нас найти
            </h2>
            <div style="position: relative; overflow: hidden; border-radius: 16px; height: 450px; background: rgba(10, 14, 39, 0.3);">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
            <p style="color: var(--glass-text-secondary); margin-top: 1rem; font-size: 0.95rem;">
                <i class="fas fa-info-circle" style="color: var(--glass-gold);"></i>
                Интерактивная карта будет доступна после настройки API-ключа Яндекс.Карт
            </p>
        </div> -->
        
        <!-- Реквизиты -->
        <div class="glass-card-glow glass-animate" style="max-width: 1200px; margin: 0 auto;">
            <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                Реквизиты
            </h2>
            
            <div style="display: grid; gap: 1.5rem;">
                <!-- Основная информация -->
                <!--     <div style="padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 12px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.35rem; margin-bottom: 1.25rem; font-weight: 600;">
                        Основная информация
                    </h3>
                    <div style="display: grid; gap: 1rem;">
                       
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Наименование:</span>
                            <span style="color: var(--glass-text-primary);">ФОНД "ВРЕМЯ ЧЕЛОВЕКА"</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">ИНН:</span>
                            <span style="color: var(--glass-text-primary);">3808295287</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">КПП:</span>
                            <span style="color: var(--glass-text-primary);">380801001</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">ОГРН:</span>
                            <span style="color: var(--glass-text-primary);">1253800019934</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Юридический адрес:</span>
                            <span style="color: var(--glass-text-primary);">664007, г. Иркутск, ул. Горького, д. 27</span>
                        </div>
                    </div>
                </div> -->
                
                <!-- Банковские реквизиты -->
                <div style="padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 12px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.35rem; margin-bottom: 1.25rem; font-weight: 600;">
                        Банковские реквизиты
                    </h3>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Наименование:</span>
                            <span style="color: var(--glass-text-primary);">ФОНД "ВРЕМЯ ЧЕЛОВЕКА"</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">ИНН:</span>
                            <span style="color: var(--glass-text-primary);">3808295287</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">КПП:</span>
                            <span style="color: var(--glass-text-primary);">380801001</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">ОГРН:</span>
                            <span style="color: var(--glass-text-primary);">1253800019934</span>
                        </div>                       
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Банк:</span>
                            <span style="color: var(--glass-text-primary);">БАЙКАЛЬСКИЙ БАНК ПАО СБЕРБАНК</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">БИК:</span>
                            <span style="color: var(--glass-text-primary);">042520607</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Корр. счёт:</span>
                            <span style="color: var(--glass-text-primary);">30101810900000000607</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Расчётный счёт:</span>
                            <span style="color: var(--glass-text-primary);">40703810618750000199</span>
                        </div>
                    </div>
                </div>
                
                <!-- Руководство -->
                <!-- <div style="padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 12px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.35rem; margin-bottom: 1.25rem; font-weight: 600;">
                        Руководство
                    </h3>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Директор:</span>
                            <span style="color: var(--glass-text-primary);">Иванов Иван Иванович</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start;">
                            <span style="color: var(--glass-text-secondary); font-weight: 500;">Основание:</span>
                            <span style="color: var(--glass-text-primary);">Действует на основании Устава</span>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <div style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;margin-top:2rem;">
                <!-- Логотип слева -->
                <div style="flex: 0 0 auto;">
                    <img src="/static/img/qr.jpg" alt="QR code фонда Время Человека" style="max-width: 350px; width: 100%; height: auto; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">
                </div>                
                <!-- Текст и кнопка справа -->
                <div style="flex: 1 1 400px;">                    
                    <p style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--glass-text-secondary); line-height: 1.8;">
                        Воспользуйтесь специальным qr-кодом для превода в приложении любого банка, например Сбербанк Онлайн. В разделе "Платежи" нажмите кнопку "Сканировать QR или телефон" и наведите камеру на изображение слева. Этот способ ускоряет заполнение реквизитов фонда. Вы можете также сделать перевод по реквизитам, указанным на странице "Контакты"
                    </p>                    
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Призыв к действию -->
<section class="glass-section">
    <div class="container">
        <div class="glass-card-glow glass-animate" style="max-width: 900px; margin: 0 auto; text-align: center;">
            <div class="glass-icon" style="margin: 0 auto 1.5rem;">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h2 style="color: var(--glass-text-primary); margin-bottom: 1rem; font-size: 2rem; font-weight: 600;">Готовы сделать доброе дело?</h2>
            <p style="color: var(--glass-text-secondary); margin-bottom: 2rem; font-size: 1.15rem; line-height: 1.8; max-width: 700px; margin-left: auto; margin-right: auto;">
                Свяжитесь с нами для получения дополнительной информации о наших проектах или чтобы обсудить возможности сотрудничества.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="glass-btn glass-btn-primary glass-btn-large">
                    <i class="fas fa-envelope"></i>
                    Написать нам
                </a>
                <a href="/projects" class="glass-btn glass-btn-outline glass-btn-large">
                    <i class="fas fa-folder-open"></i>
                    Наши проекты
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Скрипт Яндекс.Карт -->
<!-- <script src="https://api-maps.yandex.ru/2.1/?apikey=&lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
    // Инициализация карты после загрузки API
    ymaps.ready(init);
    
    function init() {
        // Создаем карту с центром в Иркутске (координаты будут заменены на реальные)
        var myMap = new ymaps.Map("map", {
            center: [52.286388, 104.280606], // Координаты Иркутска, ул. Горького, 27
            zoom: 16,
            controls: ['zoomControl', 'fullscreenControl', 'geolocationControl']
        });
        
        // Добавляем метку с адресом фонда
        var myPlacemark = new ymaps.Placemark([52.286388, 104.280606], {
            balloonContentHeader: 'БФ «Время Человека»',
            balloonContentBody: 'г. Иркутск, ул. Горького, д. 27',
            balloonContentFooter: 'Телефон: <?= e(CONTACT_PHONE) ?>',
            hintContent: 'БФ «Время Человека»'
        }, {
            preset: 'islands#goldIcon',
            iconColor: '#d4af37'
        });
        
        myMap.geoObjects.add(myPlacemark);
        
        // Отключаем скролл карты колесом мыши
        myMap.behaviors.disable('scrollZoom');
    }
</script> -->

<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => 'Контакты - Время Человека',
    'description' => 'Контактная информация Благотворительного фонда «Время Человека». Адрес, телефон, email, реквизиты для связи и пожертвований.'
]);
?>