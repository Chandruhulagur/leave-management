<?php
session_start();
include('../db.php');

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

if (!isset($_SESSION['hod_id'])) {
    header("Location: login.php");
    exit();
}

// Handle approval or rejection
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'] === 'approve' ? 'Approved' : 'Rejected';

    // Update status in DB
    $update = "UPDATE leave_applications SET status = '$action' WHERE id = $id";
    $conn->query($update);

    // Get student info
    $leave_sql = "SELECT s.name, s.email, la.leave_type, la.from_date, la.to_date, la.reason
                  FROM leave_applications la
                  JOIN students s ON la.student_id = s.id
                  WHERE la.id = $id";
    $result = $conn->query($leave_sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        sendEmailToStudent(
            $row['email'],
            $row['name'],
            $action,
            $row['leave_type'],
            $row['from_date'],
            $row['to_date'],
            $row['reason']
        );
    }

    header("Location: leave_requests.php?msg=" . $action);
    exit();
}

// Fetch pending leave applications
$sql = "SELECT la.*, s.name, s.email FROM leave_applications la
        JOIN students s ON la.student_id = s.id
        WHERE la.status = 'Pending'
        ORDER BY la.applied_on DESC";

$result = $conn->query($sql);

function sendEmailToStudent($email, $name, $status, $type, $from, $to, $reason) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';         // 🔒 Replace with sender email
        $mail->Password = 'your-app-password';            // 🔒 Replace with app-specific password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'HOD - Leave Portal');  // 🔒 Replace as needed
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Your Leave Application has been $status";

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; font-size: 15px; color: #333; line-height: 1.6;'>
                <p>Dear $name,</p>

                <p>Your leave application has been <strong>$status</strong> by the Head of Department.</p>

                <table style='border-collapse: collapse; width: 100%; max-width: 600px; margin-top: 15px;'>
                    <tr>
                        <td style='padding: 8px; font-weight: bold;'>Leave Type:</td>
                        <td style='padding: 8px;'>$type</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; font-weight: bold;'>From Date:</td>
                        <td style='padding: 8px;'>$from</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; font-weight: bold;'>To Date:</td>
                        <td style='padding: 8px;'>$to</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; font-weight: bold;'>Reason:</td>
                        <td style='padding: 8px;'>$reason</td>
                    </tr>
                </table>

                <p>Thank you for using the leave portal.</p>

                <p>Best Regards,<br>Head of Department<br>Student Leave Management System</p>
            </div>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Leave Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
        }

        h2 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f7f7f7;
            color: #333;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
        }

        .btn-approve {
            background-color: #28a745;
        }

        .btn-reject {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .msg {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background: #e1f3e8;
            color: #2e7d32;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Pending Leave Requests</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg">Leave request <?= htmlspecialchars($_GET['msg']) ?> successfully.</div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Leave Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($leave = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($leave['name']) ?></td>
                        <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                        <td><?= htmlspecialchars($leave['from_date']) ?></td>
                        <td><?= htmlspecialchars($leave['to_date']) ?></td>
                        <td><?= htmlspecialchars($leave['reason']) ?></td>
                        <td>
                            <a href="?action=approve&id=<?= $leave['id'] ?>" class="btn btn-approve">Approve</a>
                            <a href="?action=reject&id=<?= $leave['id'] ?>" class="btn btn-reject">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending leave requests.</p>
    <?php endif; ?>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>