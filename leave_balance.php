<?php
session_start();
include('../db.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student leave balance from the database
$sql = "SELECT casual_leave, sick_leave, earned_leave FROM students WHERE id = $student_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch the number of leaves the student has already taken
$leave_sql = "SELECT leave_type, SUM(DATEDIFF(to_date, from_date) + 1) AS total_days
              FROM leave_applications
              WHERE student_id = $student_id AND status = 'Approved'
              GROUP BY leave_type";
$leave_result = $conn->query($leave_sql);

$used_leave = [
    'Casual' => 0,
    'Sick' => 0,
    'Earned' => 0
];

while ($leave = $leave_result->fetch_assoc()) {
    $used_leave[$leave['leave_type']] = $leave['total_days'];
}

// Calculate remaining leaves
$remaining_leave = [
    'Casual' => $user['casual_leave'] - $used_leave['Casual'],
    'Sick' => $user['sick_leave'] - $used_leave['Sick'],
    'Earned' => $user['earned_leave'] - $used_leave['Earned']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Balance | Student Portal</title>
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
        
        .balance-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .balance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .balance-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .balance-header i {
            margin-right: 10px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .balance-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .balance-table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #eee;
        }
        
        .balance-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .balance-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .leave-type {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .leave-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .casual-icon {
            background-color: #3498db;
        }
        
        .sick-icon {
            background-color: #e74c3c;
        }
        
        .earned-icon {
            background-color: #2ecc71;
        }
        
        .progress-container {
            width: 100%;
            height: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            border-radius: 5px;
        }
        
        .casual-progress {
            background-color: #3498db;
        }
        
        .sick-progress {
            background-color: #e74c3c;
        }
        
        .earned-progress {
            background-color: #2ecc71;
        }
        
        .remaining-days {
            font-weight: 600;
            color: var(--success-color);
        }
        
        .used-days {
            font-weight: 500;
            color: #6c757d;
        }
        
        .initial-days {
            font-weight: 500;
            color: #495057;
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
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-data i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #adb5bd;
        }
    </style>
</head>
<body>
    <div class="balance-container">
        <div class="balance-header">
            <h2><i class="fas fa-calendar-alt"></i> Your Leave Balance</h2>
        </div>
        
        <div class="table-responsive">
            <table class="balance-table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Initial Balance</th>
                        <th>Used</th>
                        <th>Remaining</th>
                        <th>Utilization</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="leave-type">
                                <div class="leave-icon casual-icon">
                                    <i class="fas fa-umbrella-beach"></i>
                                </div>
                                <span>Casual Leave</span>
                            </div>
                        </td>
                        <td class="initial-days"><?= $user['casual_leave'] ?> days</td>
                        <td class="used-days"><?= $used_leave['Casual'] ?> days</td>
                        <td class="remaining-days"><?= $remaining_leave['Casual'] ?> days</td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar casual-progress" 
                                     style="width: <?= ($used_leave['Casual'] / $user['casual_leave']) * 100 ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="leave-type">
                                <div class="leave-icon sick-icon">
                                    <i class="fas fa-procedures"></i>
                                </div>
                                <span>Sick Leave</span>
                            </div>
                        </td>
                        <td class="initial-days"><?= $user['sick_leave'] ?> days</td>
                        <td class="used-days"><?= $used_leave['Sick'] ?> days</td>
                        <td class="remaining-days"><?= $remaining_leave['Sick'] ?> days</td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar sick-progress" 
                                     style="width: <?= ($used_leave['Sick'] / $user['sick_leave']) * 100 ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="leave-type">
                                <div class="leave-icon earned-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <span>Earned Leave</span>
                            </div>
                        </td>
                        <td class="initial-days"><?= $user['earned_leave'] ?> days</td>
                        <td class="used-days"><?= $used_leave['Earned'] ?> days</td>
                        <td class="remaining-days"><?= $remaining_leave['Earned'] ?> days</td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar earned-progress" 
                                     style="width: <?= ($used_leave['Earned'] / $user['earned_leave']) * 100 ?>%"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>