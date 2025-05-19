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
