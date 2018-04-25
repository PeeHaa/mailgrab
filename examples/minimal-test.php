<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Examples;

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = 'localhost';
$mail->Port = 9025;
$mail->SMTPDebug = true;

$mail->setFrom('from@example.com', 'M. Sender');
$mail->addAddress('to.with.name@example.net', 'Joe User');
$mail->addAddress('to.without.name@example.com');
$mail->addReplyTo('reply.to@example.com', 'Reply Address');
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');

$mail->Subject = 'PHPMailer';
$mail->Body    = 'This is the HTML body.';
$mail->AltBody = 'This is the plain text body.';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
