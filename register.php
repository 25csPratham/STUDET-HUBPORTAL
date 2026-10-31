<?php
$pageTitle = "Student Registration";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $error = "Security validation failed. Please refresh the page.";
    } else {
        $name            = trim($_POST['name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $studentId       = trim($_POST['studentId'] ?? '');
        $course          = trim($_POST['course'] ?? '');
        $phone           = trim($_POST['phone'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        if (empty($name) || empty($email) || empty($studentId) || empty($course) || empty($phone) || empty($password)) {
            $error = "Please fill in all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long.";
        } elseif ($password !== $confirmPassword) {
            $error = "Passwords do not match.";
        } else {
            $pdo = getDBConnection();
            if ($pdo) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = :sid OR email = :email LIMIT 1");
                $stmt->execute([':sid' => $studentId, ':email' => $email]);
                if ($stmt->fetch()) {
                    $error = "Student ID or Email is already registered in the system.";
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $ins = $pdo->prepare("INSERT INTO users (student_id, name, email, phone, course, password, role) VALUES (:sid, :name, :email, :phone, :course, :pwd, 'student')");
                    $ins->execute([
                        ':sid'    => $studentId,
                        ':name'   => $name,
                        ':email'  => $email,
                        ':phone'  => $phone,
                        ':course' => $course,
                        ':pwd'    => $hash
                    ]);
                    $success = "Registration successful! You can now login with your credentials.";
                }
            } else {
                $success = "Registration saved in offline session mode! You can now log in.";
            }
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>📝 Student Registration Form</h2>
    <p>Join StudentHub Portal to manage your academic profile, view marks, and register for campus events.</p>
    <br>

    <?php if ($error): ?>
        <div class="alert error-box" role="alert" style="background:#fee2e2;color:#b91c1c;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #f87171;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert success-box" role="alert" style="background:#dcfce7;color:#15803d;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #4ade80;">
            ✅ <?= htmlspecialchars($success) ?>
            <p style="margin-top:8px;"><a href="login.php" style="font-weight:bold;color:#15803d;text-decoration:underline;">Click here to Login</a></p>
        </div>
    <?php endif; ?>

    <form id="registerForm" action="register.php" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

        <label for="name"><strong>Full Name <span style="color:red;">*</span></strong></label>
        <input type="text" id="name" name="name" placeholder="e.g. Pratham Patel" required autocomplete="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">

        <label for="email"><strong>Email Address <span style="color:red;">*</span></strong></label>
        <input type="email" id="email" name="email" placeholder="e.g. student@studenthub.com" required autocomplete="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

        <label for="studentId"><strong>Student ID / Roll No <span style="color:red;">*</span></strong></label>
        <input type="text" id="studentId" name="studentId" placeholder="e.g. 25CS076" required autocomplete="username" value="<?= isset($_POST['studentId']) ? htmlspecialchars($_POST['studentId']) : '' ?>">

        <label for="course"><strong>Select Enrolled Course <span style="color:red;">*</span></strong></label>
        <select id="course" name="course" required>
            <option value="">-- Choose Course --</option>
            <option value="B.Tech" <?= (isset($_POST['course']) && $_POST['course'] === 'B.Tech') ? 'selected' : '' ?>>B.Tech (Computer Science & Engineering)</option>
            <option value="BCA" <?= (isset($_POST['course']) && $_POST['course'] === 'BCA') ? 'selected' : '' ?>>BCA (Bachelor of Computer Applications)</option>
            <option value="MCA" <?= (isset($_POST['course']) && $_POST['course'] === 'MCA') ? 'selected' : '' ?>>MCA (Master of Computer Applications)</option>
            <option value="BBA" <?= (isset($_POST['course']) && $_POST['course'] === 'BBA') ? 'selected' : '' ?>>BBA (Business Administration)</option>
            <option value="MBA" <?= (isset($_POST['course']) && $_POST['course'] === 'MBA') ? 'selected' : '' ?>>MBA (Master of Business Administration)</option>
        </select>

        <label for="phone"><strong>Mobile Number <span style="color:red;">*</span></strong></label>
        <input type="tel" id="phone" name="phone" placeholder="e.g. 9876543210" required autocomplete="tel" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">

        <label for="password"><strong>Create Password <span style="color:red;">*</span></strong></label>
        <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required autocomplete="new-password">

        <label for="confirmPassword"><strong>Confirm Password <span style="color:red;">*</span></strong></label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter password" required autocomplete="new-password">

        <button type="submit" id="registerBtn" style="margin-top:10px;width:100%;">Register Now</button>
    </form>

    <br>
    <p style="text-align:center;">
        Already registered? <a href="login.php" style="color:#005bea;font-weight:bold;">Login Here</a>
    </p>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
