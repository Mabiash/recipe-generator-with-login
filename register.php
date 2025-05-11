<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$success = '';
$email = '';
$name = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $name = sanitize_input($_POST['name']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validate inputs
    if (empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email is already registered';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $name);
            
            if ($stmt->execute()) {
                $success = 'Registration successful. You can now login.';
                // Clear form fields
                $email = '';
                $name = '';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Recipe Explorer</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="auth-section fade-in">
            <div class="container">
                <div class="auth-container">
                    <h1>Create an Account</h1>
                    
                    <?php if (!empty($error)): ?>
                        <?php echo display_error($error); ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <?php echo display_success($success); ?>
                    <?php endif; ?>
                    
                    <form action="register.php" method="post" class="auth-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Name (optional)</label>
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirm">Confirm Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" required>
                        </div>
                        
                        <button type="submit" class="btn-primary btn-full">Register</button>
                    </form>
                    
                    <div class="auth-footer">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    
    <script src="js/app.js"></script>
</body>
</html>