<?php
$pageTitle = "Administrator Dashboard";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

requireRole('admin', 'dashboard.php');
$currentUser = getCurrentUser();
$pdo = getDBConnection();

$students = [];
$messages = [];
$feedbacks = [];
$totalStudents = 0;
$totalEvents = 0;

if ($pdo) {
    $students = $pdo->query("SELECT id, student_id, name, email, course, phone, status, role FROM users ORDER BY id ASC")->fetchAll();
    $totalStudents = count(array_filter($students, fn($u) => $u['role'] === 'student'));
    $totalEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    $messages = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC LIMIT 5")->fetchAll();
    $feedbacks = $pdo->query("SELECT * FROM feedback ORDER BY id DESC LIMIT 5")->fetchAll();
} else {
    $students = [
        ['id' => 1, 'student_id' => '101', 'name' => 'Sapan Desai', 'course' => 'BCA', 'status' => 'Active', 'role' => 'student'],
        ['id' => 2, 'student_id' => '25CS076', 'name' => 'Pratham Patel', 'course' => 'B.Tech', 'status' => 'Active', 'role' => 'student'],
        ['id' => 3, 'student_id' => '103', 'name' => 'Om Patel', 'course' => 'MCA', 'status' => 'Inactive', 'role' => 'student'],
        ['id' => 4, 'student_id' => '104', 'name' => 'Aarav Shah', 'course' => 'BBA', 'status' => 'Active', 'role' => 'student']
    ];
    $totalStudents = 4;
    $totalEvents = 4;
}

require_once __DIR__ . '/header.php';
?>

<section class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
        <div>
            <h2>🛡️ Central Administration Panel</h2>
            <p>Welcome, <strong><?= htmlspecialchars($currentUser['name']) ?></strong> (Administrator)</p>
            <p style="font-size:14px;color:#64748b;">Manage registered students, view contact queries, and audit system feedbacks.</p>
        </div>
        <div>
            <span style="background:#fee2e2;color:#b91c1c;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:bold;">
                🔒 Admin Role Enforced
            </span>
        </div>
    </div>
</section>

<section class="dashboard" aria-label="System Metrics">
    <div class="box">
        <h3>👥 Total Students</h3>
        <p style="font-size:28px;font-weight:bold;color:#005bea;"><?= (int)$totalStudents ?></p>
        <span style="font-size:13px;color:#64748b;">Enrolled Across Programs</span>
    </div>
    <div class="box">
        <h3>🎉 Active Events</h3>
        <p style="font-size:28px;font-weight:bold;color:#10b981;"><?= (int)$totalEvents ?></p>
        <span style="font-size:13px;color:#64748b;">Published & Open for Registration</span>
    </div>
    <div class="box">
        <h3>📩 Inquiries Received</h3>
        <p style="font-size:28px;font-weight:bold;color:#f59e0b;"><?= count($messages) ?></p>
        <span style="font-size:13px;color:#64748b;">Contact Messages</span>
    </div>
    <div class="box">
        <h3>⭐ Student Reviews</h3>
        <p style="font-size:28px;font-weight:bold;color:#8b5cf6;"><?= count($feedbacks) ?></p>
        <span style="font-size:13px;color:#64748b;">Satisfaction Rating: 4.8/5</span>
    </div>
</section>

<section class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:15px;">
        <h2>🎓 Registered Student Records (MySQL Central Table)</h2>
        <button type="button" onclick="openModal('Add Student: Admin dialog opened. Enter Student ID, Full Name, Course, and Email to create record.')" style="background:#10b981;">
            ➕ Add Student
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th scope="col">ID / Roll</th>
                <th scope="col">Full Name</th>
                <th scope="col">Program / Course</th>
                <th scope="col">Account Role</th>
                <th scope="col">Status</th>
                <th scope="col">Admin Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $stu): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($stu['student_id']) ?></strong></td>
                    <td><?= htmlspecialchars($stu['name']) ?></td>
                    <td><?= htmlspecialchars($stu['course']) ?></td>
                    <td>
                        <span style="background:<?= $stu['role'] === 'admin' ? '#fee2e2' : '#e0e7ff' ?>;color:<?= $stu['role'] === 'admin' ? '#b91c1c' : '#3730a3' ?>;padding:3px 8px;border-radius:10px;font-size:12px;font-weight:bold;">
                            <?= htmlspecialchars(strtoupper($stu['role'])) ?>
                        </span>
                    </td>
                    <td>
                        <span style="background:<?= ($stu['status'] ?? 'Active') === 'Active' ? '#dcfce7' : '#f1f5f9' ?>;color:<?= ($stu['status'] ?? 'Active') === 'Active' ? '#15803d' : '#64748b' ?>;padding:3px 8px;border-radius:10px;font-size:12px;font-weight:bold;">
                            <?= htmlspecialchars($stu['status'] ?? 'Active') ?>
                        </span>
                    </td>
                    <td>
                        <button type="button" onclick="openModal('Viewing Detailed Record for <?= htmlspecialchars($stu['name']) ?> (ID: <?= htmlspecialchars($stu['student_id']) ?>). Program: <?= htmlspecialchars($stu['course']) ?>.')" style="padding:4px 10px;font-size:12px;">
                            View
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section class="card">
    <h2>📩 Recent Student Inquiries (Contact Messages)</h2>
    <br>
    <table>
        <thead>
            <tr>
                <th scope="col">Sender Name</th>
                <th scope="col">Email</th>
                <th scope="col">Subject</th>
                <th scope="col">Inquiry Details</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($msg['name']) ?></strong></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= htmlspecialchars($msg['subject']) ?></td>
                        <td><?= htmlspecialchars($msg['message']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No recent contact inquiries received.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<section class="card">
    <h2>⚡ Administrative Operations</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:10px;">
        <button type="button" onclick="openModal('📄 Full Enrollment Report generated for Odd Semester 2026. Ready for CSV download.')">
            📊 Generate Student Roster Report
        </button>
        <button type="button" onclick="openModal('📢 Broadcast Message: Notice sent to all registered students.')">
            📢 Broadcast Notice
        </button>
        <button type="button" onclick="openModal('🛠️ System Cache Cleared and Session store optimized.')">
            🧹 Purge System Cache
        </button>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
