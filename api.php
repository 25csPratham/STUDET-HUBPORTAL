<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

$pdo = getDBConnection();
$rawInput = file_get_contents('php://input');
$jsonInput = json_decode($rawInput, true);

$request = array_merge($_POST, is_array($jsonInput) ? $jsonInput : []);
$action = $request['action'] ?? ($_GET['action'] ?? '');

$response = ['success' => false, 'message' => 'Invalid action'];

switch ($action) {
    case 'login':
        $studentId = trim($request['studentId'] ?? '');
        $password = $request['password'] ?? '';

        if (empty($studentId) || empty($password)) {
            $response = ['success' => false, 'message' => 'Please enter both Student ID and Password.'];
            break;
        }

        if ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = :sid LIMIT 1");
            $stmt->execute([':sid' => $studentId]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['studenthub_user'] = [
                    'id'         => $user['id'],
                    'student_id' => $user['student_id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'course'     => $user['course'],
                    'phone'      => $user['phone'],
                    'city'       => $user['city'],
                    'dob'        => $user['dob'],
                    'role'       => $user['role']
                ];

                $redirect = ($user['role'] === 'admin') ? 'admin.php' : 'dashboard.php';
                $response = [
                    'success'  => true,
                    'message'  => 'Login successful! Welcome ' . $user['name'],
                    'role'     => $user['role'],
                    'redirect' => $redirect,
                    'user'     => $_SESSION['studenthub_user']
                ];
            } else {
                $response = ['success' => false, 'message' => 'Invalid Student ID or Password.'];
            }
        } else {
            if ($studentId === 'ADMIN01' && $password === 'Admin@123') {
                $_SESSION['studenthub_user'] = [
                    'student_id' => 'ADMIN01',
                    'name'       => 'System Administrator',
                    'email'      => 'admin@studenthub.com',
                    'role'       => 'admin',
                    'course'     => 'Administration'
                ];
                $response = ['success' => true, 'role' => 'admin', 'redirect' => 'admin.php', 'user' => $_SESSION['studenthub_user']];
            } elseif ($studentId === '25CS076' && $password === 'Student@123') {
                $_SESSION['studenthub_user'] = [
                    'student_id' => '25CS076',
                    'name'       => 'Pratham Patel',
                    'email'      => 'student@studenthub.com',
                    'role'       => 'student',
                    'course'     => 'B.Tech'
                ];
                $response = ['success' => true, 'role' => 'student', 'redirect' => 'dashboard.php', 'user' => $_SESSION['studenthub_user']];
            } else {
                $response = ['success' => false, 'message' => 'Invalid credentials.'];
            }
        }
        break;

    case 'register':
        $name            = trim($request['name'] ?? '');
        $email           = trim($request['email'] ?? '');
        $studentId       = trim($request['studentId'] ?? '');
        $course          = trim($request['course'] ?? '');
        $phone           = trim($request['phone'] ?? '');
        $password        = $request['password'] ?? '';
        $confirmPassword = $request['confirmPassword'] ?? '';

        if (empty($name) || empty($email) || empty($studentId) || empty($course) || empty($phone) || empty($password)) {
            $response = ['success' => false, 'message' => 'All fields are required.'];
            break;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = ['success' => false, 'message' => 'Please provide a valid email address.'];
            break;
        }

        if (strlen($password) < 6) {
            $response = ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
            break;
        }

        if ($password !== $confirmPassword) {
            $response = ['success' => false, 'message' => 'Passwords do not match.'];
            break;
        }

        if ($pdo) {
            $chk = $pdo->prepare("SELECT id FROM users WHERE student_id = :sid OR email = :email LIMIT 1");
            $chk->execute([':sid' => $studentId, ':email' => $email]);
            if ($chk->fetch()) {
                $response = ['success' => false, 'message' => 'Student ID or Email is already registered.'];
                break;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $ins = $pdo->prepare("INSERT INTO users (student_id, name, email, phone, course, password, role) VALUES (:sid, :name, :email, :phone, :course, :pwd, 'student')");
            $ins->execute([
                ':sid'    => $studentId,
                ':name'   => $name,
                ':email'  => $email,
                ':phone'  => $phone,
                ':course' => $course,
                ':pwd'    => $hashedPassword
            ]);

            $response = ['success' => true, 'message' => 'Registration successful! You can now log in.', 'redirect' => 'login.php'];
        } else {
            $response = ['success' => true, 'message' => 'Registration simulated successfully! You can now log in.', 'redirect' => 'login.php'];
        }
        break;

    case 'logout':
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        $response = ['success' => true, 'message' => 'Logged out successfully.', 'redirect' => 'login.php'];
        break;

    case 'get_session':
        if (isLoggedIn()) {
            $response = ['success' => true, 'loggedIn' => true, 'user' => getCurrentUser()];
        } else {
            $response = ['success' => true, 'loggedIn' => false];
        }
        break;

    case 'get_dashboard':
        if (!isLoggedIn()) {
            $response = ['success' => false, 'message' => 'Unauthorized'];
            break;
        }
        $user = getCurrentUser();
        $records = [];
        if ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM academic_records WHERE student_id = :sid");
            $stmt->execute([':sid' => $user['student_id']]);
            $records = $stmt->fetchAll();

            $evStmt = $pdo->prepare("SELECT COUNT(*) as count FROM event_registrations WHERE student_id = :sid");
            $evStmt->execute([':sid' => $user['student_id']]);
            $registeredEventsCount = $evStmt->fetchColumn();
        } else {
            $records = [
                ['subject' => 'Web Development', 'marks' => 85, 'status' => 'Pass'],
                ['subject' => 'Database Management', 'marks' => 90, 'status' => 'Pass'],
                ['subject' => 'Programming', 'marks' => 88, 'status' => 'Pass'],
                ['subject' => 'Computer Networks', 'marks' => 91, 'status' => 'Pass']
            ];
            $registeredEventsCount = 2;
        }

        $response = [
            'success'               => true,
            'user'                  => $user,
            'records'               => $records,
            'attendance'            => '92%',
            'cgpa'                  => '8.5',
            'coursesCount'          => count($records) ?: 6,
            'registeredEventsCount' => $registeredEventsCount
        ];
        break;

    case 'get_events':
        $events = [];
        $registeredIds = [];
        $sid = getCurrentUser()['student_id'] ?? '';

        if ($pdo) {
            $events = $pdo->query("SELECT * FROM events ORDER BY id ASC")->fetchAll();
            if ($sid) {
                $st = $pdo->prepare("SELECT event_id FROM event_registrations WHERE student_id = :sid");
                $st->execute([':sid' => $sid]);
                $registeredIds = $st->fetchAll(PDO::FETCH_COLUMN);
            }
        }
        $response = ['success' => true, 'events' => $events, 'registered_ids' => $registeredIds];
        break;

    case 'register_event':
        if (!isLoggedIn()) {
            $response = ['success' => false, 'message' => 'Please login first to register for events.', 'requireLogin' => true];
            break;
        }

        $eventId = (int)($request['eventId'] ?? 0);
        $sid = getCurrentUser()['student_id'];

        if ($pdo && $eventId > 0) {
            $check = $pdo->prepare("SELECT id FROM event_registrations WHERE event_id = :eid AND student_id = :sid");
            $check->execute([':eid' => $eventId, ':sid' => $sid]);
            if ($check->fetch()) {
                $response = ['success' => false, 'message' => 'You are already registered for this event.'];
                break;
            }

            $reg = $pdo->prepare("INSERT INTO event_registrations (event_id, student_id) VALUES (:eid, :sid)");
            $reg->execute([':eid' => $eventId, ':sid' => $sid]);
            $response = ['success' => true, 'message' => 'Successfully registered for event!'];
        } else {
            $response = ['success' => true, 'message' => 'Successfully registered for event (demo mode)!'];
        }
        break;

    case 'submit_contact':
        $name    = trim($request['name'] ?? '');
        $email   = trim($request['email'] ?? '');
        $subject = trim($request['subject'] ?? 'General Inquiry');
        $message = trim($request['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            $response = ['success' => false, 'message' => 'Please fill in all required fields.'];
            break;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = ['success' => false, 'message' => 'Please enter a valid email address.'];
            break;
        }

        if ($pdo) {
            $ins = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :sub, :msg)");
            $ins->execute([':name' => $name, ':email' => $email, ':sub' => $subject, ':msg' => $message]);
        }
        $response = ['success' => true, 'message' => 'Your message has been sent successfully! Our administrative team will reach out soon.'];
        break;

    case 'submit_feedback':
        $name     = trim($request['name'] ?? '');
        $email    = trim($request['email'] ?? '');
        $rating   = (int)($request['rating'] ?? 5);
        $comments = trim($request['feedback'] ?? ($request['comments'] ?? ''));

        if (empty($comments)) {
            $response = ['success' => false, 'message' => 'Please enter your feedback comments.'];
            break;
        }

        if ($rating < 1 || $rating > 5) $rating = 5;

        if ($pdo) {
            $ins = $pdo->prepare("INSERT INTO feedback (name, email, rating, comments) VALUES (:name, :email, :rating, :comm)");
            $ins->execute([
                ':name'   => $name ?: 'Anonymous Student',
                ':email'  => $email ?: 'feedback@studenthub.com',
                ':rating' => $rating,
                ':comm'   => $comments
            ]);
        }
        $response = ['success' => true, 'message' => 'Thank you for your valuable feedback! We appreciate your suggestions.'];
        break;

    case 'admin_stats':
        if (getUserRole() !== 'admin') {
            $response = ['success' => false, 'message' => 'Access denied: Administrator privileges required.'];
            break;
        }

        $userList = [];
        $msgList  = [];
        $fbList   = [];

        if ($pdo) {
            $userList = $pdo->query("SELECT id, student_id, name, email, course, role, status FROM users ORDER BY id ASC")->fetchAll();
            $msgList  = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC LIMIT 10")->fetchAll();
            $fbList   = $pdo->query("SELECT * FROM feedback ORDER BY id DESC LIMIT 10")->fetchAll();
            $totalStudents = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
            $totalEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
        } else {
            $totalStudents = 4;
            $totalEvents = 4;
        }

        $response = [
            'success'       => true,
            'totalStudents' => $totalStudents,
            'totalEvents'   => $totalEvents,
            'users'         => $userList,
            'messages'      => $msgList,
            'feedbacks'     => $fbList
        ];
        break;

    default:
        $response = ['success' => false, 'message' => 'Unknown request action: ' . htmlspecialchars($action)];
        break;
}

echo json_encode($response);
