<?php
$pageTitle = "Student Profile";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

requireLogin('login.php');
$currentUser = getCurrentUser();
$pdo = getDBConnection();

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $err = "Session verification token invalid. Please refresh.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $dob = trim($_POST['dob'] ?? '');

        if (empty($name) || empty($phone)) {
            $err = "Name and Phone number are required.";
        } else {
            if ($pdo) {
                $stmt = $pdo->prepare("UPDATE users SET name = :name, phone = :phone, city = :city, dob = :dob WHERE student_id = :sid");
                $stmt->execute([
                    ':name'  => $name,
                    ':phone' => $phone,
                    ':city'  => $city,
                    ':dob'   => $dob,
                    ':sid'   => $currentUser['student_id']
                ]);

                $_SESSION['studenthub_user']['name'] = $name;
                $_SESSION['studenthub_user']['phone'] = $phone;
                $_SESSION['studenthub_user']['city'] = $city;
                $_SESSION['studenthub_user']['dob'] = $dob;
                $currentUser = $_SESSION['studenthub_user'];

                $msg = "Profile updated successfully in MySQL database!";
            } else {
                $_SESSION['studenthub_user']['name'] = $name;
                $_SESSION['studenthub_user']['phone'] = $phone;
                $msg = "Profile updated in local session mode!";
            }
        }
    }
}

if ($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = :sid LIMIT 1");
    $stmt->execute([':sid' => $currentUser['student_id']]);
    $dbUser = $stmt->fetch();
    if ($dbUser) {
        $currentUser = array_merge($currentUser, $dbUser);
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="card" style="text-align:center;">
    <img src="https://i.pravatar.cc/180?img=12" alt="Student Profile Avatar" style="width:140px;height:140px;border-radius:50%;border:4px solid #005bea;object-fit:cover;box-shadow:0 4px 10px rgba(0,0,0,0.15);">
    <h2 style="margin-top:15px;"><?= htmlspecialchars($currentUser['name']) ?></h2>
    <p style="color:#005bea;font-weight:bold;">
        🎓 <?= htmlspecialchars($currentUser['course'] ?? 'B.Tech Computer Science & Engineering') ?>
    </p>
    <p>
        <span style="background:#e0e7ff;color:#3730a3;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:bold;">
            Role: <?= htmlspecialchars(strtoupper($currentUser['role'] ?? 'STUDENT')) ?>
        </span>
    </p>
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

<div class="card">
    <h2>📋 Student Profile & Academic Record</h2>
    <p>View or modify your personal contact and demographic information below.</p>
    <br>

    <form id="profileForm" action="profile.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

        <table>
            <tbody>
                <tr>
                    <th style="width:30%;"><label for="profName">Full Name</label></th>
                    <td>
                        <input type="text" id="profName" name="name" value="<?= htmlspecialchars($currentUser['name']) ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>Student ID / Roll No</th>
                    <td>
                        <input type="text" value="<?= htmlspecialchars($currentUser['student_id']) ?>" readonly style="background:#f1f5f9;cursor:not-allowed;">
                    </td>
                </tr>
                <tr>
                    <th>Institutional Email</th>
                    <td>
                        <input type="email" value="<?= htmlspecialchars($currentUser['email']) ?>" readonly style="background:#f1f5f9;cursor:not-allowed;">
                    </td>
                </tr>
                <tr>
                    <th><label for="profPhone">Mobile Number</label></th>
                    <td>
                        <input type="tel" id="profPhone" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '+91 9876543210') ?>" required>
                    </td>
                </tr>
                <tr>
                    <th><label for="profCity">Residential City</label></th>
                    <td>
                        <input type="text" id="profCity" name="city" value="<?= htmlspecialchars($currentUser['city'] ?? 'Nadiad') ?>">
                    </td>
                </tr>
                <tr>
                    <th><label for="profDob">Date of Birth</label></th>
                    <td>
                        <input type="date" id="profDob" name="dob" value="<?= htmlspecialchars($currentUser['dob'] ?? '2006-01-12') ?>">
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" style="background:#10b981;">
                💾 Save Changes
            </button>
            <button type="button" onclick="location.href='dashboard.php'">
                ⬅ Back to Dashboard
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
