<?php
include('db.php');

$message = "";

if (isset($_GET['token']) && isset($_GET['role'])) {
    $token = $_GET['token'];
    $role = $_GET['role'];
    $table = ($role == 'student') ? 'students' : 'hods';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE $table SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ? AND reset_token_expiry < NOW()");
        $stmt->bind_param("ss", $new_password, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "✅ Password reset successful. <a href='index.php'>home page</a>";
        } else {
            $message = "❌ Invalid or expired token.";
        }
    }

} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 50px; }
        .container { max-width: 400px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; margin-top: 20px; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; }
        .message { margin-top: 15px; text-align: center; color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>
    <form method="post">
        <label>New Password</label>
        <input type="password" name="new_password" required>

        <button type="submit">Reset Password</button>
    </form>
    <div class="message"><?= $message ?></div>
</div>
</body>
</html>
