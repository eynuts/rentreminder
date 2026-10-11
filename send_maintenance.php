<?php
// ============================================================
// SEND MAINTENANCE UPDATE EMAIL — Ramadia Rent Reminder System
// Called via POST from index.php when a ticket status changes.
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

$to_email      = trim($data['email']         ?? '');
$to_name       = trim($data['full_name']      ?? '');
$unit          = trim($data['unit_number']    ?? '');
$category      = trim($data['category']       ?? '');
$description   = trim($data['description']    ?? '');
$status        = trim($data['status']         ?? 'Resolved');
$date_reported = trim($data['date_reported']  ?? '');
$date_resolved = trim($data['date_resolved']  ?? date('Y-m-d'));

if (!$to_email || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email.']);
    exit;
}

// Status config
$statusColor  = $status === 'Resolved'    ? '#28a745' : ($status === 'In Progress' ? '#00b8de' : '#ffc107');
$statusBg     = $status === 'Resolved'    ? 'rgba(40,167,69,0.15)' : ($status === 'In Progress' ? 'rgba(0,184,222,0.15)' : 'rgba(255,193,7,0.15)');
$statusBorder = $status === 'Resolved'    ? 'rgba(40,167,69,0.4)'  : ($status === 'In Progress' ? 'rgba(0,184,222,0.4)'  : 'rgba(255,193,7,0.4)');
$headerColor  = $status === 'Resolved'    ? '#28a745' : ($status === 'In Progress' ? '#00b8de' : '#ffc107');
$emoji        = $status === 'Resolved'    ? '✅' : ($status === 'In Progress' ? '🔧' : '⏳');
$subjectLabel = $status === 'Resolved'    ? 'Maintenance Request Completed' : "Maintenance Update: {$status}";

$date_reported_fmt = $date_reported ? date('F j, Y', strtotime($date_reported)) : '—';
$date_resolved_fmt = $date_resolved ? date('F j, Y', strtotime($date_resolved)) : '—';

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
    $mail->Subject = "{$emoji} {$subjectLabel} | Ramadia Boarding House";
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
          <td style='background:{$headerColor};padding:28px 35px;text-align:center;'>
            <div style='font-size:22px;font-weight:800;color:#fff;'>
              🏠 Ramadia Boarding House
            </div>
            <div style='font-size:12px;color:#fff;margin-top:4px;opacity:0.85;'>
              Odiong, Roxas, Oriental Mindoro
            </div>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style='padding:35px;'>
            <div style='font-size:26px;font-weight:800;color:#ffffff;margin-bottom:6px;'>
              {$emoji} {$subjectLabel}
            </div>
            <div style='font-size:13px;color:#8da4be;margin-bottom:28px;'>
              Hi <strong style='color:#fff;'>" . htmlspecialchars($to_name) . "</strong>, here is an update on your maintenance request.
            </div>

            <!-- Status Badge -->
            <div style='text-align:center;margin-bottom:24px;'>
              <span style='background:{$statusBg};color:{$statusColor};border:1px solid {$statusBorder};padding:8px 24px;border-radius:20px;font-size:14px;font-weight:800;letter-spacing:0.5px;'>
                {$emoji} " . htmlspecialchars($status) . "
              </span>
            </div>

            <!-- Details Box -->
            <table width='100%' cellpadding='0' cellspacing='0'
              style='background:#070d18;border:1px solid #162a45;border-radius:8px;overflow:hidden;margin-bottom:24px;'>
              <tr style='border-bottom:1px solid #162a45;'>
                <td style='padding:13px 18px;font-size:12px;color:#8da4be;width:40%;'>Tenant</td>
                <td style='padding:13px 18px;font-size:13px;color:#ffffff;font-weight:700;'>" . htmlspecialchars($to_name) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;background:#091220;'>
                <td style='padding:13px 18px;font-size:12px;color:#8da4be;'>Unit / Room</td>
                <td style='padding:13px 18px;font-size:13px;color:#ffffff;font-weight:700;'>" . htmlspecialchars($unit) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;'>
                <td style='padding:13px 18px;font-size:12px;color:#8da4be;'>Category</td>
                <td style='padding:13px 18px;font-size:13px;color:#00b8de;font-weight:700;'>" . htmlspecialchars($category) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;background:#091220;'>
                <td style='padding:13px 18px;font-size:12px;color:#8da4be;'>Description</td>
                <td style='padding:13px 18px;font-size:13px;color:#ffffff;'>" . htmlspecialchars($description) . "</td>
              </tr>
              <tr style='border-bottom:1px solid #162a45;'>
                <td style='padding:13px 18px;font-size:12px;color:#8da4be;'>Date Reported</td>
                <td style='padding:13px 18px;font-size:13px;color:#ffffff;'>{$date_reported_fmt}</td>
              </tr>
              <tr>
                <td style='padding:13px 18px;font-size:12px;color:#8da4be;'>Date Resolved</td>
                <td style='padding:13px 18px;font-size:13px;color:{$statusColor};font-weight:700;'>{$date_resolved_fmt}</td>
              </tr>
            </table>

            <div style='background:rgba(0,184,222,0.08);border:1px solid rgba(0,184,222,0.2);border-radius:8px;padding:16px 18px;font-size:12px;color:#8da4be;line-height:1.6;'>
              If you have further concerns about this issue or need to report a new one, please contact us at
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

    $mail->AltBody = "{$subjectLabel} — Ramadia Boarding House\n\n"
        . "Hi {$to_name},\n\n"
        . "Your maintenance request has been updated.\n\n"
        . "Status: {$status}\n"
        . "Category: {$category}\n"
        . "Description: {$description}\n"
        . "Unit: {$unit}\n"
        . "Date Reported: {$date_reported_fmt}\n"
        . "Date Resolved: {$date_resolved_fmt}\n\n"
        . "Ramadia Boarding House | +63 912 345 6789";

    $mail->send();
    echo json_encode(['success' => true, 'message' => "Update sent to {$to_email}"]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $mail->ErrorInfo]);
}
