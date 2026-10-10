<?php
// ============================================================
// SEND RENT REMINDER EMAIL — Ramadia Rent Reminder System
// Called via POST from index.php, 3 days before rent due date.
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents('php://input'), true);

$to_email   = trim($data['email']       ?? '');
$to_name    = trim($data['full_name']   ?? '');
$unit       = trim($data['unit_number'] ?? '');
$amount     = number_format((float)($data['amount_due'] ?? 3500), 2);
$due_date   = trim($data['due_date']    ?? '');
$billing    = trim($data['billing']     ?? '');

if (!$to_email || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email.']);
    exit;
}

// Format due date nicely
$due_formatted = $due_date
    ? date('F j, Y', strtotime($due_date))
    : $billing;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rentreminder011@gmail.com';
    $mail->Password   = 'jnhtppinnisrunrl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = 30;
    $mail->SMTPDebug  = 0;

    $mail->setFrom('rentreminder011@gmail.com', 'Ramadia Boarding House');
    $mail->addAddress($to_email, $to_name);

    $mail->isHTML(true);
    $mail->Subject = "Rent Payment Reminder — Due on {$due_formatted} | Ramadia Boarding House";
    $mail->Body    = "
<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'></head>
<body style='margin:0;padding:0;background:#f0f4f8;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif;'>
  <table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f4f8;padding:40px 20px;'>
    <tr><td align='center'>
      <table width='100%' style='max-width:560px;background:#0c1728;border-radius:12px;overflow:hidden;border:1px solid #162a45;'>

        <!-- Header -->
        <tr>
          <td style='background:#f59e0b;padding:28px 35px;text-align:center;'>
            <div style='font-size:22px;font-weight:800;color:#1a1a1a;'>
              🏠 Ramadia Boarding House
            </div>
            <div style='font-size:12px;color:#1a1a1a;margin-top:4px;opacity:0.8;'>
              Odiong, Roxas, Oriental Mindoro
            </div>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style='padding:35px;'>
            <div style='font-size:26px;font-weight:800;color:#ffffff;margin-bottom:6px;'>
              ⏰ Rent Payment Reminder
            </div>
            <div style='font-size:13px;color:#8da4be;margin-bottom:28px;'>
              Hi <strong style='color:#fff;'>" . htmlspecialchars($to_name) . "</strong>, your rent is due in <strong style='color:#f59e0b;'>3 days</strong>. Please prepare your payment.
            </div>

            <!-- Details Box -->
            <table width='100%' cellpadding='0' cellspacing='0'
              style='background:#070d18;border:1px solid #162a45;border-radius:8px;overflow:hidden;margin-bottom:28px;'>
              <tr style='border-bottom:1px solid #162a45;'>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;width:45%;'>Tenant Name</td>
                <td style='padding:14px 18px;font-size:13px;color:#ffffff;font-weight:700;'>" . htmlspecialchars($to_name) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;background:#091220;'>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;'>Unit / Room</td>
                <td style='padding:14px 18px;font-size:13px;color:#ffffff;font-weight:700;'>" . htmlspecialchars($unit) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;'>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;'>Billing Period</td>
                <td style='padding:14px 18px;font-size:13px;color:#ffffff;font-weight:700;'>" . htmlspecialchars($billing) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;background:#091220;'>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;'>Amount Due</td>
                <td style='padding:14px 18px;font-size:15px;color:#f59e0b;font-weight:800;'>₱{$amount}</td>
              </tr>
              <tr>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;'>Due Date</td>
                <td style='padding:14px 18px;font-size:13px;color:#ff6b7a;font-weight:700;'>" . htmlspecialchars($due_formatted) . "</td>
              </tr>
            </table>

            <div style='background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:8px;padding:16px 18px;font-size:12px;color:#8da4be;line-height:1.6;'>
              Please settle your rent on or before the due date to avoid late penalties.
              For payment concerns, contact us at
              <a href='mailto:support@ramadiaboardingshouse.ph' style='color:#00b8de;text-decoration:none;'>support@ramadiaboardingshouse.ph</a>
              or call <strong style='color:#fff;'>+63 912 345 6789</strong>.
            </div>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style='background:#070d18;border-top:1px solid #162a45;padding:18px 35px;text-align:center;'>
            <div style='font-size:11px;color:#8da4be;'>
              © 2026 Ramadia Boarding House · Odiong, Roxas, Oriental Mindoro
            </div>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>";

    $mail->AltBody = "Rent Payment Reminder — Ramadia Boarding House\n\n"
        . "Hi {$to_name},\n\n"
        . "Your rent payment is due in 3 days.\n\n"
        . "Unit: {$unit}\n"
        . "Billing Period: {$billing}\n"
        . "Amount Due: ₱{$amount}\n"
        . "Due Date: {$due_formatted}\n\n"
        . "Please settle your payment on or before the due date.\n\n"
        . "Ramadia Boarding House | +63 912 345 6789";

    $mail->send();
    echo json_encode(['success' => true, 'message' => "Reminder sent to {$to_email}"]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $mail->ErrorInfo]);
}
