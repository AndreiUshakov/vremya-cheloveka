/**
 * Автоматическая генерация slug из названия проекта
 * с транслитерацией и проверкой на существующие slug
 */

// Таблица транслитерации (кириллица -> латиница)
const translitMap = {
    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
    'е': 'e', 'ё': 'yo', 'ж': 'zh', 'з': 'z', 'и': 'i',
    'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
    'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
    'у': 'u', 'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch',
    'ш': 'sh', 'щ': 'shch', 'ъ': '', 'ы': 'y', 'ь': '',
    'э': 'e', 'ю': 'yu', 'я': 'ya',
    'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D',
    'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh', 'З': 'Z', 'И': 'I',
    'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N',
    'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T',
    'У': 'U', 'Ф': 'F', 'Х': 'Kh', 'Ц': 'Ts', 'Ч': 'Ch',
    'Ш': 'Sh', 'Щ': 'Shch', 'Ъ': '', 'Ы': 'Y', 'Ь': '',
    'Э': 'E', 'Ю': 'Yu', 'Я': 'Ya'
};

// Список существующих slug
let existingSlugs = [];

/**
 * Транслитерация текста
 */
function transliterate(text) {
    let result = '';
    for (let i = 0; i < text.length; i++) {
        const char = text[i];
        result += translitMap[char] || char;
    }
    return result;
}

/**
 * Создание slug из текста
 */
function createSlug(text) {
    // Транслитерация
    let slug = transliterate(text);
    
    // Приведение к нижнему регистру
    slug = slug.toLowerCase();
    
    // Замена всех не-буквенно-цифровых символов на дефис
    slug = slug.replace(/[^a-z0-9]+/g, '-');
    
    // Удаление дефисов в начале и конце
    slug = slug.replace(/^-+|-+$/g, '');
    
    return slug;
}

/**
 * Проверка доступности slug
 */
async function checkSlugAvailability(slug, currentFile = null) {
    // Пустой slug всегда недоступен
    if (!slug) {
        return { available: false, message: 'Slug не может быть пустым' };
    }
    
    // Проверка формата
    if (!/^[a-z0-9\-]+$/.test(slug)) {
        return { available: false, message: 'Slug может содержать только строчные латинские буквы, цифры и дефисы' };
    }
    
    // Проверка на существование (кроме текущего файла при редактировании)
    const expectedFilename = slug + '.md';
    const isCurrentFile = currentFile && currentFile === expectedFilename;
    
    if (!isCurrentFile && existingSlugs.includes(slug)) {
        return { available: false, message: 'Такой slug уже используется' };
    }
    
    return { available: true, message: 'Slug доступен' };
}

/**
 * Загрузка списка существующих slug
 */
async function loadExistingSlugs() {
    try {
        const response = await fetch('?action=api&method=list_slugs');
        const data = await response.json();
        
        if (data.success) {
            existingSlugs = data.slugs || [];
        }
    } catch (error) {
        console.error('Ошибка загрузки существующих slug:', error);
    }
}

/**
 * Обновление статуса slug в UI
 */
function updateSlugStatus(available, message) {
    const slugInput = document.getElementById('slug');
    const statusDiv = document.getElementById('slug-status');
    
    if (!statusDiv) return;
    
    if (available) {
        statusDiv.className = 'slug-status success';
        statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
        slugInput.setCustomValidity('');
    } else {
        statusDiv.className = 'slug-status error';
        statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
        slugInput.setCustomValidity(message);
    }
    
    statusDiv.style.display = 'block';
}

/**
 * Инициализация автогенерации slug
 */
function initSlugGenerator() {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const currentFile = slugInput.dataset.currentFile || null;
    
    if (!titleInput || !slugInput) return;
    
    // Создаем индикатор статуса
    const statusDiv = document.createElement('div');
    statusDiv.id = 'slug-status';
    statusDiv.className = 'slug-status';
    statusDiv.style.display = 'none';
    slugInput.parentNode.appendChild(statusDiv);
    
    // Флаг для отслеживания ручного редактирования slug
    let manualEdit = false;
    
    // При изменении названия - автоматически генерируем slug
    titleInput.addEventListener('input', async function() {
        // Если slug редактировался вручную, не обновляем его автоматически
        if (manualEdit && slugInput.value.trim() !== '') {
            return;
        }
        
        const generatedSlug = createSlug(this.value);
        slugInput.value = generatedSlug;
        
        // Проверяем доступность
        const result = await checkSlugAvailability(generatedSlug, currentFile);
        updateSlugStatus(result.available, result.message);
    });
    
    // При ручном изменении slug - проверяем доступность
    slugInput.addEventListener('input', async function() {
        manualEdit = true;
        
        const result = await checkSlugAvailability(this.value, currentFile);
        updateSlugStatus(result.available, result.message);
    });
    
    // При потере фокуса - нормализуем slug
    slugInput.addEventListener('blur', async function() {
        if (this.value) {
            this.value = createSlug(this.value);
            const result = await checkSlugAvailability(this.value, currentFile);
            updateSlugStatus(result.available, result.message);
        }
    });
    
    // Если slug уже заполнен (режим редактирования), проверяем его
    if (slugInput.value) {
        manualEdit = true;
        checkSlugAvailability(slugInput.value, currentFile).then(result => {
            updateSlugStatus(result.available, result.message);
        });
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', async function() {
    // Загружаем существующие slug
    await loadExistingSlugs();
    
    // Инициализируем генератор
    initSlugGenerator();
});