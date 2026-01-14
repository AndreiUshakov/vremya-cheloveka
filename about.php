<?php
/**
 * Страница "О фонде"
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_DIR . '/layout.php';

// Начинаем буферизацию контента
startContent();
?>

<!-- Основная секция "О фонде" -->
<section class="glass-section" style="padding-top: 10rem;">
    <div class="container">
        <h1 class="glass-section-title glass-animate">            
            О фонде
        </h1>
        
        <!-- Стеклянный контейнер с основным текстом -->
        <div class="glass-card-glow glass-animate" style="max-width: 1200px; margin: 0 auto;">
            
            <!-- Кто мы -->
             <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">                   
                    Кто мы
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                    Благотворительный фонд поддержки инициатив в области народосбережения и просвещения «Время Человека» — некоммерческая организация, созданная для реализации социально значимых проектов в сфере образования, культуры, здоровья и защиты семейных ценностей. Фонд зарегистрирован в Иркутской области и осуществляет деятельность в соответствии с законодательством Российской Федерации.
                </p>
            </div>

            <!-- Наша миссия -->
            <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">                   
                    Наша миссия
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                    Формирование условий для всестороннего развития личности через поддержку образовательных, культурных и просветительских инициатив. Мы работаем над созданием благоприятной среды для духовного развития общества, укрепления традиционных семейных ценностей и сохранения здоровья нации.
                </p>
            </div>

            <!-- Ценности и принципы -->
            <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                    
                    Ценности и принципы
                </h2>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Просвещение</strong> — доступ к качественному образованию и культурному развитию для каждого
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Семья</strong> — основа общества, требующая поддержки и защиты
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Здоровье</strong> — здоровый образ жизни как путь к благополучию нации
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Прозрачность</strong> — открытая отчетность и целевое использование средств
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 0; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Солидарность</strong> — объединение усилий благотворителей, волонтеров и партнеров
                    </li>
                </ul>
            </div>

            <!-- Направления деятельности -->
            <div>
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                    
                    Направления деятельности
                </h2>
                
                <!-- Образование и наука -->
                <div style="margin-bottom: 2.5rem; padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 16px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        
                        Образование и наука
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Разработка образовательных и развивающих программ для детей в общеобразовательных, дошкольных организациях и учреждениях дополнительного образования
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Техническое оснащение образовательных организаций для повышения эффективности преподавания и творческого развития детей
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Создание высококачественных методических материалов и проведение развивающих занятий для детей, подростков и взрослых
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Проведение научно-исследовательских работ по вопросам влияния окружающей среды, образовательных пространств и информационного окружения на развитие детей
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Популяризация научных исследований о влиянии информации на развитие детей
                        </li>
                    </ul>
                </div>
                <div style="margin-bottom: 2.5rem; padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 16px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        
                        Культура, искусство и просвещение
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Содействие деятельности в сфере культуры, искусства и просвещения
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Производство и выпуск высококачественной и высокохудожественной информационной, видео и аудиопродукции
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Издательская деятельность: выпуск и распространение печатной, аудио и видео продукции в соответствии с целями фонда
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Организация культурных, развлекательных и массовых мероприятий
                        </li>
                    </ul>
                </div>
                <div style="margin-bottom: 2.5rem; padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 16px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        
                        Семья и здоровье                       

                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Поддержка, укрепление и защита семьи, многодетности, сохранение традиционных семейных ценностей
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Популяризация института брака
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Содействие защите материнства, детства и отцовства
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Профилактика и охрана здоровья граждан
                        </li>
                         <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Пропаганда здорового образа жизни
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Улучшение морально-психологического состояния граждан
                        </li>
                    </ul>
                </div>
                <div style="margin-bottom: 2.5rem; padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 16px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        
                       Молодежь и волонтерство                   

                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Содействие добровольческой (волонтерской) деятельности
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                           Поддержка научно-технического и художественного творчества детей и молодежи
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Патриотическое и духовно-нравственное воспитание детей и молодежи
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Поддержка общественно значимых молодежных инициатив, проектов, детского и молодежного движения
                        </li>
                         <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                           Профилактика безнадзорности и правонарушений несовершеннолетних
                        </li>
                    </ul>
                </div>
                <div style="margin-bottom: 2.5rem; padding: 1.5rem; background: rgba(10, 14, 39, 0.3); border-radius: 16px; border-left: 4px solid var(--glass-gold);">
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        
                       Социальная поддержка             

                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Содействие деятельности в области физической культуры и спорта
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                           Оказание бесплатной юридической помощи и правовое просвещение населения
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Содействие укреплению мира, дружбы и согласия между народами
                        </li>
                        <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                            Подготовка населения к преодолению последствий стихийных бедствий и чрезвычайных ситуаций
                        </li>
                         <li style="color: var(--glass-text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--glass-gold);">→</span>
                          Охрана окружающей среды и защита животных
                        </li>
                    </ul>
                </div>


                <!-- Здесь можно добавить остальные направления -->
            </div>

            <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">                   
                    Управление и структура
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                    Деятельность фонда обеспечивается тремя органами управления, что гарантирует эффективность работы и целевое использование средств:
                </p>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        Совет Фонда
                    </h3>
                    <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                        Высший коллегиальный орган управления, определяющий приоритетные направления и цели деятельности, утверждающий годовые отчеты, бюджет и благотворительные программы.
                    </p>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        Директор Фонда
                    </h3>
                    <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                        Единоличный исполнительный орган, руководящий текущей деятельностью, обеспечивающий выполнение решений Совета и представляющий фонд во взаимоотношениях с партнерами.
                    </p>
                    <h3 style="color: var(--glass-text-primary); font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">
                        Попечительский Совет
                    </h3>
                    <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                        Надзорный орган, осуществляющий контроль за деятельностью фонда, целевым использованием средств и соблюдением законодательства. Действует на общественных началах.
                    </p>

            </div>

            <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                    
                    Прозрачность и отчетность
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                    Фонд строит работу на принципах открытости и подотчетности:
                </p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Ежегодная публикация отчетов об использовании имущества
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Не менее 80% благотворительных пожертвований направляется на реализацию благотворительных программ в течение года
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Не более 20% средств используется на оплату труда административного персонала
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Ведение бухгалтерского учета и статистической отчетности в соответствии с законодательством РФ
                    </li>

                </ul>
            </div>


              <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                    
                   Источники финансирования
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                    Фонд формирует имущество из следующих источников:
                </p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Взносы учредителей
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Благотворительные пожертвования от граждан и юридических лиц в денежной или натуральной форме
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Благотворительные гранты
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Доходы от приносящей доход деятельности (организация мероприятий, издательская деятельность, лотереи и аукционы)
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Труд добровольцев
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        Иные не запрещенные законом поступления
                    </li>
                </ul>
            </div>

            <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">                   
                    Партнерство и сотрудничество
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                   Фонд взаимодействует с органами государственной власти Российской Федерации, субъектов РФ, органами местного самоуправления, юридическими и физическими лицами для наиболее эффективного решения задач, направленных на достижение уставных целей. Мы привлекаем российских и иностранных благотворителей и меценатов к финансированию проектов и программ фонда.
                </p>
            </div>

            <div style="margin-bottom: 3rem;">
                <h2 style="color: var(--glass-gold); font-size: 2rem; margin-bottom: 1.5rem; font-weight: 600;">
                    
                    Как поддержать фонд
                </h2>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">
                   Вы можете стать частью позитивных перемен:
                </p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Благотворительное пожертвование </strong> — разовое или регулярное в денежной или натуральной форме
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Волонтерство </strong> — участие в реализации проектов и программ фонда
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Партнерство </strong> — реализация совместных благотворительных инициатив
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.25rem; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Меценатство </strong> — поддержка конкретных образовательных, культурных или социальных проектов
                    </li>
                    <li style="color: var(--glass-text-secondary); font-size: 1.1rem; line-height: 1.8; margin-bottom: 0; padding-left: 2.5rem; position: relative;">
                        <i class="fas fa-circle" style="position: absolute; left: 0; color: var(--glass-gold); font-size: 1.25rem; top: 0.25rem;"></i>
                        <strong style="color: var(--glass-text-primary);">Солидарность</strong> — объединение усилий благотворителей, волонтеров и партнеров
                    </li>
                </ul>
                <p style="color: var(--glass-text-secondary); font-size: 1.15rem; line-height: 1.9; text-align: justify;">

                Все средства и имущество, переданные фонду, используются исключительно для достижения уставных целей. Фонд не распределяет прибыль между учредителями и сотрудниками.
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
    'title' => 'О фонде - Время Человека',
    'description' => 'Благотворительный фонд «Время Человека» — некоммерческая организация, созданная для поддержки инициатив в области народосбережения и просвещения. Наша миссия, ценности и направления деятельности.'
]);
?>