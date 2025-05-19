<?php
session_start();
include('../db.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch leave applications for this student
$sql = "SELECT * FROM leave_applications WHERE student_id = $student_id ORDER BY applied_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History | Student Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            padding: 20px;
        }
        
        .history-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .history-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .history-header i {
            margin-right: 10px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #eee;
        }
        
        .table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }
        
        .status-approved {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        
        .status-rejected {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        .no-records {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-records i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #adb5bd;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            margin-top: 25px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .back-link i {
            margin-right: 5px;
        }
        
        .days-count {
            font-weight: 500;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="history-container">
        <div class="history-header">
            <h2><i class="fas fa-history"></i> Your Leave History</h2>
        </div>
        
        <div class="table-responsive">
            <?php if ($result->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Date Range</th>
                            <th>Duration</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($leave = $result->fetch_assoc()): 
                            $start_date = new DateTime($leave['from_date']);
                            $end_date = new DateTime($leave['to_date']);
                            $duration = $start_date->diff($end_date)->days + 1;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                                <td>
                                    <?= date("M d, Y", strtotime($leave['from_date'])) ?><br>
                                    <small>to</small><br>
                                    <?= date("M d, Y", strtotime($leave['to_date'])) ?>
                                </td>
                                <td class="days-count"><?= $duration ?> day<?= $duration > 1 ? 's' : '' ?></td>
                                <td><?= htmlspecialchars($leave['reason']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($leave['status']) ?>">
                                        <?= htmlspecialchars($leave['status']) ?>
                                    </span>
                                </td>
                                <td><?= date("M d, Y, h:i A", strtotime($leave['applied_on'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-records">
                    <i class="fas fa-inbox"></i>
                    <h3>No Leave Records Found</h3>
                    <p>You haven't applied for any leave yet.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>