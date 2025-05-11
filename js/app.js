// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', () => {
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const navLinks = document.querySelector('.nav-links');
  
  if (mobileMenuToggle && navLinks) {
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenuToggle.classList.toggle('active');
      navLinks.classList.toggle('active');
    });
  }
  
  // Close mobile menu when clicking outside
  document.addEventListener('click', (e) => {
    if (navLinks && navLinks.classList.contains('active') && 
        !navLinks.contains(e.target) && 
        !mobileMenuToggle.contains(e.target)) {
      navLinks.classList.remove('active');
      mobileMenuToggle.classList.remove('active');
    }
  });
  
  // Initialize dynamic footer categories
  loadFooterCategories();
});

// Load footer categories dynamically
async function loadFooterCategories() {
  const footerCategoriesEl = document.getElementById('footer-categories');
  
  if (footerCategoriesEl) {
    try {
      const response = await fetch('https://www.themealdb.com/api/json/v1/1/categories.php');
      const data = await response.json();
      
      if (data.categories && data.categories.length > 0) {
        // Get a random selection of categories (4)
        const randomCategories = data.categories
          .sort(() => 0.5 - Math.random())
          .slice(0, 4);
        
        footerCategoriesEl.innerHTML = '';
        
        randomCategories.forEach(category => {
          const li = document.createElement('li');
          const a = document.createElement('a');
          a.href = `recipes.php?category=${encodeURIComponent(category.strCategory)}`;
          a.textContent = category.strCategory;
          li.appendChild(a);
          footerCategoriesEl.appendChild(li);
        });
      }
    } catch (error) {
      console.error('Error loading footer categories:', error);
    }
  }
}