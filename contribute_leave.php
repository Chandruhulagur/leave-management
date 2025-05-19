<?php
session_start();
include('../db.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['student_id'];

// Fetch current user's leave balance
$balance_sql = "SELECT leave_balance FROM students WHERE id = ?";
$stmt = $conn->prepare($balance_sql);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$stmt->bind_result($leave_balance);
$stmt->fetch();
$stmt->close();

// Handle contribution submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient_id = intval($_POST['recipient_id']);
    $amount = floatval($_POST['amount']);
    $note = htmlspecialchars($_POST['note']);

    if ($amount > 0 && $amount <= $leave_balance) {
        // Begin transaction
        $conn->begin_transaction();

        try {
            // Deduct from sender
            $stmt1 = $conn->prepare("UPDATE students SET leave_balance = leave_balance - ? WHERE id = ?");
            $stmt1->bind_param("di", $amount, $current_user_id);
            $stmt1->execute();

            // Add to recipient
            $stmt2 = $conn->prepare("UPDATE students SET leave_balance = leave_balance + ? WHERE id = ?");
            $stmt2->bind_param("di", $amount, $recipient_id);
            $stmt2->execute();

            // Log the contribution
            $stmt3 = $conn->prepare("INSERT INTO leave_contributions (sender_id, recipient_id, amount, note, date) VALUES (?, ?, ?, ?, NOW())");
            $stmt3->bind_param("iids", $current_user_id, $recipient_id, $amount, $note);
            $stmt3->execute();

            $conn->commit();
            $message = "✅ Leave successfully contributed!";
            $leave_balance -= $amount; // update for UI
        } catch (Exception $e) {
            $conn->rollback();
            $message = "❌ Error: Could not complete the transaction.";
        }
    } else {
        $message = "⚠️ Invalid contribution amount.";
    }
}

// Fetch other students for dropdown
$students = $conn->query("SELECT id, name FROM students WHERE id != $current_user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribute Leave | Student Portal</title>
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
            --warning-color: #ffc107;
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
        
        .contribution-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .contribution-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .contribution-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .balance-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }
        
        .balance-card h3 {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .balance-card p {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0;
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
            min-height: 100px;
            resize: vertical;
        }
        
        .btn-contribute {
            background-color: var(--success-color);
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 8px;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-contribute:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .alert-message {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
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
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
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
    </style>
</head>
<body>
    <div class="contribution-container">
        <div class="contribution-header">
            <h2><i class="fas fa-hand-holding-heart me-2"></i>Contribute Leave</h2>
            <p>Share your leave days with fellow students</p>
        </div>
        
        <div class="balance-card">
            <h3>Your Available Leave Balance</h3>
            <p><?= $leave_balance ?> days</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert-message <?= strpos($message, '✅') !== false ? 'alert-success' : (strpos($message, '⚠️') !== false ? 'alert-warning' : 'alert-danger') ?>">
                <i class="fas <?= strpos($message, '✅') !== false ? 'fa-check-circle' : (strpos($message, '⚠️') !== false ? 'fa-exclamation-triangle' : 'fa-times-circle') ?>"></i>
                <?= str_replace(['✅', '❌', '⚠️'], '', $message) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="recipient_id" class="form-label">Recipient</label>
                <select class="form-select" id="recipient_id" name="recipient_id" required>
                    <option value="">-- Select a student --</option>
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['id'] ?> - <?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="amount" class="form-label">Amount to Contribute (days)</label>
                <input type="number" class="form-control" id="amount" name="amount" min="0.5" step="0.5" max="<?=3.0 ?>" required>
                <small class="text-muted">Maximum: <?= $leave_balance ?> days</small>
            </div>
            
            <div class="mb-4">
                <label for="note" class="form-label">Note (Optional)</label>
                <textarea class="form-control" id="note" name="note" placeholder="Add a personal message..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-contribute">
                <i class="fas fa-paper-plane me-2"></i> Submit Contribution
            </button>
        </form>
        
        <div class="back-link">
            <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const amountInput = document.getElementById('amount');
            const maxBalance = <?= $leave_balance ?>;
            
            form.addEventListener('submit', function(e) {
                const amount = parseFloat(amountInput.value);
                
                if (amount <= 0) {
                    alert('Please enter a positive amount');
                    e.preventDefault();
                    return;
                }
                
                if (amount > maxBalance) {
                    alert(`You cannot contribute more than your available balance (${maxBalance} days)`);
                    e.preventDefault();
                    return;
                }
            });
        });
    </script>
</body>
</html>