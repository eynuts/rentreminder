<?php
// ============================================================
// LOGIN HANDLER — Ramadia Rent Reminder System
// Accepts POST from the login form, verifies credentials,
// starts a session, and redirects to index.php.
// ============================================================

session_start();

// If already logged in, go straight to the portal
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter your email and password.';
    } else {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare(
            "SELECT user_id, full_name, role, password_hash, unit_id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Valid credentials — store minimal info in session
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['unit_id']   = $user['unit_id'];

            header('Location: index.php');
            exit;
        } else {
            // Generic message — don't reveal whether email or password was wrong
            $error = 'Invalid email or password. Please try again.';
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Ramadia Boarding House</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background-color: #070d18; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .card { width: 100%; max-width: 420px; background-color: #0c1728; border-radius: 12px; padding: 40px; border: 1px solid #162a45; }
        .logo { display: flex; align-items: center; gap: 10px; color: #00b8de; font-size: 20px; font-weight: 800; margin-bottom: 8px; }
        .subtitle { font-size: 12px; color: #8da4be; margin-bottom: 30px; }
        .role-toggle { display: flex; background-color: #070d18; border-radius: 6px; padding: 3px; margin-bottom: 25px; border: 1px solid #162a45; }
        .role-btn { flex: 1; padding: 8px; text-align: center; font-size: 12px; font-weight: 600; color: #8da4be; border-radius: 4px; cursor: pointer; border: none; background: transparent; }
        .role-btn.active { background-color: #0c1728; color: #ffffff; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 12px; color: #8da4be; margin-bottom: 8px; }
        .input-field { position: relative; }
        .input-field input { width: 100%; background: transparent; border: none; border-bottom: 1px solid #243c5a; padding: 8px 30px 8px 0; color: #ffffff; font-size: 13px; outline: none; }
        .input-field i { position: absolute; right: 0; top: 50%; transform: translateY(-50%); color: #00b8de; font-size: 13px; }
        .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 12px; }
        .form-options label { color: #8da4be; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .form-options a { color: #00b8de; text-decoration: none; }
        .submit-btn { width: 100%; background-color: #00b8de; color: #070d18; border: none; border-radius: 6px; padding: 12px; font-size: 14px; font-weight: 700; cursor: pointer; }
        .submit-btn:hover { background-color: #0098b8; }
        .error-msg { background-color: rgba(220,53,69,0.15); border: 1px solid rgba(220,53,69,0.4); color: #ff6b7a; border-radius: 6px; padding: 10px 14px; font-size: 12px; margin-bottom: 20px; }
        .form-title { color: #00b8de; font-size: 18px; font-weight: 700; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><i class="fa-solid fa-house"></i> Ramadia Boarding House</div>
        <p class="subtitle">Odiong, Roxas, Oriental Mindoro</p>

        <h2 class="form-title" id="formRoleTitle">Tenant Sign In</h2>

        <div class="role-toggle">
            <button class="role-btn active" id="btnTenant" onclick="setRole('Tenant')">Tenant</button>
            <button class="role-btn" id="btnAdmin" onclick="setRole('Landlord / Admin')">Landlord / Admin</button>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label id="lblUser">Email / Tenant ID</label>
                <div class="input-field">
                    <input type="email" name="email" placeholder="" required autocomplete="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fa-solid fa-envelope"></i>
                </div>
            </div>
            <div class="input-group">
                <label>Password</label>
                <div class="input-field">
                    <input type="password" name="password" placeholder="" required autocomplete="current-password">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>
            <div class="form-options">
                <label><input type="checkbox" name="remember" checked> Keep me logged in</label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="submit-btn">Access Portal</button>
        </form>
    </div>

    <script>
        function setRole(role) {
            document.getElementById('btnTenant').classList.toggle('active', role === 'Tenant');
            document.getElementById('btnAdmin').classList.toggle('active', role === 'Landlord / Admin');
            document.getElementById('formRoleTitle').innerText = role + ' Sign In';
            document.getElementById('lblUser').innerText = role === 'Tenant' ? 'Email / Tenant ID' : 'Admin Username / Email';
        }
    </script>
</body>
</html>
