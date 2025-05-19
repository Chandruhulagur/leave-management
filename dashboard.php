<?php
session_start();
include('../db.php');

// Fetch student info
$student_id = $_SESSION['student_id'];
$sql = "SELECT * FROM students WHERE id = $student_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch leave history
$history_sql = "SELECT * FROM leave_applications WHERE student_id = $student_id ORDER BY from_date DESC LIMIT 5";
$history_result = $conn->query($history_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Leave Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --sidebar-width: 280px;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-profile {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 15px;
        }
        
        .sidebar-profile h4 {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        
        .sidebar-profile p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid white;
        }
        
        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 30px;
            background-color: #f8f9fc;
        }
        
        /* Dashboard Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-title h1 {
            font-size: 1.8rem;
            color: var(--dark-text);
            margin-bottom: 5px;
        }
        
        .dashboard-title p {
            color: #6c757d;
        }
        
        /* Dashboard Content */
        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        /* Student Details Card */
        .student-details-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
        }
        
        .student-details-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .student-details-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #eee;
        }
        
        .student-info h3 {
            margin-bottom: 5px;
            color: var(--dark-text);
        }
        
        .student-info p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .student-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-card h4 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: var(--primary-color);
        }
        
        .stat-card p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        /* Leave History Card */
        .leave-history-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
        }
        
        .card-title {
            font-size: 1.3rem;
            color: var(--dark-text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .leave-history-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .leave-history-table th {
            text-align: left;
            padding: 12px 15px;
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
        }
        
        .leave-history-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .status-approved {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }
        
        .status-rejected {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        .view-all {
            text-align: right;
            margin-top: 15px;
        }
        
        .view-all a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .view-all a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-brand">
              <svg width="400" height="100" xmlns="http://www.w3.org/2000/svg">
    <g fill="none" stroke="none" stroke-width="1">
        <!-- Leaf Icon -->
        <path d="M20,50 C25,30, 45,35, 40,20 C55,35, 55,70, 30,60 C10,55, 5,40, 20,50 Z" fill="#FF6F61" />
        
        <!-- Text -->
        <text x="60" y="60" font-family="Arial" font-size="38" fill="#253D68">Equi</text>
        <text x="160" y="60" font-family="Arial" font-size="38" fill="#F07C91">Leave</text>
    </g>
</svg>
        </div>
        
        <div class="sidebar-profile">
            <img src="../uploads/<?= $user['photo'] ?>" alt="Profile Photo">
            <h4><?= htmlspecialchars($user['name']) ?></h4>
            <p>Student</p>
        </div>
        
        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apply_leave.php">
                        <i class="fas fa-fw fa-paper-plane"></i>
                        <span>Apply for Leave</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leave_balance.php">
                        <i class="fas fa-fw fa-clock"></i>
                        <span>Leave Balance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leave_history.php">
                        <i class="fas fa-fw fa-history"></i>
                        <span>Leave History</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contribute_leave.php">
                        <i class="fas fa-fw fa-hand-holding-heart"></i>
                        <span>Contribute Leave</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-fw fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div id="content">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Student Dashboard</h1>
                <p>Welcome back, <?= htmlspecialchars($user['name']) ?>!</p>
            </div>
        </div>
        
        <div class="dashboard-content">
            <!-- Student Details Section -->
            <div class="student-details-card">
                <div class="student-details-header">
                    <img src="../uploads/<?= $user['photo'] ?>" alt="Student Photo">
                    <div class="student-info">
                        <h3><?= htmlspecialchars($user['name']) ?></h3>
                        <p><?= htmlspecialchars($user['email']) ?></p>
                        <p>Student ID: <?= htmlspecialchars($user['id'] ?? 'N/A') ?></p>
                    </div>
                </div>
                
                <div class="student-stats">
                    <div class="stat-card">
                        <h4>12</h4>
                        <p>Total Leaves</p>
                    </div>
                    <div class="stat-card">
                        <h4>8</h4>
                        <p>Leaves Taken</p>
                    </div>
                    <div class="stat-card">
                        <h4>4</h4>
                        <p>Leaves Remaining</p>
                    </div>
                    <div class="stat-card">
                        <h4>2</h4>
                        <p>Pending Requests</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Leave History Section -->
            <div class="leave-history-card">
                <h3 class="card-title"><i class="fas fa-history"></i> Recent Leave History</h3>
                
                <table class="leave-history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                            <?php while($leave = $history_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($leave['from_date'])) ?> - <?= date('M d, Y', strtotime($leave['to_date'])) ?></td>
                                    <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                                    <td><?= htmlspecialchars($leave['reason']) ?></td>
                                    <td>
                                        <?php 
                                            $status_class = '';
                                            if ($leave['status'] == 'approved') {
                                                $status_class = 'status-approved';
                                            } elseif ($leave['status'] == 'rejected') {
                                                $status_class = 'status-rejected';
                                            } else {
                                                $status_class = 'status-pending';
                                            }
                                        ?>
                                        <span class="status-badge <?= $status_class ?>">
                                            <?= ucfirst($leave['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No leave history found</td>
                            </tr>
                    </tbody>
                </table>
                
                <div class="view-all">
                    <a href="leave_history.php">View All History →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>