<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Explorer - Find and Save Delicious Recipes</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content fade-in">
                    <h1>Discover Delicious Recipes</h1>
                    <p>Find inspiration for your next meal from thousands of recipes.</p>
                    <form action="recipes.php" method="get" class="search-form">
                        <input type="text" name="search" class="search-recipe" placeholder="Search for recipes..." aria-label="Search for recipes">
                        <button type="submit" class="btn-primary">Search</button>
                    </form>
                </div>
                <div class="hero-image fade-in">
                    <img src="https://wallpapers.com/images/hd/food-4k-spdnpz7bhmx4kv2r.jpg" alt="Delicious food arrangement" class="rounded">
                </div>
            </div>
        </section>

        <section class="featured-recipes">
            <div class="container">
                <h2>Random Recipes</h2>
                <div class="recipes-grid" id="featured-recipes-container">
                    <div class="recipe-card skeleton"></div>
                    <div class="recipe-card skeleton"></div>
                    <div class="recipe-card skeleton"></div>
                    <div class="recipe-card skeleton"></div>
                </div>
            </div>
        </section>

        <section class="categories">
            <div class="container">
                <h2>Browse Categories</h2>
                <div class="categories-grid" id="categories-container">
                    <div class="category-card skeleton"></div>
                    <div class="category-card skeleton"></div>
                    <div class="category-card skeleton"></div>
                    <div class="category-card skeleton"></div>
                    <div class="category-card skeleton"></div>
                    <div class="category-card skeleton"></div>
                </div>
            </div>
        </section>

        <?php if(!is_logged_in()):?>
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Join our Recipe Community</h2>
                    <p>Create an account to save your favorite recipes and discover new culinary creations.</p>
                    <a href="register.php" class="btn-primary">Sign Up Now</a>
                </div>
            </div>
        </section>
    </main>
    <?php endif; ?>
    <?php include_once 'includes/footer.php'; ?>
    
    <script src="js/app.js"></script>
    <script src="js/home.js"></script>
</body>
</html>