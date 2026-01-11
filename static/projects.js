// Загрузка и отображение проектов
let allProjects = [];
let currentCategory = 'all';

// Загрузить проекты
async function loadProjects(category = 'all') {
  try {
    const url = category === 'all' 
      ? '/api/projects' 
      : `/api/projects?category=${category}`;
    
    const response = await axios.get(url);
    
    if (response.data.success) {
      allProjects = response.data.projects;
      renderProjects(allProjects);
    }
  } catch (error) {
    console.error('Error loading projects:', error);
    document.getElementById('projects-list').innerHTML = `
      <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
        <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: var(--primary-red);"></i>
        <p style="margin-top: 1rem;">Ошибка загрузки проектов. Попробуйте обновить страницу.</p>
      </div>
    `;
  }
}

// Отобразить проекты
function renderProjects(projects) {
  const container = document.getElementById('projects-list');
  
  if (projects.length === 0) {
    container.innerHTML = `
      <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
        <i class="fas fa-folder-open" style="font-size: 3rem; color: #ccc;"></i>
        <p style="margin-top: 1rem; color: #888;">Проекты в данной категории не найдены</p>
      </div>
    `;
    return;
  }
  
  container.innerHTML = projects.map(project => {
    const progress = project.target_amount 
      ? (project.collected_amount / project.target_amount * 100).toFixed(1)
      : 0;
    
    return `
      <div class="project-card">
        <img src="${project.image_url}" alt="${project.title}" class="project-image" />
        <div class="project-content">
          <span class="project-category">${getCategoryName(project.category)}</span>
          <h3 class="project-title">${project.title}</h3>
          <p class="project-description">${project.short_description}</p>
          
          ${project.target_amount ? `
            <div class="project-stats">
              <div class="stat-item">
                <span class="stat-label">Собрано</span>
                <span class="stat-value">${formatAmount(project.collected_amount)} ₽</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Цель</span>
                <span class="stat-value">${formatAmount(project.target_amount)} ₽</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Получателей</span>
                <span class="stat-value">${project.beneficiaries_count.toLocaleString('ru-RU')}</span>
              </div>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: ${progress}%"></div>
            </div>
          ` : ''}
          
          <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
            <a href="/projects/${project.slug}" class="btn btn-outline" style="flex: 1; padding: 0.75rem;">
              Подробнее
            </a>
            <a href="#donate-${project.id}" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">
              Помочь
            </a>
          </div>
        </div>
      </div>
    `;
  }).join('');
}

// Форматирование суммы
function formatAmount(amount) {
  return new Intl.NumberFormat('ru-RU').format(amount);
}

// Название категории
function getCategoryName(category) {
  const categories = {
    'children': 'Дети',
    'education': 'Образование',
    'health': 'Здоровье',
    'social': 'Социальные',
    'other': 'Другое'
  };
  return categories[category] || category;
}

// Обработчики фильтров
document.addEventListener('DOMContentLoaded', () => {
  // Загрузить проекты при загрузке страницы
  loadProjects();
  
  // Обработчики кнопок фильтра
  const filterButtons = document.querySelectorAll('.filter-btn');
  filterButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      const category = e.target.dataset.category;
      currentCategory = category;
      
      // Обновить активную кнопку
      filterButtons.forEach(b => {
        b.style.backgroundColor = '';
        b.style.color = '';
      });
      e.target.style.backgroundColor = 'var(--deep-burgundy)';
      e.target.style.color = 'white';
      
      // Загрузить проекты
      loadProjects(category);
    });
  });
  
  // Установить активной первую кнопку
  if (filterButtons.length > 0) {
    filterButtons[0].style.backgroundColor = 'var(--deep-burgundy)';
    filterButtons[0].style.color = 'white';
  }
});
