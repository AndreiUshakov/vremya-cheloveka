<?php
/**
 * Парсер Markdown файлов с frontmatter
 * Используется для чтения и обработки .md файлов проектов и новостей
 */

class MarkdownParser {
    
    /**
     * Парсит markdown файл и возвращает массив с метаданными и контентом
     * 
     * @param string $filePath Путь к .md файлу
     * @return array|null Массив с данными или null при ошибке
     */
    public static function parse($filePath) {
        if (!file_exists($filePath)) {
            return null;
        }
        
        $content = file_get_contents($filePath);
        
        // Извлекаем frontmatter (данные между --- и ---)
        $pattern = '/^---\s*\n(.*?)\n---\s*\n(.*)$/s';
        if (!preg_match($pattern, $content, $matches)) {
            return null;
        }
        
        $frontmatter = $matches[1];
        $markdown = $matches[2];
        
        // Парсим YAML frontmatter
        $data = self::parseYaml($frontmatter);
        
        // Добавляем HTML-контент
        $data['content'] = self::markdownToHtml($markdown);
        $data['excerpt'] = self::generateExcerpt($markdown);
        
        return $data;
    }
    
    /**
     * Простой парсер YAML frontmatter
     * 
     * @param string $yaml YAML строка
     * @return array Распарсенные данные
     */
    private static function parseYaml($yaml) {
        $data = [];
        $lines = explode("\n", $yaml);
        $currentKey = null;
        $arrayMode = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Обработка массивов
            if (strpos($line, '- ') === 0) {
                $value = trim(substr($line, 2));
                // Удаляем кавычки если есть
                $value = trim($value, '"\'');
                if ($currentKey && $arrayMode) {
                    $data[$currentKey][] = $value;
                }
                continue;
            }
            
            // Обработка вложенных объектов (milestones)
            if (preg_match('/^- ([a-zA-Z]+):\s*(.*)$/', $line, $match)) {
                if ($currentKey === 'milestones') {
                    if (!isset($data['milestones'])) {
                        $data['milestones'] = [];
                    }
                    $data['milestones'][] = [
                        $match[1] => trim($match[2], '"\'')
                    ];
                }
                continue;
            }
            
            // Обработка вложенных полей в milestones
            if (preg_match('/^\s{2,}([a-zA-Z]+):\s*(.*)$/', $line, $match)) {
                if ($currentKey === 'milestones' && !empty($data['milestones'])) {
                    $lastIndex = count($data['milestones']) - 1;
                    $value = trim($match[2], '"\'');
                    // Конвертируем булевы значения
                    if ($value === 'true') $value = true;
                    if ($value === 'false') $value = false;
                    // Конвертируем числа
                    if (is_numeric($value)) $value = (int)$value;
                    $data['milestones'][$lastIndex][$match[1]] = $value;
                }
                continue;
            }
            
            // Обработка обычных пар ключ: значение
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Проверяем, начинается ли массив
                if (empty($value)) {
                    $currentKey = $key;
                    $arrayMode = true;
                    $data[$key] = [];
                    continue;
                }
                
                // Сброс режима массива
                $arrayMode = false;
                $currentKey = $key;
                
                // Удаляем кавычки
                $value = trim($value, '"\'');
                
                // Конвертируем типы
                if ($value === 'true') $value = true;
                if ($value === 'false') $value = false;
                if (is_numeric($value)) $value = (int)$value;
                
                $data[$key] = $value;
            }
        }
        
        return $data;
    }
    
    /**
     * Конвертирует Markdown в HTML
     * 
     * @param string $markdown Markdown текст
     * @return string HTML
     */
    private static function markdownToHtml($markdown) {
        // Базовое преобразование Markdown в HTML
        $html = $markdown;
        
        // Заголовки
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
        
        // Жирный текст
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        
        // Курсив
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
        
        // Ссылки
        $html = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $html);
        
        // Списки (неупорядоченные)
        $html = preg_replace_callback('/^((?:- .+\n?)+)/m', function($matches) {
            $items = preg_replace('/^- (.+)$/m', '<li>$1</li>', $matches[1]);
            return '<ul>' . $items . '</ul>';
        }, $html);
        
        // Параграфы
        $html = preg_replace('/^(?!<[hul]|---)(.+)$/m', '<p>$1</p>', $html);
        
        // Убираем пустые параграфы
        $html = preg_replace('/<p>\s*<\/p>/', '', $html);
        
        // Горизонтальная линия
        $html = str_replace('---', '<hr>', $html);
        
        return $html;
    }
    
    /**
     * Генерирует краткое описание из текста
     * 
     * @param string $markdown Markdown текст
     * @param int $length Максимальная длина
     * @return string Краткое описание
     */
    private static function generateExcerpt($markdown, $length = 200) {
        // Удаляем markdown разметку
        $text = preg_replace('/[#*\[\]\(\)]/', '', $markdown);
        $text = strip_tags($text);
        $text = trim($text);
        
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . '...';
    }
    
    /**
     * Получает все файлы из директории
     * 
     * @param string $directory Путь к директории
     * @param string $extension Расширение файлов (по умолчанию .md)
     * @return array Массив объектов с данными файлов
     */
    public static function getAllFromDirectory($directory, $extension = 'md') {
        $items = [];
        
        if (!is_dir($directory)) {
            return $items;
        }
        
        $files = glob($directory . '/*.' . $extension);
        
        foreach ($files as $file) {
            $data = self::parse($file);
            if ($data) {
                // Добавляем slug из имени файла
                $data['slug'] = basename($file, '.' . $extension);
                // Добавляем ID (используем slug как ID)
                $data['id'] = $data['slug'];
                $items[] = $data;
            }
        }
        
        return $items;
    }
    
    /**
     * Получает один файл по slug
     * 
     * @param string $directory Путь к директории
     * @param string $slug Slug файла
     * @return array|null Данные файла или null
     */
    public static function getBySlug($directory, $slug) {
        $filePath = $directory . '/' . $slug . '.md';
        $data = self::parse($filePath);
        
        if ($data) {
            $data['slug'] = $slug;
            $data['id'] = $slug;
        }
        
        return $data;
    }
    
    /**
     * Сортирует массив элементов
     * 
     * @param array $items Массив элементов
     * @param string $field Поле для сортировки
     * @param string $order Порядок (asc/desc)
     * @return array Отсортированный массив
     */
    public static function sort($items, $field = 'publishedAt', $order = 'desc') {
        usort($items, function($a, $b) use ($field, $order) {
            $valA = $a[$field] ?? 0;
            $valB = $b[$field] ?? 0;
            
            if ($order === 'desc') {
                return $valB <=> $valA;
            }
            return $valA <=> $valB;
        });
        
        return $items;
    }
    
    /**
     * Фильтрует элементы
     * 
     * @param array $items Массив элементов
     * @param array $filters Ассоциативный массив фильтров
     * @return array Отфильтрованный массив
     */
    public static function filter($items, $filters) {
        return array_filter($items, function($item) use ($filters) {
            foreach ($filters as $field => $value) {
                if (!isset($item[$field]) || $item[$field] !== $value) {
                    return false;
                }
            }
            return true;
        });
    }
    
    /**
     * Получает избранные элементы (featured)
     * 
     * @param array $items Массив элементов
     * @param int $limit Максимальное количество
     * @return array Избранные элементы
     */
    public static function getFeatured($items, $limit = 3) {
        $featured = array_filter($items, function($item) {
            return isset($item['featured']) && $item['featured'] === true;
        });
        
        return array_slice($featured, 0, $limit);
    }
}