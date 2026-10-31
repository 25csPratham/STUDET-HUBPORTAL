# 🧪 StudentHub Portal - Comprehensive Test Report & Execution Matrix

**Course:** ITUE203 - Web Development Frameworks  
**Practical 15:** Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment  
**Student:** 25CS076 - Patel Pratham Bhavik  
**Test Environment:** Windows 11, XAMPP 8.2 (Apache 2.4, MariaDB 10.4, PHP 8.2), Chrome 129, Firefox 131  
**Test Date:** October 2026  
**Execution Result:** 26 / 26 Test Cases Passed (100% Success Rate)  

---

## 📋 1. Test Objectives & Scope

The testing process verifies end-to-end functionality, system integration, database integrity, user role authorization, security safeguards, accessibility compliance, and local deployment stability of the **StudentHub Portal**.

---

## 📊 2. Master Test Cases & Execution Matrix

| TC ID | Test Scenario | Test Input / Action | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-01** | Navigation Link Integrity | Click every link across all 11 pages (Home to Feedback) | All pages load with HTTP 200 without broken links | All 11 pages linked and navigable | **PASS** |
| **TC-02** | Active Navigation Highlighting | Visit `about.php` or `events.php` | The corresponding link receives `.active` class with blue indicator | Highlighted correctly | **PASS** |
| **TC-03** | Mobile Responsive Menu | Viewport resized to 375px (mobile) and toggle hamburger ☰ | Menu expands and collapses smoothly on click | Responsive navigation works | **PASS** |
| **TC-04** | Dark/Light Mode Switcher | Click floating 🌙/☀️ toggle button | Body toggles `.dark`, colors invert, state saved in localStorage | Theme toggles and persists | **PASS** |
| **TC-05** | Image Slider Controls | Click ❮ / ❯ buttons and wait for 5-sec auto timer | Slide transitions seamlessly with fade animation; pauses on hover | Slider functions properly | **PASS** |
| **TC-06** | Student Registration Validation | Submit registration form with empty inputs | Browser/form prevents submission with "Please fill in all fields" | Client validation triggers | **PASS** |
| **TC-07** | Password Confirmation Match | Enter password `secret1` and confirm password `secret2` | Alert modal: "Passwords do not match. Please verify." | Mismatch blocked | **PASS** |
| **TC-08** | Valid Student Registration | Enter valid name, email, roll `25CS999`, B.Tech, pass `Student@123` | Record inserted into MySQL `users` with BCrypt hash; redirect to login | Successfully registered | **PASS** |
| **TC-09** | Duplicate Roll Registration | Attempt registering `25CS076` again | Error: "Student ID or Email is already registered" | Duplicate rejected | **PASS** |
| **TC-10** | Student Login Authentication | Login with `25CS076` / `Student@123` | Authenticated via `password_verify()`; session created; redirected to `dashboard.php` | Successfully logged in | **PASS** |
| **TC-11** | Invalid Credentials Rejection | Login with `25CS076` / `WrongPass!` | Access denied: "Invalid Student ID or Password." | Unauthorized login blocked | **PASS** |
| **TC-12** | Administrator Login & Redirect | Login with `ADMIN01` / `Admin@123` | Authenticated with `role='admin'`; redirected to `admin.php` | Admin panel loaded | **PASS** |
| **TC-13** | Student RBAC Access Guard | Logged in as student `25CS076`, navigate directly to `admin.php` | Access blocked by `requireRole('admin')`; redirected with unauthorized warning | Restricted page protected | **PASS** |
| **TC-14** | Guest Dashboard Protection | Open `dashboard.php` without active login session | Redirected immediately to `login.php` via `requireLogin()` | Protected page guarded | **PASS** |
| **TC-15** | Dynamic Dashboard Retrieval | View `dashboard.php` as `25CS076` | Loads real MySQL records: 6 subjects, 92% attendance, CGPA 8.5 | Accurate database output | **PASS** |
| **TC-16** | Profile Information Update | Edit phone number and city in `profile.php`, click Save | MySQL `users` record updated; session synced; success banner shown | Profile updated in DB | **PASS** |
| **TC-17** | Events List Rendering | Navigate to `events.php` | All scheduled events retrieved and displayed with date, venue, details | Events dynamically rendered | **PASS** |
| **TC-18** | AJAX Event Registration | Click "Register Now" on Tech Fest 2026 as logged-in student | AJAX request sent to `api.php`; row added to `event_registrations`; button turns to "✅ Registered" | Registered without reload | **PASS** |
| **TC-19** | Duplicate Event Registration | Attempt registering for already-registered event | Alert: "You are already registered for this event." | Duplicate registration prevented | **PASS** |
| **TC-20** | Contact Form Submission | Submit query with Name, Email, Subject, Message | Stored in MySQL `contact_messages`; success confirmation displayed | Inquiry saved in DB | **PASS** |
| **TC-21** | Feedback Star Rating Submission | Select 5 stars, write review, click Submit | Record added to MySQL `feedback`; review appears in recent reviews feed | Feedback saved in DB | **PASS** |
| **TC-22** | SQL Injection Attack Defense | Input `' OR '1'='1' --` into Student ID and password fields | PDO prepared statement parameterizes input; rejected as invalid credentials | Zero SQL injection vulnerability | **PASS** |
| **TC-23** | Cross-Site Scripting (XSS) Attack | Submit `<script>alert('XSS')</script>` in contact/feedback | Input escaped via `htmlspecialchars()`; rendered as safe literal text | Script execution blocked | **PASS** |
| **TC-24** | CSRF Token Tampering Test | Alter hidden `csrf_token` in form post | Form rejected with "Security validation failed. Please refresh." | Tampering blocked | **PASS** |
| **TC-25** | Accessible FAQ Accordion | Click questions in `faq.php` or `faq.html` | Content toggles smoothly; `aria-expanded` updates; only one open at a time | Accordion functions cleanly | **PASS** |
| **TC-26** | User Session Termination (Logout) | Click "Logout" button | Session destroyed, session cookie cleared, redirected to `login.php?msg=logged_out` | Secure logout verified | **PASS** |

---

## 🔒 3. Security Audit Findings

| Category | Vulnerability Checked | Result / Mitigations |
| :--- | :--- | :--- |
| **CWE-89** | SQL Injection | **Protected:** All queries use PDO with bound parameters. Zero raw string concatenation. |
| **CWE-79** | Stored & Reflected XSS | **Protected:** All dynamic echo statements use `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`. |
| **CWE-352** | Cross-Site Request Forgery | **Protected:** 256-bit cryptographically random tokens stored in session and validated on POST. |
| **CWE-384** | Session Fixation | **Protected:** `session_regenerate_id(true)` executed upon credential validation. |
| **CWE-285** | Improper Authorization | **Protected:** Strict role checking in `session.php` before rendering administrative features. |
| **CWE-256** | Plaintext Password Storage | **Protected:** BCrypt one-way hashing (`PASSWORD_BCRYPT`, cost factor 10). |

---

## ♿ 4. Accessibility (WCAG 2.1 AA) Compliance Summary

1. **Perceivable:**
   - Color contrast ratio for text exceeds 4.5:1 against light and dark backgrounds.
   - Descriptive `alt` attributes on all images (`Campus`, `Library`, `Hackathon`, `Sports`).
2. **Operable:**
   - All interactive controls (links, buttons, accordion toggles, inputs) are fully keyboard navigable via `Tab` and `Enter/Space`.
   - Skip to main content link (`.skip-link`) allows keyboard users to bypass navigation.
   - Modals can be dismissed using the `Escape` key or backdrop clicks.
3. **Understandable:**
   - Plain language error and success alerts.
   - Form controls have explicit `<label for="...">` associations.
4. **Robust:**
   - Valid HTML5 semantic structure compatible with modern screen readers (NVDA, JAWS).

---

## 🏁 5. Final Evaluation Statement

The **StudentHub Portal** has successfully passed all 26 verification test cases covering client-side interactivity, server-side processing, database persistence, role enforcement, security hardening, and accessibility standards. The portal is fully prepared for Practical 15 demonstration, viva voce examination, and GitHub submission.
