<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave Management System | University Name</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-blue: #2c3e50;
            --secondary-blue: #3498db;
            --accent-green: #2ecc71;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .hero-section {
            padding: 100px 0;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 152, 219, 0.9) 100%);
            color: white;
            margin-bottom: 60px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }
        
        .system-name {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .system-description {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 30px;
        }
        
        .access-cards {
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }
        
        .access-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
            margin-bottom: 30px;
        }
        
        .access-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: var(--primary-blue);
            color: white;
            padding: 20px;
            text-align: center;
            font-weight: 600;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .user-icon {
            font-size: 3rem;
            color: var(--secondary-blue);
            margin-bottom: 20px;
        }
        
        .btn-login {
            background: var(--secondary-blue);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .btn-login:hover {
            background: #2980b9;
        }
        
        .btn-register {
            background: white;
            color: var(--secondary-blue);
            border: 2px solid var(--secondary-blue);
            padding: 10px 20px;
            font-weight: 500;
            width: 100%;
        }
        
        .btn-register:hover {
            background: var(--secondary-blue);
            color: white;
        }
        
        .features-section {
            padding: 80px 0;
            background: white;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--secondary-blue);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        footer {
            background: var(--primary-blue);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-logo img {
            height: 50px;
            margin-bottom: 20px;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--secondary-blue);
            transform: translateY(-3px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-blue);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <svg width="400" height="100" xmlns="http://www.w3.org/2000/svg">
    <g fill="none" stroke="none" stroke-width="1">
        <!-- Leaf Icon -->
        <path d="M20,50 C25,30, 45,35, 40,20 C55,35, 55,70, 30,60 C10,55, 5,40, 20,50 Z" fill="#FF6F61" />
        
        <!-- Text -->
        <text x="60" y="60" font-family="Arial" font-size="48" fill="red">Equi</text>
        <text x="160" y="60" font-family="Arial" font-size="48" fill="#F07C91">Leave</text>
    </g>
</svg>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="system-name animate__animated animate__fadeInDown">Employee Leave Management System</h1>
            <p class="system-description animate__animated animate__fadeIn animate__delay-1s">
                Streamlining leave requests and approvals for faculty and staff at University Name. 
                Our system provides a seamless experience for managing all types of leave applications.
            </p>
        </div>
    </section>

    <!-- Access Cards -->
    <div class="container access-cards">
        <div class="row justify-content-center">
            <!-- Student Card -->
            <div class="col-md-5 animate__animated animate__fadeInLeft">
                <div class="access-card">
                    <div class="card-header">
                        Employee Portal
                    </div>
                    <div class="card-body text-center">
                        <div class="user-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <p>Access the Employee portal to submit leave requests and track their status.</p>
                        <a href="student/login.php" class="btn btn-login">Employee Login</a>
                        <a href="student/register.php" class="btn btn-register">New Employees Registration</a>
                    </div>
                </div>
            </div>

            <!-- HOD Card -->
            <div class="col-md-5 animate__animated animate__fadeInRight">
                <div class="access-card">
                    <div class="card-header">
                        Manager Portal
                    </div>
                    <div class="card-body text-center">
                        <div class="user-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <p>Department heads can review and approve leave requests through the Manager portal.</p>
                        <a href="hod/login.php" class="btn btn-login" style="background: var(--accent-green);">Manager Login</a>
                        <a href="hod/register.php" class="btn btn-register" style="border-color: var(--accent-green); color: var(--accent-green);">Manager Registration</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container text-center">
            <h2 class="mb-5">System Features</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="feature-title">Real-time Tracking</h4>
                    <p>Track your leave requests in real-time with instant status updates and notifications.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4 class="feature-title">Mobile Friendly</h4>
                    <p>Access the system from any device, anywhere, at any time.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h4 class="feature-title">Analytics Dashboard</h4>
                    <p>Comprehensive reports and analytics for better leave management.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2>About Our System</h2>
                    <p class="lead">The Employee Leave Management System is designed to simplify and automate the leave application process for both students and Manager members.</p>
                    <p>Our platform ensures transparency, efficiency, and convenience in managing all types of leave requests, approvals, and records. The system is developed with the latest web technologies to provide a seamless user experience.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="img-fluid rounded shadow" alt="About our system">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container text-center">
            <div class="footer-logo">
                 <svg width="400" height="100" xmlns="http://www.w3.org/2000/svg">
    <g fill="none" stroke="none" stroke-width="1">
        <!-- Leaf Icon -->
        <path d="M20,50 C25,30, 45,35, 40,20 C55,35, 55,70, 30,60 C10,55, 5,40, 20,50 Z" fill="#FF6F61" />
        
        <!-- Text -->
        <text x="60" y="60" font-family="Arial" font-size="48" fill="red">Equi</text>
        <text x="160" y="60" font-family="Arial" font-size="48" fill="#F07C91">Leave</text>
    </g>
</svg>
            </div>
            <h3 class="mb-4">Leave Management System</h3>
            <p class="mb-4">
                <i class="fas fa-map-marker-alt me-2"></i>AGMRCET Varur<br>
                <i class="fas fa-phone me-2"></i> +91 1234567890 <br>
                <i class="fas fa-envelope me-2"></i> info@ac.in
            </p>
            <div class="social-icons mb-4">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Leave Management System. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>