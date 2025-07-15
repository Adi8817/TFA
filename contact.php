<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Basic input validation
function sanitize($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

try {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
      header('Location: contact-us.html?success=0');
      exit;
    }

    $mail = new PHPMailer(true);
    try {
      // SMTP settings
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'sharaths.austratech@gmail.com'; // <-- your Gmail address
      $mail->Password = 'bknd wuxa tivd ecld'; // <-- your Gmail app password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Recipients
      $mail->setFrom('Viv@cbdcorporation.com.au', 'CBD Website'); 
      $mail->addReplyTo($email, $name);  
      $mail->addAddress('sharathsrs990@gmail.com');  
      // Content
      $mail->isHTML(false);
      $mail->Subject = $subject;
      $mail->Body    = "Name: $name\nEmail: $email\n\nSubject: $subject\n\nMessage:\n$message";

      $mail->send();
      header('Location: contact-us.html?success=1');
      exit;
    } catch (Exception $e) {
      header('Location: contact-us.html?success=0');
      exit;
    }
  } else {
    header('Location: contact-us.html?success=0');
    exit;
  }
} catch (Throwable $e) {
  header('Location: contact-us.html?success=0');
  exit;
}
?>
