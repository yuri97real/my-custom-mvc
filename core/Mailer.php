<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    private $mail;
    private $company = "";
    private $failedToSend = false;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();

        $this->mail->Host       = MAILER_CONFIG["host"];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = MAILER_CONFIG["email"];
        $this->mail->Password   = MAILER_CONFIG["password"];
        $this->mail->SMTPSecure = MAILER_CONFIG["encryption"];
        $this->mail->Port       = MAILER_CONFIG["port"];
        
        $this->mail->CharSet = "UTF-8";
        $this->mail->Encoding = "base64";
        
        $this->mail->setLanguage("pt_br");
        
        $this->setCompanyName(APP_NAME);
    }

    public function sendEmail($email, $title, $content, $alt_content = "")
    {
        try {
            //Recipients

            $this->mail->setFrom(MAILER_CONFIG["email"], $this->company);
            $this->mail->addAddress($email, "Cliente");

            //Content

            $this->mail->isHTML(true);

            $this->mail->Subject = $title;
            $this->mail->Body    = $content;
            $this->mail->AltBody = $alt_content;

            //Finish

            $this->mail->send();

            return true;
            
        } catch (\Exception $e) {

            if($this->failedToSend) return [
                "errorInfo"=> $this->mail->ErrorInfo,
            ];

            $this->lowSecurity();
            return $this->sendEmail($email, $title, $content, $alt_content);

        }
    }

    public function setCompanyName($name)
    {
        $this->company = $name;
    }

    public function setAttachment($path, $filename)
    {
        $this->mail->addAttachment($path, $filename);
    }

    private function lowSecurity()
    {
        $this->failedToSend = true;

        $this->mail->SMTPOptions = [
            "ssl" => [
                "verify_peer"=> false,
                "verify_peer_name"=> false,
                "allow_self_signed"=> true,
            ]
        ];
    }

}