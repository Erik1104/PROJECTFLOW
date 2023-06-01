<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

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
      $mail->Host = 'smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = '2e2107df9c88f8';
      $mail->Password = 'd43d47c44c4e23';

      $mail->setFrom('cuentasprojectflow@gmail.com');
      $mail->addAddress('cuentasprojectflow@gmail.com', 'ProjectFlow.com' );
      $mail->Subject = 'Confirma tu cuenta';

      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';

      $contenido = "<html>";
      $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has creado tu cuenta en ProjectFlow, solo debes confirmala presionando el siguiente enlace</p>";
      $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token."'>Restablecer contraseña </a> </p>";
      $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
      $contenido .= "</html>";
      $mail->Body = $contenido;

      $mail->send();
    }

    public function enviarInstrucciones () {
 
      //crear el objeto de email
      $mail = new PHPMailer();     
      $mail->isSMTP();
      $mail->Host = 'smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = '2e2107df9c88f8';
      $mail->Password = 'd43d47c44c4e23';
    
      $mail->setFrom('cuentas@projectflow.com');
      $mail->addAddress('cuentas@projectflow.com', 'ProjectFlow.com');
      $mail->Subject = 'Restablece tu contraseña';
    
      //SET HTML
      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';
    
      $contenido = "<html>";
      $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has solicitado restablecer tu contraseña, sigue el siguiente enlace para hacerlo. </p>";
      $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/restablecer?token="  . $this->token . "'>Restablecer contraseña </a> </p>";
      $contenido .= "<p> Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
      $contenido .= "</html>";
      $mail->Body = $contenido;
    
      //enviar el email
      $mail->send();
    }
}


