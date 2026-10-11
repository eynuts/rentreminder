<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ramadia Boarding House | Automated Rent Reminders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background-color: #070d18; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 20px; }

        .inner-card { width: 100%; max-width: 1100px; background-color: #0c1728; border-radius: 12px; padding: 35px 45px; box-shadow: 0 10px 30px rgba(0,0,0,0.6); border: 1px solid #162a45; position: relative; margin: auto; }

        /* Header / Nav */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 10px 16px;
            background: #070d18;
            border: 1px solid #162a45;
            border-radius: 10px;
            gap: 12px;
        }
        .nav-links { display: flex; gap: 2px; align-items: center; flex-shrink: 0; }
        .nav-item {
            color: #8da4be;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            padding: 6px 11px;
            border-radius: 6px;
            transition: all 0.2s ease;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        .nav-item:hover { color: #ffffff; background: #0c1728; border-color: #162a45; }
        .nav-item.active { color: #00b8de; background: rgba(0,184,222,0.1); border-color: rgba(0,184,222,0.3); }
        .search-box { position: relative; width: 150px; }
        .search-box input { width: 100%; background-color: #0c1728; border: 1px solid #162a45; border-radius: 6px; padding: 6px 30px 6px 10px; color: #ffffff; font-size: 12px; outline: none; }
        .search-box input:focus { border-color: #00b8de; }
        .search-box i { position: absolute; right: 9px; top: 50%; transform: translateY(-50%); color: #00b8de; font-size: 12px; }

        /* Landing / Dashboard */
        .main-content { display: grid; grid-template-columns: 1.2fr 0.9fr; gap: 40px; align-items: center; }
        .logo { display: flex; align-items: center; gap: 12px; color: #00b8de; font-size: 26px; font-weight: 800; margin-bottom: 35px; }
        .hero-title { color: #ffffff; font-size: 38px; font-weight: 800; line-height: 1.2; margin-bottom: 14px; }
        .hero-title .highlight { color: #00b8de; }
        .hero-text { color: #8da4be; font-size: 14px; line-height: 1.6; max-width: 480px; margin-bottom: 30px; }
        .location-tag { color: #00b8de; font-size: 12px; font-weight: 600; margin-top: -20px; margin-bottom: 25px; display: flex; align-items: center; gap: 6px; }
        .icon-row { display: flex; gap: 18px; }
        .icon-btn { background-color: #070d18; border: 1px solid #162a45; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #00b8de; cursor: pointer; transition: all 0.2s ease; }
        .icon-btn:hover { border-color: #00b8de; background-color: #00b8de; color: #070d18; }

        /* Sign In Card */
        .right-section { background-color: #070d18; padding: 35px; border-radius: 10px; border: 1px solid #162a45; }
        .form-title { color: #00b8de; font-size: 20px; font-weight: 700; text-align: center; margin-bottom: 20px; }
        .role-toggle { display: flex; background-color: #0c1728; border-radius: 6px; padding: 3px; margin-bottom: 25px; border: 1px solid #162a45; }
        .role-btn { flex: 1; padding: 8px; text-align: center; font-size: 12px; font-weight: 600; color: #8da4be; border-radius: 4px; cursor: pointer; border: none; background: transparent; }
        .role-btn.active { background-color: #070d18; color: #ffffff; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 12px; color: #8da4be; margin-bottom: 8px; }
        .input-field { position: relative; }
        .input-field input { width: 100%; background: transparent; border: none; border-bottom: 1px solid #243c5a; padding: 8px 30px 8px 0; color: #ffffff; font-size: 13px; outline: none; }
        .input-field i { position: absolute; right: 0; top: 50%; transform: translateY(-50%); color: #00b8de; font-size: 13px; }
        .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 12px; }
        .form-options label { color: #8da4be; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .form-options a { color: #00b8de; text-decoration: none; }
        .submit-btn { width: 100%; background-color: #00b8de; color: #070d18; border: none; border-radius: 6px; padding: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.2s ease; }
        .submit-btn:hover { background-color: #0098b8; }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .register-link { text-align: center; font-size: 12px; color: #8da4be; margin-top: 18px; }
        .register-link a { color: #00b8de; text-decoration: none; font-weight: 600; cursor: pointer; }

        /* Error / Info messages */
        .error-msg { background-color: rgba(220,53,69,0.15); border: 1px solid rgba(220,53,69,0.4); color: #ff6b7a; border-radius: 6px; padding: 10px 14px; font-size: 12px; margin-bottom: 20px; display: none; }
        .error-msg.show { display: block; }

        /* Views */
        .system-view { display: none; }
        .system-view.active { display: block; }

        /* Tables */
        .logs-header { display: flex; justify-content: space-between; align-items: center; background-color: #070d18; padding: 20px 25px; border-radius: 8px 8px 0 0; border: 1px solid #162a45; border-bottom: none; }
        .logs-header h3 { font-size: 18px; color: #ffffff; display: flex; align-items: center; gap: 10px; }
        .logs-header h3 i { color: #00b8de; }
        .logs-header p { font-size: 12px; color: #8da4be; margin-top: 4px; }
        .action-bar { display: flex; gap: 10px; align-items: center; }
        .action-btn { background-color: #122138; color: #ffffff; border: 1px solid #243c5a; padding: 8px 14px; border-radius: 4px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .action-btn.primary { background-color: #00b8de; color: #070d18; font-weight: 700; border: none; }
        .report-type-select { background-color: #091220; color: #00b8de; border: 1px solid #162a45; padding: 8px 12px; border-radius: 4px; font-size: 12px; font-weight: 700; outline: none; }
        .report-type-select option, select option { background-color: #0c1728; color: #ffffff; }
        select { background-color: #091220; color: #ffffff; }
        .logs-table-wrapper { background-color: #070d18; border-radius: 0 0 8px 8px; border: 1px solid #162a45; overflow-x: auto; overflow-y: auto; max-height: 55vh; margin-bottom: 20px; }
        .logs-table-wrapper::-webkit-scrollbar { width: 5px; height: 5px; }
        .logs-table-wrapper::-webkit-scrollbar-track { background: #0c1728; }
        .logs-table-wrapper::-webkit-scrollbar-thumb { background: #243c5a; border-radius: 4px; }
        .logs-table-wrapper::-webkit-scrollbar-thumb:hover { background: #00b8de; }
        .logs-table thead th { position: sticky; top: 0; z-index: 1; }
        .logs-table { width: 100%; border-collapse: collapse; font-size: 12px; text-align: left; }
        .logs-table th { background-color: #091220; color: #8da4be; padding: 12px 16px; font-weight: 700; border-bottom: 1px solid #162a45; text-transform: uppercase; }
        .logs-table td { padding: 12px 16px; border-bottom: 1px solid #162a45; color: #ffffff; }
        .editable-input { background: rgba(22,42,69,0.4); border: 1px solid #162a45; color: #ffffff; padding: 6px 10px; border-radius: 4px; width: 100%; font-size: 12px; outline: none; }
        .editable-input:focus { border-color: #00b8de; background: #091220; }
        .status-select { background: #091220; border: 1px solid #162a45; color: #00b8de; padding: 6px; border-radius: 4px; font-size: 11px; font-weight: 700; }
        .status-badge-sent { background-color: rgba(0,184,222,0.15); color: #00b8de; border: 1px solid rgba(0,184,222,0.4); padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }
        .status-badge-delivered { background-color: rgba(40,167,69,0.15); color: #28a745; border: 1px solid rgba(40,167,69,0.4); padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }

        /* Summary Cards */
        .report-summary-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 15px; margin-bottom: 20px; }
        .summary-card { background-color: #070d18; border: 1px solid #162a45; padding: 18px; border-radius: 8px; }
        .summary-card h4 { font-size: 12px; color: #8da4be; margin-bottom: 6px; }
        .summary-card input { font-size: 20px; font-weight: 800; color: #00b8de; background: transparent; border: none; width: 100%; outline: none; }

        /* Print Header */
        .print-header { display: none; text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #000; }
        .print-header h1 { font-size: 24px; text-transform: uppercase; font-weight: 900; color: #000; letter-spacing: 1px; }
        .print-header p { font-size: 12px; color: #333; line-height: 1.4; }
        .formal-meta-grid { display: none; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; font-size: 12px; color: #000; }
        .formal-meta-grid table { width: 100%; }
        .formal-meta-grid td { padding: 4px 0; }

        /* Signature */
        .signature-section { display: none; margin-top: 30px; justify-content: flex-end; padding-right: 20px; }
        .signature-box { text-align: center; width: 260px; }
        .signature-line { border-top: 1px solid #00b8de; margin-top: 50px; padding-top: 6px; }
        .signature-name { font-size: 13px; font-weight: 800; color: #ffffff; }
        .signature-title { font-size: 11px; color: #8da4be; }

        /* Support */
        .support-box { background-color: #070d18; border-radius: 8px; border: 1px solid #162a45; padding: 25px; margin-bottom: 20px; }
        .support-title { display: flex; align-items: center; gap: 10px; font-size: 20px; color: #ffffff; margin-bottom: 6px; }
        .support-title i { color: #00b8de; }
        .support-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .support-card { background-color: #091220; border-radius: 6px; border: 1px solid #162a45; padding: 20px; }
        .support-card h4 { font-size: 14px; color: #00b8de; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .support-card ul { list-style: none; font-size: 12px; color: #8da4be; line-height: 1.8; }
        .support-card ul li strong { color: #ffffff; }

        /* Maintenance */
        .maintenance-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start; }
        .maintenance-card { background-color: #070d18; border: 1px solid #162a45; border-radius: 8px; padding: 25px; display: flex; flex-direction: column; max-height: 65vh; }
        .maintenance-card h4 { font-size: 16px; color: #00b8de; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .maintenance-form-group { margin-bottom: 15px; flex-shrink: 0; }
        .maintenance-form-group label { display: block; font-size: 12px; color: #8da4be; margin-bottom: 6px; }
        .maintenance-form-group input, .maintenance-form-group select, .maintenance-form-group textarea { width: 100%; background-color: #0c1728; border: 1px solid #162a45; border-radius: 6px; padding: 10px; color: #ffffff; font-size: 13px; outline: none; }
        .maintenance-form-group textarea { resize: none; height: 90px; }
        .maintenance-list { display: flex; flex-direction: column; gap: 12px; overflow-y: auto; flex: 1; padding-right: 4px; }
        .maintenance-list::-webkit-scrollbar { width: 5px; }
        .maintenance-list::-webkit-scrollbar-track { background: #0c1728; border-radius: 4px; }
        .maintenance-list::-webkit-scrollbar-thumb { background: #243c5a; border-radius: 4px; }
        .maintenance-list::-webkit-scrollbar-thumb:hover { background: #00b8de; }
        .ticket-item { background-color: #091220; border: 1px solid #162a45; border-radius: 6px; padding: 15px; display: flex; justify-content: space-between; align-items: flex-start; }
        .ticket-info h5 { font-size: 14px; color: #ffffff; margin-bottom: 4px; }
        .ticket-info p { font-size: 12px; color: #8da4be; margin-bottom: 6px; }
        .ticket-meta { font-size: 11px; color: #00b8de; }
        .ticket-status { padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }
        .status-pending { background-color: rgba(255,193,7,0.15); color: #ffc107; border: 1px solid rgba(255,193,7,0.4); }
        .status-progress { background-color: rgba(0,184,222,0.15); color: #00b8de; border: 1px solid rgba(0,184,222,0.4); }
        .status-resolved { background-color: rgba(40,167,69,0.15); color: #28a745; border: 1px solid rgba(40,167,69,0.4); }

        /* Modals */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.75); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-overlay.active { display: flex; }
        .modal-box { background-color: #0c1728; border: 1px solid #162a45; border-radius: 10px; width: 100%; max-width: 480px; padding: 30px; position: relative; }
        .modal-close { position: absolute; right: 20px; top: 20px; color: #8da4be; font-size: 18px; cursor: pointer; }
        .modal-title { font-size: 18px; font-weight: 700; color: #ffffff; margin-bottom: 6px; display: flex; align-items: center; gap: 10px; }
        .modal-title i { color: #00b8de; }
        .modal-desc { font-size: 12px; color: #8da4be; margin-bottom: 20px; }
        .modal-form-group { margin-bottom: 16px; }
        .modal-form-group label { display: block; font-size: 12px; color: #8da4be; margin-bottom: 6px; }
        .modal-form-group input { width: 100%; background-color: #070d18; border: 1px solid #162a45; border-radius: 4px; padding: 10px; color: #ffffff; font-size: 13px; outline: none; }

        /* Loading spinner */
        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid #070d18; border-top-color: transparent; border-radius: 50%; animation: spin 0.7s linear infinite; vertical-align: middle; margin-right: 6px; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Empty state */
        .empty-row td { text-align: center; color: #8da4be; padding: 30px !important; }

        @media print {
            body { background: #ffffff !important; color: #000000 !important; }
            .header, .search-box, .icon-row, .action-bar, .nav-links, .action-btn, .right-section, .status-select, th:last-child, td:last-child { display: none !important; }
            .inner-card { border: none !important; box-shadow: none !important; background: #ffffff !important; padding: 0 !important; max-width: 100% !important; }
            .print-header { display: block !important; }
            .formal-meta-grid { display: block !important; }
            .signature-section { display: flex !important; }
            .signature-line { border-top: 1px solid #000 !important; }
            .signature-name { color: #000 !important; }
            .signature-title { color: #333 !important; }
            .summary-card { border: 1px solid #000 !important; background: #ffffff !important; }
            .summary-card h4 { color: #333 !important; }
            .summary-card input { color: #000 !important; }
            .logs-table-wrapper { border: 1px solid #000 !important; background: #ffffff !important; max-height: none !important; overflow: visible !important; }
            .logs-table th { background: #f0f0f0 !important; color: #000 !important; border-bottom: 2px solid #000 !important; position: static !important; }
            .logs-table td { color: #000 !important; border-bottom: 1px solid #ccc !important; }
            .editable-input { background: transparent !important; border: none !important; color: #000 !important; padding: 0 !important; }
            .dueDateInfoBar, #dueDateInfoBar { display: none !important; }
            .report-summary-cards { display: grid !important; }
        }
    </style>
</head>
<body>
<div class="inner-card">

    <!-- Printable Formal Header -->
    <div class="print-header">
        <h1>Ramadia Boarding House</h1>
        <p>Odiong, Roxas, Oriental Mindoro</p>
        <p style="margin-top:6px;font-weight:bold;font-size:14px;text-decoration:underline;" id="printReportTitle">FORMAL MONTHLY RENT FINANCIAL REPORT</p>
    </div>
    <div class="formal-meta-grid">
        <table>
            <tr>
                <td><strong>Property Owner:</strong> Medelyn Y. Lumbres</td>
                <td style="text-align:right;"><strong>Date Generated:</strong> <span id="printDate"></span></td>
            </tr>
            <tr>
                <td><strong>Document Type:</strong> Official Financial Statement</td>
                <td style="text-align:right;"><strong>Status:</strong> Validated Soft Copy</td>
            </tr>
        </table>
    </div>

    <!-- Nav — hidden until logged in -->
    <div class="header" id="mainNav" style="display:none;">
        <div class="nav-links">
            <a class="nav-item active" id="navDashboard" onclick="switchTab('dashboard')">Dashboard</a>
            <a class="nav-item" id="navTenants"      onclick="switchTab('tenants')">Tenants</a>
            <a class="nav-item" id="navReminders"    onclick="switchTab('reminders')">Reminders</a>
            <a class="nav-item" id="navReports"      onclick="switchTab('reports')">Reports</a>
            <a class="nav-item" id="navReportsTenant" onclick="switchTab('myPayment')" style="display:none;">My Payment</a>
            <a class="nav-item" id="navMaintenance"  onclick="switchTab('maintenance')">Maintenance</a>
            <a class="nav-item" id="navSupport"      onclick="switchTab('support')">Support</a>
        </div>

        <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
            <!-- Welcome pill -->
            <div style="display:flex;align-items:center;gap:7px;background:#0c1728;border:1px solid #162a45;border-radius:8px;padding:5px 10px;max-width:170px;">
                <i class="fa-solid fa-circle-user" style="color:#00b8de;font-size:15px;flex-shrink:0;"></i>
                <div style="line-height:1.25;min-width:0;">
                    <div style="font-size:9px;color:#8da4be;white-space:nowrap;text-transform:uppercase;letter-spacing:0.5px;">Logged in as</div>
                    <div style="font-size:12px;font-weight:700;color:#ffffff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" id="navUserName"></div>
                </div>
            </div>

            <!-- Divider -->
            <div style="width:1px;height:22px;background:#162a45;flex-shrink:0;"></div>

            <!-- Search -->
            <div class="search-box">
                <input type="text" id="globalSearch" placeholder="Search..." oninput="handleSearch(this.value)">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

            <!-- Settings — icon only -->
            <a href="#" onclick="openModal('modalSettings')" title="Settings"
                style="display:flex;align-items:center;justify-content:center;width:30px;height:30px;color:#8da4be;text-decoration:none;background:#0c1728;border:1px solid #162a45;border-radius:6px;flex-shrink:0;transition:all 0.2s;"
                onmouseover="this.style.color='#fff';this.style.borderColor='#243c5a';"
                onmouseout="this.style.color='#8da4be';this.style.borderColor='#162a45';">
                <i class="fa-solid fa-gear" style="font-size:12px;"></i>
            </a>

            <!-- Logout -->
            <a href="#" onclick="doLogout()"
                style="display:flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#ff6b7a;text-decoration:none;white-space:nowrap;background:rgba(220,53,69,0.08);border:1px solid rgba(220,53,69,0.25);border-radius:6px;padding:6px 10px;flex-shrink:0;transition:all 0.2s;"
                onmouseover="this.style.background='rgba(220,53,69,0.18)';"
                onmouseout="this.style.background='rgba(220,53,69,0.08)';">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <!-- ===================== VIEW: LANDING / LOGIN ===================== -->
    <div id="viewDashboard" class="system-view active">
        <div class="main-content">
            <div class="left-section">
                <div class="logo"><i class="fa-solid fa-house"></i><span>Ramadia Boarding House</span></div>
                <div class="location-tag"><i class="fa-solid fa-location-dot"></i><span>Odiong, Roxas, Oriental Mindoro | Owner: Medelyn Y. Lumbres</span></div>
                <h1 class="hero-title">Never Miss a Payment.<br><span class="highlight">Automated Rent Reminders.</span></h1>
                <p class="hero-text">Stay on top of your monthly rent, track due dates, and receive automated SMS and email notifications before your payment is due. Designed for effortless small-scale property management.</p>
                <div class="icon-row">
                    <div class="icon-btn" title="Reminders"   onclick="switchTab('reminders')"><i class="fa-solid fa-bell"></i></div>
                    <div class="icon-btn" title="Maintenance" onclick="switchTab('maintenance')"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                    <div class="icon-btn" title="Support"     onclick="switchTab('support')"><i class="fa-solid fa-circle-question"></i></div>
                </div>
            </div>

            <!-- Login card -->
            <div class="right-section" id="loginCard">
                <h2 class="form-title" id="formRoleTitle">Tenant Sign In</h2>
                <div class="role-toggle">
                    <button class="role-btn active" id="btnTenant" onclick="setRole('Tenant')">Tenant</button>
                    <button class="role-btn" id="btnAdmin" onclick="setRole('Landlord / Admin')">Landlord / Admin</button>
                </div>
                <div class="error-msg" id="loginError"><i class="fa-solid fa-circle-exclamation"></i> <span id="loginErrorText"></span></div>
                <div class="input-group">
                    <label id="lblUser">Email / Tenant ID</label>
                    <div class="input-field">
                        <input type="email" id="loginEmail" placeholder="" autocomplete="email">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <div class="input-field">
                        <input type="password" id="loginPassword" placeholder="" autocomplete="current-password">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <div class="form-options">
                    <label><input type="checkbox" id="rememberMe" checked> Keep me logged in</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button class="submit-btn" id="loginBtn" onclick="doLogin()">Access Portal</button>
                <div class="register-link">New tenant? <a onclick="openModal('modalRegister')">Register Unit / Quick Access</a></div>
            </div>

            <!-- Logged-in welcome (replaces login card) -->
            <div class="right-section" id="welcomeCard" style="display:none;align-items:center;justify-content:center;flex-direction:column;gap:10px;text-align:center;">
                <i class="fa-solid fa-circle-check" style="font-size:40px;color:#28a745;"></i>
                <p style="color:#fff;font-weight:700;font-size:16px;">You are logged in</p>
                <p style="color:#8da4be;font-size:12px;">Use the navigation above to browse the portal.</p>
            </div>
        </div>
    </div>

    <!-- ===================== VIEW: TENANTS ===================== -->
    <div id="viewTenants" class="system-view">
        <div class="logs-header">
            <div>
                <h3><i class="fa-solid fa-users"></i> Tenant Records</h3>
                <p>Registered tenant names, contact numbers, email addresses, and assigned rooms.</p>
            </div>
            <div class="action-bar">
                <span class="status-badge-delivered" id="tenantCountBadge">0 Active Tenants</span>
                <button class="action-btn primary" onclick="openModal('modalAddTenant')">
                    <i class="fa-solid fa-user-plus"></i> Add Tenant
                </button>
            </div>
        </div>
        <div class="logs-table-wrapper">
            <table class="logs-table">
                <thead>
                    <tr><th>Tenant Name</th><th>Room No.</th><th>Contact No.</th><th>Email Address</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody id="tenantTableBody">
                    <tr class="empty-row"><td colspan="5">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================== VIEW: MY PAYMENT (tenant only) ===================== -->
    <div id="viewMyPayment" class="system-view">
        <div class="logs-header">
            <div>
                <h3><i class="fa-solid fa-file-invoice-dollar"></i> My Payment Status</h3>
                <p>Your current rent billing and payment status.</p>
            </div>
        </div>
        <div class="logs-table-wrapper">
            <table class="logs-table">
                <thead>
                    <tr><th>Billing Period</th><th>Room</th><th>Amount Due</th><th>Status</th></tr>
                </thead>
                <tbody id="tenantPaymentBody">
                    <tr class="empty-row"><td colspan="4">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================== VIEW: REMINDERS ===================== -->
    <div id="viewReminders" class="system-view">
        <div class="logs-header">
            <div>
                <h3><i class="fa-solid fa-paper-plane"></i> Automated Notification Logs</h3>
                <p>Records of SMS dispatches and email notifications sent to tenants.</p>
            </div>
        </div>
        <div class="logs-table-wrapper">
            <table class="logs-table">
                <thead>
                    <tr><th>Timestamp</th><th>Recipient</th><th>Channel</th><th>Trigger Type</th><th>Message</th><th>Status</th></tr>
                </thead>
                <tbody id="remindersTableBody">
                    <tr class="empty-row"><td colspan="6">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================== VIEW: REPORTS ===================== -->
    <div id="viewReports" class="system-view">
        <div class="logs-header">
            <div>
                <h3><i class="fa-solid fa-file-signature"></i> <span id="reportHeaderTitle">Formal Monthly Rent Financial Report</span></h3>
                <p>Editable &amp; Soft-Copy Printable Statements — Ramadia Boarding House</p>
            </div>
            <div class="action-bar" style="flex-wrap:wrap;gap:8px;">
                <select class="report-type-select" id="reportMode" onchange="toggleReportType()">
                    <option value="monthly">Monthly Financial Report</option>
                    <option value="annual">Annual Financial Summary</option>
                </select>

                <!-- Due Date Schedule -->
                <div style="display:flex;align-items:center;gap:6px;background:#091220;border:1px solid #162a45;border-radius:4px;padding:6px 10px;">
                    <i class="fa-solid fa-calendar-day" style="color:#00b8de;font-size:12px;"></i>
                    <span style="font-size:11px;color:#8da4be;white-space:nowrap;">Due:</span>
                    <select id="dueDateSchedule" onchange="saveDueSchedule()"
                        style="background:transparent;border:none;color:#ffffff;font-size:12px;font-weight:700;outline:none;cursor:pointer;">
                        <option value="end_of_month">End of Month</option>
                        <option value="15th">15th of Month</option>
                        <option value="5th">5th of Month</option>
                        <option value="1st">1st of Month</option>
                    </select>
                </div>

                <button class="action-btn" onclick="sendRemindersNow()" id="sendReminderBtn"
                    style="white-space:nowrap;">
                    <i class="fa-solid fa-bell"></i> Send Reminders
                </button>
                <button class="action-btn primary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
            </div>
        </div>

        <!-- Period Filter Bar -->
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:#091220;border:1px solid #162a45;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
            <i class="fa-solid fa-filter" style="color:#00b8de;font-size:13px;"></i>
            <span style="font-size:12px;color:#8da4be;white-space:nowrap;">Filter by:</span>

            <select id="filterPeriodType" onchange="onFilterTypeChange()"
                style="background:#0c1728;border:1px solid #162a45;color:#fff;font-size:12px;font-weight:700;padding:6px 10px;border-radius:6px;outline:none;cursor:pointer;">
                <option value="all">All Records</option>
                <option value="yearly">Yearly</option>
                <option value="monthly">Monthly</option>
            </select>

            <!-- Year picker — shown for yearly and monthly -->
            <select id="filterYear" onchange="applyReportFilter()" style="display:none;background:#0c1728;border:1px solid #162a45;color:#fff;font-size:12px;padding:6px 10px;border-radius:6px;outline:none;cursor:pointer;"></select>

            <!-- Month picker — shown for monthly only -->
            <select id="filterMonth" onchange="applyReportFilter()" style="display:none;background:#0c1728;border:1px solid #162a45;color:#fff;font-size:12px;padding:6px 10px;border-radius:6px;outline:none;cursor:pointer;">
                <option value="1">January</option><option value="2">February</option>
                <option value="3">March</option><option value="4">April</option>
                <option value="5">May</option><option value="6">June</option>
                <option value="7">July</option><option value="8">August</option>
                <option value="9">September</option><option value="10">October</option>
                <option value="11">November</option><option value="12">December</option>
            </select>

            <span id="filterResultCount" style="font-size:11px;color:#8da4be;margin-left:4px;"></span>

            <button onclick="clearReportFilter()" style="margin-left:auto;background:transparent;border:1px solid #162a45;color:#8da4be;font-size:11px;padding:5px 10px;border-radius:6px;cursor:pointer;">
                <i class="fa-solid fa-xmark"></i> Clear
            </button>
        </div>
        <div class="report-summary-cards">
            <div class="summary-card"><h4>Total Active Tenants</h4><input type="text" id="sumTenants" value="—"></div>
            <div class="summary-card"><h4 id="lblColTitle">Total Monthly Collections</h4><input type="text" id="sumCollections" value="—"></div>
            <div class="summary-card"><h4>Collection Efficiency</h4><input type="text" id="sumEfficiency" value="—"></div>
        </div>

        <!-- Due Date Info Bar -->
        <div id="dueDateInfoBar" style="background:#091220;border:1px solid #162a45;border-radius:8px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <i class="fa-solid fa-calendar-check" style="color:#00b8de;font-size:16px;"></i>
                <div>
                    <div style="font-size:12px;color:#8da4be;">Current Due Date Schedule</div>
                    <div style="font-size:14px;font-weight:700;color:#ffffff;" id="dueDateInfoText">Loading...</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <i class="fa-solid fa-envelope-circle-check" style="color:#ffc107;font-size:14px;"></i>
                <div style="font-size:12px;color:#8da4be;">Reminder emails sent <strong style="color:#ffc107;">3 days before</strong> due date</div>
            </div>
            <div id="nextReminderDate" style="font-size:12px;color:#8da4be;"></div>
        </div>
        <div class="logs-table-wrapper">
            <table class="logs-table" id="reportTable">
                <thead>
                    <tr><th>Tenant Name</th><th>Unit / Room</th><th>Billing Period</th><th>Fee Amount</th><th>Payment Status</th><th>Action</th></tr>
                </thead>
                <tbody id="reportTbody">
                    <tr class="empty-row"><td colspan="6">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="signature-section" style="display:flex;">
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-name">MEDELYN Y. LUMBRES</div>
                    <div class="signature-title">Property Owner / Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== VIEW: MAINTENANCE ===================== -->
    <div id="viewMaintenance" class="system-view">
        <div class="logs-header" style="margin-bottom:20px;border-radius:8px;border-bottom:1px solid #162a45;">
            <div>
                <h3><i class="fa-solid fa-screwdriver-wrench"></i> Property Maintenance &amp; Repair Tracker</h3>
                <p>Report and monitor room repairs, plumbing, electrical issues, and facility upkeep requests.</p>
            </div>
        </div>
        <div class="maintenance-container">
            <div class="maintenance-card">
                <h4><i class="fa-solid fa-circle-plus"></i> Submit Maintenance Ticket</h4>
                <div class="maintenance-form-group">
                    <label>Tenant Name &amp; Unit</label>
                    <input type="text" id="maintTenant" placeholder="e.g., Chariz V. Lualhati (Room 12)">
                </div>
                <div class="maintenance-form-group">
                    <label>Issue Category</label>
                    <select id="maintCategory">
                        <option value="Plumbing">Plumbing / Leaks</option>
                        <option value="Electrical">Electrical / Lighting</option>
                        <option value="Appliance">Appliance / Fixtures</option>
                        <option value="Structural">Structural / Door / Window</option>
                    </select>
                </div>
                <div class="maintenance-form-group">
                    <label>Description of Problem</label>
                    <textarea id="maintDesc" placeholder="Describe the issue clearly..."></textarea>
                </div>
                <button class="submit-btn" onclick="submitMaintenanceTicket()" style="flex-shrink:0;margin-top:auto;padding-top:15px;">Submit Ticket</button>
            </div>
            <div class="maintenance-card">
                <h4><i class="fa-solid fa-list-check"></i> Active Maintenance Logs</h4>
                <div class="maintenance-list" id="maintenanceListContainer">
                    <p style="color:#8da4be;font-size:13px;">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== VIEW: SUPPORT ===================== -->
    <div id="viewSupport" class="system-view">
        <div class="support-box">
            <div class="support-title"><i class="fa-solid fa-circle-question"></i><span>Ramadia Boarding House Support &amp; Help Desk</span></div>
            <p style="font-size:13px;color:#8da4be;">Find answers regarding automated notifications, payment channels, and boarding house regulations in Odiong, Roxas.</p>
            <div class="support-grid">
                <div class="support-card">
                    <h4><i class="fa-solid fa-bolt"></i> Automated Alerts FAQ</h4>
                    <ul>
                        <li><strong>When are SMS sent?</strong> Reminders trigger 3 days prior, on the due date, and daily for overdue accounts.</li>
                        <li><strong>What if I change numbers?</strong> Update your profile — the system uses your latest contact info automatically.</li>
                        <li><strong>Are emails official?</strong> Yes, soft-copy receipts are linked via secure automated notices.</li>
                    </ul>
                </div>
                <div class="support-card">
                    <h4><i class="fa-solid fa-address-book"></i> Direct Landlord Contact</h4>
                    <ul>
                        <li><strong>Property Owner:</strong> Medelyn Y. Lumbres</li>
                        <li><strong>Location:</strong> Odiong, Roxas, Oriental Mindoro</li>
                        <li><strong>Helpline:</strong> +63 912 345 6789</li>
                        <li><strong>Support Email:</strong> support@ramadiaboardingshouse.ph</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div><!-- /.inner-card -->

<!-- Settings / Change Password Modal -->
<div class="modal-overlay" id="modalSettings">
    <div class="modal-box" style="max-width:440px;">
        <i class="fa-solid fa-xmark modal-close" onclick="closeModal('modalSettings')"></i>
        <div class="modal-title"><i class="fa-solid fa-gear"></i> Account Settings</div>
        <div class="modal-desc">Change your password below. You must enter your current password to confirm.</div>

        <div class="modal-form-group">
            <label>Current Password</label>
            <div style="position:relative;">
                <input type="password" id="settingsCurrentPw" placeholder="Enter current password"
                    style="width:100%;background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px 36px 10px 10px;color:#fff;font-size:13px;outline:none;">
                <i class="fa-solid fa-eye" id="toggleCurrentPw" onclick="togglePwVisibility('settingsCurrentPw','toggleCurrentPw')"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#8da4be;cursor:pointer;font-size:13px;"></i>
            </div>
        </div>

        <div class="modal-form-group">
            <label>New Password</label>
            <div style="position:relative;">
                <input type="password" id="settingsNewPw" placeholder="Enter new password"
                    style="width:100%;background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px 36px 10px 10px;color:#fff;font-size:13px;outline:none;">
                <i class="fa-solid fa-eye" id="toggleNewPw" onclick="togglePwVisibility('settingsNewPw','toggleNewPw')"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#8da4be;cursor:pointer;font-size:13px;"></i>
            </div>
        </div>

        <div class="modal-form-group">
            <label>Confirm New Password</label>
            <div style="position:relative;">
                <input type="password" id="settingsConfirmPw" placeholder="Re-enter new password"
                    style="width:100%;background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px 36px 10px 10px;color:#fff;font-size:13px;outline:none;">
                <i class="fa-solid fa-eye" id="toggleConfirmPw" onclick="togglePwVisibility('settingsConfirmPw','toggleConfirmPw')"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#8da4be;cursor:pointer;font-size:13px;"></i>
            </div>
        </div>

        <!-- Strength indicator -->
        <div id="pwStrengthBar" style="height:4px;border-radius:4px;background:#162a45;margin:-8px 0 16px;transition:all 0.3s;">
            <div id="pwStrengthFill" style="height:100%;border-radius:4px;width:0%;transition:width 0.3s,background 0.3s;"></div>
        </div>
        <div id="pwStrengthLabel" style="font-size:11px;color:#8da4be;margin-top:-12px;margin-bottom:16px;"></div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button onclick="closeModal('modalSettings')"
                style="flex:1;background:#122138;color:#fff;border:1px solid #243c5a;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;">
                Cancel
            </button>
            <button onclick="changePassword()"
                style="flex:2;background:#00b8de;color:#070d18;border:none;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;">
                <i class="fa-solid fa-key"></i> Update Password
            </button>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal-overlay" id="modalRegister">
    <div class="modal-box">
        <i class="fa-solid fa-xmark modal-close" onclick="closeModal('modalRegister')"></i>
        <div class="modal-title"><i class="fa-solid fa-user-plus"></i> Tenant Registration</div>
        <div class="modal-desc">Register your unit details to activate automated rent reminders.</div>
        <form onsubmit="event.preventDefault(); window.showToast('Registration submitted successfully!', '✅', 'Registered'); closeModal('modalRegister');">
            <div class="modal-form-group"><label>Full Name</label><input type="text" placeholder="Enter full name" required></div>
            <div class="modal-form-group"><label>Assigned Unit / Room</label><input type="text" placeholder="e.g. Room 12" required></div>
            <div class="modal-form-group"><label>Mobile Number</label><input type="text" placeholder="+63 9XX XXX XXXX" required></div>
            <div class="modal-form-group"><label>Email Address</label><input type="email" placeholder="name@example.com" required></div>
            <button type="submit" class="submit-btn" style="margin-top:10px;">Complete Registration</button>
        </form>
    </div>
</div>

<!-- Mark Paid Modal -->
<div class="modal-overlay" id="modalMarkPaid">
    <div class="modal-box" style="max-width:460px;">
        <i class="fa-solid fa-xmark modal-close" onclick="closeModal('modalMarkPaid')"></i>
        <div class="modal-title"><i class="fa-solid fa-peso-sign"></i> Mark as Paid</div>
        <div class="modal-desc" id="markPaidDesc">Select which months to mark as paid for this tenant.</div>
        <input type="hidden" id="markPaidKey">

        <div style="margin-bottom:16px;">
            <label style="font-size:12px;color:#8da4be;display:block;margin-bottom:8px;">Months to Mark Paid</label>
            <div id="markPaidMonthsContainer" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
        </div>

        <div class="modal-form-group">
            <label>Date Paid</label>
            <input type="date" id="markPaidDate" style="background:#070d18;border:1px solid #162a45;border-radius:6px;padding:10px;color:#fff;font-size:13px;outline:none;width:100%;">
        </div>

        <div style="display:flex;gap:10px;margin-top:20px;">
            <button onclick="closeModal('modalMarkPaid')" style="flex:1;background:#122138;color:#fff;border:1px solid #243c5a;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;">Cancel</button>
            <button onclick="saveMarkPaid()" style="flex:2;background:#28a745;color:#fff;border:none;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;"><i class="fa-solid fa-check"></i> Confirm Payment</button>
        </div>
    </div>
</div>

<!-- Add Tenant Modal -->
<div class="modal-overlay" id="modalAddTenant">
    <div class="modal-box" style="max-width:460px;">
        <i class="fa-solid fa-xmark modal-close" onclick="closeModal('modalAddTenant')"></i>
        <div class="modal-title"><i class="fa-solid fa-user-plus"></i> Add Tenant</div>
        <div class="modal-desc">Fill in the details to register a new tenant. They can log in using their email and password.</div>
        <div class="modal-form-group">
            <label>Full Name</label>
            <input type="text" id="addTenantName" placeholder="e.g. Juan Dela Cruz">
        </div>
        <div class="modal-form-group">
            <label>Room No.</label>
            <input type="text" id="addTenantRoom" placeholder="e.g. Room 12">
        </div>
        <div class="modal-form-group">
            <label>Contact No.</label>
            <input type="text" id="addTenantContact" placeholder="e.g. 09XX XXX XXXX">
        </div>
        <div class="modal-form-group">
            <label>Email Address</label>
            <input type="email" id="addTenantEmail" placeholder="name@example.com">
        </div>
        <div class="modal-form-group">
            <label>Monthly Rent Amount (₱)</label>
            <input type="number" id="addTenantAmount" placeholder="e.g. 3500" min="0" step="0.01"
                style="background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px;color:#fff;font-size:13px;outline:none;width:100%;">
        </div>
        <div class="modal-form-group">
            <label>Password</label>
            <div style="position:relative;">
                <input type="password" id="addTenantPassword" placeholder="Set login password"
                    style="width:100%;background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px 36px 10px 10px;color:#fff;font-size:13px;outline:none;">
                <i class="fa-solid fa-eye" id="toggleAddTenantPw"
                    onclick="togglePwVisibility('addTenantPassword','toggleAddTenantPw')"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#8da4be;cursor:pointer;font-size:13px;"></i>
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:20px;">
            <button onclick="closeModal('modalAddTenant')" style="flex:1;background:#122138;color:#fff;border:1px solid #243c5a;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;">Cancel</button>
            <button onclick="addTenant()" style="flex:2;background:#00b8de;color:#070d18;border:none;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;"><i class="fa-solid fa-user-plus"></i> Add Tenant</button>
        </div>
    </div>
</div>

<!-- Edit Tenant Modal -->
<div class="modal-overlay" id="modalEditTenant">
    <div class="modal-box" style="max-width:460px;">
        <i class="fa-solid fa-xmark modal-close" onclick="closeModal('modalEditTenant')"></i>
        <div class="modal-title"><i class="fa-solid fa-user-pen"></i> Edit Tenant</div>
        <div class="modal-desc">Update tenant information. Changes are saved directly to the database.</div>
        <input type="hidden" id="editTenantKey">
        <div class="modal-form-group">
            <label>Full Name</label>
            <input type="text" id="editTenantName" placeholder="Full name">
        </div>
        <div class="modal-form-group">
            <label>Room No.</label>
            <input type="text" id="editTenantRoom" placeholder="e.g. Room 12">
        </div>
        <div class="modal-form-group">
            <label>Contact No.</label>
            <input type="text" id="editTenantContact" placeholder="e.g. 09XX XXX XXXX">
        </div>
        <div class="modal-form-group">
            <label>Email Address</label>
            <input type="email" id="editTenantEmail" placeholder="name@example.com">
        </div>
        <div class="modal-form-group">
            <label>Monthly Rent Amount (₱)</label>
            <input type="number" id="editTenantAmount" placeholder="e.g. 3500" min="0" step="0.01"
                style="background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px;color:#fff;font-size:13px;outline:none;width:100%;">
        </div>
        <div class="modal-form-group">
            <label>Password <span style="color:#8da4be;font-weight:400;">(current password shown)</span></label>
            <div style="position:relative;">
                <input type="password" id="editTenantPassword" placeholder="Password"
                    style="width:100%;background:#070d18;border:1px solid #162a45;border-radius:4px;padding:10px 36px 10px 10px;color:#fff;font-size:13px;outline:none;">
                <i class="fa-solid fa-eye" id="toggleEditTenantPw"
                    onclick="togglePwVisibility('editTenantPassword','toggleEditTenantPw')"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#8da4be;cursor:pointer;font-size:13px;"></i>
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:20px;">
            <button onclick="closeModal('modalEditTenant')" style="flex:1;background:#122138;color:#fff;border:1px solid #243c5a;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;">Cancel</button>
            <button onclick="saveTenantEdit()" style="flex:2;background:#00b8de;color:#070d18;border:none;border-radius:8px;padding:12px;font-size:14px;font-weight:700;cursor:pointer;">Save Changes</button>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal-overlay" id="modalConfirm">
    <div class="modal-box" style="max-width:400px;text-align:center;padding:40px 35px;border-radius:16px;">
        <div style="width:70px;height:70px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:rgba(220,53,69,0.15);border:2px solid rgba(220,53,69,0.4);">
            <span style="font-size:32px;line-height:1;">⚠️</span>
        </div>
        <div style="font-size:20px;font-weight:800;color:#ffffff;margin-bottom:10px;">Are you sure?</div>
        <p id="modalConfirmMsg" style="font-size:13px;color:#8da4be;line-height:1.6;margin-bottom:28px;"></p>
        <div style="display:flex;gap:12px;justify-content:center;">
            <button onclick="closeModal('modalConfirm')" style="background:#122138;color:#fff;border:1px solid #243c5a;border-radius:8px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;">Cancel</button>
            <button id="modalConfirmOkBtn" style="background:#dc3545;color:#fff;border:none;border-radius:8px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;">Delete</button>
        </div>
    </div>
</div>

<!-- Success / Info Modal -->
<div class="modal-overlay" id="modalSuccess">
    <div class="modal-box" style="max-width:400px;text-align:center;padding:40px 35px;border-radius:16px;">
        <div id="modalSuccessIconWrap" style="width:70px;height:70px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:rgba(40,167,69,0.15);border:2px solid rgba(40,167,69,0.4);">
            <span id="modalSuccessIcon" style="font-size:32px;line-height:1;">✅</span>
        </div>
        <div id="modalSuccessTitle" style="font-size:20px;font-weight:800;color:#ffffff;margin-bottom:10px;">Success</div>
        <p id="modalSuccessMsg" style="font-size:13px;color:#8da4be;line-height:1.6;margin-bottom:28px;"></p>
        <button onclick="closeModal('modalSuccess')" style="background:#00b8de;color:#070d18;border:none;border-radius:8px;padding:12px 40px;font-size:14px;font-weight:700;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#0098b8'" onmouseout="this.style.background='#00b8de'">OK</button>
    </div>
</div>

<!-- ===================== FIREBASE SDK ===================== -->
<script type="module">
import { initializeApp }                         from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getDatabase, ref, get, push, remove, update } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

// ── Firebase config ─────────────────────────────────────────
const firebaseConfig = {
    apiKey:            "AIzaSyAR-LbUZ15KlelASBxldUbLsPlkbAM2ZOg",
    authDomain:        "rentreminder-3d988.firebaseapp.com",
    projectId:         "rentreminder-3d988",
    storageBucket:     "rentreminder-3d988.firebasestorage.app",
    messagingSenderId: "547734009003",
    appId:             "1:547734009003:web:e79e5a536265790534f3ee",
    measurementId:     "G-EZ7FZJK8QS",
    databaseURL:       "https://rentreminder-3d988-default-rtdb.asia-southeast1.firebasedatabase.app/"
};

const app = initializeApp(firebaseConfig);
const db  = getDatabase(app);

// ── Session helpers (localStorage) ──────────────────────────
const SESSION_KEY = 'ramadia_user';

function saveSession(user, remember) {
    const store = remember ? localStorage : sessionStorage;
    store.setItem(SESSION_KEY, JSON.stringify(user));
}

function loadSession() {
    const ls = localStorage.getItem(SESSION_KEY);
    const ss = sessionStorage.getItem(SESSION_KEY);
    if (ls)  return JSON.parse(ls);
    if (ss)  return JSON.parse(ss);
    return null;
}

function clearSession() {
    localStorage.removeItem(SESSION_KEY);
    sessionStorage.removeItem(SESSION_KEY);
}

// ── Boot: check existing session ────────────────────────────
const existingUser = loadSession();
if (existingUser) {
    showLoggedInState(existingUser);
} else {
    showLoginState();
}

// ── UI state ─────────────────────────────────────────────────
function showLoginState() {
    document.getElementById('mainNav').style.display     = 'none';
    document.getElementById('loginCard').style.display   = 'block';
    document.getElementById('welcomeCard').style.display = 'none';
}

function showLoggedInState(user) {
    document.getElementById('mainNav').style.display     = 'flex';
    document.getElementById('loginCard').style.display   = 'none';
    document.getElementById('welcomeCard').style.display = 'flex';
    document.getElementById('navUserName').textContent   = user.full_name || user.email;

    // Role-based nav: tenants only see Reminders, My Payment, Maintenance, Support
    const isAdmin = user.role === 'landlord' || user.role === 'admin';
    document.getElementById('navTenants').style.display    = isAdmin ? '' : 'none';
    document.getElementById('navReports').style.display    = isAdmin ? '' : 'none';
    document.getElementById('navReminders').textContent    = isAdmin ? 'Reminders' : 'My Reminders';
    document.getElementById('navReportsTenant').style.display = isAdmin ? 'none' : '';

    // Pre-fill maintenance tenant field for tenants
    if (!isAdmin) {
        const maintField = document.getElementById('maintTenant');
        if (maintField) maintField.value = `${user.full_name} (${user.unit_number || 'N/A'})`;
        maintField.readOnly = true;
    }

    loadAllData(user);
}

// ── Login ────────────────────────────────────────────────────
window.doLogin = async function() {
    const email      = document.getElementById('loginEmail').value.trim().toLowerCase();
    const password   = document.getElementById('loginPassword').value;
    const remember   = document.getElementById('rememberMe').checked;
    const btn        = document.getElementById('loginBtn');
    const errBox     = document.getElementById('loginError');
    const errText    = document.getElementById('loginErrorText');
    const isAdminTab = document.getElementById('btnAdmin').classList.contains('active');

    errBox.classList.remove('show');

    if (!email || !password) {
        errText.textContent = 'Please enter your email and password.';
        errBox.classList.add('show');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>Signing in...';

    try {
        const snap = await get(ref(db, 'users'));

        if (!snap.exists()) {
            throw new Error('no_user');
        }

        let matchedUser = null;
        snap.forEach(childSnap => {
            const u = childSnap.val();
            if (u.email && u.email.toLowerCase() === email && u.password === password) {
                matchedUser = { key: childSnap.key, ...u };
            }
        });

        if (!matchedUser) {
            throw new Error('wrong_password');
        }

        // Check role matches the selected tab
        const isAdminRole = matchedUser.role === 'landlord' || matchedUser.role === 'admin';
        if (isAdminTab && !isAdminRole) {
            errText.textContent = 'This account is not an admin. Please use the Tenant tab.';
            errBox.classList.add('show');
            btn.disabled = false;
            btn.innerHTML = 'Access Portal';
            return;
        }
        if (!isAdminTab && isAdminRole) {
            errText.textContent = 'This is an admin account. Please use the Landlord / Admin tab.';
            errBox.classList.add('show');
            btn.disabled = false;
            btn.innerHTML = 'Access Portal';
            return;
        }

        saveSession(matchedUser, remember);
        showLoggedInState(matchedUser);

    } catch (err) {
        let msg = 'Invalid email or password. Please try again.';
        if (err.message === 'no_user') msg = 'No accounts found in database. Please run the seeder first.';
        if (err.code === 'auth/network-request-failed' || err.message === 'network') {
            msg = 'Network error. Check your connection.';
        }
        errText.textContent = msg;
        errBox.classList.add('show');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Access Portal';
    }
};

// Allow Enter key to submit
document.getElementById('loginPassword').addEventListener('keydown', e => { if (e.key === 'Enter') window.doLogin(); });
document.getElementById('loginEmail').addEventListener('keydown',    e => { if (e.key === 'Enter') window.doLogin(); });

// ── Logout ───────────────────────────────────────────────────
window.doLogout = function() {
    clearSession();
    showLoginState();
    switchTab('dashboard');
    // Clear rendered tables
    ['tenantTableBody','remindersTableBody','reportTbody'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = '<tr class="empty-row"><td colspan="6">—</td></tr>';
    });
    document.getElementById('maintenanceListContainer').innerHTML = '';
};

// ── Load all data from Realtime DB ───────────────────────────
async function loadAllData(user) {
    const isAdmin = user.role === 'landlord' || user.role === 'admin';
    // Reset filter cache so fresh data is loaded
    window._allPaymentsData = null;
    window._allTenantsData  = null;
    try {
        const [tenantsSnap, paymentsSnap, remindersSnap, maintenanceSnap, usersSnap] = await Promise.all([
            get(ref(db, 'tenants')),
            get(ref(db, 'payments')),
            get(ref(db, 'reminders')),
            get(ref(db, 'maintenance')),
            get(ref(db, 'users'))
        ]);

        // Build full_name → password map from /users (more reliable than email matching)
        const pwMap = {};
        if (usersSnap.exists()) {
            usersSnap.forEach(child => {
                const u = child.val();
                if (u.full_name) pwMap[u.full_name.trim().toLowerCase()] = u.password || '';
            });
        }

        if (isAdmin) {
            // Use /users directly for tenant table (has password + all fields in one place)
            renderTenants(usersSnap.val());
            renderPayments(paymentsSnap.val(), usersSnap.val());
            renderReminders(remindersSnap.val(), usersSnap.val());
            renderMaintenance(maintenanceSnap.val(), usersSnap.val());

            // Load due schedule and auto-check reminders
            const schedule = await loadDueSchedule();
            await checkAndSendReminders(schedule);
        } else {
            // Tenant sees only their own data
            const myName = user.full_name;
            const filterByName = (data) => {
                if (!data) return null;
                const filtered = {};
                Object.entries(data).forEach(([k, v]) => {
                    if ((v.full_name || v.tenant_name || '') === myName) filtered[k] = v;
                });
                return Object.keys(filtered).length ? filtered : null;
            };

            renderTenantMyPayment(filterByName(paymentsSnap.val()), user);
            renderReminders(filterByName(remindersSnap.val()), null);
            renderMaintenance(filterByName(maintenanceSnap.val()), null);
        }
    } catch (err) {
        console.error('loadAllData failed:', err);
        ['tenantTableBody','remindersTableBody','reportTbody'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = `<tr class="empty-row"><td colspan="6" style="color:#ff6b7a;">Failed to load data: ${err.message}</td></tr>`;
        });
    }
}

// ── Render: Tenants ──────────────────────────────────────────
function renderTenants(data, pwMap = {}) {
    const tbody = document.getElementById('tenantTableBody');
    if (!data) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="6">No tenants found.</td></tr>';
        document.getElementById('tenantCountBadge').textContent = '0 Active Tenants';
        return;
    }

    // Filter to tenants only from /users
    const entries = Object.entries(data).filter(([k, u]) => u.role === 'tenant');
    document.getElementById('tenantCountBadge').textContent = entries.length + ' Active Tenants';

    if (!entries.length) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="6">No tenants found.</td></tr>';
        return;
    }

    tbody.innerHTML = entries.map(([key, t]) => `
        <tr>
            <td>${esc(t.full_name || '—')}</td>
            <td>${esc(t.unit_number || '—')}</td>
            <td>${esc(t.mobile_number || '—')}</td>
            <td>${esc(t.email || '—')}</td>
            <td><span class="status-badge-delivered">Active</span></td>
            <td>
                <div style="display:flex;gap:6px;">
                <button onclick="openEditTenantModal('${key}', ${JSON.stringify({
                    full_name:     t.full_name     || '',
                    unit_number:   t.unit_number   || '',
                    mobile_number: t.mobile_number || '',
                    email:         t.email         || '',
                    monthly_rent:  t.monthly_rent  || 0,
                    password:      t.password      || ''
                }).replace(/"/g,'&quot;')})"
                    style="background:rgba(0,184,222,0.12);color:#00b8de;border:1px solid rgba(0,184,222,0.35);padding:5px 12px;border-radius:4px;font-size:11px;font-weight:700;cursor:pointer;">
                    <i class="fa-solid fa-pen"></i> Edit
                </button>
                <button onclick="deleteTenant('${key}', '${esc(t.full_name || '')}')"
                    style="background:rgba(220,53,69,0.12);color:#ff6b7a;border:1px solid rgba(220,53,69,0.35);padding:5px 12px;border-radius:4px;font-size:11px;font-weight:700;cursor:pointer;">
                    <i class="fa-solid fa-trash"></i>
                </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// ── Render: Payments / Reports ───────────────────────────────
function renderPayments(data, tenantsData, skipCache = false) {
    const tbody = document.getElementById('reportTbody');

    // Cache full dataset only on initial load, never from filter calls
    if (!skipCache) {
        window._allPaymentsData = data;
        window._allTenantsData  = tenantsData;
    }    if (!data) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="6">No payment records found.</td></tr>';
        updateSummaryCards([], tenantsData);
        return;
    }

    const entries = Object.entries(data);
    updateSummaryCards(Object.values(data), tenantsData);

    tbody.innerHTML = entries.map(([key, p], i) => {
        const period = p.billing_label || p.billing_month || '—';
        const isPaid = p.payment_status === 'Paid';
        const statusColor = isPaid ? '#28a745' : p.payment_status === 'Overdue' ? '#ff6b7a' : '#ffc107';
        const statusBg    = isPaid ? 'rgba(40,167,69,0.15)' : p.payment_status === 'Overdue' ? 'rgba(220,53,69,0.15)' : 'rgba(255,193,7,0.15)';
        const statusBorder= isPaid ? 'rgba(40,167,69,0.4)'  : p.payment_status === 'Overdue' ? 'rgba(220,53,69,0.4)'  : 'rgba(255,193,7,0.4)';

        return `
        <tr>
            <td><input type="text" class="editable-input" value="${esc(p.full_name || p.tenant_name || '')}"></td>
            <td><input type="text" class="editable-input" value="${esc(p.unit_number || '')}"></td>
            <td><input type="text" class="editable-input" value="${esc(period)}" ${i===0?'id="period1"':i===1?'id="period2"':''}></td>
            <td><input type="text" class="editable-input" value="₱${Number(p.amount_due||0).toLocaleString('en-PH',{minimumFractionDigits:2})}"></td>
            <td>
                <span style="background:${statusBg};color:${statusColor};border:1px solid ${statusBorder};padding:4px 10px;border-radius:4px;font-size:11px;font-weight:700;white-space:nowrap;">
                    ${esc(p.payment_status)}
                </span>
            </td>
            <td class="action-bar">
                ${!isPaid ? `
                <button onclick="openMarkPaidModal('${key}', ${JSON.stringify({full_name: p.full_name||p.tenant_name||'', unit_number: p.unit_number||'', billing_month: p.billing_month||'', billing_label: p.billing_label||'', amount_due: p.amount_due||0, email: p.email||''}).replace(/"/g,'&quot;')})"
                    style="background:rgba(40,167,69,0.12);color:#28a745;border:1px solid rgba(40,167,69,0.35);padding:5px 10px;border-radius:4px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                    <i class="fa-solid fa-peso-sign"></i> Mark Paid
                </button>` : `
                <button onclick="markUnpaid('${key}')"
                    style="background:rgba(220,53,69,0.12);color:#ff6b7a;border:1px solid rgba(220,53,69,0.35);padding:5px 10px;border-radius:4px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                    <i class="fa-solid fa-rotate-left"></i> Mark Unpaid
                </button>`}
            </td>
        </tr>`;
    }).join('');
}

function updateSummaryCards(payments, tenantsData) {
    const tenantCount    = tenantsData ? Object.keys(tenantsData).length : 0;
    const totalDue       = payments.reduce((s, p) => s + Number(p.amount_due || 0), 0);
    const totalCollected = payments.filter(p => p.payment_status === 'Paid').reduce((s, p) => s + Number(p.amount_due || 0), 0);
    const efficiency     = totalDue > 0 ? ((totalCollected / totalDue) * 100).toFixed(2) : '0.00';

    document.getElementById('sumTenants').value     = tenantCount + ' Tenants';
    document.getElementById('sumCollections').value = '₱' + totalCollected.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('sumEfficiency').value  = efficiency + '%';
}
// Expose renderPayments to non-module scripts for filter use
window.renderPayments = renderPayments;

// ── Render: Tenant's own payment card ───────────────────────
function renderTenantMyPayment(data, user) {
    const tbody = document.getElementById('tenantPaymentBody');
    if (!tbody) return;
    if (!data) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="4">No payment records found.</td></tr>';
        return;
    }
    const rows = Object.values(data);
    tbody.innerHTML = rows.map(p => {
        const statusColor = p.payment_status === 'Paid' ? '#28a745' : p.payment_status === 'Overdue' ? '#ff6b7a' : '#ffc107';
        return `<tr>
            <td>${esc(p.billing_label || p.billing_month || '—')}</td>
            <td>${esc(p.unit_number || '—')}</td>
            <td>₱${Number(p.amount_due||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</td>
            <td><span style="background:${statusColor}22;color:${statusColor};border:1px solid ${statusColor}66;padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700;">${esc(p.payment_status)}</span></td>
        </tr>`;
    }).join('');
}

// ── Render: Reminders ────────────────────────────────────────
function renderReminders(data, tenantsData) {
    const tbody = document.getElementById('remindersTableBody');
    if (!data) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="6">No notification logs yet.</td></tr>';
        return;
    }

    const logs = Object.values(data).sort((a,b) => new Date(b.sent_at) - new Date(a.sent_at));
    tbody.innerHTML = logs.map(rl => {
        const badgeClass = rl.delivery_status === 'Delivered' ? 'status-badge-delivered' :
                           rl.delivery_status === 'Failed'    ? '' : 'status-badge-sent';
        const badgeStyle = rl.delivery_status === 'Failed'
            ? 'style="background:rgba(220,53,69,0.15);color:#ff6b7a;border:1px solid rgba(220,53,69,0.4);padding:3px 8px;border-radius:4px;font-size:10px;font-weight:700;"'
            : '';
        const msg = (rl.message||'').length > 60 ? rl.message.substring(0,60)+'...' : (rl.message||'');
        return `
        <tr>
            <td>${esc(rl.sent_at || '—')}</td>
            <td>${esc(rl.full_name || rl.tenant_name || '—')} (${esc(rl.unit_number||'—')})</td>
            <td><span style="color:#00b8de;">${esc(rl.channel||'—')}</span></td>
            <td>${esc(rl.trigger_type||'—')}</td>
            <td>${esc(msg)}</td>
            <td><span class="${badgeClass}" ${badgeStyle}>${esc(rl.delivery_status||'—')}</span></td>
        </tr>`;
    }).join('');
}

// ── Render: Maintenance ──────────────────────────────────────
function renderMaintenance(data, tenantsData) {
    const container = document.getElementById('maintenanceListContainer');
    const u         = loadSession();
    const isAdmin   = u && (u.role === 'landlord' || u.role === 'admin');

    if (!data) {
        container.innerHTML = '<p style="color:#8da4be;font-size:13px;text-align:center;padding:20px;">No maintenance tickets found.</p>';
        return;
    }

    const entries = Object.entries(data).sort((a,b) => new Date(b[1].date_reported) - new Date(a[1].date_reported));
    container.innerHTML = entries.map(([key, m]) => {
        const statusClass = m.status === 'In Progress' ? 'status-progress' :
                            m.status === 'Resolved'    ? 'status-resolved'  : 'status-pending';
        const adminActions = isAdmin ? `
            <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;flex-shrink:0;">
                <span class="ticket-status ${statusClass}">${esc(m.status||'Pending')}</span>
                ${m.status !== 'Resolved' ? `
                <button onclick="markTicketResolved('${key}')"
                    style="background:rgba(40,167,69,0.15);color:#28a745;border:1px solid rgba(40,167,69,0.4);padding:4px 10px;border-radius:4px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                    <i class="fa-solid fa-check"></i> Mark Complete
                </button>` : ''}
                <button onclick="deleteTicket('${key}')"
                    style="background:rgba(220,53,69,0.12);color:#ff6b7a;border:1px solid rgba(220,53,69,0.35);padding:4px 10px;border-radius:4px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>` : `<span class="ticket-status ${statusClass}">${esc(m.status||'Pending')}</span>`;

        return `
        <div class="ticket-item" id="ticket-${key}" style="align-items:center;">
            <div class="ticket-info" style="flex:1;">
                <h5>${esc(m.category)}: ${esc(m.full_name || m.tenant_name || '—')}</h5>
                <p>${esc(m.full_name || '—')} (${esc(m.unit_number||'—')}) — ${esc(m.description||'')}</p>
                <span class="ticket-meta">Reported: ${esc(m.date_reported||'—')}</span>
            </div>
            ${adminActions}
        </div>`;
    }).join('');
}

// ── Mark Payment as Paid ─────────────────────────────────────
window.openMarkPaidModal = function(key, p) {
    document.getElementById('markPaidKey').value   = key;
    document.getElementById('markPaidDesc').textContent =
        `Marking payment for: ${p.full_name} (${p.unit_number})`;
    document.getElementById('markPaidDate').value  = new Date().toISOString().slice(0,10);

    // Store payment data for email use
    window._markPaidData = p;

    // Build month checkboxes — current month pre-checked, offer ±3 months
    const base    = p.billing_month ? new Date(p.billing_month) : new Date();
    const months  = [];
    for (let i = -1; i <= 3; i++) {
        const d = new Date(base.getFullYear(), base.getMonth() + i, 1);
        months.push({
            value: d.toISOString().slice(0,7),
            label: d.toLocaleString('en-PH', { month: 'long', year: 'numeric' }),
            checked: i === 0
        });
    }

    document.getElementById('markPaidMonthsContainer').innerHTML = months.map(m => `
        <label style="display:flex;align-items:center;gap:6px;background:#091220;border:1px solid #162a45;border-radius:6px;padding:7px 12px;cursor:pointer;font-size:12px;color:#fff;user-select:none;">
            <input type="checkbox" value="${m.value}" ${m.checked ? 'checked' : ''}
                style="accent-color:#00b8de;width:14px;height:14px;">
            ${m.label}
        </label>
    `).join('');

    openModal('modalMarkPaid');
};

window.saveMarkPaid = async function() {
    const key      = document.getElementById('markPaidKey').value;
    const datePaid = document.getElementById('markPaidDate').value;
    const checked  = [...document.querySelectorAll('#markPaidMonthsContainer input:checked')].map(c => c.value);

    if (!checked.length) {
        window.showToast('Please select at least one month.', '⚠️', 'No Month Selected');
        return;
    }
    if (!datePaid) {
        window.showToast('Please enter the date paid.', '⚠️', 'Missing Date');
        return;
    }

    try {
        // Update the current record
        await update(ref(db, `payments/${key}`), {
            payment_status: 'Paid',
            paid_date:      datePaid
        });

        // If extra months were selected, update matching payment records
        const allSnap = await get(ref(db, 'payments'));
        if (allSnap.exists()) {
            const updates = {};
            allSnap.forEach(child => {
                const v = child.val();
                const monthKey = (v.billing_month || '').slice(0,7);
                if (checked.includes(monthKey) && child.key !== key) {
                    // same tenant check by full_name + unit
                    const base = allSnap.child(key).val();
                    if (v.full_name === base.full_name || v.tenant_name === base.tenant_name) {
                        updates[`payments/${child.key}/payment_status`] = 'Paid';
                        updates[`payments/${child.key}/paid_date`]      = datePaid;
                    }
                }
            });
            if (Object.keys(updates).length) {
                await update(ref(db), updates);
            }
        }

        closeModal('modalMarkPaid');
        window.showToast(`Payment recorded for ${checked.length} month(s).`, '✅', 'Payment Saved');
        window._loadAllData();

        // Send receipt email via PHP
        const p = window._markPaidData || {};
        // Look up tenant email from /users by matching full_name
        const tenantsSnap = await get(ref(db, 'users'));
        let tenantEmail = '';
        let amountDue   = p.amount_due || 3500;

        if (tenantsSnap.exists()) {
            tenantsSnap.forEach(child => {
                const t = child.val();
                if ((t.full_name || '').trim() === (p.full_name || p.tenant_name || '').trim()) {
                    tenantEmail = t.email        || '';
                    amountDue   = t.monthly_rent || amountDue;
                }
            });
        }

        if (tenantEmail) {
            fetch('send_receipt.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email:         tenantEmail,
                    full_name:     p.full_name || p.tenant_name || '',
                    unit_number:   p.unit_number || '',
                    amount_due:    amountDue,
                    billing_label: p.billing_label || p.billing_month || '',
                    billing_month: p.billing_month || '',
                    paid_date:     datePaid
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    console.log('Receipt sent to', tenantEmail);
                } else {
                    console.warn('Email failed:', res.message);
                }
            })
            .catch(err => console.warn('Email error:', err));
        } else {
            console.warn('No tenant email found for:', p.full_name || p.tenant_name);
        }
    } catch (err) {
        window.showToast('Failed to save payment.', '❌', 'Error');
        console.error(err);
    }
};

// ── Change Password ───────────────────────────────────────────
window.changePassword = async function() {
    const currentPw = document.getElementById('settingsCurrentPw').value;
    const newPw     = document.getElementById('settingsNewPw').value;
    const confirmPw = document.getElementById('settingsConfirmPw').value;
    const u         = loadSession();

    if (!currentPw || !newPw || !confirmPw) {
        window.showToast('Please fill in all fields.', '⚠️', 'Missing Fields');
        return;
    }
    if (currentPw !== u.password) {
        window.showToast('Current password is incorrect.', '❌', 'Wrong Password');
        return;
    }
    if (newPw.length < 6) {
        window.showToast('New password must be at least 6 characters.', '⚠️', 'Too Short');
        return;
    }
    if (newPw !== confirmPw) {
        window.showToast('New passwords do not match.', '❌', 'Mismatch');
        return;
    }
    if (newPw === currentPw) {
        window.showToast('New password must be different from current.', '⚠️', 'Same Password');
        return;
    }

    try {
        // Update in /users node
        await update(ref(db, `users/${u.key}`), { password: newPw });

        // Update session
        u.password = newPw;
        const remember = !!localStorage.getItem('ramadia_user');
        saveSession(u, remember);

        closeModal('modalSettings');
        document.getElementById('settingsCurrentPw').value = '';
        document.getElementById('settingsNewPw').value     = '';
        document.getElementById('settingsConfirmPw').value = '';
        window.showToast('Password updated successfully.', '✅', 'Password Changed');
    } catch (err) {
        window.showToast('Failed to update password.', '❌', 'Error');
        console.error(err);
    }
};

// ── Mark Payment as Unpaid ───────────────────────────────────
window.markUnpaid = function(key) {
    window._confirmAction(
        'Revert this payment back to Pending? This will remove the paid date.',
        async () => {
            try {
                await update(ref(db, `payments/${key}`), {
                    payment_status: 'Pending',
                    paid_date:      null
                });
                window.showToast('Payment reverted to Pending.', '✅', 'Reverted');
                window._loadAllData();
            } catch (err) {
                window.showToast('Failed to revert payment.', '❌', 'Error');
                console.error(err);
            }
        },
        'Revert'
    );
};

// ── Add Tenant ────────────────────────────────────────────────
window.addTenant = async function() {
    const full_name     = document.getElementById('addTenantName').value.trim();
    const unit_number   = document.getElementById('addTenantRoom').value.trim();
    const mobile_number = document.getElementById('addTenantContact').value.trim();
    const email         = document.getElementById('addTenantEmail').value.trim();
    const monthly_rent  = parseFloat(document.getElementById('addTenantAmount').value) || 0;
    const password      = document.getElementById('addTenantPassword').value.trim();

    if (!full_name)  { window.showToast('Full name is required.', '⚠️', 'Missing Field'); return; }
    if (!email)      { window.showToast('Email is required.', '⚠️', 'Missing Field'); return; }
    if (!password)   { window.showToast('Password is required.', '⚠️', 'Missing Field'); return; }
    if (password.length < 6) { window.showToast('Password must be at least 6 characters.', '⚠️', 'Too Short'); return; }

    try {
        await push(ref(db, 'users'), {
            full_name, unit_number, mobile_number,
            email, monthly_rent, password,
            role: 'tenant'
        });

        // Auto-create a payment record for the new tenant in /payments
        const now      = new Date();
        const billing  = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-01`;
        const label    = now.toLocaleString('en-PH', { month: 'short', year: 'numeric' });
        await push(ref(db, 'payments'), {
            full_name,
            unit_number,
            billing_month:  billing,
            billing_label:  label,
            amount_due:     monthly_rent || 0,
            payment_status: 'Pending',
            paid_date:      null
        });
        closeModal('modalAddTenant');
        // Clear fields
        ['addTenantName','addTenantRoom','addTenantContact','addTenantEmail','addTenantAmount','addTenantPassword']
            .forEach(id => document.getElementById(id).value = '');
        window.showToast(`${full_name} has been added.`, '✅', 'Tenant Added');
        window._loadAllData();
    } catch (err) {
        window.showToast('Failed to add tenant.', '❌', 'Error');
        console.error(err);
    }
};

// ── Delete Tenant ─────────────────────────────────────────────
window.deleteTenant = function(key, name) {
    window._confirmAction(
        `Delete ${name}? This will permanently remove their account.`,
        async () => {
            try {
                await remove(ref(db, `users/${key}`));
                window.showToast(`${name} has been removed.`, '✅', 'Deleted');
                window._loadAllData();
            } catch (err) {
                window.showToast('Failed to delete tenant.', '❌', 'Error');
                console.error(err);
            }
        },
        'Delete'
    );
};

// ── Edit Tenant ───────────────────────────────────────────────
window.openEditTenantModal = function(key, t) {
    document.getElementById('editTenantKey').value      = key;
    document.getElementById('editTenantName').value     = t.full_name     || '';
    document.getElementById('editTenantRoom').value     = t.unit_number   || '';
    document.getElementById('editTenantContact').value  = t.mobile_number || '';
    document.getElementById('editTenantEmail').value    = t.email         || '';
    document.getElementById('editTenantAmount').value   = t.monthly_rent  || '';
    document.getElementById('editTenantPassword').value = t.password      || '';
    openModal('modalEditTenant');
};

window.saveTenantEdit = async function() {
    const key    = document.getElementById('editTenantKey').value;
    const amount = parseFloat(document.getElementById('editTenantAmount').value) || 0;
    const newPw  = document.getElementById('editTenantPassword').value.trim();

    const payload = {
        full_name:     document.getElementById('editTenantName').value.trim(),
        unit_number:   document.getElementById('editTenantRoom').value.trim(),
        mobile_number: document.getElementById('editTenantContact').value.trim(),
        email:         document.getElementById('editTenantEmail').value.trim(),
        monthly_rent:  amount,
    };
    if (newPw) payload.password = newPw;

    if (!payload.full_name) {
        window.showToast('Full name is required.', '⚠️', 'Missing Field');
        return;
    }

    try {
        // Save everything directly to /users/{key}
        await update(ref(db, `users/${key}`), payload);

        // Update amount_due in matching payment records
        if (amount > 0) {
            const paymentsSnap = await get(ref(db, 'payments'));
            if (paymentsSnap.exists()) {
                const updates = {};
                paymentsSnap.forEach(child => {
                    const v = child.val();
                    if ((v.full_name || v.tenant_name || '').trim() === payload.full_name.trim()) {
                        updates[`payments/${child.key}/amount_due`] = amount;
                    }
                });
                if (Object.keys(updates).length) await update(ref(db, '/'), updates);
            }
        }

        closeModal('modalEditTenant');
        window.showToast('Tenant updated successfully.', '✅', 'Saved');
        window._loadAllData();
    } catch (err) {
        window.showToast('Failed to save changes.', '❌', 'Error');
        console.error(err);
    }
};

// ── Due Date Schedule ────────────────────────────────────────
const DUE_SCHEDULE_LABELS = {
    'end_of_month': 'End of Month',
    '15th':         '15th of Every Month',
    '5th':          '5th of Every Month',
    '1st':          '1st of Every Month',
};

function getDueDayForSchedule(schedule, year, month) {
    // Returns the due date as a Date object for a given year/month
    if (schedule === 'end_of_month') return new Date(year, month + 1, 0); // last day
    if (schedule === '15th')         return new Date(year, month, 15);
    if (schedule === '5th')          return new Date(year, month, 5);
    if (schedule === '1st')          return new Date(year, month, 1);
    return new Date(year, month + 1, 0);
}

async function loadDueSchedule() {
    try {
        const snap = await get(ref(db, 'settings/due_schedule'));
        const schedule = snap.exists() ? snap.val() : 'end_of_month';
        const sel = document.getElementById('dueDateSchedule');
        if (sel) sel.value = schedule;
        updateDueDateInfoBar(schedule);
        return schedule;
    } catch (e) {
        return 'end_of_month';
    }
}

function updateDueDateInfoBar(schedule) {
    const label = DUE_SCHEDULE_LABELS[schedule] || 'End of Month';
    const infoText = document.getElementById('dueDateInfoText');
    const nextInfo = document.getElementById('nextReminderDate');
    if (infoText) infoText.textContent = label;

    // Calculate next due date and reminder date
    const now     = new Date();
    const dueDate = getDueDayForSchedule(schedule, now.getFullYear(), now.getMonth());
    // If due date already passed this month, get next month's
    const effectiveDue = dueDate < now
        ? getDueDayForSchedule(schedule, now.getFullYear(), now.getMonth() + 1)
        : dueDate;

    const reminderDate = new Date(effectiveDue);
    reminderDate.setDate(reminderDate.getDate() - 3);

    if (nextInfo) {
        nextInfo.innerHTML = `Next due: <strong style="color:#fff;">${effectiveDue.toLocaleDateString('en-PH',{month:'long',day:'numeric',year:'numeric'})}</strong>
            &nbsp;·&nbsp; Reminder on: <strong style="color:#ffc107;">${reminderDate.toLocaleDateString('en-PH',{month:'long',day:'numeric',year:'numeric'})}</strong>`;
    }
}

window.saveDueSchedule = async function() {
    const schedule = document.getElementById('dueDateSchedule').value;
    try {
        await update(ref(db, 'settings'), { due_schedule: schedule });
        updateDueDateInfoBar(schedule);
        window.showToast(`Due date set to: ${DUE_SCHEDULE_LABELS[schedule]}`, '✅', 'Schedule Saved');
    } catch (err) {
        window.showToast('Failed to save schedule.', '❌', 'Error');
        console.error(err);
    }
};

// ── Auto-check: send reminders if today is 3 days before due ─
async function checkAndSendReminders(schedule) {
    const now     = new Date();
    const dueDate = getDueDayForSchedule(schedule, now.getFullYear(), now.getMonth());

    const reminderDate = new Date(dueDate);
    reminderDate.setDate(reminderDate.getDate() - 3);

    const todayStr    = now.toISOString().slice(0,10);
    const reminderStr = reminderDate.toISOString().slice(0,10);

    if (todayStr !== reminderStr) return; // not reminder day

    // Check if already sent today
    const sentSnap = await get(ref(db, 'settings/last_reminder_sent'));
    if (sentSnap.exists() && sentSnap.val() === todayStr) return; // already sent

    await sendReminderEmails(schedule, dueDate, false);
    await update(ref(db, 'settings'), { last_reminder_sent: todayStr });
}

async function sendReminderEmails(schedule, dueDate, isManual = true) {
    const btn = document.getElementById('sendReminderBtn');
    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> Sending...'; }

    try {
        const [tenantsSnap, paymentsSnap] = await Promise.all([
            get(ref(db, 'users')),
            get(ref(db, 'payments'))
        ]);

        if (!tenantsSnap.exists()) {
            if (isManual) window.showToast('No tenants found.', '⚠️', 'No Data');
            return;
        }

        const dueDateStr = dueDate.toISOString().slice(0,10);
        const billing    = dueDate.toLocaleString('en-PH', { month: 'long', year: 'numeric' });

        // Build map of unpaid tenants
        const unpaidNames = new Set();
        if (paymentsSnap.exists()) {
            paymentsSnap.forEach(child => {
                const p = child.val();
                if (p.payment_status !== 'Paid') {
                    unpaidNames.add((p.full_name || p.tenant_name || '').trim());
                }
            });
        }

        const tenants = Object.values(tenantsSnap.val());
        let sent = 0, failed = 0;

        for (const t of tenants) {
            if (!t.email) continue;
            // Only remind unpaid tenants (if payment data exists), else remind all
            if (unpaidNames.size > 0 && !unpaidNames.has((t.full_name||'').trim())) continue;

            try {
                const res = await fetch('send_reminder.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email:       t.email,
                        full_name:   t.full_name || '',
                        unit_number: t.unit_number || '',
                        amount_due:  t.monthly_rent || 3500,
                        due_date:    dueDateStr,
                        billing:     billing
                    })
                });
                const json = await res.json();
                if (json.success) sent++;
                else { failed++; console.warn('Reminder failed for', t.email, json.message); }
            } catch (e) { failed++; }
        }

        if (isManual) {
            window.showToast(`Sent ${sent} reminder(s).${failed ? ` ${failed} failed.` : ''}`, sent > 0 ? '✅' : '⚠️', 'Reminders Sent');
        }
    } catch (err) {
        if (isManual) window.showToast('Failed to send reminders.', '❌', 'Error');
        console.error(err);
    } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-bell"></i> Send Reminders'; }
    }
}

window.sendRemindersNow = async function() {
    const schedule = document.getElementById('dueDateSchedule')?.value || 'end_of_month';
    const now      = new Date();
    const dueDate  = getDueDayForSchedule(schedule, now.getFullYear(), now.getMonth());
    // If due already passed, use next month
    const effectiveDue = dueDate < now
        ? getDueDayForSchedule(schedule, now.getFullYear(), now.getMonth() + 1)
        : dueDate;
    await sendReminderEmails(schedule, effectiveDue, true);
};

// ── Mark ticket as resolved ──────────────────────────────────
window.markTicketResolved = async function(key) {
    try {
        const dateResolved = new Date().toISOString().slice(0,10);
        await update(ref(db, `maintenance/${key}`), {
            status:        'Resolved',
            date_resolved: dateResolved
        });

        window.showToast('Ticket marked as completed.', '✅', 'Completed');
        window._loadAllData();

        // Send email notification to tenant
        const ticketSnap = await get(ref(db, `maintenance/${key}`));
        if (ticketSnap.exists()) {
            const m = ticketSnap.val();
            // Look up tenant email from /users by full_name
            const tenantsSnap = await get(ref(db, 'users'));
            let tenantEmail = '';
            if (tenantsSnap.exists()) {
                tenantsSnap.forEach(child => {
                    const t = child.val();
                    if ((t.full_name||'').trim() === (m.full_name||m.tenant_name||'').trim()) {
                        tenantEmail = t.email || '';
                    }
                });
            }
            if (tenantEmail) {
                fetch('send_maintenance.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email:         tenantEmail,
                        full_name:     m.full_name    || m.tenant_name || '',
                        unit_number:   m.unit_number  || '',
                        category:      m.category     || '',
                        description:   m.description  || '',
                        status:        'Resolved',
                        date_reported: m.date_reported || '',
                        date_resolved: dateResolved
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) console.log('Maintenance email sent to', tenantEmail);
                    else console.warn('Maintenance email failed:', res.message);
                })
                .catch(err => console.warn('Maintenance email error:', err));
            }
        }
    } catch (err) {
        window.showToast('Failed to update ticket.', '❌', 'Error');
        console.error(err);
    }
};

// ── Delete ticket ────────────────────────────────────────────
window.deleteTicket = async function(key) {
    window._confirmAction(
        'Delete this maintenance ticket? This cannot be undone.',
        async () => {
            try {
                await remove(ref(db, `maintenance/${key}`));
                window.showToast('Ticket deleted successfully.', '✅', 'Deleted');
                window._loadAllData();
            } catch (err) {
                window.showToast('Failed to delete ticket.', '❌', 'Error');
                console.error(err);
            }
        }
    );
};

// ── Submit Maintenance Ticket ────────────────────────────────
window.submitMaintenanceTicket = async function() {
    const u        = loadSession();
    const tenant   = document.getElementById('maintTenant').value.trim();
    const category = document.getElementById('maintCategory').value;
    const desc     = document.getElementById('maintDesc').value.trim();

    if (!tenant || !desc) { window.showToast('Please fill in all fields.', '⚠️', 'Missing Fields'); return; }

    const now         = new Date().toISOString().slice(0,10);
    const full_name   = u ? u.full_name              : tenant;
    const unit_number = u ? (u.unit_number || 'N/A') : 'N/A';

    try {
        await push(ref(db, 'maintenance'), {
            full_name,
            unit_number,
            tenant_name:   tenant,
            category,
            description:   desc,
            status:        'Pending',
            date_reported: now
        });
        document.getElementById('maintDesc').value = '';
        window.showToast('Your maintenance ticket has been submitted successfully!', '✅', 'Ticket Submitted');
        window._loadAllData();
    } catch (err) {
        window.showToast('Failed to submit ticket. Please try again.', '❌', 'Error');
        console.error(err);
    }
};

// ── Escape HTML ──────────────────────────────────────────────
function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Make loadAllData accessible globally
window._loadAllData = () => {
    const u = loadSession();
    if (u) loadAllData(u);
};

</script>

<!-- ===================== UI SCRIPTS (non-module) ===================== -->
<script>
function switchTab(tabId) {
    document.querySelectorAll('.system-view').forEach(v => v.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

    const viewMap = {
        dashboard:   ['viewDashboard',   'navDashboard'],
        tenants:     ['viewTenants',      'navTenants'],
        reminders:   ['viewReminders',    'navReminders'],
        reports:     ['viewReports',      'navReports'],
        myPayment:   ['viewMyPayment',    'navReportsTenant'],
        maintenance: ['viewMaintenance',  'navMaintenance'],
        support:     ['viewSupport',      'navSupport'],
    };

    const [viewId, navId] = viewMap[tabId] || viewMap['dashboard'];
    document.getElementById(viewId)?.classList.add('active');
    document.getElementById(navId)?.classList.add('active');
}

function togglePwVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Password strength checker
document.addEventListener('input', function(e) {
    if (e.target.id !== 'settingsNewPw') return;
    const val   = e.target.value;
    const fill  = document.getElementById('pwStrengthFill');
    const label = document.getElementById('pwStrengthLabel');
    if (!fill || !label) return;

    let strength = 0;
    if (val.length >= 6)                        strength++;
    if (val.length >= 10)                       strength++;
    if (/[A-Z]/.test(val))                      strength++;
    if (/[0-9]/.test(val))                      strength++;
    if (/[^A-Za-z0-9]/.test(val))              strength++;

    const levels = [
        { pct: '0%',   color: '#162a45', text: '' },
        { pct: '25%',  color: '#ff6b7a', text: 'Weak' },
        { pct: '50%',  color: '#ffc107', text: 'Fair' },
        { pct: '75%',  color: '#00b8de', text: 'Good' },
        { pct: '100%', color: '#28a745', text: 'Strong' },
    ];
    const lvl = val.length === 0 ? levels[0] : levels[Math.min(strength, 4)];
    fill.style.width      = lvl.pct;
    fill.style.background = lvl.color;
    label.textContent     = lvl.text;
    label.style.color     = lvl.color;
});

function setRole(role) {
    document.getElementById('btnTenant').classList.toggle('active', role === 'Tenant');
    document.getElementById('btnAdmin').classList.toggle('active', role === 'Landlord / Admin');
    document.getElementById('formRoleTitle').innerText = role + ' Sign In';
    document.getElementById('lblUser').innerText = role === 'Tenant' ? 'Email / Tenant ID' : 'Admin Username / Email';
}

function openModal(id)  { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
window.openModal  = openModal;
window.closeModal = closeModal;

// Confirmation modal helper
window._confirmAction = function(msg, onConfirm, btnLabel = 'Delete') {
    document.getElementById('modalConfirmMsg').textContent    = msg;
    document.getElementById('modalConfirmOkBtn').textContent  = btnLabel;
    const btn = document.getElementById('modalConfirmOkBtn');
    // Replace button to clear old listeners
    const fresh = btn.cloneNode(true);
    fresh.textContent = btnLabel;
    btn.parentNode.replaceChild(fresh, btn);
    fresh.addEventListener('click', () => {
        closeModal('modalConfirm');
        onConfirm();
    });
    openModal('modalConfirm');
};

function showToast(msg, icon = '✅', title = 'Success') {
    const wrap = document.getElementById('modalSuccessIconWrap');
    // Color the icon circle based on type
    if (icon === '❌') {
        wrap.style.background = 'rgba(220,53,69,0.15)';
        wrap.style.border     = '2px solid rgba(220,53,69,0.4)';
    } else if (icon === '⚠️') {
        wrap.style.background = 'rgba(255,193,7,0.15)';
        wrap.style.border     = '2px solid rgba(255,193,7,0.4)';
    } else {
        wrap.style.background = 'rgba(40,167,69,0.15)';
        wrap.style.border     = '2px solid rgba(40,167,69,0.4)';
    }
    document.getElementById('modalSuccessIcon').textContent  = icon;
    document.getElementById('modalSuccessTitle').textContent = title;
    document.getElementById('modalSuccessMsg').textContent   = msg;
    openModal('modalSuccess');
}
window.showToast = showToast;

// ── Report Period Filter ──────────────────────────────────────
// Cache all payment data for filtering
window._allPaymentsData   = null;
window._allTenantsData    = null;

(function initReportFilter() {
    const yearSel = document.getElementById('filterYear');
    const now = new Date();
    for (let y = 2020; y <= 2035; y++) {
        const o = document.createElement('option');
        o.value = y; o.textContent = y;
        if (y === now.getFullYear()) o.selected = true;
        yearSel.appendChild(o);
    }
    document.getElementById('filterMonth').value = now.getMonth() + 1;
})();

function onFilterTypeChange() {
    const type = document.getElementById('filterPeriodType').value;
    document.getElementById('filterYear').style.display  = type !== 'all' ? '' : 'none';
    document.getElementById('filterMonth').style.display = type === 'monthly' ? '' : 'none';
    applyReportFilter();
}

function applyReportFilter() {
    if (!window._allPaymentsData) return;

    const type  = document.getElementById('filterPeriodType').value;
    const year  = parseInt(document.getElementById('filterYear').value);
    const month = parseInt(document.getElementById('filterMonth').value);

    // Filter the cached payments data
    const allEntries = Object.entries(window._allPaymentsData);
    let filtered = {};

    allEntries.forEach(([key, p]) => {
        const bm = p.billing_month || ''; // e.g. "2026-09-01"
        if (!bm) { if (type === 'all') filtered[key] = p; return; }
        const d  = new Date(bm);
        const rY = d.getFullYear();
        const rM = d.getMonth() + 1;

        if      (type === 'all')     filtered[key] = p;
        else if (type === 'yearly'  && rY === year)              filtered[key] = p;
        else if (type === 'monthly' && rY === year && rM === month) filtered[key] = p;
    });

    // Re-render table and update summary cards with filtered data — skip cache
    if (window.renderPayments) {
        window.renderPayments(Object.keys(filtered).length ? filtered : null, window._allTenantsData, true);
    }

    const count = Object.keys(filtered).length;
    const label = document.getElementById('filterResultCount');
    label.textContent = type !== 'all' ? `${count} record(s) found` : '';
}

function clearReportFilter() {
    document.getElementById('filterPeriodType').value = 'all';
    onFilterTypeChange();
}

function toggleReportType() {
    const mode = document.getElementById('reportMode').value;
    const isAnnual = mode === 'annual';
    document.getElementById('reportHeaderTitle').innerText = isAnnual ? 'Formal Annual Rent Financial Summary' : 'Formal Monthly Rent Financial Report';
    document.getElementById('printReportTitle').innerText  = isAnnual ? 'FORMAL ANNUAL RENT FINANCIAL SUMMARY STATEMENT' : 'FORMAL MONTHLY RENT FINANCIAL REPORT';
    document.getElementById('lblColTitle').innerText       = isAnnual ? 'Total Annual Collections' : 'Total Monthly Collections';
}

function addReportRow() {
    const tbody = document.getElementById('reportTbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" class="editable-input" value="New Tenant"></td>
        <td><input type="text" class="editable-input" value="Room X"></td>
        <td><input type="text" class="editable-input" value="—"></td>
        <td><input type="text" class="editable-input" value="₱3,500.00"></td>
        <td><select class="status-select"><option>Paid</option><option selected>Pending</option><option>Overdue</option></select></td>
        <td class="action-bar"><button class="action-btn" style="color:#dc3545;" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash"></i></button></td>`;
    tbody.appendChild(tr);
}

function handleSearch(val) {
    const q = val.toLowerCase();
    document.querySelectorAll('.logs-table tbody tr:not(.empty-row)').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

// Set print date
document.getElementById('printDate').textContent = new Date().toLocaleDateString('en-PH', {year:'numeric',month:'long',day:'numeric'});
</script>

</body>
</html>
