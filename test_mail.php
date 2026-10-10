<?php
require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER; // full debug output
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rentreminder011@gmail.com';
    $mail->Password   = 'jnhtppinnisrunrl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = 30;

    $mail->setFrom('rentreminder011@gmail.com', 'Ramadia Test');
    $mail->addAddress('rentreminder011@gmail.com'); // send to self as test

    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email from Ramadia Rent Reminder System.';

    $mail->send();
    echo '<p style="color:green;font-size:16px;">✅ Email sent successfully!</p>';
} catch (Exception $e) {
    echo '<p style="color:red;font-size:16px;">❌ Failed: ' . htmlspecialchars($mail->ErrorInfo) . '</p>';
}
