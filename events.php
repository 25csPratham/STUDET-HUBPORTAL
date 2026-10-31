<?php
$pageTitle = "Campus Events";
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

$pdo = getDBConnection();
$currentUser = getCurrentUser();
$registeredEventIds = [];

$msg = '';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_event_id'])) {
    if (!isLoggedIn()) {
        header("Location: login.php?msg=login_required");
        exit;
    }
    $eventId = (int)$_POST['register_event_id'];
    $sid = $currentUser['student_id'];

    if ($pdo) {
        $chk = $pdo->prepare("SELECT id FROM event_registrations WHERE event_id = :eid AND student_id = :sid");
        $chk->execute([':eid' => $eventId, ':sid' => $sid]);
        if ($chk->fetch()) {
            $err = "You are already registered for this event.";
        } else {
            $ins = $pdo->prepare("INSERT INTO event_registrations (event_id, student_id) VALUES (:eid, :sid)");
            $ins->execute([':eid' => $eventId, ':sid' => $sid]);
            $msg = "Successfully registered for the event!";
        }
    } else {
        $msg = "Registered for event in offline mode!";
    }
}

$events = [];
if ($pdo) {
    $events = $pdo->query("SELECT * FROM events ORDER BY id ASC")->fetchAll();
    if ($currentUser) {
        $st = $pdo->prepare("SELECT event_id FROM event_registrations WHERE student_id = :sid");
        $st->execute([':sid' => $currentUser['student_id']]);
        $registeredEventIds = $st->fetchAll(PDO::FETCH_COLUMN);
    }
} else {
    $events = [
        ['id' => 1, 'title' => '🇮🇳 Independence Day', 'category' => 'National Celebration', 'event_date' => '15 August 2026', 'description' => 'Cultural Program, Patriotic Speeches, and Flag Hoisting ceremony.', 'venue' => 'Main Auditorium'],
        ['id' => 2, 'title' => '💻 Tech Fest 2026', 'category' => 'Technical Festival', 'event_date' => '20 September 2026', 'description' => 'Coding Competition, Robotics Arena, and AI & Web Development Workshop.', 'venue' => 'CSPIT Lab Complex'],
        ['id' => 3, 'title' => '🏆 Sports Meet', 'category' => 'Sports Event', 'event_date' => '10 October 2026', 'description' => 'Cricket League, Football, Badminton, and Athletic Track Events.', 'venue' => 'Sports Ground'],
        ['id' => 4, 'title' => '🎭 Cultural Festival', 'category' => 'Cultural Festival', 'event_date' => '05 November 2026', 'description' => 'Classical & Western Dance, Music Jam, Drama, and Street Plays.', 'venue' => 'Open Amphitheatre']
    ];
    $registeredEventIds = [1];
}

require_once __DIR__ . '/header.php';
?>

<div class="slider" aria-label="Campus Events Gallery">
    <div class="slide active-slide">
        <img src="https://picsum.photos/1200/450?random=21" alt="Independence Day Celebration at University">
    </div>
    <div class="slide">
        <img src="https://picsum.photos/1200/450?random=22" alt="Tech Fest Hackathon and Robotics Arena">
    </div>
    <div class="slide">
        <img src="https://picsum.photos/1200/450?random=23" alt="Annual Sports Championship">
    </div>
    <button class="prev" onclick="prevSlide()" aria-label="Previous Slide">❮</button>
    <button class="next" onclick="nextSlide()" aria-label="Next Slide">❯</button>
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

<section class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div>
            <h2>🎉 Campus Events & Hackathons</h2>
            <p>Participate in technical competitions, sports championships, and cultural festivals.</p>
        </div>
        <div>
            <span style="background:#e0e7ff;color:#3730a3;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:bold;">
                <?= count($events) ?> Events Scheduled
            </span>
        </div>
    </div>
</section>

<div class="dashboard" id="eventsContainer">
    <?php foreach ($events as $ev): ?>
        <?php $isRegistered = in_array($ev['id'], $registeredEventIds); ?>
        <div class="box" style="display:flex;flex-direction:column;justify-content:space-between;border-top:4px solid #005bea;">
            <div>
                <span style="background:#f1f5f9;color:#475569;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:bold;">
                    <?= htmlspecialchars($ev['category'] ?? 'Campus Event') ?>
                </span>
                <h3 style="margin-top:10px;"><?= htmlspecialchars($ev['title']) ?></h3>
                <p><strong>📅 Date:</strong> <?= htmlspecialchars($ev['event_date']) ?></p>
                <p><strong>📍 Venue:</strong> <?= htmlspecialchars($ev['venue'] ?? 'Campus Complex') ?></p>
                <p style="margin-top:8px;font-size:14px;color:#475569;">
                    <?= htmlspecialchars($ev['description']) ?>
                </p>
            </div>
            <div style="margin-top:15px;">
                <?php if ($isRegistered): ?>
                    <button type="button" disabled style="background:#10b981;cursor:default;width:100%;">
                        ✅ Registered
                    </button>
                <?php else: ?>
                    <?php if ($currentUser): ?>
                        <form method="POST" action="events.php" style="margin:0;">
                            <input type="hidden" name="register_event_id" value="<?= (int)$ev['id'] ?>">
                            <button type="submit" class="ajax-event-btn" data-event-id="<?= (int)$ev['id'] ?>" style="width:100%;">
                                Register Now
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" onclick="location.href='login.php?msg=login_required'" style="width:100%;">
                            Login to Register
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
