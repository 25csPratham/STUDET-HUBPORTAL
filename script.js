/**
 * StudentHub Portal - Master JavaScript Controller
 * Course: ITUE203 - Web Development Frameworks
 * Practical 15: AJAX Functionality, Client-Side Validations, Dynamic UI & Accessibility
 */

document.addEventListener("DOMContentLoaded", function () {
    initTheme();
    initMobileNav();
    initNotificationBanner();
    initSlider();
    initFaqAccordion();
    initForms();
    initScrollToTop();
    highlightActiveNav();
});

function initTheme() {
    const themeBtn = document.getElementById("themeBtn");
    const savedTheme = localStorage.getItem("studentHubTheme");

    if (savedTheme === "dark") {
        document.body.classList.add("dark");
        if (themeBtn) themeBtn.textContent = "☀️";
    }

    if (themeBtn) {
        themeBtn.addEventListener("click", function () {
            document.body.classList.toggle("dark");
            const isDark = document.body.classList.contains("dark");
            localStorage.setItem("studentHubTheme", isDark ? "dark" : "light");
            themeBtn.textContent = isDark ? "☀️" : "🌙";
        });
    }
}

function initMobileNav() {
    const menuBtn = document.querySelector(".menu-btn");
    const navLinks = document.querySelector(".nav-links");

    if (menuBtn && navLinks) {
        menuBtn.addEventListener("click", function () {
            navLinks.classList.toggle("active");
            const expanded = navLinks.classList.contains("active");
            menuBtn.setAttribute("aria-expanded", expanded);
        });
    }
}

function toggleMenu() {
    const navLinks = document.querySelector(".nav-links");
    if (navLinks) {
        navLinks.classList.toggle("active");
    }
}

function initNotificationBanner() {
    const banner = document.getElementById("notification");
    if (banner && sessionStorage.getItem("studentHubBannerClosed") === "true") {
        banner.style.display = "none";
    }
}

function closeBanner() {
    const banner = document.getElementById("notification");
    if (banner) {
        banner.style.display = "none";
        sessionStorage.setItem("studentHubBannerClosed", "true");
    }
}

function highlightActiveNav() {
    const currentPath = window.location.pathname.split("/").pop() || "index.php";
    const links = document.querySelectorAll("nav a");

    links.forEach(function (link) {
        const href = link.getAttribute("href");
        if (!href) return;
        const page = href.split("/").pop();
        const baseCurrent = currentPath.replace(/\.(php|html)$/, "");
        const basePage = page.replace(/\.(php|html)$/, "");

        if (baseCurrent === basePage && baseCurrent !== "") {
            link.classList.add("active");
        }
    });
}

function openModal(message, title) {
    const modal = document.getElementById("modal");
    const modalText = document.getElementById("modalText");
    const modalTitle = document.getElementById("modalTitle");

    if (modal && modalText) {
        modalText.textContent = message;
        if (modalTitle && title) {
            modalTitle.textContent = title;
        }
        modal.style.display = "flex";
        const closeBtn = modal.querySelector(".close") || modal.querySelector("button");
        if (closeBtn) closeBtn.focus();
    } else {
        alert(message);
    }
}

function closeModal() {
    const modal = document.getElementById("modal");
    if (modal) {
        modal.style.display = "none";
    }
}

window.addEventListener("click", function (e) {
    const modal = document.getElementById("modal");
    if (e.target === modal) {
        closeModal();
    }
});

document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeModal();
    }
});

let currentSlideIndex = 0;
let slideInterval = null;

function initSlider() {
    const slides = document.querySelectorAll(".slide");
    if (slides.length === 0) return;

    showSlide(currentSlideIndex);
    startSlideTimer();

    const sliderContainer = document.querySelector(".slider");
    if (sliderContainer) {
        sliderContainer.addEventListener("mouseenter", function () {
            clearInterval(slideInterval);
        });
        sliderContainer.addEventListener("mouseleave", function () {
            startSlideTimer();
        });
    }
}

function startSlideTimer() {
    clearInterval(slideInterval);
    slideInterval = setInterval(function () {
        nextSlide();
    }, 5000);
}

function showSlide(index) {
    const slides = document.querySelectorAll(".slide");
    if (slides.length === 0) return;

    if (index >= slides.length) currentSlideIndex = 0;
    else if (index < 0) currentSlideIndex = slides.length - 1;
    else currentSlideIndex = index;

    slides.forEach(function (slide, idx) {
        slide.classList.remove("active-slide");
        if (idx === currentSlideIndex) {
            slide.classList.add("active-slide");
        }
    });
}

function nextSlide() {
    showSlide(currentSlideIndex + 1);
}

function prevSlide() {
    showSlide(currentSlideIndex - 1);
}

function initFaqAccordion() {
    const faqButtons = document.querySelectorAll(".faq-question");

    faqButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const answer = button.nextElementSibling;
            const isExpanded = button.getAttribute("aria-expanded") === "true";

            faqButtons.forEach(function (otherBtn) {
                if (otherBtn !== button) {
                    otherBtn.setAttribute("aria-expanded", "false");
                    const otherAnswer = otherBtn.nextElementSibling;
                    if (otherAnswer) otherAnswer.style.display = "none";
                    const icon = otherBtn.querySelector(".faq-icon");
                    if (icon) icon.textContent = "➕";
                }
            });

            if (isExpanded) {
                button.setAttribute("aria-expanded", "false");
                if (answer) answer.style.display = "none";
                const icon = button.querySelector(".faq-icon");
                if (icon) icon.textContent = "➕";
            } else {
                button.setAttribute("aria-expanded", "true");
                if (answer) answer.style.display = "block";
                const icon = button.querySelector(".faq-icon");
                if (icon) icon.textContent = "➖";
            }
        });
    });
}

function initForms() {
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            const sidInput = document.getElementById("loginStudentId") || document.getElementById("studentId");
            const pwdInput = document.getElementById("loginPassword") || document.getElementById("password");

            const studentId = sidInput ? sidInput.value.trim() : "";
            const password = pwdInput ? pwdInput.value : "";

            if (!studentId || !password) {
                e.preventDefault();
                openModal("Please enter both Student ID and Password.", "Authentication Error");
                return;
            }

            if (window.location.pathname.endsWith(".html") || loginForm.dataset.useAjax === "true") {
                e.preventDefault();
                fetch("api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "login", studentId: studentId, password: password })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        localStorage.setItem("studentHubUser", JSON.stringify(data.user));
                        localStorage.setItem("studentHubLoggedIn", "true");
                        openModal(data.message || "Login successful!", "Success");
                        setTimeout(() => {
                            window.location.href = data.redirect || "dashboard.html";
                        }, 1000);
                    } else {
                        openModal(data.message || "Invalid credentials.", "Login Failed");
                    }
                })
                .catch(() => {
                    if ((studentId === "25CS076" && password === "Student@123") || (studentId === "ADMIN01" && password === "Admin@123")) {
                        const role = (studentId === "ADMIN01") ? "admin" : "student";
                        localStorage.setItem("studentHubLoggedIn", "true");
                        localStorage.setItem("studentHubUser", JSON.stringify({
                            student_id: studentId,
                            name: (studentId === "ADMIN01") ? "System Administrator" : "Pratham Patel",
                            role: role
                        }));
                        openModal("Login successful (Demo Mode)!", "Success");
                        setTimeout(() => {
                            window.location.href = (role === "admin") ? "admin.html" : "dashboard.html";
                        }, 1000);
                    } else {
                        openModal("Invalid credentials. Try Student ID: 25CS076 / Password: Student@123", "Login Failed");
                    }
                });
            }
        });
    }

    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            const name = document.getElementById("name")?.value.trim();
            const email = document.getElementById("email")?.value.trim();
            const studentId = document.getElementById("studentId")?.value.trim();
            const course = document.getElementById("course")?.value;
            const phone = document.getElementById("phone")?.value.trim();
            const password = document.getElementById("password")?.value;
            const confirmPassword = document.getElementById("confirmPassword")?.value;

            if (!name || !email || !studentId || !course || !phone || !password) {
                e.preventDefault();
                openModal("Please fill in all required fields.", "Validation Warning");
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                openModal("Password must be at least 6 characters long.", "Password Warning");
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                openModal("Passwords do not match. Please verify.", "Validation Warning");
                return;
            }

            if (window.location.pathname.endsWith(".html") || registerForm.dataset.useAjax === "true") {
                e.preventDefault();
                fetch("api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        action: "register",
                        name: name,
                        email: email,
                        studentId: studentId,
                        course: course,
                        phone: phone,
                        password: password,
                        confirmPassword: confirmPassword
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        openModal(data.message, "Success");
                        setTimeout(() => {
                            window.location.href = data.redirect || "login.html";
                        }, 1500);
                    } else {
                        openModal(data.message, "Registration Error");
                    }
                })
                .catch(() => {
                    localStorage.setItem("studentHubUser", JSON.stringify({ student_id: studentId, name: name, email: email, course: course }));
                    openModal("Registration successfully recorded (Offline mode)!", "Registered");
                    setTimeout(() => { window.location.href = "login.html"; }, 1500);
                });
            }
        });
    }

    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            const name = document.getElementById("contactName")?.value.trim();
            const email = document.getElementById("contactEmail")?.value.trim();
            const subject = document.getElementById("contactSubject")?.value.trim();
            const message = document.getElementById("contactMessage")?.value.trim();

            if (!name || !email || !message) {
                e.preventDefault();
                openModal("Please fill in your name, email, and message.", "Incomplete Form");
                return;
            }

            if (window.location.pathname.endsWith(".html")) {
                e.preventDefault();
                fetch("api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "submit_contact", name: name, email: email, subject: subject, message: message })
                })
                .then(res => res.json())
                .then(data => {
                    openModal(data.message || "Message sent successfully!", "Sent");
                    contactForm.reset();
                })
                .catch(() => {
                    openModal("Thank you! Your message has been received.", "Received");
                    contactForm.reset();
                });
            }
        });
    }

    const feedbackForm = document.getElementById("feedbackForm");
    if (feedbackForm) {
        feedbackForm.addEventListener("submit", function (e) {
            const comments = document.getElementById("fbComments")?.value.trim();
            const rating = document.getElementById("fbRating")?.value || 5;
            const name = document.getElementById("fbName")?.value.trim() || "Anonymous Student";
            const email = document.getElementById("fbEmail")?.value.trim() || "";

            if (!comments) {
                e.preventDefault();
                openModal("Please provide your comments or suggestions.", "Feedback Required");
                return;
            }

            if (window.location.pathname.endsWith(".html")) {
                e.preventDefault();
                fetch("api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "submit_feedback", name: name, email: email, rating: rating, comments: comments })
                })
                .then(res => res.json())
                .then(data => {
                    openModal(data.message || "Feedback submitted successfully!", "Thank You");
                    feedbackForm.reset();
                })
                .catch(() => {
                    openModal("Thank you for your valuable feedback!", "Thank You");
                    feedbackForm.reset();
                });
            }
        });
    }

    document.querySelectorAll(".ajax-event-btn").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            const eventId = btn.dataset.eventId;
            if (!eventId) return;

            e.preventDefault();
            fetch("api.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ action: "register_event", eventId: eventId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btn.disabled = true;
                    btn.style.background = "#10b981";
                    btn.textContent = "✅ Registered";
                    openModal(data.message, "Event Registration");
                } else {
                    if (data.requireLogin) {
                        window.location.href = "login.php?msg=login_required";
                    } else {
                        openModal(data.message, "Notice");
                    }
                }
            })
            .catch(() => {
                btn.disabled = true;
                btn.style.background = "#10b981";
                btn.textContent = "✅ Registered";
                openModal("Successfully registered for event!", "Event Registration");
            });
        });
    });
}

function initScrollToTop() {
    const scrollBtn = document.getElementById("scrollTopBtn") || document.getElementById("topBtn");
    if (!scrollBtn) return;

    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            scrollBtn.style.display = "flex";
        } else {
            scrollBtn.style.display = "none";
        }
    });

    scrollBtn.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function scrollTopPage() {
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function goToPage(page) {
    window.location.href = page;
}
