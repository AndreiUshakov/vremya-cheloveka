<?php
/**
 * Страница "Документы"
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Начинаем буферизацию контента
startContent();
?>

<!-- Секция с документами -->
<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <h1 class="glass-section-title glass-animate">           
            Документы фонда
        </h1>
        
        <div class="glass-card glass-animate" style="max-width: 900px; margin: 0 auto;">
            <p style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem; text-align: center;">
                Ознакомьтесь с учредительными документами и информационными материалами нашего фонда
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <!-- Устав фонда -->
                <a href="/static/documents/ustav.pdf" target="_blank" class="glass-card" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 1rem; padding: 2rem; transition: all 0.3s ease;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #fde9a9 0%, #ffb340 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);">
                        <i class="fas fa-file-pdf" style="font-size: 2rem; color: #0a0e27;"></i>
                    </div>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.3rem; font-weight: 600; margin: 0; text-align: center;">Устав фонда</h3>
                    <p style="color: var(--glass-text-secondary); font-size: 0.95rem; margin: 0; text-align: center; line-height: 1.6;">
                        Учредительный документ благотворительного фонда «Время Человека»
                    </p>
                    <div class="glass-btn glass-btn-primary" style="margin-top: auto;">
                        <i class="fas fa-download"></i>
                        Скачать PDF
                    </div>
                </a>

                <!-- Пресс-релиз -->
                <a href="/static/documents/press-release.pdf" target="_blank" class="glass-card" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 1rem; padding: 2rem; transition: all 0.3s ease;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #fde9a9 0%, #ffb340 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);">
                        <i class="fas fa-newspaper" style="font-size: 2rem; color: #0a0e27;"></i>
                    </div>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.3rem; font-weight: 600; margin: 0; text-align: center;">Пресс-релиз</h3>
                    <p style="color: var(--glass-text-secondary); font-size: 0.95rem; margin: 0; text-align: center; line-height: 1.6;">
                        Информационные материалы для СМИ о деятельности фонда
                    </p>
                    <div class="glass-btn glass-btn-primary" style="margin-top: auto;">
                        <i class="fas fa-download"></i>
                        Скачать PDF
                    </div>
                </a>
            </div>
            
            <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.1); border-radius: 12px; border: 1px solid rgba(212, 175, 55, 0.3); text-align: center;">
                <p style="color: var(--glass-text-secondary); margin: 0; line-height: 1.6;">
                    <i class="fas fa-info-circle" style="color: var(--glass-gold); margin-right: 0.5rem;"></i>
                    Для получения дополнительной информации свяжитесь с нами по адресу 
                    <a href="mailto:<?= e(CONTACT_EMAIL) ?>" style="color: var(--glass-gold); text-decoration: none; font-weight: 600;"><?= e(CONTACT_EMAIL) ?></a>
                </p>
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
            <h2 style="color: var(--glass-text-primary); margin-bottom: 1rem; font-size: 2rem; font-weight: 600;">Присоединяйтесь к нам</h2>
            <p style="color: var(--glass-text-secondary); margin-bottom: 2rem; font-size: 1.15rem; line-height: 1.8; max-width: 700px; margin-left: auto; margin-right: auto;">
                Станьте частью нашей миссии по созданию лучшего будущего для общества. Поддержите наши проекты или предложите свою инициативу.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="/projects" class="glass-btn glass-btn-primary glass-btn-large">
                    <i class="fas fa-folder-open"></i>
                    Наши проекты
                </a>
                <a href="/contacts" class="glass-btn glass-btn-outline glass-btn-large">
                    <i class="fas fa-envelope"></i>
                    Связаться с нами
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Завершаем буферизацию и рендерим страницу
endContent([
    'title' => 'Документы - Время Человека',
    'description' => 'Учредительные документы и информационные материалы благотворительного фонда «Время Человека». Устав фонда, пресс-релизы и другие документы.'
]);
?>