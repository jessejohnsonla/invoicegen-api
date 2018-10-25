<?php 
    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    class sendpdfController extends AbstractController
    {
        public function get($request)
        {
            $pdfsrc = urldecode($_GET['p']);
            $emails = urldecode($_GET['e']);
            return $this->sendPDF($pdfsrc, $emails);
        }
        protected function sendPDF($pdfsrc, $emails)
        {
            $email = new PHPMailer(true);
            try
            {
                $success = false;


                $email->SMTPDebug = 0;                                 // Enable verbose debug output
                $email->isSMTP();                                      // Set mailer to use SMTP
                $email->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                $email->SMTPAuth = true;                               // Enable SMTP authentication
                $email->Username = 'seussjohnstone@gmail.com';                 // SMTP username
                $email->Password = '!!!!google!!!!';                           // SMTP password
                $email->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $email->Port = 587;                                    // TCP port to connect to
            
                $email->setFrom('bron@son.com', 'Bronson');
                $email->Subject   = 'ATTENTION! Invoice from Bronson';
                $email->Body      = 'TESTING-1-2-3... Greetings... I have attached my latest invoice. Pay it quick, because I need money. Thank you.';
                $email->addAddress( $emails );
                $email->addAttachment( '/Library/WebServer/Documents/' . $pdfsrc , 'Invoice-from-Bronson.pdf' );
                $success = $email->send();

                return $success ? "invoice sent to: " . $emails . " | " . $pdfsrc : "fail: " . $email->ErrorInfo ;
            }
            catch(Exception $e){
                return 'error: ' . $email->ErrorInfo;
            }
        }
    }
?>