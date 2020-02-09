   <?php

@$name = $_POST['name'];
@$empresa = $_POST['empresa'];
@$email = $_POST['email'];
@$cel = $_POST['cel'];
@$msj = $_POST['comments'];
 
/*Lo primero es añadir al script la clase phpmailer desde la ubicación en que esté*/
require 'bin/class.phpmailer.php';
@$headers .= "MIME-Version: 1.0\n"; 
@$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
@$headers .= "Reply-To: " . $FromMail . "\n"; 
@$headers .= "X-Priority: 1\n"; 
@$headers .= "X-MSMail-Priority: High\n"; 
@$headers .= "X-Mailer: Widgets.com Server"; 
//Crear una instancia de PHPMailer
$mail = new PHPMailer();
//Definir que vamos a usar SMTP
$mail->IsSMTP();
//Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
// 0 = off (producción)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug  = 0;
//Ahora definimos gmail como servidor que aloja nuestro SMTP
//starkeservice01@gmail.com12ROJO34
$mail->Debugoutput = 'html';
//$mail->SMTPSecure = 'tls'; 
$mail->Host = 'mail.tecnosoftware.com.ar'; 
$mail->Port = '25'; 
//Tenemos que usar gmail autenticados, así que esto a TRUE
$mail->SMTPAuth = true;
//Definimos la cuenta que vamos a usar. Dirección completa de la misma
$mail->Username  = "web@tecnosoftware.com.ar";
//Introducimos nuestra contraseña de gmail
$mail->Password   = "Asdf4545";
//Definimos el remitente (dirección y, opcionalmente, nombre)
$mail->SetFrom('web@tecnosoftware.com.ar', 'Formulario Tecnosoftware');
//Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
$mail->AddAddress('contacte@tecnosoftware.com');
//$mail->AddAddress('juanignaciobracamonte@gmail.com');
//$mail->AddAddress("$to");

//$mail->AddBCC('juanis@brillantideaz.com', 'ClearDecisions Form');
//Definimos el tema del email
$mail -> IsHTML (true); 
$mail->Subject = 'Formulario Tecnosoftware';



$mail->Body="HOLA!";




$img="


<!DOCTYPE html>
<html>
<head>
<link href='https://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet'>

<b>Tecnosoftware Formulario</b>

<div style='font-family: 'Fjalla One', sans-serif;'>

<p>Nombre : ".$name."<p>
Empresa : ".$empresa."<p>
email : ".$email."<p>
Cel : ".$cel."<p>
Mensaje : ".$msj."<p>




<p>
<p>







</body>
</html>
";
$mail->IsHTML(true);
$mail->MsgHTML($img);

//Enviamos el correoasdasd
if(!$mail->Send()) {

  echo "Error: " . $mail->ErrorInfo;
} else {

 echo '             
                        <h1>
                            <p>
                                <b>
                                    <center><h2>¡Mensaje enviado correctamente nos pondremos en contacto a la brevedad!.</h2></center>
                                </b>
                            </p>
                        </h1>
                    </div>';



}



?>