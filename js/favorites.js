document.addEventListener('DOMContentLoaded', () => {
  // Initialize favorite buttons
  initFavoriteButtons();
});

// Initialize favorite buttons
function initFavoriteButtons() {
  const favoriteButtons = document.querySelectorAll('.favorite-btn');
  
  favoriteButtons.forEach(button => {
    button.addEventListener('click', handleFavoriteClick);
  });
}

// Handle favorite button click
async function handleFavoriteClick(e) {
  e.preventDefault();
  
  const button = e.currentTarget;
  const recipeId = button.getAttribute('data-recipe-id');
  const isFavorited = button.classList.contains('favorited');
  const action = isFavorited ? 'remove' : 'add';
  
  try {
    const response = await fetch('api/favorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        recipe_id: recipeId,
        action: action
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Toggle favorite button state
      button.classList.toggle('favorited');
      
      // Update button aria-label
      const newLabel = isFavorited ? 'Add to favorites' : 'Remove from favorites';
      button.setAttribute('aria-label', newLabel);
      
      // Update button text if it has text
      const textElement = button.querySelector('.favorite-text');
      if (textElement) {
        textElement.textContent = isFavorited ? 'Save Recipe' : 'Saved';
      }
      
      // If we're on the profile page and removing a favorite, remove the card
      if (window.location.pathname.includes('profile.php') && action === 'remove') {
        const recipeCard = button.closest('.recipe-card');
        if (recipeCard) {
          recipeCard.classList.add('fade-out');
          setTimeout(() => {
            recipeCard.remove();
            
            // Check if there are any cards left
            const remainingCards = document.querySelectorAll('.recipe-card');
            if (remainingCards.length === 0) {
              const container = document.querySelector('.recipes-grid');
              if (container) {
                container.innerHTML = `
                  <div class="no-favorites">
                    <p>You haven't saved any recipes yet.</p>
                    <a href="recipes.php" class="btn-primary">Browse Recipes</a>
                  </div>
                `;
              }
            }
          }, 300);
        }
      }
    } else {
      console.error('Error updating favorite:', data.error);
    }
  } catch (error) {
    console.error('Error updating favorite:', error);
  }
}