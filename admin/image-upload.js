/**
 * Обработка загрузки изображений проектов
 */

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('imageFile');
    const imagePreview = document.getElementById('image-preview');
    const imageUrlInput = document.getElementById('imageUrl');
    const deleteImageInput = document.getElementById('deleteImage');
    const deleteImageBtn = document.getElementById('deleteImageBtn');
    
    if (!fileInput || !imagePreview) return;
    
    // Обработка выбора файла
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) return;
        
        // Проверка типа файла
        if (!file.type.match('image.*')) {
            alert('Пожалуйста, выберите изображение');
            fileInput.value = '';
            return;
        }
        
        // Проверка размера файла (5 МБ)
        if (file.size > 5 * 1024 * 1024) {
            alert('Размер файла не должен превышать 5 МБ');
            fileInput.value = '';
            return;
        }
        
        // Показываем превью
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            
            // Показываем кнопку удаления если её не было
            if (deleteImageBtn) {
                deleteImageBtn.style.display = 'inline-block';
            }
        };
        reader.readAsDataURL(file);
    });
    
    // Обработка удаления изображения
    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (!confirm('Вы уверены, что хотите удалить изображение?')) {
                return;
            }
            
            // Устанавливаем заглушку
            imagePreview.src = '/static/img/nophoto.svg';
            
            // Очищаем поля
            imageUrlInput.value = '';
            fileInput.value = '';
            deleteImageInput.value = '1';
            
            // Скрываем кнопку удаления
            deleteImageBtn.style.display = 'none';
        });
    }
});