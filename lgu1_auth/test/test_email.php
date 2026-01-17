<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "Testing email configuration...\n";
echo "MAIL_USERNAME: " . (defined('MAIL_USERNAME') ? 'Defined' : 'Not defined') . "\n";
echo "MAIL_PASSWORD: " . (defined('MAIL_PASSWORD') ? 'Defined' : 'Not defined') . "\n";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Timeout = 10;
    
    $mail->setFrom(MAIL_USERNAME, 'LGU1 Test');
    $mail->addAddress(MAIL_USERNAME, 'Test User');
    $mail->isHTML(true);
    $mail->Subject = 'LGU1 Email Test';
    $mail->Body = '<h2>Email Test Successful</h2><p>This is a test email from LGU1 system.</p>';
    
    $mail->send();
    echo "Email test successful!\n";
} catch (Exception $e) {
    echo "Email test failed: " . $e->getMessage() . "\n";
}
?>