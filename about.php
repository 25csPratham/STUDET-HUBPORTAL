<?php
$pageTitle = "About Us";
require_once __DIR__ . '/header.php';
?>

<div class="slider" aria-label="About University Highlights">
    <div class="slide active-slide">
        <img src="https://picsum.photos/1200/450?random=10" alt="University Administration Complex">
    </div>
    <div class="slide">
        <img src="https://picsum.photos/1200/450?random=11" alt="Student Technology Auditorium">
    </div>
    <div class="slide">
        <img src="https://picsum.photos/1200/450?random=12" alt="State-of-the-art Computer Lab">
    </div>
    <button class="prev" onclick="prevSlide()" aria-label="Previous Slide">❮</button>
    <button class="next" onclick="nextSlide()" aria-label="Next Slide">❯</button>
</div>

<section class="card">
    <h2>Who We Are?</h2>
    <p>
        Student Hub Portal is a modern, responsive full-stack web application developed for the ITUE203 Web Development Frameworks course at Chandubhai S. Patel Institute of Technology (CSPIT), CHARUSAT.
        The system bridges students, faculty, and administrative departments, eliminating repetitive paperwork and providing transparent, 24/7 access to student services.
    </p>
</section>

<section class="card">
    <h2>Our Mission & Vision</h2>
    <div class="dashboard">
        <div class="box">
            <h3>🎯 Our Mission</h3>
            <p>
                To provide an intuitive, high-performance, and secure digital environment where university students can seamlessly track academic milestones, participate in co-curricular events, and communicate with institutional authorities.
            </p>
        </div>
        <div class="box">
            <h3>🔭 Our Vision</h3>
            <p>
                To pioneer smart campus automation through full-stack web frameworks, incorporating modern responsive web design, robust server-side security, accessible UX (WCAG 2.1), and scalable database architectures.
            </p>
        </div>
    </div>
</section>

<section class="card">
    <h2>Technology Architecture</h2>
    <table>
        <thead>
            <tr>
                <th scope="col">Layer</th>
                <th scope="col">Technology</th>
                <th scope="col">Key Capabilities</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Frontend</strong></td>
                <td>HTML5, CSS3, Modern JavaScript (ES6+)</td>
                <td>Semantic tags, CSS Flexbox/Grid, Dark Mode, AJAX, WCAG 2.1 compliance</td>
            </tr>
            <tr>
                <td><strong>Backend</strong></td>
                <td>PHP 8.x (Native & OOP)</td>
                <td>Session management, BCrypt password hashing, role-based access, CSRF defense</td>
            </tr>
            <tr>
                <td><strong>Database</strong></td>
                <td>MySQL (MariaDB / InnoDB)</td>
                <td>Relational integrity, prepared statements (PDO), parameterized queries</td>
            </tr>
            <tr>
                <td><strong>Deployment</strong></td>
                <td>Apache / XAMPP & GitHub</td>
                <td>Virtual Host (studenthub.local), version control, clean modular codebase</td>
            </tr>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
