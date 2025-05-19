<?php
session_start();
include('../db.php');

if (!isset($_SESSION['hod_id'])) {
    header("Location: login.php");
    exit();
}

$hod_id = $_SESSION['hod_id'];
$message = "";

// Get HOD info
$stmt = $conn->prepare("SELECT name, email FROM hods WHERE id = ?");
$stmt->bind_param("i", $hod_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Update settings
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update_profile'])) {
        $new_name = trim($_POST['name']);
        $new_email = trim($_POST['email']);

        $update = $conn->prepare("UPDATE hods SET name = ?, email = ? WHERE id = ?");
        $update->bind_param("ssi", $new_name, $new_email, $hod_id);
        $update->execute();

        if ($update->affected_rows > 0) {
            $message = "Profile updated successfully";
            $name = $new_name;
            $email = $new_email;
        } else {
            $message = "No changes made";
        }
        $update->close();
    }

    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Verify old password
        $check = $conn->prepare("SELECT password FROM hods WHERE id = ?");
        $check->bind_param("i", $hod_id);
        $check->execute();
        $check->bind_result($hashed);
        $check->fetch();
        $check->close();

        if (password_verify($current, $hashed)) {
            $update = $conn->prepare("UPDATE hods SET password = ? WHERE id = ?");
            $update->bind_param("si", $new_pass, $hod_id);
            $update->execute();
            $message = "Password changed successfully";
        } else {
            $message = "Incorrect current password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f94144;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .settings-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            overflow: hidden;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .settings-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }
        
        .settings-header h2 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .settings-header h2 i {
            margin-right: 10px;
        }
        
        .settings-body {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: var(--accent-color);
            font-size: 0.9rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.5s ease;
        }
        
        .message.success {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .message.warning {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }
        
        .message.danger {
            background-color: rgba(249, 65, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .message i {
            margin-right: 8px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--light-color);
            color: var(--primary-color);
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            border: 1px solid rgba(67, 97, 238, 0.2);
            font-weight: 500;
        }
        
        .back-btn:hover {
            background: rgba(67, 97, 238, 0.1);
            color: var(--secondary-color);
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .back-btn i {
            margin-right: 8px;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        /* Password toggle */
        .password-toggle {
            position: relative;
        }
        
        .password-toggle .toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="settings-card fade-in">
        <div class="settings-header">
            <h2><i class="fas fa-cog"></i> Account Settings</h2>
        </div>
        
        <div class="settings-body">
            <?php if ($message): ?>
                <div class="message <?php 
                    echo strpos($message, 'successfully') !== false ? 'success' : 
                         (strpos($message, 'Incorrect') !== false ? 'danger' : 'warning'); 
                ?>">
                    <i class="fas <?php 
                        echo strpos($message, 'successfully') !== false ? 'fa-check-circle' : 
                             (strpos($message, 'Incorrect') !== false ? 'fa-times-circle' : 'fa-exclamation-circle'); 
                    ?>"></i>
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="mb-4">
                <div class="section-title">
                    <i class="fas fa-user-edit"></i> Profile Information
                </div>
                
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-signature"></i> Full Name
                    </label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= htmlspecialchars($name) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($email) ?>" required>
                </div>
                
                <button type="submit" name="update_profile" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i> Update Profile
                </button>
            </form>
            
            <form method="post">
                <div class="section-title">
                    <i class="fas fa-lock"></i> Change Password
                </div>
                
                <div class="form-group password-toggle">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-key"></i> Current Password
                    </label>
                    <input type="password" class="form-control" id="current_password" 
                           name="current_password" required>
                    <i class="fas fa-eye toggle-icon" onclick="togglePassword('current_password', this)"></i>
                </div>
                
                <div class="form-group password-toggle">
                    <label for="new_password" class="form-label">
                        <i class="fas fa-key"></i> New Password
                    </label>
                    <input type="password" class="form-control" id="new_password" 
                           name="new_password" required>
                    <i class="fas fa-eye toggle-icon" onclick="togglePassword('new_password', this)"></i>
                </div>
                
                <button type="submit" name="change_password" class="btn btn-primary w-100">
                    <i class="fas fa-lock me-2"></i> Change Password
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password toggle functionality
        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
        
        // Add animation to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.form-group, .section-title');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
                el.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>