<?php
require_once __DIR__ . '/session.php';
$currentUser = getCurrentUser();
$role = getUserRole();
$activePage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StudentHub Portal - Complete Student Management System for ITUE203 Web Development Frameworks">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - StudentHub Portal' : 'Student Hub Portal' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to Main Content</a>

    <div class="notification" id="notification" role="region" aria-label="Campus Announcement">
        <div>
            🎉 Welcome to <strong>Student Hub Portal</strong>! Admissions & Semester Registration are now live.
            <?php if ($currentUser): ?>
                &nbsp;|&nbsp; Welcome, <strong><?= htmlspecialchars($currentUser['name']) ?></strong> (<?= htmlspecialchars(ucfirst($currentUser['role'])) ?>)
            <?php endif; ?>
        </div>
        <button onclick="closeBanner()" aria-label="Close Announcement Banner">✕</button>
    </div>

    <header role="banner">
        <h1>🎓 Student Hub Portal</h1>
        <p>Your Complete Student Management & Academic Information System</p>
    </header>

    <nav role="navigation" aria-label="Primary Navigation">
        <div class="menu-btn" onclick="toggleMenu()" aria-label="Toggle navigation menu" role="button" tabindex="0">
            ☰
        </div>
        <div class="nav-links">
            <a href="index.php" class="<?= ($activePage === 'index.php') ? 'active' : '' ?>">Home</a>
            <a href="about.php" class="<?= ($activePage === 'about.php') ? 'active' : '' ?>">About</a>
            
            <?php if (!$currentUser): ?>
                <a href="register.php" class="<?= ($activePage === 'register.php') ? 'active' : '' ?>">Register</a>
                <a href="login.php" class="<?= ($activePage === 'login.php') ? 'active' : '' ?>">Login</a>
            <?php else: ?>
                <a href="dashboard.php" class="<?= ($activePage === 'dashboard.php') ? 'active' : '' ?>">Dashboard</a>
                <a href="profile.php" class="<?= ($activePage === 'profile.php') ? 'active' : '' ?>">Profile</a>
                <?php if ($role === 'admin'): ?>
                    <a href="admin.php" class="<?= ($activePage === 'admin.php') ? 'active' : '' ?>">Admin Panel</a>
                <?php endif; ?>
            <?php endif; ?>

            <a href="events.php" class="<?= ($activePage === 'events.php') ? 'active' : '' ?>">Events</a>
            <a href="contact.php" class="<?= ($activePage === 'contact.php') ? 'active' : '' ?>">Contact</a>
            <a href="faq.php" class="<?= ($activePage === 'faq.php') ? 'active' : '' ?>">FAQ</a>
            <a href="feedback.php" class="<?= ($activePage === 'feedback.php') ? 'active' : '' ?>">Feedback</a>

            <?php if ($currentUser): ?>
                <a href="logout.php" style="background:#e74c3c;color:white;border-radius:4px;padding:6px 14px;">Logout</a>
            <?php endif; ?>
        </div>
    </nav>

    <main id="main-content" class="container" role="main">
