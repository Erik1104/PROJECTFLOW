<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable('../includes/.env');
$dotenv->safeLoad();

class Email {

    public $nombre;
    public $email;
    public $token;
    
    public function __construct($nombre, $email, $token) {
    
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }
    
    public function enviarConfirmacion () {
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $_ENV['MAIL_HOST'];
      $mail->SMTPAuth = true;
      $mail->Username = $_ENV['MAIL_USER'];
      $mail->Password = $_ENV['MAIL_PASSWORD'];
      $mail->SMTPSecure = 'tls';
      $mail->Port = $_ENV['MAIL_PORT'];

      $mail->setFrom('cuentasprojectflow@gmail.com');
      $mail->addAddress('cuentasprojectflow@gmail.com', 'ProjectFlow.com' );
      $mail->Subject = 'Confirma tu cuenta';

      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';

      $contenido = "<html>";
      $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has creado tu cuenta en ProjectFlow, solo debes confirmala presionando el siguiente enlace</p>";
      $contenido .= "<p>Presiona aquí: <a href='". $_ENV['SERVER_HOST'] ."/confirmar?token=" . $this->token."'>Confirmar cuenta </a> </p>";
      $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
      $contenido .= "</html>";
      $mail->Body = $contenido;

      $mail->send();
    }

    public function enviarInstrucciones () {
 
      //crear el objeto de email
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $_ENV['MAIL_HOST'];
      $mail->SMTPAuth = true;
      $mail->Username = $_ENV['MAIL_USER'];
      $mail->Password = $_ENV['MAIL_PASSWORD'];
      $mail->SMTPSecure = 'tls';
      $mail->Port = $_ENV['MAIL_PORT'];
    
      $mail->setFrom('cuentas@projectflow.com');
      $mail->addAddress('cuentas@projectflow.com', 'ProjectFlow.com');
      $mail->Subject = 'Restablece tu contraseña';
    
      //SET HTML
      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';
    
      $contenido = "<html>";
      $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has solicitado restablecer tu contraseña, sigue el siguiente enlace para hacerlo. </p>";
      $contenido .= "<p>Presiona aquí: <a'". $_ENV['SERVER_HOST'] ."/restablecer?token=" . $this->token."'>>Restablecer contraseña </a> </p>";
      $contenido .= "<p> Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
      $contenido .= "</html>";
      $mail->Body = $contenido;
    
      //enviar el email
      $mail->send();
    }
}


