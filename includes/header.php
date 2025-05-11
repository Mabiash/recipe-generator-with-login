<header class="site-header">
    <div class="container">
        <div class="header-content">
            <a href="index.php" class="logo">
                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M15 11h.01"></path><path d="M11 15h.01"></path><path d="M16 16h.01"></path><path d="M2 16l20 6-6-20A20 20 0 0 0 2 16"></path><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"></path></svg>
                <span>Recipe Explorer</span>
            </a>
            
            <nav class="main-nav">
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="recipes.php">Recipes</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <?php if (is_logged_in()): ?>
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php" class="btn-outline">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>