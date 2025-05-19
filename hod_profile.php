<?php
session_start();
include('../db.php');

if (!isset($_SESSION['hod_id'])) {
    header("Location: login.php");
    exit();
}

$hod_id = $_SESSION['hod_id'];
$message = "";

// Fetch HOD profile details
$stmt = $conn->prepare("SELECT name, email,  FROM hods WHERE id = ?");
$stmt->bind_param("ssi", $hod_id);
$stmt->execute();
$stmt->bind_result($name, $email, $profile_pic);
$stmt->fetch();
$stmt->close();

// Handle profile update (name, email, profile picture)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update_profile'])) {
        $new_name = trim($_POST['name']);
        $new_email = trim($_POST['email']);
        
        // Handle profile picture upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $upload_dir = 'uploads/';
            $file_name = basename($_FILES['profile_pic']['name']);
            $target_file = $upload_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check file type and size
            if (in_array($file_type, ['jpg', 'jpeg', 'png']) && $_FILES['profile_pic']['size'] <= 500000) {
                move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
                $profile_pic = $file_name; // Set new profile picture name
            } else {
                $message = "❌ Invalid file type or file is too large.";
            }
        }

        // Update name, email, and profile picture in the database
        $update = $conn->prepare("UPDATE hods SET name = ?, email = ?, profile_pic = ? WHERE id = ?");
        $update->bind_param("sssi", $new_name, $new_email, $profile_pic, $hod_id);
        $update->execute();

        if ($update->affected_rows > 0) {
            $message = "✅ Profile updated successfully.";
            $name = $new_name;
            $email = $new_email;
        } else {
            $message = "⚠️ No changes made.";
        }
        $update->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HOD Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            background: white;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            color: green;
        }

        .profile-pic {
            display: block;
            margin: 20px auto;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007bff;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>👤 HOD Profile</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <!-- Profile Picture -->
        <img src="uploads/<?= $profile_pic ?>" alt="Profile Picture" class="profile-pic" />
        
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label>Profile Picture (optional)</label>
        <input type="file" name="profile_pic" accept="image/*">

        <button type="submit" name="update_profile">Update Profile</button>
    </form>

    <a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>
