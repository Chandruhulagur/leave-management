<?php
session_start();
include('db.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$message = "";

if (isset($_POST['apply'])) {
    $leave_type = $_POST['leave_type'];
    $from_date  = $_POST['from_date'];
    $to_date    = $_POST['to_date'];
    $reason     = $conn->real_escape_string($_POST['reason']);

    $sql = "INSERT INTO leave_applications (student_id, leave_type, from_date, to_date, reason)
            VALUES ('$student_id', '$leave_type', '$from_date', '$to_date', '$reason')";

    if ($conn->query($sql)) {
        $message = "✅ Leave application submitted successfully.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>
<?php
include('db.php');



$student_id = $_SESSION['student_id'];
$message = "";

// Fetch student details
$student_query = $conn->query("SELECT name, email FROM students WHERE id = '$student_id'");
$student = $student_query->fetch_assoc();

if (isset($_POST['apply'])) {
    $leave_type = $_POST['leave_type'];
    $from_date  = $_POST['from_date'];
    $to_date    = $_POST['to_date'];
    $reason     = $conn->real_escape_string($_POST['reason']);

    $sql = "INSERT INTO leave_applications (student_id, leave_type, from_date, to_date, reason)
            VALUES ('$student_id', '$leave_type', '$from_date', '$to_date', '$reason')";

    if ($conn->query($sql)) {
        // Email to manager
        $to      = "example@gmail.com";
        
        mail($to, $subject, $email_body, $headers);

        $message = "✅ Leave application submitted successfully.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php'; // Adjust path if needed

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'example@gmail.com';         // Your Gmail address
    $mail->Password   = 'your app password';            // Use Gmail App Password, not regular password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('example@gmail.com', 'Mailer Test');
    $mail->addAddress('example@gmail.com', 'Test User'); // Who to send to

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test Email';
    $mail->Body    = '<strong>This is a test email sent using PHPMailer!</strong>';
    $mail->AltBody = 'This is a plain-text version of the email.';

    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave | Student Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .leave-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .leave-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .leave-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .leave-header p {
            color: #6c757d;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #495057;
        }
        
        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 8px;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .alert-message {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-message i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .back-link i {
            margin-right: 5px;
        }
        
        .date-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
    </style>
</head>
<body>
    <div class="leave-container">
        <div class="leave-header">
            <h2><i class="fas fa-paper-plane me-2"></i>Apply for Leave</h2>
            <p>Fill out the form to submit your leave application</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert-message <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?>">
                <i class="fas <?= strpos($message, '✅') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                <?= str_replace(['✅', '❌'], '', $message) ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label for="leave_type" class="form-label">Leave Type</label>
                <select class="form-select" id="leave_type" name="leave_type" required>
                    <option value="">Select Leave Type</option>
                    <option value="Casual">Casual Leave</option>
                    <option value="Sick">Sick Leave</option>
                    <option value="Earned">Earned Leave</option>
                </select>
            </div>
            
            <div class="mb-3 date-inputs">
                <div>
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" required>
                </div>
                <div>
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="reason" class="form-label">Reason for Leave</label>
                <textarea class="form-control" id="reason" name="reason" placeholder="Please provide details about your leave request..." required></textarea>
            </div>
            
            <button type="submit" name="apply" class="btn btn-submit">
                <i class="fas fa-paper-plane me-2"></i> Submit Leave Request
            </button>
        </form>
        
        <div class="back-link">
            <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date for date inputs to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('from_date').min = today;
            document.getElementById('to_date').min = today;
            
            // Update to_date min when from_date changes
            document.getElementById('from_date').addEventListener('change', function() {
                document.getElementById('to_date').min = this.value;
            });
        });
    </script>
<script>
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');

    function validateDateRange() {
        const fromDate = new Date(fromDateInput.value);
        const toDate = new Date(toDateInput.value);

        if (fromDate && toDate) {
            const timeDiff = toDate - fromDate;
            const dayDiff = timeDiff / (1000 * 3600 * 24);

            if (dayDiff < 3 || dayDiff > 5) {
                alert("Date range must be between 3 and 5 days.");
                toDateInput.value = '';
                return false;
            }
        }
        return true;
    }

    fromDateInput.addEventListener('change', validateDateRange);
    toDateInput.addEventListener('change', validateDateRange);
</script>

</body>
</html>
