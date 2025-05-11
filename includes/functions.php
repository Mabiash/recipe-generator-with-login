<?php
// Sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Redirect to URL
function redirect($url) {
    header("Location: $url");
    exit;
}

// Display error message
function display_error($message) {
    return "<div class='error-message'>{$message}</div>";
}

// Display success message
function display_success($message) {
    return "<div class='success-message'>{$message}</div>";
}

// Get recipe by ID from API
function get_recipe_by_id($recipe_id) {
    $url = API_BASE_URL . "/lookup.php?i=" . urlencode($recipe_id);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data && isset($data['meals']) && is_array($data['meals']) && count($data['meals']) > 0) {
        return $data['meals'][0];
    }
    
    return null;
}

// Get random recipes from API
function get_random_recipes($count = 4) {
    $recipes = [];
    
    for ($i = 0; $i < $count; $i++) {
        $url = API_BASE_URL . "/random.php";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        if ($data && isset($data['meals']) && is_array($data['meals']) && count($data['meals']) > 0) {
            $recipes[] = $data['meals'][0];
        }
    }
    
    return $recipes;
}

// Search recipes by name
function search_recipes($query) {
    $url = API_BASE_URL . "/search.php?s=" . urlencode($query);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data && isset($data['meals']) && is_array($data['meals'])) {
        return $data['meals'];
    }
    
    return [];
}

// Get recipes by category
function get_recipes_by_category($category) {
    $url = API_BASE_URL . "/filter.php?c=" . urlencode($category);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data && isset($data['meals']) && is_array($data['meals'])) {
        return $data['meals'];
    }
    
    return [];
}

// Get all categories
function get_categories() {
    $url = API_BASE_URL . "/categories.php";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data && isset($data['categories']) && is_array($data['categories'])) {
        return $data['categories'];
    }
    
    return [];
}

// Check if recipe is favorite for user
function is_favorite($conn, $user_id, $recipe_id) {
    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $stmt->bind_param("is", $user_id, $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Add recipe to favorites
function add_to_favorites($conn, $user_id, $recipe) {
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, recipe_id, recipe_name, recipe_image, recipe_category, recipe_area) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP");
    $stmt->bind_param("isssss", $user_id, $recipe['idMeal'], $recipe['strMeal'], $recipe['strMealThumb'], $recipe['strCategory'], $recipe['strArea']);
    return $stmt->execute();
}

// Remove recipe from favorites
function remove_from_favorites($conn, $user_id, $recipe_id) {
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $stmt->bind_param("is", $user_id, $recipe_id);
    return $stmt->execute();
}

// Get user's favorite recipes
function get_favorite_recipes($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $favorites = [];
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
    
    return $favorites;
}
?>