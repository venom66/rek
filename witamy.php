 <?php

session_start();

if(!isset($_SESSION['udanarejestracja']))
{
	header('Location: index.php');
	exit();
}
else							
	{
		unset ($_SESSION['udanarejestracja']);
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>witamy</title>
</head>

<body>
	
	Dziękujemy za rejestrację w serwisie!<br />
	Proszę sprawdzic mail w celu aktywacji konta <br /> <br />
	Bez aktywacji konta nie będzie możliwości zalogowania się do serwisu.
	
	
	
	<a href="index.php">Zaloguj się na swoje konto!</a>
	<br /><br />

</body>
</html>