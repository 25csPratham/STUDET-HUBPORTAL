<?php
$pageTitle = "Portal Feedback";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

$msg = '';
$err = '';
$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $err = "Security validation token expired. Please refresh.";
    } else {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $rating   = (int)($_POST['rating'] ?? 5);
        $comments = trim($_POST['comments'] ?? '');

        if (empty($comments)) {
            $err = "Please provide your comments or suggestions.";
        } else {
            if ($rating < 1 || $rating > 5) $rating = 5;
            if ($pdo) {
                $ins = $pdo->prepare("INSERT INTO feedback (name, email, rating, comments) VALUES (:name, :email, :rating, :comm)");
                $ins->execute([
                    ':name'   => $name ?: ($currentUser['name'] ?? 'Anonymous Student'),
                    ':email'  => $email ?: ($currentUser['email'] ?? 'student@studenthub.com'),
                    ':rating' => $rating,
                    ':comm'   => $comments
                ]);
            }
            $msg = "Thank you for your valuable feedback! Your review helps us continuously improve the StudentHub experience.";
        }
    }
}

// Fetch recent feedback entries
$recentFeedback = [];
if ($pdo) {
    $recentFeedback = $pdo->query("SELECT * FROM feedback ORDER BY id DESC LIMIT 5")->fetchAll();
} else {
    $recentFeedback = [
        ['name' => 'Pratham Patel', 'rating' => 5, 'comments' => 'StudentHub Portal makes tracking academic attendance, grades, and event registrations effortless! The dark mode is great.', 'created_at' => '2026-10-30'],
        ['name' => 'Sapan Desai', 'rating' => 4, 'comments' => 'Great UI design. Would love to see an integrated mobile push notification system for upcoming events.', 'created_at' => '2026-10-28']
    ];
}

require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>⭐ Student Feedback & Review</h2>
    <p>We value your suggestions! Let us know about your experience using the Student Hub Portal.</p>
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

<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:20px;">
    <!-- Feedback Submission Form -->
    <div class="card">
        <h3>📝 Submit Your Feedback</h3>
        <br>
        <form id="feedbackForm" action="feedback.php" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <label for="fbName"><strong>Your Name (Optional)</strong></label>
            <input type="text" id="fbName" name="name" placeholder="Enter Name" autocomplete="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>">

            <label for="fbEmail"><strong>Email Address (Optional)</strong></label>
            <input type="email" id="fbEmail" name="email" placeholder="Enter Email" autocomplete="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>">

            <label for="fbRating"><strong>Overall Experience Rating <span style="color:red;">*</span></strong></label>
            <select id="fbRating" name="rating" required>
                <option value="5" selected>⭐⭐⭐⭐⭐ (5/5) - Excellent Experience</option>
                <option value="4">⭐⭐⭐⭐ (4/5) - Very Good</option>
                <option value="3">⭐⭐⭐ (3/5) - Good / Satisfactory</option>
                <option value="2">⭐⭐ (2/5) - Needs Improvement</option>
                <option value="1">⭐ (1/5) - Poor Experience</option>
            </select>

            <label for="fbComments"><strong>Feedback & Suggestions <span style="color:red;">*</span></strong></label>
            <textarea id="fbComments" name="comments" rows="5" placeholder="Share your suggestions, feature requests, or report any issues..." required></textarea>

            <button type="submit" id="feedbackBtn" style="margin-top:10px;width:100%;">Submit Feedback</button>
        </form>
    </div>

    <!-- Recent Student Reviews -->
    <div class="card">
        <h3>💬 Recent Student Reviews</h3>
        <br>
        <div id="recentFeedbackList">
            <?php foreach ($recentFeedback as $fb): ?>
                <div style="padding:12px;border-bottom:1px solid #e2e8f0;margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <strong><?= htmlspecialchars($fb['name']) ?></strong>
                        <span style="color:#f59e0b;font-size:14px;">
                            <?= str_repeat('⭐', (int)$fb['rating']) ?>
                        </span>
                    </div>
                    <p style="margin-top:6px;font-size:14px;color:#475569;">
                        "<?= htmlspecialchars($fb['comments']) ?>"
                    </p>
                    <span style="font-size:12px;color:#94a3b8;">
                        Posted on: <?= htmlspecialchars(substr($fb['created_at'] ?? '2026-10-30', 0, 10)) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
