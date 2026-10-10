<?php
// ============================================================
// SEND PAYMENT RECEIPT EMAIL — Ramadia Rent Reminder System
// Called via POST from index.php after marking a payment paid.
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ── Read POST body ────────────────────────────────────────────
$data = json_decode(file_get_contents('php://input'), true);

$to_email    = trim($data['email']        ?? '');
$to_name     = trim($data['full_name']    ?? '');
$unit        = trim($data['unit_number']  ?? '');
$amount      = number_format((float)($data['amount_due'] ?? 0), 2);
$month       = trim($data['billing_label'] ?? $data['billing_month'] ?? '');
$date_paid   = trim($data['paid_date']    ?? date('Y-m-d'));

if (!$to_email || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing email address.']);
    exit;
}

// ── Build email ───────────────────────────────────────────────
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rentreminder011@gmail.com';
    $mail->Password   = 'jnhtppinnisrunrl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->SMTPDebug  = 0; // set to 2 for verbose debug
    $mail->Timeout    = 30;

    $mail->setFrom('rentreminder011@gmail.com', 'Ramadia Boarding House');
    $mail->addAddress($to_email, $to_name);

    $mail->isHTML(true);
    $mail->Subject = "Payment Confirmed — {$month} Rent Receipt | Ramadia Boarding House";
    $mail->Body    = "
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width,initial-scale=1.0'>
<title>Payment Receipt</title>
</head>
<body style='margin:0;padding:0;background:#f0f4f8;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif;'>
  <table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f4f8;padding:40px 20px;'>
    <tr><td align='center'>
      <table width='100%' style='max-width:560px;background:#0c1728;border-radius:12px;overflow:hidden;border:1px solid #162a45;'>

        <!-- Header -->
        <tr>
          <td style='background:#00b8de;padding:28px 35px;text-align:center;'>
            <div style='font-size:22px;font-weight:800;color:#070d18;letter-spacing:0.5px;'>
              🏠 Ramadia Boarding House
            </div>
            <div style='font-size:12px;color:#070d18;margin-top:4px;opacity:0.8;'>
              Odiong, Roxas, Oriental Mindoro
            </div>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style='padding:35px;'>
            <div style='font-size:28px;font-weight:800;color:#ffffff;margin-bottom:6px;'>
              Payment Confirmed ✅
            </div>
            <div style='font-size:13px;color:#8da4be;margin-bottom:28px;'>
              Your rent payment has been received and recorded.
            </div>

            <!-- Receipt Box -->
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
                <td style='padding:14px 18px;font-size:13px;color:#ffffff;font-weight:700;'>" . htmlspecialchars($month) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;background:#091220;'>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;'>Amount Paid</td>
                <td style='padding:14px 18px;font-size:15px;color:#00b8de;font-weight:800;'>₱{$amount}</td>
              </tr>
              <tr>
                <td style='padding:14px 18px;font-size:12px;color:#8da4be;'>Date Paid</td>
                <td style='padding:14px 18px;font-size:13px;color:#28a745;font-weight:700;'>" . htmlspecialchars($date_paid) . "</td>
              </tr>
            </table>

            <div style='background:rgba(40,167,69,0.1);border:1px solid rgba(40,167,69,0.3);border-radius:8px;padding:16px 18px;font-size:12px;color:#8da4be;line-height:1.6;'>
              This is an official payment confirmation from Ramadia Boarding House.
              Please keep this email as your receipt. If you have any concerns,
              contact us at <a href='mailto:support@ramadiaboardingshouse.ph'
              style='color:#00b8de;text-decoration:none;'>support@ramadiaboardingshouse.ph</a>.
            </div>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style='background:#070d18;border-top:1px solid #162a45;padding:18px 35px;text-align:center;'>
            <div style='font-size:11px;color:#8da4be;'>
              © 2026 Ramadia Boarding House · Odiong, Roxas, Oriental Mindoro
            </div>
            <div style='font-size:11px;color:#8da4be;margin-top:4px;'>
              Helpline: +63 912 345 6789 · support@ramadiaboardingshouse.ph
            </div>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>";

    $mail->AltBody = "Payment Confirmed — Ramadia Boarding House\n\n"
        . "Tenant: {$to_name}\n"
        . "Unit: {$unit}\n"
        . "Billing Period: {$month}\n"
        . "Amount Paid: ₱{$amount}\n"
        . "Date Paid: {$date_paid}\n\n"
        . "Thank you for your payment!\n"
        . "Ramadia Boarding House | Odiong, Roxas, Oriental Mindoro";

    $mail->send();
    echo json_encode(['success' => true, 'message' => "Receipt sent to {$to_email}"]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $mail->ErrorInfo]);
}
