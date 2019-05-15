<?php

namespace controllers;


use PHPMailer\PHPMailer\PHPMailer;

class Mailer {

    public static function send(string $address, string $name = null, string $subject, string $body, string $textBody = null) {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isMail();

            //Recipients
            $mail->setFrom(SEND_FROM, 'IOF Volunteering platform');
            if (empty($name)) {
                $mail->addAddress( $address );
            } else {
                $mail->addAddress( $address, $name );
            }

            // Content
            $mail->isHTML();
            $mail->Subject = $subject;
            $mail->Body    = $body;
            if (!empty($textBody)) {
                $mail->AltBody = $textBody;
            }

            $mail->send();

            return true;

        } catch (\Exception $e) {

            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;

        }

    }
}