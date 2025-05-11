document.addEventListener('DOMContentLoaded', () => {
  // Load featured recipes
  loadFeaturedRecipes();
  
  // Load categories
  loadCategories();
});

// Load featured recipes
async function loadFeaturedRecipes() {
  const featuredRecipesContainer = document.getElementById('featured-recipes-container');
  
  if (featuredRecipesContainer) {
    try {
      // Make 4 parallel requests for random recipes
      const promises = Array(4).fill(0).map(() => 
        fetch('https://www.themealdb.com/api/json/v1/1/random.php')
          .then(res => res.json())
          .then(data => data.meals[0])
      );
      
      const recipes = await Promise.all(promises);
      
      // Clear loading skeletons
      featuredRecipesContainer.innerHTML = '';
      
      // Add recipe cards
      recipes.forEach(recipe => {
        const recipeCard = createRecipeCard(recipe);
        featuredRecipesContainer.appendChild(recipeCard);
      });
    } catch (error) {
      console.error('Error loading featured recipes:', error);
      featuredRecipesContainer.innerHTML = '<p>Error loading recipes. Please try again later.</p>';
    }
  }
}

// Load categories
async function loadCategories() {
  const categoriesContainer = document.getElementById('categories-container');
  
  if (categoriesContainer) {
    try {
      const response = await fetch('https://www.themealdb.com/api/json/v1/1/categories.php');
      const data = await response.json();
      
      if (data.categories && data.categories.length > 0) {
        // Clear loading skeletons
        categoriesContainer.innerHTML = '';
        
        // Get first 6 categories
        const categories = data.categories.slice(0, 6);
        
        // Add category cards
        categories.forEach(category => {
          const categoryCard = createCategoryCard(category);
          categoriesContainer.appendChild(categoryCard);
        });
      }
    } catch (error) {
      console.error('Error loading categories:', error);
      categoriesContainer.innerHTML = '<p>Error loading categories. Please try again later.</p>';
    }
  }
}

// Create recipe card element
function createRecipeCard(recipe) {
  const recipeCard = document.createElement('div');
  recipeCard.className = 'recipe-card fade-in';
  
  recipeCard.innerHTML = `
    <a href="recipe.php?id=${recipe.idMeal}" class="recipe-card-link">
      <div class="recipe-image">
        <img src="${recipe.strMealThumb}" alt="${recipe.strMeal}" loading="lazy">
      </div>
      <div class="recipe-content">
        <h3>${recipe.strMeal}</h3>
        ${recipe.strCategory ? `<span class="recipe-category">${recipe.strCategory}</span>` : ''}
      </div>
    </a>
  `;
  
  // Add favorite button if user is logged in
  const isLoggedIn = document.querySelector('.nav-links').textContent.includes('My Profile');
  if (isLoggedIn) {
    const favBtn = document.createElement('button');
    favBtn.className = 'favorite-btn';
    favBtn.setAttribute('data-recipe-id', recipe.idMeal);
    favBtn.setAttribute('aria-label', 'Add to favorites');
    
    favBtn.innerHTML = `
      <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="heart-empty"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
      <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="currentColor" stroke-linecap="round" stroke-linejoin="round" class="heart-filled"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
    `;
    
    recipeCard.appendChild(favBtn);
  }
  
  return recipeCard;
}

// Create category card element
function createCategoryCard(category) {
  const categoryCard = document.createElement('a');
  categoryCard.className = 'category-card fade-in';
  categoryCard.href = `recipes.php?category=${encodeURIComponent(category.strCategory)}`;
  
  categoryCard.innerHTML = `
    <div class="category-image">
      <img src="${category.strCategoryThumb}" alt="${category.strCategory}" loading="lazy">
    </div>
    <div class="category-content">
      <h3>${category.strCategory}</h3>
      <p>${category.strCategoryDescription.substring(0, 100)}...</p>
    </div>
  `;
  
  return categoryCard;
}