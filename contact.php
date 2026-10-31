<?php
$pageTitle = "Contact Administration";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $err = "Security token validation failed. Please refresh.";
    } else {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? 'General Inquiry');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            $err = "Please fill in all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = "Please provide a valid email address.";
        } else {
            $pdo = getDBConnection();
            if ($pdo) {
                $ins = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :sub, :msg)");
                $ins->execute([
                    ':name'  => $name,
                    ':email' => $email,
                    ':sub'   => $subject,
                    ':msg'   => $message
                ]);
            }
            $msg = "Your message has been sent successfully to the academic administration! We will reply via email shortly.";
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>📍 Contact University Administration</h2>
    <p>Have inquiries regarding admissions, semester exams, or hostel facilities? Drop us a message.</p>
</div>

<?php if ($msg): ?>
    <div class="alert success-box" role="status" style="background:#dcfce7;color:#15803d;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #4ade80;">
        ✅ <?= htmlspecialchars($msg) ?>
    </div>
<?php endif; ?>

<?php if ($err): ?>
    <div class="alert error-box" role="alert" style="background:#fee2e2;color:#b91c1c;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #f87171;">
        ⚠️ <?= htmlspecialchars($err) ?>
    </div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:20px;">
    <div class="card">
        <h3>✉️ Send a Message</h3>
        <br>
        <form id="contactForm" action="contact.php" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <label for="contactName"><strong>Full Name <span style="color:red;">*</span></strong></label>
            <input type="text" id="contactName" name="name" placeholder="Enter Your Name" required autocomplete="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>">

            <label for="contactEmail"><strong>Email Address <span style="color:red;">*</span></strong></label>
            <input type="email" id="contactEmail" name="email" placeholder="Enter Your Email" required autocomplete="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>">

            <label for="contactSubject"><strong>Subject <span style="color:red;">*</span></strong></label>
            <input type="text" id="contactSubject" name="subject" placeholder="e.g. Exam Hall Ticket Inquiry" required>

            <label for="contactMessage"><strong>Detailed Message <span style="color:red;">*</span></strong></label>
            <textarea id="contactMessage" name="message" rows="5" placeholder="Write your query or message here..." required></textarea>

            <button type="submit" id="contactBtn" style="margin-top:10px;width:100%;">Send Message</button>
        </form>
    </div>

    <div class="card">
        <h3>🏛️ Campus Headquarters</h3>
        <br>
        <p><strong>Campus Address:</strong><br>
        Student Hub Portal Administrative Cell<br>
        Chandubhai S. Patel Institute of Technology (CSPIT)<br>
        CHARUSAT Campus, Changa - 388421<br>
        Dist: Anand, Gujarat, India</p>

        <hr style="margin:15px 0;border:0;border-top:1px solid #e2e8f0;">

        <p><strong>📧 Support Email:</strong><br>
        <a href="mailto:support@studenthub.com" style="color:#005bea;">support@studenthub.com</a></p>

        <br>
        <p><strong>📞 Helpdesk Helplines:</strong><br>
        +91 (02697) 265011 / 265021<br>
        +91 9876543210 (Mon-Sat, 9 AM - 5 PM)</p>

        <br>
        <p><strong>🌐 Official Web Portal:</strong><br>
        <a href="http://studenthub.local" style="color:#005bea;">http://studenthub.local</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
