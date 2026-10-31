<?php
$pageTitle = "Frequently Asked Questions";
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>❓ Frequently Asked Questions (FAQ)</h2>
    <p>Find answers to common questions about StudentHub Portal, account management, academic records, and events.</p>
</div>

<div class="card">
    <div class="faq-item" style="margin-bottom:12px;border:1px solid #e2e8f0;border-radius:6px;overflow:hidden;">
        <button class="faq-question" style="width:100%;text-align:left;background:#f8fafc;padding:14px;border:none;cursor:pointer;font-size:16px;font-weight:bold;color:#1e293b;display:flex;justify-content:space-between;align-items:center;" aria-expanded="false">
            <span>🎓 How do I register for a new account on Student Hub?</span>
            <span class="faq-icon" style="font-size:18px;">➕</span>
        </button>
        <div class="faq-answer" style="display:none;padding:15px;background:#ffffff;border-top:1px solid #e2e8f0;color:#475569;line-height:1.6;">
            Navigate to the <a href="register.php" style="color:#005bea;font-weight:bold;">Register Page</a>, fill in your Full Name, Email, Student ID/Roll Number, select your academic program, provide a mobile number, create a secure password (at least 6 characters), and submit. You can immediately log in with your credentials.
        </div>
    </div>

    <div class="faq-item" style="margin-bottom:12px;border:1px solid #e2e8f0;border-radius:6px;overflow:hidden;">
        <button class="faq-question" style="width:100%;text-align:left;background:#f8fafc;padding:14px;border:none;cursor:pointer;font-size:16px;font-weight:bold;color:#1e293b;display:flex;justify-content:space-between;align-items:center;" aria-expanded="false">
            <span>📊 How can I view my examination marks and attendance?</span>
            <span class="faq-icon" style="font-size:18px;">➕</span>
        </button>
        <div class="faq-answer" style="display:none;padding:15px;background:#ffffff;border-top:1px solid #e2e8f0;color:#475569;line-height:1.6;">
            Once logged in, go to the <a href="dashboard.php" style="color:#005bea;font-weight:bold;">Student Dashboard</a>. You will see your overall attendance percentage, cumulative CGPA, and a breakdown table of marks obtained across all semester courses pulled directly from the central database.
        </div>
    </div>

    <div class="faq-item" style="margin-bottom:12px;border:1px solid #e2e8f0;border-radius:6px;overflow:hidden;">
        <button class="faq-question" style="width:100%;text-align:left;background:#f8fafc;padding:14px;border:none;cursor:pointer;font-size:16px;font-weight:bold;color:#1e293b;display:flex;justify-content:space-between;align-items:center;" aria-expanded="false">
            <span>🎉 How do I register for campus events like Tech Fest?</span>
            <span class="faq-icon" style="font-size:18px;">➕</span>
        </button>
        <div class="faq-answer" style="display:none;padding:15px;background:#ffffff;border-top:1px solid #e2e8f0;color:#475569;line-height:1.6;">
            Visit the <a href="events.php" style="color:#005bea;font-weight:bold;">Events Page</a> to view all scheduled events, dates, venues, and descriptions. Click the "Register Now" button next to any event. The system will save your participation via AJAX and mark it as registered in your profile.
        </div>
    </div>

    <div class="faq-item" style="margin-bottom:12px;border:1px solid #e2e8f0;border-radius:6px;overflow:hidden;">
        <button class="faq-question" style="width:100%;text-align:left;background:#f8fafc;padding:14px;border:none;cursor:pointer;font-size:16px;font-weight:bold;color:#1e293b;display:flex;justify-content:space-between;align-items:center;" aria-expanded="false">
            <span>🛡️ How is student data kept secure?</span>
            <span class="faq-icon" style="font-size:18px;">➕</span>
        </button>
        <div class="faq-answer" style="display:none;padding:15px;background:#ffffff;border-top:1px solid #e2e8f0;color:#475569;line-height:1.6;">
            StudentHub uses industry best practices: passwords are encrypted using BCrypt one-way hashing (`password_hash`), all database queries use PDO prepared statements to completely mitigate SQL Injection, HTML output is sanitized against XSS, and role-based access control prevents unauthorized access to administrative endpoints.
        </div>
    </div>

    <div class="faq-item" style="margin-bottom:12px;border:1px solid #e2e8f0;border-radius:6px;overflow:hidden;">
        <button class="faq-question" style="width:100%;text-align:left;background:#f8fafc;padding:14px;border:none;cursor:pointer;font-size:16px;font-weight:bold;color:#1e293b;display:flex;justify-content:space-between;align-items:center;" aria-expanded="false">
            <span>🔑 What are the demo credentials for evaluation?</span>
            <span class="faq-icon" style="font-size:18px;">➕</span>
        </button>
        <div class="faq-answer" style="display:none;padding:15px;background:#ffffff;border-top:1px solid #e2e8f0;color:#475569;line-height:1.6;">
            For Practical 15 demonstration:
            <ul>
                <li><strong>Student Account:</strong> Student ID: <code>25CS076</code> | Password: <code>Student@123</code></li>
                <li><strong>Admin Account:</strong> Admin ID: <code>ADMIN01</code> | Password: <code>Admin@123</code></li>
            </ul>
        </div>
    </div>
</div>

<div class="card" style="text-align:center;">
    <h3>Still have questions?</h3>
    <p>Reach out directly to university administration via our contact desk.</p>
    <br>
    <button type="button" onclick="location.href='contact.php'">
        ✉️ Contact Administration
    </button>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
