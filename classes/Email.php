<?php

namespace Classes; // Definir un espacio de nombres para la clase

use PHPMailer\PHPMailer\PHPMailer; // Importar la clase PHPMailer

class Email {

    // Atributos
    public $email; // Atributo email
    public $nombre; // Atributo nombre
    public $token; // Atributo token

    // Constructor
    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    //-------------------------enviarConfirmacion--------------------------------

    // Método para enviar un correo de confirmación
    public function enviarConfirmacion() {
        $mail = new PHPMailer(); // Crear un nuevo objeto PHPMailer
        $mail->isSMTP(); // Utilizar SMTP para enviar el correo electrónico - Simple Mail Transfer Protocol

        // Este código lo copio iniciando sesión en la url de mailtrap con mi usuario y contraseña
        $mail->Host = $_ENV['EMAIL_HOST']; // Servidor de correo
        $mail->SMTPAuth = true; // Autenticación SMTP
        $mail->Port = $_ENV['EMAIL_PORT']; // Puerto de correo
        $mail->Username = $_ENV['EMAIL_USERNAME']; // Nombre de usuario
        $mail->Password = $_ENV['EMAIL_PASSWORD']; // Contraseña

        $mail->setFrom('cuentas@appsalon.com'); // Correo de la cuenta de la aplicación
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com'); // Correo del usuario
        $mail->Subject = 'Confirma tu cuenta'; // Asunto del correo

        $mail->isHTML(true); // Habilitar HTML en el correo
        $mail->CharSet = 'UTF-8'; // Codificación del correo

        //----------------------------Contenido del Correo-----------------------------
        $contenido = "<html>"; // Inicio del contenido del correo
        $contenido .=
            "<p>
                <strong> Hola $this->email </strong>
                Has creado tu cuenta en AppSalon,
            </p>"; // Saludo al usuario
        $contenido .=
            "<p>
                Presiona aquí para confirmar tu cuenta:
                <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>
            </p>"; // Enlace para confirmar la cuenta - vista /confirmar-cuenta
        $contenido .= "
            <p>
                Si tu no solicitaste esta cuenta, puedes ignorar el mensaje
            </p>";
        $contenido .= "</html>"; // Cierre del contenido del correo
        //-------------------------------------------------------------

        $mail->Body = $contenido; // Cuerpo del correo

        $mail->send(); // Enviar el correo

    }
    // ----------------------------------------------------------------------------

    // -------------------------enviarInstrucciones--------------------------------

    // Método para enviar un correo con instrucciones
    public function enviarInstrucciones() {
        $mail = new PHPMailer(); // Crear un nuevo objeto PHPMailer
        $mail->isSMTP(); // Utilizar SMTP para enviar el correo electrónico - Simple Mail Transfer Protocol

        // Este código lo copio iniciando sesión en la url de mailtrap con mi usuario y contraseña
        $mail->Host = $_ENV['EMAIL_HOST']; // Servidor de correo
        $mail->SMTPAuth = true; // Autenticación SMTP
        $mail->Port = $_ENV['EMAIL_PORT']; // Puerto de correo
        $mail->Username = $_ENV['EMAIL_USERNAME']; // Nombre de usuario de mailtrap
        $mail->Password = $_ENV['EMAIL_PASSWORD']; // Contraseña de mailtrap

        $mail->setFrom('cuentas@appsalon.com'); // Correo de la cuenta de la aplicación
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com'); // Correo del usuario
        $mail->Subject = 'Reestablece tu password'; // Asunto del correo

        $mail->isHTML(true); // Habilitar HTML en el correo
        $mail->CharSet = 'UTF-8'; // Codificación del correo

        //----------------------------Contenido del Correo-----------------------------
        $contenido = "<html>"; // Inicio del contenido del correo
        $contenido .=
            "<p>
                <strong> Hola $this->nombre </strong>
                Has solicitado reestablecer tu password en AppSalon,
            </p>"; // mensaje al usuario
        $contenido .=
            "<p>
                Presiona aquí:
                <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Reestablecer Password</a>
            </p>"; // Enlace para reestablecer el password - vista /recuperar
        $contenido .= "
            <p>
                Si tu no solicitaste este cambio, puedes ignorar el mensaje
            </p>";
        $contenido .= "</html>"; // Cierre del contenido del correo
        //-------------------------------------------------------------

        $mail->Body = $contenido; // Cuerpo del correo

        $mail->send(); // Enviar el correo
    }
}
