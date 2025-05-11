<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Get all categories
$categories = get_categories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Categories - Recipe Explorer</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Recipe Categories</h1>
                <p>Browse recipes by category to find your next favorite dish.</p>
            </div>
        </section>
        
        <section class="categories-section fade-in">
            <div class="container">
                <div class="categories-grid">
                    <?php foreach ($categories as $category): ?>
                        <a href="recipes.php?category=<?php echo urlencode($category['strCategory']); ?>" class="category-card">
                            <div class="category-image">
                                <img src="<?php echo $category['strCategoryThumb']; ?>" alt="<?php echo $category['strCategory']; ?>" loading="lazy">
                            </div>
                            <div class="category-content">
                                <h3><?php echo $category['strCategory']; ?></h3>
                                <p><?php echo substr($category['strCategoryDescription'], 0, 100); ?>...</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    
    <script src="js/app.js"></script>
</body>
</html>