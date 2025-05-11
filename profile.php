<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Redirect if not logged in
if (!is_logged_in()) {
    redirect('login.php');
}

// Get user's favorite recipes
$favorites = get_favorite_recipes($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Recipe Explorer</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="profile-header">
            <div class="container">
                <h1>My Profile</h1>
                <div class="profile-info">
                    <div class="profile-avatar">
                        <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div class="profile-details">
                        <h2><?php echo $_SESSION['user_name'] ?: $_SESSION['user_email']; ?></h2>
                        <p><?php echo $_SESSION['user_email']; ?></p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="favorites-section fade-in">
            <div class="container">
                <h2>My Favorite Recipes</h2>
                
                <?php if (empty($favorites)): ?>
                    <div class="no-favorites">
                        <p>You haven't saved any recipes yet.</p>
                        <a href="recipes.php" class="btn-primary">Browse Recipes</a>
                    </div>
                <?php else: ?>
                    <div class="recipes-grid">
                        <?php foreach ($favorites as $recipe): ?>
                            <div class="recipe-card fade-in">
                                <a href="recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="recipe-card-link">
                                    <div class="recipe-image">
                                        <img src="<?php echo $recipe['recipe_image']; ?>" alt="<?php echo $recipe['recipe_name']; ?>" loading="lazy">
                                    </div>
                                    <div class="recipe-content">
                                        <h3><?php echo $recipe['recipe_name']; ?></h3>
                                        <?php if (!empty($recipe['recipe_category'])): ?>
                                            <span class="recipe-category"><?php echo $recipe['recipe_category']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <button class="favorite-btn favorited" 
                                        data-recipe-id="<?php echo $recipe['recipe_id']; ?>"
                                        aria-label="Remove from favorites">
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="heart-empty"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="currentColor" stroke-linecap="round" stroke-linejoin="round" class="heart-filled"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    
    <script src="js/app.js"></script>
    <script src="js/favorites.js"></script>
</body>
</html>