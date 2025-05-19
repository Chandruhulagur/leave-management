<?php
include('../db.php');

if (isset($_POST['register'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $photo    = $_FILES['photo']['name'];
    $tmp      = $_FILES['photo']['tmp_name'];
    $photoPath = "../uploads/" . $photo;

    if (move_uploaded_file($tmp, $photoPath)) {
        $sql = "INSERT INTO students (name, email, password, photo) VALUES ('$name', '$email', '$password', '$photo')";
        if ($conn->query($sql)) {
            header("Location: register.php?success=1");
            exit();
        } else {
            header("Location: register.php?error=" . urlencode($conn->error));
            exit();
        }
    } else {
        header("Location: register.php?error=Photo upload failed.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Registration | University Leave System</title>

    <!-- Google Fonts + Bootstrap 5 + FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --primary-color: #6200ea;
        --primary-hover: #3700b3;
        --success-color: #2ecc71;
        --error-color: #e74c3c;
        --bg-color: #f4f6f9;
    }

    body {
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(-45deg, #e0c3fc, #8ec5fc, #fcb69f, #f6d365, #a1c4fd, #c2e9fb);
        background-size: 400% 400%;
        animation: gradientMove 5s ease infinite;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        overflow: hidden;
        position: relative;
    }

    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }

        10% {
            background-position: 25% 75%;
        }

        20% {
            background-position: 50% 50%;
        }

        30% {
            background-position: 75% 25%;
        }

        40% {
            background-position: 100% 50%;
        }

        50% {
            background-position: 75% 75%;
        }

        60% {
            background-position: 50% 100%;
        }

        70% {
            background-position: 25% 75%;
        }

        80% {
            background-position: 50% 50%;
        }

        90% {
            background-position: 75% 25%;
        }

        100% {
            background-position: 0% 50%;
        }
    }


    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.85);
    }

    .registration-container {
        position: relative;
        z-index: 2;
        background: #ffffff;
        padding: 2.5rem;
        max-width: 500px;
        width: 100%;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        opacity: 0.8;
        transform: scale(.85) translateY(-1.8rem) translateX(0.15rem);
    }

    .btn-register {
        background-color: var(--primary-color);
        color: white;
        padding: 0.75rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-register:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
    }

    .custom-file-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border: 2px dashed #ccc;
        border-radius: 0.5rem;
        cursor: pointer;
        background-color: #f9f9f9;
        transition: border-color 0.3s, background-color 0.3s;
    }

    .custom-file-upload:hover {
        border-color: var(--primary-color);
        background-color: #f1f1f1;
    }

    .file-input {
        display: none;
    }

    .file-info {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    .alert {
        font-size: 0.95rem;
    }

    .registration-footer {
        text-align: center;
        margin-top: 1.5rem;
    }

    .registration-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .registration-footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="registration-container">
        <div class="text-center mb-4">
            <svg width="360" height="80" xmlns="http://www.w3.org/2000/svg">
                <text x="0" y="50" font-family="Arial" font-size="42" fill="#6200ea">Equi</text>
                <text x="100" y="50" font-family="Arial" font-size="42" fill="#f06292">Leave</text>
            </svg>
            <h4 class="fw-bold">Employee Registration</h4>
            <p class="text-muted">Access your university leave system</p>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Registered successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error: <?= htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" novalidate>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
                <label for="name"><i class="fas fa-user me-2"></i> Full Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                <label for="email"><i class="fas fa-envelope me-2"></i> Email Address</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
                <label for="password"><i class="fas fa-lock me-2"></i> Password</label>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Employee Photo</label>
                <label for="photo" class="custom-file-upload" id="uploadLabel">
                    <i class="fas fa-camera me-2"></i>
                    <span id="file-name">Choose a photo...</span>
                </label>
                <input type="file" id="photo" name="photo" class="file-input" required>
                <p class="file-info">Upload a passport-size photo (max 2MB)</p>
            </div>

            <button type="submit" name="register" class="btn btn-register w-100">
                <i class="fas fa-user-plus me-2"></i> Register
            </button>
        </form>

        <div class="registration-footer mt-4">
            <p>Already have an account? <a href="login.php">Login here</a></p>
            <p><a href="../index.php"><i class="fas fa-arrow-left me-1"></i> Back to Home</a></p>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('photo').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose a photo...';
        document.getElementById('file-name').textContent = fileName;
    });
    </script>
</body>

</html>