<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("../mail/vendor/autoload.php"); 
function test_gmail_smtp_basic($mail_receveur, $sujet, $nom) {

    date_default_timezone_set('Europe/Paris');
    $mail = new PHPMailer(); 
    $body = file_get_contents('../mail/content.html');
    $mail->IsSMTP();                            // telling the class to use SMTP
    $mail->Host       = "smtp.gmail.com";      // SMTP server
    $mail->SMTPDebug  = 0;                      // enables SMTP debug information (for testing)
                                                    // 0 default no debugging messages
                                                    // 1 = errors and messages
                                                    // 2 = messages only
    $mail->SMTPAuth   = true;                   // enable SMTP authentication
    $mail->SMTPSecure = 'tls';                  // Supported
    $mail->Host       = "smtp.gmail.com";       // sets the SMTP server
    $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username   = "arthurysiite@gmail.com";         // SMTP account username (how you login at gmail)
    $mail->Password   = "arthur3103";      // SMTP account password (how you login at gmail)
 
    $mail->setFrom('Hypertube@contact.com', 'Hypertube');
 
    $mail->Subject = $sujet;
 
    $mail->msgHTML($body);
 
    $address = $mail_receveur;
    $mail->addAddress($address, $nom);
 
    $mail->Send();
}
?>