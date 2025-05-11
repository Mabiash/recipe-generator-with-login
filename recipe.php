<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? sanitize_input($_GET['id']) : null;

// If no recipe ID provided, redirect to recipes page
if (empty($recipe_id)) {
    redirect('recipes.php');
}

// Fetch recipe details
$recipe = get_recipe_by_id($recipe_id);

// If recipe not found, redirect to recipes page
if (empty($recipe)) {
    redirect('recipes.php');
}

// Check if recipe is in user's favorites
$is_favorite = false;
if (is_logged_in()) {
    $is_favorite = is_favorite($conn, $_SESSION['user_id'], $recipe_id);
}

// Extract ingredients and measurements
$ingredients = [];
for ($i = 1; $i <= 20; $i++) {
    $ingredient = $recipe["strIngredient{$i}"];
    $measure = $recipe["strMeasure{$i}"];
    
    if (!empty($ingredient) && $ingredient !== " ") {
        $ingredients[] = [
            'ingredient' => $ingredient,
            'measure' => $measure
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recipe['strMeal']; ?> - Recipe Explorer</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="recipe-detail fade-in">
            <div class="container">
                <div class="recipe-header">
                    <h1><?php echo $recipe['strMeal']; ?></h1>
                    
                    <div class="recipe-meta">
                        <?php if (!empty($recipe['strCategory'])): ?>
                            <span class="badge">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20l4-16m2 16l4-16"></path><path d="M3 8h18"></path><path d="M3 16h18"></path></svg>
                                <?php echo $recipe['strCategory']; ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($recipe['strArea'])): ?>
                            <span class="badge">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path><path d="M2 12h20"></path></svg>
                                <?php echo $recipe['strArea']; ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (is_logged_in()): ?>
                            <button class="favorite-btn large <?php echo $is_favorite ? 'favorited' : ''; ?>" 
                                    data-recipe-id="<?php echo $recipe['idMeal']; ?>"
                                    aria-label="<?php echo $is_favorite ? 'Remove from favorites' : 'Add to favorites'; ?>">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="heart-empty"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="currentColor" stroke-linecap="round" stroke-linejoin="round" class="heart-filled"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                <span class="favorite-text"><?php echo $is_favorite ? 'Saved' : 'Save Recipe'; ?></span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="recipe-content">
                    <div class="recipe-image-container">
                        <img src="<?php echo $recipe['strMealThumb']; ?>" alt="<?php echo $recipe['strMeal']; ?>" class="recipe-image">
                    </div>
                    
                    <div class="recipe-details">
                        <div class="recipe-ingredients">
                            <h2>Ingredients</h2>
                            <ul class="ingredients-list">
                                <?php foreach ($ingredients as $item): ?>
                                    <li>
                                        <span class="ingredient-measure"><?php echo $item['measure']; ?></span>
                                        <span class="ingredient-name"><?php echo $item['ingredient']; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="recipe-instructions">
                            <h2>Instructions</h2>
                            <?php
                            // Format instructions into paragraphs
                            $instructions = $recipe['strInstructions'];
                            $paragraphs = explode("\r\n\r\n", $instructions);
                            
                            foreach ($paragraphs as $paragraph) {
                                if (!empty(trim($paragraph))) {
                                    echo "<p>{$paragraph}</p>";
                                }
                            }
                            ?>
                        </div>
                        
                        <?php if (!empty($recipe['strYoutube'])): ?>
                            <div class="recipe-video">
                                <h2>Video Tutorial</h2>
                                <div class="video-container">
                                    <?php
                                    // Convert YouTube URL to embed format
                                    $video_id = '';
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $recipe['strYoutube'], $match)) {
                                        $video_id = $match[1];
                                    }
                                    
                                    if (!empty($video_id)) {
                                        echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                    } else {
                                        echo '<a href="' . $recipe['strYoutube'] . '" target="_blank" rel="noopener" class="btn-primary">Watch on YouTube</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($recipe['strSource'])): ?>
                            <div class="recipe-source">
                                <p>
                                    <strong>Source:</strong> 
                                    <a href="<?php echo $recipe['strSource']; ?>" target="_blank" rel="noopener"><?php echo parse_url($recipe['strSource'], PHP_URL_HOST); ?></a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    
    <script src="js/app.js"></script>
    <script src="js/favorites.js"></script>
</body>
</html>