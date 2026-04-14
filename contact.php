<?php
// contact.php — Contact form handler
// Saves message to MySQL and sends email notification

// ── Database config ── (update these values)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');

// ── Your email ──
define('YOUR_EMAIL', 'vishalsarena44@gmail.com');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Sanitize inputs
function clean($val) {
    return htmlspecialchars(trim(strip_tags($val)), ENT_QUOTES, 'UTF-8');
}

$name    = clean($_POST['name']    ?? '');
$email   = clean($_POST['email']   ?? '');
$subject = clean($_POST['subject'] ?? '');
$message = clean($_POST['message'] ?? '');

// Basic validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// ── Save to database ──
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare(
        "INSERT INTO contact_messages (name, email, subject, message, created_at)
         VALUES (:name, :email, :subject, :message, NOW())"
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':subject' => $subject,
        ':message' => $message,
    ]);

} catch (PDOException $e) {
    // Log error (don't expose details to client)
    error_log("DB Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
    exit;
}

// ── Send email notification ──
$to      = YOUR_EMAIL;
$headers = "From: Portfolio Contact <noreply@yourdomain.com>\r\n"
         . "Reply-To: $email\r\n"
         . "MIME-Version: 1.0\r\n"
         . "Content-Type: text/html; charset=UTF-8\r\n";

$body = "
<!DOCTYPE html>
<html>
<head><style>
  body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;}
  .card{background:#fff;border-radius:8px;padding:30px;max-width:560px;margin:0 auto;}
  h2{color:#00f5d4;margin-top:0;}
  .field{margin:12px 0;}
  .label{font-weight:bold;color:#555;font-size:13px;}
  .value{color:#222;margin-top:4px;}
  .msg{background:#f8f8f8;padding:12px;border-radius:4px;border-left:3px solid #00f5d4;white-space:pre-wrap;}
</style></head>
<body>
<div class='card'>
  <h2>&#x1F4E9; New Portfolio Message</h2>
  <div class='field'><div class='label'>Name</div><div class='value'>$name</div></div>
  <div class='field'><div class='label'>Email</div><div class='value'>$email</div></div>
  <div class='field'><div class='label'>Subject</div><div class='value'>$subject</div></div>
  <div class='field'><div class='label'>Message</div><div class='msg'>$message</div></div>
  <p style='color:#999;font-size:12px;margin-top:20px;'>Received: " . date('d M Y, H:i') . " IST</p>
</div>
</body>
</html>
";

mail($to, "Portfolio: $subject", $body, $headers);

// ── Success response ──
echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
exit;
