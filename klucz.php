<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title> aktywacja konta </title>
<meta name="description" content="" />
<meta name="keywords" content="key" />
<meta http-equiv="X-UA-Compatibile" content="IE=edge,chrome=1" />
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" href="css/fontello.css" type="text/css" />

</head>

<body>

Gratulacje aktywowales konto!!! <br/>

Mo¿esz ju¿ zalogowaæ siê na konto <br/>
<a href="index.php"> zaloguj </a>
	
<?php

session_start();



$email= ($_GET['email']);
echo "email:".$email;
echo "<br/>";

//$email = trim($_GET['email']);
$klucz = ($_GET['klucz']);
echo "klucz: ".$klucz;
echo "<br/>";




//$klucz = mysqli_real_escape_string($klucz);

//if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['klucz']) && !empty($_GET['klucz'])){
    // Verify data
  //  $email = mysqli_real_escape_string($_GET['email']); // Set email variable
   // $hash = mysql_escape_string($_GET['hash']); // Set hash variable
//}


require_once "connect.php";

//mysqli_report(MYSQLI_REPORT_STRICT);

$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

$rezultat = $polaczenie->query("SELECT id FROM users WHERE mail= '$email' AND emailcode = '$klucz' AND aktiv = NULL");



if($rezultat==false)
{
echo "b³ad: upsss cos posz³o nie tak...";
	}
	else
	{
	$polaczenie->query("UPDATE users SET aktiv='1' WHERE mail = '$email' AND aktiv = 0");
	echo "SUKCESSSS:) twoje konto zostalo aktywowane";
	//sleep(2);
	
	require 'PHPMailer/PHPMailerAutoload.php';
							
						
							$mail = new PHPMailer;

							//$mail->SMTPDebug = 3;                               // Enable verbose debug output

							$mail->isSMTP();                                      // Set mailer to use SMTP
							$mail->Host = 'mail.k.lapy.pl';  					// Specify main and backup SMTP servers
							$mail->SMTPAuth = true;                               // Enable SMTP authentication
							$mail->Username = 'noreplay@k.lapy.pl';                 // SMTP username
							$mail->Password = 'honey6';                           // SMTP password
							$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
							$mail->Port = 587;                                    // TCP port to connect to

							$mail->setFrom('noreplay@k.lapy.pl', 'Mailer');
							$mail->addAddress($email, $nick);     // Add a recipient // tu zmienialem
							$mail->addAddress($email);               // Name is optional
							$mail->addReplyTo('g8824@wp.pl', 'Information');
							$mail->addCC('cc@example.com');
							$mail->addBCC('bcc@example.com');

							$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
							$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
							$mail->isHTML(true);                                  // Set email format to HTML

							$mail->Subject = 'Aktywacja konta k.lapy';
							$mail->Body    = 'Gratuluje twoje konto zostalo aktywowane:) <b> !!!!! </b> <br/>
							zyczymy milego korzystania ze strony';
							$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
							
						//	('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

							if(!$mail->send()) 
							{
								echo 'Message could not be sent.';
								//echo 'Mailer Error: ' . $mail->ErrorInfo;
							} else 
								{
								echo 'Message has been sent';
								}
							
							
						}
	
$polaczenie->close();

	
?>	
	
	</form>
	</footer>

</body>

</html>