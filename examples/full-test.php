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

$mail->addAttachment(__DIR__ . '/attachment/pdf-sample.pdf');
$mail->addAttachment(__DIR__ . '/attachment/pdf-sample-2.pdf', 'With custom name');

$mail->Subject = 'PHPMailer';
$mail->Body    = file_get_contents(__DIR__ . '/template/leemunroe.php');
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
