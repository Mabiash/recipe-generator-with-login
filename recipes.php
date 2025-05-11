<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

$recipes = [];
$search_query = '';
$category = '';
$title = 'Browse Recipes';

// Handle search query
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = sanitize_input($_GET['search']);
    $recipes = search_recipes($search_query);
    $title = 'Search Results for "' . $search_query . '"';
}

// Handle category filter
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = sanitize_input($_GET['category']);
    $recipes = get_recipes_by_category($category);
    $title = $category . ' Recipes';
}

// If no search or category, load random recipes
if (empty($recipes) && empty($search_query) && empty($category)) {
    $recipes = get_random_recipes(8);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Recipe Explorer</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1><?php echo $title; ?></h1>
                
                <form action="recipes.php" method="get" class="search-form">
                    <input type="text" name="search" placeholder="Search for recipes..." value="<?php echo $search_query; ?>" aria-label="Search for recipes">
                    <button type="submit" class="btn-primary">Search</button>
                </form>
            </div>
        </section>
        
        <section class="recipes-section">
            <div class="container">
                <?php if (!empty($category) || !empty($search_query)): ?>
                    <div class="filter-info">
                        <?php if (!empty($category)): ?>
                            <span class="badge"><?php echo $category; ?></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($search_query)): ?>
                            <span class="search-term">"<?php echo $search_query; ?>"</span>
                        <?php endif; ?>
                        
                        <a href="recipes.php" class="clear-filters">Clear filters</a>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($recipes)): ?>
                    <div class="no-results">
                        <p>No recipes found. Try a different search term or browse by category.</p>
                    </div>
                <?php else: ?>
                    <div class="recipes-grid">
                        <?php foreach ($recipes as $recipe): ?>
                            <div class="recipe-card fade-in">
                                <a href="recipe.php?id=<?php echo $recipe['idMeal']; ?>" class="recipe-card-link">
                                    <div class="recipe-image">
                                        <img src="<?php echo $recipe['strMealThumb']; ?>" alt="<?php echo $recipe['strMeal']; ?>" loading="lazy">
                                    </div>
                                    <div class="recipe-content">
                                        <h3><?php echo $recipe['strMeal']; ?></h3>
                                        <?php if (isset($recipe['strCategory'])): ?>
                                            <span class="recipe-category"><?php echo $recipe['strCategory']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <?php if (is_logged_in()): ?>
                                    <button class="favorite-btn <?php echo is_favorite($conn, $_SESSION['user_id'], $recipe['idMeal']) ? 'favorited' : ''; ?>" 
                                            data-recipe-id="<?php echo $recipe['idMeal']; ?>"
                                            aria-label="<?php echo is_favorite($conn, $_SESSION['user_id'], $recipe['idMeal']) ? 'Remove from favorites' : 'Add to favorites'; ?>">
                                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="heart-empty"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="currentColor" stroke-linecap="round" stroke-linejoin="round" class="heart-filled"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    </button>
                                <?php endif; ?>
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