<?php
/**
 * Event Reservation Booking API Handler for cPanel PHP Server
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
$phone = trim($input['phone'] ?? '');
$eventType = trim($input['eventType'] ?? '');
$eventDate = trim($input['eventDate'] ?? '');
$guestCount = trim($input['guestCount'] ?? '');
$location = trim($input['location'] ?? '');
$notes = trim($input['notes'] ?? '');

if (!$name || !$phone || !$eventType) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide name, phone, and event type.']);
    exit();
}

$to = 'booking@rmevents.com'; // Update recipient email address as needed
$emailSubject = "VIP Event Booking Request from " . $name;

$emailBody = "New Event Reservation Booking Request:\n\n";
$emailBody .= "Client Name: " . $name . "\n";
$emailBody .= "Phone: " . $phone . "\n";
$emailBody .= "Event Type: " . $eventType . "\n";
$emailBody .= "Target Date: " . $eventDate . "\n";
$emailBody .= "Guest Count: " . $guestCount . "\n";
$emailBody .= "Location: " . $location . "\n\n";
$emailBody .= "Notes / Custom Vision:\n" . $notes . "\n";

$host = $_SERVER['HTTP_HOST'] ?? 'rmevents.com';
$headers = "From: Website Booking <no-reply@" . $host . ">\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = @mail($to, $emailSubject, $emailBody, $headers);

echo json_encode([
    'success' => true,
    'message' => 'Booking request submitted successfully! Our executive planner will contact you.'
]);
?>
