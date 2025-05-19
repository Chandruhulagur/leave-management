<?php
include('db.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $role = $_POST["role"]; // 'student' or 'hod'

    if ($role == "student") {
        $table = "students";
    } elseif ($role == "hod") {
        $table = "hods";
    } else {
        $message = "Invalid role selected.";
    }

    if ($message == "") {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600); // 1 hour

        $stmt = $conn->prepare("UPDATE $table SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Simulated link (you’d email this in production)
            $reset_link = "http://localhost/hacketon4/reset_password.php?token=$token&role=$role";
            $message = "✅ Reset link (simulated): <a href='$reset_link'>Click here to reset</a>";
        } else {
            $message = "❌ Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 50px; }
        .container { max-width: 400px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { text-align: center; }
        input, select { width: 100%; padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; margin-top: 20px; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; }
        .message { margin-top: 15px; text-align: center; color: green; }
        a { color: #007bff; }
    </style>
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>
    <form method="post">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Role</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="hod">HOD</option>
        </select>

        <button type="submit">Send Reset Link</button>
    </form>
    <div class="message"><?= $message ?></div>
</div>
</body>
</html>
