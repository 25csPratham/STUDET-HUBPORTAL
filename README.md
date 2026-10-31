# 🎓 StudentHub Portal - Complete Full-Stack Web Application

**Course:** ITUE203 - Web Development Frameworks  
**Institution:** Chandubhai S. Patel Institute of Technology (CSPIT), CHARUSAT  
**Practical 15:** Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment  
**Student Name:** Patel Pratham Bhavik  
**Student ID / Roll:** 25CS076  
**Semester:** 3rd Semester, B.Tech CSE  

---

## 📌 1. Project Overview & Problem Definition

The **StudentHub Portal** is a unified, full-stack digital academic management portal integrating all modules developed across the semester into a single, secure, accessible, and deployable web application.

The portal streamlines core university processes:
- **Authentication & Authorization:** Secure registration and login with session management and Role-Based Access Control (RBAC) separating **Students** and **Administrators**.
- **Academic Performance Tracking:** Dynamic retrieval and display of course enrollments, examination marks, overall attendance, and CGPA from a relational MySQL database.
- **Campus Event Management:** Real-time event publishing, seat capacity tracking, and instantaneous AJAX-based registration without full page reloads.
- **Institutional Communication:** Two-way contact inquiry messaging and 5-star rating feedback system.
- **Modern Accessible UI/UX:** WCAG 2.1 compliant interface with semantic HTML5, CSS Grid/Flexbox responsiveness, accessible modal alerts, dark/light theme switching, and keyboard navigation.

---

## 🏗️ 2. Technology Stack & System Architecture

| Tier | Technologies Used | Core Responsibilities |
| :--- | :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+ AJAX / Fetch API) | Semantic structure, responsive layouts, dynamic DOM updates, dark mode, accessible ARIA attributes |
| **Backend** | PHP 8.x (Native & OOP Architecture) | RESTful API endpoints (`api.php`), session state, CSRF token validation, BCrypt password hashing |
| **Database** | MySQL (MariaDB / InnoDB Engine) | Relational schema (`users`, `courses`, `academic_records`, `events`, `event_registrations`, `contact_messages`, `feedback`) |
| **Server / Host** | Apache (XAMPP) & Apache Virtual Hosts | Local deployment via `http://studenthub.local` and standard `http://localhost/` |
| **VCS / Delivery** | Git & GitHub | Modular version control, structured commits, and cloud project repository |

---

## 📂 3. Repository Directory Structure & Page Inventory

```
StudentHub-Portal/
│
├── studenthub.sql             # Complete database schema and seed data
├── db.php                     # Centralized PDO database connection with error handling
├── session.php                # Secure session configuration, CSRF tokens & RBAC helpers
├── api.php                    # RESTful AJAX controller handling all backend actions
├── header.php                 # Reusable semantic navigation, accessible banner & skip-link
├── footer.php                 # Reusable footer, modal system, theme button & scripts
│
├── index.php / index.html     # Page 1: Home page with campus highlights slider & feature overview
├── about.php / about.html     # Page 2: About page with institutional vision & tech architecture
├── register.php / register.html # Page 3: Student registration with client/server validation
├── login.php / login.html     # Page 4: Secure authentication with test credentials box
├── dashboard.php / dashboard.html # Page 5: Student academic dashboard (courses, attendance, marks)
├── events.php / events.html   # Page 6: Campus events gallery with AJAX registration
├── profile.php / profile.html # Page 7: Student profile with edit and database synchronization
├── contact.php / contact.html # Page 8: Contact inquiry form with headquarters details
├── admin.php / admin.html     # Page 9: Administrator dashboard with student roster & system stats
├── faq.php / faq.html         # Page 10: Frequently Asked Questions with accessible accordion
├── feedback.php / feedback.html # Page 11: 5-star review system with live student testimonial feed
├── logout.php                 # Secure session termination and cookie cleanup
│
├── style.css                  # Master responsive stylesheet (Dark/Light themes, WCAG contrast)
├── script.js                  # Master JavaScript controller (AJAX, validation, sliders, accordion)
├── apache-vhost.conf          # Virtual Host Apache configuration for studenthub.local
├── README.md                  # Comprehensive project documentation (this file)
└── TEST_REPORT.md             # End-to-end verification report with 26 test cases
```

---

## 🔑 4. Practical 15 Evaluation Test Credentials

For evaluation, testing, and grading, use the following pre-seeded test accounts:

| Role | User ID / Roll | Password | Access Capabilities |
| :--- | :--- | :--- | :--- |
| **Student** | `25CS076` | `Student@123` | Dashboard, Grade Sheet, Profile Edit, Event Registration, Feedback |
| **Administrator** | `ADMIN01` | `Admin@123` | Admin Panel, Student Roster, Inquiries, Feedback Audit, System Stats |

---

## 🚀 5. Local Deployment Guide (XAMPP & Apache Virtual Host)

### Option A: Standard XAMPP Deployment (`http://localhost`)
1. Start **Apache** and **MySQL** modules from the **XAMPP Control Panel**.
2. Open your web browser and navigate to **phpMyAdmin**: `http://localhost/phpmyadmin/`.
3. Create a new database named `studenthub`.
4. Click on the **Import** tab and select `studenthub.sql` from this folder, then click **Import**.
5. Copy or symlink this folder to your XAMPP web root (`C:\xampp\htdocs\studenthub`).
6. Access the application in your browser:
   ```
   http://localhost/studenthub/index.php
   ```

### Option B: Apache Virtual Host Deployment (`http://studenthub.local`)
1. Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf` and paste the contents of `apache-vhost.conf`:
   ```apache
   <VirtualHost *:80>
       ServerAdmin admin@studenthub.local
       DocumentRoot "C:/Users/Pratham/OneDrive/Desktop/WDF/StudentHub-Portal"
       ServerName studenthub.local
       ServerAlias www.studenthub.local

       <Directory "C:/Users/Pratham/OneDrive/Desktop/WDF/StudentHub-Portal">
           Options Indexes FollowSymLinks MultiViews
           AllowOverride All
           Require all granted
           DirectoryIndex index.php index.html
       </Directory>
   </VirtualHost>
   ```
2. Open Notepad as **Administrator** and edit `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1    studenthub.local
   127.0.0.1    www.studenthub.local
   ```
3. Restart Apache in XAMPP Control Panel.
4. Open your browser and navigate to:
   ```
   http://studenthub.local
   ```

---

## 🛡️ 6. Security Implementation Checklist

| Security Control | Implementation Mechanism | Status |
| :--- | :--- | :---: |
| **SQL Injection Defense** | 100% prepared statements via PHP PDO with strict parameterized queries | ✅ Passed |
| **Cross-Site Scripting (XSS)** | `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` applied to all user-generated strings | ✅ Passed |
| **Password Storage** | BCrypt one-way password hashing via PHP's native `password_hash()` | ✅ Passed |
| **CSRF Defense** | Cryptographically secure 256-bit anti-CSRF session token verification | ✅ Passed |
| **Session Fixation Defense** | `session_regenerate_id(true)` executed upon successful authentication | ✅ Passed |
| **Role-Based Access Control** | Server-side role inspection in `session.php` restricting `admin.php` | ✅ Passed |
| **Input Validation** | Double-layer validation: client-side regex + server-side filter verification | ✅ Passed |

---

## ♿ 7. Accessibility (A11y / WCAG 2.1) Checklist

- **Semantic HTML5:** Full usage of `<header>`, `<nav>`, `<main>`, `<section>`, and `<footer>` landmarks.
- **Form Association:** Every input, select, and textarea has a matching `<label for="...">`.
- **Keyboard Navigation:** Logical tab indexing, skip-to-content anchor, and Escape key modal dismissal.
- **Color Contrast:** High contrast compliance in both light and dark mode modes (contrast ratio > 4.5:1).
- **ARIA Enhancements:** `aria-expanded`, `aria-label`, `role="region"`, `role="dialog"`, and `role="alert"` attributes.
- **Alt Text:** Descriptive alternative text on all campus and event imagery.

---

## 📦 8. GitHub Repository Submission

- **Repository Name:** `STUDET-HUBPORTAL`
- **Owner / Student:** `25csPratham`
- **Branch:** `main`
- **Submission Scope:** Complete full-stack source code, database dump (`studenthub.sql`), test report (`TEST_REPORT.md`), and Apache configuration.

---
*Developed with pride by Patel Pratham Bhavik (25CS076) for ITUE203 Practical 15.*
