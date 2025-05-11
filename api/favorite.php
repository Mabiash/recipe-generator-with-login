<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['recipe_id']) || !isset($data['action'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    
    $recipe_id = $data['recipe_id'];
    $action = $data['action'];
    $user_id = $_SESSION['user_id'];
    
    if ($action === 'add') {
        // Get recipe details
        $recipe = get_recipe_by_id($recipe_id);
        
        if (!$recipe) {
            http_response_code(404);
            echo json_encode(['error' => 'Recipe not found']);
            exit;
        }
        
        // Add to favorites
        if (add_to_favorites($conn, $user_id, $recipe)) {
            echo json_encode(['success' => true, 'message' => 'Recipe added to favorites']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add recipe to favorites']);
        }
    } elseif ($action === 'remove') {
        // Remove from favorites
        if (remove_from_favorites($conn, $user_id, $recipe_id)) {
            echo json_encode(['success' => true, 'message' => 'Recipe removed from favorites']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to remove recipe from favorites']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>