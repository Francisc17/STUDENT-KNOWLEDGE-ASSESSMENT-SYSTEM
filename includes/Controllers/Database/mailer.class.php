<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

class mailer{

    private $mail;

    /**
     * funcao que envia o email
     * @param $to email para o qual vai enviar
     * @param $subject titulo do email
     * @param $body conteudo do email
     */
    public function mail($to, $subject, $body){
        try{       
            $this->mail = new PHPMailer(true);
            $this->mail->SMTPDebug = 0; 
            $this->mail->isSMTP();  
            $this->mail->Host = 'smtp.gmail.com';  
            $this->mail->SMTPAuth = true; 
            
            $this->mail->Username = 'franciscomesquita2000@gmail.com';  // colocar o endereço de email do gmail
            $this->mail->Password = 'gmail(pass){login();}';  // colocar a password  normal a nova que foi gerada
            
            $this->mail->SMTPSecure = 'tls'; 
            $this->mail->Port = 587;
            $this->mail->setFrom('franciscomesquita2000@gmail.com', 'Francisco');    // colocar primeiro o endereço de email e depois o seu nome completo
            $this->mail->addAddress($to);  
            $this->mail->Subject = $subject;
            $this->mail->Body=$body;
            $this->mail->isHTML(true);
            $this->mail->Send();
            echo "Message sent!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
