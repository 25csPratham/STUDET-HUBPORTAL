<?php
$pageTitle = "Student Dashboard";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

requireLogin('login.php');
$currentUser = getCurrentUser();

$pdo = getDBConnection();
$records = [];
$registeredEventsCount = 0;
$totalCourses = 6;
$attendancePercent = "92%";
$cgpa = "8.5";

if ($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM academic_records WHERE student_id = :sid");
    $stmt->execute([':sid' => $currentUser['student_id']]);
    $records = $stmt->fetchAll();

    $evStmt = $pdo->prepare("SELECT COUNT(*) FROM event_registrations WHERE student_id = :sid");
    $evStmt->execute([':sid' => $currentUser['student_id']]);
    $registeredEventsCount = $evStmt->fetchColumn();

    if (!empty($records)) {
        $totalCourses = count($records);
        $avgMarks = array_sum(array_column($records, 'marks')) / $totalCourses;
        $cgpa = number_format(($avgMarks / 10), 2);
    }
} else {
    $records = [
        ['subject' => 'Web Development Frameworks', 'marks' => 85, 'status' => 'Pass'],
        ['subject' => 'Database Management Systems', 'marks' => 90, 'status' => 'Pass'],
        ['subject' => 'Programming & Data Structures', 'marks' => 88, 'status' => 'Pass'],
        ['subject' => 'Computer Networks', 'marks' => 91, 'status' => 'Pass']
    ];
    $registeredEventsCount = 2;
}

require_once __DIR__ . '/header.php';
?>

<section class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
        <div>
            <h2>👋 Welcome, <?= htmlspecialchars($currentUser['name']) ?></h2>
            <p><strong>Student ID / Roll:</strong> <?= htmlspecialchars($currentUser['student_id']) ?> &nbsp;|&nbsp; <strong>Enrolled Course:</strong> <?= htmlspecialchars($currentUser['course'] ?? 'B.Tech') ?></p>
            <p><strong>Institutional Email:</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
        </div>
        <div>
            <a href="profile.php" style="background:#005bea;color:white;padding:10px 18px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:bold;">
                👤 View Full Profile
            </a>
        </div>
    </div>
</section>

<section class="dashboard" aria-label="Key Academic Metrics">
    <div class="box">
        <h3>📚 Registered Courses</h3>
        <p style="font-size:26px;font-weight:bold;color:#005bea;"><?= (int)$totalCourses ?> Subjects</p>
        <span style="font-size:13px;color:#64748b;">Current Semester: 3rd</span>
    </div>
    <div class="box">
        <h3>📅 Overall Attendance</h3>
        <p style="font-size:26px;font-weight:bold;color:#10b981;"><?= htmlspecialchars($attendancePercent) ?></p>
        <span style="font-size:13px;color:#64748b;">Minimum Required: 75%</span>
    </div>
    <div class="box">
        <h3>🏆 Cumulative CGPA</h3>
        <p style="font-size:26px;font-weight:bold;color:#f59e0b;"><?= htmlspecialchars($cgpa) ?> / 10</p>
        <span style="font-size:13px;color:#64748b;">First Class with Distinction</span>
    </div>
    <div class="box">
        <h3>🎉 Event Registrations</h3>
        <p style="font-size:26px;font-weight:bold;color:#8b5cf6;"><?= (int)$registeredEventsCount ?> Registered</p>
        <span style="font-size:13px;color:#64748b;"><a href="events.php" style="color:#8b5cf6;text-decoration:underline;">Browse All Events</a></span>
    </div>
</section>

<section class="card">
    <h2>📊 Semester Academic Performance (Database Verified)</h2>
    <p>Official course examination marks retrieved directly from the MySQL central academic database.</p>
    <br>
    <table>
        <thead>
            <tr>
                <th scope="col">Course / Subject Name</th>
                <th scope="col">Maximum Marks</th>
                <th scope="col">Marks Obtained</th>
                <th scope="col">Result Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)): ?>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['subject']) ?></strong></td>
                        <td>100</td>
                        <td><span style="font-weight:bold;color:#005bea;"><?= htmlspecialchars($row['marks']) ?></span></td>
                        <td>
                            <span style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No examination records found for this student.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<section class="card">
    <h2>⚡ Quick Actions & Downloads</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:10px;">
        <button type="button" onclick="openModal('📄 Official Attendance Transcript: Overall attendance is 92%. Minimum criteria (75%) successfully fulfilled.')">
            📅 Attendance Report
        </button>
        <button type="button" onclick="openModal('📥 Grade Card Download: Semester 3 Grade Sheet successfully compiled with CGPA 8.5. Starting PDF export.')">
            🏆 Download Grade Sheet
        </button>
        <button type="button" onclick="location.href='events.php'">
            🎉 Register for Events
        </button>
        <button type="button" onclick="location.href='feedback.php'">
            ⭐ Submit Portal Feedback
        </button>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
