<?php
	session_start();
	
	if(isset($_POST['email']))
	{
	$wszystko_OK=true;
	//sprawdzenie nika
	$imie=$_POST['imie'];
	//sprawdzenie dlugosci nika
	if((strlen($imie)<3) || (strlen($imie)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_imie']="imie musi posiadać od 3 do 20 znaków!";
	
	}
	
	if(ctype_alnum($imie)==false)
	{
		$_wszystko_OK=false;
		$_SESSION['e_imie']="nick może skladać się tylko z liter i cyfr bez polskich znaków";
	}
	
	$nick=$_POST['nick'];
	//sprawdzenie dlugosci nika
	if((strlen($nick)<3) || (strlen($nick)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_nick']="nick pole posiadać od 3 do 20 znaków!";
	
	}
	
	if(ctype_alnum($nick)==false)
	{
		$_wszystko_OK=false;
		$_SESSION['e_nick']="pole może skladać się tylko z liter i cyfr bez polskich znaków";
	}
	
	// sprawdz poprawność e-mail
	$email = $_POST['email'];
	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		$wszystko_OK=false;
		$_SESSION['e_email']="Podaj poprawny format mail";
	}
	
	
	$gender=$_POST['gender']; // sprawdzenia pola gender
	
	if(!isset($gender) || $gender==="")
	{	
	$wszystko_OK=false;
	$_SESSION['e_gender']="wybierz gender";
	}
	
	// sprawdz poprawność hasla
	$haslo1 = $_POST['haslo1'];
	$haslo2 = $_POST['haslo2'];
	
	
	If((strlen($haslo1)<=2) || (strlen($haslo1>20)))
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="haslo moze miec od 2 do 20 znaków";
	}
	if($haslo1!=$haslo2)
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Podane hasła nie są identyczne";
	}
	
	$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
	//echo $haslo_hash; exit(); - do sprawdzenia hashu hasła
	/*
	if(!isset($_POST['regulamin']))
	{	
	$wszystko_OK=false;
	$_SESSION['e_regulamin']="Zaakceptuj regulamin";
	}
	
	//recatpcha
	$sekret = "6LfGdk0UAAAAAOcvkRVH_oRsWpQaL-CVdjGkuBYM"; // podmieniony
	
	//$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
	
	
	//$odpowiedz = json_decode($sprawdz);
	
	if($odpowiedz -> success==false)
	{
	$wszystko_OK=false;
	$_SESSION['e_bot']="potwierdź ze nie jesteś z metalu (lub nie jesteś botem) :)";
	}
	*/
	require_once "connect.php";  // zmienic przy publikacji domeny
	
	mysqli_report(MYSQLI_REPORT_STRICT); //zamiast warningow to wyjatki rzucamy w ostrzezeniach
	
	try
	{
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		if($polaczenie->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else 
				{
					//czy email juz istnieje
					$rezultat = $polaczenie->query("SELECT id FROM users WHERE email='$email'");
					
					if(!$rezultat) throw new Exception($polaczenie->error);
					
					$ile_takich_maili = $rezultat->num_rows;
					
					if($ile_takich_maili>0)
					{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu mail :/";
					}
					
										//czy nick juz istnieje
					$rezultat = $polaczenie->query("SELECT id FROM users WHERE nick='$nick'"); //???
					
					if(!$rezultat) throw new Exception($polaczenie->error);
					
					$ile_takich_nickow = $rezultat->num_rows;
					
					if($ile_takich_nickow>0)
					{
					$wszystko_OK=false;
					$_SESSION['e_nick']="Istnieje już osoba o takim nazwisku :/, wybierz inny";
					}
					
					$klucz=md5(mt_rand()); // generuje klucz random lub md5($_POST['nick'] + microtime())
					
					$data = date('Y-m-d');
					$mdata= date('Y-m-d H:i:s');
					
					if($wszystko_OK==true)
					{
							//wszystkie testy zakończone, dodajemy gracza do bazy
					
						if($polaczenie->query("INSERT INTO users VALUES (NULL, '$imie', '$nick', '$email', '$gender', '$haslo_hash', '$data', '$mdata', '$klucz', NULL)"))
						{
							$_SESSION['udanarejestracja']=true;
							header('Location: witamy.php');
							require 'PHPMailer/PHPMailerAutoload.php';
							
						
							$mail = new PHPMailer;

							//$mail->SMTPDebug = 3;                               // Enable verbose debug output

							$mail->isSMTP();                                      // Set mailer to use SMTP
							$mail->Host = '';  					// Specify main and backup SMTP servers
							$mail->SMTPAuth = true;                               // Enable SMTP authentication
							$mail->Username = '';                 // SMTP username
							$mail->Password = '';                           // SMTP password
							$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
							$mail->Port = 587;                                    // TCP port to connect to

							$mail->setFrom('noreplay@k.lapy.pl', 'Mailer');
							$mail->addAddress($email, $nick);     // Add a recipient // tu zmienialem
							$mail->addAddress();               // Name is optional
							$mail->addReplyTo('g8824@wp.pl', 'Information');
							$mail->addCC('cc@example.com');
							$mail->addBCC('bcc@example.com');

							$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
							$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
							$mail->isHTML(true);                                  // Set email format to HTML

							$mail->Subject = 'Rejestracja k.lapy';
							$mail->Body    = 'Gratuluje zostales zarejestrowany:) <b> !!!!! </b> <br/>
							twoj klucz to:'.$klucz.'<br/>
							Prosze wprowadz klucz na stronie: 
							http://www.k.lapy.pl/klucz.php?email='.$email.'&klucz='.$klucz;
							$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
							
						//	('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

							if(!$mail->send()) 
							{
								echo 'Message could not be sent.';
								echo 'Mailer Error: ' . $mail->ErrorInfo;
							} else 
								{
								echo 'Message has been sent';
								}
							
							
						}
						else
						{
							throw new Exception($polaczenie->error);
						}
					}
					
					
					$polaczenie->close();
				}
	}
	catch(Exception $e)
	{
		echo '<span style="color; red;">Blad serwera (serwer error), prosimy o rejestracje w innym czasie" </span>';
	}
	
	//echo '<br /> Informacja developerska;'.$e; // pozniej do wykreślenia
	
	
	}
?>

<!DOCTYPE HTML>

<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http=equiv="X-UA-Compatible" content=IE=edge, chrome=1"/>
	<title> Rejestracja </title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<link rel="stylesheet" href="style.css" type="text/css" />
	
	
	
</head>


<body>
<h1>Rejestracja </h1>

	<form method="post">
	
	first_name: <br/> <input type="text" name="imie" /> <br />
	
	<?php
	
	if(isset($_SESSION['e_imie']))
	{
	echo'<div class="error">'.$_SESSION['e_imie'].'</div>';
	unset($_SESSION['e_imie']);
	}
	
	?>
	
	last_name: <br/> <input type="text" name="nick" /> <br />
	
	<?php
	
	if(isset($_SESSION['e_nick']))
	{
	echo'<div class="error">'.$_SESSION['e_nick'].'</div>';
	unset($_SESSION['e_nick']);
	}
	
	?>
	
	E-mail: <br/> <input type="text" name="email" /> <br/>
	
	<?php
	
	if(isset($_SESSION['e_email']))
	{
	echo'<div class="error">'.$_SESSION['e_email'].'</div>';
	unset($_SESSION['e_email']);
	}
	
	?>
	
	Gender: <select name="gender">
	<option value="">Select...</option>
	<option value="B">Boy</option>
	<option value="G">Girl</option>
			</select>
	<br/>
	
	<?php
	
	if(isset($_SESSION['e_gender']))
	{
	echo'<div class="error">'.$_SESSION['e_gender'].'</div>';
	unset($_SESSION['e_gender']);
	}
	
	?>
	
				
	Password: <br/> <input type="password" name="haslo1" /> <br/>
	
	<?php
	
	if(isset($_SESSION['e_haslo']))
	{
	echo'<div class="error">'.$_SESSION['e_haslo'].'</div>';
	unset($_SESSION['e_haslo']);
	}
	
	?>
	
	Powtorz Password: <br/> <input type="password" name="haslo2" /> <br/>
				

			<!---	
	<div class="g-recaptcha" data-sitekey="6Lea_BkUAAAAACbNRwhbpqjCmKQxzu2cxCZ_63cg"></div>
	
	<?php 
	/*
	if(isset($_SESSION['e_bot']))
	{
	echo'<div class="error">'.$_SESSION['e_bot'].'</div>';
	unset($_SESSION['e_bot']);
	}
	*/
	?>
	--->
	<br/>
	<input type="submit" value="Zarejestruj sie" />
	
	</form>
	
	
<br />

</body>
</html>