<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M15 11h.01"></path><path d="M11 15h.01"></path><path d="M16 16h.01"></path><path d="M2 16l20 6-6-20A20 20 0 0 0 2 16"></path><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"></path></svg>
                <span>Recipe Explorer</span>
            </div>
            
            <div class="footer-links">
                <div class="footer-section">
                    <h3>Navigation</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="recipes.php">Recipes</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <?php if (is_logged_in()): ?>
                            <li><a href="profile.php">My Profile</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Categories</h3>
                    <ul class="footer-categories" id="footer-categories">
                        <li><a href="recipes.php?category=Breakfast">Breakfast</a></li>
                        <li><a href="recipes.php?category=Lunch">Lunch</a></li>
                        <li><a href="recipes.php?category=Dinner">Dinner</a></li>
                        <li><a href="recipes.php?category=Dessert">Dessert</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>About</h3>
                    <p>Recipe Explorer helps you discover delicious recipes from around the world. Save your favorites and become a better cook!</p>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Recipe Explorer. All rights reserved.</p>
            <p>Powered by <a href="https://www.themealdb.com/api.php" target="_blank" rel="noopener">TheMealDB API</a></p>
        </div>
    </div>
</footer>