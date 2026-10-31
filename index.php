<?php
$pageTitle = "Home";
require_once __DIR__ . '/header.php';
?>

<div class="slider" aria-label="Campus Highlights">
    <div class="slide active-slide">
        <img src="https://picsum.photos/1200/450?random=1" alt="CSPIT Modern University Campus">
    </div>
    <div class="slide">
        <img src="https://picsum.photos/1200/450?random=2" alt="Central Academic Library and Study Hall">
    </div>
    <div class="slide">
        <img src="https://picsum.photos/1200/450?random=3" alt="Students in Collaborative Computing Lab">
    </div>
    <button class="prev" onclick="prevSlide()" aria-label="Previous Slide">❮</button>
    <button class="next" onclick="nextSlide()" aria-label="Next Slide">❯</button>
</div>

<section class="card" aria-labelledby="welcome-heading">
    <h2 id="welcome-heading">Welcome To Student Hub Portal</h2>
    <p>
        Student Hub Portal is a unified digital academic platform designed for college students, faculty, and administrators. 
        It streamlines course registration, academic performance monitoring, event participation, profile management, and two-way institutional communication in one secure, accessible interface.
    </p>
    <br>
    <button type="button" onclick="openModal('Explore all features: view your attendance, register for upcoming hackathons, and download semester grade cards!')">
        Learn More
    </button>
</section>

<section class="card" aria-labelledby="features-heading">
    <h2 id="features-heading">Core Integrated Features</h2>
    <div class="dashboard">
        <div class="box">
            <h3>📝 Online Registration</h3>
            <p>Self-service student account registration with server-side validation and password hashing.</p>
        </div>
        <div class="box">
            <h3>🔐 Secure Authentication</h3>
            <p>Session-based login with role-based access control (Student vs. Administrator).</p>
        </div>
        <div class="box">
            <h3>📊 Live Dashboard</h3>
            <p>Real-time academic performance, registered courses, attendance tracking, and CGPA overview.</p>
        </div>
        <div class="box">
            <h3>🎉 Campus Events</h3>
            <p>Interactive event registrations with instant AJAX feedback and participation status.</p>
        </div>
        <div class="box">
            <h3>👤 Student Profile</h3>
            <p>Dynamic profile updates, personal details, contact information, and academic credentials.</p>
        </div>
        <div class="box">
            <h3>🛡️ Admin Control</h3>
            <p>Dedicated administration dashboard for managing students, tracking inquiries, and auditing feedbacks.</p>
        </div>
    </div>
</section>

<section class="card" style="text-align:center;">
    <h2>Portal Quick Statistics</h2>
    <div class="dashboard" style="margin-top:15px;">
        <div class="box">
            <h3 style="color:#005bea;font-size:32px;">1,250+</h3>
            <p>Enrolled Students</p>
        </div>
        <div class="box">
            <h3 style="color:#005bea;font-size:32px;">48+</h3>
            <p>Active Courses</p>
        </div>
        <div class="box">
            <h3 style="color:#005bea;font-size:32px;">98.4%</h3>
            <p>System Availability</p>
        </div>
        <div class="box">
            <h3 style="color:#005bea;font-size:32px;">100%</h3>
            <p>WCAG & Security Checked</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
