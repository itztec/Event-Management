<?php
/**
 * Contact Inquiry API Handler for cPanel PHP Server
 */
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$name = trim($input['name'] ?? '');
$email = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = trim($input['subject'] ?? 'New Website Inquiry');
$message = trim($input['message'] ?? '');

if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide name, valid email, and message.']);
    exit();
}

$to = 'info@elitemoments.com'; // Update recipient email address as needed
$emailSubject = "Contact Inquiry: " . $subject;

$emailBody = "You have received a new contact inquiry:\n\n";
$emailBody .= "Name: " . $name . "\n";
$emailBody .= "Email: " . $email . "\n";
$emailBody .= "Subject: " . $subject . "\n\n";
$emailBody .= "Message:\n" . $message . "\n";

$headers = "From: " . $name . " <" . $email . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = @mail($to, $emailSubject, $emailBody, $headers);

echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your inquiry has been sent successfully.'
]);
?>
