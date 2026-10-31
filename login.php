<?php
$pageTitle = "Student & Admin Login";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

if (isLoggedIn()) {
    header("Location: " . (getUserRole() === 'admin' ? 'admin.php' : 'dashboard.php'));
    exit;
}

$error = '';
$message = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $message = "You have been logged out successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $error = "Session expired or invalid security token. Please try again.";
    } else {
        $studentId = trim($_POST['studentId'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($studentId) || empty($password)) {
            $error = "Please enter both Student ID and Password.";
        } else {
            $pdo = getDBConnection();
            if ($pdo) {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = :sid LIMIT 1");
                $stmt->execute([':sid' => $studentId]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['studenthub_user'] = [
                        'id'         => $user['id'],
                        'student_id' => $user['student_id'],
                        'name'       => $user['name'],
                        'email'      => $user['email'],
                        'course'     => $user['course'],
                        'phone'      => $user['phone'],
                        'city'       => $user['city'],
                        'dob'        => $user['dob'],
                        'role'       => $user['role']
                    ];
                    header("Location: " . ($user['role'] === 'admin' ? 'admin.php' : 'dashboard.php'));
                    exit;
                } else {
                    $error = "Invalid Student ID or Password.";
                }
            } else {
                if ($studentId === 'ADMIN01' && $password === 'Admin@123') {
                    $_SESSION['studenthub_user'] = [
                        'student_id' => 'ADMIN01',
                        'name'       => 'System Administrator',
                        'email'      => 'admin@studenthub.com',
                        'role'       => 'admin',
                        'course'     => 'Administration'
                    ];
                    header("Location: admin.php");
                    exit;
                } elseif ($studentId === '25CS076' && $password === 'Student@123') {
                    $_SESSION['studenthub_user'] = [
                        'student_id' => '25CS076',
                        'name'       => 'Pratham Patel',
                        'email'      => 'student@studenthub.com',
                        'role'       => 'student',
                        'course'     => 'B.Tech'
                    ];
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid credentials. Use provided test credentials below.";
                }
            }
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="card" style="max-width: 520px; margin: 0 auto;">
    <h2>🔐 Account Login</h2>
    <p>Sign in to access your student dashboard, courses, and registered events.</p>
    <br>

    <?php if ($message): ?>
        <div class="alert success-box" role="status" style="background:#dcfce7;color:#15803d;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #4ade80;">
            ℹ️ <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error-box" role="alert" style="background:#fee2e2;color:#b91c1c;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #f87171;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form id="loginForm" action="login.php" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

        <label for="loginStudentId"><strong>Student ID / Admin ID <span style="color:red;">*</span></strong></label>
        <input type="text" id="loginStudentId" name="studentId" placeholder="e.g. 25CS076 or ADMIN01" required autocomplete="username" value="<?= isset($_POST['studentId']) ? htmlspecialchars($_POST['studentId']) : '' ?>">

        <label for="loginPassword"><strong>Password <span style="color:red;">*</span></strong></label>
        <input type="password" id="loginPassword" name="password" placeholder="Enter Password" required autocomplete="current-password">

        <button type="submit" id="loginBtn" style="margin-top:10px;width:100%;">Secure Login</button>
    </form>

    <br>
    <div style="display:flex;justify-content:space-between;font-size:14px;">
        <p>
            Forgot Password? <a href="#" onclick="openModal('Password Reset: A temporary password reset link will be dispatched to your registered institutional email.')" style="color:#005bea;font-weight:bold;">Click Here</a>
        </p>
        <p>
            <a href="register.php" style="color:#005bea;font-weight:bold;">Create New Account</a>
        </p>
    </div>

    <hr style="margin:20px 0;border:0;border-top:1px solid #e2e8f0;">

    <div style="background:#f8fafc;padding:14px;border-radius:8px;border:1px dashed #94a3b8;font-size:13px;">
        <strong>🔑 Practical 15 Evaluation Test Credentials:</strong>
        <div style="margin-top:6px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div>
                <strong>Student Account:</strong><br>
                ID: <code>25CS076</code><br>
                Pass: <code>Student@123</code>
            </div>
            <div>
                <strong>Administrator Account:</strong><br>
                ID: <code>ADMIN01</code><br>
                Pass: <code>Admin@123</code>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
